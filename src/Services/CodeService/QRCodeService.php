<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService;


use ArtisanCloud\SaaSFramework\Exceptions\BaseException;
use App\Exceptions\SendCodeException;
use App\Exceptions\SendCodeTooManyTimesException;

use ArtisanCloud\SaaSFramework\Models\ClientProfile;
use App\Models\User;
use ArtisanCloud\SaaSFramework\Services\CodeService\Models\Code;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Channel;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\CodeGenerator;
use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\Driver;

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
     * @param string $to
     * @param $code
     * @return bool
     */
    function verify(string $to, $code, $label = '')
    {
        $realCode = $this->driver->getCode($this->channel, $to, $label);

        return $realCode == $code;
    }

    /**
     * Verify verify code
     * @param string $to
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
     * @param string $to
     * @param string $label
     * @param array $options
     * @return mixed
     * @throws SendCodeTooManyTimesException
     */
    function sendCode(CodeGenerator $codeGenerator, string $to, $label = '', $expires = 300, array $options = [])
    {
        // 频率限制
        if (!$this->driver->canSend($this->throttles, $this->channel, $to, $label)) {
//            throw new SendCodeTooManyTimesException();
            throw new BaseException(API_ERR_CODE_VERIFY_CODE_REQUEST_DUPLICATED, null);
        }

        $result = null;
        try {
            DB::transaction(function () use ($expires, $options, $label, $to, $codeGenerator, &$result) {
                // 生成验证码
                $code = $this->generateCode($codeGenerator, $to, $label, $expires, $options);
                if(!$code){
                    throw new \Exception(API_ERR_CODE_FAIL_TO_CREATE_VERIFY_CODE);
                }

                // 发送验证码
                $result = $this->channel->send($to, $code, $options);
                if(!$result){
                    throw new BaseException(API_ERR_CODE_FAIL_TO_SEND_VERIFY_CODE, null, $e);
                }

            });
        } catch (\Throwable $e) {
            throw new BaseException($e->getCode(), null, $e);
        }

        return $result;
    }

    function generateCode(CodeGenerator $codeGenerator, string $to, $label = '', $expires = 300, array $options = [])
    {
        // 生成验证码
        $code = $codeGenerator->getCode($options);
        // 储存验证码
        if(!$this->driver->setCode($code, $expires, $this->channel, $to, $label)){
            return false;
        }

        return $code;
    }

    function getTo($code, $label = '')
    {
        return $this->driver->getTo($code, $label);
    }


}
