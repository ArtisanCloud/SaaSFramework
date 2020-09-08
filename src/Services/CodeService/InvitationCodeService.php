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

class InvitationCodeService implements CodeServiceContract
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
     * @param CodeGenerator $codeGenerator
     * @param string $to
     * @param int $type
     * @param int $expires
     * @param array $options
     *
     * @return $this
     */
    function generateCode(CodeGenerator $codeGenerator, string $to, $type = '', $expires = 300, array $options = [])
    {
        // 生成邀请码
        $code = $codeGenerator->getCode($options);
        // 储存验证码
        if (!$this->driver->setCode($code, $expires, $this->channel, $to, $type)) {
            return false;
        }

        return $code;
    }


    /**
     * @param CodeGenerator $codeGenerator
     * @param string $to
     * @param string $type
     * @param array $options
     * @return bool
     * @throws SendCodeTooManyTimesException
     */
    function sendCode(CodeGenerator $codeGenerator, string $to, $type = '', $expires = 300, array $options = []) : bool
    {
        // 频率限制
        if (!$this->driver->canSend($this->throttles, $this->channel, $to, $type)) {
//            throw new SendCodeTooManyTimesException();
            throw new BaseException(API_ERR_CODE_VERIFY_CODE_REQUEST_DUPLICATED, null);
        }

        $result = false;
        try {
            $result = DB::transaction(function () use ($expires, $options, $type, $to, $codeGenerator) {
                // 生成验证码
                $code = $this->generateCode($codeGenerator, $to, $type, $expires, $options);
                if (!$code) {
                    throw new \Exception(API_ERR_CODE_FAIL_TO_CREATE_VERIFY_CODE);
                }

                // 发送验证码
                $result = $this->channel->send($to, $code, $options);
                if (!$result) {
                    throw new \Exception(API_ERR_CODE_FAIL_TO_SEND_VERIFY_CODE);
                }
                return $result;
            });
        } catch (\Throwable $e) {
//            dd($e);
            throw new BaseException($e->getCode(), null, $e);
        }

        return $result;
    }


    /**
     * Verify verify code
     * @param string $to
     * @param $code
     * @return bool
     */
    function verify(string $to, $code, $type = '')
    {
        $realCode = $this->driver->getCode($to, $type);
        return $realCode == $code;
    }


    function getTo($code, $type = '')
    {
        return $this->driver->getTo($code, $type);
    }

    public function batchGenerateCode(CodeGenerator $codeGenerator, int $count = 50)
    {
        foreach ($n as $item) {
            $this->generateCode($generator, null, );
        }
    }

}
