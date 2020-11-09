<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Address extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->integer('zip')->default('0')->unsigned()->comment('郵遞區號');
            $table->string('city', 32)->default('')->comment('城市');
            $table->string('area', 32)->default('')->comment('區/鄉/鎮');
            $table->string('road', 32)->default('')->comment('路/街');
            $table->integer('lane')->default('0')->unsigned()->comment('巷');
            $table->integer('alley')->default('0')->unsigned()->comment('弄');
            $table->string('no', 32)->default('')->comment('號');
            $table->integer('floor')->default('0')->unsigned()->comment('樓');
            $table->string('address', 255)->default('')->comment('其他資訊');
            $table->string('filename', 8)->default('')->comment('Address 檔案');
            $table->decimal('latitude')->default('0.0')->unsigned();
            $table->decimal('lontitue')->default('0.0')->unsigned();
            $table->string('full_address', 255)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
