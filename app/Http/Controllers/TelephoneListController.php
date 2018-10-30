<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\UtilityRepository;
use App\Helpers\ViewHelper;
use Carbon\Carbon;
use Auth;
use Excel;
use App\Classes\PdfWrapper;

use App\Role;
use App\User;
use App\UserSettings;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole; 
use App\MandantInfo;
use App\InternalMandantUser;

class TelephoneListController extends Controller
{
    public function __construct(UtilityRepository $utilRepo)
    {
        $this->utility = $utilRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         $partner = false; // Partner user/mandant check var
        $visible = $this->utility->getPhonelistSettings();
        // dd($visible);
        // $mandants = array();
        $myMandant = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
         $myMandants = Mandant::whereIn('id', array_pluck(MandantUser::where('user_id', Auth::user()->id)->get(), 'mandant_id'))->get();
        // dd($myMandants);

        if (ViewHelper::universalHasPermission() || $myMandant->id == 1 || $myMandant->rights_admin == 1) {
            $mandants = Mandant::where('active', 1)->orderBy('mandant_number')->get();
        } else {
            $partner = true;
            $mandants = Mandant::where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
        }

         foreach ($myMandants as $tmp) {
             if (!$mandants->contains($tmp)) {
                 $mandants->prepend($tmp);
             }
         }

        // Sort by Mandant No.
        $mandants = array_values(array_sort($mandants, function ($value) {
            return $value['mandant_number'];
        }));
        // dd($mandants);

        foreach ($mandants as $k => $mandant) {
            $userArr = array();
            $usersInternal = array();

            // Check if the logged user is in the current mandant
            // $localUser = MandantUser::where('mandant_id', $mandant->id)->where('user_id', Auth::user()->id)->first();

            // Get all InternalMandantUsers
            // NOTE: groupBy eliminates duplicates with same role_id, user_id and mandant_id_edit
            $internalMandantUsers = InternalMandantUser::whereIn('mandant_id', array_pluck($myMandants, 'id'))
                ->where('mandant_id_edit', $mandant->id)->groupBy('role_id', 'user_id', 'mandant_id_edit')->get();

            foreach ($internalMandantUsers as $user) {
                $usersInternal[] = $user;
            }

            // partner user -> for neptun flag mandants - phone roles;
            // partner user -> for other mandants - partner roles;
            // admin user/neptun user -> for all mandants - phone and partner roles;

            foreach ($mandant->users as $k2 => $mUser) {
                foreach ($mUser->mandantRoles as $mr) {
                    // do not add the user if he is in $usersInternal array
                    if ($mUser->active && !in_array($mUser->id, array_pluck($usersInternal, 'user_id'))) {
                        // Check for phone roles
                        if ($mr->role->phone_role || $mr->role->mandant_role) {
                            $internalRole = InternalMandantUser::where('role_id', $mr->role->id)
                                ->whereIn('mandant_id', array_pluck($myMandants, 'id'))->where('mandant_id_edit', $mandant->id)
                                ->groupBy('role_id', 'user_id', 'mandant_id_edit')->get();
                            // $internalRole = InternalMandantUser::where('role_id', $mr->role->id)->where('mandant_id_edit', $mandant->id)->first();
                            if (!count($internalRole)) {
                                $userArr[] = $mandant->users[$k2]->id;
                            }
                        }
                    }
                }
            } // end second foreach

            // if($mandant->id == 1) dd($usersInternal);

            $mandant->usersInternal = $usersInternal;

            $mandant->usersInMandants = $mandant->users->whereIn('id', $userArr);

            $userInMandantExists = array();
            $roleExists = array();
            if ($mandant->id == 1) {
                foreach ($mandant->usersInternal as $ui) {
                    if (!is_null($ui->user)) {
                        $userInMandantExists[] = $ui->user_id;
                        $roleExists[] = $ui->role_id;
                    }
                }
                foreach ($mandant->usersInMandants as $um) {
                    if (!is_null($um)) {
                        $userInMandantExists[] = $um->id;
                    }
                }
                $viewAllNeptunPhoneRoles = false;
                // dd(ViewHelper::getUserMandants(Auth::user()->id));
                if (ViewHelper::universalHasPermission() == true || in_array(1, ViewHelper::getUserMandants(Auth::user()->id)->toArray())) {
                    $viewAllNeptunPhoneRoles = true;
                }
                if ($viewAllNeptunPhoneRoles == true) {
                    $availableRoles = Role::where('phone_role', 1)->pluck('id')->toArray();
                    $mandantUserRoles = MandantUserRole::whereIn('role_id', $roleExists)->pluck('mandant_user_id')->toArray();
                    $mandantUsers = MandantUser::where('mandant_id', 1)->whereIn('id', $mandantUserRoles)->get();
                } else {
                    $availableRoles = Role::whereNotIn('id', $roleExists)->where('phone_role', 1)->pluck('id')->toArray();
                    $mandantUserRoles = MandantUserRole::whereIn('role_id', $roleExists)->pluck('mandant_user_id')->toArray();
                    $mandantUsers = MandantUser::where('mandant_id', 1)->whereNotIn('id', $mandantUserRoles)->whereIn('user_id', $userInMandantExists)->get();
                }
            }
            if (isset($mandantUsers) && count($mandantUsers)) {
                $mandant->usersInMandants = $mandant->users->whereIn('id', $mandantUsers->pluck('user_id')->toArray());
            }
        }

        $searchSuggestions = ViewHelper::getTelephonelistSearchSuggestions($mandants);

        return view('telefonliste.index', compact('mandants', 'visible', 'partner', 'searchSuggestions'));
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
     * Store telefonliste table view options.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function displayOptions(Request $request)
    {
        $visibleColumns = $request->get('visibleColumns');
        $settingsUID = Auth::user()->id;
        $settingsCategory = 'telefonliste';
        $settingsName = 'visibleColumns';
        $settingsOld = UserSettings::where('user_id', $settingsUID)->where('category', $settingsCategory)->forceDelete();

        if (count($visibleColumns)) {
            foreach ($visibleColumns as $key => $value) {
                UserSettings::create(['user_id' => $settingsUID, 'category' => $settingsCategory, 'name' => $settingsName, 'value' => $value]);
            }
        }

        return back()->with('message', 'Einstellungen erfolgreich gespeichert.');
    }

    /**
     * Generate PDF document for the passed Mandant ID.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function pdfExport($id)
    {
        // if( ViewHelper::universalHasPermission( array(20) ) == false  ){
        //     return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        // }
        $dateNow = Carbon::now()->format('M Y');
        $mandant = Mandant::find($id);
        $mandantInfo = MandantInfo::where('mandant_id', $id)->first();
        $hauptstelle = Mandant::find($mandant->mandant_id_hauptstelle);
        $margins = new \StdClass();
        $margins->left = 10;
        $margins->right = 10;
        $margins->top = 10;
        $margins->bottom = 10;
        $margins->headerTop = 0;
        $margins->footerTop = 5;
        $or = 'P';
        $render = view('pdf.mandant', compact('mandant', 'mandantInfo', 'hauptstelle', 'dateNow'));
        $pdf = new PdfWrapper;
        $pdf->AddPage($or,$margins->left, $margins->right, $margins->top, $margins->bottom,$margins->headerTop, $margins->footerTop);
        $pdf->WriteHTML($render);

        return $pdf->stream();
        // $pdf = \PDF::loadView('pdf.mandant', compact('mandant','mandantInfo','hauptstelle','dateNow'));
        // // $pdf = \PDF::html('pdf.mandant', compact('mandant','mandantInfo','hauptstelle','dateNow'));
        // return $pdf->stream();
    }

    /**
     * Generate XLS document for the passed request parameters.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function xlsExport(Request $request)
    {
        $exportMandants = $request->input('export-mandants');
        $exportOption = $request->input('export-option');

        switch ($exportOption) {
            case 1: {
                Excel::create('Telefonliste Export - Partner Gesamt', function ($excel) use ($exportMandants, $exportOption) {
                    $excel->setTitle('Partner Gesamt');
                    $excel->setDescription('Partner Gesamt');

                    // $mandant = Mandant::find($id);
                    // $mandantInfo = MandantInfo::where('mandant_id', 1)->first();
                    // $hauptstelle = Mandant::find($mandant->mandant_id_hauptstelle);

                    // Add sheet
                    $excel->sheet('Alle Mandanten', function ($sheet) use ($exportMandants) {
                        // dd($exportMandants);
                        $sheet->row(1, array('Nr.', 'Firma', 'Ort', 'Bundesland', 'Zuständige AA für Erlaubnisverfahren ab 01.07.12'));

                        if (in_array('0', $exportMandants)) {
                            foreach (Mandant::all() as $mandant) {
                                $mandantInfo = MandantInfo::where('mandant_id', $mandant->id)->first();

                                // Add rows
                                $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort, $mandant->bundesland, $mandantInfo->erlaubnisverfahren));
                            }
                        } else {
                            foreach ($exportMandants as $id) {
                                $mandant = Mandant::find($id);
                                $mandantInfo = MandantInfo::where('mandant_id', $id)->first();

                                // Add rows
                                $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort, $mandant->bundesland, $mandantInfo->erlaubnisverfahren));
                            }
                        }
                    });
                })->download('xls');

                return back();
            }
            break;

            case 2: {
                Excel::create('Telefonliste Export - Einteilung Mandanten - NEPTUN-Mitarbeiter', function ($excel) use ($exportMandants, $exportOption) {
                    $excel->setTitle('Einteilung Mandanten - NEPTUN-Mitarbeiter');
                    $excel->setDescription('Einteilung Mandanten - NEPTUN-Mitarbeiter');

                    // $mandant = Mandant::find($id);
                    // $mandantInfo = MandantInfo::where('mandant_id', 1)->first();
                    // $hauptstelle = Mandant::find($mandant->mandant_id_hauptstelle);

                    // Add sheet
                    $excel->sheet('Alle Mandanten', function ($sheet) use ($exportMandants) {
                        $phoneRoles = Role::where('phone_role', 1)->get();

                        // $edv = Role::find(21)->name;
                        // $fibu = Role::find(22)->name;
                        // $lohn = Role::find(23)->name;

                        $rowTitles = array('Nr.', 'Firma', 'Ort');
                        foreach ($phoneRoles as $phoneRole) {
                            array_push($rowTitles, $phoneRole->name);
                        }

                        // dd($rowTitles);
                        // $sheet->row(1, array('Nr.', 'Firma', 'Ort', $lohn, $fibu, $edv));

                        $sheet->row(1, $rowTitles);

                        if (in_array('0', $exportMandants)) {
                            foreach (Mandant::all() as $mandant) {
                                $mandantInfo = MandantInfo::where('mandant_id', $mandant->id)->first();

                                /*
                                $internalUserEdv =  InternalMandantUser::where('mandant_id', $mandant->id)->where('role_id', 21)->get();
                                $internalUserFibu =  InternalMandantUser::where('mandant_id', $mandant->id)->where('role_id', 22)->get();
                                $internalUserLohn =  InternalMandantUser::where('mandant_id', $mandant->id)->where('role_id', 23)->get();

                                $rowArray = array(
                                    0 => $mandant->mandant_number,
                                    1 => $mandant->name,
                                    2 => $mandant->ort,
                                    3 => "-",
                                    4 => "-",
                                    5 => "-"
                                );

                                if(isset($internalUserLohn)){
                                    $rowArray[3] = '';
                                    foreach($internalUserLohn as $tmp){
                                        $user = User::where('id', $tmp->user_id)->first();
                                        $rowArray[3] .= $user->title .' '. $user->first_name .' '. $user->last_name ."; ";

                                    }
                                }

                                if(isset($internalUserFibu)){
                                    $rowArray[4] = '';
                                    foreach($internalUserFibu as $tmp){
                                        $user = User::where('id', $tmp->user_id)->first();
                                        $rowArray[4] .= $user->title.' '.$user->first_name.' '.$user->last_name ."; ";
                                    }
                                }

                                if(isset($internalUserEdv)){
                                    $rowArray[5] = '';
                                    foreach($internalUserEdv as $tmp){
                                        $user = User::where('id', $tmp->user_id)->first();
                                        $rowArray[5] .= $user->title .' '. $user->first_name .' '. $user->last_name ."; ";
                                    }
                                }
                                */

                                $cellValues = array($mandant->mandant_number, $mandant->name, $mandant->ort);
                                foreach ($phoneRoles as $phoneRole) {
                                    $value = '';
                                    $users = InternalMandantUser::where('mandant_id', $mandant->id)->where('role_id', $phoneRole->id)->get();
                                    foreach ($users as $internal) {
                                        $user = User::where('id', $internal->user_id)->first();
                                        $value .= $user->title.' '.$user->first_name.' '.$user->last_name.'; ';
                                    }
                                    array_push($cellValues, $value);
                                }

                                // dd($cellValues);

                                // Add rows
                                $sheet->appendRow($cellValues);
                            }
                        } else {
                            foreach ($exportMandants as $id) {
                                $mandant = Mandant::find($id);
                                $mandantInfo = MandantInfo::where('mandant_id', $id)->first();

                                $cellValues = array($mandant->mandant_number, $mandant->name, $mandant->ort);
                                foreach ($phoneRoles as $phoneRole) {
                                    $value = '';
                                    $users = InternalMandantUser::where('mandant_id', $mandant->id)->where('role_id', $phoneRole->id)->get();
                                    foreach ($users as $internal) {
                                        $user = User::where('id', $internal->user_id)->first();
                                        $value .= $user->title.' '.$user->first_name.' '.$user->last_name.'; ';
                                    }
                                    array_push($cellValues, $value);
                                }

                                // dd($cellValues);

                                // Add rows
                                $sheet->appendRow($cellValues);
                            }
                        }
                    });
                })->download('xls');

                return back();
            }
            break;

            case 3: {
                Excel::create('Telefonliste Export - Adressliste Mandanten-Gesamt', function ($excel) use ($exportMandants, $exportOption) {
                    $excel->setTitle('Adressliste Mandanten-Gesamt');
                    $excel->setDescription('Adressliste Mandanten-Gesamt');

                    // Add sheet
                    $excel->sheet('Alle Mandanten', function ($sheet) use ($exportMandants) {
                        $sheet->row(1, array('Nr.', 'Firma', 'Strasse', 'Ort', 'Telefon', 'Fax', 'Geschäftsführer', 'Mail'));

                        if (in_array('0', $exportMandants)) {
                            foreach (Mandant::all() as $mandant) {
                            
                                $rowArray = array(
                                    0 => $mandant->mandant_number,
                                    1 => $mandant->name,
                                    2 => $mandant->strasse,
                                    3 => $mandant->ort,
                                    4 => $mandant->telefon,
                                    5 => $mandant->fax,
                                    6 => $mandant->geschaftsfuhrer_history,
                                    7 => $mandant->email,
                                );

                                // Add rows
                                $sheet->appendRow($rowArray);
                            }
                        } else {
                            foreach ($exportMandants as $id) {
                                
                                $mandant = Mandant::find($id);
                                
                                $rowArray = array(
                                    0 => $mandant->mandant_number,
                                    1 => $mandant->name,
                                    2 => $mandant->strasse,
                                    3 => $mandant->ort,
                                    4 => $mandant->telefon,
                                    5 => $mandant->fax,
                                    6 => $mandant->geschaftsfuhrer_history,
                                    7 => $mandant->email,
                                );

                                // Add rows
                                $sheet->appendRow($rowArray);
                            }
                        }
                    });
                })->download('xls');

                return back();
            }
            break;

            case 4: {
                Excel::create('Telefonliste Export - Partner Gesamt', function ($excel) use ($exportMandants, $exportOption) {
                    $excel->setTitle('Partner Gesamt');
                    $excel->setDescription('Partner Gesamt');

                    // $mandant = Mandant::find($id);
                    // $mandantInfo = MandantInfo::where('mandant_id', 1)->first();
                    // $hauptstelle = Mandant::find($mandant->mandant_id_hauptstelle);

                    // Add sheet
                    $excel->sheet('Alle Mandanten', function ($sheet) use ($exportMandants) {
                        // dd($exportMandants);
                        $sheet->row(1, array('Nr.', 'Firma', 'Ort', 'Beteiligungspartner'));

                        if (in_array('0', $exportMandants)) {
                            foreach (Mandant::all() as $mandant) {
                                $mandantInfo = MandantInfo::where('mandant_id', $mandant->id)->first();

                                // Add rows
                                $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort, '-'));
                            }
                        } else {
                            foreach ($exportMandants as $id) {
                                $mandant = Mandant::find($id);
                                $mandantInfo = MandantInfo::where('mandant_id', $id)->first();

                                // Add rows
                                $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort, '-'));
                            }
                        }
                    });
                })->download('xls');

                return back();
            }
            break;

            case 5: {
                Excel::create('Telefonliste Export - Zeitarbeits-Partner', function ($excel) use ($exportMandants, $exportOption) {
                    $excel->setTitle('Zeitarbeits-Partner');
                    $excel->setDescription('Zeitarbeits-Partner');

                    // $mandant = Mandant::find($id);
                    // $mandantInfo = MandantInfo::where('mandant_id', 1)->first();
                    // $hauptstelle = Mandant::find($mandant->mandant_id_hauptstelle);

                    // Add sheet
                    $excel->sheet('Alle Mandanten', function ($sheet) use ($exportMandants) {
                        // dd($exportMandants);
                        $sheet->row(1, array('Nr.', 'Firma', 'Ort'));

                        if (in_array('0', $exportMandants)) {
                            foreach (Mandant::all() as $mandant) {
                                $mandantInfo = MandantInfo::where('mandant_id', $mandant->id)->first();

                                // Add rows
                                $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort));
                            }
                        } else {
                            foreach ($exportMandants as $id) {
                                $mandant = Mandant::find($id);
                                $mandantInfo = MandantInfo::where('mandant_id', $id)->first();

                                // Add rows
                                $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort));
                            }
                        }
                    });
                })->download('xls');

                return back();
            }
            break;

            case 6: {
                Excel::create('Telefonliste Export - Bankverbindungen', function ($excel) use ($exportMandants, $exportOption) {
                    $excel->setTitle('Bankverbindungen');
                    $excel->setDescription('Bankverbindungen');

                    // $mandant = Mandant::find($id);
                    // $mandantInfo = MandantInfo::where('mandant_id', 1)->first();
                    // $hauptstelle = Mandant::find($mandant->mandant_id_hauptstelle);

                    // Add sheet
                    $excel->sheet('Alle Mandanten', function ($sheet) use ($exportMandants) {
                        // dd($exportMandants);
                        $sheet->row(1, array('Nr.', 'Firma', 'Ort', 'Bankname', 'IBAN', 'BIC', 'Bemerkung'));

                        if (in_array('0', $exportMandants)) {
                            foreach (Mandant::all() as $mandant) {
                                $mandantInfo = MandantInfo::where('mandant_id', $mandant->id)->first();

                                $name = $iban = $bic = $memo = '-';
                                $bankInfos = array();

                                if (isset($mandantInfo->bankverbindungen)) {
                                    $bankInfos = explode(']', trim(str_replace(array('"', "\r\n"), '', $mandantInfo->bankverbindungen)));
                                }

                                if (count($bankInfos) > 1) {
                                    foreach ($bankInfos as $bankInfo) {
                                        if (!empty($bankInfo)) {
                                            $bank = explode(';', str_replace(array('[', ']'), '', $bankInfo));
                                            // Add rows
                                            $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort, $bank[0], $bank[1], $bank[2], $bank[3]));
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($exportMandants as $id) {
                                $mandant = Mandant::find($id);
                                $mandantInfo = MandantInfo::where('mandant_id', $id)->first();

                                $name = $iban = $bic = $memo = '-';
                                $bankInfos = array();

                                if (isset($mandantInfo->bankverbindungen)) {
                                    $bankInfos = explode(']', trim(str_replace(array('"', "\r\n"), '', $mandantInfo->bankverbindungen)));
                                }

                                if (count($bankInfos) > 1) {
                                    foreach ($bankInfos as $bankInfo) {
                                        if (!empty($bankInfo)) {
                                            $bank = explode(';', str_replace(array('[', ']'), '', $bankInfo));
                                            // Add rows
                                            $sheet->appendRow(array($mandant->mandant_number, $mandant->name, $mandant->ort, $bank[0], $bank[1], $bank[2], $bank[3]));
                                        }
                                    }
                                }
                            }
                        }
                    });
                })->download('xls');

                return back();
            }
            break;

            default:{
                return back();
                break;
            }
        }
    }
}
