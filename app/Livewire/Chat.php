<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Events\MessageSent;
use App\Models\ChatRoom;

class Chat extends Component
{
    use WithPagination;

    public $chatRoomId = null;
    public $chatRooms = [];
    public $newChatRoomName = '';
    public $newMessage = '';
    public $messages = [];
    public $currentChatRoomName = '';

    protected $rules = [
        'newMessage' => 'required|string|max:255',
    ];

    public function mount($chatRoomId = null)
    {
        $this->chatRoomId = $chatRoomId;

        // Load existing chat rooms
        $this->chatRooms = ChatRoom::all()->toArray();

        if ($this->chatRoomId) {
            $this->loadMessagesForChatRoom();
        }

        // Load existing messages for the chat room
        $this->messages = Message::with('user')
            ->where('chat_room_id', $chatRoomId)
            ->orWhereNull('chat_room_id')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        // Listen for new messages
        $this->listeners['echo:chat-room.' . $this->chatRoomId . ',MessageSent'] = 'receiveMessage';
    }

    private function loadMessagesForChatRoom()
    {
        if ($this->chatRoomId) {
            $chatRoom = ChatRoom::find($this->chatRoomId);
            $this->currentChatRoomName = $chatRoom?->name;

            $this->messages = Message::with('user')
                ->where('chat_room_id', $this->chatRoomId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        }
    }

    public function sendMessage()
    {
        $this->validate();

        $message = Message::create([
            'user_id' => Auth::id(),
            'message' => $this->newMessage,
            'chat_room_id' => $this->chatRoomId,
        ]);

        $this->messages[] = $message->load('user')->toArray();

        $this->newMessage = '';

        // Broadcast the message to others
        broadcast(new MessageSent($message->load('user')))->toOthers();

        // Dispatch a browser event to trigger scrolling
        $this->dispatch('message-sent');
    }

    public function createChatRoom()
    {
        $this->validate([
            'newChatRoomName' => 'required|string|max:30',
        ]);

        $chatRoom = ChatRoom::create(['name' => $this->newChatRoomName]);


        // set the current chat room details
        $this->chatRoomId = $chatRoom->id;
        $this->loadMessagesForChatRoom();
        $this->currentChatRoomName = $chatRoom->name;

        // clear the input field
        $this->newChatRoomName = '';

        // reload the chat rooms to include the new ones
        $this->chatRooms = ChatRoom::all()->toArray();

        // Clear the old messages
        $this->messages = [];

        // Trigger browser event
        $this->dispatch('room-entered');
    }

    public function receiveMessage($message)
    {
        $this->messages[] = $message;
        $this->dispatch('message-sent');
    }


    public function updatedChatRoomId($value)
    {
        if ($value) {
            $this->chatRoomId = $value;
            $this->loadMessagesForChatRoom();
            $this->listeners = [
                'echo:chat-room.' . $value . ',MessageSent' => 'receiveMessage',
            ];
            $this->dispatch('room-entered');
        } else {
            $this->listeners = [];
            $this->messages = [];
            $this->currentChatRoomName = null;
        }
    }





    public function leaveChatRoom()
    {
        $this->chatRoomId = null;
        $this->currentChatRoomName = null;

        $this->messages = [];
    }



    public function render()
    {
        return view('livewire.chat', [
            'messages' => $this->messages,
        ]);
    }
}

