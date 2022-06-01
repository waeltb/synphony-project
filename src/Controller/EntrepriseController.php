<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Postulation;
use App\Form\OffreFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/home", name="app_publication")
     */
    public function index(): Response

    {
        
        return $this->render('Entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }
    /**
     * @Route("/offres_edit/mobile/{id}/{titre}/{contenu}", name="offre_mobile_edit")
     */
    public function offre_mobile_edit( $id,$titre,$contenu, NormalizerInterface  $normalizer)
    {
        $offre= $this->getDoctrine()->getRepository(Offre::class)->find($id);
        $offre->setTitre($titre);
        $offre->setContenu($contenu);
       
        $em = $this->getDoctrine()->getManager();
     
        $em->flush();
        $offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        $jsonContent = $normalizer->normalize($offres, 'json',['groups' => ['off']]);
        return new JsonResponse($jsonContent);

    }
     /**
     * @Route("/offres/mobile",name="offre_mobile")
     */
    public function offre_mobile( NormalizerInterface  $normalizer)
    {


        $offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        $jsonContent = $normalizer->normalize($offres, 'json',['groups' => ['off']]);
        return new JsonResponse($jsonContent);

    }
    /**
     * @Route("/offres_add/mobile/{titre}/{contenu}", name="offre_mobile_add")
     */
    public function offre_mobile_add( $titre,$contenu, NormalizerInterface  $normalizer)
    {
        $offre = new Offre();
        $offre->setTitre($titre);
        $offre->setContenu($contenu);
        $date=new \DateTime();
        $offre->setCreatedAt($date);
        $date2=new \DateTime();
        $datefin=date_add($date2, date_interval_create_from_date_string('90 days'));
        $offre->setUpdateAt($datefin);
        $em = $this->getDoctrine()->getManager();
        $em->persist($offre);//Add
        $em->flush();
        $offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        $jsonContent = $normalizer->normalize($offres, 'json',['groups' => ['off']]);
        return new JsonResponse($jsonContent);

    }
    /**
     * @Route("/offres_delete/mobile/{id}", name="offre_mobile_delete")
     */
    public function offre_mobile_delete( $id, NormalizerInterface  $normalizer)
    {

        $of = $this->getDoctrine()->getRepository(Offre::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($of);
        $em->flush();
        $offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        $jsonContent = $normalizer->normalize($offres, 'json',['groups' => ['off']]);
        return new JsonResponse($jsonContent);

    }
    /**
     * @Route("/listOff", name="listOff")
     */
    public function listOff(): Response
    {
        $offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        $Postulations = $this->getDoctrine()->getRepository(Postulation::class)->findAll();
      
        return $this->render('Entreprise/listeO.html.twig', array("ofs"=>$offres,'pos'=>$Postulations));
    }
     /**
      
     * @Route("/addoffre", name="addoffre")
     */
    public function addOffre(Request $request): Response
    {
        $offre = new Offre();

        $form = $this->createForm(OffreFormType::class,$offre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $date=new \DateTime();
            $offre->setCreatedAt($date);
            $date2=new \DateTime();
            $datefin=date_add($date2, date_interval_create_from_date_string('90 days'));
            $offre->setUpdateAt($datefin);
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);//Add
            $em->flush();

            return $this->redirectToRoute('listOff');
        }
        return $this->render('Entreprise/createOffre.html.twig',['f'=>$form->createView()]);




    }
    
    /**
     * @Route("/deleteOf/{id}", name="deleteOf")
     */
    public function deleteOf($id): Response
    {
        $of = $this->getDoctrine()->getRepository(Offre::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($of);
        $em->flush();
        return $this->redirectToRoute('listOff');
    }
    /**
     * @Route("/updateOf/{id}", name="updateOf")
     */
    public function updateOf(Request $request,$id): Response
    {

        $of= $this->getDoctrine()->getRepository(Offre::class)->find($id);
        
        $form=$this->createForm(OffreFormType::class, $of);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            /*$em->persist($pub);*/
            $em->flush();
            return $this->redirectToRoute('listOff');

        }
        return $this->render("Entreprise/updateO.html.twig",array('f'=>$form->createView()));
    }
      
    

}
