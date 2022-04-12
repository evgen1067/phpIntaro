<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
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
    #[Route('/registration', name: 'app_registration')]
    public function index(): Response
    {
        $user = new User();
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        return $this->render('registration/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/registration/send', name: 'app_send_registration')]
    public function registrationUser(ManagerRegistry $doctrine): Response
    {
        $name = $_POST['name'];
        $phone = $_POST['tel'];
        $email = $_POST['email'];
        $password = $_POST['pass'];

        /**
         * проверяю, что такого mail еще нет, иначе регистрирую
         */
        $emailExist = $doctrine
            ->getRepository(User::class)
            ->findOneBy(['mail' => $email]);

        if ($emailExist) {
            return new Response(json_encode([
                'msg' => 'Email уже зарегистрирован',
                'status' => false
            ]));
        } else {
            $entityManager = $doctrine->getManager();

            $user = new User();
            $password = $this->hasher->hashPassword($user, $password);
            $user
                ->setName($name)
                ->setMail($email)
                ->setPhone($phone)
                ->setPassword($password);

            $entityManager->persist($user);

            $entityManager->flush();

            $_SESSION['user'] = $user;

            return new Response(json_encode([
                'msg' => 'Вы успешно зарегистрированы',
                'status' => true,
            ]));
        }
    }
}
