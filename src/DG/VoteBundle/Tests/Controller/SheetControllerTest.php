<?php

namespace DG\VoteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SheetControllerTest extends WebTestCase
{
    private $client;
    
    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => 'test',
            '_password'  => 'test',
        ));
        
        $this->client->submit($form);
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}
