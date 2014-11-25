<?php

namespace DG\VoteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SheetControllerTest extends WebTestCase
{
    private $client;
    
    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => 'test',
            '_password' => 'test',
        ));
        
        $this->client->submit($form);
    }

    public function testListView()
    {
        $this->client->request('GET', '/sheet');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Liste des fiches/', $this->client->getResponse()->getContent());
    }
    
    public function testListLink()
    {
        $crawler = $this->client->request('GET', '/sheet');
        $link = $crawler->selectLink('Ajouter une fiche')->last()->link();
        $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Formulaire de cr/', $this->client->getResponse()->getContent());
//        $info = $crawler->extract(array('_text', 'href'));
//        var_dump($info);
    }

    public function testAddSheetPage()
    {
        $crawler = $this->client->request('GET', '/sheet/add');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Formulaire de cr/', $this->client->getResponse()->getContent());
    }
    
    public function testAddSheet()
    {
        $image = new UploadedFile(
            __DIR__.'/../files/red.jpg',
            'red.jpg',
            'image/jpeg',
            2270
        );
        
        $crawler = $this->client->request('GET', '/sheet/add');
        $form = $crawler->selectButton('dg_votebundle_sheet[save]')->form(array(
            'dg_votebundle_sheet[name]' => 'Test sheet',
            'dg_votebundle_sheet[description]' => 'Test description',
            'dg_votebundle_sheet[file]' => $image,
        ));
        $crawler = $this->client->submit($form);
        $this->assertRegExp('#sheet/edit#', $this->client->getRequest()->getUri());
        $info = $crawler->extract(array('_text', 'href'));
        
        $crawler = $this->client->request('GET', '/sheet');
        $link = $crawler->selectLink('Test sheet')->last()->link();
        $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Formulaire de cr/', $this->client->getResponse()->getContent());
    }
    
    public function testEditSheet()
    {
        $image = new UploadedFile(
            __DIR__.'/../files/blue.jpg',
            'blue.jpg',
            'image/jpeg',
            2270
        );
        
        $crawler = $this->client->request('GET', '/sheet');
        $link = $crawler->selectLink('Test sheet')->last()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Formulaire de cr/', $this->client->getResponse()->getContent());
        $info = $crawler->extract(array('_text', 'href', 'body', ''));
//        var_dump($info);
//        print_r($crawler->html());
        
        $form = $crawler->selectButton('dg_votebundle_sheet_edit[save]')->form(array(
            'dg_votebundle_sheet_edit[name]' => 'Test sheet edit',
            'dg_votebundle_sheet_edit[description]' => 'Test description',
            'dg_votebundle_sheet_edit[file]' => $image,
        ));
        $crawler = $this->client->submit($form);
        $this->assertRegExp('#sheet/edit#', $this->client->getRequest()->getUri());
//        $info = $crawler->extract(array('_text', 'href'));
//        
        $crawler = $this->client->request('GET', '/sheet');
        $link = $crawler->selectLink('Test sheet edit')->last()->link();
        $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegExp('/Formulaire de cr/', $this->client->getResponse()->getContent());
    }
    
    public function testDeleteSheet()
    {
        $crawler = $this->client->request('GET', '/sheet');
        $link = $crawler->selectLink('X')->last()->link();
        $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotRegExp('/Test sheet edit/', $this->client->getResponse()->getContent());
    }
}
