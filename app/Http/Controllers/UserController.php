<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Helpers\ViewHelper;
use App\Http\Requests\BenutzerRequest;
use App\Http\Requests\BenutzerPartnerRequest;
use Carbon\Carbon;
use App\User;
use App\Role;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\InternalMandantUser;
use App\Document;
use App\DocumentType;
use App\UserReadDocument;
use App\UserEmailSetting;
use App\GlobalSettings;
use App\Http\Repositories\UtilityRepository;

class UserController extends Controller
{
    /**
     * Class constructor.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(UtilityRepository $utilities)
    {
        $this->utils = $utilities;
        // Define file upload path
        $this->fileUploadPath = public_path().'/files/pictures/users';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $searchParameter = null;
        $deletedUsers = null;
        $deletedMandants = null;
        if (ViewHelper::universalHasPermission(array(2, 4, 17), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        $mandantUserIds = MandantUserRole::whereIn('role_id', array(2, 4, 17))->pluck('mandant_user_id')->toArray();
        $mandantIds = MandantUser::where('user_id', $uid)->whereIn('id', $mandantUserIds)->pluck('mandant_id')->toArray();
        $roles = Role::all();
        $mandants = Mandant::whereIn('id', $mandantIds)->get();
        

        return view('mandanten.individualAdministration', compact('mandants', 'roles',
        'searchParameter', 'deletedUsers', 'deletedMandants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if(!$this->utils->universalHasPermission([2,4,17]))
        if (ViewHelper::universalHasPermission([17]) == false) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }

        return view('benutzer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BenutzerRequest $request)
    {
        if (!$request->has('username_sso') || $request->get('username_sso') == '' || empty($request->get('username_sso'))) {
            $request->merge(array('username_sso' => null));
        }

        // dd( $request->all() );
        $user = User::create($request->all());

        $userUpdate = User::find($user->id);
        $userUpdate->created_by = Auth::user()->id;
        $userUpdate->last_login = null;
        $userUpdate->save();

        if ($request->file()) {
            $userModel = User::find($user->id);
            $picture = $this->fileUpload($userModel, $this->fileUploadPath, $request->file());
            $userModel->update(['picture' => $picture]);
        }

        // Set all documents as read for new user
        $documents = Document::all();
        foreach ($documents as $document) {
            $readDocs = UserReadDocument::where('document_group_id', $document->document_group_id)
                        ->where('user_id', $user->id)->get();
            if (count($readDocs) == 0) {
                UserReadDocument::create([
                    'document_group_id' => $document->document_group_id,
                    'user_id' => $user->id,
                    'date_read' => Carbon::now(),
                    // 'date_read_last'=> Carbon::parse('1999-01-01 00:00:00')
                ]);
            }
        }

        // dd($userUpdate);
        // if($this->utils->universalHasPermission([2, 4, 17]) && !$this->utils->universalHasPermission())
        //     return redirect()->route('benutzer.edit-user-partner', [$user])->with('message', 'Benutzer erfolgreich gespeichert.');
        // else
        return redirect()->route('benutzer.edit', [$user])->with('message', 'Benutzer erfolgreich gespeichert.');
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
        // if(!$this->utils->universalHasPermission([2, 4, 17]))
        if (ViewHelper::universalHasPermission([17]) == false) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }

        $restored = false;

        if (User::find($id)) {
            $user = User::find($id);
        } elseif (User::withTrashed()->find($id)) {
            User::withTrashed()->find($id)->restore();
            $user = User::find($id);
            $restored = true;
        } else {
            $user = null;
        }

        // $user = User::find($id);
        $usersAll = User::orderBy('last_name', 'asc')->get();
        $mandantsAll = Mandant::all();
        // $rolesAll = Role::all();
        // $rolesAll = Role::where('phone_role', false)->get();
        $rolesAll = Role::all();

        $mandantUsers = MandantUser::where('user_id', $id)->get();
        $mandants = Mandant::whereIn('id', array_pluck($mandantUsers, 'mandant_id'))->get();
        $mandantUserRoles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        $roles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();

        $defaultRoles = $this->utils->getDefaultUserRoleSettings();

        // dd($mandantUserRoles);
        if (isset($user)) {
            return view('benutzer.edit', compact('user', 'usersAll', 'mandantsAll', 'rolesAll', 'defaultRoles'));
        } else {
            return back()->with('message', 'Benutzer existiert nicht oder kann nicht bearbeitet werden.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function editPartner($id)
    {
        if (ViewHelper::universalHasPermission([2, 4, 17], false) == false) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }

        $restored = false;

        if (User::find($id)) {
            $user = User::find($id);
        } elseif (User::withTrashed()->find($id)) {
            User::withTrashed()->find($id)->restore();
            $user = User::find($id);
            $restored = true;
        } else {
            $user = null;
        }

        // $user = User::find($id);
        $usersAll = User::all();
        $mandantsAll = Mandant::all();
        // $rolesAll = Role::where('phone_role', false)->get();
        $rolesAll = Role::where('mandant_role', 1)->get();

        $loggedUserMandants = MandantUser::where('user_id', Auth::user()->id)->get();
        $mandantsUser = Mandant::whereIn('id', $loggedUserMandants->pluck('mandant_id'))->get();

        $defaultRoles = $this->utils->getDefaultUserRoleSettings();

        // $loggedUserRoles = MandantUserRole::where('mandant_user_id', MandantUser::where('user_id', Auth::user()->id)->where('mandant_id', $mandantId)->first()->id)->get();

        // foreach ($loggedUserRoles as $tmp) {
        //     if(!in_array($tmp->role_id, array_pluck($rolesAll,'id')))
        //       $rolesAll->push(Role::find($tmp->role_id));
        // }

        foreach ($defaultRoles as $def) {
            if (!in_array($def, array_pluck($rolesAll, 'id'))) {
                $rolesAll->push(Role::find($def));
            }
        }

        // dd($rolesAll);

        $mandantUsers = MandantUser::where('user_id', $id)->whereIn('mandant_id', $mandantsUser->pluck('id'))->get();
        $mandants = Mandant::whereIn('id', array_pluck($mandantUsers, 'mandant_id'))->get();
        $mandantUserRoles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        $roles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();

        // Check permission to edit user (user should belong to mandants you own)
        if ($user) {
            $canEdit = count(MandantUser::where('user_id', $user->id)->whereIn('mandant_id', $mandantsUser->pluck('id'))->get());
        }

        if ($canEdit) {
            return view('benutzer.editPartner', compact('user', 'usersAll', 'mandantsAll', 'mandantsUser', 'rolesAll', 'mandantUsers', 'defaultRoles'));
        } else {
            return back()->with('message', 'Benutzer existiert nicht oder kann nicht bearbeitet werden.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function createPartner(Request $request)
    {
        if (ViewHelper::universalHasPermission([2, 4, 17], false) == false) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }
        
        $rolesAll = Role::where('mandant_role', 1)->get();
        $loggedUserMandants = MandantUser::where('user_id', Auth::user()->id)->get();
        // $loggedUserRoles = MandantUserRole::whereIn('mandant_user_id', $loggedUserMandants->pluck('id'))->get();
        $mandantsAll = Mandant::whereIn('id', $loggedUserMandants->pluck('mandant_id'))->get();

        $defaultRoles = $this->utils->getDefaultUserRoleSettings();
        $defaultRoles = Role::whereIn('id', $defaultRoles)->get();
        // foreach ($loggedUserRoles as $tmp) {
        //     if(!in_array($tmp->role_id, array_pluck($rolesAll,'id')))
        //       $rolesAll->push(Role::find($tmp->role_id));
        // }

        return view('benutzer.createPartner', compact('user', 'mandantsAll', 'rolesAll', 'defaultRoles'));
    }

    /**
     * Save user/role data for partner roles (GF, NL etc.).
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function createPartnerStore(BenutzerPartnerRequest $request)
    {
        // dd( $request->all() );

        if (!$request->has('username_sso') || $request->get('username_sso') == '' || empty($request->get('username_sso'))) {
            $request->merge(array('username_sso' => null));
        }

        $user = User::create($request->all());
        // $user = new User($request->all());
        
        $userUpdate = User::find($user->id);
        $userUpdate->created_by = Auth::user()->id;
        $userUpdate->last_login = null;
        $this->generateUsername($userUpdate);
        $userUpdate->save();

        // $defaultRoles = $this->utils->getDefaultUserRoleSettings();
        $defaultRoles = array();
        $mandantId = $request->get('mandant_id');
        $roles = $request->get('role_id');

        $mandantUser = MandantUser::create(['mandant_id' => $mandantId, 'user_id' => $user->id]);
        foreach ($roles as $role) {
            MandantUserRole::create(['mandant_user_id' => $mandantUser->id, 'role_id' => $role]);
        }
        foreach ($defaultRoles as $def) {
            if (!in_array($def, $roles)) {
                MandantUserRole::create(['mandant_user_id' => $mandantUser->id, 'role_id' => $def]);
            }
        }

        if ($request->file()) {
            $userModel = User::find($user->id);
            $picture = $this->fileUpload($userModel, $this->fileUploadPath, $request->file());
            $userModel->update(['picture' => $picture]);
        }

        // Set all documents as read for new user
        $documents = Document::all();
        foreach ($documents as $document) {
            $readDocs = UserReadDocument::where('document_group_id', $document->document_group_id)
                        ->where('user_id', $user->id)->get();
            if (count($readDocs) == 0) {
                UserReadDocument::create([
                    'document_group_id' => $document->document_group_id,
                    'user_id' => $user->id,
                    'date_read' => Carbon::now(),
                    // 'date_read_last'=> Carbon::parse('1999-01-01 00:00:00')
                ]);
            }
        }
        // benutzer/{id}/edit-partner/{mandant_id}
        return redirect('benutzer/'.$user->id.'/partner/edit')->with('message', 'Benutzer erfolgreich gespeichert.');
    }

    /**
     * Save mandant/user/role data for partner roles (GF, NL etc.).
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function createPartnerRolesStore(Request $request)
    {
        // dd($request->all());
        $checkMandant = MandantUser::where('mandant_id', $request->get('mandant_id'))->where('user_id', $request->get('user_id'))->count();
        if ($checkMandant > 0) {
            return back()->with('message', 'Dieser Mandant ist dem Benutzer bereits zugeordnet.');
        }

        $mandantUser = MandantUser::create($request->all());

        foreach ($request->input('role_id') as $roleId) {
            $tmpRole = Role::find($roleId);
            $mandantUserRole = new MandantUserRole();
            $mandantUserRole->mandant_user_id = $mandantUser->id;
            $mandantUserRole->role_id = $roleId;
            $mandantUserRole->save();
        }
        // return back()->with('message', 'Mandant und Rollen erfolgreich gespeichert.');
        return redirect('benutzer/'.$mandantUser->user_id.'/partner/edit#mandant-role-'.$mandantUser->id)->with('message', 'Mandant und Rollen erfolgreich gespeichert.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BenutzerRequest $request, $id)
    {
        $user = User::find($id);
        // $user->update($request->all());
         if (!$request->has('username_sso') || $request->get('username_sso') == '' || empty($request->get('username_sso'))) {
             $request->merge(array('username_sso' => null));
         }
        $user->update($request->except(['password', 'password_repeat']));

        // Fix Carbon date parsing
        if (!$request->has('birthday')) {
            $user->update(['birthday' => '']);
        }
        if (!$request->has('active_from')) {
            $user->update(['active_from' => '']);
        }
        if (!$request->has('active_to')) {
            $user->update(['active_to' => '']);
        }

        if ($request->has('active')) {
            $user->update(['active' => true]);
        } else {
            $user->update(['active' => false]);
        }

        if ($request->has('email_reciever')) {
            $user->update(['email_reciever' => true]);
        } else {
            $user->update(['email_reciever' => false]);
        }

        $pass = $request->input('password');
        $passRep = $request->input('password_repeat');

        if (!empty($pass) && ($pass == $passRep)) {
            $user->update(['password' => $pass]);
        }

        if ($request->file()) {
            $userModel = User::find($user->id);
            $picture = $this->fileUpload($userModel, $this->fileUploadPath, $request->file());
            $userModel->update(['picture' => $picture]);
        }

        // dd($request->all());
        // dd($user->getAttributes());
        // $user->save();

        if ($request->has('partner-role')) {
            return back()->with('message', 'Benutzer erfolgreich aktualisiert.');
        }

        return back()->with('message', 'Benutzer erfolgreich aktualisiert.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $id = Auth::user()->id;
        $documentTypes = DocumentType::where('publish_sending', true)->get();
        // $emailSettings = UserEmailSetting::where('user_id', $id)->get();
        $emailSettings = UserEmailSetting::where('user_id', $id)->orderBy('document_type_id')->orderBy('email_recievers_id')->get();

        if (User::find($id)) {
            $user = User::find($id);
        } elseif (User::withTrashed()->find($id)) {
            User::withTrashed()->find($id)->restore();
            $user = User::find($id);
            $restored = true;
        } else {
            $user = null;
        }

        // NEPTUN-818
        // $rolesAll = Role::all();
        // $emailRecievers = Role::where('system_role', true)->get();
        $loggedUserMandants = MandantUser::where('user_id', $id)->get();
        $mandantsUser = Mandant::whereIn('id', $loggedUserMandants->pluck('mandant_id'))->get();
        $mandantUsers = MandantUser::where('user_id', $id)->whereIn('mandant_id', $mandantsUser->pluck('id'))->get();
        $mandants = Mandant::whereIn('id', array_pluck($mandantUsers, 'mandant_id'))->get();
        $mandantUserRoles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        $emailRecievers = Role::whereIn('id', $mandantUserRoles->pluck('role_id'))->get();

        if (isset($user)) {
            return view('benutzer.profile', compact('user', 'rolesAll', 'documentTypes', 'emailRecievers', 'emailSettings'));
        } else {
            return back()->with('message', 'Benutzer existiert nicht oder kann nicht bearbeitet werden.');
        }
    }

    /**
     * Store users email preferences.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveEmailSettings(Request $request)
    {
        // dd($request->all());
        $uid = Auth::user()->id;
        $emailSetting = new UserEmailSetting();

        $emailSetting->user_id = $uid;

        if ($request->settings_document_type == 'all') {
            $emailSetting->document_type_id = 0;
        } else {
            $emailSetting->document_type_id = $request->settings_document_type;
        }

        if ($request->settings_email_recievers == 'all') {
            $emailSetting->email_recievers_id = 0;
        } else {
            $emailSetting->email_recievers_id = $request->settings_email_recievers;
        }

        $emailSetting->sending_method = $request->settings_sending_method;

        if (in_array($emailSetting->sending_method, [1, 2])) {
            $emailSetting->recievers_text = $request->settings_email;
        }

        if (in_array($emailSetting->sending_method, [3])) {
            $emailSetting->recievers_text = $request->settings_fax_custom;
        }
        
        if ($emailSetting->sending_method == 4) {
            // $mandant = Mandant::find($request->settings_mandant);
            // $address = $mandant->strasse .' '. $mandant->hausnummer .' '. $mandant->plz .' '. $mandant->ort .' '. $mandant->bundesland .' '. $mandant->adreszusatz;
            $emailSetting->mandant_id = $request->settings_mandant;
        }

        // dd($emailSetting);
        $emailSetting->save();

        return back()->with('message', trans('benutzerForm.saveEmailSettingSuccess'));
    }

    /**
     * Update users email preferences.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateEmailSettings(Request $request)
    {
        $setting = UserEmailSetting::find($request->user_email_setting_id);
        if ($setting) {
            if ($request->has('active')) {
                $setting->active = $request->active;
                $setting->save();

                return back()->with('message', trans('benutzerForm.updateEmailSettingSuccess'));
            }

            if ($request->has('delete')) {
                $setting->delete();

                return back()->with('message', trans('benutzerForm.deleteEmailSettingSuccess'));
            }
        } else {
            return back()->with('message', trans('benutzerForm.saveEmailSettingError'));
        }
    }

    /**
     * Show the form for editing the defaults for new users.
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultUser()
    {
        if (ViewHelper::universalHasPermission(array(6)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $defaultRoles = $this->utils->getDefaultUserRoleSettings();
        $rolesAll = Role::all();

        return view('benutzer.defaultUser', compact('rolesAll', 'defaultRoles'));
    }

    /**
     * Save defaults for new users.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultUserSave(Request $request)
    {
        GlobalSettings::where('category', 'users')->where('name', 'defaultRoles')->delete();
        if ($roleIds = $request->get('role_id')) {
            foreach ($roleIds as $roleId) {
                GlobalSettings::create(['category' => 'users', 'name' => 'defaultRoles', 'value' => $roleId]);
            }
        }

        return back()->with('message', trans('benutzerForm.saveSuccess'));
    }

    /**
     * Transfer roles from one user to another.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function userRoleTransfer(Request $request)
    {
        // dd($request->all());
        $user = $request->input('user_id'); //primary user
        $selectedUser = $request->input('user_transfer_id'); //dropdown user
        $userMandants = MandantUser::where('user_id', $user)->pluck('mandant_id')->toArray(); //pUser Mandants

        $sourceUserId = $user;
        $targetUserId = $selectedUser;

        $targetUserDocs = UserReadDocument::where('user_id', $targetUserId)->get();
        $sourceUserDocs = UserReadDocument::where('user_id', $sourceUserId)->whereNotIn('document_group_id', array_pluck($targetUserDocs, 'document_group_id'))->get();

        // Copy missing read documents from source to target user
        foreach ($sourceUserDocs as $readDoc) {
            UserReadDocument::create([
                'document_group_id' => $readDoc->document_group_id,
                'user_id' => $targetUserId,
                'date_read' => Carbon::now(),
                'date_read_last' => Carbon::now(),
            ]);
        }

        $mandantUsers = MandantUser::where('user_id', $selectedUser)->get(); //ddUser in user mandant

        foreach ($mandantUsers as $mandantUser) {
            $sUserM = MandantUser::where('user_id', $user)->where('mandant_id', $mandantUser->mandant_id)->first();
            if (!count($sUserM)) {
                $sUserM = MandantUser::create(['user_id' => $user, 'mandant_id' => $mandantUser->mandant_id]);
            }

            // dd($sUserM);
            $selectedMURoles = MandantUserRole::where('mandant_user_id', $mandantUser->id)->get();

            $userMandantUserRoles = MandantUserRole::where('mandant_user_id', $sUserM->id)->pluck('role_id')->toArray();
            foreach ($selectedMURoles as $smur) {
                if (!in_array($smur->role_id, $userMandantUserRoles)) {
                    $create = MandantUserRole::create(['mandant_user_id' => $sUserM->id, 'role_id' => $smur->role_id]);
                }
            }
        }

        return back()->with('message', 'Rollenübertragung erfolgreich abgeschlossen.');
    }

    /**
     * Assign a mandant and roles for the user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function userMandantRoleAdd(Request $request)
    {
        // dd($request->all());
        $checkMandant = MandantUser::where('mandant_id', $request->get('mandant_id'))->where('user_id', $request->get('user_id'))->count();
        if ($checkMandant > 0) {
            return back()->with('message', 'Dieser Mandant ist dem Benutzer bereits zugeordnet.');
        }

        $mandantUser = MandantUser::create($request->all());

        foreach ($request->input('role_id') as $roleId) {
            $tmpRole = Role::find($roleId);

            // if($tmpRole->phone_role){
            //     $internalMandantUser = InternalMandantUser::create([
            //         'mandant_id' => $request->get('mandant_id'),
            //         'role_id' => $roleId,
            //         'user_id' => $request->get('user_id'),
            //     ]);
            // }

            $mandantUserRole = new MandantUserRole();
            $mandantUserRole->mandant_user_id = $mandantUser->id;
            $mandantUserRole->role_id = $roleId;
            $mandantUserRole->save();
        }
        // return back()->with('message', 'Mandant und Rollen erfolgreich gespeichert.');
        return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandant-role-'.$mandantUser->id)->with('message', 'Mandant und Rollen erfolgreich gespeichert.');
    }

    /**
     * Update mandant roles for the user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function userMandantRoleEdit(Request $request)
    {
        // dd($request->all());
        $mandantUser = MandantUser::where('id', $request->input('mandant_user_id'))->first();
        if ($request->has('save')) {
            // dd( $request->all() );
            $clearedRoles = $request->has('role_id');
            $mandantUserRoles = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->pluck('role_id')->toArray();
            $noDeleteArr = array();

            if ($request->has('partner-role')) {
                // Delete all "partner" roles for the "mandant_user_id"
                $partnerRoles = Role::where('mandant_role', 1)->pluck('id')->toArray();
                // dd($partnerRoles);
                // MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->whereIn('role_id', $partnerRoles)->delete();
                MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->delete();
            } else {
                if (!count($request->input('role_id'))) {
                    return back()->with('message', 'Rollen dürfen nicht leer sein.');
                }

                $temp = $this->preventDeleteRoles(
                    $mandantUserRoles, $request->input('role_id'),
                    $request->input('mandant_id'),
                    $request->input('mandant_user_id')
                );

                if (count($temp)) {
                    $noDeleteArr = $temp;
                }

                $del = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->whereNotIn('role_id', $noDeleteArr)->delete();
            }

            $requestRoleArray = array();
            if ($request->has('role_id')) {
                foreach ($request->input('role_id') as $roleId) {
                    $mandantUserRole = new MandantUserRole();
                    $mandantUserRole->mandant_user_id = $request->input('mandant_user_id');
                    $mandantUserRole->role_id = $roleId;
                    $mandantUserRole->save();
                }
            }
            // return back()->with('message', 'Rollen erfolgreich aktualisiert.');
            $message = '';

            if (count($noDeleteArr)) {
                $message .= trans('benutzerForm.lastMandantRole').'<br/>';
            }
            $message .= 'Rollen erfolgreich aktualisiert.';
            //  dd($message);

            if ($request->has('partner-role')) {
                return back()->with('message', $message);
            }

            return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandant-role-'.$mandantUser->id)->with('message', $message);
        }

        if ($request->has('remove')) {
            // dd($request->all());
            $mandantUserRoles = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->pluck('role_id')->toArray();
            $noDeleteArr = array();

            if ($request->has('role_id')) {
                $temp = $this->preventDeleteRoles($mandantUserRoles, $request->input('role_id'),
                    $request->input('mandant_id'), $request->input('mandant_user_id'), true);

                if (count($temp)) {
                    $noDeleteArr = $temp;
                }
            }

            $message = '';
            if (count($noDeleteArr)) {
                $message .= trans('benutzerForm.lastMandantRole');

                return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandant-role-'.$mandantUser->id)->with('message', $message);
            }
            MandantUser::where('id', $request->input('mandant_user_id'))->delete();
            MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->delete();

            return back()->with('message', 'Rollen wurden entfernt.');
            // return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandants-roles')->with('message', 'Rollen wurden entfernt.');
        }
    }

    /**
     * Update mandant roles for the user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function userMandantRoleEditPartner(Request $request)
    {
        // dd($request->all());
        $mandantUser = MandantUser::where('id', $request->input('mandant_user_id'))->first();

        if ($request->has('save')) {
            $clearedRoles = $request->has('role_id');
            $mandantUserRoles = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->pluck('role_id')->toArray();
            $noDeleteArr = array();

            if ($request->has('partner-role')) {
                // Delete all "partner" roles for the "mandant_user_id"
                $partnerRoles = Role::where('mandant_role', 1)->pluck('id')->toArray();
                // dd($partnerRoles);
                // MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->whereIn('role_id', $partnerRoles)->delete();
                MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->delete();
            } else {
                if (!count($request->input('role_id'))) {
                    return back()->with('message', 'Rollen dürfen nicht leer sein.');
                }

                $temp = $this->preventDeleteRoles(
                    $mandantUserRoles, $request->input('role_id'),
                    $request->input('mandant_id'),
                    $request->input('mandant_user_id')
                );

                if (count($temp)) {
                    $noDeleteArr = $temp;
                }

                $del = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->whereNotIn('role_id', $noDeleteArr)->delete();
            }

            $requestRoleArray = array();
            if ($request->has('role_id')) {
                foreach ($request->input('role_id') as $roleId) {
                    $mandantUserRole = new MandantUserRole();
                    $mandantUserRole->mandant_user_id = $request->input('mandant_user_id');
                    $mandantUserRole->role_id = $roleId;
                    $mandantUserRole->save();
                }
            }
            // return back()->with('message', 'Rollen erfolgreich aktualisiert.');
            $message = '';

            if (count($noDeleteArr)) {
                $message .= trans('benutzerForm.lastMandantRole').'<br/>';
            }
            $message .= 'Rollen erfolgreich aktualisiert.';
            //  dd($message);

            if ($request->has('partner-role')) {
                return back()->with('message', $message);
            }

            return redirect('benutzer/'.$mandantUser->user_id.'/partner/edit#mandant-role-'.$mandantUser->id)->with('message', $message);
        }

        if ($request->has('remove')) {
            // dd($request->all());
            $mandantUserRoles = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->pluck('role_id')->toArray();
            $noDeleteArr = array();

            if ($request->has('role_id')) {
                $temp = $this->preventDeleteRoles($mandantUserRoles, $request->input('role_id'),
                    $request->input('mandant_id'), $request->input('mandant_user_id'), true);

                if (count($temp)) {
                    $noDeleteArr = $temp;
                }
            }

            $message = '';
            if (count($noDeleteArr)) {
                $message .= trans('benutzerForm.lastMandantRole');

                return redirect('benutzer/'.$mandantUser->user_id.'/partner/edit#mandant-role-'.$mandantUser->id)->with('message', $message);
            }

            // Count for mandants where the user belongs, and where you are able do manage users
            $loggedUserMandants = MandantUser::where('user_id', Auth::user()->id)->get();
            $mandantsUser = Mandant::whereIn('id', $loggedUserMandants->pluck('mandant_id'))->get();
            $muCount = count(MandantUser::where('user_id', $request->input('user_id'))->whereIn('mandant_id', $mandantsUser->pluck('id'))->get());

            // Prevent deletion if only 1 mandant is assigned
            if ($muCount > 1) {
                MandantUser::where('id', $request->input('mandant_user_id'))->delete();
                MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->delete();
                // return back()->with('message', 'Rollen wurden entfernt.');
                return redirect('benutzer/'.$mandantUser->user_id.'/partner/edit#mandants-roles')->with('message', 'Rollen wurden entfernt.');
            } else {
                return redirect('benutzer/'.$mandantUser->user_id.'/partner/edit#mandants-roles')->with('message', 'Mindestens ein Mandant muss zugeordnet sein.');
            }
        }
    }

    /**
     * Activate or deactivate a user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function userActivate(Request $request)
    {
        User::find($request->input('user_id'))->update(['active' => !$request->input('active')]);

        return back()->with('message', 'Benutzer erfolgreich aktualisiert.')->with('mandantChanged', $request->get('mandant_id'));
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
        $user = User::findOrFail($id);

        if ($user->id == 1) {
            return back()->with('message', 'Benutzer kann nicht entfernt werden.');
        }

        $mandantUsers = MandantUser::where('user_id', $id)->get();

        foreach ($mandantUsers as $mandantUser) {
            $mandantUserRoles = MandantUserRole::where('mandant_user_id', $mandantUser->id)->get();
            foreach ($mandantUserRoles as $mandantUserRole) {
                $mandantUserRole->delete();
            }
            $mandantUser->delete();
        }

        $user->delete();

        return redirect('mandanten')->with('message', 'Benutzer erfolgreich entfernt.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyMandantUser(Request $request)
    {
        $requiredUsers = array();
        $requiredRoles = array();
        $requiredRolesResult = array();

        $mandantUser = MandantUser::where('user_id', $request->input('user_id'))->where('mandant_id', $request->input('mandant_id'))->first();
        $mandantUserAll = MandantUser::where('mandant_id', $request->input('mandant_id'))->get();

        foreach ($mandantUser->role as $role) {
            if ($role->mandant_required || $role->system_role) {
                array_push($requiredRoles, $role);
            }
        }

        foreach ($requiredRoles as $requiredRole) {
            $roleUsers = array();
            foreach ($mandantUserAll as $mu) {
                foreach ($mu->mandantUserRoles as $mandantUserRole) {
                    if ($requiredRole->id == $mandantUserRole->role_id) {
                        if (!in_array($mu, $roleUsers)) {
                            array_push($roleUsers, $mu);
                        }
                    }
                }
            }
            $requiredRolesResult[] = array('role_id' => $requiredRole->id, 'user_count' => count($roleUsers));
        }

        // dd($requiredRolesResult);

        foreach ($requiredRolesResult as $reqRole) {
            if ($reqRole['user_count'] == 1) {
                return redirect('benutzer')->with('message', 'Benutzer kann nicht entfernt werden.');
            }
        }

        $mandantUserRoles = MandantUserRole::where('mandant_user_id', $mandantUser->id)->get();
        foreach ($mandantUserRoles as $mandantUserRole) {
            // dd($mandantUserRole->roles);
            $mandantUserRole->delete();
        }
        $mandantUser->delete();
        InternalMandantUser::where('user_id', $request->input('user_id'))->where('mandant_id', $request->input('mandant_id'))->delete();

        return redirect('benutzer')->with('message', 'Benutzer erfolgreich entfernt.');
    }

    private function fileUpload($model, $path, $files)
    {
        if (is_array($files)) {
            $uploadedNames = array();
            foreach ($files as $file) {
                $uploadedNames = $this->moveUploaded($file, $path, $model);
            }
        } else {
            $uploadedNames = $this->moveUploaded($files, $path, $model);
        }

        return $uploadedNames;
    }

    private function moveUploaded($file, $folder, $model)
    {
        $newName = str_slug('user-'.$model->id).'.'.$file->getClientOriginalExtension();
        $path = $folder.'/'.$newName;
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder.'/'.$filename);

        return $newName;
    }

    /**
     * Check if roles are system roles and if this user is the only user in mandat with this role.
     *
     * @param array $roleArray
     *
     * @return array $roleArray
     */
    public function preventDeleteRoles($mandantUserRoles, $roleArray, $mandantId, $mandatUserId, $deleteCheck = false)
    {
        $difference = array_diff($mandantUserRoles, $roleArray);
        $noDeleteArr = array();
        $midCheck = array();
        $midCheck2 = array();
        if ($deleteCheck == false) {
            foreach ($difference as $k => $role) {
                $roleDb = Role::find($role);
                if ($roleDb->mandant_required == 1) {
                    $mUsers = MandantUser::where('mandant_id', $mandantId)->get();
                    // $mUsers = MandantUser::where('mandant_user_id', $mandantId)->where('role_id',$role)->get();
                    // dd($mUsers);
                    $count = 0;
                    foreach ($mUsers as $mUser) {
                        $mur = MandantUserRole::where('mandant_user_id', $mUser->id)->where('role_id', $role)->first();
                        if ($mur != null) {
                            $midCheck2[] = $mur->mandant_user_id;
                            if (count($mur)) {
                                if (in_array($mur->mandant_user_id, $midCheck) == false) {
                                    ++$count;
                                    $midCheck[] = $mur->mandant_user_id;
                                }
                            }
                        }
                    }
                    if ($count <= 1) {
                        $noDeleteArr[] = $role;
                    }
                }
            }
        } else {
            foreach ($mandantUserRoles as $role) {
                $roleDb = Role::find($role);
                if ($roleDb->mandant_required == 1) {
                    $mUsers = MandantUser::where('mandant_id', $mandantId)->get();
                    // $mUsers = MandantUser::where('mandant_user_id', $mandantId)->where('role_id',$role)->get();
                    // dd($mUsers);
                    $count = 0;
                    foreach ($mUsers as $mUser) {
                        $mur = MandantUserRole::where('mandant_user_id', $mUser->id)->where('role_id', $role)->first();
                        if ($mur != null) {
                            $midCheck2[] = $mur->mandant_user_id;
                            if (count($mur)) {
                                if (in_array($mur->mandant_user_id, $midCheck) == false) {
                                    ++$count;
                                    $midCheck[] = $mur->mandant_user_id;
                                }
                            }
                        }
                    }
                    if ($count <= 1) {
                        $noDeleteArr[] = $role;
                    }
                }
            }
        }

        return $noDeleteArr;
    }
    
    private function generateUsername(User $user){
        
        $counter = 1;
        $username = false;
        $part1 = str_slug($user->last_name);
        $part2 = str_slug($user->first_name);
        $nameLength = strlen($part2);
        
        for($i=1; $i <= $nameLength; $i++){
            $tmpUsername = $part1 . substr($part2, 0, $i);
            $dbUser = User::where('username', $tmpUsername)->first();
            if(is_null($dbUser)) {
                $username = $tmpUsername;
                break;
            }
        }
        
        while($username == false) {
            $tmpUsername = $part1 . $part2 . $counter;
            $dbUser = User::where('username', $tmpUsername)->first();
            if(is_null($dbUser)) {
                $username = $tmpUsername;
                break;
            }
            $counter += 1;
        }
        
        $user->username = $username;
    }
    
}
