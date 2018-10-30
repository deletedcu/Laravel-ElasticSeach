<?php

namespace App\Http\Repositories;

/*
 * Created by PhpStorm.
 * User: Marijan
 * Date: 04.05.2016.
 * Time: 11:42
 */

use DB;
use Auth;
use Carbon\Carbon;
use App\User;
use App\MandantUser;
use App\MandantUserRole;
use App\Document;
use App\DocumentType;
use App\DocumentApproval;
use App\DocumentCoauthor;
use App\PublishedDocument;
use App\UserReadDocument;
use App\EditorVariant;
use App\Helpers\ViewHelper;

class DocumentRepository
{
    public function __construct(){
        
        $this->pdfPath = public_path().'/files/documents/';    
    }
    
    /**
     * Generate dummy data.
     *
     * @return object array $array
     */
    public function generateDummyData($name = '', $collections = array(), $tags = true)
    {
        $array = array();
        for ($i = 0; $i < rand(1, 10); ++$i) {
            $data = new \StdClass();
            $data->text = $name.'-'.rand(1, 200);
            if ($tags == true) {
                $data->tags = array(count($collections));
            }

            if (count($collections) > 0) {
                $data->nodes = $collections;
            }
            array_push($array, $data);
        }

        return $array;
    }

    public function generateDummyDataSingle($name = '', $collections = array(), $tags = true)
    {
        $array = array();
        for ($i = 0; $i < 1; ++$i) {
            $data = new \StdClass();
            $data->text = $name.'-'.rand(1, 200);
            if ($tags == true) {
                $data->tags = array(count($collections));
            }

            if (count($collections) > 0) {
                $data->nodes = $collections;
            }
            array_push($array, $data);
        }

        return $array;
    }

