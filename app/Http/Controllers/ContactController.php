<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'message'    => 'required|string',
        ]);

        Mail::raw(
            "Nama: {$validated['first_name']} {$validated['last_name']}\n" .
            "Email: {$validated['email']}\n\n" .
            "Pesan:\n{$validated['message']}",
            function ($mail) use ($validated) {
                $mail->to('zenifenagusti70@gmail.com')
                     ->subject('Pesan Baru dari ' . $validated['first_name'] . ' ' . $validated['last_name'])
                     ->replyTo($validated['email']);
            }
        );

        return redirect(url()->previous() . '#contact')->with('success', 'Pesan berhasil dikirim!');
    }
}

