<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use App\Helpers\ViewHelper;
use App\Http\Requests\DocumentRequest;
use App\Http\Repositories\SearchRepository;
use App\Http\Repositories\DocumentRepository;

use App\WikiPage;
use App\WikiPageStatus;
use App\WikiRole;
use App\WikiCategory;
use App\WikiCategoryUser;
use App\WikiPageHistory;
use App\User;
use App\MandantUser;
use App\MandantUserRole;

class WikiController extends Controller
{
     /**
     * Class constructor
     */
    public function __construct(SearchRepository $searchRepo, DocumentRepository $docRepo)
    {
        $this->middleware('wiki')->only('index', 'search', 'show');
        $this->middleware('wiki.editor')->except('index', 'search', 'show');
        $this->search = $searchRepo;
        $this->document = $docRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        // $userRoles = ViewHelper::getUserRole( Auth::user()->id );
        // // dd(WikiRole::where('role_id', $userRoles)->get());
        $wikiPermissions = ViewHelper::getWikiUserCategories();
        // dd($wikiPermissions);
        $wikiCategories = $wikiPermissions->categoriesIdArray;
        
        $topCategories = WikiCategory::where('top_category',1)->whereIn('id',$wikiCategories)->get();
        
        $newestWikiEntriesPagination = WikiPage::where('status_id',2)->whereIn('category_id',$wikiCategories)
        ->orderBy('updated_at','DESC')->paginate(10, ['*'], 'neueste-beitraege');
        $newestWikiEntries = $this->document->generateWikiTreeview($newestWikiEntriesPagination);
        
        $myWikiPagesPagination = WikiPage::where('user_id', Auth::user()->id)->whereIn('category_id',$wikiCategories)->orderBy('updated_at','DESC')->paginate(10, ['*'], 'meine-beitraege');
        $myWikiPages = $this->document->generateWikiTreeview($myWikiPagesPagination);
        $searchResults = [];
        return view('wiki.index', compact('topCategories','newestWikiEntries','newestWikiEntriesPagination','myWikiPages','myWikiPagesPagination','searchResults'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( ViewHelper::universalHasPermission( array(15) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $wikiStatuses = WikiPageStatus::all();
        $wikiRoles = WikiRole::all();
        if( ViewHelper::universalHasPermission(array()) )
            $wikiCategories = WikiCategory::all();
        else{
            $wikiCategoryUsers = WikiCategoryUser::where('user_id', Auth::user()->id)->pluck('wiki_category_id')->toArray();
            $wikiCategories = WikiCategory::whereIn('id',$wikiCategoryUsers)->get();
        }
        
        return view('formWrapper', compact('data','wikiCategories','wikiStatuses','wikiRoles') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //auto date
        //auto update
        //user_id
        //dd($request->all() );
        $wiki = WikiPage::create( $request->all() );
        
        $wikiStatuses = WikiPageStatus::all();
        $wikiRoles = WikiRole::all();
        $wikiCategories = WikiCategory::all();
        
        session()->flash('message',trans('wiki.wikiCreateSuccess'));
        return redirect('wiki/'.$wiki->id.'/edit' );
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = WikiPage::find($id);
        $wikiPermissions = ViewHelper::getWikiUserCategories();
        if( !in_array($data->category_id,$wikiPermissions->categoriesIdArray) )
            return redirect('/wiki')->with('messageSecondary', trans('documentForm.noPermission'));
            
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/wiki')->with('messageSecondary', trans('documentForm.noPermission'));
        
        if( ViewHelper::universalHasPermission( array(15) ) == false && ( $data->status_id == 1 || $data->status_id == 3)  )
            return redirect('/wiki')->with('messageSecondary', trans('documentForm.noPermission'));
        
        
        return view('wiki.show', compact('data') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( ViewHelper::universalHasPermission( array(15) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        
        $data = WikiPage::find($id);
        if( $data == null  )
            return redirect('/wiki')->with('messageSecondary', trans('wiki.noWikiPage'));
        $wikiStatuses = WikiPageStatus::all();
        $wikiRoles = WikiRole::all();
        if( ViewHelper::universalHasPermission(array()) )
            $wikiCategories = WikiCategory::all();
        else{
            $wikiCategoryUsers = WikiCategoryUser::where('user_id', Auth::user()->id)->pluck('wiki_category_id')->toArray();
            $wikiCategories = WikiCategory::whereIn('id',$wikiCategoryUsers)->get();
        }
        return view('formWrapper', compact('data','wikiCategories','wikiStatuses','wikiRoles') );
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
        // dd( $request->all() );
        $dirty = false;
        $data = WikiPage::find($id);
        $dirty = ViewHelper::isDirty($data);
        $data->fill( $request->all() );
        $dirty = $data->isDirty();
        $data->save();
       
        if( $dirty ){
            WikiPageHistory::create([
            'user_id' => Auth::user()->id,
            'wiki_page_id' => $id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ]);
        }
        session()->flash('message',trans('wiki.wikiEditSuccess'));
        return redirect()->back();
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wikiActivation($id)
    {
        $wiki = WikiPage::find($id);
        if($wiki->active == true){
            $wiki->active = false;
            $wiki->status_id = 3;
            
        }
        else{
            $wiki->active = true;
            $wiki->status_id = 2;
        }
            
        $wiki->save();
        
     return redirect('wiki/'.$id);
    }
    
    
     /**
     * Search documents by request parameters.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $userRoles = ViewHelper::getUserRole( Auth::user()->id );
        // dd(WikiRole::where('role_id', $userRoles)->get());
        $wikiRoles = WikiRole::whereIn('role_id', $userRoles)->pluck('wiki_category_id')->toArray();
        // $wikiCategories =ViewHelper::getAvailableWikiCategories();
        $wikiPermissions = ViewHelper::getWikiUserCategories();
        $wikiCategories = $wikiPermissions->categoriesIdArray;
        // \DB::enableQueryLog();
        $topCategories = WikiCategory::where('top_category',1)->whereIn('id',$wikiCategories)->get();
        
        // if( ViewHelper::universalHasPermission(array()) )
        //     $topCategories = WikiCategory::where('top_category',1)->get();
        
        $newestWikiEntriesPagination = WikiPage::whereIn('category_id',$wikiCategories)->where('status_id',2)->orderBy('created_at','DESC')->paginate(10, ['*'], 'neueste-beitraege');
        
        $newestWikiEntries = $this->document->generateWikiTreeview($newestWikiEntriesPagination);
        
        $myWikiPagesPagination = WikiPage::where('user_id', Auth::user()->id)->whereIn('category_id',$wikiCategories)->orderBy('created_at','DESC')->paginate(10, ['*'], 'meine-beitraege');
        $myWikiPages = $this->document->generateWikiTreeview($myWikiPagesPagination);
        
        if(empty($request->all())) return redirect('/wiki');
            $searchInput = $request->get('search');
            
        $search = $this->search->searchWiki( $request->all() );  
        
        $userRoles = ViewHelper::getUserRole( Auth::user()->id );
        // dd(WikiRole::where('role_id', $userRoles)->get());
        $wikiRoles = WikiRole::whereIn('role_id', $userRoles)->pluck('wiki_category_id')->toArray();
        
        $topCategories = WikiCategory::where('top_category',1)->whereIn('id',$wikiCategories)->get();
        
       
        $myWikiPagesPagination = WikiPage::whereIn('category_id',$wikiCategories)->where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->paginate(10, ['*'], 'meine-beitraege');
        $myWikiPages = $this->document->generateWikiTreeview($myWikiPagesPagination);
        
        $searchResults = $search->paginate(10, ['*'], 'wiki-suche');
        // dd( $searchResults );
        $searchResultsTree = $this->document->generateWikiTreeview( $searchResults );
    
        return view('wiki.index', compact('topCategories','searchResults','searchResultsTree','newestWikiEntries',
        'newestWikiEntriesPagination','myWikiPages','myWikiPagesPagination','searchInput')); 
        // return view('wiki.index', compact('search','topCategories','newestWikiEntries','newestWikiEntriesPagination','myWikiPages','myWikiPagesPagination','searchInput')); 
    }
    
    /**
     * Wiki admin managment
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function managmentAdmin()
    {  
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $empty = new \StdClass();
        $empty->id = '';
        $empty->name = 'Alle';
        $data = array();
        $statuses = WikiPageStatus::all();
     
        $wikiUsers = WikiCategoryUser::where('user_id',Auth::user()->id )->pluck('wiki_category_id')->toArray();
        
        $wikiPermissions = ViewHelper::getWikiUserCategories();
        $wikiCategories = $wikiPermissions->categoriesIdArray;
        
        $categories = WikiCategory::whereIn('id',$wikiCategories)->get();
        $wikies = WikiPage::whereIn('category_id',$wikiCategories)->orderBy('updated_at','desc')->get();
        //   if( ViewHelper::universalHasPermission(array()) ){
        //     $categoriesId = WikiCategory::pluck('id')->toArray();
        //     $categories = WikiCategory::whereIn('id',$categoriesId)->get();
        //     $wikies = WikiPage::whereIn('category_id',$categoriesId)->orderBy('created_at','desc')->get();
        // }
        $users = WikiPage::orderBy('id','asc')->pluck('user_id')->toArray();
        $wikiUsers = User::whereIn('id',$users)->get();
        $admin = true;
        
        return view('wiki.managment', compact('data','wikies', 'statuses','wikiUsers','categories','admin')); 
    }
    
    /**
     * Wiki admin managment
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function managmentUser()
    {  
        if( ViewHelper::universalHasPermission( array(15) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $data = array();
        
        $wikiUsers = WikiCategoryUser::where('user_id',Auth::user()->id )->pluck('wiki_category_id')->toArray();
        
        $categories = WikiCategory::whereIn('id',$wikiUsers)->get();
        $wikies = WikiPage::whereIn('category_id',$wikiUsers)->orderBy('created_at','desc')->get();
           if( ViewHelper::universalHasPermission(array()) ){
            $categoriesId = WikiCategory::pluck('id')->toArray();
            $categories = WikiCategory::whereIn('id',$categoriesId)->get();
            $wikies = WikiPage::whereIn('category_id',$categoriesId)->orderBy('created_at','desc')->get();
        }
        
        $wikies = WikiPage::orderBy('created_at','desc')->get();
        $statuses = WikiPageStatus::all();
        $categories = WikiCategory::all();
        $users = WikiPage::orderBy('id','asc')->pluck('user_id')->toArray();
        $wikiUsers = User::whereIn('id',$users)->get();
        $admin = false;
        
        return view('wiki.managment', compact('data','wikies', 'statuses','wikiUsers','categories','admin')); 
    }
    
     /**
     * Search documents by request parameters.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchManagment(Request $request)
    {
        $data = new \StdClass(); 
        foreach($request->all() as $k => $v){
            $data->$k = $v;
        }
        $wikies = $this->search->searchManagmentSearch($data);
        $statuses = WikiPageStatus::all();
        $wikiUsers = WikiCategoryUser::where('user_id',Auth::user()->id )->pluck('wiki_category_id')->toArray();
        
        $categoriesId = WikiCategoryUser::where('user_id',Auth::user()->id )->pluck('wiki_category_id')->toArray();
        // $categoriesId = WikiCategoryUser::where('user_id',Auth::user()->id )->pluck('wiki_category_id')->toArray();
        $wikiPermissions = ViewHelper::getWikiUserCategories();
        $wikiCategories = $wikiPermissions->categoriesIdArray;
        $categories = WikiCategory::whereIn('id',$wikiCategories)->get();
        
        $users = WikiPage::orderBy('id','asc')->pluck('user_id')->toArray();
        
        $wikiUsers = User::whereIn('id',$users)->get();
        if($data->admin == true)
            $admin = true;
        else 
            $admin = false;
        
       return view('wiki.managment', compact('data','wikies', 'statuses','wikiUsers','categories','admin')); 
    }
    
    /**
     * Duplicate existing wiki page
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
        $originalWiki = WikiPage::find($id);
        
        $wiki = $originalWiki->replicate();
        $wiki->name .= ' (Kopie)';
        $wiki->save();
        //  dd($wiki);
        
        return redirect('/wiki/'.$wiki->id.'/edit');
    }
}
