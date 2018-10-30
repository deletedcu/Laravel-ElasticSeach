<?php

use Illuminate\Database\Seeder;

class JuristenPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        /*
        - Juristen Administrator
        - Juristen Benutzer
        - Juristen Administrator Löscher
        - Juristen Dokument Anleger
        - Juristen Log Leser
        */
        
        DB::table('roles')->insert(
            [
                [
                    'name' => 'Juristen Administrator',
                    'mandant_required' => '0',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'phone_role' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Juristen Benutzer',
                    'mandant_required' => '0',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'phone_role' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Juristen Administrator Löscher',
                    'mandant_required' => '0',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'phone_role' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Juristen Dokument Anleger',
                    'mandant_required' => '0',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'phone_role' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Juristen Log Leser',
                    'mandant_required' => '0',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'phone_role' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
    }
}
