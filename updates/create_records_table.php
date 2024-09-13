<?php

namespace SpAnjaan\Forms\Updates;

use Winter\Storm\Support\Facades\Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('spanjaan_forms_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('group')->default('None');
            $table->text('form_data')->nullable();
            $table->string('ip')->nullable();
            $table->boolean('unread')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spanjaan_forms_records');
    }
}
