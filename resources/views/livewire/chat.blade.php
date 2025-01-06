<div class="bg-sky-50 p-6 rounded-lg">
    <!-- Chat Room Selection -->
    <div class="flex justify-between items-center mb-4">
        <div>
            <select wire:model="chatRoomId" class="form-select rounded border-sky-300 px-4 py-2">
                <option value="">Select a chat room</option>
                @foreach ($chatRooms as $room)
                    <option value="{{ $room['id'] }}">{{ $room['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="text" wire:model="newChatRoomName" class="form-input rounded border-sky-300 px-4 py-2"
                placeholder="New chat room name">
            <button wire:click="createChatRoom" class="ml-2 px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-800">
                Create
            </button>
        </div>
    </div>

    <!-- Chat Room Header -->
    <div class="mb-4">
        @if ($chatRoomId)
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold text-sky-800 capitalize">{{ $currentChatRoomName }}</h2>
                <button wire:click="leaveChatRoom" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800">
                    Leave Room
                </button>
            </div>
        @else
            <p class="text-gray-600">Please select or create a chat room to start chatting.</p>
        @endif
    </div>


    <!-- Chat Messages -->
    @if ($chatRoomId)
        <div id="chat-container" class="chat-messages overflow-y-auto border border-sky-200 rounded-lg p-4 mb-4"
            style="max-height: 500px;">
            @foreach($messages as $message)
                <div class="{{ $message['user']['id'] == Auth::id() ? 'text-right ml-12' : 'text-left mr-12' }} mb-6">
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
    @endif

    <!-- Message Input -->
    @if ($chatRoomId)
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
    @endif

    <!-- JavaScript for Scrolling -->
    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('message-sent', () => {
                const chatBox = document.getElementById('chat-container');
                chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom
            });

            Livewire.on('room-entered', () => {
                const chatBox = document.getElementById('chat-container');
                if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
            });
        });
    </script>
</div>