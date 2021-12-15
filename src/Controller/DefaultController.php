<?php

namespace App\Controller;

use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepository,
        private FormFactoryInterface $formFactory,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/', name: 'default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'tasks' => $this->taskRepository->findAll(),
        ]);
    }

    #[Route('/new')]
    public function new(Request $request): Response
    {
        $form = $this->formFactory->create(TaskType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('default');
        }

        return $this->renderForm('default/new.html.twig', [
            'form' => $form,
        ]);
    }
}
