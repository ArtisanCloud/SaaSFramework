<?php

namespace ArtisanCloud\SaaSFramework\Http\Controllers\API;

use ArtisanCloud\SaaSFramework\Services\CodeService\CodeGenerators\RandomStringGenerator;
use ArtisanCloud\SaaSFramework\Services\CodeService\InvitationCodeService;
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


    public function apiBatchGenerateCode(Request $request)
    {
        $generator = new RandomStringGenerator();
        $this->m_invitationCodeService->generateCode($generator, 50);
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
