<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\BigbuyProductUpdate;

class BigbuyProductUpdateByJob implements ShouldQueue
{
    use BigbuyProductUpdate;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $primaryData;
    public $jobNo;
    /**
     * Create a new job instance.
     *
     */
    public function __construct($primaryData,$jobNo)
    {
        $this->primaryData = $primaryData;
        $this->jobNo = $jobNo;
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
            BigbuyProductUpdateBySecondaryJob::dispatch($primaryChunkSingleData)->onQueue('dsync-big-prodt-updt-part-two-job'.$this->jobNo)->delay(now()->addSeconds(1));//->delay(now()->addSeconds(10))//->delay(now()->addMinutes(1))
        }
        return true;
    }
}
