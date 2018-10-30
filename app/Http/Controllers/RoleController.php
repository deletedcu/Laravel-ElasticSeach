<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Helpers\ViewHelper;

use App\Role;
use App\User;

class RoleController extends Controller
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
        $roles = Role::all();
        $users = User::all();
        return view('rollen.index', compact('roles', 'users'));
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
        $role = new Role();
        
        $role->name = $request->input('name');
        
        $role->active = true;
        $role->system_role = false;
        
        $role->mandant_required = false;
        $role->admin_role = false; 
        $role->mandant_role = false;
        $role->wiki_role = false; 
        $role->phone_role = false;
        
        if($request->has('wiki')) $role->wiki_role = true;
        
        if($request->has('role')){
            
            $inputRoles =  $request->input('role');
           
            foreach($inputRoles as $inputRole){
                if($inputRole == 'required') $role->mandant_required = true;
                if($inputRole == 'admin') $role->admin_role = true; 
                if($inputRole == 'mandant') $role->mandant_role = true;
                if($inputRole == 'wiki') $role->wiki_role = true; 
                if($inputRole == 'phone') $role->phone_role = true;
            }
        }
        
        // dd($request);
        $role->save();
        return back()->with('message', 'Rolle erfolgreich gespeichert.');
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
        
        // dd($request->all());
        $role = Role::find($id);
        
        $role->name = $request->input('name');
        
        if($request->has('activate')) $role->active = !$request->input('activate');
        
        $role->mandant_required = false;
        $role->admin_role = false; 
        $role->mandant_role = false;
        $role->wiki_role = false; 
        $role->phone_role = false;
        
        if($request->has('role')){
            
            $inputRoles =  $request->input('role');
           
            foreach($inputRoles as $inputRole){
                if($inputRole == 'required') $role->mandant_required = true;
                if($inputRole == 'admin') $role->admin_role = true; 
                if($inputRole == 'mandant') $role->mandant_role = true;
                if($inputRole == 'wiki') $role->wiki_role = true; 
                if($inputRole == 'phone') $role->phone_role = true;
            }
        }
        
        if($request->has('wiki')) $role->wiki_role = true;
        if($request->has('mandant')) $role->mandant_role = true;
        if($request->has('phone')) $role->phone_role = true;
        
        // dd($request);
        $role->save();
        return back()->with('message', 'Rolle erfolgreich aktualisiert.');
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
