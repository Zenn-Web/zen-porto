<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Throwable;

class ContactController extends Controller
{
    public function store(ContactRequest $request, ContactMessageService $messages): RedirectResponse
    {
        $redirectTo = url('/').'#contact';

        try {
            $messages->queue($request->validated());
        } catch (Throwable) {
            // The service already logged the failure (exception class only).
            return redirect($redirectTo)
                ->withInput()
                ->withErrors(['contact' => ContactMessageService::FAILURE_MESSAGE]);
        }

        return redirect($redirectTo)->with('success', ContactMessageService::SUCCESS_MESSAGE);
    }
}
