<div class="bg-sky-50 p-6 rounded-lg">
    <div class="chat-box overflow-y-auto" id="chat-messages" style="max-height: 500px;" x-data
        @message-added.window="scrollToBottom()">
        @foreach($messages as $message)
            <div class="{{ $message['user']['id'] == Auth::id() ? 'text-right ml-12' : 'text-left mr-12' }} mb-6 ">
                @if ($message['user']['id'] != Auth::id())
                    <h6 class="text-sky-700 text-xs font-semibold capitalize">{{ $message['user']['first_name'] }}
                        {{ $message['user']['last_name'] }}
                    </h6>
                @endif
                <div
                    class="px-4 py-2 inline-block text-sm {{ $message['user']['id'] == Auth::id() ? 'bg-sky-800 text-sky-50' : 'bg-sky-200 text-sky-900' }} rounded-md">

                    <p class="font-normal text-left">{{ $message['message'] }}</p>
                    <span class="block text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($message['created_at'])->format('M d, Y') }}
                        {{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}
                </div>
            </div>
        @endforeach
        <div id="scroll-to-bottom"></div>
    </div>

    <form wire:submit.prevent="sendMessage" x-data="{ isTyping: false }" class="mt-3">
        <div class="flex">
            <input type="text" wire:model.lazy="newMessage" class="form-input rounded border-gray-300 w-full"
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
        document.addEventListener('livewire:load', () => {
            Livewire.on('messageAdded', () => {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        });

        function scrollToBottom() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</div>