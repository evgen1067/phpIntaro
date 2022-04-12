<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{
    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/edit/{id}', name: 'app_edit')]
    public function index(int $id, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        $book = $doctrine
            ->getRepository(Book::class)
            ->findOneBy([
                'id' => $id
            ]);

        return $this->render('edit/index.html.twig', [
            'user' => $user,
            'book' => $book,
        ]);
    }

    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     * @throws Exception
     */
    #[Route('/editing/book/{id}', name: 'app_edit_book')]
    public function edit(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $book = $entityManager->getRepository(Book::class)->findOneBy(['id' => $id]);

        $dateRead = new DateTime($_POST['date']);

        $book
            ->setName($_POST['name'])
            ->setAuthor($_POST['author'])
            ->setDateRead($dateRead);

        # проверка что удаляли постер
        if (isset($_POST['editImage'])) {
            $book->setCover(null);
        }

        # проверка что удаляли файл
        if (isset($_POST['editFile'])) {
            $book->setFile(null);
        }

        # если загрузили новый постер
        if (isset($_FILES['editImage'])) {
            $pathImage = 'uploads/' . uniqid() . $_FILES['editImage']['name'];
            move_uploaded_file($_FILES['editImage']['tmp_name'], $pathImage);
            $book->setCover($pathImage);
        }

        # если загрузили новый файл
        if (isset($_FILES['editFile'])) {
            $pathFile = 'uploads/' . time() . $_FILES['editFile']['name'];
            move_uploaded_file($_FILES['editFile']['tmp_name'], $pathFile);
            $book->setFile($pathFile);
        }

        $entityManager->persist($book);
        $entityManager->flush();

        return new Response(json_encode([
            'msg' => 'Вы успешно отредактировали книгу',
        ]));
    }
}
