<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Document;

class JuristDocumentCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:jurist-document-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete documents without document type and the files associated with these documents ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    //   $documents = Document::where('document_type_id',7)->orWhere('document_type_id',null)->get();
       $documents = Document::where('document_type_id',null)->withTrashed()->get();
        $counter = 0;
        foreach($documents as $document){
            
            // dd($document->editorVariantDocument);
             if( \File::deleteDirectory(public_path('files/documents/'.$document->id))){
                    $counter++;
                }
            foreach($document->editorVariantDocument as $ev){
               
                foreach($ev->documentUpload as $up){
                
                    $up->delete();
                }
                $ev->delete();
            }
            $document->delete();
        }
        echo 'Deleted documents:'.$counter;
    }
}
