<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\BigbuyProductImportByQueue;

class BigbuyProductImportableBySecondaryJob implements ShouldQueue
{
    use BigbuyProductImportByQueue;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $secondaryData;
    /**
     * Create a new job instance.
     *
     */
    public function __construct($secondaryData)
    {
        $this->secondaryData = $secondaryData;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        ini_set('max_execution_time', 28800);
        foreach($this->secondaryData as $da_productId)
        {
            $this->importableProductByProductId($da_productId);
        }
        return true;
    }
}
