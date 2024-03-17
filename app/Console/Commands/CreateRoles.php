<?php

namespace App\Console\Commands;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class CreateRoles extends Command
{
    protected $signature = 'roles:create';
    protected $description = 'Create default roles';

    public function handle()
    {
        Role::create(['name' => 'admin']); 
        Role::create(['name' => 'content-creator']);
        Role::create(['name' => 'subscriber']);
        Role::create(['name' => 'user']);       

        $this->info('Roles created successfully!');
    }
}