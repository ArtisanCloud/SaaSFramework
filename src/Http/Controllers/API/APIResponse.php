<?php


namespace ArtisanCloud\SaaSFramework\Http\Controllers\API;

use ArtisanCloud\SaaSFramework\Models\ClientProfile;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class APIResponse implements Responsable
{
    private int $returnCode = API_RETURN_CODE_INIT;
    private string $returnMessage = '';

    private int $resultCode = 0;
    private string $resultMessage = '';

    private ?array $data = [];

    protected $m_module;

    public function __construct()
    {
        $this->m_returnCode = API_RETURN_CODE_INIT;
        $this->m_module = 'messages';
    }

    public function setLocaleModule($module = 'messages')
    {
        $this->m_module = $module;
    }

    public static function success($data = null): string
    {
        $response = new self();
        $response->setReturnCode(API_RETURN_CODE_INIT);
        $response->setReturnMessage(trans("messages." . API_RETURN_CODE_INIT));
        $response->setResultCode(API_RESULT_CODE_INIT);
        $response->setResultMessage(trans("messages." . API_RESULT_CODE_INIT));
        $response->setData($data);

        return $response->toJson();
    }

    public static function error($resultCode, $resultMessage = "", $returnMessage = null): string
    {
        $response = new self();
        $response->setReturnCode(API_RETURN_CODE_ERROR);
        $response->setReturnMessage(trans("messages." . API_RETURN_CODE_ERROR));
        $response->setResultCode($resultCode);

        // given return message
        if (!empty($returnMessage)) {
            $response->setReturnMessage($returnMessage);
        }

        // given result message
        if (empty($resultMessage)) {
            $response->setResultMessage(trans("messages." . $resultCode));
        } else {
            $response->setResultMessage($resultMessage);
        }
        return $response->toJson();
    }

    /**
     * Is no error.
     *
     * @return bool
     */
    public function isNoError(): bool
    {

        return $this->returnCode == API_RETURN_CODE_INIT;
    }

    /**
     * Set codes.
     *
     * @param int $iReturnCode
     * @param int $iReturnCode
     * @param string $returnMSG
     * @param string $resultMSG
     *
     * @return void
     */
    public function setCode(int $iResultCode, int $iReturnCode = API_RETURN_CODE_ERROR, string $returnMSG = '', string $resultMSG = ''): void
    {
        $this->setReturnCode($iReturnCode);
        $this->setReturnMessage($returnMSG);

        $this->setResultCode($iResultCode);
        $this->setResultMessage($resultMSG);
    }

    /**
     * @return mixed
     */
    public function getReturnCode(): int
    {
        return $this->returnCode;
    }

    /**
     * @param int $returnCode
     */
    public function setReturnCode(int $returnCode): APIResponse
    {
        $this->returnCode = $returnCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }

    /**
     * @param string $returnMessage
     */
    public function setReturnMessage(string $returnMessage = ''): APIResponse
    {
        $this->returnMessage = $this->returnMessage != "" ? $this->returnMessage : $this->getLocaleMessage($this->returnCode);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultCode(): int
    {
        return $this->resultCode;
    }

    /**
     * @param int $resultCode
     */
    public function setResultCode(int $resultCode): APIResponse
    {
        $this->resultCode = $resultCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultMessage(): string
    {
        return $this->resultMessage;
    }

    /**
     * @param string $resultMessage
     */
    public function setResultMessage(string $resultMessage=''): APIResponse
    {
        $this->resultMessage = $this->resultMessage != "" ? $this->resultMessage : $this->getLocaleMessage($this->resultCode);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): APIResponse
    {
        $this->data = $data;
        return $this;
    }

    public function toJson(): JsonResponse
    {
        $response = [
            'meta' => [
                'return_code' => $this->returnCode,
                'return_message' => $this->returnMessage,
                'result_code' => $this->resultCode,
                'result_message' => $this->resultMessage,
                'timezone' => ClientProfile::TIMEZONE,
                'locale' => ClientProfile::getSessionLocale(),
            ],
        ];
        if (!is_null($this->data)) {
            $response['data'] = $this->data;
        }
        return response()->json($response);
    }


    /**
     * Get locale message.
     *
     * @param string $code
     *
     * @return string $strMessage
     */
    protected function getLocaleMessage($code = '')
    {

//        dd($locale);

        $module = $this->m_module ?? 'messages';
        $strMessage = __("{$module}.{$code}");
//        dump($code,$strMessage);

        return $strMessage;
    }

    /**
     * @inheritDoc
     */
    public function toResponse($request): string
    {
        return $this->toJson();
    }

    /**
     * Throw json response.
     *
     * @param null
     * @return Response response
     */
    public function throwJSONResponse(): void
    {
        header('Content-Type: application/json');
        echo $this->toResponse()->content();
        exit();
    }
}
