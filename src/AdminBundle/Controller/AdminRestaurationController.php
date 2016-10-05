<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AdminBundle\Controller;

use AdminBundle\Entity\Resto;
use AdminBundle\Form\RestoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AdminRestaurationController
 *
 * @author vivien
 */
class AdminRestaurationController extends Controller{
    /**
     * @Route("/admin/resto",name="adminResto")
     * @Template("AdminBundle::adminResto.html.twig")
     * 
     */
    public function indexResto() {
        $em = $this->getDoctrine()->getManager();
        $resto = $em->getRepository("AdminBundle:Resto")->findAll();
        return $this->render('AdminBundle::adminResto.html.twig', array("resto" => $resto));
    }
    /**
     * @Route("/admin/resto/add", name="adminRestoAdd")
     * @Template("AdminBundle::adminRestoAdd.html.twig")
     */
    public function addResto() {
        $f = $this->createForm(RestoType::class,new Resto());
        return array("formResto" => $f->createView());
        
    }
    
    /**
     * @Route("/admin/resto/get", name="validResto")
     */
    public function getResto(Request $request){
        
       $resto = new Resto();
       
       $f = $this->createForm(RestoType::class,$resto);
       $f->handleRequest($request);
       $nomDuFichier = md5(uniqid()).'.'.$resto->getPhoto()->getClientOriginalExtension();
       $resto->getPhoto()->move('../web/images',$nomDuFichier);
       $resto->setPhoto($nomDuFichier);
       $em = $this->getDoctrine()->getManager();
       $em->persist($resto);
       $em->flush();
       
       return $this->redirect($this->generateUrl('adminResto'));
    }
    /**
     * @Route("/admin/resto/deleteR/{id}", name="suprResto")
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $recupId = $em->find("AdminBundle:Resto", $id);
        $em->remove($recupId);
        $em->flush();
        return $this->redirect($this->generateUrl('adminResto'));
    }
}
