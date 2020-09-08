<?php
declare(strict_types=1);

use ArtisanCloud\SaaSFramework\Models\ArtisanCloudModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->string('name');

            $table->tinyInteger('status')->default(ArtisanCloudModel::STATUS_INIT);
            
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
        Schema::dropIfExists('lands');
    }
}