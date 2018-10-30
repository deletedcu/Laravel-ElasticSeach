<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Auth;
use Carbon\Carbon;
use File;
use Mail;
use App\Document;
use App\DocumentComment;
use App\DocumentCoauthor;
use App\Mandant;
use App\User;
use App\MandantUser;
use App\MandantUserRole;
use App\WikiPage;
use App\WikiCategory;
use App\WikiCategoryUser;
use App\DocumentApproval;
use App\ContactMessage;
use App\MessageFile;
use App\Helpers\ViewHelper;
use App\Http\Repositories\DocumentRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(DocumentRepository $docRepo)
    {
        $this->document = $docRepo;
        $this->uploadPath = public_path().'/files/contacts/';
    }

    /**
     * Create a length aware custom paginator instance.
     *
     * @param Collection $items
     * @param int        $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        /*
            if role Struktur admin view all ()
            maybe if document/Rundscriben verfasser
            else if Find all document where document  user_id,owner_user_id or coAuthor
        */
        $documentsNew = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
        ->where('document_status_id', 3)->where('is_attachment', 0)->where('documents.active', 1)
        ->where('document_types.document_art', 0)
        ->orderBy('documents.date_published', 'desc')->limit(50)
        ->get(['*', 'document_types.name as docTypeName', 'documents.name as name',
        'document_types.id as docTypeId', 'documents.id as id', 'documents.created_at as created_at', ]);
        
        // Hide documents that have publish date higher than today
        $documentsNew = $documentsNew->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });
        
        $documentsNew = $this->document->getUserPermissionedDocuments($documentsNew, 'neue-dokumente', array('field' => 'documents.date_published', 'sort' => 'desc'), $perPage = 10);
        
        $documentsNewTree = $this->document->generateTreeview($documentsNew, array('pageHome' => true, 'showAttachments' => true, 'showHistory' => true));

        $myRundCoauthor = DocumentCoauthor::where('user_id', Auth::user()->id)->pluck('document_id')->toArray();
        // dd($myRundCoauthor);

        $rundschreibenMy = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
        ->where('documents.active', 1)
        ->where('document_types.document_art', 0)
        ->where('document_types.jurist_document', 0)
        ->where(
            /* Neptun-345 */
            function ($query) use ($myRundCoauthor) {
                $query->where('user_id', Auth::user()->id)->orWhere('owner_user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', $myRundCoauthor);
            }
        );
        
        // JIRA Task NEPTUN-620
        // Filter highest document version
        $myDocIds = array();
        $docGroupIds = $rundschreibenMy->pluck('document_group_id')->unique()->sort()->values();
        foreach($docGroupIds as $groupId){
            $myDocIds[] = Document::where('document_group_id', $groupId)->orderBy('version', 'desc')->pluck('id')->first();
        }
        $rundschreibenMy = $rundschreibenMy->whereIn('documents.id', $myDocIds);
        
        // JIRA Task NEPTUN-657
        if ($filter) {
            // Draft ($document->document_status_id == 1)
            // Released ($document->document_status_id == 3)
            // Not Released in_array($document->document_status_id, [2,6])
            // Approved ($document->document_status_id == 2)
            // Not Approved ($document->document_status_id != 2)

            if ($filter == 'draft') {
                $rundschreibenMy = $rundschreibenMy->where('documents.document_status_id', 1);
            }
            if ($filter == 'approved') {
                $rundschreibenMy = $rundschreibenMy->where('documents.document_status_id', 2);
            }
            if ($filter == 'not-approved') {
                $rundschreibenMy = $rundschreibenMy->where('documents.document_status_id', 6);
            }
            if ($filter == 'published') {
                $rundschreibenMy = $rundschreibenMy->where('documents.document_status_id', 3);
            }
            if ($filter == 'not-published') {
                $rundschreibenMy = $rundschreibenMy->whereIn('documents.document_status_id', [2, 6]);
            }
        }

        $rundschreibenMy = $rundschreibenMy->limit(50)
        ->orderBy('documents.id', 'desc')->get(['documents.id as id', 'documents.date_published as date_published']);
        
        // Hide documents that have publish date higher than today
        $rundschreibenMy = $rundschreibenMy->reject(function($document, $key){
            return Carbon::parse($document->date_published)->gt(Carbon::today());
        });
        
        $rundschreibenMy = Document::whereIn('id', array_pluck($rundschreibenMy, 'id'))->orderBy('date_published', 'desc')
        ->paginate(10, ['*'], 'meine-rundschrieben');
        
        // dd($rundschreibenMy);
        $rundschreibenMyTree = $this->document->generateTreeview($rundschreibenMy, array('pageHome' => true, 'showHistory' => true, 'myDocuments' => true));

        $uid = Auth::user()->id;
        $approval = DocumentApproval::where('user_id', $uid)->where('date_approved', null)->pluck('document_id')->toArray();

        $freigabeEntries = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
        ->whereIn('documents.document_status_id', [2, 6])
        ->where('document_types.document_art', 0)
        ->where(function ($query) use ($approval) {
            $query->where('documents.user_id', Auth::user()->id)
                  ->orWhere('documents.owner_user_id', Auth::user()->id);
        })
        ->where('documents.active', 1)
        ->orWhereIn('documents.id', $approval)
        ->orderBy('documents.id', 'desc')->limit(50)->get(['documents.id as id']);
        // ->paginate(10, ['*', 'documents.id as id', 'documents.created_at as created_at', 'documents.name as name' ],'freigabe-dokumente');
        // dd($freigabeEntries);
        $freigabeEntries = Document::whereIn('id', array_pluck($freigabeEntries, 'id'))->orderBy('documents.id', 'desc')->whereIn('documents.document_status_id', [2, 6])
        ->paginate(10, ['*'], 'freigabe-dokumente');

        $freigabeEntriesTree = $this->document->generateTreeview($freigabeEntries, array('pageHome' => true));

        /* Wiki setup */
            $categoriesId = WikiCategoryUser::where('user_id', Auth::user()->id)->pluck('wiki_category_id')->toArray();
        if (ViewHelper::universalHasPermission(array())) {
            $categoriesId = WikiCategory::pluck('id')->toArray();
        }
        $wikiPermissions = ViewHelper::getWikiUserCategories();
        $categoriesId = $wikiPermissions->categoriesIdArray;
            // dd($categoriesId);
        /* End Wiki setup */
        $wikiEntries = $this->document->generateWikiTreeview(WikiPage::where('status_id', 2)->whereIn('category_id', $categoriesId)
        ->orderBy('updated_at', 'DESC')->take(5)->get());

        $commentsNew = DocumentComment::where('id', '>', 0)->orderBy('updated_at', 'desc')->take(10)->get();

        $commentVisibility = false;
        $uid = Auth::user()->id;
        /* Freigabe user */
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 9 || $umr->role_id == 1) {
                    $commentVisibility = true;
                }
            }
        }
        /* End Freigabe user */
        $commentsMy = DocumentComment::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->take(10)->get();

        return view('dashboard', compact('documentsNew', 'documentsNewTree', 'rundschreibenMy', 'rundschreibenMyTree', 'freigabeEntries', 'freigabeEntriesTree', 'wikiEntries', 'commentsNew', 'commentsMy', 'commentVisibility', 'filter'));
    }

    /**
     * Display the documents for the specified source.
     *
     * @return \Illuminate\Http\Response
     */
    public function neptunManagment()
    {
        if (ViewHelper::universalHasPermission(array(6, 35)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        return view('simple-pages.neptunManagment');
    }

    /**
     * Contact form.
     *
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */

    /**
     * Contact form.
     *
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function contact()
    {
        //Dropdown: ALL Neptun - active - user - firstname lastname
        $data = array();
        $neptun = Mandant::find(1);
        $mandantUsers = MandantUser::where('mandant_id', $neptun->id)->pluck('user_id')->toArray();
        $users = User::whereIn('id', $mandantUsers)->where('active', 1)->orderBy('last_name', 'asc')->get();

        return view('contact', compact('data', 'users'));
    }

    /**
     * Contact form.
     *
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function contactSend(Request $request)
    {
        $this->validate($request, [
            'to_user' => 'required',
            'subject' => 'required|max:255',
            'summary' => 'required',
            // 'files[]' => 'max:4096',
        ]);

        $uid = Auth::user()->id;
        $copy = false;
        if ($request->has('copy')) {
            $copy = true;
        }
        $files = $request->file();
        $sizeLimit = 4096000;
        $request = $request->all();
        $from = User::find($uid);
        $toUser = User::find($request['to_user']);
        $request['logo'] = asset('/img/logo-neptun-new.png');
        $request['from'] = $from;
        $request['to'] = $toUser;

        $template = view('email.contact', compact('request'))->render();

        // Store message data
        $messageContact = ContactMessage::create(['user_id' => $request['to_user'], 'user_id_from' => $uid, 'title' => $request['subject'], 'message' => $request['summary'], 'send_copy' => $copy]);

        // Store message attachment files
        $uploads = ViewHelper::fileUpload(User::find($request['to_user']), $this->uploadPath, $files, $sizeLimit);

        if ($uploads === false) {
            return redirect()->back()->with(['message' => trans('contactForm.fileSizeExceeded'), 'alert-class' => 'alert-danger']);
        }
        // if($uploads == false) return redirect()->back()->with('alert-class', 'danger');

        // Store message attachment files data
        if (isset($messageContact) && isset($uploads)) {
            foreach ($uploads as $file) {
                MessageFile::create(['contact_message_id' => $messageContact->id, 'filename' => $file]);
            }
        }

        $sent = Mail::send([], [], function ($message) use ($template, $request, $from, $uploads) {
            $to = User::find($request['to_user']);
            $message->from($from->email, $from->first_name.' '.$from->last_name)
            ->to($to->email, $to->first_name.' '.$to->last_name)
            ->subject($request['subject'])
            ->setBody($template, 'text/html');
            foreach ($uploads as $file) {
                $message->attach($this->uploadPath.$to->id.'/'.$file);
            }
        });

        if ($copy == true) {
            $request['copy'] = 'yes';
            $request['subject'] = 'E-Mail Kopie "'.$request['subject'].'"';
            $template = view('email.contact', compact('request'))->render();
            $sent = Mail::send([], [], function ($message) use ($template, $request, $from, $uploads) {
                $to = User::find($request['to_user']);
                $message->from($from->email, $from->first_name.' '.$from->last_name)
                ->to($from->email, $from->first_name.' '.$from->last_name)
                ->subject($request['subject'])
                ->setBody($template, 'text/html');
                foreach ($uploads as $file) {
                    $message->attach($this->uploadPath.$to->id.'/'.$file);
                }
            });
        }

        return redirect()->back()->with('message', trans('contactForm.sendSuccess'));
    }

    /**
     * Contact messages overview.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactIndex()
    {
        if (!ViewHelper::universalHasPermission(array(6))) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }
        $userId = null;
        $messagesAll = ContactMessage::orderBy('created_at', 'desc')->get();
        $messagesAllPaginated = ContactMessage::orderBy('created_at', 'desc')->paginate(20, ['*'], 'seite');
        $usersAll = User::whereIn('id', array_unique(array_pluck($messagesAll, 'user_id')))->get();

        return view('kontakt.index', compact('messagesAll', 'messagesAllPaginated', 'usersAll', 'userId'));
    }

    /**
     * Contact messages overview.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactSearch(Request $request)
    {
        $userId = $request->get('user_id');
        if (empty($userId)) {
            return back()->with('message', 'Benutzer kann nicht gefunden werden.');
        }
        $messagesAll = ContactMessage::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $messagesAllPaginated = ContactMessage::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(20, ['*'], 'seite');
        $usersAll = User::whereIn('id', array_unique(array_pluck(ContactMessage::all(), 'user_id')))->get();

        return view('kontakt.index', compact('messagesAll', 'messagesAllPaginated', 'usersAll', 'userId'));
    }

    /**
     * Download document.
     *
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function download($partOne, $partTwo, $subDir = 'documents')
    {
        $file = public_path().'/files/'.$subDir.'/'.$partOne.'/'.$partTwo;

        return response()->download($file);
    }

    /**
     * Open document (PDF).
     *
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function open($partOne, $partTwo, $subDir = 'documents')
    {
        $file = File::get(public_path().'/files/'.$subDir.'/'.$partOne.'/'.$partTwo);

        return response($file, 200)->header('Content-Type', 'application/pdf');
    }
}
