<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactQuestionMail;

class ContactController extends Controller
{
    // Attēlo lietotāja kontaktu ziņojumus ar meklēšanu, filtrēšanu un statusiem
    public function index(Request $request)
    {
        $messages = collect();

        if (auth()->check()) {
            $q = $request->q;
            $status = $request->status;
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;

            ContactMessage::updateOverdue();

            if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
                return back()->with('error', 'Datums "No" nedrīkst būt lielāks par datumu "Līdz".');
            }

            $messages = ContactMessage::where('user_id', auth()->id())
                ->whereNull('user_deleted_at')
                ->whereNull('user_archived_at');

            if ($q) {
                $messages->where(function ($query) use ($q) {
                    $query->where('subject', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%")
                        ->orWhere('reply', 'like', "%{$q}%");
                });
            }

            if ($status) {
                $messages->where('status', $status);
            }

            if ($dateFrom) {
                $messages->whereDate('created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $messages->whereDate('created_at', '<=', $dateTo);
            }

            $messages = $messages->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString();
        }

        $statuses = ContactMessage::statuses();

        return view('contacts.index', compact('messages', 'statuses'));
    }

    // Attēlo lietotāja arhivētos kontaktu ziņojumus
    public function archiveList(Request $request)
    {
        $messages = collect();

        if (auth()->check()) {
            $q = $request->q;
            $status = $request->status;
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;

            ContactMessage::updateOverdue();

            if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
                return back()->with('error', 'Datums "No" nedrīkst būt lielāks par datumu "Līdz".');
            }

            $messages = ContactMessage::where('user_id', auth()->id())
                ->whereNull('user_deleted_at')
                ->whereNotNull('user_archived_at');

            if ($q) {
                $messages->where(function ($query) use ($q) {
                    $query->where('subject', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%")
                        ->orWhere('reply', 'like', "%{$q}%");
                });
            }

            if ($status) {
                $messages->where('status', $status);
            }

            if ($dateFrom) {
                $messages->whereDate('created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $messages->whereDate('created_at', '<=', $dateTo);
            }

            $messages = $messages->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString();
        }

        $statuses = ContactMessage::statuses();

        return view('contacts.archive', compact('messages', 'statuses'));
    }

    // Saglabā lietotāja nosūtīto kontaktu ziņojumu un nosūta paziņojumu administratoram
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:50',
                'subject' => 'required|string|max:100',
                'message' => 'required|string|max:600',
            ],
            [
                'name.required' => 'Lūdzu, ievadiet vārdu.',
                'name.max' => 'Vārds nedrīkst būt garāks par 100 simboliem.',

                'email.required' => 'Lūdzu, ievadiet e-pastu.',
                'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
                'email.max' => 'E-pasts nedrīkst būt garāks par 50 simboliem.',

                'subject.required' => 'Lūdzu, ievadiet tēmu.',
                'subject.max' => 'Tēma nedrīkst būt garāka par 100 simboliem.',

                'message.required' => 'Lūdzu, ievadiet ziņojumu.',
                'message.max' => 'Ziņojums nedrīkst būt garāks par 600 simboliem.',
            ]
        );

        $lastMessage = ContactMessage::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->first();

        if ($lastMessage) {
            $secondsPassed = $lastMessage->created_at->diffInSeconds(now());

            if ($secondsPassed < 30) {
                $wait = max(0, (int) ceil(30 - $secondsPassed));

                return back()
                    ->withInput()
                    ->with('error', "Tu vari nosūtīt nākamo ziņojumu pēc {$wait} sek.");
            }
        }

        $contactMessage = ContactMessage::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'new',
        ]);

        $adminEmail = env('ADMIN_CONTACT_EMAIL');

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new ContactQuestionMail($contactMessage));
        }

        return back()->with('success', 'Ziņojums veiksmīgi nosūtīts.');
    }

    // Pārvieto lietotāja ziņojumu uz arhīvu
    public function archive($id)
    {
        $message = ContactMessage::where('user_id', auth()->id())
            ->whereNull('user_deleted_at')
            ->findOrFail($id);

        $message->update([
            'user_archived_at' => now(),
        ]);

        return back()->with('success', 'Ziņojums pārvietots uz arhīvu.');
    }

    // Atjauno lietotāja ziņojumu no arhīva
    public function unarchive($id)
    {
        $message = ContactMessage::where('user_id', auth()->id())
            ->whereNull('user_deleted_at')
            ->findOrFail($id);

        $message->update([
            'user_archived_at' => null,
        ]);

        return back()->with('success', 'Ziņojums izņemts no arhīva.');
    }

    // Atzīmē lietotāja ziņojumu kā dzēstu
    public function delete($id)
    {
        $message = ContactMessage::where('user_id', auth()->id())
            ->whereNull('user_deleted_at')
            ->findOrFail($id);

        $message->update([
            'user_deleted_at' => now(),
        ]);

        return back()->with('success', 'Ziņojums dzēsts.');
    }
}