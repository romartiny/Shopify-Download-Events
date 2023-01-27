<?php

namespace App\Http\Repository;

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\VersionController;

class ResponseRepository
{
    public string|VersionController $API_ADMIN_VERSION;

    public function __construct(VersionController $versionController)
    {
        $this->API_ADMIN_VERSION = $versionController;
    }

    public function getShopifyResponse($requestType ,$shopifyData, $resourceType)
    {
        return $shopifyData->api()
            ->rest($requestType, $this->API_ADMIN_VERSION->retrieveApiAdminVersion() . $resourceType);
    }
}
