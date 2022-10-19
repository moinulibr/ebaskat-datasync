<?php

namespace App\Console\Commands\Tables;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersTableChanges
{
    public function __construct()
    {
        $table_name  = 'users';

        // adding column : deleted_at
        if(!Schema::hasColumn($table_name, 'deleted_at'))
        {
            echo "\n<br/><h3> Adding column deleted_at in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable()->after('updated_at');
            });
        }
    }
}
