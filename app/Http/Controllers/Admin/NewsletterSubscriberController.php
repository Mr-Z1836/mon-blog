<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class NewsletterSubscriberController extends Controller
{
    public function index(): View
    {
        return view('admin.newsletter-subscribers.index', [
            'subscribers' => NewsletterSubscriber::query()
                ->active()
                ->latest('subscribed_at')
                ->paginate(25),
            'totalActive' => NewsletterSubscriber::query()->active()->count(),
        ]);
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber): RedirectResponse
    {
        $newsletterSubscriber->update(['unsubscribed_at' => now()]);

        return to_route('admin.newsletter-subscribers.index')
            ->with('status', 'Abonné désinscrit.');
    }
}
