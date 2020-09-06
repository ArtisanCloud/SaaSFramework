<?php

namespace ArtisanCloud\SaaSFramework\Http\Requests;

class RequestGenerateInvitationCode extends RequestBasic
{
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
            'email' => 'required|email:rfc,dns'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __("{$this->m_module}.required"),
            'email.email' => __("{$this->m_module}.email"),
        ];
    }
}
