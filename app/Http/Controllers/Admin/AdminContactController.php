<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $status = $request->status;
        $archived = $request->archived;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
            return back()->with('error', 'Datums "No" nedrīkst būt lielāks par datumu "Līdz".');
        }

        $messages = ContactMessage::whereNull('admin_deleted_at');

        if ($q) {
            $messages->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('subject', 'like', "%{$q}%")
                      ->orWhere('message', 'like', "%{$q}%")
                      ->orWhere('reply', 'like', "%{$q}%");
            });
        }

        if ($status) {
            $messages->where('status', $status);
        }

        if ($archived === '1') {
            $messages->whereNotNull('admin_archived_at');
        } elseif ($archived === '0') {
            $messages->whereNull('admin_archived_at');
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

        $statuses = [
            'new' => 'Jauns',
            'in_progress' => 'Apstrādē',
            'answered' => 'Atbildēts',
            'closed' => 'Aizvērts',
        ];

        return view('admin.contacts.index', compact(
            'messages',
            'statuses',
            'q',
            'status',
            'archived',
            'dateFrom',
            'dateTo'
        ));
    }

    public function show($id)
    {
        $message = ContactMessage::whereNull('admin_deleted_at')->findOrFail($id);

        $statuses = [
            'new' => 'Jauns',
            'in_progress' => 'Apstrādē',
            'answered' => 'Atbildēts',
            'closed' => 'Aizvērts',
        ];

        return view('admin.contacts.show', compact('message', 'statuses'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate(
            [
                'reply' => 'required|string|max:3000',
            ],
            [
                'reply.required' => 'Lūdzu, ievadiet atbildi.',
                'reply.string' => 'Atbildei jābūt tekstam.',
                'reply.max' => 'Atbilde nedrīkst būt garāka par 3000 simboliem.',
            ]
        );

        $message = ContactMessage::whereNull('admin_deleted_at')->findOrFail($id);

        $message->update([
            'reply' => $request->reply,
            'replied_at' => now(),
            'replied_by' => auth()->id(),
            'status' => 'answered',
        ]);

        return back()->with('success', 'Atbilde saglabāta.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(
            [
                'status' => 'required|in:new,in_progress,answered,closed',
            ],
            [
                'status.required' => 'Lūdzu, izvēlieties statusu.',
                'status.in' => 'Nederīgs statuss.',
            ]
        );

        $message = ContactMessage::whereNull('admin_deleted_at')->findOrFail($id);

        $message->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Statuss veiksmīgi atjaunināts.');
    }

    public function archive($id)
    {
        $message = ContactMessage::whereNull('admin_deleted_at')->findOrFail($id);

        $message->update([
            'admin_archived_at' => now(),
        ]);

        return back()->with('success', 'Ziņojums pārvietots uz arhīvu.');
    }

    public function unarchive($id)
    {
        $message = ContactMessage::whereNull('admin_deleted_at')->findOrFail($id);

        $message->update([
            'admin_archived_at' => null,
        ]);

        return back()->with('success', 'Ziņojums izņemts no arhīva.');
    }

    public function delete($id)
    {
        $message = ContactMessage::whereNull('admin_deleted_at')->findOrFail($id);

        $message->update([
            'admin_deleted_at' => now(),
        ]);

        return back()->with('success', 'Ziņojums dzēsts.');
    }
}
