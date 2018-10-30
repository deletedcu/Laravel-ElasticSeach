<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use App\Document;
use App\DocumentType;
use App\PublishedDocument;
use App\FavoriteDocument;
use App\FavoriteCategory;

use App\Helpers\ViewHelper;
use App\Http\Repositories\DocumentRepository;

class FavoritesController extends Controller
{
    
    public function __construct(DocumentRepository $docRepo){
        $this->favorites =  $docRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // if($request->all())
            // dd($request->all());
        
        $sort = $request->get('sort');
        $categoryParam = $request->get('category');
        $type = $request->get('type');
        
        $favorites =  $favoriteDocuments = $favoritesTreeview = $favoritesPaginated = array();
        $favoriteDocuments = FavoriteDocument::where('user_id', Auth::user()->id)->get();
        $documentTypes = DocumentType::all();
        
        $favoriteCategories = FavoriteCategory::where('user_id', Auth::user()->id)->get();
        
        $hasFavorites = $hasFavoriteCategories = false;
        $favoritesAll = $favoritesCategorised = array();
        // dd(array_pluck($documentTypes, array('id', 'name')));
        
        foreach ($documentTypes as $docType) {
            
            $favsArray = array();
            $favsTmp = array();
            
            $favsArray['document_type_id'] = $docType->id;
            $favsArray['document_type_name'] = $docType->name;
    
            foreach($favoriteDocuments as $fav){
                if(empty($fav->favorite_categories_id)){ // Prevent loading favorites with user-defined categories as duplicates in document-type treeview
                    $published = PublishedDocument::where('document_group_id', $fav->document_group_id)->orderBy('id', 'desc')->first();
                    if(isset($published->document)){
                        if($published->document->document_type_id == $docType->id  && $published->document->active == 1 && $published->document->document_status_id == 3){
                            array_push($favsTmp, $published->document);
                            $hasFavorites = true;
                        }
                    }
                }
            }
    
            // $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('name', 'asc')->paginate(10, ['*'], 'seite');
            if($sort && ($type == $docType->id)){
                if($sort == 'asc') $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('date_published', 'asc')->paginate(10, ['*'], 'page-type-'.str_slug($docType->id));
                else $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('date_published', 'desc')->paginate(10, ['*'], 'page-type-'.str_slug($docType->id));
            } else $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('date_published', 'desc')->paginate(10, ['*'], 'page-type-'.str_slug($docType->id));
            
            $favoritesTreeview = $this->favorites->generateTreeview($favoritesPaginated, array('pageFavorites' => true,'showDelete' => true,'showAttachments' => true ));
            
            $favsArray['favoritesPaginated'] = $favoritesPaginated;
            $favsArray['favoritesTreeview'] = $favoritesTreeview;
            
            array_push($favoritesAll, $favsArray);
        }
        
        foreach ($favoriteCategories as $category) {
            
            $favsArray = array();
            $favsTmp = array();
            
            $favsArray['category'] = $category;
    
            foreach($favoriteDocuments as $fav){
                if($fav->favorite_categories_id){ // Check for favorite category ID
                    $published = PublishedDocument::where('document_group_id', $fav->document_group_id)->orderBy('id', 'desc')->first();
                    if(isset($published->document) && isset($fav->favorite_categories_id)){
                        if(($fav->favorite_categories_id == $category->id) && ($published->document->active == 1) && ($published->document->document_status_id == 3)){
                            array_push($favsTmp, $published->document);
                            $hasFavoriteCategories = true;
                        }
                    }
                }
            }
    
            // $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('name', 'asc')->paginate(10, ['*'], 'seite');
            if($sort && ($categoryParam == $category->id)){
                if($sort == 'asc') $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('date_published', 'asc')->paginate(10, ['*'], 'page-category-'. str_slug($category->id));
                else $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('date_published', 'desc')->paginate(10, ['*'], 'page-category-'. str_slug($category->id));
            } else $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('date_published', 'desc')->paginate(10, ['*'], 'page-category-'. str_slug($category->id));
            $favoritesTreeview = $this->favorites->generateTreeview($favoritesPaginated, array('pageFavorites' => true,'showDelete' => true,'showAttachments' => true ));
            
            $favsArray['favoritesPaginated'] = $favoritesPaginated;
            $favsArray['favoritesTreeview'] = $favoritesTreeview;
            
            array_push($favoritesCategorised, $favsArray);
        }
        
        // dd($favoritesCategorised);
        
        return view('favoriten.index', compact('favoritesAll', 'hasFavorites', 'favoritesCategorised', 'hasFavoriteCategories'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editCategories(){
        $favoriteCategories = FavoriteCategory::where('user_id', Auth::user()->id)->get();
        return view('favoriten.editCategories', compact('favoriteCategories'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFavoriteCategory(Request $request)
    {
        if($request->get('name')) FavoriteCategory::create(['name' => $request->get('name'), 'user_id' => Auth::user()->id]);
        return back()->with('message', trans('favoriten.category-saved'));
    }
    
    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFavoriteCategory(Request $request)
    {
        // dd($request->all());
        $id = $request->get('category_id');
        $name = $request->get('name');
        
        if($request->get('save')){
            FavoriteCategory::find($id)->update(['name' => $name]);
        }
        
        if($request->get('delete')){
            FavoriteCategory::destroy($id);
            return back()->with('message', trans('favoriten.category-removed'));
        }
        
        return back()->with('message', trans('favoriten.category-updated'));
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
        $document = Document::find($request->get('document_id'));
        $favorite = FavoriteDocument::where('document_group_id',$document->document_group_id)->where('user_id', Auth::user()->id)->first();
        // dd(!$favorite);
        if($request->get('save')){
            if($request->has('category_name')){
                // Save with new category name
                $favCat = FavoriteCategory::create(['name' => $request->get('category_name'), 'user_id' => Auth::user()->id]);
                if($favorite)
                    $favorite->update(['favorite_categories_id' => $favCat->id]);
                else 
                    FavoriteDocument::create(['document_group_id'=> $document->document_group_id, 'user_id' => Auth::user()->id, 'favorite_categories_id' => $favCat->id ]);
            } elseif ($request->has('category_id') && (in_array($request->get('category_id'), [0, 'new']) == false)){
                // Save with selected/existing category ID
                $favCat = FavoriteCategory::where('id', $request->get('category_id'))->where('user_id', Auth::user()->id)->first();
                if($favCat){
                    if($favorite)
                        $favorite->update(['favorite_categories_id' => $request->get('category_id')]);
                    else
                        FavoriteDocument::create( ['document_group_id'=> $document->document_group_id, 'user_id' => Auth::user()->id, 'favorite_categories_id' => $request->get('category_id') ]);
                } else return back()->with('message', trans('favoriten.category-unavailable'));
            } else {
                // Save without category, will show by Document-Type
                if($favorite)
                    $favorite->update(['favorite_categories_id' => NULL]);
                else 
                    FavoriteDocument::create( ['document_group_id'=> $document->document_group_id, 'user_id' => Auth::user()->id ]);
            }
        }
        return back()->with('message', trans('dokumentShow.favoriteSaved'));
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCategory($id)
    {
        if(isset($id)){
            $category = FavoriteCategory::where('id', $id)->where('user_id', Auth::user()->id)->first();
            if($category){
                if($category->delete()) return back()->with('message', trans('favoriten.category-removed'));
            } else return back()->with('message', trans('favoriten.category-removed-error'));
        }
        return back();
    }
}
