<?php

namespace App\Console\Commands;

use App\Services\ImportFileService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportFileData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:articles {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Articles to DB from formatted sheet File inside storage/app/public/files directory';

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
     * @return int
     */
    public function handle()
    {
        $file = storage_path('app/public/files/' . $this->argument('file'));

        if(!File::exists($file))
        {
            return $this->error('File is not exist');
        }

        $this->line('Process data in provided file');

        try {
            Excel::import(new ImportFileService, $file);
        } 
        catch(ValidationException $e) 
        {   
            foreach($e->failures() as $failure) 
            {
                /*
                    $failure->row(); // row that went wrong
                    $failure->attribute(); // either heading key (if using heading row concern) or column index
                    $failure->errors(); // Actual error messages from Laravel validator
                    $failure->values(); // The values of the row that has failed.
                */
                if(!empty($failure->errors()))
                {
                    foreach($failure->errors() as $error) {
                        $this->error($error . ' on row ' . $failure->row());
                    }
                }
            }

            return $this->info('Failed to import file');
        }

        $this->info('File imported Successfully');
    }
}
