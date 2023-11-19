<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empresa;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //crear empresa excellence clock
        $empresa = Empresa::Create([
            'nombre' => 'Excellence Clock',
            'responsable' => 'admin'
        ]);

        //crear usuario admin
        $admin = User::create([
            'name' => 'admin',
            'shurname' => 'admin',
            'email' => 'admin@excellenceclock.net',
            'password' => Hash::make('admin'),
            'id_empresa' => 1
        ]);

        //asignar rol admin
        $admin->assignRole('admin');

    }
}
