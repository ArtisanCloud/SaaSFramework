<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services\LandlordService\src;

use ArtisanCloud\SaaSFramework\Services\ArtisanCloudService;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\Contracts\LandlordServiceContract;
use ArtisanCloud\SaaSFramework\Services\LandlordService\src\Models\Landlord;


/**
 * Class LandlordService
 * @package ArtisanCloud\SaaSFramework\Services\LandlordService\src
 */
class LandlordService extends ArtisanCloudService implements LandlordServiceContract
{
    const TAG_NAME = 'landlord';

    //
    public function __construct()
    {
        parent::__construct();
        $this->m_model = new Landlord();
    }


    public static function setSessionLandlord($landlord){
        session(['landlord' => $landlord]);
    }

    /**
     * return current session auth user
     *
     * @return Landlord
     */
    public static function getSessionLandlord(): ?Landlord
    {
        return session('landlord');
    }
    




}
