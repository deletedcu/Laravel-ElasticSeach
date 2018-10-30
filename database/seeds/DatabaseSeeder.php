<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(DummyDocumentStatusSeeder::class);
        // $this->call(DummyDocumentTypeSeeder::class);
        // $this->call(DummyRoleSeeder::class);
        // $this->call(DummyMandantSeeder::class);
        // $this->call(DummyUserSeeder::class);
        // $this->call(DummyIsoCategorySeeder::class);
        // $this->call(DummyAdressatSeeder::class);
        // $this->call(DummyDocumentSeeder::class);
        // $this->call(DummyWikiCategorySeeder::class);
        // $this->call(DummyWikiStatusSeeder::class);
        // $this->call(SatistikRoleSeeder::class);
        // $this->call(InventarRoleSeeder::class);
        // $this->call(JuristenPortalSeeder::class);
        $this->call(JuristenCategoriesNewSeeder::class);
    }
}
