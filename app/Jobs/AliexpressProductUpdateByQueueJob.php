<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\BigbuyProductUpdate;

class AliexpressProductUpdateByQueueJob implements ShouldQueue
{
    use BigbuyProductUpdate;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $primaryData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($primaryData)
    {
        $this->primaryData = $primaryData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', 28800);
        $primaryChunks = array_chunk($this->primaryData,10);//25
        foreach($primaryChunks as $primaryChunkSingleData)
        {
            AliexpressProductUpdateBySecondaryQueueJob::dispatch($primaryChunkSingleData)->onQueue('ali-pro-updt2')->delay(now()->addSeconds(2));
        } 
        return true;
    }
}