    /**
     * Generate documents treeview. If no array parameter is present, all documents are read.
     *
     * @param object array $array
     * @param bool         $tags
     * @param bool         $document
     *
     * @return object array $array
     */
    // public function generateTreeview( $items = array(), $tags = false, $document=true, $documentId=0, $hrefDelete=false ){
    public function generateTreeview($items = array(), $options = array())
    {
        $optionsDefault = [
            'tags' => false,
            'document' => true,
            'documentId' => 0,
            'showApproval' => false,
            'showUniqueURL' => false,
            'showDelete' => false,
            'showHistory' => false,
            'pageHome' => false,
            'pageHistory' => false,
            'pageWiki' => false,
            'pageFavorites' => false,
            'pageDocuments' => false,
            'pageSearch' => false,
            'myDocuments' => false,
            'noCategoryDocuments' => false,
            'temporaryNull' => false,
            'beratungsDokumente' => false,
            // 'formulare' => false,
        ];
        $options = array_merge($optionsDefault, $options);

        $treeView = array();
        $documents = array();

        if (count($items)) {
            $documents = $items;
        }
        if ($options['pageSearch']) {
            $searchResultsCounter = 0;
        }

        if ($options['document'] == true && count($documents) > 0) {
            foreach ($documents as $document) {
                $node = new \StdClass();
                $node->text = $document->name;
                $icon = $icon2 = '';

                if ($document->document_type_id == 3) {
                    if ($document->qmr_number != null) {
                        $node->text = $document->qmr_number.$document->additional_letter.': '.$node->text;
                    }
                    $node->text = 'QMR '.$node->text;
                }

                // Treeview Setting for Homepage
                if ($options['pageHome'] == true || $options['pageFavorites'] == true) {
                    if ($document->published || ($options['myDocuments'] == true)) {
                        $node->beforeText = '';
                        if ($options['pageHome'] == true && $options['myDocuments'] == true) {
                            $node->beforeText = 'Version '.$document->version.', '.$document->documentStatus->name.' - ';
                        } // Version 3, Entwurf
                        if ($document->date_published == null && is_null($document->document_type_id)) {
                            $node->beforeText .= Carbon::parse($document->created_at)->format('d.m.Y').' - ';
                        } elseif ($document->date_published != null) {
                            $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y').' - ';
                        } else {
                            $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y').' - ';
                        }
                        if (isset($document->owner)) {
                            $node->beforeText .= $document->owner->first_name.' '.$document->owner->last_name;
                        }

                        if ($document->published != null) {
                            $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                            ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                        }

                        if (!is_null($document->document_type_id) && $document->documentType->read_required) {
                            if ($document->document_status_id == 3) {
                                if (isset($readDocument)) {
                                    $icon = 'icon-read ';
                                    $node->titleText = 'gelesen';
                                } else {
                                    $icon = 'icon-notread ';
                                    $node->titleText = 'muss gelesen werden';
                                }
                            }
                        }

                        if ($options['pageFavorites'] == true) {
                            $node->hrefDelete = url('dokumente/'.$document->id.'/favorit');
                            // $node->icon2 = 'icon-trash ';
                            // $icon2 = 'icon-trash ';
                        }
                    }
                    if (!is_null($document->document_type_id)) {
                        $node->afterText = $document->documentType->name;
                    }
                }

                // Treeview Setting for Document Overview Pages
                if ($options['pageDocuments'] == true) {
                    $node->beforeText = '';
                    $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y');
                    if (isset($document->owner)) {
                        $node->beforeText .= ' - '.$document->owner->first_name.' '.$document->owner->last_name;
                    }

                    if ($document->published != null) {
                        $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                        ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                    }

                    if ($document->documentType->read_required) {
                        if ($document->document_status_id == 3) {
                            if (isset($readDocument)) {
                                $icon = 'icon-read ';
                                $node->titleText = 'gelesen';
                            } else {
                                $icon = 'icon-notread ';
                                $node->titleText = 'muss gelesen werden';
                            }
                        }
                    }

                    $node->afterText = $document->documentType->name;

                    // if( isset($options['formulare']) && $options['formulare'] == true ){
                    if ($document->documentType->document_art == 1) {
                        $variants = $this->documentVariantPermission($document, null, false)->variants;

                        $links = null;
                        foreach ($document->variantDocuments as $key => $dc) {
                            if (isset($dc->editorVariant->document)) {
                                if (in_array($dc->editorVariant->document->document_status_id, [3, 5])) {
                                    if ($this->universalDocumentPermission($dc->editorVariant->document)) {
                                        if (isset($dc->editorVariant->document->published)) {
                                            $links .= trim('<a href="/dokumente/'.$dc->editorVariant->document->published->url_unique.'" target="_blank" class="link-after-text">'.$dc->editorVariant->document->name.'</a>').'; ';
                                        } else {
                                            $links .= trim('<a href="/dokumente/'.$dc->editorVariant->document->id.'" target="_blank" class="link-after-text">'.$dc->editorVariant->document->name.'</a>').'; ';
                                        }
                                    }
                                }
                            }
                        }
                        if ($links) {
                            $node->afterLink = '<span class="attached-documents">'.$links.'</span>';
                        }
                    }
                }

                if ($options['noCategoryDocuments'] == true) {
                    if (is_null($document->document_type_id)) {
                        $variants = $this->documentVariantPermission($document, null, false)->variants;
                    // if (sizeof($document->editorVariantNoDeleted)) {

                    if (count($variants)) {
                        // get all variants, and all their attachments

                        $documentsAttached = array();

                        foreach ($variants as $ev) {
                            array_push($documentsAttached, Document::find($ev->document_id));
                        }

                        // generate item for treeview and add his attachments

                        if (count($documentsAttached)) {
                            $node->nodes = array();
                            // $node->icon .= ' parent-node ';

                            // if ($options['tags']) $node->tags = array(sizeof($document->editorVariantDocument));

                            foreach ($documentsAttached as $secondDoc) {
                                // $node->href = route('dokumente.show', $secondDoc->id);

                                if (isset($secondDoc) && (!$secondDoc->documentUploads->isEmpty())) {
                                    // $subNode->nodes = array();

                                    foreach ($secondDoc->documentUploads as $upload) {
                                        $subNode = new \StdClass();
                                        $subNode->icon = 'child-node hidden ';
                                        $subNode->icon2 = 'icon-download ';
                                        $subNode->titleText2 = 'Download';
                                        $subNode->text = $secondDoc->name;
                                        // $subNode->text = $upload->file_path;
                                        $subNode->href = '/download/'.$secondDoc->id.'/'.$upload->file_path;
                                        array_push($node->nodes, $subNode);
                                    }
                                }
                            }

                            if (count($node->nodes) < 1) {
                                unset($node->nodes);
                            }
                        }
                    }
                    }
                }
                if ($options['pageHistory'] == true) {
                    // $node->text = "Version " . $document->version . "- " . $node->text . " - " . $document->updated_at;

                    $node->beforeText = '';
                    $node->beforeText = 'Version '.$document->version.', '.$document->documentStatus->name.' - '; // Version 3, Entwurf
                    $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y').' - '.$document->owner->first_name.' '.$document->owner->last_name;

                    if ($document->published != null) {
                        $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                        ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                    }

                    $node->afterText = $document->documentType->name;

                    if ($document->published && $document->document_status_id == 3) {
                        $icon = 'icon-released ';
                        $node->titleText = 'veröffentlicht';
                    }
                    // else $icon = 'icon-notreleased ';
                }

                // Search results TreeView
                if ($options['pageSearch'] == true) {
                    /*
                    #1 - 01.10.2016 - Struktur Administrator
                    Rundschreiben - Rund Frei test
                    HIER KOMMT TEXT FALLS DARIN DER TREFFER WAR
                    */
                    $searchResultsCounter += 1;
                    if ($document->published || ($document->document_status_id == 1 && $options['myDocuments'] == true)) {
                        $node->beforeText = '#'.$searchResultsCounter.' - '.$document->documentType->name.' - ';
                        // if($options['pageHome'] == true && $options['myDocuments'] == true)
                        //     $node->beforeText = 'Version '.$document->version.', '.$document->documentStatus->name.' - ';// Version 3, Entwurf

                        $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y').' - ';
                        if (isset($document->owner)) {
                            $node->beforeText .= $document->owner->first_name.' '.$document->owner->last_name;
                        }

                        if ($document->published != null) {
                            $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                            ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                        }

                        if ($document->documentType->read_required) {
                            if ($document->document_status_id == 3) {
                                if (isset($readDocument)) {
                                    $icon = 'icon-read ';
                                    $node->titleText = 'gelesen';
                                } else {
                                    $icon = 'icon-notread ';
                                    $node->titleText = 'muss gelesen werden';
                                }
                            }
                        }
                    }

                    // $node->text = $document->documentType->name .' - '. $node->text;
                    if (isset($document->inhalt) && !empty($document->inhalt)) {
                        $node->afterText = ViewHelper::extractTextSimple($document->inhalt);
                    } else {
                        $node->afterText = 'Keine Inhalte';
                    }
                    // $node->afterText = $document->documentType->name;
                }

                if ($document->document_status_id == 3) {
                    if ($document->created_at->gt(Auth::user()->last_login_history)) {
                        if ($options['pageFavorites'] == false) {
                            $icon2 = 'icon-favorites ';
                            $node->titleText2 = 'neues Dokument';
                        }
                    }

                    if ($this->canViewHistory()) {
                        if ($options['showHistory'] == true) {
                            if (PublishedDocument::where('document_group_id', $document->document_group_id)->count() > 1) {
                                $node->hrefHistory = url('dokumente/historie/'.$document->id);
                            }
                        }
                    }
                }

                if (in_array($document->document_status_id, [2, 6])) {
                    if ($options['pageHome'] == true) {
                        $node->beforeText = '';
                        $node->beforeText = 'Version '.$document->version.', '.$document->documentStatus->name.' - ';
                        $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y');
                        if (isset($document->owner)) {
                            $node->beforeText .= ' - '.$document->owner->first_name.' '.$document->owner->last_name;
                        }
                    }

                    if ($this->canViewHistory()) {
                        if ($options['showHistory'] == true) {
                            if (PublishedDocument::where('document_group_id', $document->document_group_id)->count() > 1) {
                                $node->hrefHistory = url('dokumente/historie/'.$document->id);
                            }
                        }
                    }

                    if ($document->document_status_id == 2) {
                        $icon = 'icon-open ';
                        $node->titleText = 'freigegeben';
                    } else {
                        $icon = 'icon-blocked ';
                        $node->titleText = 'nicht freigegeben';
                    }

                    $icon2 = 'icon-notreleased ';
                    $node->titleText2 = 'nicht veröffentlicht';
                }

                $node->icon = $icon;
                $node->icon2 = $icon2;

                // $node->icon3 = $icon3 . 'last-node-icon ';
                // if ($options['showUniqueURL'] == true)
                // dd(is_null($document->document_type_id ));
                if ($document->document_status_id == 3) {
                    if (isset($document->published)) {
                        $node->href = route('dokumente.show', $document->published->url_unique);
                    } else {
                        $node->href = route('dokumente.show', $document->id);
                    }
                } elseif ($document->document_status_id == 6) {
                    $node->href = url('dokumente/'.$document->id.'/freigabe');
                } 
                elseif ( $options['temporaryNull'] == true && $options['beratungsDokumente'] == true &&
                    is_null($document->document_type_id ) ) {
                    $node->href = url('beratungsdokumente/'.$document->id.'/edit');
                } 
                elseif ( $options['temporaryNull'] == true && is_null($document->document_type_id ) ) {
                    $node->href = url('notiz/'.$document->id.'/edit');
                } 
                elseif (is_null($document->document_type_id)  ) {
                    $node->href = url('dokumente/'.$document->id.'/edit');
                } 
                else {
                    $node->href = route('dokumente.show', $document->id);
                }

                // TreeView Delete Option - Uncomment if needed
                if ($options['pageFavorites'] && $options['showDelete']) {
                    $node->hrefDelete = url('dokumente/'.$document->id.'/favorit');
                    $node->text = $document->name;
                    if ($document->document_type_id == 3) {
                        if ($document->qmr_number != null) {
                            $node->text = $document->qmr_number.$document->additional_letter.': '.$node->text;
                        }
                        $node->text = 'QMR '.$node->text;
                    }
                    //  $icon2 = 'icon-trash';
                    //  $node->icon2 = 'icon-trash ';
                }

                if ($document->document_status_id != 6) {
                    $variants = $this->documentVariantPermission($document, null, false)->variants;
                    // if (sizeof($document->editorVariantNoDeleted)) {
                    if (count($variants)) {
                        // get all variants, and all their attachments

                        $documentsAttached = array();

                        foreach ($variants as $ev) {
                            if ($ev->hasPermission == true) {
                                foreach ($ev->editorVariantDocument as $evd) {
                                    array_push($documentsAttached, Document::find($evd->document_id));
                                }
                            }
                        }

                        // generate item for treeview and add his attachments

                        if (count($documentsAttached)) {
                            $node->nodes = array();
                            // $node->icon .= ' parent-node ';

                            // if ($options['tags']) $node->tags = array(sizeof($document->editorVariantDocument));

                            foreach ($documentsAttached as $secondDoc) {
                                // $node->href = route('dokumente.show', $secondDoc->id);

                                if (isset($secondDoc) && (!$secondDoc->documentUploads->isEmpty())) {
                                    // $subNode->nodes = array();

                                    foreach ($secondDoc->documentUploads as $upload) {
                                        $subNode = new \StdClass();
                                        $subNode->icon = 'child-node hidden ';
                                        $subNode->icon2 = 'icon-download ';
                                        $subNode->titleText2 = 'Download';
                                        $subNode->text = $secondDoc->name;
                                        // $subNode->text = $upload->file_path;
                                        $subNode->href = '/download/'.$secondDoc->id.'/'.$upload->file_path;
                                        array_push($node->nodes, $subNode);
                                    }
                                }
                            }

                            if (count($node->nodes) < 1) {
                                unset($node->nodes);
                            }
                        }
                    }
                }
                array_push($treeView, $node);
            }
        } elseif ($options['document'] == false && count($documents) > 0) {
            foreach ($documents->editorVariantDocument as $evd) {
                if (Document::find($evd->document_id) != null) {
                    if ($evd->document_id != null && $options['documentId'] != 0 && $evd->document_id != $options['documentId']) {
                        $secondDoc = Document::find($evd->document_id);
                        // if($secondDoc != null){
                        $node = new \StdClass();
                        $node->text = $secondDoc->name;
                        $node->icon = 'icon-parent';

                        // TreeView Delete Option - Uncomment if needed
                        // if ($options['showDelete']){
                        //     $node->hrefDelete = url('anhang-delete/' . $options['documentId']. '/' .$evd->editor_variant_id . '/' .$evd->document_id );
                        // }
                        //$node->href = route('dokumente.show', $secondDoc->id);

                        if (!$secondDoc->documentUploads->isEmpty()) {
                            $node->nodes = array();
                            if ($options['tags']) {
                                $node->tags = array(count($secondDoc->documentUploads));
                            }

                            foreach ($secondDoc->documentUploads as $upload) {
                                $subNode = new \StdClass();
                                // $subNode->text = basename($upload->file_path);
                                $subNode->text = 'PDF Rundschreiben';
                                $subNode->icon = 'fa fa-file-o';
                                // $subNode->href = '/download/' . str_slug($secondDoc->name) . '/' . $upload->file_path;
                                $subNode->href = '/download/'.$secondDoc->id.'/'.$upload->file_path;
                                $subNode->child = true;

                                array_push($node->nodes, $subNode);
                            }

                            if (count($node->nodes) < 1) {
                                unset($node->nodes);
                            }
                        }

                        array_push($treeView, $node);
                    }
                }
                // }//if second doc not null
            }
        }

        return json_encode($treeView);
    }

