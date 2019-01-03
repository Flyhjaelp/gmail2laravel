<?php

use flyhjaelp\Gmail2Laravel\Gmail2Laravel;
use PHPUnit\Framework\Constraint\IsType;

use PHPUnit\Framework\TestCase;

class GmailClientFeatureTest extends TestCase
{
    protected $credentials = 'testing/path/to/key.json';

    /** @test */
    public function instantiating_new_object_sets_the_path_as_string()
    {
        $app = new Gmail2Laravel($this->credentials);

        $this->assertInternalType(IsType::TYPE_STRING, $app->getCredentialsPath());
    }

    /** @test */
    public function availableClients_returns_an_array_of_user_emails()
    {
        $app = new Gmail2Laravel($this->credentials);

        $this->assertInternalType(IsType::TYPE_ARRAY, $app->availableClients());
    }

    /** @test */
    public function creating_a_new_gmail_client_for_an_address_adds_it_to_the_array_of_available_client()
    {
        $app = new Gmail2Laravel($this->credentials);

        $app->getGmailClient('testing@flyhjaelp.dk');

        $this->assertEquals(1,count($app->availableClients()));
    }

    /** @test */
    public function deleting_a_gmail_client_instance_removes_it_from_the_memory()
    {
        $app = new Gmail2Laravel($this->credentials);

        $app->getGmailClient('testing@flyhjaelp.dk');

        $this->assertEquals(1,count($app->availableClients()));

        $app->deleteGmailClient('testing@flyhjaelp.dk');

        $this->assertEquals(0,count($app->availableClients()));
    }

    /** @test */
    public function calling_an_already_existing_client_fetches_from_memory_and_does_not_create_a_new_client()
    {
        $app = new Gmail2Laravel($this->credentials);

        $app->getGmailClient('testing@flyhjaelp.dk');

        $this->assertEquals(1,count($app->availableClients()));

        $app->getGmailClient('testing@flyhjaelp.dk');

        $this->assertEquals(1,count($app->availableClients()));
    }

    // validity and exceptions

    /** @test */
    public function an_exception_is_thrown_if_the_email_is_not_part_of_the_domain()
    {
        $this->expectException(Exception::class);

        $app =  new Gmail2Laravel($this->credentials);

        $app->getGmailClient('email@email.com');
    }

    /** @test */
    public function an_exception_is_thrown_if_the_email_is_part_of_the_domains_but_a_non_existing_email_address()
    {
        $this->expectException(Exception::class);

        $app =  new Gmail2Laravel($this->credentials);

        $app->getGmailClient('email@flyhjaelp.dk');
    }

    /** @test */
    public function an_exception_is_not_thrown_for_a_correct()
    {
        $app =  new Gmail2Laravel($this->credentials);

        $response = $app->getGmailClient('testing@flyhjaelp.dk');

        $this->assertInstanceOf(Google_Service_Gmail::class, $response);
    }

    /** @test */
    public function you_can_fetch_emails_from_multiple_adresses()
    {
        $app =  new Gmail2Laravel($this->credentials);

        $app->getGmailClient('testing@flyhjaelp.dk');

        $app->getGmailClient('testing2@flyhjaelp.dk');

        $emails = $app->getEmailsFromAllClients('package query');

        $this->assertEquals(count($emails),0);

        $this->assertInternalType(IsType::TYPE_ARRAY, $emails);
    }

    /** @test */
    public function you_can_set_batch_on_the_client()
    {
        $app =  new Gmail2Laravel($this->credentials);

        $user = 'testing@flyhjaelp.dk';

        $app->getGmailClient($user);

        $app->enableBatch($user);

        $this->assertInstanceOf(Google_Client::class,  $app->getGmailClient($user)->getClient());

        $app->disableBatch($user);

        $this->assertInstanceOf(Google_Client::class,  $app->getGmailClient($user)->getClient());
    }
}