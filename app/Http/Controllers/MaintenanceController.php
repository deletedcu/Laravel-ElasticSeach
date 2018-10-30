<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Helpers\ViewHelper;

use App\UserEmailSetting;

class MaintenanceController extends Controller
{
     /**
     * Show the index page for app/database maintenance.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( ViewHelper::universalHasPermission() == true ){
            return view('simple-pages.maintenance');
        }
    }
    
    public function deleteSendingPublished()
    {
        if( ViewHelper::universalHasPermission() == true ){
            
            // Delete all UserEmailSetting (this also CASCADE DELETEs UserSentDocument entries)
            UserEmailSetting::whereNotNull('id')->delete();
            
            return back()->with('message', trans('maintenance.databaseCleanupSuccess'));
        }
    }
}
