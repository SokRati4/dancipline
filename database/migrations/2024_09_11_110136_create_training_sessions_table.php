<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('system_id');
            $table->unsignedInteger('note_id')->nullable();
            $table->enum('type', ['individual', 'group', 'stamina', 'self']);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->decimal('duration_hours', 5, 2);
            $table->unsignedInteger('intensity');
            $table->enum('style', ['ST', 'LA', '10 dances', 'Smooth']);
            $table->text('dances_planned');
            $table->text('dances_performed')->nullable();
            $table->boolean('five_dances')->default(0);
            $table->boolean('with_partner')->default(0);
            $table->boolean('started')->default(0);
            $table->dateTime('start_confirmed_at')->nullable();
            $table->boolean('completed')->default(0);
            $table->dateTime('end_confirmed_at')->nullable();
            $table->timestamps();

            // Indexes and foreign keys
            $table->foreign('note_id')->references('id')->on('training_notes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_sessions');
    }
}