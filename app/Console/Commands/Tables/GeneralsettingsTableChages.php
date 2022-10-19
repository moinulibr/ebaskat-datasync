<?php

namespace App\Console\Commands\Tables;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GeneralsettingsTableChages
{
    public function __construct()
    {
        $table_name  = 'generalsettings';

        // removing maintain_text
        if(Schema::hasColumn($table_name, 'maintain_text'))
        {
            echo "\n<br/><h3> Removing column maintain_text in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn('maintain_text');
            });
        }

    }
}
