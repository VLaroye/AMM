<?php

namespace App\Service;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookApiRequest
{
    private $appId;
    private $appSecret;
    private $defaultGraphVersion;
    private $accessToken;

    public function __construct($appId, $appSecret, $defaultGraphVersion, $accessToken)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->defaultGraphVersion = $defaultGraphVersion;
        $this->accessToken = $accessToken;
    }

    public function facebookGetRequest($url)
    {
        try {
            $fb = new Facebook([
                'app_id' => $this->appId,
                'app_secret' => $this->appSecret,
                'default_graph_version' => $this->defaultGraphVersion,
                'default_access_token' => $this->accessToken,
            ]);

            $response = $fb->get($url);
        } catch (FacebookSDKException $exception) {
            return $exception->getMessage();
        }

        return $response->getDecodedBody();
    }
}
