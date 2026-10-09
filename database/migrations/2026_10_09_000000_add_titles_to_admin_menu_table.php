<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitlesToAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection');
        $menuTable = config('admin.database.menu_table') ?: 'admin_menu';
        $schema = Schema::connection($connection);

        if (!$schema->hasTable($menuTable) || $schema->hasColumn($menuTable, 'titles')) {
            return;
        }

        $schema->table($menuTable, function (Blueprint $table) {
            $table->json('titles')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('admin.database.connection');
        $menuTable = config('admin.database.menu_table') ?: 'admin_menu';
        $schema = Schema::connection($connection);

        if (!$schema->hasTable($menuTable) || !$schema->hasColumn($menuTable, 'titles')) {
            return;
        }

        $schema->table($menuTable, function (Blueprint $table) {
            $table->dropColumn('titles');
        });
    }
}
