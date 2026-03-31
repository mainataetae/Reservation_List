<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->comment('この予約管理表を所有する院名');
            $table->foreign('store_id')->references('id')->on('users');
            $table->string('customer_name',128)->comment('患者様名');
            $table->integer('status')->default(0)->comment('患者様の状態');
            $table->string('staff_name',128)->comment('担当スタッフ名');
            $table->date('reservation_date')->comment('予約日');
            $table->string('reservation_time')->comment('予約時間');
            $table->text('memo')->nullable()->comment('メモ');
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->collation = 'utf8mb4_bin';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
