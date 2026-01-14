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
            'browse courses',
            'purchase courses',
            'access enrolled courses',

            // Teacher course permissions
            'teacher.manage_courses',
            'teacher.manage_course_lessons',
            'teacher.view_course_sales',

            // Admin course permissions
            'admin.manage_all_courses',
        ];

        // Create all permissions first (with explicit guard)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Admin role
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissionNames = [
            'manage users',
            'manage subjects',
            'manage locations',
            'manage teachers',
            'manage bookings',
            'manage payments',
            'manage settings',
            'view reports',
            'admin.manage_all_courses',
        ];
        $adminPermissions = Permission::whereIn('name', $adminPermissionNames)->where('guard_name', 'web')->get();
        $admin->syncPermissions($adminPermissions);

        // Teacher role
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $teacherPermissionNames = [
            'manage own availability',
            'manage own slots',
            'view own bookings',
            'manage own bookings',
            'mark booking status',
            'reschedule booking',
            'block slots',
            'teacher.manage_courses',
            'teacher.manage_course_lessons',
            'teacher.view_course_sales',
        ];
        $teacherPermissions = Permission::whereIn('name', $teacherPermissionNames)->where('guard_name', 'web')->get();
        $teacher->syncPermissions($teacherPermissions);

        // Student role
        $student = Role::firstOrCreate(['name' => 'student']);
        $studentPermissionNames = [
            'browse subjects',
            'browse teachers',
            'view available slots',
            'book slot',
            'view own bookings',
            'cancel own bookings',
            'pay for booking',
            'browse courses',
            'purchase courses',
            'access enrolled courses',
        ];
        $studentPermissions = Permission::whereIn('name', $studentPermissionNames)->where('guard_name', 'web')->get();
        $student->syncPermissions($studentPermissions);
    }
}
