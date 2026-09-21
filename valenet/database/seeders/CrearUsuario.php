<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CrearUsuario extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Antonio Mantilla',
            'email' => 'valenet@admin.com',
            'password' => Hash::make('admin123'),
            'rol' => 'administrador',
            'estado' => 1
        ]);
    }
}