    /**
     * Generate wiki entry treeview. If no array parameter is present, all documents are read.
     *
     * @param object array $array
     * @param bool         $tags
     * @param bool         $document
     *
     * @return object array $array
     */
    public function generateWikiTreeview($items = array(), $options = array())
    {
        $optionsDefault = [
            'tags' => false,
            'pageSearch' => false,
        ];
        $options = array_merge($optionsDefault, $options);

        $treeView = array();
        $wikiPages = array();
        $wikiPageCount = 0;

        if (count($items)) {
            $wikiPages = $items;
        }

        foreach ($wikiPages as $wikiPage) {
            $node = new \StdClass();
            $node->text = $wikiPage->name;
            // $icon = $icon2 = '';

            $node->beforeText = '';
            $node->beforeText .= Carbon::parse($wikiPage->updated_at)->format('d.m.Y').' - '.
                $wikiPage->user->first_name.' '.$wikiPage->user->last_name;

            $node->afterText = $wikiPage->category->name;

            // $node->icon = $icon;
            // $node->icon2 = $icon2;

            $node->href = url('/wiki/'.$wikiPage->id);

            if ($options['pageSearch']) {
                $wikiPageCount += 1;
                $node->beforeText = '#'.$wikiPageCount.' - '.$wikiPage->category->name.' - '.$node->beforeText;

                if (isset($wikiPage->content) && !empty($wikiPage->content)) {
                    $node->afterText = ViewHelper::extractTextSimple($wikiPage->content);
                } else {
                    $node->afterText = 'Keine Inhalte';
                }
            }

            array_push($treeView, $node);
        }

        return json_encode($treeView);
    }

