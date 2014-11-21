<?php

namespace DG\VoteBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DG\VoteBundle\Entity\Vote;
use DG\UserBundle\Entity\User;
use DG\VoteBundle\Entity\Sheet;

class VoteTest extends WebTestCase
{
    public function testGetSetVote()
    {
        $vote = new Vote();
        $vote->setVote('42');
        $vote_number = $vote->getVote();

        $this->assertEquals('42', $vote_number);
    }
    
    public function testGetSetUser()
    {
        $user = new User();
        $user->setUsername('test_name');
        
        $vote = new Vote();
        $vote->setUser($user);
        $user_result = $vote->getUser();
        
        $this->assertEquals($user, $user_result);
    }
    
    public function testGetSetSheet()
    {
        $sheet = new Sheet();
        $sheet->setName('test_name');
        
        $vote = new Vote();
        $vote->setSheet($sheet);
        $sheet_result = $vote->getSheet();
        
        $this->assertEquals($sheet, $sheet_result);
    }
}
