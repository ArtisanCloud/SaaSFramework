<?php

namespace ArtisanCloud\SaaSFramework\Services\TenantService\database\migrations;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantMigration extends Migration
{
    protected $schema = null;

    protected $table = '';

    function __construct()
    {
        $this->schema = Schema::connection('tenant')->getConnection()->getSchemaBuilder();
//        dd($this->schema);
//        if (app()->environment() != 'local') {
//            $searchPath = config('database.connections.pgsql.search_path');
//            \DB::connection('tenant')->statement("SET search_path TO {$searchPath}");
//        }

    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        dump($this->table);
        $this->schema->dropIfExists($this->table);
//        if (app()->environment('local')) {
//            $this->schema->dropIfExists($this->table);
//        }
    }
}
