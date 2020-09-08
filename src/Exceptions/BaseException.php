<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Exceptions;


use ArtisanCloud\SaaSFramework\Http\Controllers\API\APIResponse;
use ArtisanCloud\SaaSFramework\Models\ClientProfile;
use Exception;
use Throwable;

class BaseException extends Exception
{
    public function __construct($code = 0, $message = "", Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
//        dump('base report:');
    }

    public function render($request)
    {
        return APIResponse::error($this->getResultCode());
    }


    function getResultCode()
    {
        return $this->getCode();
    }


}
