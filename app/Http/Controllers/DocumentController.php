<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Request as RequestMerge;
use App\Helpers\ViewHelper;
use Auth;
use DB;
use File;
use Session;
use App\Classes\PdfWrapper;
use App\Document;
use App\DocumentCoauthor;
use App\DocumentType;
use App\DocumentMandant;
use App\DocumentMandantMandant;
use App\DocumentMandantRole;
use App\DocumentUpload;
use App\DocumentApproval;
use App\DocumentStatus;
use App\DocumentComment;
use App\UserReadDocument;
use App\UserEmailSetting;
use App\PublishedDocument;
use App\FavoriteDocument;
use App\FavoriteCategory;
use App\Role;
use App\IsoCategory;
use App\User;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\Adressat;
use App\EditorVariant;
use App\UserSentDocument;
use App\EditorVariantDocument; //latest active document
use App\Http\Repositories\DocumentRepository;
use App\Http\Repositories\UtilityRepository;

class DocumentController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct(DocumentRepository $docRepo, UtilityRepository $utilRepo)
    {
        $this->document = $docRepo;
        $this->utility = $utilRepo;
        $this->movePath = public_path().'/files/documents';
        $this->pdfPath = public_path().'/files/documents/';
        $this->newsId = 1;
        $this->rundId = 2;
        $this->qmRundId = 3;
        $this->isoDocumentId = 4;
        $this->formulareId = 5;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documentTypes = DocumentType::where('menu_position', 1)->whereNotIn('name', ['Juristendokumente'])->orderBy('order_number', 'asc')->get();
        $isoCategories = IsoCategory::where('active', 1)->get();

        return view('dokumente.documentIndex', compact('documentTypes', 'isoCategories'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTrash()
    {
        $trashedDocuments = array();
        $onlyTrashed = Document::onlyTrashed()->get();
        foreach ($onlyTrashed as $trashed) {
            if (ViewHelper::universalDocumentPermission($trashed, false)) { // false - disables session flash message
                // Assign download link for upload-type documents
                if (($trashed->pdf_upload == 1) || (isset($trashed->documentType) && $trashed->documentType->document_art == 1)) {
                    foreach ($trashed->editorVariantTrashed as $ev) {
                        foreach ($ev->documentUploadTrashed as $key => $du) {
                            if ($key > 0) {
                                break;
                            }
                            $trashed->propAttachment = $du->file_path;
                        }
                    }
                }
                $trashedDocuments[] = $trashed;
            }
        }

        return view('papierkorb.index', compact('trashedDocuments'));
    }

    /**
     * Download a trashed document copy.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadTrash($id)
    {
        // First check if document exists
        $document = Document::onlyTrashed()->where('id', $id)->first();

        if ($document) {
            // Restore deleted doc
            $document->restore();
            foreach ($document->editorVariantTrashed as $ev) {
                foreach ($ev->documentUploadTrashed as $du) {
                    $du->restore();
                }
                foreach ($ev->editorVariantDocumentTrashed as $evd) {
                    $evd->restore();
                }
                $ev->restore();
            }

            // PDF generating procedure
            $variantPermissions = ViewHelper::documentVariantPermission($document);
            if ($variantPermissions->permissionExists == false) {
                session()->flash('message', trans('documentForm.noPermission'));

                return redirect('/');
            }

            $datePublished = new Carbon($document->date_published);
            $dateNow = $this->getGermanMonthName(intval($datePublished->format('m')));

            $dateNow .= ' '.$datePublished->format('Y');

            $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
            $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
            $mandantIdArr = $mandantId->toArray();
            $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
            $mandantRolesArr = $mandantRoles->toArray();

            $hasPermission = false;
            $variants = $variantPermissions->variants;

            $document = Document::find($id);
            $margins = $this->setPdfMargins($document);

            $or = 'P';
            if ($document->landscape == true) {
                $or = 'L';
            }
            $pdf = new PdfWrapper();
            $pdf->debug = true;

            if ($document->document_type_id == $this->isoDocumentId) {
                $pdf->SetHTMLHeader(view('pdf.headerIso', compact('document', 'variants', 'dateNow'))->render());
                $pdf->SetHTMLFooter(view('pdf.footerIso', compact('document', 'variants', 'dateNow'))->render());

                $render = view('pdf.documentIso', compact('document', 'variants', 'dateNow'))->render();
            } else {
                if ($document->document_template == 1) {
                    $render = view('pdf.document', compact('document', 'variants', 'dateNow'))->render();
                    $pdf->SetHTMLFooter(view('pdf.footer', compact('document', 'variants', 'dateNow'))->render());
                } else {
                    $render = view('pdf.new-layout-rund', compact('document', 'variants', 'dateNow'))->render();
                    $header = view('pdf.new-layout-rund-header', compact('document', 'variants', 'dateNow'))->render();
                    $footer = view('pdf.new-layout-rund-footer', compact('document', 'variants', 'dateNow'))->render();
                    $pdf->SetHTMLHeader($header);
                    $pdf->SetHTMLFooter($footer);
                }
            }
            // return $footer;
            $pdf->AddPage($or, $margins->left, $margins->right, $margins->top, $margins->bottom, $margins->headerTop, $margins->footerTop);
            $pdf->WriteHTML($render);

            // Delete document
            foreach ($document->editorVariant as $ev) {
                foreach ($ev->documentUpload as $du) {
                    $du->delete();
                }
                foreach ($ev->editorVariantDocument as $evd) {
                    $evd->delete();
                }
                $ev->delete();
            }
            $document->delete();

            // Return PDF
            return $pdf->stream();
        } else {
            return redirect('/papierkorb')->with('message', trans('documentForm.noPermission'));
        }
    }

    /**
     * Permanently delete trashed resources specified in the request.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyTrash(Request $request)
    {
        $data = $request->all();
        $documentsIds = $data['documentIds'];

        if (isset($data['delete'])) {
            if (count($documentsIds)) {
                foreach ($documentsIds as $id) {
                    $document = Document::onlyTrashed()->where('id', $id)->first();
                    $directory = $this->pdfPath.$document->id;
                    if (($document->id) && (\File::exists($directory))) {
                        \File::deleteDirectory($directory);
                    }
                    $document->forceDelete();
                }

                return back()->with('message', trans('documentForm.trashDeleted'));
            } else {
                return back()->with('message', trans('documentForm.noSelection'));
            }
        } elseif (isset($data['restore'])) {
            if (count($documentsIds)) {
                foreach ($documentsIds as $id) {
                    $document = Document::onlyTrashed()->where('id', $id)->first();
                    $document->restore();
                    foreach ($document->editorVariantTrashed as $ev) {
                        foreach ($ev->documentUploadTrashed as $du) {
                            $du->restore();
                        }
                        foreach ($ev->editorVariantDocumentTrashed as $evd) {
                            $evd->restore();
                        }
                        $ev->restore();
                    }
                }

                return back()->with('message', trans('documentForm.trashRestored'));
            } else {
                return back()->with('message', trans('documentForm.noSelection'));
            }
        } elseif (isset($data['empty-trash'])) {
            $trashed = Document::onlyTrashed()->get();
            foreach ($trashed as $markedForDeletion) {
                if (ViewHelper::universalDocumentPermission($markedForDeletion)) {
                    $directory = $this->pdfPath.$markedForDeletion->id;
                    if (($markedForDeletion->id) && (\File::exists($directory))) {
                        \File::deleteDirectory($directory);
                    }
                    $markedForDeletion->forceDelete();
                }
            }

            return back()->with('message', trans('documentForm.trashEmptied'));
        } else {
            return back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->canCreateEditDoc() == true) {
            $documentTypes = DocumentType::where('jurist_document', 0)->get(); // if struktur admin

            if ($this->returnRole() != false && $this->returnRole() == 11) { // 11 Rundschreiben Verfasser
                $documentTypes = DocumentType::where('document_art', 0)->where('jurist_document', 0)->get();
            } elseif ($this->returnRole() != false && $this->returnRole() == 13) { // 13 Dokumenten Verfasser
               $documentTypes = DocumentType::where('document_art', 1)->where('jurist_document', 0)->get();
            }

            $isoDocuments = IsoCategory::all();
            $documentStatus = DocumentStatus::all();
            $mandantUserRoles = MandantUserRole::where('role_id', 10)->pluck('mandant_user_id');
            $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
            $mandantUsers = MandantUser::whereIn('mandant_id', $mandantId)->get();
            $mandantUsers = $this->clearUsers($mandantUsers);

            $mandantUsers2 = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
            ->where('mandant_id', $mandantId)->get();

            $pluckIdMandantUsers = $mandantUsers->pluck('user_id')->toArray();
            $mandantUsers = User::whereIn('id', $pluckIdMandantUsers)->orderBy('last_name', 'asc')->get();

            $incrementedQmr = Document::where('document_type_id', $this->qmRundId)->orderBy('qmr_number', 'desc')->first();
            if (count($incrementedQmr) < 1) {
                $incrementedQmr = 1;
            } else {
                $incrementedQmr = $incrementedQmr->qmr_number;
                $incrementedQmr = $incrementedQmr + 1;
            }

            $incrementedIso = Document::where('document_type_id', $this->isoDocumentId)->orderBy('iso_category_number', 'desc')->first();
            if (count($incrementedIso) < 1) {
                $incrementedIso = 1;
            } else {
                $incrementedIso = $incrementedIso->iso_category_number;
                $incrementedIso = $incrementedIso + 1;
            }
            $documentCoauthors = $mandantUsers;

            //this is until Neptun inserts the documents
            $documentUsers = $mandantUsers;

            return view('formWrapper',
            compact('url', 'documentTypes', 'isoDocuments', 'documentStatus', 'mandantUsers', 'documentUsers', 'documentCoauthors', 'incrementedQmr', 'incrementedIso'));
        } else {
            session()->flash('message', trans('documentForm.noPermission'));

            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $adressats = Adressat::where('active', 1)->get();
        $docType = DocumentType::find($request->get('document_type_id'));

        //fix if document type not iso category -> don't save iso_category_id
        if ($request->get('document_type_id') != $this->isoDocumentId) {
            RequestMerge::merge(['iso_category_id' => null]);
        }

        if ($request->get('document_type_id') != $this->newsId && $request->get('document_type_id') != $this->rundId
           && $request->get('document_type_id') != $this->isoDocumentId && $request->get('document_type_id') != $this->qmRundId && $request->has('pdf_upload')) {
            RequestMerge::merge(['pdf_upload' => 0]);
        }

        if (!$request->has('date_published')) {
            RequestMerge::merge(['date_published' => Carbon::now()->addDay()->format('d.m.Y')]);
        }

        RequestMerge::merge(['version' => 1]);

        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload'));

        if (!$request->has('name_long')) {
            RequestMerge::merge(['name_long' => $request->get('name')]);
        }

        if (!$request->has('betreff')) {
            RequestMerge::merge(['betreff' => $request->get('name_long')]);
        }

        $data = Document::create($request->all());

        $lastId = Document::orderBy('id', 'DESC')->first();
        $lastId->document_group_id = $lastId->id;
        $lastId->save();

        if ($request->has('document_coauthor') && $request->input('document_coauthor')[0] != '0'
        && $request->input('document_coauthor')[0] != 0) {
            $coauthors = $request->input('document_coauthor');
            foreach ($coauthors as $coauthor) {
                if ($coauthor != '0');
            }
            DocumentCoauthor::create(['document_id' => $lastId->id, 'user_id' => $coauthor]);
        }

        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = '/dokumente/'.$data->id.'/edit';
        session()->flash('message', trans('documentForm.documentCreateSuccess'));

        return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function pdfUpload(Request $request)
    {
        $dirty = false;
        $model = Document::find($request->get('model_id'));
        if ($model == null) {
            return redirect('dokumente/create')->with(array('message' => 'No Document with that id'));
        }
        // dd($request->all() );
        if ($request->file()) {
            $files = $request->file();
            if( $files['files'][0] != null && ViewHelper::fileTypeAllowed($files['files'][0],['pdf']) == false ){
                return redirect('/dokumente/pdf-upload/'.$model->id.'/edit')->with('messageSecondary',
                trans('documentForm.onlyPDF'));
            }
        }
        
        if ($request->has('betreff')) {
            $model->betreff = $request->get('betreff');
            $dirty = $this->dirty($dirty, $model);
            $model->save();
        }

        $filename = '';
        $path = $this->pdfPath;
        if ($request->file()) {
            $fileNames = $this->fileUpload($model, $path, $request->file());
        }
        //not summary, it is inhalt + attachment
        RequestMerge::merge(['pdf_upload' => 1/*maybe auto*/]);
        $data = Document::findOrNew($request->get('model_id'));

        $data->fill($request->all());
        $dirty = $this->dirty($dirty, $data);
        $data->save();
        $id = $data->id;

        RequestMerge::merge(['document_id' => $id, 'variant_number' => 1/*maybe auto*/]);
        $editorVariantId = EditorVariant::where('document_id', $id)->first();
        if ($editorVariantId == null) {
            $editorVariantId = new EditorVariant();
        }
        $editorVariantId->document_id = $id;
        $editorVariantId->variant_number = 1;
        $editorVariantId->inhalt = $request->get('inhalt');
        $dirty = $this->dirty($dirty, $editorVariantId);
        $editorVariantId->save();
        $editorVariantId::where('document_id', $id)->first();

        if (count($fileNames) > 0) {
            $folderName = $this->movePath.'/'.str_slug($model->name);

            foreach ($model->documentUploads as $oldUpload) {
                $filePath = $folderName.'/'.$oldUpload->file_path;
                \File::delete($filePath);
                $oldUpload->delete();
            }
            foreach ($fileNames as $fileName) {
                $documentAttachment = new DocumentUpload();
                $documentAttachment->editor_variant_id = $editorVariantId->id;
                $documentAttachment->file_path = $fileName;
                $documentAttachment->save();
                $dirty = true;
            }
        }
        if ($dirty == true) {
            session()->flash('message', trans('documentForm.documentPdfCreateSuccess'));
        }
        if ($request->has('save')) {
            $adressats = Adressat::where('active', 1)->get();
            $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = '/dokumente/'.$data->id.'/edit';

            return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
        }
        $backButton = '/dokumente/pdf-upload/'.$data->id.'/edit';

        /* Preview link preparation */
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = '/dokumente/'.$data->id.'/edit';
        $adressats = Adressat::where('active', 1)->get();
        $currentVariant = 0;
        $previewUrl = '';
        if ($request->has('current_variant')) {
            $currentVariant = $request->get('current_variant');
        }

        if ($request->has('preview') && $currentVariant != 0) {
            $previewUrl = url('dokumente/ansicht/'.$id.'/'.$currentVariant);

            return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats', 'previewUrl'));
        }

        if ($request->has('pdf_preview') && $currentVariant != 0) {
            $previewUrl = url('dokumente/ansicht-pdf/'.$id.'/'.$currentVariant);

            return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats', 'previewUrl'));
        }
        /* End Preview link preparation */

        if ($request->has('attachment')) {
            return redirect('dokumente/anlagen/'.$id);
        }

        return redirect('dokumente/rechte-und-freigabe/'.$id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editPdfUpload($id)
    {
        $adressats = Adressat::where('active', 1)->get();
        $data = Document::find($id);
        $backButton = '/dokumente/'.$data->id.'/edit';
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
        $url = $setDocument->url;
        $form = $setDocument->form;

        return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentUpload(Request $request)
    {
        $model = Document::find($request->get('model_id'));
        if ($model == null) {
            return redirect('dokumente/create')->with(array('message' => 'No Document with that id'));
        }

        $filename = '';
        $path = $this->pdfPath;
        if ($request->file()) {
            $fileNames = $this->fileUpload($model, $path, $request->file());
        }
        //not summary, it is inhalt + attachment
        $data = Document::findOrNew($request->get('model_id'));
        $data->fill($request->all());
        $dirty = $this->dirty(false, $data);
        $data->save();
        $id = $data->id;

        $counter = 0;
        if (isset($fileNames) && count($fileNames) > 0) {
            foreach ($fileNames as $fileName) {
                ++$counter;
                //Editor variant  upload
                $editorVariantId = EditorVariant::where('document_id', $id)->where('variant_number', $counter)->first();
                if ($editorVariantId == null) {
                    $editorVariantId = new EditorVariant();
                }
                $editorVariantId->document_id = $id;
                $editorVariantId->variant_number = $counter;
                $editorVariantId->inhalt = $request->get('inhalt');
                $dirty = $this->dirty($dirty, $editorVariantId);
                $editorVariantId->save();
                //Upload documents

                $documentAttachment = new DocumentUpload();
                $documentAttachment->editor_variant_id = $editorVariantId->id;
                $documentAttachment->file_path = $fileName;
                $dirty = $this->dirty($dirty, $documentAttachment);
                $documentAttachment->save();
            }
        }
        if ($dirty == true) {
            session()->flash('message', trans('documentForm.documentUploadedCreateSuccess'));
        }
        if ($request->has('save')) {
            $adressats = Adressat::where('active', 1)->get();
            $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = '/dokumente/'.$data->id.'/edit';

            return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
        }
        if ($request->has('attachment')) {
            return redirect('dokumente/anlagen/'.$id);
        }

        return redirect('dokumente/rechte-und-freigabe/'.$id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editDocumentUpload($id)
    {
        $adressats = Adressat::where('active', 1)->get();
        $data = Document::find($id);
        $backButton = '/dokumente/'.$data->id.'/edit';
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
        $url = $setDocument->url;
        $form = $setDocument->form;

        return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function documentEditor(Request $request)
    {
        $model = Document::find($request->get('model_id'));
        if ($model == null) {
            return redirect('dokumente/create')->with(array('message' => 'No Document with that id'));
        }
        //fix pdf checkbox
        if (!$request->has('show_name')) {
            RequestMerge::merge(['show_name' => 0]);
        }

        $data = Document::findOrNew($request->get('model_id'));
        $data->fill($request->all());
        $data->save();
        $dirty = $this->dirty(false, $data);
        $id = $data->id;

        if ($request->has('betreff')) {
            $model->betreff = $request->get('betreff');
            $dirty = $this->dirty($dirty, $model);
            $model->save();
        }

        $savedVariantIds = array();
        //check if has variant first count

        foreach ($request->all() as $k => $v) {
            if (strpos($k, 'variant-') !== false) {
                $variantNumber = $this->document->variantNumber($k);
                $editorVariant = EditorVariant::where('document_id', $id)->where('variant_number', $variantNumber)->first();
                if ($editorVariant == null) {
                    $editorVariant = new EditorVariant();
                }
                $editorVariant->document_id = $id;
                $editorVariant->variant_number = $variantNumber;
                $editorVariant->inhalt = $v;
                $dirty = $this->dirty($dirty, $editorVariant);
                $editorVariant->save();
                $savedVariantIds[] = $editorVariant->variant_number;
            }
        }

            /*If some variant are removed*/
             $removeEditors = EditorVariant::where('document_id', $id)->whereNotIn('variant_number', $savedVariantIds)->get();
            //  dd($removeEditors);
            foreach ($removeEditors as $editor) {
                if ($editor->deleted_at == null) {
                    $editor->delete();
                }
            }
            /*end some variant are removed*/
         if ($dirty == true) {
             session()->flash('message', trans('documentForm.documentEditorCreateSuccess'));
         }

        if ($request->has('save')) {
            $adressats = Adressat::where('active', 1)->get();
            $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = '/dokumente/'.$data->id.'/edit';

            return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
        }
        /* Preview link preparation */
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = '/dokumente/'.$data->id.'/edit';
        $adressats = Adressat::where('active', 1)->get();
        $currentVariant = 0;
        $previewUrl = '';
        if ($request->has('current_variant')) {
            $currentVariant = $request->get('current_variant');
        }

        if ($request->has('preview') && $currentVariant != 0) {
            $previewUrl = url('dokumente/ansicht/'.$id.'/'.$currentVariant);

            return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats', 'previewUrl'));
        }

        if ($request->has('pdf_preview') && $currentVariant != 0) {
            $previewUrl = url('dokumente/ansicht-pdf/'.$id.'/'.$currentVariant);

            return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats', 'previewUrl'));
        }
        /* End Preview link preparation */

        if ($request->has('attachment')) {
            return redirect('dokumente/anlagen/'.$id);
        }

        return redirect('dokumente/rechte-und-freigabe/'.$id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editDocumentEditor($id)
    {
        $adressats = Adressat::where('active', 1)->get();
        $data = Document::find($id);
        $backButton = '/dokumente/'.$data->id.'/edit';
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload);
        $url = $setDocument->url;
        $form = $setDocument->form;

        return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attachments($id, $preparedVariant = 1)
    {
        $data = Document::find($id);
        /* Trigger document visibility */

        if (ViewHelper::universalDocumentPermission($data) == false) {
            session()->flash('message', trans('documentForm.noPermission'));

            return redirect('/');
        }

        $dt = DocumentType::find($this->formulareId); //vorlage document

        $backButton = '/dokumente/editor/'.$data->id.'/edit';
        if (($data->document_type_id == $this->newsId && $data->pdf_upload == true) || ($data->document_type_id == $this->rundId
        && $data->pdf_upload == true) || ($data->document_type_id == $this->qmRundId && $data->pdf_upload == true)) {
            $backButton = '/dokumente/pdf-upload/'.$id.'/edit';
        } elseif ($data->document_type_id == $dt->id) {
            $backButton = '/dokumente/dokumente-upload/'.$id.'/edit';
        }

        $nextButton = '/dokumente/rechte-und-freigabe/'.$data->id;
        $url = '';
        $documents = Document::where('document_type_id', $this->formulareId)
        ->where('document_status_id', 1)->orWhere('document_status_id', 3)->whereNotIn('id', array($id))->get(); // documentTypeId 5 = Vorlagedokument
        foreach ($documents as $document) {
            $document->name = $document->name.' ('.$document->documentStatus->name.')';
        }

        $mandantId = MandantUser::where('user_id', Auth::user()->id)->first()->mandant_id;
        $attachmentArray = array();
        /*Check if document has editorVariant*/

        if (count($data->editorVariantNoDeleted) > 0) {
            foreach ($data->editorVariantNoDeleted as $variant) {
                $attachmentArray[$variant->id] = $this->document->getAttachedDocumentLinks($variant, $id);
            }
        }

        /*End Check if document has attachments*/

        $url = '';
        $documentTypes = DocumentType::where('document_art', 1)->get();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id', 10)->pluck('mandant_user_id');
        $uploadTypes = DocumentType::where('document_art', 1)->pluck('id');
        $documentsFormulare = Document::whereIn('document_type_id', $uploadTypes)->whereIn('document_status_id', array(1, 3))->where('active', 1)->get();
        foreach ($documentsFormulare as $df) {
            $df->name = $df->name.' ('.$df->documentStatus->name.')';
        }
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)->get();
        $mandantUsers = $this->clearUsers($mandantUsers);

        $pluckIdMandantUsers = $mandantUsers->pluck('user_id')->toArray();
        $mandantUsers = User::whereIn('id', $pluckIdMandantUsers)->orderBy('last_name', 'asc')->get();

        $collections = array();
        $roles = Role::all();
        //find variants
        $variants = EditorVariant::where('document_id', $data->id)->get();
        $documentStatus = DocumentStatus::all();

        $incrementedQmr = Document::where('document_type_id', $this->qmRundId)->orderBy('qmr_number', 'desc')->first();
        if ($incrementedQmr == null || $incrementedQmr->qmr_number == null) {
            $incrementedQmr = 1;
        } else {
            $incrementedQmr = $incrementedQmr->qmr_number;
            $incrementedQmr = $incrementedQmr + 1;
        }

        $incrementedIso = Document::where('document_type_id', $this->isoDocumentId)->orderBy('iso_category_number', 'desc')->first();
        if (count($incrementedIso) < 1 || $data == null) {
            $incrementedIso = 1;
        } else {
            $incrementedIso = $incrementedIso->iso_category_number;
            $incrementedIso = $incrementedIso + 1;
        }

        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        $mandantUsers = MandantUser::distinct('user_id')->whereIn('mandant_id', $mandantId)->get();
        $mandantUsers = $this->clearUsers($mandantUsers);

        $pluckIdMandantUsers = $mandantUsers->pluck('user_id')->toArray();
        $mandantUsers = User::whereIn('id', $pluckIdMandantUsers)->orderBy('last_name', 'asc')->get();

        $documentCoauthor = $mandantUsers;

        //this is until Neptun inserts the documents
        $documentUsers = $mandantUsers;

        return view('dokumente.attachments', compact('collections', 'data', 'data2', 'attachmentArray', 'documents', 'documentsFormulare', 'documentStatus', 'url', 'documentTypes',
        'isoDocuments', 'mandantUsers', 'documentUsers', 'backButton', 'nextButton', 'preparedVariant', 'incrementedQmr', 'incrementedIso'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveAttachments(Request $request, $id, $preparedVariant = 1)
    {
        // option 1-> dodat dokument kao attachmet za trenutnu variantu i vorlage
        // option 2-> kreira se skroz novi dokument, ali se dodaje kao attchment postojećem
        //document attachment is a record in editor_variants && editor_variant_document
        $data = Document::find($id);

        if (!$request->has('date_published')) {
            RequestMerge::merge(['date_published' => Carbon::now()->addDay()]);
        }

        $currentEditorVariant = $request->get('variant_id');

        $document = Document::find($request->get('document_id'));

        /*If option 1*/
            if ($request->has('attach')) {
                $currentDocumentLast = $data->lastEditorVariant[0];

                    /*
                        Opcija 1 razložena
                        za svaki document upload od drugog documenta
                        dodaj i editor_variant_documents --->Kako razlikovat attachmente od dokumenata ako je ista varijanta?
                        document_id u editor_variant_documents je foreign key!
                        i documentUploads uzmi path od drugog dokumenta i stavi editor_variant_id od trenutnog
                    */
                $currDocEv = EditorVariant::find($currentEditorVariant);

                $documentCheck = EditorVariantDocument::where('editor_variant_id', $currentEditorVariant)->where('document_id', $document->id)->count();
                if ($documentCheck < 1) {
                    $newAttachment = new EditorVariantDocument();
                    $newAttachment->editor_variant_id = $currentEditorVariant;
                    $newAttachment->document_id = $document->id;
                    $newAttachment->document_status_id = 1;
                    $newAttachment->save();
                }
            }

        /*If option 2*/
        else {
            /*
                Option 2: create a new Vorlagedokument and add it as an attachment
            */
            //  dd($request->all());
            ///$dt = DocumentType::find(  $this->formulareId = $this->formulareId);//vorlage document
             $uid = Auth::user()->id;
            // RequestMerge::merge(['version' => 1, 'document_type_id' => $dt->id,'is_attachment'=> 1] );
            RequestMerge::merge(['version' => 1, 'is_attachment' => 1, 'user_id' => $uid, 'user_owner_id' => $uid]);

            if (!$request->has('name_long')) {
                RequestMerge::merge(['name_long' => $request->get('name')]);
            }

            if (!$request->has('betreff')) {
                RequestMerge::merge(['betreff' => $request->get('name_long')]);
            }

            /*Create a new document*/
            $data = Document::create($request->all());
            $lastId = Document::orderBy('id', 'DESC')->first();
            $lastId->document_group_id = $lastId->id;
            $lastId->save();

            if ($request->has('document_coauthor') && $request->input('document_coauthor')[0] != '0'
            && $request->input('document_coauthor')[0] != 0) {
                $coauthors = $request->input('document_coauthor');
                foreach ($coauthors as $coauthor) {
                    if ($coauthor != '0');
                }
                DocumentCoauthor::create(['document_id' => $lastId->id, 'user_id' => $coauthor]);
            }

            /*Upload document files*/
            $filename = '';
            $path = $this->pdfPath;
            if ($request->file()) {
                $fileNames = $this->fileUpload($lastId, $path, $request->file());
            }

               //not summary, it is inhalt + attachment

            $counter = 0;
            if (isset($fileNames) && count($fileNames) > 0) {
                foreach ($fileNames as $fileName) {
                    ++$counter;
                    //Editor variant  upload
                    $editorVariantId = new EditorVariant();
                    $editorVariantId->document_id = $lastId->id;
                    $editorVariantId->variant_number = $counter;
                    $editorVariantId->inhalt = $request->get('inhalt');
                    $dirty = $this->dirty(false, $editorVariantId);
                    $editorVariantId->save();

                    $documentAttachment = new DocumentUpload();
                    $documentAttachment->editor_variant_id = $editorVariantId->id;
                    $documentAttachment->file_path = $fileName;
                    $dirty = $this->dirty($dirty, $documentAttachment);
                    $documentAttachment->save();
                }
            }
                /*end upload files*/

                $currDocEv = EditorVariant::find($currentEditorVariant);

            $newAttachment = new EditorVariantDocument();
            $newAttachment->editor_variant_id = $currentEditorVariant;
            $newAttachment->document_id = $lastId->id;
            $newAttachment->document_status_id = 1;
            $newAttachment->save();

            $adressats = Adressat::where('active', 1)->get();
            $docType = DocumentType::find($request->get('document_type_id'));

            $backButton = '/dokumente/'.$data->id.'/edit';
        }

        if ($request->has('next')) {
            return redirect('dokumente/rechte-und-freigabe/'.$id);
        }

    //   return redirect()->action('DocumentController@attachments', $id,$variant);
       return redirect('dokumente/anlagen/'.$id.'/'.$preparedVariant);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anlegenRechteFreigabe($id, $backButton = null)
    {
        $data = Document::find($id);

        // NEPTUN-815, NEPTUN-817
        // if(in_array($data->document_status_id, [2, 6])) return back();

        $dt = DocumentType::find($this->formulareId); //vorlage document

        $backButton = '/dokumente/editor/'.$data->id.'/edit';
        if (($data->document_type_id == $this->rundId && $data->pdf_upload == true) || ($data->document_type_id == $this->qmRundId && $data->pdf_upload == true)
        || ($data->document_type_id == $this->newsId && $data->pdf_upload == true)) {
            $backButton = '/dokumente/pdf-upload/'.$id.'/edit';
        } elseif ($data->document_type_id == $dt->id) {
            $backButton = '/dokumente/dokumente-upload/'.$id.'/edit';
        }

        if ($data->pdf_upload == true || $data->pdf_upload == 1) {
            $backButton = '/dokumente/pdf-upload/'.$data->id.'/edit';
        } elseif ($data->pdf_upload == false && $data->document_type_id == $this->formulareId) {
            $backButton = '/dokumente/dokumente-upload/'.$data->id.'/edit';
        }

        $collections = array();
        $roles = Role::all();
        //find variants
        $variants = EditorVariant::where('document_id', $data->id)->get();

        $mandantUserRoles = MandantUserRole::where('role_id', 10)->pluck('mandant_user_id');
        $mandantUsersTable = MandantUser::whereIn('id', $mandantUserRoles)->pluck('user_id');
        $mandantUsers = User::whereIn('id', $mandantUsersTable)->orderBy('last_name', 'asc')->get();
        $mandants = Mandant::whereNull('deleted_at')->orderBy('mandant_number','asc')->get();

        $documentMandats = DocumentMandant::where('document_id', $data->id)->get();
        foreach ($variants as $variant) {
            $variant->hasPreviousData = false;
            foreach ($mandants as $mandant) {
                // $selected = ViewHelper::setComplexMultipleSelect($variant,'documentMandantMandants', $mandant->id, 'mandant_id',true);
            }
        }

        return view('dokumente.anlegenRechteFreigabe', compact('collections',
        'mandants', 'mandantUsers', 'variants', 'roles', 'data', 'backButton'));
    }

    /**
     * Process the Rechte und freigabe request.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function saveRechteFreigabe(Request $request, $id)
    {
        $variants = EditorVariant::where('document_id', $id)->count();
        $countVariants = 0;
        foreach ($request->all() as $k => $v) {
            /* If Variants are not empty */
            if (strpos($k, 'variante-') !== false && !empty($v)) {
                ++$countVariants;
            }
        }
        if ($variants > 1 && $countVariants != $variants) {
            session()->flash('message', 'Bitte wählen Sie bei jeder Variante einen Mandaten aus.');
            session()->flash('alert-class', 'alert-danger');

            return redirect()->back()->withInput($request->all());
        }
        $document = Document::find($id);
        if ($request->get('roles') != null && count($request->get('roles')) == 1 && in_array('Alle', $request->get('roles'))) {
            $document->approval_all_roles = 1;
            $document->email_approval = $request->get('email_approval');
            $dirty = $this->dirty(false, $document);
            $document->save();
            //Process document approval users
        } else {
            $document->approval_all_roles = 0;
            $dirty = $this->dirty(false, $document);
            $document->save();
            if (!count($request->get('roles')) || ($request->get('roles') != null && in_array('Alle', $request->get('roles')))) {
                if (is_null($request->get('roles'))) {
                    $request->merge(array('roles' => array('Alle' => 'Alle')));
                }
                $key = array_search('Alle', $request->get('roles'));
                $tempArr = $request->get('roles');
                unset($tempArr[$key]);
                $request->merge(array('roles' => $tempArr));
            }
        }

        $documentApproval = DocumentApproval::where('document_id', $id)->get();
        $documentApprovalPluck = DocumentApproval::where('document_id', $id)->pluck('user_id');

        // NEPTUN-871
        // Add check for document status
        if ($document->document_status_id == 1) {
            //Process document approval users
            //do document approvals need soft delete
            if (!empty($request->get('approval_users'))) {
                $this->document->processOrSave($documentApproval, $documentApprovalPluck, $request->get('approval_users'),
                'DocumentApproval', array('user_id' => 'inherit', 'document_id' => $id), array('document_id' => array($id)));
            } else {
                $documentApproval = DocumentApproval::where('document_id', $id)->delete();
            }
        }

        //check if has variant
        $hasVariants = false;
        $processedArray = array();
        /* If variants exist in request */
        foreach ($request->all() as $k => $v) {
            /* If Variants are not empty */
            if (strpos($k, 'variante-') !== false && !empty($v)) {
                $hasVariants = true;
                $variantNumber = $this->document->variantNumber($k);
                $processedArray[] = $variantNumber;

                if (in_array('Alle', $request->get($k)) && count($request->get($k)) <= 1) {
                    $editorVariant = EditorVariant::where('document_id', $id)->where('variant_number', $variantNumber)->first();
                    if ($editorVariant) {
                        $editorVariant->approval_all_mandants = 1;
                        $dirty = $this->dirty($dirty, $editorVariant);
                        $editorVariant->save();

                    /*Fix where where variant is Alle and roles different from All*/
                    $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();

                        if (count($documentMandants)) {
                            foreach ($documentMandants as $documentMandant) {
                                $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->get();
                                $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->pluck('role_id');

                                if ($request->has('roles')) {
                                    $this->document->processOrSave($documentMandantRoles, $documentMandantRolesPluck, $request->get('roles'), 'DocumentMandantRole',
                                array('document_mandant_id' => $documentMandant->id, 'role_id' => 'inherit'),
                                array('document_mandant_id' => array($documentMandant->id)));
                                } elseif (!$request->has('roles')) {
                                    $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->delete();
                                }
                            }//end foreach
                        } else {
                            $dM = DocumentMandant::create(array('document_id' => $id, 'editor_variant_id' => $editorVariant->id));
                            $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();
                            foreach ($documentMandants as $documentMandant) {
                                $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->get();
                                $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->pluck('role_id');

                                if ($request->has('roles')) {
                                    $this->document->processOrSave($documentMandantRoles, $documentMandantRolesPluck, $request->get('roles'), 'DocumentMandantRole',
                                array('document_mandant_id' => $documentMandant->id, 'role_id' => 'inherit'),
                                array('document_mandant_id' => array($documentMandant->id)));
                                } elseif (!$request->has('roles')) {
                                    $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->delete();
                                }
                            }//end foreach
                        }
                    }
                     /* End Fix where where variant is Alle and roles different from All*/
                } else {
                    //editorVariant insert/edit
                    $editorVariant = EditorVariant::where('document_id', $id)->where('variant_number', $variantNumber)->first();

                    /*Fix when you remove document variant error*/
                    if (is_null($editorVariant)) {
                        $editorVariant = EditorVariant::where('document_id', $id)->where('variant_number', $variantNumber + 1)->first();
                        $editorVariant->variant_number = $variantNumber;
                        $editorVariant->save();
                    }
                    $editorVariant->approval_all_mandants = 0;
                    $dirty = $this->dirty($dirty, $editorVariant);
                    $editorVariant->save();

                    /*Create DocumentManant */
                    $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();

                    if (count($documentMandants) < 1) {
                        $documentMandant = new DocumentMandant();
                        $documentMandant->document_id = $id;
                        $documentMandant->editor_variant_id = $editorVariant->id;
                        $dirty = $this->dirty($dirty, $documentMandant);
                        $documentMandant->save();
                        $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();
                    }
                   /*End Create DocumentManant */

                    /* Create DocumentManant roles*/

                    foreach ($documentMandants as $documentMandant) {
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->get();
                        $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->pluck('role_id');
                        // dd('brejk2');

                        if ($request->has('roles')) {
                            $this->document->processOrSave($documentMandantRoles, $documentMandantRolesPluck, $request->get('roles'), 'DocumentMandantRole',
                            array('document_mandant_id' => $documentMandant->id, 'role_id' => 'inherit'),
                            array('document_mandant_id' => array($documentMandant->id)));
                        } elseif (!$request->has('roles')) {
                            $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->delete();
                        }
                    }

                    /*End Create DocumentManant roles*/

                    /* Create DocumentManant mandant*/
                    foreach ($documentMandants as $documentMandant) {
                        $documentMandantMandats = DocumentMandantMandant::where('document_mandant_id', $documentMandant->id)->get();
                        $documentMandantMandatsPluck = DocumentMandantMandant::where('document_mandant_id', $documentMandant->id)->pluck('mandant_id');

                      //INSERTS LAST VALUES->check foreach!

                      $this->document->processOrSave($documentMandantMandats, $documentMandantMandatsPluck, $request->get($k),
                      'DocumentMandantMandant', array('document_mandant_id' => $documentMandant->id, 'mandant_id' => 'inherit'),
                            array('document_mandant_id' => array($documentMandant->id)), true);
                    }
                    /*End Create DocumentManant mandant*/
                }//end else
            }
        }

        /*if removed mandants from variant return bool approval_all_mandants to true*/
            $editorVariants = EditorVariant::where('document_id', $id)->whereNotIn('variant_number', $processedArray)->get();

        foreach ($editorVariants as $ev) {
            $ev->approval_all_mandants = 1;
            $dirty = $this->dirty($dirty, $ev);
            $ev->save();
        }
        /*End if removed mandants from variant return bool approval_all_mandants to true*/

        /* End If variants exist in request */

        //fix when there are roles set, but no variants

        if ($hasVariants == false && $request->has('roles')) {
            $editorVariantsNumbers = EditorVariant::where('document_id', $id)->get();

            foreach ($editorVariantsNumbers as $editorVariant) {
                $variantNumber = $editorVariant->variant_number;

                $editorVariant->approval_all_mandants = 1;
                $dirty = $this->dirty($dirty, $editorVariant);
                $editorVariant->save();

                    /*Create DocumentManant */
                    $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();

                if (count($documentMandants) < 1) {
                    $documentMandant = new DocumentMandant();
                    $documentMandant->document_id = $id;
                    $documentMandant->editor_variant_id = $editorVariant->id;
                    $dirty = $this->dirty($dirty, $documentMandant);
                    $documentMandant->save();
                    $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();
                }
                    /*End Create DocumentManant */

                    /* Create DocumentManant roles*/
                    foreach ($documentMandants as $documentMandant) {
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->get();
                        $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->pluck('role_id');

                        $this->document->processOrSave($documentMandantRoles, $documentMandantRolesPluck, $request->get('roles'), 'DocumentMandantRole',
                            array('document_mandant_id' => $documentMandant->id, 'role_id' => 'inherit'),
                            array('document_mandant_id' => array($documentMandant->id)));
                    }
                    /*End Create DocumentManant roles*/

                    /* Delete variant mandants*/

                        $documentMandantMandats = DocumentMandantMandant::where('document_mandant_id', $documentMandant->id)->delete();
                    /* End Delete variant mandants*/
            }
        }//end has variants false and has roles

        /*fix where roles aren't set and variants aren't set*/
        elseif ($hasVariants == false && !$request->has('roles')) {
            $editorVariantsNumbers = EditorVariant::where('document_id', $id)->get();

            foreach ($editorVariantsNumbers as $editorVariant) {
                $variantNumber = $editorVariant->variant_number;

                $editorVariant->approval_all_mandants = 1;
                $dirty = $this->dirty($dirty, $editorVariant);
                $editorVariant->save();

                    /*Create DocumentManant */
                    $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();

                if (count($documentMandants) < 1) {
                    $documentMandant = new DocumentMandant();
                    $documentMandant->document_id = $id;
                    $documentMandant->editor_variant_id = $editorVariant->id;
                    $dirty = $this->dirty($dirty, $documentMandant);
                    $documentMandant->save();
                    $documentMandants = DocumentMandant::where('document_id', $id)->where('editor_variant_id', $editorVariant->id)->get();
                }
                    /*End Create DocumentManant */

                    /* Delete DocumentManant roles*/

                    foreach ($documentMandants as $documentMandant) {
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id', $documentMandant->id)->delete();

                    /*End Delete DocumentManant roles*/

                    /* Delete variant mandants*/
                        $documentMandantMandats = DocumentMandantMandant::where('document_mandant_id', $documentMandant->id)->delete();
                    /* End Delete variant mandants*/
                    }
            }
        }
        /* End fix where roles aren't set and variants aren't set*/

        $document = Document::find($id);
        if ($request->has('email_approval')) {
            $document->email_approval = 1;
        }

        if ($request->has('fast_publish')) {
            //save to Published documents
            $document->document_status_id = 3; //aktualan
            $dirty = $this->dirty($dirty, $document);
            $document->save();

            $publishedDocs = $this->publishProcedure($document, false);

            $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                            ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
            if ($readDocument != null && $readDocument->deleted_at == null) {
                $readDocument->delete();
            }

            $otherDocuments = Document::where('document_group_id', $document->document_group_id)
                                ->whereNotIn('id', array($document->id))->get();
            /*Set attached documents as actuell */
            $variantsAttachedDocuments = EditorVariant::where('document_id', $document->id)->get();
            foreach ($variantsAttachedDocuments as $vad) {
                $editorVariantDocuments = $vad->editorVariantDocument;
                foreach ($editorVariantDocuments as $evd) {
                    $evd->document_status_id = 3;
                    $evd->save();
                    $doc = Document::find($evd->document_id);
                    $doc->document_status_id = 3;
                    $doc->save();
                }
            }
            /* End set attached documents as actuell */

            foreach ($otherDocuments as $oDoc) {
                if ($oDoc->document_status_id != 6 && $oDoc->document_status_id != 2) {
                    $oDoc->document_status_id = 5;
                    $oDoc->save();
                }
            }

            if ($dirty == true) {
                UserReadDocument::where('document_group_id', $document->published->document_group_id)->delete();
                session()->flash('message', trans('documentForm.fastPublished'));
            }

            // NEPTUN-870
            // When fast publishing - Add the entry for the fast-publisher
            DocumentApproval::create(array('document_id' => $document->id, 'user_id' => Auth::user()->id,
                'date_approved' => Carbon::now(), 'approved' => true, 'fast_published' => true, ));

            return redirect('/');
        } // end fast publish
        elseif ($request->has('fast_publish_send')) {
            //save to Published documents
            $document->document_status_id = 3; //aktualan
            $dirty = $this->dirty($dirty, $document);
            $document->save();

            $publishedDocs = $this->publishProcedure($document, true);

            $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                            ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
            if ($readDocument != null && $readDocument->deleted_at == null) {
                $readDocument->delete();
            }

            $otherDocuments = Document::where('document_group_id', $document->document_group_id)
                                ->whereNotIn('id', array($document->id))->get();
            /*Set attached documents as actuell */
            $variantsAttachedDocuments = EditorVariant::where('document_id', $document->id)->get();
            foreach ($variantsAttachedDocuments as $vad) {
                $editorVariantDocuments = $vad->editorVariantDocument;
                foreach ($editorVariantDocuments as $evd) {
                    $evd->document_status_id = 3;
                    $evd->save();
                    $doc = Document::find($evd->document_id);
                    $doc->document_status_id = 3;
                    $doc->save();
                }
            }
            /* End set attached documents as actuell */

            foreach ($otherDocuments as $oDoc) {
                if ($oDoc->document_status_id != 6 && $oDoc->document_status_id != 2) {
                    $oDoc->document_status_id = 5;
                    $oDoc->save();
                }
            }

            if ($dirty == true) {
                UserReadDocument::where('document_group_id', $document->published->document_group_id)->delete();
                session()->flash('message', trans('documentForm.fastPublished'));
            }

            // NEPTUN-870
            // When fast publishing - Add the entry for the fast-publisher
            DocumentApproval::create(array('document_id' => $document->id, 'user_id' => Auth::user()->id,
                'date_approved' => Carbon::now(), 'approved' => true, 'fast_published' => true, ));

            return redirect('/');
        } // end fast publish with sending
        elseif ($request->has('ask_publishers')) {
            // NEPTUN-815, NEPTUN-817
            // if($document->document_status_id != 1) return back();

            $document->document_status_id = 6;

            //if send email-> send emails || messages
            $dirty = $this->dirty($dirty, $document);
            $document->save();

            if ($request->has('approval_users')) {
                $approvals = DocumentApproval::where('document_id', $id)->delete();
                foreach ($request->get('approval_users') as $approvalUser) {
                    $approvalUser = DocumentApproval::create(array('document_id' => $id, 'user_id' => $approvalUser));
                    if ($request->has('email_approval')) {
                        ViewHelper::notifyFreigeber($approvalUser);
                    }
                    $dirty = true;
                }
            }

            if ($dirty == true) {
                session()->flash('message', trans('documentForm.askPublishers'));
            }

            return redirect('/');
        } elseif ($request->has('reset_approval')) {
            // NEPTUN-815, NEPTUN-817
            $document->document_status_id = 1;

            $dirty = $this->dirty($dirty, $document);
            $document->save();

            $approvals = DocumentApproval::where('document_id', $id)->delete();
            $dirty = true;

            if ($dirty == true) {
                session()->flash('message', trans('documentForm.approvalReset'));
            }

            return redirect('/');
        } else {
            //just refresh the page
            $dirty = $this->dirty($dirty, $document);
            $document->save();

            if ($dirty == true) {
                session()->flash('message', trans('documentForm.saved'));
            }

            return redirect('dokumente/rechte-und-freigabe/'.$id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datePublished = null;
        $document = Document::find($id);
        $initialUrl = $id;
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->orderBy('id', 'DESC')->first();
            if (is_null($publishedDocs)) {
                return redirect('/')->with('messageSecondary', trans('documentForm.documentUnAvailable'));
            }
            $id = $publishedDocs->document_id;
            $datePublished = $publishedDocs->created_at;
            $document = Document::find($id);
            if (is_null($document)) {
                return redirect('/')->with('messageSecondary', trans('documentForm.documentUnAvailable'));
            }
            /*Published hotfix*/
            if ($document->date_published == null) {
                $doc = Document::find($document->id);
                $doc->date_published = $doc->created_at->addDay();
                $doc->save();
                $document->date_published = $document->created_at;
            }
            /*Published hotfix*/

             if ($document->document_status_id == 5 && ViewHelper::universalHasPermission(array(14)) == true) {
                 return redirect('dokumente/'.$document->id);
             } elseif ($document->document_status_id == 5 && ViewHelper::universalHasPermission(array(14)) == false) {
                 return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
             }
            // add UserReadDocumen

            $readDocs = UserReadDocument::where('document_group_id', $publishedDocs->document_group_id)
                    ->where('user_id', Auth::user()->id)->get();
            $dateReadBckp = '';

            if (count($readDocs) == 0) {
                UserReadDocument::create([
                    'document_group_id' => $publishedDocs->document_group_id,
                    'user_id' => Auth::user()->id,
                    'date_read' => Carbon::now(),
                    'date_read_last' => Carbon::now(),
                ]);
            } else {
                foreach ($readDocs as $readDoc) {
                    $readDoc->date_read_last = Carbon::now();
                    $readDoc->save();
                }
            }
        } else {
            if (is_null($document)) {
                return redirect('/')->with('messageSecondary', trans('documentForm.documentUnAvailable'));
            }
            $oldStatus = $document->document_status_id;
         /*
            Check if document is latest published. if not redirect from unique url to id url
            This is used as a failsafe for documents accessed from browser history
         */

        /*Published hotfix*/
        if ($document->date_published == null) {
            $doc = Document::find($document->id);
            $doc->date_published = $doc->created_at->addDay();
            $doc->save();
            $document->date_published = $document->created_at;
        }
        /*Published hotfix*/

         $latestPublished = PublishedDocument::where('document_group_id', $document->document_group_id)->orderBy('updated_at', 'desc')->first();
            if ($latestPublished != null && $latestPublished->document_id == $document->id && $document->document_status_id != 5) {
                return redirect('dokumente/'.$latestPublished->url_unique);
            } elseif ($document->id != $initialUrl) {
                return redirect('dokumente/'.$document->id);
            }
        }

        $document = $this->checkFreigabeRoles($document);

        $documentPermission = ViewHelper::universalDocumentPermission($document, false);
        $variantPermissions = ViewHelper::documentVariantPermission($document);

        // if($document->active == 0
        if (($document->document_status_id == 5 && ViewHelper::universalHasPermission(array(14)) == false) ||
        ($variantPermissions->permissionExists == false && $documentPermission == false)) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        //this is the fix  to prevent non document users(authors,freigabe,struktur) to view when document is authorized and not published
        if ($this->document->universalDocumentPermission($document, false, true) == false &&
        ($document->document_status_id == 2 || $document->document_status_id == 6)) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $favorite = null;
        if (isset($document->document_group_id) && isset(Auth::user()->id)) {
            $favorite = FavoriteDocument::where('document_group_id', $document->document_group_id)->where('user_id', Auth::user()->id)->first();
        }

        if ($favorite == null) {
            $document->hasFavorite = false;
        } else {
            $document->hasFavorite = true;
        }
            //user_id, owner_user_id,
        $documentComments = DocumentComment::where('document_id', $id)->where('freigeber', 0)->orderBy('created_at', 'DESC')->get();
        $myComments = DocumentComment::where('document_id', $id)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();

        /* User and freigabe comment visibility */
        $commentVisibility = $this->commentVisibility($document);

        $variants = EditorVariant::where('document_id', $id)->get();

        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
        $mandantRolesArr = $mandantRoles->toArray();
        $auth = DocumentApproval::where('document_id', $id)->get();

        /* Button check */
        $published = false;
        $canPublish = false;
        $authorised = false;
        $authorisedPositive = false;
             //  je li date expired manji od now
        if ($document->date_expired != null) {
            $dateExpired = new Carbon($document->date_expired);
            $now = new Carbon(Carbon::now());
            $datePassed = $dateExpired->lt(Carbon::now());
            if ($datePassed == true) {
                $canPublish = true;
            }
        }

        if (count($document->documentApprovalsApprovedDateNotNull) > 0 && (count($document->documentApprovals) == count($document->documentApprovalsApprovedDateNotNull))) {
            $authorised = true;

            foreach ($document->documentApprovals->pluck('approved') as $approved) {
                if ($approved == true) {
                    $authorisedPositive = true;
                }
            }
        }

        if (count($document->publishedDocuments->first()) > 0) {
            $published = true;
        }
        /* End Button check */

        $hasPermission = false;
        foreach ($variants as $variant) {
            if ($hasPermission == false) {
                if ($variant->approval_all_mandants == true) {
                    if ($document->approval_all_roles == true) {
                        $hasPermission = true;
                        $variant->hasPermission = true;
                    } else {
                        foreach ($variant->documentMandantRoles as $role) {
                            if (in_array($role->role_id, $mandantRolesArr)) {
                                $variant->hasPermission = true;
                                $hasPermission = true;
                            }
                        }//end foreach documentMandantRoles
                    }
                } else {
                    foreach ($variant->documentMandantMandants as $mandant) {
                        if (in_array($mandant->mandant_id, $mandantIdArr)) {
                            if ($document->approval_all_roles == true) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                            } else {
                                foreach ($variant->documentMandantRoles as $role) {
                                    if (in_array($role->role_id, $mandantRolesArr)) {
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }

        $variants = $variantPermissions->variants;
        $documentCommentsFreigabe = DocumentComment::where('document_id', $id)->where('freigeber', 1)->orderBy('created_at', 'DESC')->get();

        /* If the document can be published or in freigabe process and the roles are correct */
            if ($authorised == true && $canPublish == true && $published == false && $document->document_status_id != 5 && $document->document_status_id != 1) {
                if (($document->documentType->document_art == 1 && ViewHelper::universalHasPermission(array(13)) == true)
                    || ($document->documentType->document_art == 0 && ViewHelper::universalHasPermission(array(11)) == true)
                    || (ViewHelper::universalDocumentPermission($document, false, false, true))) {
                    return redirect('dokumente/'.$document->id.'/freigabe');
                }
            } elseif ($document->document_status_id != 1 && $document->document_status_id != 5 && (($authorised == false && $published == false) ||
                   ($authorised == true && $published == false) || ($canPublish == true && $published == false)
                   && (ViewHelper::universalDocumentPermission($document, false, false, true)))) {
                if ((($document->documentType->document_art == 1 &&
                                ViewHelper::universalHasPermission(array(13)) == true) ||
                                ($document->documentType->document_art == 0 &&
                                ViewHelper::universalHasPermission(array(11)) == true))
                                && ViewHelper::universalDocumentPermission($document, false, false, true)) {
                    return redirect('dokumente/'.$document->id.'/freigabe');
                }
            } else {
                if ($document->document_status_id == 2 && $authorised == true) {
                    $doc = Document::find($document->id); //need a pure collections(elimintae hasFavorite error)
                   $this->publishProcedure($doc);
                }
            }

        if (count($document->documentApprovalsApprovedDateNotNull) == count($document->documentApprovals)) {
            $canPublish = false;
            $authorised = false;
        }

        /* End If the document can be published or in freigabe process and the roles are correct */

        $isoCategoryName = '';
        $docTypeSearch = DocumentType::find($document->document_type_id);
        $docTypeSlug = '';

        if (isset($docTypeSearch)) {
            $docTypeSlug = str_slug($docTypeSearch->name);
        }

        if ($document->document_type_id == 4) {
            if ($document->iso_category_id != null) {
                $isoCategory = IsoCategory::find($document->iso_category_id);
                $isoCategoryName = str_slug($isoCategory->name);
                $categoryIsParent = IsoCategory::where('iso_category_parent_id', $isoCategory->id)->get();

                $iso_category_id = $isoCategory->id;
                if ($isoCategory->iso_category_parent_id) {
                    $isoCategoryParent = IsoCategory::where('id', $isoCategory->iso_category_parent_id)->first();
                } else {
                    $isoCategoryParent = null;
                }
            }
        }

        $favoriteCategories = FavoriteCategory::where('user_id', Auth::user()->id)->get();
        $document->favorite = FavoriteDocument::where('document_group_id', $document->document_group_id)->where('user_id', Auth::user()->id)->first();

        // Prevent showing of document if publish date ist not today or past
        // if (Carbon::parse($document->date_published)->gt(Carbon::today())) {
        //     return redirect('/')->with('message', trans('documentForm.noPermission'));
        // }

        return view('dokumente.show', compact('document', 'documentComments', 'documentCommentsFreigabe',
        'variants', 'published', 'datePublished', 'canPublish', 'authorised', 'commentVisibility', 'myComments',
        'isoCategoryName', 'isoCategoryParent', 'isoCategory', 'favoriteCategories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Document::find($id);
            // dd($data);
            if ($data == null) {
                return redirect('dokumente/create');
            }
        if ($this->canCreateEditDoc($data) == true) {
            $documentTypes = DocumentType::where('jurist_document', 0)->get(); // if struktur admin
            $docTypesArr = $documentTypes->pluck('id')->toArray();

            if ($this->returnRole() != false && $this->returnRole() == 11) { // 11 Rundschreiben Verfasser
                $documentTypes = DocumentType::where('document_art', 0)->get();
                $docTypesArr = $documentTypes->pluck('id')->toArray();
            } elseif ($this->returnRole() != false && $this->returnRole() == 13) {
                $documentTypes = DocumentType::where('document_art', 1)->get();
                $docTypesArr = $documentTypes->pluck('id')->toArray();
            } // 13 Dokumenten Verfasser

            if (count($docTypesArr) && ViewHelper::universalDocumentPermission($data, true, false, true) == false) {
                if (!in_array($data->document_type_id, $docTypesArr)) {
                    session()->flash('message', trans('documentForm.noPermission'));

                    return redirect()->back();
                }
            }
            // only strukturadmin and
            if ((Auth::user()->id != 1 && ViewHelper::universalDocumentPermission($data, false, false, true) == false)
                && $data->document_status_id == 5) {
                return redirect('dokumente/'.$data->id)->with('messageSecondary', trans('documentForm.noPermission'));
            }

            $url = 'PATCH';

            $url = '';
            $documentCoauthor = DocumentCoauthor::where('document_id', $id)->get();
            $documentCoauthors = DocumentCoauthor::where('document_id', $id)->get();
            $isoDocuments = IsoCategory::all();
            $mandantUserRoles = MandantUserRole::where('role_id', 10)->pluck('mandant_user_id');
            $documentStatus = DocumentStatus::all();
            $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
            $mandantUsers = MandantUser::distinct('user_id')->whereIn('mandant_id', $mandantId)->get();
            $mandantUsers = $this->clearUsers($mandantUsers);

            $pluckIdMandantUsers = $mandantUsers->pluck('user_id')->toArray();
            $mandantUsers = User::whereIn('id', $pluckIdMandantUsers)->orderBy('last_name', 'asc')->get();

            $documentCoauthor = $mandantUsers;

            //this is until Neptun inserts the documents
            $documentUsers = $mandantUsers;

            $incrementedQmr = Document::where('document_type_id', $this->qmRundId)->orderBy('qmr_number', 'desc')->first();
            if ($incrementedQmr == null || $incrementedQmr->qmr_number == null) {
                $incrementedQmr = 1;
            } else {
                $incrementedQmr = $incrementedQmr->qmr_number;
                $incrementedQmr = $incrementedQmr + 1;
            }

            $incrementedIso = Document::where('document_type_id', $this->isoDocumentId)->orderBy('iso_category_number', 'desc')->first();
            if (count($incrementedIso) < 1 || $data == null) {
                $incrementedIso = 1;
            } else {
                $incrementedIso = $incrementedIso->iso_category_number;
                $incrementedIso = $incrementedIso + 1;
            }

            return view('formWrapper', compact('data', 'method', 'url', 'documentTypes', 'isoDocuments',
            'documentStatus', 'mandantUsers', 'documentUsers', 'documentCoauthor', 'documentCoauthors', 'incrementedQmr', 'incrementedIso'));
        } else {
            session()->flash('message', trans('documentForm.noPermission'));

            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Document::find($id);
        $adressats = Adressat::where('active', 1)->get();
        //fix if document type not iso category -> don't save iso_category_id
        if ($request->get('document_type_id') != $this->isoDocumentId) {
            RequestMerge::merge(['iso_category_id' => null]);
        }
        //fix pdf checkbox
        if (!$request->has('pdf_upload')) {
            RequestMerge::merge(['pdf_upload' => 0]);
        }
        //if doc type formulare set ladnsace to null
        if (!$request->has('landscape')) {
            RequestMerge::merge(['landscape' => 0]);
        }

        if ($request->get('document_type_id') != $this->newsId && $request->get('document_type_id') != $this->rundId
           && $request->get('document_type_id') != $this->isoDocumentId && $request->get('document_type_id') != $this->qmRundId && $request->has('pdf_upload')) {
            RequestMerge::merge(['pdf_upload' => 0]);
        }

        if (!$request->has('name_long')) {
            RequestMerge::merge(['name_long' => $request->get('name')]);
        }

        if (!$request->has('betreff') && ($data->betreff == '' || $data->betreff == null)) {
            RequestMerge::merge(['betreff' => $request->get('name_long')]);
        }

        if (!$request->has('date_published')) {
            $publishedDate = new Carbon($data->created_at);
            RequestMerge::merge(['date_published' => $publishedDate->addDay()->format('d.m.Y')]);
        }

        if (!$request->has('date_expired') || $request->get('date_expired') == '') {
            RequestMerge::merge(['date_expired' => null]);
        }
        $prevName = $data->name;

        if ($data->document_type_id == $this->formulareId) {
            RequestMerge::merge(['landscape' => 0]);
        }

        $data->fill($request->all())->save();

        $data = Document::find($id);

        $variant = $data->editorVariant();
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload'));

        $url = $setDocument->url;
        $form = $setDocument->form;

        DocumentCoauthor::where('document_id', $id)->delete();
        if ($request->has('document_coauthor') && $request->input('document_coauthor')[0] != '0'
        && $request->input('document_coauthor')[0] != 0) {
            $coauthors = $request->input('document_coauthor');
            foreach ($coauthors as $coauthor) {
                DocumentCoauthor::create(['document_id' => $id, 'user_id' => $coauthor]);
            }
        }
        $backButton = url('/dokumente/'.$data->id.'/edit');

        return view('dokumente.formWrapper', compact('data', 'backButton', 'form', 'url', 'adressats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function newVersion($id)
    {
        $uid = Auth::user()->id;
        //find if document has version higher than one
        $document = Document::find($id);
        $highestVersion = Document::where('document_group_id', $document->document_group_id)->orderBy('version', 'DESC')->first();
        $version = $highestVersion->version;

        /*Set all previous versions to arhived*/
        /*End Set all previous versions to arhived*/
        $newDocument = $document->replicate();
        $newDocument->active = true;
        $newDocument->version = $version + 1;
        $newDocument->version_parent = $version;
        $newDocument->document_status_id = 1;
        $newDocument->owner_user_id = $uid;
        $newDocument->date_expired = null;
        $newDocument->date_published = null;
        $newDocument->date_approved = null;
        $newDocument->save();

        /*Duplicate document variants*/
        foreach ($document->editorVariant as $variant) {
            $newVariant = $variant->replicate();
            $newVariant->document_id = $newDocument->id;
            $newVariant->save();
            /*Duplicate document uploads*/
            foreach ($variant->documentUpload as $upload) {
                $newUpload = $upload->replicate();
                $newUpload->editor_variant_id = $newVariant->id;
                $newUpload->file_path = $upload->file_path;
                $newUpload->save();

                if (!\File::exists($this->movePath.'/'.$newDocument->id.'/')) {
                    \File::makeDirectory($this->movePath.'/'.$newDocument->id.'/', $mod = 0777, true, true);
                }
                $copy = copy($this->movePath.'/'.$document->id.'/'.$upload->file_path, $this->movePath.'/'.$newDocument->id.'/'.$upload->file_path);
            }
            /*End Duplicate document uploads*/

            /*Duplicate editor_variant_documents*/
            foreach ($variant->editorVariantDocument as $editorVariantDocument) {
                $newEditorVariantDocument = $editorVariantDocument->replicate();

                $newEditorVariantDocument->editor_variant_id = $newVariant->id;
                $newEditorVariantDocument->document_status_id = $newDocument->document_status_id;
                $newEditorVariantDocument->document_group_id = $document->document_group_id;
                $newEditorVariantDocument->document_id = $newDocument->id;
                $newEditorVariantDocument->save();
            }
            /*End Duplicate editor_variant_documents*/

            /*Duplicate document mandants*/
            if (count($variant->documentMandants) > 0) {
                foreach ($variant->documentMandants as $documentMandant) {
                    $newDocumentMandant = $documentMandant->replicate();
                    $newDocumentMandant->document_id = $newDocument->id;
                    $newDocumentMandant->editor_variant_id = $newVariant->id;
                    $newDocumentMandant->save();

                /*Duplicate document mandant mandants*/
                foreach ($documentMandant->documentMandantMandants as $docMandantMandant) {
                    $newDMM = $docMandantMandant->replicate();
                    $newDMM->document_mandant_id = $newDocumentMandant->id;
                    $newDMM->save();
                }
                /*End Duplicate document mandant mandants*/

                /*Duplicate document mandant roles*/
                 foreach ($documentMandant->documentMandantRole as $docMandantRole) {
                     $newDMR = $docMandantRole->replicate();
                     $newDMR->document_mandant_id = $newDocumentMandant->id;
                     $newDMR->save();
                 }
                /*End Duplicate document mandant roles*/
                }
            }
            /*End Duplicate  document mandants*/
        }
        /* End Duplicate document variants*/
         session()->flash('message', trans('documentForm.newVersionSuccess'));

        return redirect('dokumente/'.$newDocument->id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyByLink($documentId, $editorId, $editorDocumentId)
    {
        $documentCheck = EditorVariantDocument::where('editor_variant_id', $editorId)->where('document_id', $editorDocumentId)->first();

        if ($documentCheck != null) {
            $documentCheck->delete();
        }

        return redirect('/dokumente/anlagen/'.$documentId)->with('message', 'Dokument wurde entfernt.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = Document::find($id);
        if (isset($document) && $document->document_status_id == 1) {
            foreach ($document->editorVariant as $ev) {
                foreach ($ev->documentUpload as $du) {
                    $du->delete();
                }
                foreach ($ev->editorVariantDocument as $evd) {
                    $evd->delete();
                }
                $ev->delete();
            }
            $document->delete();

            return redirect('/')->with('message', trans('dokumentShow.movedToTrash'));
        } else {
            return redirect('/')->with('message', trans('dokumentShow.movedToTrashError'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function saveComment(Request $request, $id)
    {
        RequestMerge::merge(['document_id' => $id, 'user_id' => Auth::user()->id, 'active' => 1, 'freigeber' => 0]);
        //  dd( $request->all() );
        $comment = DocumentComment::create($request->all());
        $document = Document::find($id);
        session()->flash('message', trans('documentForm.savedComment'));
        if ($request->has('page')) {
            $publishedDocs = PublishedDocument::where('document_id', $document->id)->where('document_group_id', $document->document_group_id)->first();
            if ($publishedDocs != null) {
                $id = $publishedDocs->url_unique;
            }

            return redirect('dokumente/'.$id);
        } else {
            return redirect('dokumente/'.$id.'/freigabe');
        }
    }

    /**
     * Destory the comment in the database.
     *
     * @param int $id
     * @param int $documentId
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteComment($id, $documentId)
    {
        $comment = DocumentComment::find($id);
        $comment->delete();

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function freigabeApproval($id)
    {
        $document = Document::find($id);
        $variantPermissions = ViewHelper::documentVariantPermission($document);

        if ($this->document->universalDocumentPermission($document, true, true) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        if ($document->document_status_id == 1) {
            return redirect('dokumente/'.$id);
        }

        $variants = EditorVariant::where('document_id', $id)->get();

        $documentCommentsUser = DocumentComment::where('document_id', $id)->where('freigeber', 0)->orderBy('id', 'DESC')->get();
        $documentCommentsFreigabe = DocumentComment::where('document_id', $id)->where('freigeber', 1)->orderBy('id', 'DESC')->get();
        /* Button check */
        $published = false;
        $canPublish = false;
        $authorised = false;
        $authorisedPositive = false;
        if ($document->date_expired != null) {
            $dateExpired = new Carbon($document->date_expired);
            $now = new Carbon(Carbon::now());
            $datePassed = $dateExpired->lt(Carbon::now());
            if ($datePassed == true) {
                $canPublish = true;
            }
        }
        if (count($document->documentApprovalsApprovedDateNotNull) == count($document->documentApprovals)) {
            $authorised = true;
        }

        if (count($document->publishedDocuments->first()) > 0) {
            $published = true;
        }

        $hasPermission = false;
        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');

        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
        $mandantRolesArr = $mandantRoles->toArray();
        $mandantIdArr = $mandantId->toArray();
        foreach ($variants as $variant) {
            if ($hasPermission == false) {
                if ($variant->approval_all_mandants == true) {
                    if ($document->approval_all_roles == true) {
                        $hasPermission = true;
                        $variant->hasPermission = true;
                    } else {
                        foreach ($variant->documentMandantRoles as $role) {
                            if (in_array($role->role_id, $mandantRolesArr)) {
                                $variant->hasPermission = true;
                                $hasPermission = true;
                            }
                        }//end foreach documentMandantRoles
                    }
                } else {
                    foreach ($variant->documentMandantMandants as $mandant) {
                        if (in_array($mandant->mandant_id, $mandantIdArr)) {
                            if ($document->approval_all_roles == true) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                            } else {
                                foreach ($variant->documentMandantRoles as $role) {
                                    if (in_array($role->role_id, $mandantRolesArr)) {
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }

        if (count($document->documentApprovalsApprovedDateNotNull) != count($document->documentApprovals)) {
            $published = false;
            $canPublish = false;
            $authorised = false;
            $authorisedPositive = false;
        }
        $variants = $variantPermissions->variants;
        /* End Button check */

        // dd($variants);

        /* User and freigabe comment visibility */
        $commentVisibility = $this->commentVisibility($document);

        // Prepares and stores email settings entry counts to show
        $emailSettings = array();

        return view('dokumente.freigabe', compact('document', 'variants', 'documentCommentsUser', 'documentCommentsFreigabe', 'published',
        'canPublish', 'hasPermission', 'authorised', 'authorisedPositive', 'commentVisiblity', 'emailSettings'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publishApproval(Request $request, $id)
    {
        $sending = false;
        if ($request->segment(4) == 'send') {
            $sending = true; // Process sending options when publishing documents
        }

        $document = Document::find($id);
        $otherDocuments = Document::where('document_group_id', $document->document_group_id)->whereNotIn('id', array($document->id))->get();
        foreach ($otherDocuments as $oDoc) {
            $oDoc->document_status_id = 5;
            $oDoc->save();
        }

        $document->document_status_id = 3;
        $document->save();
        $continue = true;
        $uniqeUrl = '';
        $this->publishProcedure($document, $sending);
        $attachedDocuments = $document->editorVariantDocument;
        foreach ($document->editorVariant as $ev) {
            foreach ($ev->editorVariantDocument as $evD) {
                $this->publishProcedure($evD->document); // Dont send emails for attachments
            }
        }
        UserReadDocument::where('document_group_id', $document->published->document_group_id)->delete();

        if ($sending) {
            return back()->with('message', trans('dokumentShow.publishSendSuccess'));
        } else {
            return back()->with('message', trans('dokumentShow.publishSuccess'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function favorites($id)
    {
        $document = Document::find($id);
        $favoriteCheck = FavoriteDocument::where('document_group_id', $document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if ($favoriteCheck == null) {
            $favorite = FavoriteDocument::create(['document_group_id' => $document->document_group_id, 'user_id' => Auth::user()->id]);

            return back();
        } else {
            $favoriteCheck->delete();

            return back()->with('message', trans('dokumentShow.favoriteRemoved'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentActivation($id)
    {
        $document = Document::find($id);

        if (in_array($document->document_status_id, [3, 5])) {
            if ($document->active == true) {
                $document->document_status_id = 5;
                $document->active = false;
            } else {
                $document->document_status_id = 3;
                $document->active = true;
            }
        }

        $document->save();

        if ($document->published != null && $document->published->url_unique) {
            return redirect('dokumente/'.$document->published->url_unique);
        } else {
            return redirect('dokumente/'.$id);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request, $id)
    {
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        } else {
            $document = Document::find($id);
        }
        $variantPermissions = ViewHelper::documentVariantPermission($document);
        if ($variantPermissions->permissionExists == false) {
            session()->flash('message', trans('documentForm.noPermission'));

            return redirect('/');
        }

        $datePublished = new Carbon($document->date_published);
        $dateNow = $this->getGermanMonthName(intval($datePublished->format('m')));

        $dateNow .= ' '.$datePublished->format('Y');
        // setlocale(LC_TIME, '');

        $favorite = FavoriteDocument::where('document_group_id', $document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if ($favorite == null) {
            $document->hasFavorite = false;
        } else {
            $document->hasFavorite = true;
        }
        $documentComments = DocumentComment::where('document_id', $id)->where('freigeber', 0)->get();

        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
        $mandantRolesArr = $mandantRoles->toArray();

        $hasPermission = false;
        $variants = $variantPermissions->variants;

        $document = Document::find($id);
        $margins = $this->setPdfMargins($document);

        $or = 'P';
        if ($document->landscape == true) {
            $or = 'L';
        }
        $pdf = new PdfWrapper();
        $pdf->debug = true;

        if ($document->document_type_id == $this->isoDocumentId) {
            $pdf->SetHTMLHeader(view('pdf.headerIso', compact('document', 'variants', 'dateNow'))->render());
            $pdf->SetHTMLFooter(view('pdf.footerIso', compact('document', 'variants', 'dateNow'))->render());

            $render = view('pdf.documentIso', compact('document', 'variants', 'dateNow'))->render();
        } else {
            if ($document->document_template == 1) {
                $render = view('pdf.document', compact('document', 'variants', 'dateNow'))->render();
                $pdf->SetHTMLFooter(view('pdf.footer', compact('document', 'variants', 'dateNow'))->render());
            } else {
                $render = view('pdf.new-layout-rund', compact('document', 'variants', 'dateNow'))->render();
                $header = view('pdf.new-layout-rund-header', compact('document', 'variants', 'dateNow'))->render();
                $footer = view('pdf.new-layout-rund-footer', compact('document', 'variants', 'dateNow'))->render();

                $pdf->SetHTMLHeader($header);
                $pdf->SetHTMLFooter($footer);
            }
        }
        $pdf->AddPage($or, $margins->left, $margins->right, $margins->top, $margins->bottom, $margins->headerTop, $margins->footerTop);
        $pdf->WriteHTML($render);

        if ($request->segment(4) == 'download') {
            return $pdf->download('dokument_'.date('d-m-Y_h-i-s').'.pdf');
        } else {
            return $pdf->stream();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePdfPreview($id, $editorId)
    {
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        } else {
            $document = Document::find($id);
        }

        $datePublished = new Carbon($document->date_published);
        $dateNow = $this->getGermanMonthName(intval($datePublished->format('m')));

        $dateNow .= ' '.$datePublished->format('Y');

        $variants = EditorVariant::where('document_id', $id)->where('variant_number', $editorId)->get();
        foreach ($variants as $variant) {
            $variant->hasPermission = true;
        }

        $margins = $this->setPdfMargins($document);

        $or = 'P';
        if ($document->landscape == true) {
            $or = 'L';
        }
        $pdf = new PdfWrapper();
        $pdf->debug = true;

        if ($document->document_type_id == $this->isoDocumentId) {
            $pdf->SetHTMLHeader(view('pdf.headerIso', compact('document', 'variants', 'dateNow'))->render());
            $pdf->SetHTMLFooter(view('pdf.footerIso', compact('document', 'variants', 'dateNow'))->render());

            $render = view('pdf.documentIso', compact('document', 'variants', 'dateNow'))->render();
        } else {
            if ($document->document_template == 1) {
                $render = view('pdf.document', compact('document', 'variants', 'dateNow'))->render();
                $pdf->SetHTMLFooter(view('pdf.footer', compact('document', 'variants', 'dateNow'))->render());
            } else {
                $render = view('pdf.new-layout-rund', compact('document', 'variants', 'dateNow'))->render();
                $header = view('pdf.new-layout-rund-header', compact('document', 'variants', 'dateNow'))->render();
                $footer = view('pdf.new-layout-rund-footer', compact('document', 'variants', 'dateNow'))->render();
                $pdf->SetHTMLHeader($header);
                $pdf->SetHTMLFooter($footer);
            }
        }

        $pdf->AddPage($or, $margins->left, $margins->right, $margins->top, $margins->bottom, $margins->headerTop, $margins->footerTop);
        $pdf->WriteHTML($render);

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function previewDocument($id, $editorId)
    {
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        } else {
            $document = Document::find($id);
        }

        $datePublished = $document->created_at;
        if ($document->date_published == null) {
            $doc = Document::find($document->id);
            $doc->date_published = $datePublished;
            $doc->save();
            $document->date_published = $datePublished;
        }
        if ($document->document_status_id == 5 && ViewHelper::universalHasPermission(array(14)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $favorite = FavoriteDocument::where('document_group_id', $document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if ($favorite == null) {
            $document->hasFavorite = false;
        } else {
            $document->hasFavorite = true;
        }
        $documentComments = DocumentComment::where('document_id', $id)->where('freigeber', 0)
         ->where(function ($query) use ($document) {
             $query->where('user_id', $document->user_id)
                      ->orWhere('user_id', $document->owner_user_id);
             if ($document->documentCoauthor != null && isset($document->documentCoauthor->user_id)) {
                 $query->orWhere('user_id', $document->documentCoauthor->user_id);
             }
         })
        ->orderBy('id', 'DESC')->get();
        $variants = EditorVariant::where('document_id', $id)->where('variant_number', $editorId)->get();

        foreach ($variants as $variant) {
            $variant->hasPermission = true;
        }

        return view('dokumente.showPreview', compact('document', 'documentComments', 'variants'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function previewPdf($id)
    {
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        } else {
            $document = Document::find($id);
        }
        $favorite = FavoriteDocument::where('document_group_id', $document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if ($favorite == null) {
            $document->hasFavorite = false;
        } else {
            $document->hasFavorite = true;
        }
        $documentComments = DocumentComment::where('document_id', $id)->where('freigeber', 0)->get();
        $variants = EditorVariant::where('document_id', $id)->get();

        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
        $mandantRolesArr = $mandantRoles->toArray();

        $hasPermission = false;
        // dd($mandantId);

        foreach ($variants as $variant) {
            if ($hasPermission == false) {
                if ($variant->approval_all_mandants == true) {
                    if ($document->approval_all_roles == true) {
                        $hasPermission = true;
                        $variant->hasPermission = true;
                    } else {
                        foreach ($variant->documentMandantRoles as $role) {
                            if (in_array($role->role_id, $mandantRolesArr)) {
                                $variant->hasPermission = true;
                                $hasPermission = true;
                            }
                        }//end foreach documentMandantRoles
                    }
                } else {
                    foreach ($variant->documentMandantMandants as $mandant) {
                        if (in_array($mandant->mandant_id, $mandantIdArr)) {
                            if ($document->approval_all_roles == true) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                            } else {
                                foreach ($variant->documentMandantRoles as $role) {
                                    if (in_array($role->role_id, $mandantRolesArr)) {
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }

        return view('dokumente.show', compact('document', 'documentComments', 'variants'));
    }

    /**
     * Generate a PDF object by document id (for further manipulation).
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePdfObject(Request $request, $id, $variantNumber = null)
    {
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        } else {
            $document = Document::find($id);
        }

        $variantPermissions = ViewHelper::documentVariantPermission($document);
        if ($variantPermissions->permissionExists == false) {
            return false;
        }

        $datePublished = new Carbon($document->date_published);
        $dateNow = $this->getGermanMonthName(intval($datePublished->format('m')));
        $dateNow .= ' '.$datePublished->format('Y');

        // Extend this functionality
        if (isset($variantNumber)) {
            $variants = EditorVariant::where('document_id', $id)->where('variant_number', $variantNumber)->get();
            foreach ($variants as $variant) {
                $variant->hasPermission = true;
            }
        } else {
            $variants = $variantPermissions->variants;
        }

        // $document = Document::find($id);
        $margins = $this->setPdfMargins($document);

        $or = 'P';
        if ($document->landscape == true) {
            $or = 'L';
        }

        $pdf = new PdfWrapper();

        if ($document->document_type_id == $this->isoDocumentId) {
            $pdf->SetHTMLHeader(view('pdf.headerIso', compact('document', 'variants', 'dateNow'))->render());
            $pdf->SetHTMLFooter(view('pdf.footerIso', compact('document', 'variants', 'dateNow'))->render());
            $render = view('pdf.documentIso', compact('document', 'variants', 'dateNow'))->render();
        } else {
            if ($document->document_template == 1) {
                $render = view('pdf.document', compact('document', 'variants', 'dateNow'))->render();
                $pdf->SetHTMLFooter(view('pdf.footer', compact('document', 'variants', 'dateNow'))->render());
            } else {
                $render = view('pdf.new-layout-rund', compact('document', 'variants', 'dateNow'))->render();
                $header = view('pdf.new-layout-rund-header', compact('document', 'variants', 'dateNow'))->render();
                $footer = view('pdf.new-layout-rund-footer', compact('document', 'variants', 'dateNow'))->render();
                $pdf->SetHTMLHeader($header);
                $pdf->SetHTMLFooter($footer);
            }
        }

        $pdf->AddPage($or, $margins->left, $margins->right, $margins->top, $margins->bottom, $margins->headerTop, $margins->footerTop);
        $pdf->WriteHTML($render);

        $filename = sys_get_temp_dir().'/'.$id.'_'.md5(microtime()).'.pdf';
        if (isset($variantNumber)) {
            $filename = sys_get_temp_dir().'/'.$id.'v'.$variantNumber.'_'.md5(microtime()).'.pdf';
        }
        $pdf->save($filename);

        if ($request->segment(4) == 'download') {
            return response()->download($filename);
        }

        return $filename;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authorizeDocument(Request $request, $id)
    {
        if ($request->get('validation_status') == 1) {
            $approved = true;
        } else {
            $approved = false;
        }

        $dateApproved = Carbon::now();
        $document = Document::find($id);
        $user = Auth::user()->id;

        $documentApproval = DocumentApproval::firstOrCreate(array('document_id' => $id, 'user_id' => $user));
        $documentApproval->approved = $approved;
        $documentApproval->date_approved = $dateApproved;
        $documentApproval->save();

        if ($request->has('comment') || $request->has('betreff')) {
            RequestMerge::merge(['freigeber' => 1, 'active' => 1, 'approved' => $approved, 'document_id' => $document->id, 'user_id' => $user]);
            $comment = DocumentComment::create($request->all());
        }

        if (count($document->documentApprovalsApprovedDateNotNull) == count($document->documentApprovals)) {
            $document->document_status_id = 2;
            $document->save();

            $now = Carbon::now();
            // dd($document->date_published);
            if ($document->date_published == null) {
                $document->date_published = $document->created_at->addDay();
                $document->save();
            }

            $publishTime = $now->gt(Carbon::parse($document->date_published)); //if true you can publish
        }

        if ($approved) {
            session()->flash('message', trans('documentForm.authorized'));
        } else {
            session()->flash('message', trans('documentForm.not-authorized'));
        }

        return redirect('/dokumente/'.$id.'/freigabe');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreiben(Request $request)
    {
        $docs = $request->get('documents');
        $sort = $request->get('sort');
        $myRundCoauthorArr = DocumentCoauthor::where('user_id', Auth::user()->id)->pluck('document_id')->toArray();
        $docType = $this->rundId;
        $myRundCoauthor = Document::whereIn('id', $myRundCoauthorArr)->where('document_type_id', $docType)->pluck('id')->toArray();

        $highRole = false;
        if (ViewHelper::universalHasPermission(array(10)) == true) {
            $highRole = true;
        }
        // status entwurf
        $rundEntwurfPaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        ->where('document_status_id', 1)
        ->orderBy('date_published', 'desc')->paginate(10, ['*'], 'rundschreiben-entwurf');
        $rundEntwurfTree = $this->document->generateTreeview($rundEntwurfPaginated, array('pageDocuments' => true));

        // status im freigabe prozess
        $rundFreigabePaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        ->whereIn('document_status_id', [2, 6])
        ->orderBy('date_published', 'desc')->paginate(10, ['*'], 'rundschreiben-freigabe');
        $rundFreigabeTree = $this->document->generateTreeview($rundFreigabePaginated, array('pageDocuments' => true));

        // all status aktuell/published
        $rundAllPaginated = Document::where('document_type_id', $docType)
        ->where('document_status_id', 3)
        ->where('active', 1)->get();
        
        // Hide documents that have publish date higher than today
        $rundAllPaginated = $rundAllPaginated->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });
        
        if ($docs == 'alle' && $sort == 'asc') {
            $rundAllPaginated = $this->document->getUserPermissionedDocuments($rundAllPaginated, 'alle-rundschreiben', array('field' => 'date_published', 'sort' => 'asc'));
        } else {
            $rundAllPaginated = $this->document->getUserPermissionedDocuments($rundAllPaginated, 'alle-rundschreiben', array('field' => 'date_published', 'sort' => 'desc'));
        }
        
        $rundAllTree = $this->document->generateTreeview($rundAllPaginated, array('pageDocuments' => true, 'showHistory' => true));

        // $rundschreibenAll = Document::where(['document_type_id' =>  $docType])->where('document_status_id',3)
        // ->where('active',1)->orderBy('id', 'desc')->paginate(10, ['*'], 'alle-rundschreiben');
        // $rundschreibenAllTree = $this->document->generateTreeview( $rundschreibenAll );

        // $rundschreibenMeine = Document::where(['user_id' => Auth::user()->id, 'document_type_id' =>  $this->rundId])->orderBy('id', 'desc')->take(10)->paginate(10, ['*'], 'meine-rundschreiben');
        // $rundschreibenMeineTree = $this->document->generateTreeview( $rundschreibenMeine );

        // $request->flash();

        $docType = DocumentType::find($docType);

        return view('dokumente.rundschreiben', compact('docType', 'rundEntwurfPaginated', 'rundEntwurfTree', 'rundFreigabePaginated', 'rundFreigabeTree', 'rundAllPaginated', 'rundAllTree', 'docs', 'sort'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreibenPdf()
    {
        $counter = 0;
        $data = json_encode($this->document->generateDummyData('Anhag dokumente', array(), false));
        $comment = $this->document->generateDummyData('Lorem ipsum comment', array(), false);
        $data2 = json_encode($this->document->generateDummyData('Herr Engel - Betreff', $comment));

        return view('dokumente.rundschreibenPdf', compact('data', 'data2', 'counter'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreibenQmr(Request $request)
    {
        $docs = $request->get('documents');
        $sort = $request->get('sort');

        $docType = $this->qmRundId;

        $highRole = false;
        if (ViewHelper::universalHasPermission(array(10)) == true) {
            $highRole = true;
        }
        $myRundCoauthorArr = DocumentCoauthor::where('user_id', Auth::user()->id)->pluck('document_id')->toArray();
        $myRundCoauthor = Document::whereIn('id', $myRundCoauthorArr)->where('document_type_id', $docType)->pluck('id')->toArray();

        // status entwurf
        $qmrEntwurfPaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        ->where('document_status_id', 1)
        // ->orderBy('id', 'desc')->paginate(10, ['*'], 'qmr-entwurf');
        ->orderBy('qmr_number', 'desc')->paginate(10, ['*'], 'qmr-entwurf');
        $qmrEntwurfTree = $this->document->generateTreeview($qmrEntwurfPaginated, array('pageDocuments' => true));

        // status im freigabe prozess
        $qmrFreigabePaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        ->whereIn('document_status_id', [2, 6])
        ->orderBy('qmr_number', 'desc')->paginate(10, ['*'], 'qmr-freigabe');
        $qmrFreigabeTree = $this->document->generateTreeview($qmrFreigabePaginated, array('pageDocuments' => true));

        // all status aktuell/published
        $qmrAllPaginated = Document::where('document_type_id', $docType)
        ->where('document_status_id', 3)
        ->where('active', 1)
        ->orderBy('qmr_number', 'desc')->get();
        
        // Hide documents that have publish date higher than today
        $qmrAllPaginated = $qmrAllPaginated->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });
        
        if ($docs == 'alle' && $sort == 'desc') {
            $qmrAllPaginated = $this->document->getUserPermissionedDocuments($qmrAllPaginated, 'alle-qmr', array('field' => 'qmr_number', 'sort' => 'desc'));
        } else {
            $qmrAllPaginated = $this->document->getUserPermissionedDocuments($qmrAllPaginated, 'alle-qmr', array('field' => 'qmr_number', 'sort' => 'asc'));
        }

        $qmrAllTree = $this->document->generateTreeview($qmrAllPaginated, array('pageDocuments' => true, 'showHistory' => true));

        $docType = DocumentType::find($docType);

        // $request->flash();
        return view('dokumente.circularQMR', compact('docType', 'qmrEntwurfTree', 'qmrEntwurfPaginated', 'qmrFreigabeTree', 'qmrFreigabePaginated', 'qmrAllTree', 'qmrAllPaginated', 'docs', 'sort'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreibenNews(Request $request)
    {
        $docs = $request->get('documents');
        $sort = $request->get('sort');
        $docType = $this->newsId;
        $myRundCoauthorArr = DocumentCoauthor::where('user_id', Auth::user()->id)->pluck('document_id')->toArray();
        $myRundCoauthor = Document::whereIn('id', $myRundCoauthorArr)->where('document_type_id', $docType)->pluck('id')->toArray();

        $highRole = false;
        if (ViewHelper::universalHasPermission(array(10)) == true) {
            $highRole = true;
        }
        // status entwurf
        $newsEntwurfPaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        ->where('document_status_id', 1);
        //   ->orderBy('id', 'desc')->paginate(10, ['*'], 'news-entwurf')
        if ($docs == 'entwurf' && $sort == 'asc') {
            $newsEntwurfPaginated = $newsEntwurfPaginated->orderBy('date_published', 'asc')->paginate(10, ['*'], 'news-entwurf');
        } else {
            $newsEntwurfPaginated = $newsEntwurfPaginated->orderBy('date_published', 'desc')->paginate(10, ['*'], 'news-entwurf');
        }

        $newsEntwurfTree = $this->document->generateTreeview($newsEntwurfPaginated, array('pageDocuments' => true));

        // dd($newsEntwurfPaginated);

        // status im freigabe prozess
        $newsFreigabePaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        ->whereIn('document_status_id', [2, 6]);
        // ->orderBy('id', 'desc')->paginate(10, ['*'], 'news-freigabe');
        if ($docs == 'freigabe' && $sort == 'asc') {
            $newsFreigabePaginated = $newsFreigabePaginated->orderBy('date_published', 'asc')->paginate(10, ['*'], 'news-freigabe');
        } else {
            $newsFreigabePaginated = $newsFreigabePaginated->orderBy('date_published', 'desc')->paginate(10, ['*'], 'news-freigabe');
        }

        $newsFreigabeTree = $this->document->generateTreeview($newsFreigabePaginated, array('pageDocuments' => true));

        // all status aktuell/published
        $newsAllPaginated = Document::where('document_type_id', $docType)
        ->where('document_status_id', 3)
        ->where('active', 1)->get();
        
        // Hide documents that have publish date higher than today
        $newsAllPaginated = $newsAllPaginated->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });
        
        if ($docs == 'alle' && $sort == 'asc') {
            $newsAllPaginated = $this->document->getUserPermissionedDocuments($newsAllPaginated, 'alle-news', array('field' => 'date_published', 'sort' => 'asc'));
        } else {
            $newsAllPaginated = $this->document->getUserPermissionedDocuments($newsAllPaginated, 'alle-news', array('field' => 'date_published', 'sort' => 'desc'));
        }

        $newsAllTree = $this->document->generateTreeview($newsAllPaginated, array('pageDocuments' => true, 'showHistory' => true));

        $docType = DocumentType::find($docType);

        return view('dokumente.rundschreibenNews', compact('newsEntwurfPaginated', 'newsEntwurfTree',
        'newsFreigabePaginated', 'newsFreigabeTree', 'newsAllPaginated', 'newsAllTree', 'docType', 'docs', 'sort'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentTemplates(Request $request)
    {
        $docs = $request->get('documents');
        $sort = $request->get('sort');
        $highRole = false;
        if (ViewHelper::universalHasPermission(array(10)) == true) {
            $highRole = true;
        }
        $docType = $this->formulareId;
        $myRundCoauthorArr = DocumentCoauthor::where('user_id', Auth::user()->id)->pluck('document_id')->toArray();
        $myRundCoauthor = Document::whereIn('id', $myRundCoauthorArr)->where('document_type_id', $docType)->pluck('id')->toArray();

        $formulareEntwurfPaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        ->where('document_status_id', 1)
        // ->orderBy('id', 'desc')->paginate(10, ['*'], 'meine-formulare');
        ->orderBy('date_published', 'desc')->paginate(10, ['*'], 'meine-formulare');
        $formulareEntwurfTree = $this->document->generateTreeview($formulareEntwurfPaginated, array('pageDocuments' => true));
        // $formulareEntwurfTree = $this->document->generateTreeview( $formulareEntwurfPaginated, array('pageDocuments' => true,'formulare' => true) );

        $formulareFreigabePaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) use ($highRole, $myRundCoauthor) {
            if ($highRole == false) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        })
        // ->whereIn('document_status_id', [2,6])->orderBy('id', 'desc')
        ->whereIn('document_status_id', [2, 6])->orderBy('date_published', 'desc')
        // ->get();
        ->paginate(10, ['*'], 'meine-formulare-freigabe');

        // $formulareFreigabePaginated = $this->document->getUserPermissionedDocuments($formulareFreigabePaginated, 'meine-formulare-freigabe');
        $formulareFreigabeTree = $this->document->generateTreeview($formulareFreigabePaginated, array('pageDocuments' => true));
        // $formulareFreigabeTree = $this->document->generateTreeview( $formulareFreigabePaginated, array('pageDocuments' => true,'formulare' => true) );

        $formulareAllPaginated = Document::where(['document_type_id' => $docType])->where('document_status_id', 3)->where('active', 1)
        ->orderBy('id', 'desc')->get();
        
        // Hide documents that have publish date higher than today
        $formulareAllPaginated = $formulareAllPaginated->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });
        
        if ($docs == 'alle' && $sort == 'asc') {
            $formulareAllPaginated = $this->document->getUserPermissionedDocuments($formulareAllPaginated, 'alle-formulare', array('field' => 'date_published', 'sort' => 'asc'));
        } else {
            $formulareAllPaginated = $this->document->getUserPermissionedDocuments($formulareAllPaginated, 'alle-formulare', array('field' => 'date_published', 'sort' => 'desc'));
        }

        $formulareAllTree = $this->document->generateTreeview($formulareAllPaginated, array('pageDocuments' => true, 'showHistory' => true));
        // $formulareAllTree = $this->document->generateTreeview( $formulareAllPaginated, array('pageDocuments' => true,'formulare' => true) );
        $docType = DocumentType::find($docType);
        // dd($formulareEntwurfPaginated);

        // $request->flash();
        return view('dokumente.documentTemplates', compact('docType', 'formulareAllPaginated', 'formulareAllTree', 'formulareEntwurfPaginated', 'formulareEntwurfTree',
        'formulareFreigabePaginated', 'formulareFreigabeTree', 'docs', 'sort'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentType($type, Request $request)
    {
        $docs = $request->get('documents');
        $sort = $request->get('sort');

        $documentType = null;
        $documentsByTypePaginated = array();
        $documentsByTypeTree = array();
        $docsByTypeEntwurfPaginated = array();
        $docsByTypeEntwurfTree = array();
        $docsByTypeFreigabePaginated = array();
        $docsByTypeFreigabeTree = array();
        $highRole = false;
        if (ViewHelper::universalHasPermission(array(10)) == true) {
            $highRole = true;
        }
        foreach (DocumentType::all() as $docType) {
            if (str_slug($docType->name) == $type) {
                $documentType = $docType;
                break;
            }
        }

        if (isset($documentType)) {
            $myRundCoauthorArr = DocumentCoauthor::where('user_id', Auth::user()->id)->pluck('document_id')->toArray();
            $myRundCoauthor = Document::whereIn('id', $myRundCoauthorArr)->where('document_type_id', $docType)->pluck('id')->toArray();

            $docsByTypeEntwurfPaginated = Document::where('document_type_id', $documentType->id)
            ->where(function ($query) use ($highRole, $myRundCoauthor) {
                if ($highRole == false) {
                    $query->where('owner_user_id', Auth::user()->id)
                    ->orWhere('user_id', Auth::user()->id);
                    $query->orWhereIn('documents.id', $myRundCoauthor);
                }
            })
            ->where('deleted_at', null)
            ->where('document_status_id', 1)
            // ->orderBy('id', 'desc')->paginate(10, ['*'], str_slug($documentType->name.'-entwurf'));
            ->orderBy('date_published', 'desc')->paginate(10, ['*'], str_slug($documentType->name.'-entwurf'));

            $docsByTypeEntwurfTree = $this->document->generateTreeview($docsByTypeEntwurfPaginated, array('pageDocuments' => true));

            $docsByTypeFreigabePaginated = Document::where('document_type_id', $documentType->id)
             ->where(function ($query) use ($highRole, $myRundCoauthor) {
                 if ($highRole == false) {
                     $query->where('owner_user_id', Auth::user()->id)
                    ->orWhere('user_id', Auth::user()->id);
                     $query->orWhereIn('documents.id', $myRundCoauthor);
                 }
             })
            ->where('deleted_at', null)
            ->whereIn('document_status_id', [2, 6])
            // ->orderBy('id', 'desc')->paginate(10, ['*'], str_slug($documentType->name.'-freigabe'));
            ->orderBy('date_published', 'desc')->paginate(10, ['*'], str_slug($documentType->name.'-freigabe'));
            $docsByTypeFreigabeTree = $this->document->generateTreeview($docsByTypeFreigabePaginated, array('pageDocuments' => true));

            $documentsByTypePaginated = Document::where('document_type_id', $documentType->id)->where('deleted_at', null)
            ->where('document_status_id', 3)->orderBy('id', 'desc')
            ->get();
    
            // Hide documents that have publish date higher than today
            $documentsByTypePaginated = $documentsByTypePaginated->reject(function($document, $key){
                return Carbon::parse($document->date_published)->gt(Carbon::today());
            });
    
            if ($docs == 'alle' && $sort == 'asc') {
                $documentsByTypePaginated = $this->document->getUserPermissionedDocuments($documentsByTypePaginated, str_slug('all-'.$documentType->name), array('field' => 'date_published', 'sort' => 'asc'));
            } else {
                $documentsByTypePaginated = $this->document->getUserPermissionedDocuments($documentsByTypePaginated, str_slug('all-'.$documentType->name), array('field' => 'date_published', 'sort' => 'desc'));
            }

            $documentsByTypeTree = $this->document->generateTreeview($documentsByTypePaginated, array('pageDocuments' => true, 'showHistory' => true));
        }

        return view('dokumente.documentType', compact('documentType', 'documentsByTypeTree', 'documentsByTypePaginated', 'docsByTypeEntwurfPaginated',
            'docsByTypeEntwurfTree', 'docsByTypeFreigabePaginated', 'docsByTypeFreigabeTree', 'docs', 'sort'));
    }

    /**
     * Display the statistics for the document with the passed ID parameter.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentStats($id)
    {
        $document = Document::find($id);
        if ((ViewHelper::universalDocumentPermission($document) == false) || (ViewHelper::universalHasPermission(array(33)) == false)) { // JIRA Task NEPTUN-650
             return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $approvalAllMandants = false;
        $mandants = array();

        $document = $this->checkFreigabeRoles($document);

        $editorVariants = EditorVariant::where('document_id', $id)->get();
        foreach ($editorVariants as $ev) {
            if (!$ev->approval_all_mandants) {
                $allDocumentMandants = DocumentMandant::where('document_id', $id)->pluck('id')->toArray();
                $allDocumentMandantMandants = DocumentMandantMandant::whereIn('document_mandant_id', $allDocumentMandants)->get();
                foreach ($allDocumentMandantMandants as $admm) {
                    // dd($admm->documentMandant->editorVariant->approval_all_mandants);
                    if (!in_array($admm->mandant, $mandants)) {
                        $mandants[] = $admm->mandant;
                    }
                }
            } else {
                $approvalAllMandants = true;
            }
        }
        // dd($mandants);

        if ($approvalAllMandants) {
            $mandants = Mandant::all();
        }

        $users = User::all();
        $documentReaders = array();
        $documentReadersCount = array();
        $usersCountRead = array();
        $usersCountUnread = array();
        $usersCountNew = array();
        $usersCountActive = array();

        if (!isset($document)) {
            return back();
        }

        $documentReadersObj = UserReadDocument::where('document_group_id', $document->published->document_group_id)->get();

        foreach ($mandants as $mandant) {
            $usersCountRead[$mandant->id] = 0;
            $usersCountNew[$mandant->id] = 0;
            $documentReadersCount[$mandant->id] = array();

            foreach ($mandant->users as $mandantUser) {
                foreach ($documentReadersObj as $docReader) {
                    if ($mandantUser->active) {
                        if ($mandantUser->id == $docReader->user_id) {
                            if (!in_array($docReader, $documentReadersCount[$mandant->id])) {
                                array_push($documentReadersCount[$mandant->id], $docReader);
                            }
                        }
                    }
                }
            }

            foreach ($documentReadersCount[$mandant->id] as $reader) {
                if (Carbon::parse($reader->date_read_last)->eq(Carbon::parse('0000-00-00 00:00:00'))) {
                    $usersCountNew[$mandant->id] += 1;
                } else {
                    $usersCountRead[$mandant->id] += 1;
                }
            }

            $userIds = MandantUser::where('mandant_id', $mandant->id)->pluck('user_id');
            $usersCountActive[$mandant->id] = count(User::where('active', 1)->whereIn('id', $userIds)->get());
            $usersCountUnread[$mandant->id] = $usersCountActive[$mandant->id] - $usersCountRead[$mandant->id];
        }

        // dd($usersCountUnread);

        foreach ($documentReadersObj as $documentReader) {
            $documentReaders[$documentReader->user_id] = [
                'document_group_id' => $documentReader->document_group_id,
                'date_read' => $documentReader->date_read,
                'date_read_last' => $documentReader->date_read_last,
            ];
        }

        // dd($mandants);
        $mandants = array_values(array_sort($mandants, function ($value) {
            return $value['mandant_number'];
        }));

        // $data = '';
        return view('dokumente.statistik', compact('documentReaders', 'documentReadersCount', 'usersCountRead', 'usersCountUnread', 'usersCountNew', 'usersCountActive', 'mandants', 'users', 'document'));
    }

    /**
     * Display the history for the document with the passed ID parameter.
     *
     * @return array $uploadedNames
     */
    public function documentHistory($id)
    {
        if (ViewHelper::universalHasPermission(array(14)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $document = Document::find($id);
        $documentHistory = Document::where('document_group_id', $document->document_group_id)->orderBy('id', 'desc')->paginate(10, ['*'], 'dokument-historie');
        // $documentHistory = Document::where('document_group_id', $document->document_group_id)->whereIn('document_status_id', array(3,5))->orderBy('id', 'desc')->paginate(10, ['*'], 'dokument-historie');
        $documentHistoryTree = $this->document->generateTreeview($documentHistory, array('pageHistory' => true));
        // dd($documentHistory);
        return view('dokumente.historie', compact('document', 'documentHistory', 'documentHistoryTree'));
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
    private function fileUpload($model, $path, $files)
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
        //$filename = $image->getClientOriginalName();
        $diffMarker = time() + $counter;
        $newName = str_slug($model->id).'-'.date('d-m-Y-H:i:s').'-'.$diffMarker.'.'.$file->getClientOriginalExtension();
        $path = "$folder/$newName";
// 		dd($path);
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder.'/'.$filename);

        return $newName;
    }

    /**
     * Display the documents for the specified ISO category slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function isoCategoriesBySlug($slug, Request $request)
    {
        $docs = $request->get('documents');
        $sort = $request->get('sort');
        $highRole = false;
        if (ViewHelper::universalHasPermission(array(10)) == true) {
            $highRole = true;
        }
        $docType = $this->isoDocumentId;

        $canDeleteButton = false;

        $isoCategory = IsoCategory::where('slug', $slug)->first();
        $categoryIsParent = IsoCategory::where('iso_category_parent_id', $isoCategory->id)->get();
        $iso_category_id = $isoCategory->id;
        if ($isoCategory->iso_category_parent_id) {
            $isoCategoryParent = IsoCategory::where('id', $isoCategory->iso_category_parent_id)->first();
        } else {
            $isoCategoryParent = null;
        }

        $isoAllPaginated = Document::join('iso_categories', 'documents.iso_category_id', '=', 'iso_categories.id')
        ->where('documents.document_type_id', $docType)
        ->where('slug', $slug)
        ->where('documents.document_status_id', 3)
        ->orderBy('documents.date_published', 'desc')
        ->get(['*', 'iso_categories.name as isoCatName', 'documents.name as name', 'documents.id as id']);

        // Hide documents that have publish date higher than today
        $isoAllPaginated = $isoAllPaginated->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });

        if ($docs == 'alle' && $sort == 'desc') {
            $isoAllPaginated = $this->document->getUserPermissionedDocuments($isoAllPaginated, 'all-iso-dokumente', array('field' => 'iso_category_number', 'sort' => 'desc'));
        } else {
            $isoAllPaginated = $this->document->getUserPermissionedDocuments($isoAllPaginated, 'all-iso-dokumente', array('field' => 'iso_category_number', 'sort' => 'asc'));
        }

        $isoAllTree = $this->document->generateTreeview($isoAllPaginated, array('pageDocuments' => true, 'showHistory' => true));

        $uid = Auth::user()->id;
        $myRundCoauthorArr = DocumentCoauthor::where('user_id', Auth::user()->id)->pluck('document_id')->toArray();
        $myRundCoauthor = Document::whereIn('id', $myRundCoauthorArr)->where('document_type_id', $docType)->pluck('id')->toArray();
        $isoEntwurfPaginated = Document::join('iso_categories', 'documents.iso_category_id', '=', 'iso_categories.id')
        // ->join('editor_variants','documents.id','=','editor_variants.document_id')
        ->where(function ($query) use ($myRundCoauthor, $highRole) {
           if ($highRole == false) {
               $query->where('user_id', Auth::user()->id)
                    ->orWhere('owner_user_id', Auth::user()->id);
               $query->orWhereIn('documents.id', $myRundCoauthor);
           }
       }
        )
        ->where('documents.document_type_id', $docType)
        ->where('slug', $slug)
        ->where('documents.document_status_id', 1)
        ->orderBy('documents.name', 'asc')
        ->paginate(10, ['*', 'iso_categories.name as isoCatName', 'documents.name as name', 'documents.id as id'], 'iso-dokumente-entwurf');
        $isoEntwurfTree = $this->document->generateTreeview($isoEntwurfPaginated, array('pageDocuments' => true));
        // dd($isoEntwurfPaginated);
        $approval = DocumentApproval::where('user_id', $uid)->where('date_approved', null)->pluck('document_id')->toArray();

        $isoFreigabePaginated = Document::join('iso_categories', 'documents.iso_category_id', '=', 'iso_categories.id')
        // ->join('editor_variants','documents.id','=','editor_variants.document_id')
        ->where(function ($query) use ($myRundCoauthor, $highRole) {
            if ($highRole == false) {
                $query->where('user_id', Auth::user()->id)
                          ->orWhere('owner_user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        }
        )
        ->where('documents.document_type_id', $docType)
        ->where('slug', $slug)
        ->whereIn('documents.document_status_id', [2, 6])
        ->orWhereIn('documents.id', $approval)
        ->orderBy('documents.name', 'asc')
        ->paginate(10, ['*', 'iso_categories.name as isoCatName', 'documents.name as name', 'documents.id as id'], 'iso-dokumente-freigabe');
        $isoFreigabeTree = $this->document->generateTreeview($isoFreigabePaginated, array('pageDocuments' => true));

        $docType = DocumentType::find($docType);

        if (count($isoFreigabePaginated) < 1 && count($isoAllPaginated) < 1 && count($isoEntwurfPaginated) < 1 && count($categoryIsParent) < 1) {
            $canDeleteButton = true;
        }

        return view('dokumente.isoDocument', compact('docType', 'canDeleteButton', 'isoAllPaginated', 'isoAllTree', 'isoEntwurfPaginated',
        'isoEntwurfTree', 'isoFreigabePaginated', 'isoFreigabeTree', 'isoCategory', 'iso_category_id', 'isoCategoryParent', 'categoryIsParent', 'docs', 'sort'));
    }

    /**
     * Delete ISo category by id.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteIsoCategoriesById($id)
    {
        $isoCategory = IsoCategory::find($id);
        $name = $isoCategory->name;
        $isoCategory->delete();
        // dd($isoCategory);
        return redirect('/iso-kategorien')->with('messageSecondary', 'Gelöschte Kategorie "'.$name.'"');
    }

    /**
     * Display the documents for the specified ISO category slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function isoCategoriesIndex()
    {
        // $isoCategories = IsoCategory::all();
        $isoCategories = IsoCategory::where('active', 1)->get();

        return view('dokumente.isoCategoriesIndex', compact('isoCategories'));
    }

    /**
     * Search documents by request parameters.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // dd($request->all());
        if (empty($request->all())) {
            return redirect('/');
        }

        $docs = $request->get('documents');
        $sort = $request->get('sort');

        $docType = $request->input('document_type_id');
        $docTypeSearch = DocumentType::find($docType);
        $docTypeName = $docTypeSearch->name;
        $iso_category_id = $request->input('iso_category_id');
        $search = $request->input('search');
        $isoCategoryName = '';
        $docTypeSlug = '';

        if (isset($docTypeSearch)) {
            $docTypeSlug = str_slug($docTypeSearch->name);
        }

        // status entwurf
        $searchEntwurfPaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) {
            $query->where('owner_user_id', Auth::user()->id)->orWhere('user_id', Auth::user()->id);
        })
        ->where('document_status_id', 1);

        if ($docType == 4) {
            if (!empty($iso_category_id)) {
                $searchEntwurfPaginated = $searchEntwurfPaginated->where('iso_category_id', $iso_category_id);
            }
            $isoCategory = IsoCategory::find($iso_category_id);
            if ($isoCategory != null) {
                $isoCategoryName = str_slug($isoCategory->name);
            } else {
                $isoCategoryName = str_slug($docTypeSearch->name);
            }
            if ($isoCategory->name == '') {
                $docTypeName = $docTypeSearch->name;
            } else {
                $docTypeName = $isoCategory->name;
            }
        }

        // dd($searchEntwurfPaginated->get());
        // $searchEntwurfPaginated = $searchEntwurfPaginated->orderBy('id', 'desc')->paginate(10, ['*'], $docTypeName.'-entwurf');
        $searchEntwurfPaginated = $searchEntwurfPaginated->orderBy('date_published', 'desc')->paginate(10, ['*'], $docTypeName.'-entwurf');
        $searchEntwurfTree = $this->document->generateTreeview($searchEntwurfPaginated, array('pageDocuments' => true));

        // status im freigabe prozess
        $searchFreigabePaginated = Document::where('document_type_id', $docType)
        ->where(function ($query) {
            $query->where('owner_user_id', Auth::user()->id)->orWhere('user_id', Auth::user()->id);
        })
        ->whereIn('document_status_id', [2, 6]);

        if ($docType == 4) {
            if (!empty($iso_category_id)) {
                $searchFreigabePaginated = $searchFreigabePaginated->where('iso_category_id', $iso_category_id);
            }
        }

        // DB::enableQueryLog();

        // $searchFreigabePaginated = $searchFreigabePaginated->orderBy('id', 'desc')->paginate(10, ['*'], $docTypeName.'-freigabe');
        $searchFreigabePaginated = $searchFreigabePaginated->orderBy('date_published', 'desc')->paginate(10, ['*'], $docTypeName.'-freigabe');
        $searchFreigabeTree = $this->document->generateTreeview($searchFreigabePaginated, array('pageDocuments' => true));

        $resultAllPaginated = Document::where('document_type_id', $docType)->where('document_status_id', 3)->where('active', 1);

        // QMR query options
        if ($docType == 3) {
            if (stripos($search, 'QMR') !== false) {
                $qmr = trim(str_ireplace('QMR', '', $search));
                $qmrNumber = (int) preg_replace('/[^0-9]+/', '', $qmr);
                $qmrString = preg_replace('/[^a-zA-Z]+/', '', $qmr);

                $resultAllPaginated = $resultAllPaginated->where(function ($query) use ($qmrNumber, $qmrString) {
                    if ($qmrNumber) {
                        $query = $query->where('qmr_number', 'LIKE', $qmrNumber);
                    }
                    if (!empty($qmrString)) {
                        $query = $query->where('additional_letter', 'LIKE', '%'.$qmrString.'%');
                    }
                });

                // where('document_type_id', 3);
            } else {
                $resultAllPaginated = $resultAllPaginated->where('name', 'LIKE', '%'.$search.'%')->orderBy('id', 'desc');
            }
        }

        // ISO query options
        elseif ($docType == 4) {
            if (!empty($iso_category_id)) {
                $resultAllPaginated = $resultAllPaginated->where('iso_category_id', $iso_category_id);
            }

            if (stripos($search, 'ISO') !== false) {
                $iso = trim(str_ireplace('ISO', '', $search));
                $isoNumber = (int) preg_replace('/[^0-9]+/', '', $iso);
                $isoString = preg_replace('/[^a-zA-Z]+/', '', $iso);
                // dd($isoNumber);

                $resultAllPaginated = $resultAllPaginated->where(function ($query) use ($isoNumber, $isoString) {
                    if ($isoNumber) {
                        $query = $query->where('iso_category_number', 'LIKE', $isoNumber);
                    }
                    if (!empty($isoString)) {
                        $query = $query->where('additional_letter', 'LIKE', '%'.$isoString.'%');
                    }
                });
            } else {
                $resultAllPaginated = $resultAllPaginated->where('name', 'LIKE', '%'.$search.'%')->orderBy('id', 'desc');
            }
        }

        // General query options
        else {
            $resultAllPaginated = $resultAllPaginated->where('name', 'LIKE', '%'.$search.'%')->orderBy('id', 'desc');
        }

        // $resultAllPaginated = $resultAllPaginated->paginate(10, ['*'], 'ergebnisse-alle');

        // Set sorting field according to document type
        $sortField = 'date_published';
        if ($docType == 3 && (stripos($search, 'QMR') !== false)) {
            $sortField = 'qmr_number';
        }
        if ($docType == 4 && (stripos($search, 'ISO') !== false)) {
            $sortField = 'iso_category_number';
        }

        if ($docs == 'alle' && $sort == 'asc') {
            $resultAllPaginated = $this->document->getUserPermissionedDocuments($resultAllPaginated, 'ergebnisse-alle', array('field' => $sortField, 'sort' => 'asc'));
        } else {
            $resultAllPaginated = $this->document->getUserPermissionedDocuments($resultAllPaginated, 'ergebnisse-alle', array('field' => $sortField, 'sort' => 'desc'));
        }

        // dd($resultAllPaginated);

        // dd(DB::getQueryLog());

        $resultAllTree = $this->document->generateTreeview($resultAllPaginated, array('pageDocuments' => true, 'showHistory' => true));

        /* qm-rundschreiben slug fix */
        if ($docTypeSlug == 'qm-rundschreiben') {
            $docTypeSlug = 'rundschreiben-qmr';
        }
        /* end qm-rundschreiben slug fix */

        /* iso-document slug fix */
        if ($docType == 4) {
            $docTypeSlug = $isoCategoryName;
        }
        /* end iso-document slug fix */

        /* volagedokumente slug fix */
        if ($docType == 5) {
            $docTypeSlug = 'vorlagedokumente';
        }
        /* end vorlagedokumente slug fix */

        return view('dokumente.suchergebnisse')->with(compact('search', 'docType', 'docTypeSearch', 'docTypeName',
            'resultAllPaginated', 'resultAllTree', 'searchEntwurfPaginated', 'searchEntwurfTree',
            'searchFreigabePaginated', 'searchFreigabeTree', 'iso_category_id', 'isoCategoryName', 'docTypeSlug', 'docs', 'sort'));
    }

    /**
     * Set back button.
     *
     * @param file object           $file
     * @param string                $folder
     * @param DB object(collection) $model
     *
     * @return string $newName
     */
    private function setBackButton($id, $attachment = false)
    {
        $docType = $this->detectDocumentType($id);
        if ($attachment == true) {
        }

        return $newName;
    }

    /**
     * detect document type.
     *
     * @param file object           $file
     * @param string                $folder
     * @param DB object(collection) $model
     *
     * @return string $newName
     */
    private function detectDocumentType($id)
    {
        $document = Document::find($id);
        if ($document->pdf_upload == true) {
            return 'pdf';
        } elseif ($document->document_type_id == 5) {
            return 'upload';
        }

        return 'editor';
    }

    /**
     * detect if model is dirty or not.
     *
     * @return bool
     */
    private function dirty($dirty, $model)
    {
        if ($model->isDirty() || $dirty == true) {
            return true;
        }

        return false;
    }

    /**
     * detect if model is dirty or not.
     *
     * @return bool
     */
    private function clearUsers($users)
    {
        $clearedArray = array();
        foreach ($users as $k => $user) {
            $u = User::find($user->user_id);
            if (!in_array($user->user_id, $clearedArray) && $u->active == 1) {
                $clearedArray[] = $user->user_id;
            } else {
                unset($users[$k]);
            }
        }

        return $users;
    }

    /**
     * detect if model is dirty or not.
     *
     * @return bool
     */
    private function generateUniqeLink($length = 6)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; ++$i) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    /**
     * publish check and.
     *
     * @return bool
     */
    private function publishProcedure($document, $sending = false)
    {
        $id = $document->id;
        $document->document_status_id = 3;
        $document->published_at = Carbon::now(); // NEPTUN-679 publish procedure upgrade
        // $document->date_published = Carbon::now(); // NEPTUN-582, NEPTUN-590
        $document->save();
        // dd($document);
        $continue = true;
        $uniqeUrl = '';
        $sendOptions = $sending; // Flag for determining if the document should be sent to users or not

        $oldDocumentVersion = PublishedDocument::where('document_group_id', $document->document_group_id)->orderBy('id', 'DESC')->first();

        $publishedDocs = PublishedDocument::where('document_id', $id)->first();
        if ($publishedDocs == null) {
            $continue = true;
            $uniqeUrl = '';
            if ($oldDocumentVersion != null) {
                $continue = false;
                $uniqeUrl = $oldDocumentVersion->url_unique;
            }
            while ($continue) {
                $uniqeUrl = $this->generateUniqeLink();
                if (PublishedDocument::where('url_unique', $uniqeUrl)->count() != 1) {
                    $continue = false;
                }
            }
            $publishedDocs = PublishedDocument::create(['document_id' => $id, 'document_group_id' => $document->document_group_id,
                            'url_unique' => $uniqeUrl, ]);
            $publishedDocs->fill(['document_id' => $id, 'document_group_id' => $document->document_group_id])->save();
        } else {
            $publishedDocs->fill(['document_id' => $id, 'document_group_id' => $document->document_group_id])->save();
            if ($publishedDocs->deleted_at != null) {
                $publishedDocs->restore();
            }
        }

        /*Set attached documents as aktuell */
        $variantsAttachedDocuments = EditorVariant::where('document_id', $document->id)->get();
        foreach ($variantsAttachedDocuments as $vad) {
            $editorVariantDocuments = $vad->editorVariantDocument;
            foreach ($editorVariantDocuments as $evd) {
                $evd->document_status_id = 3;
                $evd->save();
                $doc = Document::find($evd->document_id);
                $doc->document_status_id = 3;
                $doc->save();
            }
        }
        /* End set attached documents as actuell */

        // Add published docs for sending if necessary
        if ($sendOptions) {
            // Set document flag for sending to TRUE
            $document->send_published = true;
            $document->save();

            // Get emails and clasify them by sending types
            $emailSettings = UserEmailSetting::all();

            foreach ($emailSettings as $emailSetting) {
                // Get user data for the email setting
                $user = User::find($emailSetting->user_id);

                // Skip email sending if user has the email sending flag disabled
                // Skip email sending if document type has no publish sending flag
                if (($user->email_reciever == false) || ($document->documentType->publish_sending == false)) {
                    continue;
                }

                // Check if user has document permission
                // $documentPermission = ViewHelper::documentVariantPermission($document, $user->id)->permissionExists;
                // if($documentPermission){
                    
                    // Check if the role assigned to the email setting is in the document verteiler roles for the document
                    $allowed = false;
                    if ($emailSetting->email_recievers_id == 0) {
                        $allowed = true;
                    } else {
                        $documentRecievers = $document->documentMandants->first()->documentMandantRole->pluck('role_id');
                        if ($documentRecievers->contains($emailSetting->email_recievers_id)) {
                            $allowed = true;
                        }
                    }

                    // Proceed if role assignment criteria is met
                    if ($allowed) {
                        // Sending method: email (This method is avaliable to all users)
                        if ($emailSetting->sending_method == 1) {
                            // Check if the document type is corresponding the mailing settings
                            if (in_array($emailSetting->document_type_id, [0, $document->document_type_id])) {
                                UserSentDocument::create(['user_email_setting_id' => $emailSetting->id, 'document_id' => $document->id]);
                            }
                        }

                        // Sending method: email + attachment (ONLY system roles can recieve documents as attachments: this is handled in the user profile form)
                        if ($emailSetting->sending_method == 2) {
                            // Check if the document type is corresponding the mailing settings
                            if (in_array($emailSetting->document_type_id, [0, $document->document_type_id])) {
                                // Save sent documents and settings log to DB
                                UserSentDocument::create(['user_email_setting_id' => $emailSetting->id, 'document_id' => $document->id]);
                            }
                        }

                        // Sending method: fax (This method sends the document via fax commands)
                        if ($emailSetting->sending_method == 3) {
                            // Check if the document type is corresponding the mailing settings
                            if (in_array($emailSetting->document_type_id, [0, $document->document_type_id])) {
                                UserSentDocument::create(['user_email_setting_id' => $emailSetting->id, 'document_id' => $document->id]);
                            }
                        }
                    }

                // }
            }
        }
    }

    /**
     * detect if user has privileges to see the comments.
     *
     * @param Collection $document
     * @param int        $uid      (user id)
     *
     * @return object
     */
    public function commentVisibility($document, $uid = 0)
    {
        if ($uid == 0) {
            $uid = Auth::user()->id;
        }
        $commentVisibility = new \StdClass();
        /* Common user */

        $commentVisibility->user = false;
        $commentVisibility->freigabe = false;
        // if($uid == $document->user_id || ( $document->documentCoauthor != null && $uid == $document->documentCoauthor->user_id ) || $uid == $document->owner_user_id)
        if (ViewHelper::universalDocumentPermission($document, false, false, true)) {
            $commentVisibility->user = true;
        }
        /* End Common user */

        /* Freigabe user */
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        // dd($mandantUsers);
        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();

            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 9 || $umr->role_id == 1) {
                    $commentVisibility->freigabe = true;
                }
            }
        }

        /* End Freigabe user */
        return $commentVisibility;
    }

    /**
     * Generate PDF with the list of all users that need to.
     *
     * @param Collection $document
     *
     * @return object
     */
    public function postVersand(Request $request, $id, $variantNumber)
    {
        $allMandants = false;
        $variant = $variantNumber;
        $document = Document::find($id);

        // Get all users with sending options
        $userSettings = UserEmailSetting::where('sending_method', 4)
            ->whereIn('document_type_id', [0, $document->document_type_id])
            ->where('active', 1)->get();
        $users = User::whereIn('id', $userSettings->pluck('user_id'))->groupBy('id')->get();
        $settingsMandant = Mandant::whereIn('id', $userSettings->pluck('mandant_id'))->get();

        // Get list of user mandants that have permission for the document variant
        $mandantsList = array();
        foreach ($users as $user) {
            $editorVariants = ViewHelper::documentVariantPermission($document, $user->id, true); // Third parameter is for showing all variants
            foreach ($editorVariants->variants as $ev) {
                if ($ev->approval_all_mandants == true) {
                    // Handle the case where a variant has approval for ALL mandants
                    $allMandants = true;
                } elseif (($variantNumber == $ev->variant_number) && ($ev->hasPermission == true)) {
                    $dm = DocumentMandant::where('editor_variant_id', $ev->id)->pluck('id');
                    $dmm = DocumentMandantMandant::whereIn('document_mandant_id', $dm)->pluck('mandant_id');
                    foreach ($dmm as $id) {
                        if (!in_array($id, $mandantsList)) {
                            $mandantsList[] = $id;
                        }
                    }
                }
            }
        }

        // Find mandants by id
        if ($allMandants == true) {
            $mandants = Mandant::all();
        } else {
            $mandants = Mandant::whereIn('id', $mandantsList)->get();
        }

        // Filter mandants with permissions by selecting the mandant from the user email settings
        $mandants = $mandants->filter(function ($value, $key) use ($settingsMandant) {
            return $settingsMandant->contains($value);
        });

        // dd($userSettings);

        $margins = new \StdClass();
        $margins->left = 10;
        $margins->right = 10;
        $margins->top = 10;
        $margins->bottom = 10;
        $margins->headerTop = 0;
        $margins->footerTop = 5;
        $render = view('pdf.post-versand', compact('users', 'mandants', 'userSettings'));
        $pdf = new PdfWrapper();
        $pdf->AddPage($or, $margins->left, $margins->right, $margins->top, $margins->bottom, $margins->headerTop, $margins->footerTop);
        $pdf->WriteHTML($render);

        return $pdf->stream();
    }

    /**
     * Set return url depeding if document has uniqe url or not.
     *
     * @param Collection $document
     * @param int        $uid      (user id)
     *
     * @return object
     */
    private function setDocumentReturnUrl($document, $uid = 0)
    {
        $document = Document::find($id);
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->first();
            $id = $publishedDocs->document_id;
            $datePublished = $publishedDocs->created_at;
            // add UserReadDocumen
            $readDocs = UserReadDocument::where('document_group_id', $publishedDocs->document_group_id)
                    ->where('user_id', Auth::user()->id)->get();
                    // dd($readDocs);
            if (count($readDocs) == 0) {
                UserReadDocument::create([
                    'document_group_id' => $publishedDocs->document_group_id,
                    'user_id' => Auth::user()->id,
                    'date_read' => Carbon::now(),
                    'date_read_last' => Carbon::now(),
                ]);
            } else {
                foreach ($readDocs as $readDoc) {
                    $readDoc->date_read_last = Carbon::now();
                    $readDoc->save();
                }
            }
        }//end check if user has uniqe id
    }

    /**
     * Check if user is Struktur Admin, Dokumenten Verfasser, Rundschreiben Verfasser.
     *
     * @return bool
     */
    private function canCreateEditDoc($document = null)
    {
        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 1 || $umr->role_id == 11 || $umr->role_id == 13 || ($document != null && ViewHelper::universalDocumentPermission($document, false, false, true))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Return role_id if user is Struktur Admin, Dokumenten Verfasser, Rundschreiben Verfasser.
     *
     * @return int or bool
     */
    private function returnRole()
    {
        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        $roleArray = array();
        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 11 || $umr->role_id == 13 || $umr->role_id == 1) {
                    $roleArray[] = $umr->role_id;
                }
            }
        }
        if (count($roleArray) > 2) {
            return true;
        } elseif (count($roleArray) == 1) {
            return $roleArray[0];
        }

        return false;
    }

    /**
     * Universal dosument permission chekc.
     *
     * @param array      $userArray
     * @param collection $document
     * @param bool       $message
     *
     * @return bool || response
     */
    private function universalDocumentPermission($document, $message = true, $freigeber = false)
    {
        return ViewHelper::universalDocumentPermission($document, $message, $freigeber);
    }

    /**
     * Check if freigabe roles are set, if not then add all to database.
     *
     * @param collection $document
     *
     * @return $document
     */
    private function checkFreigabeRoles($document)
    {
        $mandantRoles = array();
        // dd( $document );
        /*if($document->documentMandantRoles != null)
            $mandantRoles = $document->documentMandantRoles->where('role_id',0);
        */
        $mandants = $document->documentMandants;
        $mandantRolesAll = 0;
        $mandantsHasPermissionAll = 0;
        foreach ($mandants as $dc) {
            $mandantRolesAll = $mandantRolesAll + count($dc->documentMandantRole);
            if ($dc->editorVariant->approval_all_mandants == 1) {
                ++$mandantsHasPermissionAll;
            }
        }
        if (($document->approval_all_roles != 1 && (count($mandants) == 0 && $mandantsHasPermissionAll == 0))
            || ($document->approval_all_roles != 1 && count($mandantRolesAll) < 1)) {
            $document->approval_all_roles = 1;
            $document->save();
            // if( count($mandantRoles) ){
            //     DocumentMandantRole::whereIn('id',$mandantRoles)->delete();
            // }
        }

        return $document;
    }

    /**
     * Document variant permission.
     *
     * @param collection $document
     *
     * @return object $object
     */
    private function documentVariantPermission($document, $userId = null)
    {
        /*  class $object stores 2 attributes:
            1. permissionExists( this is a global hasPermissionso we dont have to iterate again to see if permission exists  )
            2. variants (to store variants)[duuh]
        */

        $object = new \StdClass();
        $object->permissionExists = false;

        // Added check for custom user id lookup
        if (isset($userId)) {
            $mandantId = MandantUser::where('user_id', $userId)->pluck('id');
            $mandantUserMandant = MandantUser::where('user_id', $userId)->pluck('mandant_id');
        } else {
            $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
            $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        }

        $mandantIdArr = $mandantId->toArray();
        $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
        $mandantRolesArr = $mandantRoles->toArray();
        $variants = EditorVariant::where('document_id', $document->id)->get();
        $hasPermission = false;

        foreach ($variants as $variant) {
            if ($hasPermission == false) {//check if hasPermission is already set
                if ($variant->approval_all_mandants == true) {//database check
                    if ($document->approval_all_roles == true) {//database check
                            $hasPermission = true;
                        $variant->hasPermission = true;
                        $object->permissionExists = true;
                    } else {
                        foreach ($variant->documentMandantRoles as $role) {// if not from database then iterate trough roles
                                if (in_array($role->role_id, $mandantRolesArr)) {//check if it exists in mandatRoleArr
                                 $variant->hasPermission = true;
                                    $hasPermission = true;
                                    $object->permissionExists = true;
                                }
                        }//end foreach documentMandantRoles
                    }
                } else {
                    foreach ($variant->documentMandantMandants as $mandant) {
                        if ($this->universalDocumentPermission($document) == true) {
                            $hasPermission = true;
                            $variant->hasPermission = true;
                            $object->permissionExists = true;
                        } elseif (in_array($mandant->mandant_id, $mandantIdArr)) {
                            if ($document->approval_all_roles == true) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                                $object->permissionExists = true;
                            } else {
                                foreach ($variant->documentMandantRoles as $role) {
                                    if (in_array($role->role_id, $mandantRolesArr)) {
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                        $object->permissionExists = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }

        $object->variants = $variants;

        return $object;
    }

//end documentVariant permission

    /**
     * Return german months.
     *
     * @param int $id
     *
     * @return string
     */
    private function getGermanMonthName($id)
    {
        $months = array(
            1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'Ausgust',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember',
            );

        return $months[$id];
    }

    /**
     * Return pdf margins.
     *
     * @param collection $document
     *
     * @return object $margins
     */
    private function setPdfMargins($document)
    {
        $margins = new \StdClass();
       /* Set the document orientation */
        $margins->orientation = 'P';
        if ($document->landscape == true) {
            $margins->orientation = 'L';
        }

        /* End  Set the document orientation */

        /* Set the document margins */
        // $margins->left = 10;
        // $margins->right = 10;
        // $margins->top = 10;
        // $margins->bottom = 10;
        // $margins->headerTop = 0;
        // $margins->footerTop = 5;

        if ($document->document_type_id == $this->isoDocumentId && $margins->orientation == 'P') {// if  iso document and orientation portrait
            $margins->left = 10;
            $margins->right = 10;
            $margins->top = 40;
            $margins->bottom = 25;
            $margins->headerTop = 0;
            $margins->footerTop = 5;
        } elseif ($document->document_type_id == $this->isoDocumentId && $margins->orientation == 'L') {// if  iso document and orientation landscape
            $margins->left = 10;
            $margins->right = 50;
            $margins->top = 30;
            $margins->bottom = 10;
            $margins->headerTop = 0;
            $margins->footerTop = 5;
        } elseif ($document->document_type_id != $this->isoDocumentId && $margins->orientation == 'P') {// if not iso document and orientation portrait
            $margins->left = 10;
            $margins->right = 8;
            $margins->top = 65;
            $margins->bottom = 30;
            $margins->headerTop = -0;
            $margins->footerTop = 0;

            if ($document->document_template == 1) {
                $margins->left = 10;
                $margins->right = 5;
                $margins->top = 10;
                $margins->bottom = 20;
                $margins->headerTop = 10;
                $margins->footerTop = 0;
            }
        } else { // if not iso document and orientation landscape
            $margins->left = 5;
            $margins->right = 0;
            $margins->top = 50;
            $margins->bottom = 10;
            $margins->headerTop = 0;
            $margins->footerTop = 0;
            if ($document->document_template == 1) {
                $margins->left = 5;
                $margins->right = 5;
                $margins->top = 10;
                $margins->bottom = 10;
                $margins->headerTop = 0;
                $margins->footerTop = 0;
            }
        }

        /* End Set the document margins */

        return $margins;
    }
}
