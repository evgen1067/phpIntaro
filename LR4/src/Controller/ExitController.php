<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExitController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/exit', name: 'app_exit')]
    public function index(): Response
    {
        $_SESSION['user'] = null;
        return new Response(json_encode(['msg' => 'Вы успешно вышли']));
    }
}
