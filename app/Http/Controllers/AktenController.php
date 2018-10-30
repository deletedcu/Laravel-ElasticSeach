<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ViewHelper;
use App\JuristCategory;
use App\Document;
use App\Role;
use App\DocumentStatus;
use App\JuristFileType;

class AktenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!ViewHelper::universalHasPermission(array(6, 35))) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }
        $users = ViewHelper::getUserCollectionByRole([Role::JURISTBENUTZER ]);
        $documentStatus = DocumentStatus::all();
        $juristenCategories = JuristCategory::all();
        $documentArts = JuristFileType::all();
        
        return view('stjepan_aufgabe.aktenAnlegen', compact('users', 'documentStatus','juristenCategories','documentArts'));
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
        //
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
        //
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
