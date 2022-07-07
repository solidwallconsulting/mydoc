<?php

namespace App\Controller;

use App\Entity\ContractPageProperty;
use App\Form\ContractPagePropertyType;
use App\Repository\ContractPagePropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/web-master/contract-properties")
 */
class ContractPagePropertyController extends AbstractController
{
    /**
     * @Route("/", name="contract_page_property_index", methods={"GET"})
     */
    public function index(ContractPagePropertyRepository $contractPagePropertyRepository): Response
    {
        return $this->render('contract_page_property/index.html.twig', [
            'contract_page_properties' => $contractPagePropertyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="contract_page_property_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contractPageProperty = new ContractPageProperty();
        $form = $this->createForm(ContractPagePropertyType::class, $contractPageProperty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contractPageProperty);
            $entityManager->flush();

            return $this->redirectToRoute('contract_page_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contract_page_property/new.html.twig', [
            'contract_page_property' => $contractPageProperty,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="contract_page_property_show", methods={"GET"})
     */
    public function show(ContractPageProperty $contractPageProperty): Response
    {
        return $this->render('contract_page_property/show.html.twig', [
            'contract_page_property' => $contractPageProperty,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contract_page_property_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContractPageProperty $contractPageProperty): Response
    {
        $form = $this->createForm(ContractPagePropertyType::class, $contractPageProperty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contract_page_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contract_page_property/edit.html.twig', [
            'contract_page_property' => $contractPageProperty,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="contract_page_property_delete", methods={"POST"})
     */
    public function delete(Request $request, ContractPageProperty $contractPageProperty): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contractPageProperty->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contractPageProperty);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contract_page_property_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/delete/{id}", name="contract_page_property_delete_ajax", methods={"POST"})
     */
    public function deleteViaAJAX(Request $request, ContractPageProperty $contractPageProperty): Response
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contractPageProperty);
            $entityManager->flush();

            return $this->json(['success'=>true]);
            
        } catch (\Throwable $th) {
            return $this->json(['success'=>false]);
        }


        
    }

}
