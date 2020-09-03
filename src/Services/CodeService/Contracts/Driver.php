<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface Driver
{
    /**
     * @param string $expire seconds
     * @param Sendable $sendable
     * @param string $label
     * @return mixed
     */
    function setVerifyCode($code, $expires, Channel $channel, Sendable $sendable, $label = '');

    function getVerifyCode(Channel $channel, Sendable $sendable, $label = '');

    function canSend($throttles, Channel $channel, Sendable $sendable, $label = '');

    function getSendable($code, $label = '');

    function setQRCode($code, $expires, Channel $channel, Sendable $sendable, $label = '');
    function readQRCode($value, $label);

}
