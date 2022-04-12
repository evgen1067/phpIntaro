<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @return Response
     */
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        $user = new User();
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        return $this->render('auth/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/auth/login', name: 'app_auth_login')]
    public function login(ManagerRegistry $doctrine): Response
    {
        /**
         * нахожу нужного по mail
         */
        $user = $doctrine
            ->getRepository(User::class)
            ->findOneBy([
                'mail' => $_POST['email']
            ]);

        /**
         * если такого нет - ошибка
         */
        if ($user == null) {
            return new Response(json_encode([
                'status' => false,
                'field' => 'email',
                'msg' => 'Такой email не найден!'
            ]));
        }

        /**
         *  сравниваю отправленный пароль и сверяю с хешем
         */
        $plaintextPassword = $_POST['pass'];

        if (!$this->hasher->isPasswordValid($user, $plaintextPassword)) {
            return new Response(json_encode([
                'status' => false,
                'field' => 'pass',
                'msg' => 'Пароль введен неверно!'
            ]));
        } else {
            $_SESSION['user'] = $user;
            return new Response(json_encode([
                'status' => true,
                'msg' => 'Вы успешно авторизованы!'
            ]));
        }
    }
}
