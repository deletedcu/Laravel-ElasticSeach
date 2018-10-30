<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ViewHelper;
use App\JuristCategory;
use App\Document;
use App\Role;
use App\DocumentStatus;

use App\Http\Repositories\DocumentRepository;

class BeratungController extends Controller
{
   /**
     * Class constructor.
     */
    public function __construct(DocumentRepository $docRepo)
    {
        $this->document = $docRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!ViewHelper::universalHasPermission(array(6, 35))) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }
        $juristenCategories = $juristCategoryOptions = JuristCategory::where('beratung',1)->get();
       
        return view('beratung-kategorien.index', compact('juristenCategories', 'juristCategoryOptions'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function singlePageAll()
    {
        if (!ViewHelper::universalHasPermission(array(6, 35))) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }
        $juristenCategories = $juristCategoryOptions = JuristCategory::where('beratung',1)
        ->where('parent',1)->where('active',1)->get();

        return view('beratung-kategorien.showAll', compact('juristenCategories', 'juristCategoryOptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('category_id')) {
            $request->merge(['jurist_category_parent_id' => $request->get('category_id')]);
        }
        else{
            $request->merge([
                'jurist_category_parent_id' =>null,
                'parent' => 'on',
            ]);
        }
        if ($request->has('parent') && $request->get('parent') == 'on') {
            $request->merge(['parent' => 1]);
        }
         $request->merge(['beratung' => 1,'active'=>0]);
        $juristenCategory = JuristCategory::create($request->all());

        return back()->with('message', 'Beratung-Kategorie erfolgreich gespeichert.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $category = JuristCategory::find($id);
    //   dd( count($category->juristCategories) );
        if(count($category->juristCategoriesActive )){
            return view('beratung-kategorien.categoryWithSubcategories', compact('category') );
        }
        else{
        $documents = Document::where('jurist_category_id',$category->id)->paginate(10);    
        $documentsTree = $this->document->generateTreeview($documents, array('pageHome' => true, 'myDocuments' => true, 
        'showAttachments' => true, 'showHistory' => true));
       
       
         return view('beratung-kategorien.category', compact('category','documents','documentsTree') );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $juristenCategory = JuristCategory::find($id);

        if ($request->has('activate')) {
            $status = !$request->input('activate');
            $juristenCategory->active = $status;
        }
        if ($request->has('category_id') && $request->get('category_id') != 'parent') {
            $juristenCategory->jurist_category_parent_id = $request->input('category_id');
            $juristenCategory->parent = 0;
        }
        else{
            $juristenCategory->jurist_category_parent_id = null;
            $juristenCategory->parent = 1;
        }
        
        $juristenCategory->name = $request->input('name');
        $juristenCategory->slug = str_slug($juristenCategory->name);
        $juristenCategory->save();

        return back()->with('message', 'Beratung-Kategorie erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $juristenCategory = JuristCategory::find($id);
        $juristenCategory->delete();

        return back()->with('message', 'Kategorie gelöscht.');
    }
}
