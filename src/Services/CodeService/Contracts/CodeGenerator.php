<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface CodeGenerator
{
    function getCode(array $options = []);
}
