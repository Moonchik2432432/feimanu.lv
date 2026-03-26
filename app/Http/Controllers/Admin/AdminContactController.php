<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderByDesc('created_at')->paginate(10);

        return view('admin.contacts.index', compact('messages'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        return view('admin.contacts.show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:3000',
        ]);

        $message = ContactMessage::findOrFail($id);

        $message->update([
            'reply' => $request->reply,
            'replied_at' => now(),
            'replied_by' => auth()->id(),
        ]);

        return back()->with('success', 'Atbilde saglabāta.');
    }
}