<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Repositories\SearchRepository;
use App\Http\Repositories\DocumentRepository;
use App\Http\Repositories\UtilityRepository;

use App\Document;
use App\DocumentType;
use App\EditorVariant;
use App\User;
use App\Role;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\InternalMandantUser;
use App\WikiPage;

use App\Helpers\ViewHelper;

class SearchController extends Controller
{
     /**
     * Class constructor
     *
     */
    public function __construct(SearchRepository $searchRepo, DocumentRepository $docRepo, UtilityRepository $utilRepo )
    {
        $this->search =  $searchRepo;
        $this->document =  $docRepo;
        $this->utility =  $utilRepo;
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parameter = null;
        $parameterArray = array();
        $searchQmrIso = false;
        $results = array();
        $resultsWiki = array();
        $variants = array();
        $searchResultsPaginated = array();
        // $documentTypes = DocumentType::all();
        $documentTypes = DocumentType::where('active', 1)->where('visible_navigation', 1)->get();
        
        
        // Fill the $mandantUsers array with users that share the same mandant as the logged in user
        $mandantUsers = array();
        foreach(MandantUser::all() as $mandantUser){
            foreach(Auth::user()->mandantUsers as $loggedUserMandant){
                if($loggedUserMandant->mandant_id == $mandantUser->mandant_id){
                    if(!in_array($mandantUser, $mandantUsers))
                        array_push($mandantUsers, $mandantUser);
                }
            }
        }
        // dd($mandantUsers);
        
        if($request->get('sort') == 'asc') $sort = 'asc';
        else $sort = 'desc';
        
        if($request->has('parameter')){
            
            $parameter = $request->input('parameter');
            $documents = Document::where('document_status_id', 3)->where('active', 1);
            
            if( stripos($parameter, 'QMR') !== false ){
                // Check for occurence of "QMR" string, search for QMR documents if found
                $searchQmrIso = true;
                $qmr = trim(str_ireplace('QMR', '', $parameter));
                $qmrNumber = (int) preg_replace("/[^0-9]+/", "", $qmr);
                $qmrString = preg_replace("/[^a-zA-Z]+/", "", $qmr);
                // dd(!empty($qmrString));
                
                $documents = $documents->where(function($query) use ($qmrNumber, $qmrString) {
                    if($qmrNumber) $query = $query->where('qmr_number', 'LIKE', $qmrNumber);
                    if(!empty($qmrString)) $query = $query->where('additional_letter', 'LIKE', '%'.$qmrString.'%');
                });
                
                $documents->where('document_type_id', 3);

            } elseif( stripos($parameter, 'ISO') !== false ){
                // Check for occurence of "ISO" string, search for ISO documents if found
                $searchQmrIso = true;
                $iso = trim(str_ireplace('ISO', '', $parameter));
                $isoNumber = (int) preg_replace("/[^0-9]+/", "", $iso);
                $isoString = preg_replace("/[^a-zA-Z]+/", "", $iso);
                // dd($isoNumber);
                
                $documents = $documents->where(function($query) use ($isoNumber, $isoString) {
                    if($isoNumber) $query = $query->where('iso_category_number', 'LIKE', $isoNumber);
                    if(!empty($isoString)) $query = $query->where('additional_letter', 'LIKE', '%'.$isoString.'%');
                });
                
                $documents->where('document_type_id', 4);
                
            } else {
                // Search for other parameters
                $documents = $documents->where(function($query) use ($parameter) {
                    $query->where('name_long', 'LIKE', '%'.$parameter.'%' )
                    ->orWhere('search_tags', 'LIKE', '%'.$parameter.'%' )
                    ->orWhere('summary', 'LIKE', '%'.$parameter.'%' )
                    ->orWhere('betreff', 'LIKE', '%'.$parameter.'%' );
                });
                
            }       

            $documents = $documents->get();
            // dd($documents);
            
            // $variantsQuery = EditorVariant::where('inhalt', 'LIKE', '%'.$parameter.'%')->get();
            $variantsQuery = EditorVariant::whereNotNull('inhalt')->get();
            foreach ($variantsQuery as $tmp) {
                // Filter out images to only get the content
                $filteredVariant = preg_replace("/<img[^>]+\>/i", "", $tmp->inhalt);
                // Check if the search string is contained withing variants content
                if(stripos($filteredVariant, $parameter) !== false)
                    $variants[] = $tmp;
            }
            // dd($variants);
            
            foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
            
            if(count($variants)){
                foreach ($variants as $variant){
                    if(isset($variant->document)){
                        if(!in_array($variant->document, $results)){
                            if($variant->document->document_status_id == 3)
                                array_push($results, $variant->document);
                        }
                    }
                }
            } 

            // $results = $results->sortBy('date_published');
            
                
            $results = $this->filterByVisibility(collect($results));
            // add docs where $document->published->url_unique isset
            $resultsWithUrls = array();
            foreach($results as $tmp) if(isset($tmp->published->url_unique)) $resultsWithUrls[] = $tmp;
            $resultIds = array_pluck($resultsWithUrls, 'id');
            // $resultIds = array_pluck($results, 'id');
            
            // Hide documents that have publish date higher than today
            $tmpResults = Document::whereIn('id', $resultIds)->get();
            $tmpResults = $tmpResults->reject(function($document, $key){
                return Carbon::parse($document->date_published)->gt(Carbon::today());
            });
            $resultIds = array_pluck($tmpResults, 'id');
            
            if($sort == 'asc')
                $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->paginate(20, ['*'], 'seite');
                // $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
            else
                $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->paginate(20, ['*'], 'seite');
                // $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
            
            // Sort by results
            // $results = array_values(array_sort($results, function ($value) {
            //     return $value['date_published'];
            // }));
        }
        
        $request->flush();
        return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers'));
        // return view('suche.erweitert', compact('parameter', 'resultsWiki', 'documentTypes','mandantUsers', 'searchResultsTree', 'searchResultsPaginated'));
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
        //
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
     * Advanced search.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchAdvanced(Request $request)
    {
        // dd($request);
        $searchQmrIso = false;
        $emptySearch = true;
        $emptyDocs = true;
        $inputs = $request->all();
        $results = array();
        $resultsWiki = array();
        $variants = array();
        $searchResultsPaginated = array();
        // $documentTypes = DocumentType::all();
        $documentTypes = DocumentType::where('active', 1)->where('visible_navigation', 1)->get();
        
        if($request->get('sort') == 'asc') $sort = 'asc';
        else $sort = 'desc';
        
        foreach ($inputs as $key=>$input){
            // if($key != 'wiki' && $key != 'inhalt'){
            if($key != 'wiki' && $key != 'adv-search'){
                if(!empty($input)){
                    $emptySearch = false;
                }
            }
            
            if($key != 'inhalt' && $key != 'wiki' && $key != 'adv-search'){
                if(!empty($input)){
                    $emptyDocs = false;
                }
            }
        }
        
        // dd($emptyDocs);
        
        $mandantUsers = array();
        foreach(MandantUser::all() as $mandantUser){
            foreach(Auth::user()->mandantUsers as $loggedUserMandant){
                if($loggedUserMandant->mandant_id == $mandantUser->mandant_id){
                    if(!in_array($mandantUser, $mandantUsers))
                        array_push($mandantUsers, $mandantUser);
                }
            }
        }
        
        $parameter = $request->input('parameter');
        $name = $request->input('name');
        $betreff = $request->input('betreff');
        $summary = $request->input('beschreibung');
        $inhalt = $request->input('inhalt');
        $search_tags = $request->input('tags');
        $date_published = strlen($request->input('publish_date')) ? Carbon::parse($request->input('publish_date'))->toDateTimeString()  : null;
        // $date_from = $request->input('datum_von');
        $date_from = strlen($request->input('datum_von')) ? Carbon::parse($request->input('datum_von'))->toDateTimeString()  : null;
        // $date_from = strlen($request->input('datum_von')) ? Carbon::parse($request->input('datum_von'))  : null;
        // $date_to = $request->input('datum_bis');
        $date_to = strlen($request->input('datum_bis')) ? Carbon::parse($request->input('datum_bis'))->toDateTimeString()  : null;
        // $date_to = strlen($request->input('datum_bis')) ? Carbon::parse($request->input('datum_bis'))  : null;
        $document_type = $request->input('document_type');
        $wiki = $request->has('wiki');
        $history = $request->has('history');
        $user_id = $request->input('user_id');
        $qmr_number = $request->input('qmr_number');
        $iso_category_number = $request->input('iso_category_number');
        $additional_letter = $request->input('additional_letter');
        
        // dd($request->all());
        
        // DB::enableQueryLog();
        
        
        if($history) {
            // $documents = Document::where('document_status_id', 5)->where('active', 1);
            $documents = Document::where('document_status_id', 5);
        } else {
            $documents = Document::where('document_status_id', 3)->where('active', 1);
        }
        
        // Add-in parameter for use inside advanced search
        if(!empty($parameter)){
            
            if( stripos($parameter, 'QMR') !== false ){
                // Check for occurence of "QMR" string, search for QMR documents if found
                $searchQmrIso = true;
                $qmr = trim(str_ireplace('QMR', '', $parameter));
                $qmrNumber = (int) preg_replace("/[^0-9]+/", "", $qmr);
                $qmrString = preg_replace("/[^a-zA-Z]+/", "", $qmr);
                
                $documents = $documents->where(function($query) use ($qmrNumber, $qmrString) {
                    if($qmrNumber) $query = $query->where('qmr_number', 'LIKE', $qmrNumber);
                    if(!empty($qmrString)) $query = $query->where('additional_letter', 'LIKE', '%'.$qmrString.'%');
                });
                
                $documents->where('document_type_id', 3);
                
            } elseif( stripos($parameter, 'ISO') !== false ){
                // Check for occurence of "ISO" string, search for ISO documents if found
                $searchQmrIso = true;
                $iso = trim(str_ireplace('ISO', '', $parameter));
                $isoNumber = (int) preg_replace("/[^0-9]+/", "", $iso);
                $isoString = preg_replace("/[^a-zA-Z]+/", "", $iso);
                
                $documents = $documents->where(function($query) use ($isoNumber, $isoString) {
                    if($isoNumber) $query = $query->where('iso_category_number', 'LIKE', $isoNumber);
                    if(!empty($isoString)) $query = $query->where('additional_letter', 'LIKE', '%'.$isoString.'%');
                });
                
                $documents->where('document_type_id', 4);
                
            } else {
                $documents->where(function($query) use ($parameter) {
                    $query->where('name_long', 'LIKE', '%'.$parameter.'%' )
                        ->orWhere('search_tags', 'LIKE', '%'.$parameter.'%' )
                        ->orWhere('betreff', 'LIKE', '%'.$parameter.'%')
                        ->orWhere('summary', 'LIKE', '%'.$parameter.'%');
                });
            }
        }
        
        
        $documents = $documents->get();
        
        if($emptyDocs) $documents = array();

        $variantsQuery = EditorVariant::whereNotNull('inhalt')->get();
        foreach ($variantsQuery as $tmp) {
            // Filter out images to only get the content
            $filteredVariant = preg_replace("/<img[^>]+\>/i", "", $tmp->inhalt);
            
            // Check if the search string is contained withing variants content
            if(!empty($parameter)) {
                if(stripos($filteredVariant, $parameter) !== false)
                    $variants[] = $tmp;
            }
            if(!empty($inhalt)) {
                if(stripos($filteredVariant, $inhalt) !== false)
                    $variants[] = $tmp;
            }
        }
        
        if(empty($parameter) && empty($inhalt)) $variants = array();
        
        // Add standard documents to results array
        foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
        
        // Add document variants to results array
        if(count($variants)){
            foreach ($variants as $variant){
                if(isset($variant->document)){
                    if(!in_array($variant->document, $results)){
                        if($variant->document->document_status_id == 3){
                            if(!empty($document_type)){
                                if($variant->document->document_type_id == $document_type)
                                    array_push($results, $variant->document);
                            } else array_push($results, $variant->document);
                        }
                    }
                }
            }
        }
        
        // Filter documents and variants by document_status_id if history search is enabled
        if($history){
            foreach($results as $key => $value){
                if($value->document_status_id != 5)
                    $results = array_except($results, $key);
            }
        }
        
        // Define filters for all search results here (documents AND document variants)
        foreach($results as $key => $doc){
            
            // dd($doc);
            
            // Filter results and variants by date_from
            if(!empty($date_from)) {
                if(!Carbon::parse($doc->date_published)->gte(Carbon::parse($date_from))) 
                    unset($results[$key]);
            }
            
            // Filter results and variants by date_to
            if(!empty($date_to)) {
                if(!Carbon::parse($doc->date_published)->lte(Carbon::parse($date_to))) 
                    unset($results[$key]);
            }
            
            // Filter results and variants by date_to
            if(!empty($date_published)) {
                if(!Carbon::parse($doc->date_published)->eq(Carbon::parse($date_published))) 
                    unset($results[$key]);
            }
            
            // Filter results by title/name
            if(!empty($name)) {
                if(!(stripos($doc->name_long, $name) !== false)){
                    unset($results[$key]);
                }
            }
            
            // Filter results by tags/keywords
            if(!empty($search_tags)) {
                if(!(stripos($doc->search_tags, $search_tags) !== false)){
                    unset($results[$key]);
                }
            }
            
            // Filter results by summary
            if(!empty($summary)) {
                if(!(stripos($doc->summary, $summary) !== false)){
                    unset($results[$key]);
                }
            }
            
            // Filter results by content
            if(!empty($inhalt)) {
                $searchResult = false;
                // dd($doc->editorVariant);
                foreach ($doc->editorVariant as $variant) {
                    // if($variant->inhalt != null) dd($variant->inhalt);
                    $content = preg_replace("/<img[^>]+\>/i", "", $variant->inhalt);
                    if(stripos($content, $inhalt) !== false) $searchResult = true;
                }
                if(!$searchResult) unset($results[$key]);
            }
            
            // Filter results by betreff
            if(!empty($betreff)) {
                if(!(stripos($doc->betreff, $betreff) !== false)){
                    unset($results[$key]);
                }
            }
            
            // Filter results by user/author
            if(!empty($user_id)) {
                if($doc->owner_user_id != $user_id){
                    unset($results[$key]);
                }
            }
            
            // Filter results by user/author
            if(!empty($document_type)) {
                if($doc->document_type_id != $document_type) unset($results[$key]);
                else {
                    if($document_type == 3){
                        if(!empty($qmr_number)){
                            if($doc->qmr_number != $qmr_number)
                                unset($results[$key]);
                        }
                        if(!empty($additional_letter)){
                            if(!(stripos($doc->additional_letter, $additional_letter) !== false))
                                unset($results[$key]);
                        }
                    } elseif($document_type == 4){
                        if(!empty($iso_category_number)){
                            if($doc->iso_category_number != $iso_category_number)
                                unset($results[$key]);
                        }
                        if(!empty($additional_letter)){
                            if(!(stripos($doc->additional_letter, $additional_letter) !== false))
                                unset($results[$key]);
                        }
                    }
                }
            }
            
        }
        
        // dd(array_pluck($results, 'document_status_id'));
        
        $request->flash();
        
        if($wiki){
            // search for $name, $inhalt
            $resultsWiki = WikiPage::where('id', '>', 0)->where('status_id', 2)->where('active', 1);
            if(!empty($name)) $resultsWiki = $resultsWiki->where('name', 'LIKE', '%'. $name. '%');
            if(!empty($inhalt)) $resultsWiki = $resultsWiki->where('content', 'LIKE', '%'. $inhalt. '%');
            
            $resultsWiki = $resultsWiki->get();
        }
        
        // dd($results);
        $results = $this->filterByVisibility(collect($results));
        
        // add docs where $document->published->url_unique isset
        $resultsWithUrls = array();
        foreach($results as $tmp) if(isset($tmp->published->url_unique)) $resultsWithUrls[] = $tmp;
        $resultIds = array_pluck($resultsWithUrls, 'id');
        
        // Hide documents that have publish date higher than today
        $tmpResults = Document::whereIn('id', $resultIds)->get();
        $tmpResults = $tmpResults->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });
        $resultIds = array_pluck($tmpResults, 'id');
    
