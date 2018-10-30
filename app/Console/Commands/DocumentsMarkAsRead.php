<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\User;
use App\Document;
use App\UserReadDocument;

class DocumentsMarkAsRead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:mark-read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks all documents as read for users present at the time of execution.';

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
        $documents = Document::all();
        $users = User::all();
        
        foreach($users as $user){
            foreach($documents as $document){
                
                $readDocs = UserReadDocument::where('document_group_id', $document->document_group_id)
                            ->where('user_id', $user->id)->get();
                        
                if(count($readDocs) == 0){
                    UserReadDocument::create([
                        'document_group_id'=> $document->document_group_id, 
                        'user_id'=> $user->id, 
                        'date_read'=> Carbon::now(), 
                        'date_read_last'=> Carbon::now()
                    ]);
                }
                
            }
        }
    }
}