    /**
     * Generate link list of attached documents for the passed item(s).
     *
     * @param object array $items
     * @param int          $id
     *
     * @return object array $resultArray
     */
    public function getAttachedDocumentLinks($items = array(), $id = 0)
    {
        $options = [
            'document' => false,
            'documentId' => $id,
        ];

        $documents = array();
        $resultArray = array();

        if (count($items)) {
            $documents = $items;
        }

        if (count($documents) > 0) {
            foreach ($documents->editorVariantDocument as $evd) {
                if (Document::find($evd->document_id) != null) {
                    // dd($options['documentId']);
                    if ($evd->document_id != null && $options['documentId'] != 0 && $evd->document_id != $options['documentId']) {
                        $secondDoc = Document::find($evd->document_id);
                        $node = new \StdClass();
                        $node->name = $secondDoc->name.' ('.$secondDoc->documentStatus->name.')';
                        $node->documentId = $secondDoc->id;
                        $node->deleteUrl = url('anhang-delete/'.$options['documentId'].'/'.$evd->editor_variant_id.'/'.$evd->document_id);

                        //$//$node->href = route('dokumente.show', $secondDoc->id);
                        // $secondDoc->d->documentUploads);
                        if (!$secondDoc->documentUploads->isEmpty()) {
                            $node->files = array();

                            foreach ($secondDoc->documentUploads as $upload) {
                                $subNode = new \StdClass();
                                // $subNode->text = basename($upload->file_path);
                                // $subNode->downloadUrl = '/download/' . str_slug($secondDoc->name) . '/' . $upload->file_path;
                                $subNode->downloadUrl = '/download/'.$secondDoc->id.'/'.$upload->file_path;
                                array_push($node->files, $subNode);
                            }

                            array_push($resultArray, $node);
                        }
                    }
                }
            }
        }

        // dd($resultArray);
        return $resultArray;
    }

