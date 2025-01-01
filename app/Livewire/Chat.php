<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Chat extends Component
{
    use WithPagination;

    public $newMessage = '';
    public $chatRoomId = null;

    public $messages = [];

    protected $rules = [
        'newMessage' => 'required|string|max:255',
    ];

    public function mount($chatRoomId = null)
    {
        $this->chatRoomId = $chatRoomId;

        // Load existing messages for the chat room
        $this->messages = Message::with('user')->where('chat_room_id', $chatRoomId)->get()->toArray();
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

        // Manually trigger scroll
        $this->dispatchBrowserEvent('message-added');
    }

    public function render()
    {
        return view('livewire.chat', [
            'messages' => Message::where('chat_room_id', $this->chatRoomId)
                ->orWhereNull('chat_room_id')
                ->with('user')
                ->latest()
                ->paginate(50),
        ]);
    }
}

