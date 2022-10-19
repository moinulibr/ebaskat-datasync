<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\BigbuyProductUpdate;

class BigbuyProductUpdateBySecondaryJob implements ShouldQueue
{
    use BigbuyProductUpdate;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $secondaryData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($secondaryData)
    {
        $this->secondaryData = $secondaryData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', 28800);
        foreach($this->secondaryData as $da_productId)
        {
            $this->productId = $da_productId;
            $this->updateProductByProductId();
        }
        return true;
    }
}
