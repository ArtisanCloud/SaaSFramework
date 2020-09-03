<?php


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
     * @param Sendable $sendable
     * @param $code
     * @param string $type
     * @return bool
     */
    function verify(Sendable $sendable, $code, $type = '');


    /**
     * @param CodeGenerator $codeGenerator
     * @param Sendable $sendable
     * @param int $type
     * @param int $expires
     * @param array $options
     * @return mixed
     */
    function sendVerifyCode(CodeGenerator $codeGenerator, Sendable $sendable, int $type, int $expires = 300, array $options = []);

    /**
     * @param CodeGenerator $codeGenerator
     * @param Sendable $sendable
     * @param int $type
     * @param int $expires
     * @param array $options
     * @return mixed
     */
    function generateCode(CodeGenerator $codeGenerator, Sendable $sendable, int $type, int $expires = 300, array $options = []);

    function getSendable($code, $type = '');
}
