<?php namespace DG\VoteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VoteControllerTest extends WebTestCase
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

    public function testListView()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Liste des fiches/', $this->client->getResponse()->getContent());
    }
    
    public function testListLink()
    {
        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('Vote')->last()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Voter pour/', $this->client->getResponse()->getContent());
//        $info = $crawler->extract(array('_text', 'href'));
//        var_dump($info);
    }
    
    public function testFalseVote()
    {
        $this->client->request('GET', '/vote/abc');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
    
    public function testPostVote()
    {
        $crawler = $this->client->request('GET', '/vote/1');
        $form = $crawler->selectButton('dg_votebundle_vote[submit]')->form(array(
            'dg_votebundle_vote[vote]'  => '3',
        ));
        $this->client->submit($form);
    }
}
