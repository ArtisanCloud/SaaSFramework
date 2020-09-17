<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Http\Requests;

use ArtisanCloud\SaaSFramework\Http\Controllers\API\APIResponse;
use ArtisanCloud\SaaSFramework\Models\ClientProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RequestBasic extends FormRequest
{
    const MAX_PER_PAGE = 20;

    public APIResponse $m_apiResponse;
    public string $m_module = 'validation';

    function __construct()
    {
        $this->m_apiResponse = new APIResponse();
        // set up APIResponse string source and language
        $this->m_apiResponse->setLocaleModule($this->m_module);
        $sessionLocale = ClientProfile::getSessionLocale();
        \App::setLocale($sessionLocale);

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'channel' => 'nullable|integer',
            'platform' => 'nullable|integer',
            'deviceID' => 'nullable|string',
            'osType' => 'nullable|integer',
            'osVersion' => 'nullable|string',

        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {

        $validator->after(function ($validator) {

            $arrayTransformedKeys = transformArrayKeysToSnake($this->all());
//            dd($arrayTransformedKeys);
            $this->replace($arrayTransformedKeys);
        });
        
    }




}
