<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyTables extends Migration
{
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->integer('num_code')->unsigned();
            $table->char('char_code', 3);
            $table->integer('nominal')->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->unique('num_code');
            $table->unique(['char_code', 'nominal']);
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('currency_id')->unsigned()->index();
            $table->date('date');
            $table->decimal('rate', 10, 4, true);
            $table->timestamps();

            $table->unique(['currency_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency_rates');
        Schema::dropIfExists('currencies');
    }
}
