<div class="bg-sky-50 p-6 rounded-lg">
    <div id="chat-container" class="chat-messages overflow-y-auto" style="max-height: 500px;">
        @foreach($messages as $message)
            <div class="{{ $message['user']['id'] == Auth::id() ? 'text-right ml-12' : 'text-left mr-12' }} mb-6 ">
                @if ($message['user']['id'] != Auth::id())
                    <h6 class="text-sky-700 text-xs font-semibold capitalize">{{ $message['user']['first_name'] }}
                        {{ $message['user']['last_name'] }}
                    </h6>
                @endif
                <div
                    class="p-2 inline-block text-sm {{ $message['user']['id'] == Auth::id() ? 'bg-sky-800 text-sky-50' : 'bg-sky-200 text-sky-900' }} rounded-md">

                    <p class="font-normal text-left">{{ $message['message'] }}</p>

                    <span class="block text-xs text-gray-400 mt-1 text-left">
                        {{ \Carbon\Carbon::parse($message['created_at'])->format('M d, Y') }}
                        {{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}
                    </span>
                </div>
            </div>
        @endforeach
        <div id="scroll-to-bottom"></div>
    </div>

    <form wire:submit.prevent="sendMessage" x-data="{ isTyping: false }" class="mt-3">
        <div class="flex">
            <input type="text" wire:model.lazy="newMessage" class="form-input rounded border-sky-300 w-full px-4 py-2"
                placeholder="Type your message here..." x-on:keydown="isTyping = true"
                x-on:keyup.debounce.2000="isTyping = false">
            <button type="submit" class="ml-2 px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-800">
                Send
            </button>
        </div>
        <div x-show="isTyping" class="text-sm text-gray-500 mt-2">You are typing...</div>
        @error('newMessage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </form>


    <script>
        document.addEventListener('livewire:message-sent', function () {
            console.log('Message sent event received');
            setTimeout(() => {
                let chatContainer = document.getElementById('chat-container');
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }, 50);
        });

        // Optionally scroll on component load
        document.addEventListener('livewire:load', function () {
            let chatContainer = document.getElementById('chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
</div>