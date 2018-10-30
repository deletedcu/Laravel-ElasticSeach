<?php

use Illuminate\Database\Seeder;

class DummyDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_types')->insert(
            [
                
                [
                    'name' => 'News',
                    'document_art' => '0',
                    'document_role' => '1',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'visible_navigation' => '1',
                    'order_number' => '1',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Rundschreiben',
                    'document_art' => '0',
                    'document_role' => '1',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'visible_navigation' => '1',
                    'order_number' => '2',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'QM-Rundschreiben',
                    'document_art' => '0',
                    'document_role' => '1',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'visible_navigation' => '1',
                    'order_number' => '3',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'ISO Dokumente',
                    'document_art' => '0',
                    'document_role' => '0',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'visible_navigation' => '1',
                    'order_number' => '4',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Formulare',
                    'document_art' => '1',
                    'document_role' => '0',
                    'read_required' => '0',
                    'allow_comments' => '0',
                    'visible_navigation' => '1',
                    'order_number' => '5',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Anlagen',
                    'document_art' => '1',
                    'document_role' => '0',
                    'read_required' => '0',
                    'allow_comments' => '0',
                    'visible_navigation' => '1',
                    'order_number' => '6',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Juristendokumente',
                    'document_art' => '0',
                    'document_role' => '0',
                    'read_required' => '0',
                    'allow_comments' => '1',
                    'visible_navigation' => '0',
                    'order_number' => '7',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
                
            ]
        );
    }
}
