<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->string('table_number')->change();
        });
    }

    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->unsignedInteger('table_number')->change();
        });
    }
};
