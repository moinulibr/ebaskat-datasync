<?php

namespace App\Console\Commands\Tables;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminLogHistoriesChanges
{
    public function __construct()
    {
        $table_name  = 'admin_log_histories';
        $db_name = env('DB_DATABASE');

        $indexs = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = '$db_name' AND index_name != 'primary' AND TABLE_NAME = '$table_name';");

        $indexs_list = [];
        foreach($indexs as $item)
        {
            $indexs_list[] = $item->COLUMN_NAME;
        }
        
        Schema::table($table_name, function (Blueprint $table) use ( $table_name, $indexs_list) {

            // indexing column list
            $indexs_col = ['admin_user_id','activity_label','ip_address','mac_address', 'module_name'];
            foreach ($indexs_col as $col) {

                if(!in_array($col, $indexs_list))
                {
                    echo "\n<br/><h3> Adding index column $col in $table_name </h3>";
                    $table->index($col);
                }
            }
        });
    }
}
