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
        $repository = $this->get('fos_elastica.manager.orm')->getRepository('DGVoteBundle:Sheet');
        
        $sheets = $repository->findOrdered('', array('name.raw' => 'asc'));
        
        $vote = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Vote');
        
        return $this->render('DGVoteBundle:Vote:list.html.twig', array(
            'sheets' => $sheets,
            'vote' => $vote
        ));
    }
    
    public function randomAction(Request $request)
    {
        $vote_repository = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Vote');
        $sheet_repository = $this->get('fos_elastica.manager.orm')->getRepository('DGVoteBundle:Sheet');
        
        $token = $this->get('security.context')->getToken();
        if(!empty($token))
            $user = $token->getUser();
        
        $votes = $vote_repository->findBy(array('user' => $user->getId()));
        $votes_array = array();
        foreach($votes as $vote_user)
            $votes_array[] = $vote_user->getSheet()->getId();

        $sheets = $sheet_repository->findByIds('id', $votes_array, false);
        if(!empty($sheets))
        {

            $randnum = rand(0, count($sheets) - 1);

            $vote = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Vote')->findBy(array('sheet' => $sheets[$randnum]->getId(), 'user' => $user->getId()));

            if(empty($vote))
            {
                $vote = new Vote();
                $vote->setUser($user);
                $vote->setSheet($sheets[$randnum]);
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
                return $this->redirect($this->generateUrl('dg_vote_vote_random'));
            }
            $sheet_votes = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Vote')->findBy(array('sheet' => $sheets[$randnum]->getId()));
            
            return $this->render('DGVoteBundle:Vote:random.html.twig', array(
                'vote' => $form->createView(),
                'sheet' => $sheets[$randnum],
                'sheet_votes' => $sheet_votes,
            ));
        }
        else
            return $this->render('DGVoteBundle:Vote:random.html.twig', array(
                'no_more' => true,
            ));
    }
}
