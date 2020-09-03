<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService;


use ArtisanCloud\SaaSFramework\Exceptions\BaseException;
use App\Exceptions\SendVerifyCodeException;
use App\Exceptions\SendVerifyCodeTooManyTimesException;

use ArtisanCloud\SaaSFramework\Models\ClientProfile;
use App\Models\User;
use ArtisanCloud\SaaSFramework\Services\CodeService\Models\VerifyCode;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\CodeGenerator;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Driver;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Sendable;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\CodeServiceContract;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class QRCodeService implements CodeServiceContract
{
    /**
     * @var Driver
     */
    protected $driver;

    /**
     * @var Channel
     */
    private $channel;

    /**
     * throttle time
     * @var int
     */
    protected $throttles = 60;

    public function __construct(Driver $driver, Channel $channel)
    {
        $this->driver = $driver;
        $this->channel = $channel;
    }

    /**
     * @param Driver $driver
     * @return $this
     */
    public function setDriver(Driver $driver)
    {
        $this->driver = $driver;
        return $this;
    }

    public function setThrottles($throttles)
    {
        $this->throttles = $throttles;
        return $this;
    }

    /**
     * @param Channel $channel
     * @return $this
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * Verify verify code
     * @param Sendable $sendable
     * @param $code
     * @return bool
     */
    function verify(Sendable $sendable, $code, $label = '')
    {
        $realCode = $this->driver->getVerifyCode($this->channel, $sendable, $label);

        return $realCode == $code;
    }

    /**
     * Verify verify code
     * @param Sendable $sendable
     * @param $code
     * @return User
     */
    function verifyQR($code, $label = '')
    {
        $mobile = $this->driver->readQRCode($code, $label);
//        Log::info('verify mobile:'.$mobile);

        return User::getByPhone($mobile);

    }

    /**
     * @param CodeGenerator $codeGenerator
     * @param Sendable $sendable
     * @param string $label
     * @param array $options
     * @return mixed
     * @throws SendVerifyCodeTooManyTimesException
     */
    function sendVerifyCode(CodeGenerator $codeGenerator, Sendable $sendable, $label = '', $expires = 300, array $options = [])
    {
        // 频率限制
        if (!$this->driver->canSend($this->throttles, $this->channel, $sendable, $label)) {
//            throw new SendVerifyCodeTooManyTimesException();
            throw new BaseException(API_ERR_CODE_VERIFY_CODE_REQUEST_DUPLICATED, null);
        }

        $result = null;
        try {
            DB::transaction(function () use ($expires, $options, $label, $sendable, $codeGenerator, &$result) {
                // 生成验证码
                $code = $this->generateVerifyCode($codeGenerator, $sendable, $label, $expires, $options);
                if(!$code){
                    throw new \Exception(API_ERR_CODE_FAIL_TO_CREATE_VERIFY_CODE);
                }

                // 发送验证码
                $result = $this->channel->send($sendable, $code, $options);
                if(!$result){
                    throw new BaseException(API_ERR_CODE_FAIL_TO_SEND_VERIFY_CODE, null, $e);
                }

            });
        } catch (\Throwable $e) {
            throw new BaseException($e->getCode(), null, $e);
        }

        return $result;
    }

    function generateVerifyCode(CodeGenerator $codeGenerator, Sendable $sendable, $label = '', $expires = 300, array $options = [])
    {
        // 生成验证码
        $code = $codeGenerator->getCode($options);
        // 储存验证码
        if(!$this->driver->setVerifyCode($code, $expires, $this->channel, $sendable, $label)){
            return false;
        }

        return $code;
    }

    function getSendable($code, $label = '')
    {
        return $this->driver->getSendable($code, $label);
    }


}
