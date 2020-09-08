<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface CodeServiceContract
{
    public function __construct(Driver $driver, Channel $channel);

    /**
     * @param Driver $driver
     * @return $this
     */
    function setDriver(Driver $driver);

    /**
     * @param Channel $channel
     * @return $this
     */
    function setChannel(Channel $channel);

    /**
     * @param $throttles
     * @return $this
     */
    function setThrottles($throttles);

    /**
     * @param string $to
     * @param $code
     * @param string $type
     * @return bool
     */
    function verify(string $to, $code, $type = '');


    /**
     * @param CodeGenerator $codeGenerator
     * @param string $to
     * @param int $type
     * @param int $expires
     * @param array $options
     * @return mixed
     */
    function sendCode(CodeGenerator $codeGenerator, string $to, int $type, int $expires = 300, array $options = []);

    /**
     * @param CodeGenerator $codeGenerator
     * @param string $to
     * @param int $type
     * @param int $expires
     * @param array $options
     * @return mixed
     */
    function generateCode(CodeGenerator $codeGenerator, string $to, int $type, int $expires = 300, array $options = []);

    /**
     * @param mixed $code
     * @param int $type
     * @return mixed
     */
    function getTo($code, $type = '');
}
