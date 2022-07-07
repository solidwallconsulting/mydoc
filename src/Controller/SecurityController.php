<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }



    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('profile_route');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }




    /**
     * @Route("/create-account", name="app_siginup", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $error="";



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // check if email already in use
            if ($userRepository->findOneBy(['email'=>$user->getEmail()]) != null) {
                $error="Cette adresse e-mail est déjà utilisée par un autre utilisateur";
            }else{
                $user->setRoles(['ROLE_CLIENT']);
            $user->setPhotoURL('/assets/img/users/avatar.png');
            $user->setIsBlocked(false);

            $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
            }
        }


        
        

        return $this->render('security/signup.html.twig', [
            
            'form' => $form->createView(),
            'error' => $error
        ]);
    }


    
    /**
     * @Route("/account", name="profile_route")
     */
    public function profile(): Response
    {
        return $this->render('app/profile.html.twig', []);
    }


    
    /**
     * @Route("/account/update/profile", name="edit_account_data_route", methods={"GET","POST"})
     */
    public function profile_edit_route(Request $request ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user,['isEditing'=>true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_route', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('app/edit-profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }



    
    /**
     * @Route("/account/update/profile-picture-update", name="update_profile_picture", methods={"GET","POST"})
     */
    public function update_profile_picture(Request $request ): Response
    {
        $user = $this->getUser();



        $parameters = $request->request;
        $files = $request->files;
        $method = $request->getMethod();

 
        if ($method == 'POST') {  
            

            $image = $files->get('photo'); 


            // save the user
            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try { 

                    
                    $image->move('assets/img/users',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
 
                $user->setPhotoURL('/assets/img/users/'.$newFilename);
                $this->getDoctrine()->getManager()->flush();
            }
            

          
        } 
        

         
        return $this->redirectToRoute('profile_route', [], Response::HTTP_SEE_OTHER);
            

            
         

        
    }


    

    




    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
