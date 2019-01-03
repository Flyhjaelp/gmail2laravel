<?php

namespace flyhjaelp\Gmail2Laravel\Traits;

use Exception;
use Google_Client;
use Google_Service_Exception;
use Google_Service_Gmail;

trait GmailClientCreatableTrait
{
    private function privateGmailClient(String $user)
    {

        if(!isset($this->gmailClients[$user])){
            return $this->createGmailClient($user);
        }
        else{

            if($this->gmailClients[$user] == 'Not valid'){
                throw new Exception('The email is not part of the domain.');
            }
            else{
                return $this->gmailClients[$user];
            }
        }
    }

    private function createGmailClient($user)
    {

        $client = $this->createGoogleClient();

        $client  = $this->setGMailScopes($client);

        $client->setSubject($user);

        //TODO  check if its inside the domain

        $gmailClient = new Google_Service_Gmail($client);



        if($this->checkEmailDomainValidity($gmailClient)){
            $this->gmailClients[$user] = $gmailClient;
            return $this->gmailClients[$user];
        }
        else{
            $this->gmailClients[$user] = 'Not valid';
            throw new Exception('The email is not part of the domain.');
        }
    }

    protected function checkEmailDomainValidity($gmailClient)
    {
        try{
            $gmailClient->users->getProfile('me');
            return true;

        }catch (Google_Service_Exception $e){
            return false;
        }
    }

    protected function setGMailScopes(Google_Client  $client) : Google_Client
    {
        $client->setScopes([
            'https://www.googleapis.com/auth/admin.directory.user',
            'https://www.googleapis.com/auth/admin.directory.group',
            'https://mail.google.com/',
            'https://mail.google.com/auth/gmail.modify',
            'https://mail.google.com/auth/gmail.readonly',
        ]);

        return $client;
    }
}