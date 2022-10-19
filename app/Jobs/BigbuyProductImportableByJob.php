<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\BigbuyProductUpdate;

class BigbuyProductImportableByJob implements ShouldQueue
{
    use BigbuyProductUpdate;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $primaryData;
    /**
     * Create a new job instance.
     *
     */
    public function __construct($primaryData)
    {
        $this->primaryData = $primaryData;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        ini_set('max_execution_time', 28800);
        $primaryChunks = array_chunk($this->primaryData,10);
        foreach($primaryChunks as $primaryChunkSingleData)
        {
            BigbuyProductImportableBySecondaryJob::dispatch($primaryChunkSingleData)->onQueue('dsync-big-prodt-import-part-two-job')->delay(now()->addSeconds(1));//->delay(now()->addSeconds(10))//->delay(now()->addMinutes(1))
        }
        return true;
    }
}
