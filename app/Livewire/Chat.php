<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Events\MessageSent;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\Log;

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
            $this->listeners = [
                'echo:chat-room.' . $this->chatRoomId . ',MessageSent' => 'receiveMessage',
            ];
        }
    }

    public function updatedChatRoomId($chatRoomId)
    {
        Log::info('updatedChatRoomId called with: ' . $this->chatRoomId);
        if ($chatRoomId) {
            Log::info('Chat room updated: ' . $this->chatRoomId);
            $this->loadMessagesForChatRoom();
            $this->listeners = [
                'echo:chat-room.' . $this->chatRoomId . ',MessageSent' => 'receiveMessage',
            ];
        }

    }

    private function loadMessagesForChatRoom()
    {

        $chatRoom = ChatRoom::find($this->chatRoomId);
        if ($chatRoom) {
            Log::info('Loading messages for chat room: ' . $this->chatRoomId);
            $this->currentChatRoomName = $chatRoom->name;

            $this->messages = Message::with('user')
                ->where('chat_room_id', $this->chatRoomId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        } else {
            Log::warning('Chat room not found: ' . $this->chatRoomId);
            $this->messages = [];
            $this->currentChatRoomName = '';
        }
    }

    public function createChatRoom()
    {
        $this->validate([
            'newChatRoomName' => 'required|string|max:30',
        ]);

        $chatRoom = ChatRoom::create(['name' => $this->newChatRoomName]);

        // Reload chat rooms and set the new one as selected
        $this->chatRooms = ChatRoom::all()->toArray();
        $this->chatRoomId = $chatRoom->id;

        $this->newChatRoomName = '';
        $this->loadMessagesForChatRoom();

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


    public function receiveMessage($message)
    {
        $this->messages[] = $message;
        $this->dispatch('message-sent');
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

