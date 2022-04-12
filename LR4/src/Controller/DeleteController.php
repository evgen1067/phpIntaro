<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return RedirectResponse
     */
    #[Route('/delete/{id}', name: 'app_delete')]
    public function index(int $id, ManagerRegistry $doctrine): RedirectResponse
    {
        $book = $doctrine
            ->getRepository(Book::class)
            ->findOneBy([
                'id' => $id
            ]);

        $doctrine
            ->getRepository(Book::class)
            ->remove($book);

        return $this->redirectToRoute('app_home');
    }
}
