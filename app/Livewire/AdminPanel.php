<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminPanel extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $name, $email, $userId;
    public $isModalOpen = false;
    public $newUser = [];
    public $selectedUser;
    public $selectedSpatieRole;
    public $isEditModalOpen = false;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    
    protected $rules = [
        'newUser.name' => 'required|string|max:255',
        'newUser.email' => 'required|email|unique:users,email',
        'newUser.password' => 'required|min:6',
    ];
    
    public function createUser()
    {
        $this->validate();
    
        User::create([
            'name' => $this->newUser['name'],
            'email' => $this->newUser['email'],
            'password' => bcrypt($this->newUser['password']),
        ]);
    
        $this->reset('newUser');
        $this->closeModal();
    }
    
    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->status = $user->status;
        dd($user);
        $this->selectedSpatieRole = $user->roles->isNotEmpty() ? $user->roles->first()->name : null;
        $this->isEditModalOpen = true;
    }
    
    public function saveUser()
    {
        $user = User::findOrFail($this->userId);
        if ($user) {
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
            ]);
    
            if ($this->selectedSpatieRole) {
                $spatieRole = Role::where('name', $this->selectedSpatieRole)->first();
                if ($spatieRole) {
                    $user->syncRoles([$spatieRole->id]);
                }
            }
    
            $user->save();
            $this->closeEditModal();
        }
    }
    
    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
    }
    
    public function closeModal()
    {
        $this->isModalOpen = false;
    }
    
    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->selectedUser = null;
        $this->selectedSpatieRole = null;
    }
    
    public function render()
    {
        $query = User::query();
    
        $users = $query->where(function ($query) {
            $query->where('name', 'LIKE', '%' . $this->search . '%')
                ->orWhere('email', 'LIKE', '%' . $this->search . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    
        $spatieRoles = Role::all();
    
        return view('livewire.admin-panel', compact('users', 'spatieRoles'));
    }
    
}
