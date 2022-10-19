<?php

namespace App\Console\Commands\Tables;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminUserMessagesTable
{
    public function __construct()
    {
        $table_name  = 'admin_user_messages';

        // adding column : files
        if(!Schema::hasColumn($table_name, 'files'))
        {
            echo "\n<br/><h3> Adding column files in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) use ($table_name){
                $table->text('files')->nullable()->after('user_id');
            });
        }
        
        $db_name = env('DB_DATABASE');
        $indexs = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = '$db_name' AND index_name != 'primary' AND TABLE_NAME = '$table_name';");

        $indexs_list = [];
        foreach($indexs as $item)
        {
            $indexs_list[] = $item->COLUMN_NAME;
        }

        Schema::table($table_name, function (Blueprint $table) use ( $table_name, $indexs_list) {

            // indexing column list
            $indexs_col = ['conversation_id', 'user_id'];
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
