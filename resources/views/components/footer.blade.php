<footer class="shadow bg-sky-900 mt-10">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="flex items-center justify-center">
            <span class="block text-sm sm:text-center text-gray-200">© {{now()->year}}
                <a href="{{route('home')}}" class="hover:underline">{{config('app.name')}}</a>. All Rights
                Reserved.</span>
        </div>
    </div>
</footer>