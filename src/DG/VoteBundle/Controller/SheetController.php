<?php

namespace DG\VoteBundle\Controller;

use DG\VoteBundle\Entity\Sheet;
use DG\VoteBundle\Form\SheetType;
use DG\VoteBundle\Form\SheetEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SheetController extends Controller
{
    public function listAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Sheet');
        
        $sheets = $repository->findBy(array(), array('name' => 'asc'));

        $token = $this->get('security.context')->getToken();
        if(!empty($token))
            $user = $token->getUser();
        
        $rights = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        
        return $this->render('DGVoteBundle:Sheet:list.html.twig', array(
            'sheets' => $sheets,
            'user' => $user,
            'rights' => $rights,
        ));
    }
    
    public function addAction(Request $request)
    {
        $sheet = new Sheet();
        
        $form = $this->createForm(new SheetType, $sheet);
        
        $form->handleRequest($request);

        $token = $this->get('security.context')->getToken();
        if(!empty($token))
        {
            $user = $token->getUser();
            $sheet->setUser($user);
        }

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

        $token = $this->get('security.context')->getToken();
        if(!empty($token))
            $user = $token->getUser();
        
        if (empty($token) || !$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') && $user->getId() != $sheet->getUser()->getId())
            throw new AccessDeniedException('Vous n\'avez pas accès à la modification de cette fiche');

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
                'sheet' => $sheet,
                'img' => $sheet->getWebPath(),
            ));
    }
    
    public function deleteAction($id)
    {
        $sheet = $this->getDoctrine()->getManager()->getRepository('DGVoteBundle:Sheet')->find($id);

        if($sheet == null)
            throw new NotFoundHttpException('La fiche '.$id.' n\'existe pas');

        $token = $this->get('security.context')->getToken();
        if(!empty($token))
            $user = $token->getUser();
        
        if (empty($token) || !$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') && $user->getId() != $sheet->getUser()->getId())
            throw new AccessDeniedException('Vous n\'avez pas accès à la modification de cette fiche');
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($sheet);
        $em->flush();
        
        return $this->redirect($this->generateUrl('dg_vote_sheet_list'));
    }
}
