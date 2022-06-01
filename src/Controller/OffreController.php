<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\PostulationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Postulation ;
use App\Form\OffreFormType;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
/**
 * Class OffreController
 * @package App\Controller
 
 */

class OffreController extends AbstractController
{



   

/**
     * @Route("/postulation/{id_o}/{email}/{contenu}/{psuedo}", name="postulation")
    */
    public function postulation($id_o,$email,$contenu,$psuedo, NormalizerInterface  $normalizer) 
    {
       
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find( $id_o);
        
         $postulation = new Postulation();

      
$postulation->setContenu($contenu);
$postulation->setEmail($email);
$postulation->setPseudo($psuedo);         
            $postulation->setOffre($offre);

            
            $postulation->setCreatedAt(new \DateTime('now'));
$postulation->setActive('active');
$em = $this->getDoctrine()->getManager();
$em->persist($postulation);//Add
$em->flush();      
$offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();
$jsonContent = $normalizer->normalize($offres, 'json',['groups' => ['off']]);
return new JsonResponse($jsonContent);
        
        
        


}





    /**
     * @Route("/detaile/{id}", name="detaile")
    */
    public function detaile($id, Request $request) 
    {
        // On récupère l'article correspondant au slug
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find( $id);
        
        $offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        // Nous créons l'instance de "Commentaires"
        $postulation = new Postulation();

        // Nous créons le formulaire en utilisant "CommentairesType" et on lui passe l'instance
        $form = $this->createForm(PostulationFormType::class, $postulation);

        // Nous récupérons les données
        $form->handleRequest($request);



        // Nous vérifions si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            
            $postulation->setOffre($offre);

            // Hydrate notre commentaire avec la date et l'heure courants
            $postulation->setCreatedAt(new \DateTime('now'));
$postulation->setActive('active');
            

            $doctrine = $this->getDoctrine()->getManager();

            // On hydrate notre instance $commentaire
            $doctrine->persist($postulation);

            // On écrit en base de données
            $doctrine->flush();
            // Si l'article existe nous envoyons les données à la vue
        return $this->render('offres/offre.html.twig', [
            
            'off' => $offres,
            'poste' => $postulation,
        ]);
        
        
        
    }
    return $this->render('offres/ajoutPoste.html.twig', [
        'offres'=>$offre, 
        'formPostul'=>$form->createView()
    ]);
    

}
     /**
     * @Route("/pdf", name="pdf",  methods={"GET","POST"})
     */
    public function pdf(Request $request)
    {

// Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

// Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $repository=$this->getdoctrine()->getrepository(Offre::class);
        $offres=$repository->findAll();

// Retrieve the HTML generated in our twig file
        $html = $this->renderView('offres/pdf.html.twig', [
            'title' => "Welcome to our PDF Test", 'offres'=>$offres,
        ]);

// Load HTML to Dompdf
        $dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A2', 'portrait');

// Render the HTML as PDF
        $dompdf->render();

// Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
        $pdfOptions->set('isRemoteEnabled', true);

    }
    /**
     * @Route("/", name="accueil" )
     */
    public function index(): Response
    {
        $this->getDoctrine()->getRepository(Offre::class)->mise_a_jour();
        //on appele la liste de tous les offres
        $offre = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        

        
        return $this->render('offres/index.html.twig', [
            'offres' => $offre
        ]);
    }
    
    
  


    }

      
    


