<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Console\Commands\Schema;

use ArtisanCloud\SaaSFramework\Jobs\IterateSchema;
use ArtisanCloud\SaaSMonomer\Services\TenantService\src\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class Iterate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema-iterate:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'schema iterate:run';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
//        dd($this->arguments());
//        dd($this->options());

//        Artisan::call('passport:install');
        $this->info('command iterate schema...');
//        DB::table('tenants')
        Tenant::orderBy('uuid')
            ->chunk(100, function($tenants)
        {
//            dd($tenants);
            foreach ($tenants as $tenant)
            {
                //
//                dd($tenant);
                $this->info("process tenant: {$tenant->host}");

                $dispatch = IterateSchema::dispatch($tenant)
                    ->onConnection('redis-schema-iteration')
                    ->onQueue('schema-iteration');

            }
        });

        return 0;
    }



}
