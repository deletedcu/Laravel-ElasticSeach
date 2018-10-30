<?php

use Illuminate\Database\Seeder;

class DummyDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('documents')->insert(
            
            /*
              `id` int(11),
              `document_type_id` int(11),
              `document_status_id` int(11),
              `user_id` int(11),
              `date_created` datetime,
              `version` int(11),
              `name` varchar(200),
              `owner_user_id` int(11),
              `search_tags` varchar(256),
              `summary` text,
              `date_published` datetime,
              `date_modified` datetime,
              `date_expired` datetime,
              `version_parent` int(11),
              `document_group_id` int(11),
              `iso_category_id` int(11),
              `show_name` bool,
              `adressat_id` int(11),
              `betreff` varchar(200),
              `document_replaced_id` int(11),
              `date_approved` datetime,
              `email_approval` bool,
              `approval_all_roles` bool,
              `pdf_upload` bool,
            */
            
            [
                [
                    'document_type_id' => 2,
                    'document_status_id' => 3,
                    'user_id' => 1,
                    'version' => 1,
                    'name' => 'Dummy document 1',
                    'owner_user_id' => 1,
                    'search_tags' => 'dummy, test, document',
                    'summary' => 'Document created for development and testing purposes.',
                    'date_published' => date("Y-m-d H:i:s"),
                    'date_expired' => null,
                    'version_parent' => null,
                    'document_group_id' => 1,
                    'iso_category_id' => 1,
                    'show_name' => true,
                    'adressat_id' => 1,
                    'betreff' => 'Test Betreff',
                    'document_replaced_id' => null,
                    'date_approved' => date("Y-m-d H:i:s"),
                    'email_approval' => true,
                    'approval_all_roles' => true,
                    'pdf_upload' => true,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'document_type_id' => 2,
                    'document_status_id' => 3,
                    'user_id' => 1,
                    'version' => 1,
                    'name' => 'Dummy document 2',
                    'owner_user_id' => 3,
                    'search_tags' => 'dummy, test, document',
                    'summary' => 'Document created for development and testing purposes.',
                    'date_published' => date("Y-m-d H:i:s"),
                    'date_expired' => null,
                    'version_parent' => null,
                    'document_group_id' => 2,
                    'iso_category_id' => 2,
                    'show_name' => true,
                    'adressat_id' => 2,
                    'betreff' => 'Test Betreff 123',
                    'document_replaced_id' => null,
                    'date_approved' => date("Y-m-d H:i:s"),
                    'email_approval' => true,
                    'approval_all_roles' => true,
                    'pdf_upload' => true,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
        
        DB::table('editor_variants')->insert(
            
            /*
              `id` int(11),
              `document_id` int(11),
              `variant_number` int(11),
              `document_status_id` int(11),
              `inhalt` text,
              `approval_all_mandants` bool
            */
            
            [
                [
                    'document_id' => 1,
                    'variant_number' => 1,
                    'inhalt' => 'Bacon ipsum dolor amet ham hock shank turducken, kielbasa alcatra turkey frankfurter ground round swine shankle leberkas.',
                    'approval_all_mandants' => true,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'document_id' => 2,
                    'variant_number' => 1,
                    'inhalt' => 'Bacon ipsum dolor amet ham hock shank turducken, biltong brisket flank sirloin shank alcatra.',
                    'approval_all_mandants' => false,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
        
        DB::table('editor_variant_documents')->insert(
            
            /*
              `id` int(11),
              `editor_variant_id` int(11),
              `document_status_id` int(11),
              `document_group_id` int(11),
              `document_id` int(11)
            */
            
            [
                [
                    'editor_variant_id' => 1,
                    'document_status_id' => 3,
                    'document_group_id' => 1,
                    'document_id' => 1,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'editor_variant_id' => 2,
                    'document_status_id' => 3,
                    'document_group_id' => 2,
                    'document_id' => 2,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
        
        /*
        DB::table('document_uploads')->insert(
            
            [
                [
                    'editor_variant_id' => 1,
                    'file_path' => '/files/documents/dummy-document-1/dummy-document-1-upload-1.pdf',
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'editor_variant_id' => 1,
                    'file_path' => '/files/documents/dummy-document-1/dummy-document-1-upload-2.pdf',
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'editor_variant_id' => 2,
                    'file_path' => '/files/documents/dummy-document-2/dummy-document-2-upload-1.pdf',
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
        */
        
        DB::table('document_mandants')->insert(
            
            /*
            `id` int(11),
            `document_id` int(11),
            `editor_variant_id` int(11),
            
            */
            
            [
                [
                    'document_id' => 2,
                    'editor_variant_id' => 2,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
        
        DB::table('document_mandant_mandants')->insert(
            
            /*
            `id` int(11),
            `document_mandant_id` int(11),
            `mandant_id` int(11)
            */
            
            [
                [
                    'document_mandant_id' => 1,
                    'mandant_id' => 1,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
        
        DB::table('document_mandant_roles')->insert(
            
            /*
            `id` int(11),
            `document_mandant_id` int(11),
            `role_id` int(11)
            */
            
            [
                [
                    'document_mandant_id' => 1,
                    'role_id' => null,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );

        DB::table('document_approvals')->insert(
            
            /*
            `id` int(11),
            `user_id` int(11),
            `document_id` int(11),
            `date_approved` datetime,
            `approved` bool
            */
            
            [
                [
                    'user_id' => 3,
                    'document_id' => 1,
                    'date_approved' => date("Y-m-d H:i:s"),
                    'approved' => true,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'user_id' => 3,
                    'document_id' => 2,
                    'date_approved' => date("Y-m-d H:i:s"),
                    'approved' => true,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
        
        DB::table('published_documents')->insert(
            
            /*
            `id` int(11),
            `document_id` int(11),
            `document_group_id` int(11),
            `url_unique` varchar(200)
            */
            
            [
                [
                    'document_id' => 1,
                    'document_group_id' => 1,
                    'url_unique' => 'asd123',
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'document_id' => 2,
                    'document_group_id' => 2,
                    'url_unique' => 'asd456',
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
    }
}
