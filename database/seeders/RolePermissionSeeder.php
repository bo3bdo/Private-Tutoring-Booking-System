<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Admin permissions
            'manage users',
            'manage subjects',
            'manage locations',
            'manage teachers',
            'manage bookings',
            'manage payments',
            'manage settings',
            'view reports',

            // Teacher permissions
            'manage own availability',
            'manage own slots',
            'view own bookings',
            'manage own bookings',
            'mark booking status',
            'reschedule booking',
            'block slots',

            // Student permissions
            'browse subjects',
            'browse teachers',
            'view available slots',
            'book slot',
            'view own bookings',
            'cancel own bookings',
            'pay for booking',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage users',
            'manage subjects',
            'manage locations',
            'manage teachers',
            'manage bookings',
            'manage payments',
            'manage settings',
            'view reports',
        ]);

        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $teacher->givePermissionTo([
            'manage own availability',
            'manage own slots',
            'view own bookings',
            'manage own bookings',
            'mark booking status',
            'reschedule booking',
            'block slots',
        ]);

        $student = Role::firstOrCreate(['name' => 'student']);
        $student->givePermissionTo([
            'browse subjects',
            'browse teachers',
            'view available slots',
            'book slot',
            'view own bookings',
            'cancel own bookings',
            'pay for booking',
        ]);
    }
}
