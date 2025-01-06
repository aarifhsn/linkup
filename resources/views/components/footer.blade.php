<footer class="shadow bg-sky-900 mt-10">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="flex items-center justify-center">
            <span class="block text-sm sm:text-center text-gray-200">© {{now()->year}}
                <a href="{{route('home')}}" class="hover:underline">{{config('app.name')}}</a>. All Rights
                Reserved.</span>
        </div>
    </div>


    <!-- Group Chat Component -->
    @if (auth()->check())
        <div x-data="{ open: false, show: false }" class="fixed right-0 bottom-0 mr-6 mb-12">
            <!-- Chat Button with Tooltip -->
            <div class="relative mr-2 flex border-2 border-sky-600 rounded-full p-2" @mouseover="show = true"
                @mouseleave="show = false">
                <button @click="open = !open" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" id="message" x="0" y="0"
                        fill="currentColor" class="w-6 h-6 text-sky-600" version="1.1" viewBox="0 0 20 20">
                        <path d="M18 6v7c0 1.1-.9 2-2 2h-4v3l-4-3H4c-1.101 0-2-.9-2-2V6c0-1.1.899-2 2-2h12c1.1 0 2 .9 2 2z">
                        </path>
                    </svg>
                </button>

                <!-- Tooltip -->
                <div x-show="show" x-transition
                    class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-sky-800 text-white text-sm py-1 px-2 rounded shadow-lg">
                    Group Chat
                </div>
            </div>

            <!-- Chat Box -->
            <div class="absolute right-0 bottom-0 mr-4 mb-4 min-w-max border-2 rounded border-sky-300" x-show="open"
                @click.outside="open = false">
                <div class="">
                    <livewire:chat />
                </div>
            </div>
        </div>
    @endif

</footer>