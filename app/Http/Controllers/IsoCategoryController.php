<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\IsoCategoryRequest;
use App\Helpers\ViewHelper;

use App\IsoCategory;

class IsoCategoryController extends Controller
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
        $isoCategories = $isoCategoryOptions = IsoCategory::all();
        return view('iso-kategorien.index', compact('isoCategories', 'isoCategoryOptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IsoCategoryRequest $request)
    {
        $isoCategory = new IsoCategory();
        if($request->has('category_id')) $isoCategory->iso_category_parent_id = $request->input('category_id');
        $isoCategory->parent = $request->has('parent');
        $isoCategory->name = $request->input('name');
        $isoCategory->slug = str_slug($isoCategory->name);
        $isoCategory->active = true;
        $isoCategory->save();
        return back()->with('message', 'ISO Kategorie erfolgreich gespeichert.');
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
    public function update(IsoCategoryRequest $request, $id)
    {
        $isoCategory = IsoCategory::find($id);
        
        if($request->has('activate')){
            $status = !$request->input('activate');
            $isoCategory->active = $status;
        } 
        
        if($request->has('category_id')){
            if($isoCategory->parent) return back()->with('error', 'Hauptkategorie kann nicht als Unterkategorie gespeichert werden.');
            $isoCategory->iso_category_parent_id = $request->input('category_id');
        } 
        
        $isoCategory->name = $request->input('name');
        $isoCategory->slug = str_slug($isoCategory->name);
        $isoCategory->save();
        
        return back()->with('message', 'ISO Kategorie erfolgreich aktualisiert.');
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
