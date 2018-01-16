<?php

namespace App\Service;

use Facebook\Facebook;

class FacebookApiRequest
{
    private $fb;

    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => '713831422155114',
            'app_secret' => '2f60b0148e0137b093d1d1ad20ee05b7',
            'default_graph_version' => 'v2.11',
            'default_access_token' => '713831422155114|iuEk_7llEs4BFw0Vn5p2pQXdGq4',
        ]);
    }

    public function facebookGetRequest($url)
    {
        try {
            // Returns a `FacebookFacebookResponse` object
            $response = $this->fb->get(
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
