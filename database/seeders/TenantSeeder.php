<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tanents\Tanent;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tanent::create([
            'name' => 'Tenant One',
            'subdomain' => 'tenant1.localhost',
        ]);

        Tanent::create([
            'name' => 'Tenant Two',
            'subdomain' => 'tenant2.localhost',
        ]);
    }
}
