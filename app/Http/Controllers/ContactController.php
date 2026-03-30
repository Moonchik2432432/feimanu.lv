<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactQuestionMail;

class ContactController extends Controller
{
    public function index()
    {
        $messages = [];

        if (auth()->check()) {
            $messages = ContactMessage::where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->get();
        }

        return view('contacts.index', compact('messages'));
    }

    public function store(Request $request)
    {
        dd(config('mail.admin_contact_email'));
        $request->validate(
            [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:150',
                'subject' => 'required|string|max:150',
                'message' => 'required|string|max:300',
            ],
            [
                'name.required' => 'Lūdzu, ievadiet vārdu.',
                'name.max' => 'Vārds nedrīkst būt garāks par 100 simboliem.',

                'email.required' => 'Lūdzu, ievadiet e-pastu.',
                'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
                'email.max' => 'E-pasts nedrīkst būt garāks par 150 simboliem.',

                'subject.required' => 'Lūdzu, ievadiet tēmu.',
                'subject.max' => 'Tēma nedrīkst būt garāka par 150 simboliem.',

                'message.required' => 'Lūdzu, ievadiet ziņojumu.',
                'message.max' => 'Ziņojums nedrīkst būt garāks par 300 simboliem.',
            ]
        );

        $contactMessage = ContactMessage::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // 🔥 отправка письма
        $adminEmail = env('ADMIN_CONTACT_EMAIL');

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new ContactQuestionMail($contactMessage));
        }

        return back()->with('success', 'Ziņojums veiksmīgi nosūtīts.');
    }
}
