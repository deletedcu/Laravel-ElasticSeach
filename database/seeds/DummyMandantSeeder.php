<?php

use Illuminate\Database\Seeder;

class DummyMandantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
     
    //   $mandantId = DB::table('mandants')->insertGetId(
       DB::table('mandants')->insert(

                [
                    'name' => 'NEPTUN',
                    'kurzname' => 'NEP',
                    'mandant_number' => '003',
                    'rights_wiki' => 1,
                    'rights_admin' => 1,
                    'active' => 1,
                    // 'logo' => 'mandant-default.png',
                    // 'mandant_id_hauptstelle' => null,
                    'hauptstelle' => 1,
                    'adresszusatz' => 'Test Adresszusatz',
                    'strasse' => 'Teststrasse',
                    'hausnummer' => '123a',
                    'plz' => '50000',
                    'ort' => 'Testort',
                    'telefon' => '+000 123 456 7890',
                    'fax' => '+000 123 456 0000',
                    'kurzwahl' => '321',
                    'email' => 'info@neptun-gmbh.de',
                    'website' => 'http://www.neptun-gmbh.de',
                    'geschaftsfuhrer_history' => 'Historie Testeintrag 1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
                
       );
       
     
       DB::table('mandant_infos')->insert(
            
                [
                    // 'mandant_id' => $mandantId,
                    'mandant_id' => 1,
                    'prokura' => 'Test Prokura',
                    'betriebsnummmer' => 'BN-12345',
                    'handelsregister' => 'HR-12345',
                    'handelsregister_sitz' => 'Handelsregister Sitz Test',
                    'steuernummer' => '12 345 67890',
                    'steuernummer_lohn' => '11 222 33333',
                    'ust_ident_number' => '00 123 66666',
                    'zausatzinfo_steuer' => 'Zusatzinfo Steuer Test',
                    'berufsgenossenschaft_number' => '0123456',
                    'berufsgenossenschaft_zusatzinfo' => 'Berufsgenossenschaft Zusatzinfo Test',
                    'erlaubniss_gultig_ab' => date("Y-m-d H:i:s"),
                    'erlaubniss_gultig_von' => 'Test',
                    'geschaftsjahr' => '2016',
                    'geschaftsjahr_info' => 'Geschaftsjahr Info Test',
                    'bankverbindungen' => 'Bankverbindungen Test',
                    'info_wichtiges' => 'Info Wichtiges Test',
                    'info_sonstiges' => 'Info Sonstiges Test',
                    
                    // 'mitarbeiter_lohn_id' => null,
                    // 'mitarbeiter_finanz_id' => null,
                    // 'mitarbeiter_edv_id' => null,
                    // 'mitarbeiter_vertrieb_id' => null,
                    // 'mitarbeiter_umwelt_id' => null,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
            
       );
     
    //   $mandantId = DB::table('mandants')->insertGetId(
       DB::table('mandants')->insert(
                [
                    'name' => 'Deadpool mandant',
                    'kurzname' => 'dPool',
                    'mandant_number' => '003',
                    'rights_wiki' => 1,
                    'rights_admin' => 1,
                    'active' => 1,
                    // 'logo' => 'mandant-default.png',
                    // 'mandant_id_hauptstelle' => null,
                    'hauptstelle' => 1,
                    'adresszusatz' => 'Test Sokut',
                    'strasse' => 'Poljicka',
                    'hausnummer' => '65',
                    'plz' => '21000',
                    'ort' => 'Split',
                    'telefon' => '+000 123 456 7890',
                    'fax' => '+000 123 456 0000',
                    'kurzwahl' => '321',
                    'email' => 'info@deadpool.de',
                    'website' => 'http://www.deadpool.de',
                    'geschaftsfuhrer_history' => 'Historie TESTING 1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
                
       );
       
     
       DB::table('mandant_infos')->insert(
            
                [
                    // 'mandant_id' => $mandantId,
                    'mandant_id' => 2,
                    'prokura' => 'Test Prokura 2',
                    'betriebsnummmer' => 'BN-54321',
                    'handelsregister' => 'HR-54321',
                    'handelsregister_sitz' => 'Handelsregister Sitz Test 2',
                    'steuernummer' => '12 345 67890',
                    'steuernummer_lohn' => '11 222 33333',
                    'ust_ident_number' => '00 123 66666',
                    'zausatzinfo_steuer' => 'Zusatzinfo Steuer Test DeadPool',
                    'berufsgenossenschaft_number' => '0123456',
                    'berufsgenossenschaft_zusatzinfo' => 'Berufsgenossenschaft Zusatzinfo Test DeadPool',
                    'erlaubniss_gultig_ab' => date("Y-m-d H:i:s"),
                    'erlaubniss_gultig_von' => 'Test DeadPool',
                    'geschaftsjahr' => '2016',
                    'geschaftsjahr_info' => 'Geschaftsjahr Info Test DeadPool',
                    'bankverbindungen' => 'Bankverbindungen Test DeadPool',
                    'info_wichtiges' => 'Info Wichtiges Test DeadPool',
                    'info_sonstiges' => 'Info Sonstiges Test DeadPool',
                    
                    // 'mitarbeiter_lohn_id' => null,
                    // 'mitarbeiter_finanz_id' => null,
                    // 'mitarbeiter_edv_id' => null,
                    // 'mitarbeiter_vertrieb_id' => null,
                    // 'mitarbeiter_umwelt_id' => null,
                    
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
            
       );
       
    }
}