    /**
     * Get redirection form.
     *
     * @return string $form
     */
    public function setDocumentForm($documentType, $pdf = false, $attachment = false)
    {
        $data = new \StdClass();

        $modelUpload = DocumentType::find($documentType);
        $data->form = 'editor';
        $data->url = 'editor';
        if ($modelUpload->document_art == true || $documentType == 5) {
            $data->form = 'upload';
            $data->url = 'document-upload';
        }
        // dd(strtolower($modelUpload->name) );
        if ($pdf == 1 || $pdf == '1' || $pdf == 'on' || $pdf == true) {
            $data = $this->checkUploadType($data, $modelUpload, $pdf);
        }

        return $data;
    }

    /**
     * Check if document type Round.
     *
     * @return string $form
     */
    public function checkUploadType($data, $model, $pdf)
    {
        $docTypeNews = DocumentType::find(DocumentType::NEWS);
        $docTypeIso = DocumentType::find(DocumentType::ISO_DOKUMENTE);

        if (((strpos(strtolower($model->name), 'rundschreiben') !== false) || (strpos(strtolower($model->name), strtolower($docTypeNews->name)) !== false)
        || (strpos(strtolower($model->name), strtolower($docTypeIso->name)) !== false)) && ($pdf != null || $pdf != 0 || $pdf != false)
        ) {
            $data->form = 'pdfUpload';
            $data->url = 'pdf-upload';
        }

        return $data;
    }

