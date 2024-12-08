<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ __('Reviews List') }}
            </h2>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="overflow-x-auto shadow-xl sm:rounded-lg">
                <table class="w-full divide-y divide-content-border">
                    <thead class="bg-content-table2">
                        <tr>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Game
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Created
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Edited
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-content-table divide-y divide-content-border">
                        @foreach ($reviews as $review)
                            <tr @if ($loop->even) class="bg-content-table2" @endif>
                                <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-content_text">
                                    {{ $review->id }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                    <a class="text-accent" href="{{ route('profile.show', $review->user_id) }}">
                                        {{ $review->author->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $review->game->name }}
                                </td>
                                <td>
                                    {{ $review->status }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                    <div>{{ $review->author->name ?? 'N/A' }}</div>
                                    <div>{{ $review->created_at->format('Y-m-d') }}</div>
                                    <div>{{ $review->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                    <div>{{ $review->updated_at->format('Y-m-d') }}</div>
                                    <div>{{ $review->updated_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('review.edit', $review->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 m-2">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
