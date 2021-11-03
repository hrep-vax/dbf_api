<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('checks', function (Blueprint $table) {
      //$table->id();
      //$table->timestamps();
      $table->string('code');
      $table->string('check');
      $table->decimal('amount');
      $table->timestamp('che_date');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('checks');
  }
}
