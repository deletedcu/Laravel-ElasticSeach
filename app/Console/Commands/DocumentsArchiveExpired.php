<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Document;
use Illuminate\Console\Command;

class DocumentsArchiveExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:archive-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets document status to "Archiv" for documents whose date of expiration is due.';

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
        $documents = Document::whereNotNull('date_expired')->get();
        foreach ($documents as $document) {
            if(Carbon::parse($document->date_expired)->lt(Carbon::today())){
                $document->update(['document_status_id' => 5]);
            }
        }
    }
}
