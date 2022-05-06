<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Form\CarsType;
use App\Repository\CarsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cars')]
class CarsController extends AbstractController
{
    #[Route('/', name: 'app_cars_index', methods: ['GET'])]
    public function index(CarsRepository $carsRepository): Response
    {
        return $this->render('cars/index.html.twig', [
            'cars' => $carsRepository->findAll(),
        ]);
    }
    #[Route('/tab/{id}', name: 'tab', methods: ['GET'])]
    public function tab(CarsRepository $carsRepository, $id): Response
    {
        return $this->render('main/tab.html.twig', [
            'car' => $carsRepository->find($id),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_cars_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarsRepository $carsRepository): Response
    {
        $car = new Cars();
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);
        $directory='./img/';
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get("picture")->getData();
            if($file){
                $file->move($directory, $file->getClientOriginalName());
                $car->setPicture($directory.$file->getClientOriginalName());
            }
            $carsRepository->add($car);
            return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cars/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_show', methods: ['GET'])]
    public function show(Cars $car): Response
    {
        return $this->render('cars/show.html.twig', [
            'car' => $car,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_cars_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cars $car, CarsRepository $carsRepository): Response
    {
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carsRepository->add($car);
            return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cars/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_cars_delete', methods: ['POST'])]
    public function delete(Request $request, Cars $car, CarsRepository $carsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $carsRepository->remove($car);
        }

        return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
