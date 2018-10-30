<?php

use Illuminate\Database\Seeder;

class DummyIsoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        /*
            $table->integer('iso_category_parent_id');
            $table->string('name');
            $table->boolean('active');
            $table->boolean('parent');
        */
        
       DB::table('iso_categories')->insert(
            [
                [
                    'iso_category_parent_id' => null,
                    'name' => 'Hauptkategorie',
                    'slug' => str_slug('Hauptkategorie'),
                    'parent' => true,
                    'active' => true,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'iso_category_parent_id' => 1,
                    'name' => 'Unterkategorie 1',
                    'slug' => str_slug('Unterkategorie 1'),
                    'parent' => false,
                    'active' => true,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'iso_category_parent_id' => 1,
                    'name' => 'Unterkategorie 2',
                    'slug' => str_slug('Unterkategorie 2'),
                    'parent' => false,
                    'active' => true,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
    }
}
