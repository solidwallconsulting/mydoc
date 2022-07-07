<?php

namespace App\Controller;

use App\Entity\ContactPages;
use App\Entity\Contacts;
use App\Entity\ContractPageProperty;
use App\Form\ContactPagesType;
use App\Repository\ContactPagesRepository;
use App\Repository\ContractPagePropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/web-master/contact/pages")
 */
class ContactPagesController extends AbstractController
{
    /**
     * @Route("/parent/{id}", name="contact_pages_index", methods={"GET"})
     */
    public function index(ContactPagesRepository $contactPagesRepository, Contacts $contract): Response
    {
        return $this->render('contact_pages/index.html.twig', [
            'contact_pages' => $contactPagesRepository->findBy(['contract'=>$contract]),
            'contract'=>$contract
        ]);
    }

    /**
     * @Route("/new/{id}", name="contact_pages_new", methods={"GET","POST"})
     */
    public function new(Request $request, Contacts $contract): Response
    {
        $contactPage = new ContactPages();
        $contactPage->setContract($contract);
        $contactPage->setNumber( (sizeof($contract->getContractPages()) +1) );
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contactPage);
        $entityManager->flush();


        $form = $this->createForm(ContactPagesType::class, $contactPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_pages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact_pages/new.html.twig', [
            'contact_page' => $contactPage,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/add-properity/{id}", name="add_page_contract_property", methods={"GET","POST"})
     */
    public function addProperty(Request $request, ContactPages $page, ContractPagePropertyRepository $contractPagePropertyRepository): JsonResponse
    {
        $params = $request->request;
        $method = $request->getMethod();
        
        
        if ($method === 'POST') {
            /**
             * feildName: qsq
             *  fieldID: qsqs
             */
            $feildName = $params->get('feildName');
            $fieldID = $params->get('fieldID');
            $feildType = $params->get('feildType');


            // check for old property
            $prop = $contractPagePropertyRepository->findOneBy(['page'=>$page,'feildID'=>$fieldID]);
            if ($prop == null) {
                $property = new ContractPageProperty();

                $property->setPage($page);
                $property->setFeildID($fieldID);
                $property->setFeildName($feildName);
                $property->settype($feildType);
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($property);
                $entityManager->flush();

                return $this->json(['success'=>true,'message'=>'Propriété enregistrée avec succès']);

            }else{
                return $this->json(['success'=>false,'message'=>'Cette propriété existe déjà dans cette page.']);
            }
            
            
            return $this->json( ['feildName'=>$feildName, $feildName=>$feildName] );
        }

        return $this->json(['success'=>false,'message'=>'mauvais appel']);
        
    }


    






    /**
     * @Route("/{id}", name="contact_pages_show", methods={"GET"})
     */
    public function show(ContactPages $contactPage): Response
    {
        return $this->render('contact_pages/show.html.twig', [
            'contact_page' => $contactPage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contact_pages_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContactPages $contactPage): Response
    {
        $form = $this->createForm(ContactPagesType::class, $contactPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_pages_index', ['id'=>$contactPage->getContract()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact_pages/edit.html.twig', [
            'contact_page' => $contactPage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="contact_pages_delete", methods={"POST"})
     */
    public function delete(Request $request, ContactPages $contactPage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactPage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contactPage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_pages_index', ['id'=>$contactPage->getContract()->getId()], Response::HTTP_SEE_OTHER);
    }
}
