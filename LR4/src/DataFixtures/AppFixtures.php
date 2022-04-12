<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $password = $this->hasher->hashPassword($user, '123456');
        $user
            ->setName('Евгений Богомолов')
            ->setPhone('89508035631')
            ->setMail('bogomolov.evgen1067@mail.ru')
            ->setPassword($password);
        $manager->persist($user);

        for ($i = 1; $i < 51; $i++) {
            $book = new Book();
            $book
                ->setName('Книга ' . $i)
                ->setAuthor('Автор ' . $i)
                ->setAddedUser($user)
                ->setDateRead(new \DateTime('now + ' . $i . ' hours'));
            $manager->persist($book);
        }

        $manager->flush();
    }
}
