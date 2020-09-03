<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\CodeGenerators;


use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\CodeGenerator;
use Illuminate\Support\Str;

class RandomStringGenerator implements CodeGenerator
{

    function getCode(array $options = [])
    {
        return Str::random();
    }
}
