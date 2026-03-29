<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

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

        ContactMessage::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Ziņojums veiksmīgi nosūtīts.');
    }
}