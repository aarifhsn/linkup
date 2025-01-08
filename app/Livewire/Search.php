<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Search extends Component
{
    use WithPagination;

    public $results = [];
    protected $queryString = ['searchText'];
    public $username;
    public $id;
    public $searchText = '';

    public function rules()
    {
        return [
            'searchText' => 'required',
        ];
    }

    public function updatedSearchText($value)
    {
        $this->reset('results');

        $users = User::where('username', 'like', '%' . $value . '%')
            ->orWhere('email', 'like', '%' . $value . '%')
            ->orWhereRaw("(first_name || ' ' || last_name) LIKE ?", ["%{$value}%"])
            ->pluck('id');

        $posts = Post::whereIn('user_id', $users)
            ->orWhere('content', 'like', '%' . $value . '%')
            ->with('user') // Eager load user data
            ->paginate(10);

        $this->results = $posts;
    }

    public function render()
    {
        return view('livewire.search', [
            'results' => $this->results
        ]);
    }
}