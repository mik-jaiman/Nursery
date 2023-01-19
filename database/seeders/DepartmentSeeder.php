<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department = Department::create([
            'name' => 'แผนก1'
        ]);

        $department->branches()->create([
            'name' => 'สาขา1'
        ]);

        $department = Department::create([
            'name' => 'แผนก2'
        ]);

        $department->branches()->create([
            'name' => 'สาขา2'
        ]);

        \App\Models\User::factory(20)->create();
    }
}
