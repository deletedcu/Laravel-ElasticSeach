<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use File;

use App\Document;

class ClearDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete documents with it\'s following database tables';

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
       $documents = Document::all();
        $deletedCount = 0;
        $notDeletedCount = 0;
        if(count($documents)){
            foreach($documents as $document){
                if( \File::deleteDirectory(public_path('files/documents/'.$document->id))){
                    $deletedCount++;
                }
                else{
                    $notDeletedCount++;
                }
                foreach($document->editorVariantDocument as $ev){
                   
                    foreach($ev->documentUpload as $up){
                    
                        $up->delete();
                    }
                    $ev->delete();
                }
                $document->delete();
            }
        
        echo 'Number of deleted documents:'.$deletedCount;
        }
        else{
            echo 'Documents are already deleted';
        }
        
    }
}
