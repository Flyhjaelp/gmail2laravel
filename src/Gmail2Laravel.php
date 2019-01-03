<?php

namespace flyhjaelp\Gmail2Laravel;

use flyhjaelp\Gmail2Laravel\Traits\GmailClientCreatableTrait;
use flyhjaelp\Gmail2Laravel\Traits\GoogleClientCreatableTrait;

class Gmail2Laravel
{
    use GmailClientCreatableTrait, GoogleClientCreatableTrait;

    protected $gmailClients = [];

    protected $tokenForClients = [];

    protected $credentialsPath;

    public function __construct(String $credentialsPath)
    {
        $this->setCredentialsPath($credentialsPath);
    }

    /*************************************/
    /*      GOOGLE SETTER AND GETTERS    */
    /*************************************/

    public function getGmailClient(String $user)
    {
        $gmailClient = $this->privateGmailClient($user);

        $this->gmailClients[$user] = $gmailClient;

        return  $this->gmailClients[$user];
    }

    public function deleteGmailClient(String $user)
    {
        unset($this->gmailClients[$user]);

        return true;
    }

    public function getEmailsFromAllClients(String $query)
    {
        $emails = [];

        foreach ($this->gmailClients as $gmailClient){

            do{
                $opt_param = [
                    'maxResults' => 500,
                    'q' =>  $query
                ];
                $result = $gmailClient->users_messages->listUsersMessages('me', $opt_param);
                $emails = array_merge($emails, $result['messages']);

            }while($result['nextPageToken']);
        }

        return $emails;
    }

    public function availableClients()
    {
        return array_keys($this->gmailClients);
    }

    public function enableBatch(String $user)
    {
        return $this->gmailClients[$user]->getClient()->setUseBatch(true);
    }

    public function disableBatch(String $user)
    {
        return $this->gmailClients[$user]->getClient()->setUseBatch(false);
    }




    /*************************************/
    /*       SETTERS AND GETTERS         */
    /*************************************/

    /**
     * @return String
     */
    public function getCredentialsPath(): String
    {
        return $this->credentialsPath;
    }

    /**
     * @param String $credentialsPath
     */
    public function setCredentialsPath(String $credentialsPath): void
    {
        $this->credentialsPath = $credentialsPath;
    }

}