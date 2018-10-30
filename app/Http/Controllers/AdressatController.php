<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ViewHelper;

use App\Adressat;

class AdressatController extends Controller
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
        $adressate = Adressat::all();
        return view('adressaten.index', compact('adressate'));
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
        $adressat = new Adressat();
        $adressat->name = $request->input('name');
        $adressat->active = true;
        $adressat->save();
        
        return back()->with(['message'=>'Adressat erfolgreich gespeichert.']);
        // return redirect()->route('adressaten.index')->with(['message'=>'Task was successful!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $adressat = Adressat::find($id);
        
        if($request->has('activate'))
            $adressat->active = !$request->input('activate');
        
        if($request->has('save'))
            $adressat->name = $request->input('name');    
        
        $adressat->save();
        
        return back()->with('message', 'Adressat erfolgreich aktualisiert');
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
