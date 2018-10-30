<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ViewHelper;
use App\JuristCategory;
use App\Document;
use App\DocumentType;
use App\Role;
use App\JuristCategoryMeta;
use App\DocumentStatus;
use App\JuristFileType;
use App\DocumentComment;
use Auth;

class BeratungDocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!ViewHelper::universalHasPermission(array(6, 35))) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }
        $users = ViewHelper::getUserCollectionByRole([Role::JURISTBENUTZER ]);
        $documentStatus = DocumentStatus::all();
        $juristenCategories = JuristCategory::all();
        $documentArts = JuristFileType::all();
        
        return view('formWrapper', compact('users', 'documentStatus','juristenCategories','documentArts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([ 'version'=>'0'  ]);
        JuristCategory::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id=1212)
    {
        $document = Document::find($id);
        $variants = ['','',''];
        $favoriteCategories = JuristCategory::all();
         $commentVisibility = new \StdClass();
        /* Common user */

        $commentVisibility->user = true;
        $commentVisibility->freigabe = true;
        
        $documentCommentsFreigabe = DocumentComment::where('document_id', $id)->where('freigeber', 1)->orderBy('created_at', 'DESC')->get();
        $myComments = DocumentComment::where('document_id', $id)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        $documentComments = DocumentComment::where('document_id', $id)->where('freigeber', 0)->orderBy('created_at', 'DESC')->get();
        
        return view('beratungsdokumente.show',compact('document', 'variants', 'favoriteCategories', 'commentVisibility', 'documentCommentsFreigabe', 'myComments', 'documentComments'));
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
        $users = ViewHelper::getUserCollectionByRole(array(Role::JURISTADMINISTRATOR,Role::JURISTBENUTZER,Role::JURISTENDOKUMENTANLEGER) );
        $documentArts = JuristCategoryMeta::where('active',1)->get();
        $documentTypes = DocumentType::where('jurist_document',1)->whereNotIn('id',array(DocumentType::NOTIZEN))->get();
        $documentFilteredStatus = DocumentStatus::whereIn('id',[DocumentStatus::AKTUELL,DocumentStatus::ENTWURF,DocumentStatus::ARCHIVE])->get();
        
        return view('formWrapper',compact('data','users','documentArts','documentFilteredStatus','documentTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = JuristCategory::find($id);
        $model->fill($request->all())->save();
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
}
