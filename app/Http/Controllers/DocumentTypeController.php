<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use Auth;
use File;
use App\Helpers\ViewHelper;
use App\User;
use App\MandantUser;
use App\MandantUserRole;
use App\Document;
use App\UserReadDocument;
use App\DocumentType;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!ViewHelper::universalHasPermission( array(6)))
            return redirect('/')->with('message', trans('documentForm.noPermission'));
            
        $documentTypes = DocumentType::orderBy('order_number', 'asc')->get();
        $documentTypesSubmenu = DocumentType::where('menu_position', 1)->orderBy('order_number', 'asc')->get();
        $documentTypesMenu = DocumentType::where('menu_position', 2)->orderBy('order_number', 'asc')->get();
        return view('dokument-typen.index', compact('documentTypes','documentTypesMenu','documentTypesSubmenu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $documentType = new DocumentType();
        $documentType->name = $request->input('name');
        $documentType->document_art = $request->input('document_art');
        $documentType->document_role = $request->input('document_role');
        if($request->has('read_required')) $documentType->read_required = true;
        if($request->has('allow_comments')) $documentType->allow_comments = true;
        if($request->has('visible_navigation')) $documentType->visible_navigation = true;
        if($request->has('publish_sending')) $documentType->publish_sending = true;
        $documentType->active = true;
        $documentType->menu_position = $request->input('menu_position');
        
        $documentType->order_number = 1;
        $documentTypeLast = DocumentType::orderBy('id', 'desc')->first();
        if($documentTypeLast) $documentType->order_number = $documentTypeLast->order_number+1;
        
        $documentType->active = true;
        $documentType->save();
        return back()->with('message', 'Dokument Typ erfolgreich gespeichert.');
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
        //
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
        $documentType = DocumentType::find($id);
        
        if($request->has('save')){
        
            $documentType->name = $request->input('name');
            
            if($request->input('document_art')) $documentType->document_art = $request->input('document_art');
            $documentType->document_role = $request->input('document_role');
            
            if($request->has('read_required')) $documentType->read_required = true;
            else $documentType->read_required = false;
            
            if($request->has('allow_comments')) $documentType->allow_comments = true;
            else $documentType->allow_comments = false;
            
            if($request->has('visible_navigation')) $documentType->visible_navigation = true;
            else $documentType->visible_navigation = false;
            
            if($request->has('publish_sending')) $documentType->publish_sending = true;
            else $documentType->publish_sending = false;
        }
        
        if($request->has('activate'))
            $documentType->active = !$request->input('activate');
            
        if($request->has('switch_menu')){
            $menuPosition = $request->input('switch_menu');
            $documentType->menu_position = $menuPosition;
            $lastDocType = DocumentType::where('menu_position', $menuPosition)->orderBy('order_number', 'desc')->first();
            // dd(count($lastDocType));
            if(count($lastDocType)) $documentType->order_number = $lastDocType->order_number += 1;
            else $documentType->order_number = 1;
        }
            
        
        $documentType->save();
        
        return back()->with('message', trans('dokumentTypenForm.updated'));
    }

    /**
     * Increase order number for the item.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sortUp($id)
    {
        // dd('up '.$id);
        $docType = DocumentType::find($id);
        $docTypePrev = DocumentType::where('menu_position', $docType->menu_position)->where('order_number', $docType->order_number-1)->first();
        // dd($docTypePrev);
        
        if($docTypePrev){
            $docType->order_number -= 1;
            $docType->save();
            $docTypePrev->order_number += 1;
            $docTypePrev->save();
        }
        
        return back()->with('message', trans('dokumentTypenForm.updated'));
    }
    
    /**
     * Decrease order number for the item.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sortDown($id)
    {
        // dd('down '.$id);
        $docType = DocumentType::find($id);
        $docTypeNext = DocumentType::where('menu_position', $docType->menu_position)->where('order_number', $docType->order_number+1)->first();
        // dd($docTypeNext);
        if($docTypeNext){
            $docType->order_number += 1;
            $docType->save();
            $docTypeNext->order_number -= 1;
            $docTypeNext->save();
        }
        
        return back()->with('message', trans('dokumentTypenForm.updated'));
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
    
    /**
     * Development sandbox function for testing and debugging
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function devSandbox(Request $request)
    {
         
        // ViewHelper::logSendPublished(true, "Yeeee");
        
        // if(ViewHelper::getMandantIsNeptun(Auth::user()->id)) echo 'NEPTUN Mandant';
        
        // $mandantUsersRoles = MandantUserRole::where('role_id', 40)->groupBy('mandant_user_id')->get();
        // $mandantUsers = MandantUser::whereNotIn('id', array_pluck($mandantUsersRoles, 'mandant_user_id'))->orderBy('mandant_id')->orderBy('user_id')->get();
        // foreach($mandantUsers as $mu){
        //     if($mu->mandant->rights_admin == false)
        //         MandantUserRole::create(['mandant_user_id' => $mu->id, 'role_id' => 40]);
        // }
        
        // $mandantUsersRoles = MandantUserRole::where('role_id', 14)->groupBy('mandant_user_id')->get();
        // dd($mandantUsersRoles);
        // foreach($mandantUsersRoles as $mur) $mur->forceDelete();
        // $mandantUsers = MandantUser::whereNotIn('id', array_pluck($mandantUsersRoles, 'mandant_user_id'))->orderBy('mandant_id')->orderBy('user_id')->get();
        // foreach($mandantUsers as $mu){
        //     MandantUserRole::create(['mandant_user_id' => $mu->id, 'role_id' => 14]);
        // }
        
    }
    
    
}
