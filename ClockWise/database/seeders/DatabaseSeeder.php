<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate users table to avoid duplicate entries
        DB::table('users')->truncate();
        // Seed admin
        User::factory()->create([
            'name' => 'Leif',
            'email' => 'leif@company.com',
            'role' => 'admin',
            'id_number' => '0000',
            'password' => bcrypt('password'),
        ]);

        // Seed employees
        $employees = [
            'Andrea', 'Caryll', 'Cedes', 'Charm', 'Daisy', 'Danna', 'Devey', 'Dianne', 'Ebbie', 'Eunice',
            'Giesel', 'James', 'Jem', 'Jerica', 'Jesper', 'Jeff', 'Joan', 'Joe', 'Jobelle', 'Kadan So',
            'Katia', 'Kyla', 'Lani', 'Lea', 'Leah', 'Leiza', 'Lia', 'Maisie', 'Marilyn', 'Mitch', 'Raanah',
            'Rash', 'Shann', 'Shane', 'Sharlyn', 'Shie', 'Shishi', 'Trishia', 'Vera', 'Wilma'
        ];
        $id = 1;
        foreach ($employees as $name) {
            $email = strtolower(str_replace(' ', '', $name)) . '@company.com';
            $id_number = str_pad($id, 4, '0', STR_PAD_LEFT);
            User::factory()->create([
                'name' => $name,
                'email' => $email,
                'role' => 'employee',
                'id_number' => $id_number,
                'password' => bcrypt('password'),
            ]);
            $id++;
        }
    }
}
