<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\JuristResubmissionPriority;
use App\Helpers\ViewHelper;

class WiedervorlagenStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
//        echo 'coming soon...';
        if(!ViewHelper::universalHasPermission( array(6)))
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        $wiedervorlagenStatuss = $wiedervorlagenStatusOptions = JuristResubmissionPriority::all();
        return view('wiedervorlagen-status.index', compact('wiedervorlagenStatuss', 'wiedervorlagenStatusOptions'));
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
        //dd($request);
        $wiedervorlagenStatus = JuristResubmissionPriority::create($request->all());
        return back()->with('message', trans('wiedervorlagenStatus.show-save'));
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
        $wiedervorlagenStatus = JuristResubmissionPriority::find($id);
        
        if($request->has('activate')){
            $status = !$request->input('activate');
            $wiedervorlagenStatus->active = $status;
        } 
        
        $wiedervorlagenStatus->name = $request->input('name');
        $wiedervorlagenStatus->color = $request->input('color');
        $wiedervorlagenStatus->bgcolor = $request->input('bgcolor');
        $wiedervorlagenStatus->save();
        
        return back()->with('message', trans('wiedervorlagenStatus.show-update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wiedervorlagenStatus = JuristResubmissionPriority::find($id);
        // dd($wiedervorlagenStatus);
        $name = $wiedervorlagenStatus->name;
        $wiedervorlagenStatus->delete();
        return back()->with('messageSecondary', trans('wiedervorlagenStatus.show-deleted').' "'.$name.'"');
    }
}
