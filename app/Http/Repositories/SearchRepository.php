<?php
namespace App\Http\Repositories;
/**
 * User: Marijan
 * Date: 14.06.2016.
 * Time: 08:11
 */

use Auth;
use DB;

use Carbon\Carbon;
use App\Helpers\ViewHelper;

use App\Mandant;
use App\MandantUser;
use App\User;
use App\WikiPage;
use App\WikiCategory;
use App\WikiCategoryUser;

class SearchRepository
{
    /**
     * Search Phone list
     *
     * @return object array $array
     */
     public function phonelistSearch($request ){
        
         $query = Mandant::where('active',1);
        if($request->has('deletedMandants') )
             $query = $query->withTrashed();
        
         if( $request->has('parameter') )
            $query->where('name','LIKE', '%'.$request->get('parameter').'%')->orWhere('mandant_number','=',$request->get('parameter') );
            
        $mandants = $query->orderBy('mandant_number')->get();   
            
            // dd($request->all());
        if( count($mandants) ){
            
            foreach($mandants as $mandant){
                $mandantQuery = $mandant->users();
                
               /* if( $request->has('parameter') )
                    $mandantQuery->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );*/
               
                if($request->has('deletedMandants') )
                    $mandantQuery->withTrashed();
                
                $mandant->usersInMandants = $mandantQuery->get();
            }
        }
       else{
           
            // $mandants = Mandant::where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
            // $myMandant = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
            // if(!$mandants->contains($myMandant))
            //     $mandants->prepend($myMandant);
                
            $query = User::where('users.id','>',0);
                
            if( $request->has('parameter') )
                $query->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );
            
            if($request->has('deletedUsers') )
                $query ->withTrashed();        
             
            // $additionalQuery = $query;   
            $users = $query->get();   
            if( count($users) ){
                // DB::enableQueryLog();
                $usersIds = $query->pluck('id');   
                $userMandants = MandantUser::whereIn('user_id',$usersIds)->pluck('mandant_id');
                $mandants = Mandant::whereIn('id',$userMandants)->orderBy('mandant_number')->get();
                
                foreach($mandants as $mandant){
                    $userQuery = User::whereIn('id',$usersIds);
                     if( $request->has('parameter') )
                        $userQuery->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );
                     if($request->has('deletedUsers') )
                        $userQuery ->withTrashed();    
                
                    $mandant->usersInMandants = $userQuery->get();
                     
                    // dd($mandant->usersInMandants );
                      
                    if( count($mandant->usersInMandants) > 0 )
                        $mandant->openTreeView = true;
                }
                
            }
        }

        return $mandants;
    }
    /**
     * Search Wiki subject or inhalt
     *
     * @return object array $array
     */     
     public function searchWiki( $request ){
        $searchParam =  $request['search'];
        $filterWikiPages = WikiPage::all();
        $wikiIds = array();
        foreach($filterWikiPages as $wikiPage){
            // Filter out images to only get the content
            $filterContent = preg_replace("/<img[^>]+\>/i", "", $wikiPage->content);
            if(stripos($filterContent, $searchParam) !== false){
                $wikiIds[] = $wikiPage->id;    
            }
        }
        // $wikiCategories = ViewHelper::getAvailableWikiCategories() ;
        $wikiPermissions = ViewHelper::getWikiUserCategories();
        $wikiCategories = $wikiPermissions->categoriesIdArray;
        
        $results = WikiPage::whereIn('category_id',$wikiCategories)
        ->where(function ($query) use($searchParam,$wikiIds) {
          $query->where('name','LIKE','%'.$searchParam.'%' )->orWhere('subject','LIKE','%'.$searchParam.'%' )->orWhereIn('id', $wikiIds);
        //   ->where('name','LIKE','%'.$searchParam.'%' )->orWhere('subject','LIKE','%'.$searchParam.'%' )->orWhereIn('id', $wikiIds);  
        });
        if( ViewHelper::universalHasPermission( array(15) ) == false   )
            $results->whereNotIn('status_id',array(1,3) );
        return $results;
     }
     
    /**
     * Search Wiki subject or inhalt
     *
     * @return object array $array
     */     
     public function searchWikiCategories( $request ){
        $searchParam =  $request['search'];
        $categoryId = $request['category'];
        // $wikiCategory = WikiCategory::find($categoryId);
        
        $filterWikiPages = WikiPage::all();
        $wikiIds = array();
        foreach($filterWikiPages as $wikiPage){
            // Filter out images to only get the content
            $filterContent = preg_replace("/<img[^>]+\>/i", "", $wikiPage->content);
            if(stripos($filterContent, $searchParam) !== false){
                $wikiIds[] = $wikiPage->id;    
            }
        }
       
        $results = WikiPage::where('category_id',$categoryId)
        ->where(function ($query) use($searchParam,$wikiIds) {
                $query->where('name','LIKE','%'.$searchParam.'%' )->orWhere('subject','LIKE','%'.$searchParam.'%' )->orWhereIn('id', $wikiIds);
            });
            
        if( ViewHelper::universalHasPermission( array(15) ) == false   )
            $results->whereNotIn('status_id',array(1,3) );
            
        return $results;
     }
     
    /**
     * Search Wiki something
     *
     * @return object array $array
     */     
     public function searchManagmentSearch( $request ){
        $categoriesId = WikiCategoryUser::where('user_id',Auth::user()->id )->pluck('wiki_category_id')->toArray();
     
        $query = WikiPage::whereIn('category_id',$categoriesId)->orderBy('updated_at','desc');
        if( ViewHelper::universalHasPermission(array()) ){
            $categoriesId = WikiCategory::pluck('id')->toArray();
            $usersId = WikiCategoryUser::whereIn('user_id', $categoriesId )->pluck('user_id')->toArray();
            $query = WikiPage::whereIn('category_id',$categoriesId)->orderBy('updated_at','desc');
        }
        
        if( $request->name != '' )
            $query->where('name','like','%'.$request->name.'%');
        
        if( $request->date_from != '' )
             $query->where('updated_at','>=', Carbon::parse($request->date_from) );
         
        if( $request->date_to != '' )
            $query->where('updated_at','<=', Carbon::parse($request->date_to));
         
        if( $request->category != '' )
            $query->where( 'category_id',$request->category );
             
        if( $request->status != '' )
            $query->where( 'status_id',$request->status );
         
        if( isset($request->ersteller) && $request->ersteller != '' )
            $query->where( 'user_id',$request->ersteller );
        $results = $query->get() ;
        
         return $results;
         
     }
}
