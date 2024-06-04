<x-layout>

    <script>
        let startTime = Date.now();

        function updateTime() {
            let currentTime = Date.now();
            let elapsedTime = currentTime - startTime;

            let seconds = Math.floor(elapsedTime / 1000);
            let minutes = Math.floor(seconds / 60);
            let hours = Math.floor(minutes / 60);

            let formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            document.getElementById('session_time_field').value = formattedTime;
        }

        setInterval(updateTime, 1000);
    </script>

    <h1 class="title">Hello</h1>
    @if(session('success'))
        <div id="success-message">
            <p class="mb-2 text-sm font-medium text-white px-3 py-1 rounded-md bg-green-500">
                {{ session('success') }}
            </p>
        </div>

        <script>
            const successMessage = document.getElementById('success-message');
            const delay = 5000;
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, delay);
        </script>
    @endif

    <div class="mx-auto max-w-screen-sm card">
        <form action="{{ route('amo') }}" method="post">
            @csrf
            <div class="mb-4">
                <label for="name">Ваше Имя</label>
                <input type="text" name="name" class="input @error('name') ring-red-500 @enderror" value="{{ old('name') }}">
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email">Email</label>
                <input type="text" name="email" class="input @error('email') ring-red-500 @enderror" value="{{ old('email') }}">
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password">Телефон</label>
                <input type="text" name="phone" class="input @error('phone') ring-red-500 @enderror" value="{{ old('phone') }}">
                @error('phone')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password">Цена</label>
                <input type="text" name="price" class="input @error('price') ring-red-500 @enderror" value="{{ old('price') }}">
                @error('price')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <input type="hidden" name="session_time" id="session_time_field" value="">
            </div>
            <div>
                <button class="btn">Register</button>
            </div>
        </form>
    </div>
</x-layout>
