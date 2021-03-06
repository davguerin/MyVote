<?php

namespace DG\VoteBundle\Controller;

use DG\VoteBundle\Entity\Vote;
use DG\VoteBundle\Form\VoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VoteController extends Controller
{
    public function indexAction($id, Request $request)
    {
        $sheet = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Sheet')->find($id);
        if($sheet == null)
            throw new NotFoundHttpException('La fiche '.$id.' n\'existe pas');
        
        $token = $this->get('security.context')->getToken();
        if(!empty($token))
            $user = $token->getUser();
        
        $vote = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Vote')->findBy(array('sheet' => $sheet->getId(), 'user' => $user->getId()));
        
        if(empty($vote))
        {
            $vote = new Vote();
            $vote->setUser($user);
            $vote->setSheet($sheet);
        }
        else
            $vote = $vote[0];
        $form = $this->createForm(new VoteType, $vote);
        
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vote);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Vote enregistrée');
            return $this->redirect($this->generateUrl('dg_vote_vote_list'));
        }
        $sheet_votes = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Vote')->findBy(array('sheet' => $sheet->getId()));
        
        return $this->render('DGVoteBundle:Vote:index.html.twig', array(
            'vote' => $form->createView(),
            'sheet' => $sheet,
            'sheet_votes' => $sheet_votes,
        ));
    }
    
    public function listAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Sheet');
        
        $sheets = $repository->findBy(array(), array('name' => 'asc'));
        
        $vote = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Vote');
        
        return $this->render('DGVoteBundle:Vote:list.html.twig', array(
            'sheets' => $sheets,
            'vote' => $vote
        ));
    }
}
