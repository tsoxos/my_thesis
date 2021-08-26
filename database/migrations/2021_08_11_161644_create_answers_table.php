<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("gender_id");
            $table->bigInteger("age_group_id");
            $table->bigInteger('questionnaire_id');
            $table->bigInteger('token_id');
            $table->longText('answers')->nullable();
            $table->timestamps();
            $table->unique(['questionnaire_id', 'token_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
