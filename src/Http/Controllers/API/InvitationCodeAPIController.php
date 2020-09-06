<?php

namespace ArtisanCloud\SaaSFramework\Http\Controllers\API;

use ArtisanCloud\SaaSFramework\Http\Requests\RequestGenerateInvitationCode;

use ArtisanCloud\SaaSFramework\Services\CodeService\CodeGenerators\RandomStringGenerator;
use ArtisanCloud\SaaSFramework\Services\CodeService\InvitationCodeService;

use ArtisanCloud\SaaSFramework\Services\CodeService\Models\Code;
use Illuminate\Http\Request;


class InvitationCodeAPIController extends APIController
{

    protected $m_invitationCodeService = null;

    function __construct(Request $request, InvitationCodeService $service)
    {
        // init the default value
        // parent will construction automatically
        parent::__construct($request);

        $this->m_invitationCodeService = $service;
    }


    public function apiGenerateCode(RequestGenerateInvitationCode $request)
    {
        $email = $request->input('email');

        $generator = new RandomStringGenerator();
        $expire = 30*24*60*60; // 1 month
        $bResult = $this->m_invitationCodeService->sendCode($generator, $email,Code::TYPE_INVTATION, $expire);
        if(!$bResult){
            return APIResponse::error(API_ERR_CODE_FAIL_TO_SEND_INVITATION_CODE);
        }

        return APIResponse::success();
        
    }

    public function apiGetList(Request $request)
    {
//        dd($request->all());
        $para = $this->m_requestData;
//        dd($this->m_requestData);
        $para['type'] = count($para['type'])>0 ? $para['type'] : null;

        $accountList = $this->m_account->getListForClient($para)->get();
//        $accountList = $this->m_account->getCachedListForClient($para);
//        dd($accountList);

        $apiResource = AccountResource::collection($accountList);

        $this->setData($apiResource);

        return $this->getJSONResponse();

    }


    public function apiGetDetail(Request $request)
    {
//        dd($request->all());
        $uuid = $this->m_requestData['uuid'];
//        dd($id);

        $account = $this->m_account->getDetailForClientByUUID($uuid);
//        $account = $this->m_account->getCachedDetailForClientByUUID($uuid);
//        dd($account);

        $apiResource = new AccountResource($account);
        $this->setData($apiResource);

        return $this->getJSONResponse();

    }




}
