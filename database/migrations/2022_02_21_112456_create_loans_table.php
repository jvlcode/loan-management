<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ref_no');
            $table->unsignedBigInteger('loan_type_id');
            $table->decimal('amount');
            $table->string('purpose');
            $table->boolean('is_settled')->default(0);
            $table->unsignedBigInteger('loan_plan_id');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=approved,2=rejected');
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('loans');
    }
}
