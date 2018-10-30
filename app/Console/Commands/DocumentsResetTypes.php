<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\DocumentType;

class DocumentsResetTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:reset-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Document Types sorting and positions reset.';

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
        $docTypes = DocumentType::all();
        for ($i = 0; $i < sizeof($docTypes); $i++) {
            $type = $docTypes[$i];
            $type->update(['order_number' => ($i+1), 'menu_position' => 1]);
        }
    }
}
