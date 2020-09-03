<?php


namespace ArtisanCloud\SaaSFramework\Services\CodeService\CodeGenerators;


use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\CodeGenerator;

class NumberCodeGenerator implements CodeGenerator
{

    /**
     * @var int
     */
    private $length;

    public function __construct($length = 4)
    {
        $this->length = $length;
    }

    function getCode(array $options = [])
    {
        $length = isset($options['length']) ? $options['length'] : $this->length;
        return $this->generateNumberCode($length);
    }

    /**
     * Generate numeric verify code
     * @param $length
     * @return string
     */
    protected function generateNumberCode($length)
    {
        $characters = '0123456789';
        return $this->generateCode($characters, $length);
    }

    /**
     * Generate random string from characters
     * @param string $characters
     * @param $length
     * @return string
     */
    protected function generateCode(string $characters, $length)
    {
        $codes = [];
        $characters = str_split($characters);
        $charactersLength = count($characters);
        for ($i = 0; $i < $length; $i++) {
            $codes[] = $characters[rand(0, $charactersLength - 1)];
        }

        return implode('', $codes);
    }
}
