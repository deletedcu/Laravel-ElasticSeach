<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Auth;
use Carbon\Carbon;

use App\Document;
use App\EditorVariant;
use App\PublishedDocument;
use App\UserReadDocument;

class DocumentsPublishForms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:publish-forms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all documents of type "Formulare" and make them visible to all mandants and roles.';

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
        // get all documents with doc-type 5 (formulare)
        $documentsUpdated = Document::where('document_type_id', 5)->get();
        
        foreach ($documentsUpdated as $document) {
        
            //save to Published documents
            $document->document_status_id = 3; //aktualan
            $document->approval_all_roles = 1; //permisija
            $document->save();
            
            $this->publishProcedure($document);
            $this->checkFreigabeRoles($document);
            
            $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                            ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
            if($readDocument != null && $readDocument->deleted_at == null)
                            $readDocument->delete();
                            
            $otherDocuments = Document::where('document_group_id',$document->document_group_id)
                                ->whereNotIn('id',array($document->id))->get();
            
            /* Set attached documents as aktuell */
            
            $variantsAttachedDocuments = EditorVariant::where('document_id',$document->id)->get();
            foreach( $variantsAttachedDocuments as $vad ){
                $vad->approval_all_mandants = 1;
                $vad->save();
                $editorVariantDocuments = $vad->editorVariantDocument;
                foreach($editorVariantDocuments as $evd){
                    $evd->document_status_id = 3;
                    $evd->save();
                    $doc = Document::find($evd->document_id);
                    $doc->document_status_id = 3; //aktualan
                    $doc->approval_all_roles = 1; //permisija
                    $doc->save();
                }
            }
            
            /* End set attached documents as aktuell */
            
            foreach($otherDocuments as $oDoc){
                if( $oDoc->document_status_id != 6 && $oDoc->document_status_id != 2 ){
                    $oDoc->document_status_id = 5;
                    $oDoc->save();
                }
            }                
        }
        
        // return list of changed docs
        // return view('dokumente.statusUpdate', compact('documentsUpdated'));
    }
    
    /**
     * Document publishing procedure
     * @return bool 
     */
    private function publishProcedure($document){
        
        $id = $document->id;
        $document->document_status_id = 3;
        $document->date_published = Carbon::now();
        $document->save();
        $continue = true;
        $uniqeUrl = '';
       
        $oldDocumentVersion = PublishedDocument::where('document_group_id',$document->document_group_id)
        ->orderBy('id','DESC')->first();
        $publishedDocs =  PublishedDocument::where('document_id',$id)->first();
            if($publishedDocs == null){
                $continue = true;
                $uniqeUrl = '';
                if($oldDocumentVersion != null){
                    $continue = false;
                    $uniqeUrl = $oldDocumentVersion->url_unique;
                }
                while ($continue) {
                    $uniqeUrl = $this->generateUniqeLink();
                    if (PublishedDocument::where('url_unique',$uniqeUrl)->count() != 1)
                        $continue = false;
                }
                $publishedDocs = PublishedDocument::create(['document_id'=> $id, 'document_group_id' => $document->document_group_id,
                            'url_unique'=>$uniqeUrl]);
                $publishedDocs->fill(['document_id'=> $id, 'document_group_id' => $document->document_group_id])->save();
            }
            else{
                $publishedDocs->fill(['document_id'=> $id, 'document_group_id' => $document->document_group_id])->save();
                if($publishedDocs->deleted_at != null)
                    $publishedDocs->restore();
            }
            
            /* Set attached documents as actuell */
            $variantsAttachedDocuments = EditorVariant::where('document_id',$document->id)->get();
            foreach($variantsAttachedDocuments as $vad){
                $editorVariantDocuments = $vad->editorVariantDocument;
                foreach($editorVariantDocuments as $evd){
                    $evd->document_status_id = 3;
                    $evd->save();
                    $doc = Document::find($evd->document_id);
                    $doc->document_status_id = 3;
                    $doc->save();
                }
            }
            /* End set attached documents as actuell */
       
    }
    
    /**
     * Document freigabe roles check
     * @return bool 
     */
    private function checkFreigabeRoles( $document ){
        $mandantRoles = array();
        $mandants = $document->documentMandants;
        $mandantRolesAll = 0;
        $mandantsHasPermissionAll = 0;
        foreach($mandants as $dc){
            $mandantRolesAll = $mandantRolesAll + count( $dc->documentMandantRole);
            if( $dc->editorVariant->approval_all_mandants == 1 )
                $mandantsHasPermissionAll++;
        }
        if( ( $document->approval_all_roles != 1 && (count($mandants) == 0 && $mandantsHasPermissionAll == 0 ) )
            || (  $document->approval_all_roles != 1 && count($mandantRolesAll) < 1 ) ){
            $document->approval_all_roles = 1;
            $document->save();
        }
        return $document;
    }
    
    /**
     * Generate Unique URL String
     * @return bool 
     */
    private function generateUniqeLink($length=6){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
}
