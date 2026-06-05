<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Signalements</h2></x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status'))<p class="text-green-700">{{ session('status') }}</p>@endif
        @foreach ($reports as $report)
            <div class="bg-white p-4 shadow rounded-lg">
                <p class="text-sm text-gray-500">Article : <a class="text-brand-red" href="{{ route('posts.show', $report->comment->post) }}">{{ $report->comment->post->title }}</a></p>
                <p class="mt-2 text-sm">{{ $report->comment->content }}</p>
                <p class="mt-1 text-xs text-gray-500">Par {{ $report->reporter->name }} — {{ $report->reason }}</p>
                <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="mt-2 flex gap-2">
                    @csrf @method('PATCH')
                    <select name="status" class="rounded-md border-gray-300 text-sm">
                        <option value="pending" @selected($report->status === 'pending')>En attente</option>
                        <option value="reviewed" @selected($report->status === 'reviewed')>Traité</option>
                        <option value="dismissed" @selected($report->status === 'dismissed')>Rejeté</option>
                    </select>
                    <button class="text-sm text-brand-red">Mettre à jour</button>
                </form>
            </div>
        @endforeach
        {{ $reports->links() }}
    </div>
</x-app-layout>
