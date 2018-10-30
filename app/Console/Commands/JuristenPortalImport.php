<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Illuminate\Support\Facades\File;

use App\Helpers\OcrHelper;
use App\Document;
use App\EditorVariant;
use App\DocumentUpload;
use App\User;

/**
 * Imports all uploaded files into the Juristenportal
 * 
 * @author Mirko Rosenthal <mirko.rosenthal@webbite.de>
 */
class JuristenPortalImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'juristenportal:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads all files in the ocrUploads Folder and moves them to the correct places';


    /**
     * Path to the upload folder
     * 
     * @var string
     */
    protected $portalOcrUploads;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->portalOcrUploads = public_path().'/files/juristenportal/ocr-uploads/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = File::files($this->portalOcrUploads);
        // dd($files);
        foreach($files as $file){
            $this->importFile($file);
        }
    }
    

    /**
     * Handles the import of a file
     * @param string $fileName Name of the file
     */
    public function importFile($fileName){
        //Filter out files which exists somewhere in the database
        if(substr($fileName, -10) == '.duplicate'){
            echo "Skipping <code>" . $fileName . "</code><br />" . PHP_EOL;
            return;
        }
        
        $ocrHelper = new OcrHelper($this->portalOcrUploads, $fileName);
      
        $text = $ocrHelper->extractText();
        $metaData = $ocrHelper->getMetaData();

        if(strpos($text, 'DUMMY DOCUMENT') !== false){
            //Ignore the dummy file
            return false;
        }
        $ocrHelper->setFilename($fileName); /* Restore original filename */
        $converted_file = $ocrHelper->convertToPDF();

        $explode = explode('/', $fileName);
        $filteredName = last($explode);
        
        $user_id = 1;
        $real_user_name = '';
        
        if(isset($metaData['Last Modified By'])){
            $real_user_name = $metaData['Last Modified By'];
        }else if(isset($metaData['Creator'])){
            $real_user_name = $metaData['Creator'];
        }
        if(($user = User::findByName($real_user_name)) != null){
            $user_id = $user->id;
        }

        $document = new Document();
        $document->document_type_id = null;
        $document->user_id = $user_id;
        if(isset($metaData['Title']) && !empty($metaData['Title'])){
            $document->name = $metaData['Title'];
            // dd($metaData);
            // $document->name_long = $metaData['Title'];
        }else{
            $document->name = $ocrHelper->getFileBaseName();
            // $document->name_long = $ocrHelper->getFileBaseName();
        }
        if(isset($metaData['Keywords'])){
            $document->search_tags = $metaData['Keywords'];
        }
        if(isset($metaData['Description'])){
            $document->summary = $metaData['Description'];
        }
        $document->owner_user_id = $user_id;
        $document->version = 1;
        $document->save();
        if( empty($document->name) ){
            $document->name = 'Dokument '.$document->id;
            // $document->long_name = 'Dokument '.$document->id;
            $document->save();
        }
        
        $editor_variant = new EditorVariant();
        $editor_variant->document_id = $document->id;
        $editor_variant->variant_number = 1;
        $editor_variant->inhalt = $text;
        $editor_variant->save();

    
        $document_dir = public_path() . '/files/documents/'. $document->id.'/' ;
        if (!File::exists($document_dir)) {
            File::makeDirectory($document_dir, 0777, true);
        }
        File::move($fileName, $document_dir . $ocrHelper->getFileBaseName());
        
        
        $document_upload_original = new DocumentUpload();
        $document_upload_original->editor_variant_id = $editor_variant->id;
        $document_upload_original->file_path = $ocrHelper->getFileBaseName();
        $document_upload_original->save();
        
        if (File::exists( $this->portalOcrUploads.'/'. $converted_file)) {
            if (!File::exists($document_dir . $converted_file)) {
                File::move( $this->portalOcrUploads.'/'.$converted_file ,$document_dir . $converted_file );
            }
            $document_upload_converted = new DocumentUpload();
            $document_upload_converted->editor_variant_id = $editor_variant->id;
            $document_upload_converted->file_path = $converted_file ;
            $document_upload_converted->save();   
        }
    }
}
