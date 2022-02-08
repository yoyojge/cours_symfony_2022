<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{
    #[Route('/personne', name: 'personne')]
    public function index(): Response
    {
        return $this->render('personne/index.html.twig', [
            'controller_name' => 'PersonneController',
        ]);
    }
    #[Route('/personne/add', name: 'personne_add')]
    public function addForm(Request $request, EntityManagerInterface $em): Response
    {
        $personne = new Personne();
        /*1.On appelle la méthode createFormBuilder
        2 On spécifie le nom d’objet qui va assurer le binding
        3 On indique le type HTML pour chaque attribut de l’objet
        4 On appelle la méthode getForm () pour générer le formulaire
        */
        $form = $this->createFormBuilder($personne)
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("creer", SubmitType::class)
            ->getForm();
        /*                
        5 On passe la méthode createView () du formulaire à la vue */
        //on récupère les données de formulaire
        $form->handleRequest($request);
        //si le formulaire a été envoyé avec la méthode POST
        if ($form->isSubmitted() && $form->isValid()) {
            $personne = $form->getData();
            $em->persist($personne);
            $em->flush();
            //on redirige vers /personne
            return $this->redirectToRoute("personne");
        }
        return $this->render('personne/add.html.twig', [
            "formUI" => $form->createView()
        ]);
    }
}
