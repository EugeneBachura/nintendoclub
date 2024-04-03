<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <form action="{{ route('notifications.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id">Выберите пользователя:</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="url">Ссылка:</label>
                <textarea name="url" id="url" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="message">Сообщение:</label>
                <textarea name="message" id="message" class="form-control"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Отправить уведомление</button>
        </form>
    </div>
</x-app-layout>