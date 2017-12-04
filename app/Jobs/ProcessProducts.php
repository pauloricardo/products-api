<?php

namespace App\Jobs;

use App\Products;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $folder = "";
    private $allCsvFiles = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->folder = storage_path('csv/files');
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->openFolder(function($aCsv) {
            foreach($aCsv as $file => $csvLines) {
                foreach($csvLines as $line) {
                    $product = Products::create($line);
                }
            }
        });
    }

    private function openFolder($cb) {
        if(is_dir($this->folder)) {
            $mainFolder = opendir($this->folder);
            while(($file = readdir($mainFolder)) !== false) {
                $this->allCsvFiles[$file] = $this->openFile($this->folder, $file);
            }
        }
        return $cb($this->allCsvFiles);
    }
    private function openFile($dir, $file) {
        $handle = fopen($dir . '/' . $file, "r");
        $data = [];
        if($handle) {
            while(($product = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($product);
                for($i = 0 ; $i < $num ; $i++) {
                    $data[] = $product[$i];
                }
            }
            fclose($handle);
            return $data;
        } else {
            return [];
        }
    }
}
