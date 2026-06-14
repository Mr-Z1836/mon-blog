<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterSubscriptionController extends Controller
{
    public function store(StoreNewsletterSubscriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $email = strtolower($data['email']);
        $source = $data['source'] ?? null;

        $subscriber = NewsletterSubscriber::query()->where('email', $email)->first();

        if ($subscriber?->isActive()) {
            return back()->with('status', 'Tu es déjà abonné à la newsletter Built in Benin.');
        }

        if ($subscriber) {
            $subscriber->resubscribe($source);

            return back()->with('status', 'Bienvenue de retour ! Ton abonnement à la newsletter est réactivé.');
        }

        NewsletterSubscriber::create([
            'email' => $email,
            'source' => $source,
            'abonne_le' => now(),
        ]);

        return back()->with('status', 'Merci ! Tu es abonné à la newsletter Built in Benin.');
    }
}
