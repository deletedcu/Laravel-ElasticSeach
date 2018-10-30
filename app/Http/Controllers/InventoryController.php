<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ViewHelper;
use Auth;
use Mail;
use Carbon\Carbon;
use App\Classes\PdfWrapper;

//Models
use App\User;
use App\MandantUser;
use App\Mandant;
use App\MandantUserRole;
use App\Inventory;
use App\MandantInventoryAccounting;
use App\InventoryCategory;
use App\InventorySize;
use App\InventoryHistory;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (ViewHelper::universalHasPermission(array(7, 34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $categories = InventoryCategory::where('active', 1)->get();
        $sizes = InventorySize::where('active', 1)->get();
        $searchInput = '';

        return view('inventarliste.index', compact('categories', 'sizes','searchInput'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (ViewHelper::universalHasPermission( array(7, 34) ) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        if (!$request->has('search')) {
            return redirect('inventarliste');
        }

        $searchInput = $request->get('search');

        $searchCategories = InventoryCategory::where('active', 1)->where('name', 'LIKE', '%'.$searchInput.'%')->get();
        $categories = InventoryCategory::where('active', 1)->get();
        $activeCategories = $categories->pluck('id')->toArray();
        $searchInventory = Inventory::whereIn('inventory_category_id', $activeCategories)->where('name', 'LIKE', '%'.$searchInput.'%')->get();

        $sizes = InventorySize::all();

        return view('inventarliste.index', compact('categories', 'sizes', 'searchCategories', 'searchInventory', 'searchInput'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   /* public function search(Request $request)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $searchInput = $request->get('search');
        $searchCategories = InventoryCategory::where('active', 1)->where('name', 'LIKE', '%'.$searchInput.'%')->get();
        $categories = InventoryCategory::where('active', 1)->get();
        $activeCategories = $categories->pluck('id')->toArray();
        $searchInventory = Inventory::whereIn('inventory_category_id', $activeCategories)->where('name', 'LIKE', '%'.$searchInput.'%')->get();

        $sizes = InventorySize::all();

        return view('inventarliste.index', compact('categories', 'sizes', 'searchCategories', 'searchInventory', 'searchInput'));
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $categories = InventoryCategory::where('active', 1)->get();
        $sizes = InventorySize::where('active', 1)->get();

        return view('formWrapper', compact('categories', 'sizes'));
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
        $inventory = Inventory::create($request->all());
        $text = trans('inventoryList.itemCreated').', '.trans('inventoryList.name').': '.$inventory->name;
        $text .= ', '.trans('inventoryList.category').': '.$inventory->category->name;
        $text .= ', '.trans('inventoryList.size').': '.$inventory->size->name.', '.trans('inventoryList.number').': '.$inventory->value;
        $text .= ', '.trans('inventoryList.minStock').': '.$inventory->min_stock;
        $text .= ', '.trans('inventoryList.purchasePrice').': '.$inventory->purchase_price;
        $text .= ', '.trans('inventoryList.sellPrice').': '.$inventory->sell_price;
        if ($inventory->neptun_intern == 1) {
            $text .= ', '.trans('inventoryList.neptunIntern').': Ja';
        } else {
            $text .= ', '.trans('inventoryList.neptunIntern').': Nein';
        }
        $request->merge(['inventory_id' => $inventory->id, 'user_id' => Auth::user()->id, 'description_text' => $text]);
        $history = InventoryHistory::create($request->all());

        return redirect()->back()->with('messageSecondary', trans('inventoryList.inventoryAdded'));
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
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
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
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $data = Inventory::find($id);
        $categories = InventoryCategory::where('active', 1)->get();
        $sizes = InventorySize::where('active', 1)->get();

        return view('formWrapper', compact('data', 'categories', 'sizes'));
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
        $search = '';
        if ($request->has('search') && !empty($request->get('search'))) {
            $search = $request->get('search');
        }
        $href = '';
        if ($request->has('href') && !empty($request->get('href'))) {
            $href = $request->get('href');
        }
        $item = Inventory::find($id);
        $oldItem = Inventory::find($id);
        if (!$request->has('neptun_intern') && !$request->has('taken')) {
            $request->merge(['neptun_intern' => 0]);
        }
        if ($request->has('taken')) {
            $newValue = $item->value - intval($request->get('taken'));
            if ($newValue < 0) {
                return redirect()->back()->with('messageSecondary', trans('inventoryList.lowerThanZero'));
            }
            $request->merge(['value' => $newValue]);
        } else {
            $request->merge(['is_updated' => Carbon::now()]);
        }
        if ($request->has('text') && empty($request->get('text'))) {
            $request->merge(['text' => null]);
        }
        if (empty($request->get('mandant_id'))) {
            $request->merge(['mandant_id' => null]);
        }
        $item->fill($request->all())->save();

        //change for InventoryHistory
        if ($request->has('taken')) {
            $request->merge(['value' => $request->get('taken')]);
        }
        if ($request->has('taken') && !empty($request->get('mandant_id'))) {
            $request->merge(['inventory_id' => $item->id, 'inventory_category_id' => $item->inventory_category_id,
             'inventory_size_id' => $item->inventory_size_id, 'sell_price' => $item->sell_price, ]);
            MandantInventoryAccounting::create($request->all());
        }

        $descriptionString = '';
        $request->merge(['user_id' => Auth::user()->id, 'inventory_id' => $id]);

        //prevent filling up the database when all three values are null
        if (!is_null($request->get('value')) || !is_null($request->get('inventory_category_id')) ||
        !is_null($request->get('inventory_size_id')) ||
        !is_null($request->get('min_stock')) ||
        !is_null($request->get('purchase_price')) ||
        !is_null($request->get('sell_price'))) {
            // dd($request->all() );
            $history = InventoryHistory::create($request->all());
            //send email if value under the database marked value
            if ((!is_null($request->get('value')) && $request->has('taken')) && $item->min_stock >= $item->value) {
                $request = $request->all();
                $from = new \StdClass();
                $from->name = 'Informationsservice';
                $from->email = 'info@neptun-gmbh.de';
            
                $request['logo'] = asset('/img/logo-neptun-new.png');
                $request['from'] = $from->email;

                $request['subject'] = trans('inventoryList.emailSubject');
                $template = view('email.lowStock', compact('request', 'item'))->render();
                $mandantUserIds = MandantUserRole::where('role_id', 34)->pluck('mandant_user_id')->toArray();
                $mandatUsers = MandantUser::whereIn('id', $mandantUserIds)->pluck('user_id')->toArray();

                if (count($mandatUsers)) {
                    foreach ($mandatUsers as $user) {
                        $request['to'] = $to = User::find($user);
                        // dd($request);
                        $sent = Mail::send([], [], function ($message) use ($template, $request, $from, $to, $item) {
                            $message->from($from->email, $from->name)
                            ->to($to->email, $to->first_name.' '.$to->last_name)
                            ->subject($request['subject'])
                            ->setBody($template, 'text/html');
                        });
                    }
                }
            }
        }
        $previousUrl = app('url')->previous();
        if (strpos($previousUrl, 'suche') !== false && ($search)) {
            return redirect($previousUrl.$href)->with('messageSecondary', trans('inventoryList.inventoryUpdated'));
        }

        return redirect()->to($previousUrl.$href)->with('messageSecondary', trans('inventoryList.inventoryUpdated'));
        // return redirect()->back($href);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyCategory($id)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $category = InventoryCategory::find($id);

        if (!is_null($category)) {
            $category->delete();
        }

        return redirect()->back()->with('messageSecondary', trans('inventoryList.invetoryCategoryDeleted'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroySize($id)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $size = InventorySize::find($id);
        if (!is_null($size)) {
            $size->delete();
        }

        return redirect()->back()->with('messageSecondary', trans('inventoryList.invetorySizeDeleted'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function history($itemId)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $item = Inventory::find($itemId);
        $histories = InventoryHistory::where('inventory_id', $itemId)
        ->orderBy('updated_at', 'desc')->paginate(20);

        return view('inventarliste.history', compact('histories', 'item'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $categories = InventoryCategory::all();

        return view('inventarliste.categories', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postCategories(Request $request)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $exists = InventoryCategory::where('name', $request->get('name'))->first();
        if (!is_null($exists)) {
            return redirect()->back()->with('messageSecondary', trans('inventoryList.categoryExists'));
        }
        $request->merge(['active' => 1]);
        $newCategory = InventoryCategory::create($request->all());

        return redirect()->back()->with('messageSecondary', trans('inventoryList.categoryCreated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCategories(Request $request, $id)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $category = InventoryCategory::find($id);
        $category->fill($request->all())->save();

        return redirect()->back()->with('messageSecondary', trans('inventoryList.categoryUpdated'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sizes()
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $sizes = InventorySize::all();

        return view('inventarliste.sizes', compact('sizes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postSizes(Request $request)
    {
        $exists = InventorySize::where('name', $request->get('name'))->first();
        if (!is_null($exists)) {
            return redirect()->back()->with('messageSecondary', trans('inventoryList.sizeExists'));
        }
        $request->merge(['active' => 1]);
        $newCategory = InventorySize::create($request->all());

        return redirect()->back()->with('messageSecondary', trans('inventoryList.sizeCreated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSizes(Request $request, $id)
    {
        $sizes = InventorySize::find($id);
        $sizes->fill($request->all())->save();

        return redirect()->back()->with('messageSecondary', trans('inventoryList.sizeUpdated'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function abrechnen()
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $mandants = Mandant::join('mandant_inventory_accountings', 'mandant_inventory_accountings.mandant_id', '=', 'mandants.id')
        ->orderBy('mandant_number', 'asc')->groupBy('mandant_id')->where('accounted_for', 0)->get();
        foreach ($mandants as $mandant) {
            $items = MandantInventoryAccounting::where('mandant_id', $mandant->mandant_id)->where('accounted_for', 0)
            ->orderBy('created_at', 'desc')->get();
            $mandant->items = $items;
        }
        $searchSuggestions = ViewHelper::getMandantAccountingSearchSuggestions(array(0));
        $searchInput = '';
        return view('inventarliste.deduct', compact('mandants', 'searchSuggestions','searchInput'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function abrechnenAbgerechnt()
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $mandants = Mandant::join('mandant_inventory_accountings', 'mandant_inventory_accountings.mandant_id', '=', 'mandants.id')
        ->orderBy('mandant_number', 'asc')->groupBy('mandant_id')->where('accounted_for', 1)->get();
        foreach ($mandants as $mandant) {
            $items = MandantInventoryAccounting::where('mandant_id', $mandant->mandant_id)
            ->orderBy('created_at', 'desc')->where('accounted_for', 1)->get();
            $mandant->items = $items;
        }
        $searchSuggestions = ViewHelper::getMandantAccountingSearchSuggestions(array(1));

        return view('inventarliste.deduct', compact('mandants', 'searchSuggestions'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function abrechnenAlle()
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $mandants = Mandant::join('mandant_inventory_accountings', 'mandant_inventory_accountings.mandant_id', '=', 'mandants.id')
        ->orderBy('mandant_number', 'asc')->groupBy('mandant_id')->get();
        foreach ($mandants as $mandant) {
            $items = MandantInventoryAccounting::where('mandant_id', $mandant->mandant_id)
            ->orderBy('created_at', 'desc')->get();
            $mandant->items = $items;
        }
        $searchSuggestions = ViewHelper::getMandantAccountingSearchSuggestions();

        return view('inventarliste.deduct', compact('mandants', 'searchSuggestions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAbrechnen(Request $request, $id)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $accounting = MandantInventoryAccounting::find($id);
        if (!$request->has('accounted_for')) {
            $request->merge(['accounted_for' => 0]);
        }
        $accounting->fill($request->all())->save();

        $href = '';
        if ($request->has('href') && !empty($request->get('href'))) {
            $href = $request->get('href');
        }
        $previousUrl = app('url')->previous();

        return redirect()->to($previousUrl.$href)->with('messageSecondary', trans('inventoryList.itemUpdated'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchAbrechnen(Request $request)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $searchInput = $request->get('search');
        $mandants = Mandant::where(function ($query) use ($searchInput) {
            $query->where('name', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('kurzname', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('mandant_number', 'LIKE', '%'.$searchInput.'%');
        })->orderBy('mandant_number', 'asc')->pluck('id');

        $searchMandants = MandantInventoryAccounting::orderBy('mandant_id', 'asc')->groupBy('mandant_id')->whereIn('mandant_id', $mandants)
        ->where('accounted_for', 0)->get();
        foreach ($searchMandants as $mandant) {
            $items = MandantInventoryAccounting::where('mandant_id', $mandant->mandant_id)->where('accounted_for', 0)
            ->orderBy('created_at', 'desc')->get();
            $mandant->items = $items;
        }
        $searchSuggestions = ViewHelper::getMandantAccountingSearchSuggestions(array(0));

        return view('inventarliste.deduct', compact('searchMandants', 'searchInput', 'searchSuggestions'));
    }

    public function searchAbrechnenAbgerechnt(Request $request)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $searchInput = $request->get('search');
        $mandants = Mandant::where(function ($query) use ($searchInput) {
            $query->where('name', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('kurzname', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('mandant_number', 'LIKE', '%'.$searchInput.'%');
        })->orderBy('mandant_number', 'asc')->pluck('id');

        $searchMandants = MandantInventoryAccounting::groupBy('mandant_id')->whereIn('mandant_id', $mandants)
        ->where('accounted_for', 1)->get();
        foreach ($searchMandants as $mandant) {
            $items = MandantInventoryAccounting::where('mandant_id', $mandant->mandant_id)->where('accounted_for', 1)
            ->orderBy('created_at', 'desc')->get();
            $mandant->items = $items;
        }
        $searchSuggestions = ViewHelper::getMandantAccountingSearchSuggestions(array(1));

        return view('inventarliste.deduct', compact('searchMandants', 'searchInput', 'searchSuggestions'));
    }

    public function searchAbrechnenAlle(Request $request)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $searchInput = $request->get('search');
        $mandants = Mandant::where(function ($query) use ($searchInput) {
            $query->where('name', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('kurzname', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('mandant_number', 'LIKE', '%'.$searchInput.'%');
        })->orderBy('mandant_number', 'asc')->pluck('id');

        $searchMandants = MandantInventoryAccounting::groupBy('mandant_id')->whereIn('mandant_id', $mandants)->get();
        foreach ($searchMandants as $mandant) {
            $items = MandantInventoryAccounting::where('mandant_id', $mandant->mandant_id)
            ->orderBy('created_at', 'desc')->get();
            $mandant->items = $items;
        }
        $searchSuggestions = ViewHelper::getMandantAccountingSearchSuggestions();

        return view('inventarliste.deduct', compact('searchMandants', 'searchInput', 'searchSuggestions'));
    }

    public function abrechnenPdf(Request $request)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $searchInput = $request->get('search');
        $accountedFor = $request->get('accounted_for');
        if ($accountedFor == 'all') {
            $accountedFor = array(0, 1);
        } else {
            $accountedFor = array($accountedFor);
        }

        if (!empty($searchInput)) {
            $mandants = Mandant::where(function ($query) use ($searchInput) {
                $query->where('name', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('kurzname', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('mandant_number', 'LIKE', '%'.$searchInput.'%');
            })->orderBy('mandant_number', 'asc')->pluck('id');
        } else {
            $mandants = Mandant::orderBy('mandant_number', 'asc')->pluck('id');
        }

        $mandants = Mandant::join('mandant_inventory_accountings', 'mandant_inventory_accountings.mandant_id', '=', 'mandants.id')
        ->orderBy('mandant_number', 'asc')->groupBy('mandant_id')->whereIn('mandant_id', $mandants)
        ->whereIn('accounted_for', $accountedFor)->get();
        foreach ($mandants as $mandant) {
            $items = MandantInventoryAccounting::where('mandant_id', $mandant->mandant_id)->whereIn('accounted_for', $accountedFor)
            ->orderBy('created_at', 'desc')->get();
            $mandant->items = $items;
        }

        $margins = $this->setPdfMargins();
        $or = 'P';
        $pdf = new PdfWrapper;
        $pdf->debug = true;
        $render = view('pdf.abrechnen', compact('mandants'))->render();
        $pdf->AddPage($or,$margins->left, $margins->right, $margins->top, $margins->bottom,$margins->headerTop, $margins->footerTop);
        $pdf->WriteHTML($render);

        return $pdf->stream();
    }
    

    /**
     * Return pdf margins.
     *
     * @param collection $document
     *
     * @return object $margins
     */
    private function setPdfMargins()
    {
        $margins = new \StdClass();
       /* Set the document orientation */
        $margins->orientation = 'P';
        $margins->left = 10;
        $margins->right = 10;
        $margins->top = 10;
        $margins->bottom = 10;
        $margins->headerTop = 0;
        $margins->footerTop = 5;

        return $margins;
    }

    /**
     * Format description text string.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    private function formatDescriptionString($string, $message)
    {
        if (empty($string)) {
            return strtoupper($message);
        } else {
            return ', '.$message;
        }
    }
}
