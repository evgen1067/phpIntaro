<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/add', name: 'app_add')]
    public function index(): Response
    {
        $user = new User();
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        return $this->render('add/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @return RedirectResponse
     * @throws Exception
     */
    #[Route('/add/book', name: 'app_add_book')]
    public function add(ManagerRegistry $doctrine): RedirectResponse
    {
        $entityManager = $doctrine->getManager();

        $user = $_SESSION['user'];
        echo $user->getId();

        $dateRead = new DateTime($_POST['date']);

        $book = new Book();
        $book
            ->setName($_POST['name'])
            ->setAuthor($_POST['author'])
            ->setDateRead($dateRead)
            ->setAddedUser($user);

        if (isset($_FILES['image'])) {
            $pathImage = 'uploads/' . uniqid() . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $pathImage);
            $book->setCover($pathImage);
        }

        if (isset($_FILES['file'])) {
            $pathFile = 'uploads/' . time() . $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $pathFile);
            $book->setFile($pathFile);
        }

        $entityManager->merge($book);

        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
