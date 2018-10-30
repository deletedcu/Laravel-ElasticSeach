<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Helpers\ViewHelper;

use App\Http\Requests;
use App\Document;
use App\DocumentType;
use App\DocumentStatus;
use App\DocumentMandantMandant;
use App\DocumentMandant;
use App\Mandant;
use App\MandantUser;
use App\EditorVariant;
use App\DocumentUpload;
use App\Role;
use App\User;
use Auth;


use App\Http\Repositories\DocumentRepository;

class NoticeController extends Controller
{
    
    /**
     * Class constructor.
     */
    public function __construct(DocumentRepository $docRepo)
    {
        $this->document = $docRepo;
        $this->uploadPath = public_path().'/files/contacts/';
        
        $this->movePath = public_path().'/files/documents';
        $this->pdfPath = public_path().'/files/documents/';
        $this->portalOcrUploads = public_path().'/files/juristenportal/ocr-uploads/';
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchString = '';
        $documents =  Document::where('document_type_id', DocumentType::NOTIZEN)
        ->orderBy('created_at', 'desc')->paginate(10, ['*'], 'notiz');
        
        $documentsTree = $this->document->generateTreeview($documents,  array('pageHome' => true, 'myDocuments' => true,
        'beratungsDokumente' => true,
        'showAttachments' => true, 'temporaryNull' => true));
        return view('notiz.index',compact('documents','documentsTree','searchString') );
    
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchString = $request->get('search');
        $documents =  Document::where('document_type_id', DocumentType::NOTIZEN)->where('name','LIKE','%'.$request->get('search').'%')
        ->orderBy('created_at', 'desc')->paginate(10, ['*'], 'notiz');
        $documentsTree = $this->document->generateTreeview($documents, array('pageHome' => true, 'myDocuments' => true, 'noCategoryDocuments' => true,
        'showAttachments' => true, 'showHistory' => true,'temporaryNull'=>true));
        $mandants = Mandant::all();
        return view('notiz.index',compact('documents','documentsTree','searchString'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = new Document;
        
        $mandantUsers = User::where('active', 1)->get();
        $mandants = Mandant::all();
        $mitarbeiterUsers = $mandantUsers;

        return view('formWrapper',
            compact( 'data', 'mandants','mandantUsers', 'mitarbeiterUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $filename = '';
        $path = $this->pdfPath;
    
        $request->merge(['version'=> 1,'document_type_id' => DocumentType::NOTIZEN, 'user_id' => Auth::user()->id,
        'owner_user_id' => Auth::user()->id,'name' => $request->get('betreff'),
        'name_long' => $request->get('betreff') ]);
        
        $note = Document::create($request->all() );
        $model = $note;
        
        $request->merge([ 'document_id' =>$note->id, 'variant_number' => 1]);
        $editorVariant = EditorVariant::create( $request->all() );
        
        $request->merge([ 'editor_variant_id' => $editorVariant->id ]);
        $documentMandat =  DocumentMandant::create($request->all() );
        
        $request->merge([ 'document_mandant_id' => $documentMandat->id ]);
        $mandant =  DocumentMandantMandant::create($request->all() );
     
        if ($request->file()) {
            $fileNames = $this->document->fileUpload($model, $path, $request->file());
        }
        $counter = 0;
        $attachArr = [];
        // dd($request->all());
        if (isset($fileNames) && count($fileNames) > 0) {
            foreach ($fileNames as $fileName) {
                ++$counter;
                $request->merge([ 'file_path' => $fileName ]);
                $documentAttachment = DocumentUpload::create( $request->all() );
                $attachArr[] = $documentAttachment;
            }
        }
        
        return redirect('notiz')->with('messageSecondary', trans('notiz.noteCreated'));

    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Document::find($id);
        $mandantUsers = User::where('active', 1)->get();
        $mitarbeiterUsers = $mandantUsers;
        $mandants = Mandant::all();
        //dd($data);
        return view('formWrapper',
            compact('data', 'mandants', 'mandantUsers', 'mitarbeiterUsers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $filename = '';
        $path = $this->pdfPath;
    
        $request->merge(['version'=> 1,'document_type_id' => DocumentType::NOTIZEN, 'user_id' => Auth::user()->id,
        'owner_user_id' => Auth::user()->id,'name' => $request->get('betreff'),
        'name_long' => $request->get('betreff') ]);
        if(!$request->has('ruckruf')){
            $request->merge(['ruckruf' => 0]);
        }
        
        $note = Document::find($id);
        $note->fill($request->all() )->save();
        
        $model = $note;
        
        
        
        $editorVariant = EditorVariant::where('document_id',$id)->first();
        $editorVariant->fill($request->all() )->save();
        
        $documentMandat =  DocumentMandant::where('editor_variant_id',$editorVariant->id)->first();
        $documentMandat->fill($request->all() );
        
        $request->merge([ 'document_mandant_id' => $documentMandat->id ]);
        $mandant =  DocumentMandantMandant::create($request->all() );
     
        if ($request->file()) {
            $fileNames = $this->document->fileUpload($model, $path, $request->file());
        }
        $counter = 0;
        $attachArr = [];
        // dd($request->all());
        if (isset($fileNames) && count($fileNames) > 0) {
            foreach ($fileNames as $fileName) {
                ++$counter;
                $request->merge([ 'file_path' => $fileName ]);
                $documentAttachment = DocumentUpload::create( $request->all() );
                $attachArr[] = $documentAttachment;
            }
        }
        
        return redirect()->back()->withMessage('messageSecondary'. trans('notiz.updated') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function uploadView($id)
    {
        $note = Document::find($id);
        return view('notiz.uploadNote', compact('note'));
    }        
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
     
   public function upload(Request $request)
    {
         // not yet completed !!!!!! conflict with findDuplicates
        
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $uploaded = $this->fileUpload($request->file);
        $this->findDuplicates($uploaded);
       
        if ($uploaded == false) {
            return redirect()->back()->with('messageSecondary', 'Upload fehlgeschlagen');
        }
        else {
            Artisan::call('juristenportal:import');
            //dd($uploaded);
            return redirect()->back()->with('messageSecondary', 'Dateien hochgeladen');
        }
    }
    
        /**
     * Find duplicate Files in the database
     * 
     * This function tries to find duplicate files by filename or meta data
     * At first, it will check if a file with the same name exists.
     * If not, then it checks the Author + Title in the Documents Table
     * 
     * Duplicate File will get the file ending .duplicate
     * all other files will be renamed from .processing to their original name
     * 
     * @param array $files Array of fileNames [without the Path]
     * @return array Array with duplicate Files and their original Document Object
     */
    private function findDuplicates(array $files){
        
        $duplicates = [];
        foreach($files as $filename){
            $ocrHelper = new \App\Helpers\OcrHelper($this->portalOcrUploads, $filename);
            $metaData = $ocrHelper->getMetaData();
            $originalFilename = substr($filename, 0, -11);
            
            $dbDocumentUpload = DocumentUpload::with('editorVariant', 'editorVariant.document', 'editorVariant.document.user')
                                                ->where('file_path', $originalFilename)
                                                ->first();
                                               
            if($dbDocumentUpload != null){
                $duplicates[$filename] = ['metadata' => $metaData, 'original' => $dbDocumentUpload->editorVariant->document];
                File::move($this->portalOcrUploads . $filename , $this->portalOcrUploads . $originalFilename . '.duplicate');
                continue;
               
            }
            
            $user_id = 1;
            $real_user_name = '';
            $title = '';
            
            if(isset($metaData['Last Modified By'])){
                $real_user_name = $metaData['Last Modified By'];
            }else if(isset($metaData['Creator'])){
                $real_user_name = $metaData['Creator'];
            }
            if(isset($metaData['Title'])){
                $title = $metaData['Title'];
            }

            if(($user = User::findByName($real_user_name)) != null){
                $user_id = $user->id;
            }
            
            $document = Document::where('name', $title)
                                 ->where('user_id', $user_id)
                                 ->first();
            if($document != null){
                 $duplicates[$filename] = ['metadata' => $metaData, 'original' => $document];
                 File::move($this->portalOcrUploads . $filename , $this->portalOcrUploads . $originalFilename . '.duplicate');
                 continue;
            }

            File::move($this->portalOcrUploads . $filename , $this->portalOcrUploads . $originalFilename);
        }
        return $duplicates;
    }
    
    /**
     * Process files for upload.
     *
     * @param array $files
     *
     * @return \Illuminate\Http\Response
     */
    private function fileUpload($files)
    {
        $folder = $this->portalOcrUploads;
        $uploadedNames = array();
        if (!\File::exists($folder)) {
            \File::makeDirectory($folder, $mod = 0777, true, true);
        }
        if (is_array($files)) {
           //  dd($files);
            foreach ($files as $k => $file) {
                 if (ViewHelper::fileTypeAllowed($file)) {
                        $uploadedNames[] = $this->moveUploaded($file, $folder);
                }
            }
        } else {
            if (ViewHelper::fileTypeAllowed($files)) {
                $uploadedNames[] = $this->moveUploaded($files, $folder);
            }
        }

        if (count($uploadedNames)) {
            return $uploadedNames;
        } else {
            return false;
        }
    }


    /**
     * Move files from temp folder and rename them.
     *
     * @param file object $file
     * @param string      $folder
     *
     * @return string $newName
     */
    private function moveUploaded($file, $folder, $counter = 0)
    {
        $filename = $file->getClientOriginalName();
        $newName = $filename . '.processing';
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder.'/'.$filename);

        return $newName;
    }
}
