<?php
declare(strict_types=1);


namespace App\Services\CodeService\Testing;


use App\Services\CodeService\Contracts\Channel;
use App\Services\CodeService\Contracts\CodeGenerator;
use App\Services\CodeService\Contracts\Driver;

use App\Services\CodeService\Contracts\CodeService;

class CodeServiceFake implements CodeService
{
    public static $verifycodes = [];

    private $driver;

    private $channel;

    private $throttles;

    public function __construct(Driver $driver, Channel $channel)
    {
        $this->driver = $driver;
        $this->channel = $channel;
    }

    /**
     * @inheritDoc
     */
    function setDriver(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @inheritDoc
     */
    function setChannel(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @inheritDoc
     */
    function setThrottles($throttles)
    {
        $this->throttles = $throttles;
    }

    /**
     * @inheritDoc
     */
    function verify(string $to, $code, $label = '')
    {
        if (isset(static::$verifycodes[$to->getCodeAddress($this->channel)])) {
            return static::$verifycodes[$to->getCodeAddress($this->channel)] == $code;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    function verifyQR($code, $label = ''){

    }

    /**
     * @inheritDoc
     */
    function sendCode(CodeGenerator $codeGenerator, string $to, $label = '', $expires = 300, array $options = [])
    {
        //
    }

    /**
     * @inheritDoc
     */
    function generateCode(CodeGenerator $codeGenerator, string $to, $label = '', $expires = 300, array $options = [])
    {
        // TODO: Implement generateCode() method.
    }

    function getTo($code, $label = '')
    {
        // TODO: Implement getTo() method.
    }
}
