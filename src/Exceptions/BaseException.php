<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Exceptions;


use ArtisanCloud\SaaSFramework\Http\Controllers\API\APIResponse;
use ArtisanCloud\SaaSFramework\Models\ClientProfile;
use Exception;
use Throwable;

class BaseException extends Exception
{
    protected ?string $messageNamespace = null;
    protected APIResponse $apiResponse;

    public function __construct(int $code = 0, string $message = "", $messageNamespace = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->messageNamespace = $messageNamespace;
        $this->apiResponse = new APIResponse();
    }

    public function report()
    {
//        dump('base report:');
    }

    public function render($request)
    {
//        dd($this->getResultCode(), $this->getResultMessage());
        $this->apiResponse->setLocaleNamespace($this->messageNamespace);
        $this->apiResponse->setCode(
            $this->getResultCode(),
            API_RETURN_CODE_ERROR,
            '',
            $this->getResultMessage());

        return $this->apiResponse->toResponse();
    }


    function getResultCode()
    {
        return $this->getCode();
    }

    function getResultMessage()
    {
        return $this->getMessage();
    }

    public function setMessageNamespace($namespace = null)
    {
        $this->messageNamespace = $namespace;
    }


}
