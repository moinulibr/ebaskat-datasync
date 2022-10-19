<?php

namespace App\Console\Commands\Datasync\Bigbuy;

use Illuminate\Console\Command;
use App\Traits\BigbuyProductUpdate;

class UpdateBigbuyProductTaskScheduleCommand extends Command
{
    use BigbuyProductUpdate;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:datasync-bigbuy-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data syncing Update Bigbuy Product';

    /**
     * Create a new command instance.
     *
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
        $this->updateProductProcessByQueue($jobNo = 1); //use App\Traits\Datasync\Bigbuy\BigbuyProductUpdateTrait;
    }
}