        if($sort == 'asc')
            $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->paginate(20, ['*'], 'seite');
            // $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
        else
            $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->paginate(20, ['*'], 'seite');;
            // $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
        
        // if($emptySearch) $searchResultsPaginated = array();
        if($emptySearch) $results = array();
        if(empty($name) && empty($inhalt)) $resultsWiki = null;
        
        return view('suche.erweitert', compact('results', 'parameter', 'resultsWiki', 'variants', 'documentTypes', 'mandantUsers'));
        // return view('suche.erweitert', compact('results', 'resultsWiki', 'resultsWikiPagination', 'resultsWikiTree', 'variants', 'documentTypes', 'mandantUsers', 'searchResultsPaginated', 'searchResultsTree'));
    }

    /**
     * Return search results for the phone list users/mandants.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchPhoneList(Request $request){
        if(!$request->has('search') || $request->method() == "GET")
            return redirect('telefonliste');
            
        $visible = $this->utility->getPhonelistSettings();
            
        $partner = false;
        $adminRights = false;
        $search = true;
        $searchParameter = $request->get('search');
        $roles = Role::all();
        $loggedUserMandant = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
        $loggedUserMandants = Mandant::whereIn('id', array_pluck(MandantUser::where('user_id', Auth::user()->id)->get(),'mandant_id'))->get();
        // dd($loggedUserMandants);
        
        foreach ($loggedUserMandants as $tmp) if($tmp->rights_admin) $adminRights = true;
        
        // Get searched mandants
        $mandants = Mandant::where(function ($query) use($searchParameter) {
            $query-> where('name','LIKE', '%'. $searchParameter .'%')
        ->orWhere('kurzname','LIKE', '%'. $searchParameter .'%')
        ->orWhere('mandant_number','LIKE', '%'. $searchParameter .'%');
        }); 
        
        
        if(Auth::user()->id == 1 || $loggedUserMandant->id == 1 || $adminRights)
            $mandants = $mandants->where('active', 1)->orderBy('mandant_number')->get();
        else{
            $partner = true;
            $mandants = $mandants->where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
        }

        // dd($loggedUserMandants);
        foreach ($loggedUserMandants as $tmp) {
            if(!$mandants->contains($tmp) && (
                (stripos($tmp->name, $searchParameter) !== false) ||
                (stripos($tmp->kurzname, $searchParameter) !== false) ||
                (stripos($tmp->mandant_number, $searchParameter) !== false )) ){
                    $mandants->prepend($tmp);
            }
        }
        
        
        // Sort by Mandant No.
        $mandants = array_values(array_sort($mandants, function ($value) {
            return $value['mandant_number'];
        }));
        // dd($mandants);
        
        // Get users for searched mandants
        foreach($mandants as $k => $mandant){
            
            $userArr = array();
            $usersInternal = array();
            
            // Check if the logged user is in the current mandant
            $localUser = MandantUser::where('mandant_id', $mandant->id)->where('user_id', Auth::user()->id)->first();
            
            // Get all InternalMandantUsers
            // $internalMandantUsers = InternalMandantUser::where('mandant_id', $loggedUserMandant->id)
            //     ->where('mandant_id_edit', $mandant->id)->get();
            $internalMandantUsers = InternalMandantUser::whereIn('mandant_id', array_pluck($loggedUserMandants, 'id'))
                ->where('mandant_id_edit', $mandant->id)->get();
            foreach ($internalMandantUsers as $user)
                $usersInternal[] = $user;
    
            foreach($mandant->users as $k2 => $mUser){
                
                foreach($mUser->mandantRoles as $mr){
                    if($mUser->active && !in_array($mUser->id, array_pluck($usersInternal,'user_id'))){
                        // Check for phone roles
                        if( $mr->role->phone_role || $mr->role->mandant_role ) {
                            $internalRole = InternalMandantUser::where('role_id', $mr->role->id)
                                ->whereIn('mandant_id', array_pluck($loggedUserMandants, 'id'))->where('mandant_id_edit', $mandant->id)
                                ->groupBy('role_id','user_id','mandant_id_edit')->get();
                            // $internalRole = InternalMandantUser::where('role_id', $mr->role->id)->where('mandant_id_edit', $mandant->id)->first();
                            if(!count($internalRole)){
                                $userArr[] = $mandant->users[$k2]->id;
                            }
                        }
                
                    }
                }
            } //end second foreach
            
            $mandant->usersInternal = $usersInternal;
            $mandant->usersInMandants = $mandant->users->whereIn('id', $userArr);
        }
        
        // Get mandants for searched users
        if($partner)
            $mandantsSearch = Mandant::where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
        else
            $mandantsSearch = Mandant::where('active', 1)->orderBy('mandant_number')->get();
            
        $myMandantSearch = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
        if(!$mandantsSearch->contains($myMandantSearch))
            $mandantsSearch->prepend($myMandantSearch);
        
        $users = User::where(function ($query) use($searchParameter) {
            $query->where('first_name', 'LIKE', '%'. $searchParameter .'%')
        ->orWhere('last_name', 'LIKE', '%'. $searchParameter .'%');
        // ->orWhere('short_name', 'LIKE', '%'. $searchParameter .'%');
        });
        //dd( \DB::getQueryLog() );
        
        $usersInMandants = array();
        $usersInternal = array();
        $usersInMandantsInternal = array();

        // Get searched users    
        foreach($mandantsSearch as $k => $mandant){
            
            // $internalMandantUsers = InternalMandantUser::where('mandant_id', $mandant->id)->get();
            // $internalMandantUsers = InternalMandantUser::where('mandant_id', $loggedUserMandant->id)
            //     ->where('mandant_id_edit', $mandant->id)->get();
            
            $internalMandantUsers = InternalMandantUser::whereIn('mandant_id', array_pluck($loggedUserMandants, 'id'))
                ->where('mandant_id_edit', $mandant->id)->get();
                
            foreach ($internalMandantUsers as $user)
                $usersInMandantsInternal[] = $user;
            
            // dd($mandant->users);
            foreach($mandant->users as $k2 => $mUser){
                foreach($mUser->mandantRoles as $mr){
                    if($mUser->active && !in_array($mUser->id, array_pluck($usersInternal,'user_id'))){
                        // Check for phone roles
                        if( $mr->role->phone_role || $mr->role->mandant_role ) {
                            $internalRole = InternalMandantUser::where('role_id', $mr->role->id)
                                ->whereIn('mandant_id', array_pluck($loggedUserMandants, 'id'))->where('mandant_id_edit', $mandant->id)
                                ->groupBy('role_id','user_id','mandant_id_edit')->get();
                            // $internalRole = InternalMandantUser::where('role_id', $mr->role->id)->where('mandant_id_edit', $mandant->id)->first();
                            if(!count($internalRole)){
                                // if( $mr->role->phone_role && !in_array($mandant->users[$k2]->id, $usersInMandants) )
                                     $usersInMandants[] = $mandant->users[$k2]->id;
                            }
                        }
                    }
                }
            }
        }
        
        // Add internal users if they satisfy search criteria
        $tmpUsrs = User::where(function ($query) use($searchParameter) {
            $query->where('first_name', 'LIKE', '%'. $searchParameter .'%')
            ->orWhere('last_name', 'LIKE', '%'. $searchParameter .'%');
        });
        
        foreach ($usersInMandantsInternal as $umi) {
            if(count($tmpUsrs->where('id', $umi->user_id)->get()))
                $usersInternal[] = $umi;
        }
        
        $users = $users->whereIn('id', $usersInMandants)->get();
        
        // dd($usersInternal);
        $searchSuggestions = ViewHelper::getTelephonelistSearchSuggestions();
        
        return view('telefonliste.index', compact('search', 'partner','searchParameter', 'mandants', 'users', 'usersInternal', 'roles', 'visible', 'searchSuggestions') );
    }
    
    // Function to filter out documents by permissions
    public function filterByVisibility($documents){
        
        foreach($documents as $key => $value){
            if(!ViewHelper::documentVariantPermission($value)->permissionExists)
                // dd($key);
                $documents->forget($key);
        }
        
        return $documents;
    }
    
}