    /**
     * Process save or update multiple select fiels.
     *
     * @return bool
     */
    public function processOrSave($collections, $pluckedCollection, $requests, $modelName, $fields = array(), $notIn = array(), $tester = false)
    {
        $triggerDelete = false;
        if ($modelName == 'DocumentMandantMandant') {
            $triggerDelete = true;
        }
        $modelName = '\App\\'.$modelName;
        $modelStringName = $modelName;
        if (count($collections) < 1 && count($pluckedCollection) < 1) {
            if ($tester == true) {
                //  dd($requests);
                $array = array();
            }
            foreach ($requests as $request) {
                $model = new $modelName();
                foreach ($fields as $k => $field) {
                    if ($field == 'inherit') {
                        $model->$k = $request;
                    } else {
                        $model->$k = $field;
                    }
                }

                $model->save();
                $array[] = $model->id;
                // if ($triggerDelete == true){
                //     $array[] = $model->id;

                // }
            }
            //  if($triggerDelete == true){
            //     dd($array);
            //  }
            //   dd($modelStringName);
            // if($modelStringName == '\App\DocumentApproval')
        } else {
            // \DB::enableQueryLog();
            $modelDelete = $modelName::where('id', '>', 0);

            if (count($notIn) > 0) {
                foreach ($notIn as $n => $in) {
                    $modelDelete->whereIn($n, $in);
                    /* if($tester == true){
                         var_dump($n);
                         var_dump($in);
                         echo '<hr/>';
                     }*/
                }
            }
            if ($triggerDelete == true) {
                $additionalFix = $modelDelete;
                $additionalFix = $additionalFix->get();
            }
            if ($tester == true) {
                // dd($modelDelete->get());
            }
            $modelDelete->delete();
            if ($triggerDelete == true) {
                foreach ($additionalFix as $fx) {
                    // $fx->delete();
                }
            }
            if ($tester == true) {
                // dd( \DB::getQueryLog() );
            }
            if (count($requests) > 0) {
                foreach ($requests as $request) {
                    $exists = false;
                    if ($triggerDelete == true) {
                        if (isset($result) && count($result) > 0) {
                            $variant = $result[0]->documentMandant->editorVariant;
                            $exists = true;
                        }
                    }
                    if ($exists == false) {
                        $model = new $modelName();
                        foreach ($fields as $k => $field) {
                            if ($field == 'inherit') {
                                $model->$k = $request;
                            } else {
                                $model->$k = $field;
                            }
                        }
                        $model->save();
                    }
                }
                //}
                /* if($tester == true)
                    dd( \DB::getQueryLog() );*/
            }
        }
    }

    /**
     * Get variant number.
     *
     * @return string $string
     */
    public function variantNumber($name)
    {
        $string = explode('-', $name);

        return $string[1];
    }

