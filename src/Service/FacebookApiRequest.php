<?php

namespace App\Service;

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
        $fb = new Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
            'default_graph_version' => $this->defaultGraphVersion,
            'default_access_token' => $this->accessToken,
        ]);

        try { 
            // Returns a `FacebookFacebookResponse` object
            $response = $fb->get(
                $url);
        } catch (FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returned an error: '.$e->getMessage();
            exit;
        } catch (FacebookExceptionsFacebookSDKException $e) {
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            exit;
        }

        return $response->getDecodedBody();
    }
}
