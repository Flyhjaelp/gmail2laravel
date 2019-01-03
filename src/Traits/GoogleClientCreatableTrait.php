<?php

namespace flyhjaelp\Gmail2Laravel\Traits;

use Google_Client;

trait GoogleClientCreatableTrait
{
    protected function createGoogleClient() : Google_Client
    {

        $client = new Google_Client();

        $client = $this->setDefaultToken($client);

        $client = $this->setGoogleClientAuthConfig($client);

        $client->setAccessType('on');

        $client = $this->setValidTokenForClient($client);

        return $client;
    }

    protected function setDefaultToken($client): Google_Client
    {

        if($client->getAccessToken() == null)
        {
            $tempToken  = file_get_contents(__DIR__ .'/../../storage/temp_token.json');

            $client->setAccessToken($tempToken);

        }
        return $client;

    }

    protected function setValidTokenForClient(Google_Client $client): Google_Client
    {
        if ($client->isAccessTokenExpired()) {

            $client->refreshToken($client->getAccessToken());

        }
        return $client;
    }

    protected function setGoogleClientAuthConfig(Google_Client $client): Google_Client
    {
        $client->setAuthConfig($this->getCredentialsPath());

        return $client;

    }

}