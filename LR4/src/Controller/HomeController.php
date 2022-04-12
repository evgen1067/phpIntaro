<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {
        /**
         * количество записей на странице
         */
        $booksOnPage = 4;

        /**
         * текущая страница
         */
        $currentPage = $request->query->getInt('page', 1);

        /**
         * массив записей до пагинации
         */
        $booksBeforePaginate = $doctrine
            ->getRepository(Book::class)
            ->findBy([], ['dateRead' => 'DESC']);

        /**
         * флаг предыдущей страницы
         */
        $hasPreviousPage = false;
        if ($currentPage != 1) {
            $hasPreviousPage = true;
        }

        /**
         * флаг следующей страницы
         */
        $hasNextPage = false;
        if ((count($booksBeforePaginate) - $booksOnPage * $currentPage) > 0) {
            $hasNextPage = true;
        }

        /**
         * пагинация записей
         */
        $books = $paginator->paginate(
            $booksBeforePaginate,
            $currentPage,
            $booksOnPage
        );

        $user = new User();
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        /**
         * рендерим шаблон главной страницы
         */
        return $this->render('home/index.html.twig', [
            'books' => $books,
            'hasPreviousPage' => $hasPreviousPage,
            'currentPage' => $currentPage,
            'hasNextPage' => $hasNextPage,
            'user' => $user
        ]);
    }
}
