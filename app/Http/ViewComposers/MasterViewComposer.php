<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Request;

use App\DocumentStatus;

class MasterViewComposer
{
    
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {   
        $formWrapperData = $this->detectMethod();
        $genderHerr = new \StdClass();
        $genderHerr->id = 'Herr';
        $genderHerr->name = 'Herr';
        $genderFrau = new \StdClass();
        $genderFrau->id = 'Frau';
        $genderFrau->name = 'Frau';
        $gender = array($genderHerr,$genderFrau);
        $view->with('formWrapperData',$formWrapperData );
        $view->with('documentStatus', DocumentStatus::all() );
        $view->with('gender', $gender );
        $view->with('collections', array() );
        $view->with('counter', 0 );
        
        if( Request::is('*/create') || !Request::is('*/edit')){
            $data = '';
            $view->with('data', $data );
        }
       
        
    }
    
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
     protected function detectMethod(){
        $formWrapperData = new \stdClass();
        $formWrapperData->action = 'store';
        $formWrapperData->method = 'post';
        $formWrapperData->form = '';
        $formWrapperData->controller = Request::segment(1);
        $formWrapperData->title = trans( 'controller.'.$formWrapperData->controller );
        $formWrapperData->buttonMethod = trans('formWrapper.save');
        $formWrapperData->formUrl = '';
        $formWrapperData->fileUpload = '';
        if( Request::is('*/edit') ){
            $formWrapperData->action = 'PATCH';
            $formWrapperData->method = 'post';
            $formWrapperData->form = '';
            $formWrapperData->formUrl = '/'.Request::segment(2);
            $formWrapperData->title = trans( 'controller.'.$formWrapperData->controller );
            $formWrapperData->buttonMethod = trans('formWrapper.update');
        } 
        elseif( Request::is('*/search') ){
            $formWrapperData->form = '';
            $formWrapperData->button = 'Save';
            $formWrapperData->title = trans( 'controller.'.$formWrapperData->controller );
            $formWrapperData->buttonMethod = trans('formWrapper.update');
        }
        return $formWrapperData;
     }
}