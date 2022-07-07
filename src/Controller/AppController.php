<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Contacts;
use App\Entity\Message;
use App\Entity\PrintHistory;
use App\Form\MessageType;
use App\Repository\CategoryRepository;
use App\Repository\ContactsRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppController extends AbstractController
{

    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }



    
 

    /**
     * @Route("/redirection-auth-route", name="redirection_auth_route")
     */
    public function redirectionAfterLogin(): Response
    {
        
        if ( $this->getUser()->getRoles()[0] == 'ROLE_ADMIN' ) {
            return $this->redirectToRoute('web_master_route', [], Response::HTTP_SEE_OTHER);
        }else{
            return $this->redirectToRoute('profile_route', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/all-categories", name="all_categories_route")
     */
    public function allCategories(CategoryRepository $categoryRepository): Response
    {

        return $this->render('app/all-categories.html.twig', [
             'categories'=>$categoryRepository->findAll()
        ]);
    }

    
    /**
     * @Route("/contracts-category/{id}", name="contracts_by_category")
     */
    public function contracts_by_category(Category $category): Response
    {

        return $this->render('app/contracts-category.html.twig', [
             'category'=>$category
        ]);
    }


   


    /**
     * @Route("/account/generate-ontract/{id}", name="generate_ontract_route")
     */
    public function generateContract(CategoryRepository $categoryRepository,Contacts $contract): Response
    {
 

        return $this->render('app/generate-contract.html.twig', [
             'contract'=>$contract
        ]);
    }




    


    /**
     * @Route("/", name="main_route")
     */
    public function index(): Response
    {
        
        return $this->render('app/index.html.twig', [
             
        ]);
    }



    /**
     * @Route("/web-master", name="web_master_route")
     */
    public function web_master_route(UserRepository $userRepository ): Response
    { 
        $members = $userRepository->findAll(); 


   
        return $this->render('admin/index.html.twig', [
            "members"=>$members, 
            
            
        ]);
    }

 

        
    /**
     * @Route("/forget-password", name="forget_password_route", methods={"GET","POST"})
     */
    public function forgetPassword(Request $request,UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $errorMessage='';
        $successMessage='';
        

        if ($request->getMethod() == 'POST') {
            $email = trim($request->request->get('email'));
            $user = $userRepository->findOneBy(['email'=>$email]);
            if ($user != null) {

                $domaine = $request->server->get('HTTP_HOST');
                $token = uniqid($email,true);

                $blocEmail = '
                <h3>Mot de passe oublié?</h3> 
                <p>Vous nous avez dit que vous avez oublié votre mot de passe. Définissez-en un nouveau en suivant le lien ci-dessous.</p>
                <a href="https://'.$domaine.'/new-password?token='.$token.'">réinitialiser le mot de passe</a>
                <hr/>';
                $blocEmail.="<p>Si vous n'avez pas besoin de réinitialiser votre mot de passe, ignorez simplement cet e-mail. Votre mot de passe ne changera pas.</p>";                
 

                $user->setResetPasswordToken($token);

                $this->getDoctrine()->getManager()->flush();


                // send verification mail
                $emailMessage = (new Email())
                ->from('support@sougna.com')
                ->to($email) 
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Mot de passe oublié')
                ->html($blocEmail);

            

            try {
                $mailer->send($emailMessage);
                $successMessage="un e-mail de vérification a été envoyé avec succès à ".$email.", veuillez vérifier votre boîte de réception.";
             
            } catch (\Throwable $th) {
                
            }
 
               
            }else{
                $errorMessage= 'Mauvaise adresse e-mail, veuillez réessayer';
            }
        
        }



        return $this->render('app/forget-password.html.twig', [ 
            'errorMessage'=>$errorMessage,
            'successMessage'=>$successMessage
       ]);
    }


    

    /**
     * @Route("/new-password", name="new_password_route", methods={"GET","POST"})
     */
    public function newPassword(Request $request,UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $errorMessage='';
        $successMessage=''; 

        if ($request->getMethod() == 'POST') {

            $token = $request->query->get('token'); 
            $newPasswordTXT = trim($request->request->get('new-password'));
            $user = $userRepository->findOneBy(['resetPasswordToken'=>$token]);

             
           if ($user != null) {
              
            $user->setPassword($this->encoder->encodePassword($user,$newPasswordTXT));
            $user->setResetPasswordToken(null);

            $this->getDoctrine()->getManager()->flush();

            $successMessage ='Votre mot de passe est mis à jour avec succès.';
            
           }else{
               $errorMessage ='On dirait que vous utilisez un ancien lien.';
           }
        
        }



        return $this->render('app/new-password.html.twig', [ 
            'errorMessage'=>$errorMessage,
            'successMessage'=>$successMessage
       ]);
    }


    /**
     * @Route("/faq", name="faq_route")
     */
    public function faq_route( ): Response
    {
        return $this->render('app/faq.html.twig', [ 
        ]);
    }


 

    /**
     * @Route("/contact", name="contact_route")
     */
    public function contact_us_route(Request $request,UserRepository $userRepository ): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSentAt(new DateTime());
            $message->setIsSeen(false);
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('contact_route', ["message"=>"Votre message a été envoyé avec succès, nous vous contacterons dans les plus brefs délais."], Response::HTTP_SEE_OTHER);
        }

         
        $message="";

        if ($request->query->get('message') != null) {
            $message = $request->query->get('message');
        }


        return $this->renderForm('app/contact.html.twig', [ 
            'message' => $message,
            'form' => $form,
            "message"=>$message
        ]);
    }



    
    
 
    /**
     * @Route("/account/save-document", name="save_document_ajax", methods={"POST"})
     */
    public function deleteViaAJAX(Request $request, ContactsRepository $contactsRepository ): Response
    {
        try {
             
            $params = $request->request;
            $content = $params->get('content');
            $docID = $params->get('docID');
            
            $contract = $contactsRepository->findOneBy(['id'=>$docID]);

            $history = new PrintHistory();
            $history->setContent($content);
            $history->setPrintDate(new DateTime());
            $history->setUser($this->getUser());
            $history->setContract($contract);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($history);
            $entityManager->flush();

            return $this->json(['success'=>true,'content'=>$content]);
            
        } catch (\Throwable $th) {
            return $this->json(['success'=>false]);
        }


        
    }


}
