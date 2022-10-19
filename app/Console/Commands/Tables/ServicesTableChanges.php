<?php

namespace App\Console\Commands\Tables;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServicesTableChanges
{
    public function __construct()
    {
        $table_name  = 'services';

        // adding column : deleted_at
        if(!Schema::hasColumn($table_name, 'deleted_at'))
        {
            echo "\n<br/><h3> Adding column deleted_at in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) use ($table_name){
                if(Schema::hasColumn($table_name, 'updated_at'))
                {
                    $table->timestamp('deleted_at')->nullable()->after('updated_at');
                }
                else
                {
                    $table->timestamp('deleted_at')->nullable();
                }
            });
        }
    }
}
