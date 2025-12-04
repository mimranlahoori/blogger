<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $category = '';
    public $selected = [];
    public $selectAll = false;

    protected $queryString = ['search', 'status', 'category'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function bulkAction($action)
    {
        if (empty($this->selected)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select at least one post.'
            ]);
            return;
        }

        $posts = Post::whereIn('id', $this->selected);

        switch ($action) {
            case 'publish':
                $posts->update(['status' => 'published', 'published_at' => now()]);
                $message = 'Posts published successfully!';
                break;
            case 'draft':
                $posts->update(['status' => 'draft', 'published_at' => null]);
                $message = 'Posts moved to draft!';
                break;
            case 'archive':
                $posts->update(['status' => 'archived']);
                $message = 'Posts archived successfully!';
                break;
            case 'delete':
                $posts->delete();
                $message = 'Posts deleted successfully!';
                break;
            default:
                $message = 'Action completed!';
        }

        $this->selected = [];
        $this->selectAll = false;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    public function render()
    {
        $query = Post::with(['user', 'categories']);

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->category) {
            $query->whereHas('categories', function($q) {
                $q->where('categories.id', $this->category);
            });
        }

        $posts = $query->latest()->paginate(20);

        return view('livewire.admin.post-table', [
            'posts' => $posts
        ]);
    }
}
