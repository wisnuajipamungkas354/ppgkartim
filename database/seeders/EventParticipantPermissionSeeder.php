<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EventParticipantPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // List permission yang dibutuhkan oleh RelationManager Participants
        $permissions = [
            'view_any_event::participant',
            'view_event::participant',
            'create_event::participant',
            'update_event::participant',
            'delete_event::participant',
            'restore_event::participant',
            'force_delete_event::participant',
            'attach_event::participant',
            'detach_event::participant',
            'detach_any_event::participant',
            'associate_event::participant',
            'dissociate_event::participant',
            'dissociate_any_event::participant'
        ];

        // Create permission if not exists
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign ke role super_admin
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $role->givePermissionTo($permissions);

        $this->command->info('Event participant relation permissions assigned to super_admin!');
    }
}
