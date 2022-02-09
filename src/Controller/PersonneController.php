<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
        /*1.On appelle la méthode createForm
        2 On spécifie le constructeur de form et nom d’objet qui va assurer le binding
        */
        $form = $this->createForm(PersonneType::class, $personne);
        /*                
        5 On passe la méthode createView () du formulaire à la vue */
        //on récupère les données de formulaire
        $form->handleRequest($request);
        //si le formulaire a été envoyé avec la méthode POST
        if ($form->isSubmitted() && $form->isValid()) {
            // var_dump($form->getData());
            $personne = $form->getData();
            $em->persist($personne);
            foreach ($personne->getSports() as $sport) {
                $em->persist($sport);
            }
            $em->flush();
            //on redirige vers /personne
            return $this->redirectToRoute("personne");
        }
        return $this->render('personne/add.html.twig', [
            "formUI" => $form->createView()
        ]);
    }

    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('test.greta92@gmail.com')
            ->to('soupramanien@baobab-ingenierie.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return new Response("mail envoyé avec succès");
    }
}