    /**
     * Check if user is Historien Leser.
     *
     * @return bool
     */
    public function canViewHistory()
    {
        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 14) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * detect if model is dirty or not.
     *
     * @return bool
     */
    public function clearUsers($users)
    {
        $clearedArray = array();
        foreach ($users as $k => $user) {
            if (!in_array($user->user_id, $clearedArray)) {
                $clearedArray[] = $user->user_id;
            } else {
                unset($users[$k]);
            }
        }

        return $users;
    }

    /**
     * Universal document permission check.
     *
     * @param array      $userArray
     * @param collection $document
     * @param bool       $message
     *
     * @return bool || response
     */
    public function universalDocumentPermission($document, $message = true, $freigeber = false, $filterAuthors = false)
    {
        return ViewHelper::universalDocumentPermission($document, $message, $freigeber, $filterAuthors);

        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        $role = 0;
        $hasPermission = false;

        foreach ($mandantUsers as $mu) {
            $userMandatRole = MandantUserRole::where('mandant_user_id', $mu->id)->first();
            if ($userMandatRole != null && $userMandatRole->role_id == 1) {
                $hasPermission = true;
            }
        }
        if ($freigeber == true) {
            $documentAprrovers = DocumentApproval::where('document_id', $document->id)->where('user_id', $uid)->get();
            if (count($documentAprrovers)) {
                $hasPermission = true;
            }
        }
        $coAuthors = DocumentCoauthor::where('document_id', $document->id)->pluck('user_id')->toArray();
        if ($uid == $document->user_id || $uid == $document->owner_user_id || in_array($uid, $coAuthors)
        || ($freigeber == false && $filterAuthors == false && $document->approval_all_roles == 1) || $role == 1) {
            $hasPermission = true;
        }

        if ($message == true && $hasPermission == false) {
            session()->flash('message', trans('documentForm.noPermission'));
        }
        //if($document->id == 118)
        //    dd($hasPermission);
        return $hasPermission;
    }

    /**
     * Document variant permission.
     *
     * @param collection $document
     *
     * @return object $object
     */
    public function documentVariantPermission($document, $message = true)
    {
        /*  class $object stores 2 attributes:
            1. permissionExists( this is a global hasPermissionso we dont have to iterate again to see if permission exists  )
            2. variants (to store variants)[duuh]
        */
        return ViewHelper::documentVariantPermission($document, null, $message);
    }

//end documentVariant permission

public function getUserPermissionedDocuments($collection, $paginator = 'page', $orderBy = array('field' => 'id', 'sort' => 'desc'), $perPage = 50)
{
    foreach ($collection as $key => $document) {
        if (ViewHelper::documentVariantPermission($document, null, false)->permissionExists == false) {
            unset($collection[$key]);
        }
    }
    $documentsNewArr = $collection->pluck('id')->toArray();

    $collection = Document::whereIn('id', $documentsNewArr)->orderBy($orderBy['field'], $orderBy['sort'])->paginate($perPage, ['*'], $paginator);

    return $collection;
}

    /**
     * Process files for upload.
     *
     * @param DB Object(collection) $model
     * @param string                $path
     * @param array                 $files
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload($model, $path, $files)
    {
        $folder = $this->pdfPath.str_slug($model->id);
        $uploadedNames = array();
        if (!\File::exists($folder)) {
            \File::makeDirectory($folder, $mod = 0777, true, true);
        }
        if (is_array($files)) {
            $uploadedNames = array();
            $counter = 0;
            foreach ($files as $file) {
                if (is_array($file)) {
                    foreach ($file as $f) {
                        ++$counter;
                        if ($f !== null) {
                            $uploadedNames[] = $this->moveUploaded($f, $folder, $model, $counter);
                        }
                    }
                } else {
                    $uploadedNames[] = $this->moveUploaded($file, $folder, $model);
                }
            }
        } else {
            $uploadedNames[] = $this->moveUploaded($files, $folder, $model);
        }

        return $uploadedNames;
    }

    /**
     * Move files from temp folder and rename them.
     *
     * @param file object           $file
     * @param string                $folder
     * @param DB object(collection) $model
     *
     * @return string $newName
     */
    private function moveUploaded($file, $folder, $model, $counter = 0)
    {   
        $diffMarker = time() + $counter;
        $newName = str_slug($model->id).'-'.date('d-m-Y-H:i:s').'-'.$diffMarker.'.'.$file->getClientOriginalExtension();
        $path = "$folder/$newName";
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder.'/'.$filename);

        return $newName;
    }
}
