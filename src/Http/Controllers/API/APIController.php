<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Http\Controllers\API;

use App\Models\User;
use App\Services\UserService\UserService;
use ArtisanCloud\SaaSFramework\Services\ArtisanCloudService;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\Models\Landlord;
use ArtisanCloud\SaaSPolymer\Services\ArtisanService\src\Models\Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class APIController extends Controller
{

    const DATE_FORMAT = "Y-m-d";
    const DATE_TIME_FORMAT = "Y-m-d H:i:s";
    const DATE_TIME_ISO_FORMAT = "Y-m-d\TH:i:s.u\Z";
    const TIME_FORMAT = "H:i:s";

    public APIResponse $m_apiResponse;


    /**
     * Constructor.
     *
     * @param Request $request
     *
     */
    public function __construct(Request $request)
    {
        $this->m_apiResponse = new APIResponse();

    }
    


}
