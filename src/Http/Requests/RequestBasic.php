<?php

namespace ArtisanCloud\SaaSFramework\Http\Requests;

use ArtisanCloud\SaaSFramework\Http\Controllers\API\APIResponse;
use ArtisanCloud\SaaSFramework\Models\ClientProfile;
use Illuminate\Foundation\Http\FormRequest;

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


        });

//        $validator->extend('not_exists', function ($attribute, $value, $parameters) use ($validator) {
//            //this is opposite of using `exists` rule, so we use exists
//            //but when it fails, it returns true! [simple solution!]
//            $v = $validator->make(
//                [
//                    $attribute => $value
//                ],
//                [
//                    $attribute => "exists:" . implode(",", $parameters)
//                ]
//            );
//            return $v->fails();
//        });
    }


}
