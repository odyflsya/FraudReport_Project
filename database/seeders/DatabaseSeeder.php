<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            AdminUserSeeder::class,
            RefAktivitasTerkaitSeeder::class,
            RefJabatanSeeder::class,
            RefJenisFraudSeeder::class,
            RefJenisIdentitasSeeder::class,
            RefKejadianFraudSeeder::class,
            RefKelemahanFraudSeeder::class,
            RefLokasiFraudSeeder::class,
            RefPencegahanFraudSeeder::class,
            RefPihakDirugikanSeeder::class,
            RefStatusPelakuSeeder::class,
            RefTindakanPenangananSeeder::class,
        ]);
    }
}
