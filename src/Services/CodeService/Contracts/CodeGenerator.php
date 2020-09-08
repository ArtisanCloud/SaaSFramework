<?php
declare(strict_types=1);


namespace ArtisanCloud\SaaSFramework\Services\CodeService\Contracts;


interface CodeGenerator
{
    function getCode(array $options = []);
}
