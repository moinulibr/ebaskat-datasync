<?php

namespace App\Console\Commands\Tables;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminsTableChanges
{
    public function __construct()
    {
        $table_name  = 'admins';
        $db_name = env('DB_DATABASE');

        // adding column : deleted_at
        if (!Schema::hasColumn($table_name, 'deleted_at')) {
            echo "\n<br/><h3> Adding column deleted_at in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable()->after('updated_at');
            });
        }

        // adding column : token
        if (!Schema::hasColumn($table_name, 'token')) {
            echo "\n<br/><h3> Adding column token in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->string('token', 255)->nullable()->after('remember_token');
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
            $indexs_col = ['role_id','token'];
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
