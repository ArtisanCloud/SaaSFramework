<?php

namespace ArtisanCloud\SaaSFramework\Jobs;

use ArtisanCloud\SaaSFramework\Exceptions\BaseException;

use ArtisanCloud\SaaSMonomer\Services\TenantService\src\Models\Tenant;
use ArtisanCloud\SaaSMonomer\Services\TenantService\src\Models\TenantModel;
use ArtisanCloud\SaaSMonomer\Services\TenantService\src\TenantService;

use ArtisanCloud\UBT\Facades\UBT;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Throwable;

class IterateSchema implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public bool $isStandalone;
    public TenantService $tenantService;

    /**
     * Create a new job instance.
     *
     * @param Tenant $tenant
     *
     * @return void
     */
    public function __construct(Tenant $tenant, bool $isStandalone = false)
    {
        //
        $this->isStandalone = $isStandalone;

        $this->tenantService = resolve(TenantService::class);
        $this->tenantService->setModel($tenant);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tenant = $this->tenantService->getModel();
//        dd($tenant);
        // check tenant exists
        if (is_null($tenant)) {
            UBT::info('Tenant is null');
            return;
        }

        // log start
        UBT::info('Job handle iterate schema: ', [
            'tenant' => $tenant->uuid,
            'subdomain' => $tenant->subdomain,
        ]);

        // set tenant connection
        $this->tenantService->setConnection($tenant);

        // run the schema iteration sql
        $connection = DB::connection(TenantModel::getConnectionNameStatic());
        $result = $connection->transaction(function () use ($connection){
            $result = false;
            try {
                $result = $connection->unprepared(file_get_contents(database_path('migrations/tenants/iterations/run.sql')));

            } catch (Throwable $e) {
//                dd($e);
                report($e);
            }

            return $result;

        });

        // log start
        UBT::info('Job iterate schema result:' . $result, [
            'tenant' => $tenant->uuid,
            'subdomain' => $tenant->subdomain,
        ]);



    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        // Send user notification of failure, etc...
        UBT::sendError($exception);
    }

}
