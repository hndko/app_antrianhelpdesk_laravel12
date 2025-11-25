<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-text-secondary">Email</label>
            <input type="email" name="email" id="email" required autofocus
                class="mt-1 block w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent focus:border-accent outline-none transition">
            @error('email')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-text-secondary">Password</label>
            <input type="password" name="password" id="password" required
                class="mt-1 block w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent focus:border-accent outline-none transition">
        </div>

        <button type="submit"
            class="w-full py-2 px-4 bg-accent hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition duration-200">
            Masuk
        </button>
    </form>
</x-guest-layout>