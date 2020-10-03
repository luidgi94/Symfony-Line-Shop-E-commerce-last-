<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form_register = $this->createForm(RegistrationType::class, $user); //Création du form Symfony à partir de la classe

        $form_register->handleRequest($request);

        
        $form = $this->createFormBuilder()
        ->add('search', TextType::class)
        ->add('send', SubmitType::class, ['label' => 'Rechercher'])
        ->getForm();
        
        if ($form_register->isSubmitted() && $form_register->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword()); //Encodage du mot de passe pour le hacher...
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->render('security/login.html.twig', [
                'message' => 'Vous vous êtes bien inscrit sur le site !', 
                'form' => $form->createView()
            ]);
        }

        return $this->render('security/registration.html.twig', [
            'form_register' =>$form_register->createView(),
            'form' =>$form->createView() //Affichage du form Symfony
        ]);
    }


     /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();   
        $lastUsername = $authenticationUtils->getLastUsername();// last username entered by the user
        $form = $this->createFormBuilder()
        ->add('search', TextType::class)
        ->add('send', SubmitType::class, ['label' => 'Rechercher'])
        ->getForm();
        if ($error !=null){
            return $this->render('security/login.html.twig', ['form' => $form->createView(),'last_username' => $lastUsername, 'message'=>'votre identifiant ou mot de passe incorrect','error' => $error]);
        }
        return $this->render('security/login.html.twig', ['form' => $form->createView(), 'message'=>'','last_username' => $lastUsername,'error' => $error]);
    }


    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        //c'est important d'avoir la fonction 
        $session = new Session(new NativeSessionStorage(), new AttributeBag());
        $token = $session->remove('user_connected');
        $session->clear();
        $token->clear();
        return $this->redirectToRoute('/'); // J'ai rajouter une redirection a l'acceuil 
    }
}