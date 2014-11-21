<?php

namespace DG\VoteBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DG\VoteBundle\Entity\Sheet;
use DG\UserBundle\Entity\User;

class SheetTest extends WebTestCase
{
    public function testGetSetName()
    {
        $sheet = new Sheet();
        $sheet->setName('test');
        $name = $sheet->getName();
        
        $this->assertEquals('test', $name);
    }
    
    public function testGetSetDescription()
    {
        $sheet = new Sheet();
        $sheet->setDescription('Une description');
        $description = $sheet->getDescription();
        
        $this->assertEquals('Une description', $description);
    }
    
    public function testGetSetImage()
    {
        $sheet = new Sheet();
        $sheet->setImage('test.png');
        $image = $sheet->getImage();
        
        $this->assertEquals('test.png', $image);
    }
    
    public function testGetSetUser()
    {
        $user = new User();
        $user->setUsername('test_name');
        
        $sheet = new Sheet();
        $sheet->setUser($user);
        $user_result = $sheet->getUser();
        
        $this->assertEquals('test_name', $user_result->getUsername());
    }
}
