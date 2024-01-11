<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <h1 class="text-white text-lg font-bold">{{ $ticket->title }}</h1>
        <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-between p-4">
                <p>{{ $ticket->description }}</p>
                <p>{{ $ticket->created_at->diffForHumans() }}</p>
                @if ($ticket->attachment)
                    <a href="{{ '/storage/' . $ticket->attachment }}" target="_blank">Attachment</a>
                @endif
            </div>
            <div class="flex gap-4 justify-between">
                <div class="flex gap-4">
                    <a href="{{ route('ticket.edit', $ticket) }}">
                        <x-primary-button>Edit</x-primary-button>
                    </a>
                    <form action="{{ route('ticket.destroy', $ticket) }}" method="POST" class="inline">
                        @csrf
                        @method('delete')
                        <x-primary-button>Delete</x-primary-button>
                    </form>
                </div>
                @if (auth()->user()->isAdmin)
                    <div class="flex gap-4">
                        <form action="{{ route('ticket.update', $ticket) }}" method="POST">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="status" value="approved" />
                            <x-primary-button>Approve</x-primary-button>
                        </form>
                        <form action="{{ route('ticket.update', $ticket) }}" method="POST" class="inline">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="status" value="rejected" />
                            <x-primary-button>Reject</x-primary-button>
                        </form>
                    </div>
                @else
                    <p>Status： {{ $ticket->status }}</p>
                @endif
            </div>
        </div>
</x-app-layout>
