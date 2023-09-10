<?php

namespace App\Controller\User;

use App\Entity\Addressing\UserAddress;
use App\Form\Addressing\UserAddressType;
use App\Repository\Addressing\UserAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/address')]
class AddressController extends AbstractController
{
    #[Route('/', name: 'app_user_address_index', methods: ['GET'])]
    public function index(UserAddressRepository $userAddressRepository): Response
    {
        return $this->render('user/address/index.html.twig', [
            'user_addresses' => $userAddressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userAddress = new UserAddress();
        $form = $this->createForm(UserAddressType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/address/new.html.twig', [
            'user_address' => $userAddress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_address_show', methods: ['GET'])]
    public function show(UserAddress $userAddress): Response
    {
        return $this->render('user/address/show.html.twig', [
            'user_address' => $userAddress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserAddress $userAddress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserAddressType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/address/edit.html.twig', [
            'user_address' => $userAddress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_address_delete', methods: ['POST'])]
    public function delete(Request $request, UserAddress $userAddress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userAddress->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userAddress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_address_index', [], Response::HTTP_SEE_OTHER);
    }
}
