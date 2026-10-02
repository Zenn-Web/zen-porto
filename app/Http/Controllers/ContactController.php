<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function store(ContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $redirectTo = url()->previous().'#contact';

        try {
            Mail::to(config('mail.contact_recipient'))->queue(new ContactMessage(
                $validated['first_name'],
                $validated['last_name'],
                $validated['email'],
                $validated['message'],
            ));
        } catch (Throwable $exception) {
            // Only the exception class is logged: queue driver errors can embed the payload.
            Log::error('Contact message could not be queued.', [
                'exception' => $exception::class,
            ]);

            return redirect($redirectTo)
                ->withInput()
                ->withErrors(['contact' => 'Pesan gagal dikirim. Silakan coba lagi nanti.']);
        }

        return redirect($redirectTo)->with('success', 'Pesan berhasil dikirim!');
    }
}
