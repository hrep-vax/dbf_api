<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChecksRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    //
    Schema::create('checks_records', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('hrep_id');
      $table->string('check_no');
      $table->decimal('amount', 12, 2);
      $table->date('check_date');
      $table->text('description');
      $table->timestamp('release_date');
      $table->unsignedBigInteger('received_by');
      $table->unsignedBigInteger('released_by');
      $table->boolean('is_claimed')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('checks_records');
  }
}
