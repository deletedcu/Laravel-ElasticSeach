<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Mail;
use Illuminate\Console\Command;

use App\User;
use App\Document;
use App\DocumentApproval;


class DocumentsNotifyApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:notify-approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notification emails to users who approve (Freigabe) documents.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $approvals = DocumentApproval::whereNull('date_approved')->get();
    
        foreach($approvals as $approval){
            
            // Get user and document data 
            $user = User::find($approval->user_id);
            $document = Document::find($approval->document_id);
            
            // Set document as approved if the document status is current (Aktuell)
            if($document->document_status_id != 6){
                $approval->date_approved = Carbon::now();
                $approval->save();
                continue; // Skip to the next item in loop
            }
            
            // Fill the email container class with adequate values
            $mailContent = new \StdClass();
            $mailContent->subject = 'Benachrichtigung über eine Dokumentfreigabe im Intranet: "'. $document->name .'"';
            $mailContent->title = 'Benachrichtigung über eine Dokumentfreigabe im Intranet: "'. $document->name .'"';
            $mailContent->fromEmail = 'info@neptun-gmbh.de';
            $mailContent->fromName = 'Informationsservice';
            $mailContent->link = url('dokumente/' . $document->id . '/freigabe');
                
            // Send email
            $mailContent->toEmail = $user->email;
            $mailContent->toName = $user->first_name .' '. $user->last_name;
            $sent = Mail::send('email.notifyApproval', ['content' => $mailContent, 'user' => $user, 'document' => $document], 
                function ($message) use ($mailContent, $document) {
                    $message->from($mailContent->fromEmail, $mailContent->fromName);
                    $message->to($mailContent->toEmail, $mailContent->toName);
                    $message->subject($mailContent->subject);
            });
             
        }
        
    }
}
