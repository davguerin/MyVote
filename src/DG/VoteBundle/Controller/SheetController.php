<?php

namespace DG\VoteBundle\Controller;

use DG\VoteBundle\Entity\Sheet;
use DG\VoteBundle\Form\SheetType;
use DG\VoteBundle\Form\SheetEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SheetController extends Controller
{
    public function listAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Sheet');
        
        $sheets = $repository->findBy(array(), array('name' => 'asc'));
        
        return $this->render('DGVoteBundle:Sheet:list.html.twig', array(
            'sheets' => $sheets,
        ));
    }
    
    public function addAction(Request $request)
    {
        $sheet = new Sheet();
        
        $form = $this->createForm(new SheetType, $sheet);
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sheet);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Fiche enregistrée');
            return $this->redirect($this->generateUrl('dg_vote_sheet_edit', array('id' => $sheet->getId())));
        }
        
        return $this->render('DGVoteBundle:Sheet:edit.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    public function editAction($id, Request $request)
    {
        $sheet = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Sheet')->find($id);

        if($sheet == null)
            throw new NotFoundHttpException('La fiche '.$id.' n\'existe pas');
        
        $form = $this->createForm(new SheetEditType, $sheet);
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sheet);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Fiche enregistrée');
            return $this->redirect($this->generateUrl('dg_vote_sheet_edit', array('id' => $sheet->getId())));
        }
        
        return $this->render('DGVoteBundle:Sheet:edit.html.twig', array(
                'form' => $form->createView(),
            ));
    }
    
    public function deleteAction()
    {
        
    }
}
