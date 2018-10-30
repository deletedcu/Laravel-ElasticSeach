<?php

use Illuminate\Database\Seeder;
use App\DocumentType;

class JuristenCategoriesNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $juristportalDocType = DocumentType::where('name','LIKE','%Juristendokument%')->first();
        if( !is_null($juristportalDocType)){
            $juristportalDocType->name = 'Rechtsablage';
            $juristportalDocType->jurist_document = 1;
            $juristportalDocType->save();
        }
        else{
            $juristportalDocType = DocumentType::where('name','LIKE','%Rechtsablage%')->first();
            $juristportalDocType->jurist_document = 1;
            $juristportalDocType->save();
        }
        $notiz = DocumentType::where('name','LIKE','%Notizen%')->first();
        if( !is_null($notiz)){
            $notiz->jurist_document = 1;
            $notiz->save();
        }
        
        DB::table('document_types')->insert(
            [
                [
                    'name' => 'Beratungsdokument',
                    'document_art' => '0',
                    'document_role' => '0',
                    'read_required' => '0',
                    'allow_comments' => '1',
                    'visible_navigation' => '0',
                    'jurist_document' => '1',
                    'menu_position' => '1',
                    'order_number' => '9',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
            ]);
    }
}
