<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ __('Post List') }}
            </h2>
            <x-button-link href="{{ route('post.create') }}">Add Post</x-button-link>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="overflow-x-auto shadow-xl sm:rounded-lg">
            <table class="w-full divide-y divide-content-border">
                <thead class="bg-content-table2">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                            Titles
                        </th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                            Created
                        </th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                            Edited
                        </th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-content-table divide-y divide-content-border">
                    @foreach ($postsList as $post)
                    <tr @if($loop->even) class="bg-content-table2" @endif>
                        <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-content_text">
                            {{ $post->id }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                            <a href="{{ route('post.show', ['alias' => $post->alias] ) }}" class="text-indigo-600 hover:text-indigo-900">
                                ({{ $post->language }}) {{ $post->title }}
                            </a>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                            <div>{{ $post->author->name ?? 'N/A' }}</div>
                            <div>{{ $post->created_at->format('Y-m-d') }}</div>
                            <div>{{ $post->created_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                            <div>{{ $post->reviewer->name ?? 'N/A' }}</div>
                            <div>{{ $post->updated_at->format('Y-m-d') }}</div>
                            <div>{{ $post->updated_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('post.edit', $post->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('post.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-accent hover:text-accent-hover">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 m-2">
                {{ $postsList->links() }}
            </div>
        </div>
    </div>
</x-app-layout>