<?php

namespace App\Jobs;

use App\Mail\CsvImportedMail;
use App\Products;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $method;
    public $timeout = 120;
    private $filePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($options)
    {
        $this->filePath = storage_path('app/csv/');
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->csv_import();
        } catch(\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function csv_import()
    {
        $this->scanDirectory($this->filePath . 'files/', function($csvFiles) {
            if (!count($csvFiles)) return;
            $date = (new \DateTime())->format('Y-m-d H:i');
            $log = [];
            $product = '';
            try {
                \DB::beginTransaction();
                foreach($csvFiles as $fileName => $dataInsert) {
                    foreach($dataInsert as $key => $data) {
                        $product = Products::create($data);
                        $log[] = [$fileName => $product];
                    }
                    $file = $this->filePath . "files/{$fileName}";
                        $importedFile = $this->filePath . "imported/{$fileName}";
                        if (file_exists($file)) {
                        \File::move($file, $importedFile);
//                        \Mail::to(User::first()->getAttribute('email'))
//                            ->send(new CsvImportedMail($log, $importedFile));
                        }
                        \DB::commit();
                }
            } catch(\Exception $exception) {
                $log = [$fileName => $product, 'error' => $exception->getMessage()];
                \DB::rollBack();
            }
            file_put_contents(storage_path("logs/import_csv-{$date}.log"), json_encode($log), FILE_APPEND);
        });
    }
    private function scanDirectory($path, $handler)
    {
        $csvfiles = [];
        if(is_dir($path)){
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if($file !== '.' && $file !== '..') {
                        $csvfiles[$file] = $this->getCSV($path . '/' . $file);
                    }
//                    echo "filename: $file : filetype: " . filetype($path . $file) . "\n";
                }
                closedir($dh);
            }
        }
        return $handler($csvfiles);
    }
    private function getCSV($filename, $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) return false;

        try {
            $arData   = [];
            if(($handle = fopen($filename, 'r')) !== false ) {
                $header = null;
                $arData = [];
                while(($data = fgetcsv($handle, 1000, $delimiter)) !== false ) {
                    if(!$header)
                        $header = $data;
                    else
                        $arData[] = array_combine($header, $data);
                }
            }
            return $arData;
        } catch(\Exception $e) {
            dd($e->getMessage());
        }
    }
}
