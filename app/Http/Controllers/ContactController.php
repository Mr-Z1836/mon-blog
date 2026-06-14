<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $contactMessage = ContactMessage::create([
            'nom' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);

        $adminEmail = config('blog.contact_email');

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new ContactMessageReceived($contactMessage));
        }

        return back()->with('status', 'Merci ! Ton message a bien été envoyé. On te répondra dès que possible.');
    }
}
