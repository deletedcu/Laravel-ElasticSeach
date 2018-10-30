<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Request;

use App\DocumentType;
use App\IsoCategory;
use App\JuristCategory;

class SidebarViewComposer
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
        $view->with('documentTypes', DocumentType::orderBy('order_number', 'asc')->get() );
        $view->with('documentTypesSubmenu', DocumentType::where('menu_position', 1)->orderBy('order_number', 'asc')->get() );
        $view->with('documentTypesMenu', DocumentType::where('menu_position', 2)->orderBy('order_number', 'asc')->get() );
        $view->with('isoCategories', IsoCategory::where('active', 1)->get() );
        $view->with('juristenCategories', JuristCategory::where('beratung',0)->where('parent',1)->where('active',1)->get() );
        $beratungOne = JuristCategory::where('beratung',1)->where('parent',1)->where('active',1)->get();
        // dd($beratungOne);
        $view->with('juristenCategoriesBeratung', $beratungOne );
       
        // dd(JuristCategory::where('beratung',1)->where('parent',1)->where('active',1)->get());
        // $view->with('isoCategories', IsoCategory::all() );
    }
    
   
}