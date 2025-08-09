<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'email' => 'admin@gmail.com',
            'password' => '123456',
            'tenant_id' => '1',
            'name' => 'user1'
        ]);

        //run TenantSeeder
        $this->call(TenantSeeder::class);
    }
}
