<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        for ($i = 1; $i <= 30; $i++) {
            $article = new Article();
            $article->setTitle($faker->realText(mt_rand(10, 15)))
                ->setContent($faker->text(300))
                ->setUrlImageMain(mt_rand(1, 20) === 1 ? "assets/img/news-by-default.jpg" : $faker->imageURL(640, 480))
                ->setUrlImageAdditional1(mt_rand(0, 2) === 1 ? $faker->imageURL(640, 480) : null)
                ->setUrlImageAdditional2(mt_rand(0, 4) === 1 ? $faker->imageURL(640, 480) : null)
                ->setUrlImageAdditional2(mt_rand(0, 9) === 1 ? $faker->imageURL(640, 480) : null)
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($article);
        }

        for ($i = 1; $i <= 20; $i++) {
            $event = new Event;
            $event
                ->setStartDate($faker->dateTimeBetween("-1 week", "+2 weeks"))
                ->setEndDate(mt_rand(1, 2) === 1 ? null : $faker->dateTimeBetween("+1 week", "+4 weeks"))
                ->setContent($faker->realText(20))
                ->setUrlImage($faker->imageURL(640, 480))
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($event);
        }

        $member = new User;
        $admin = new User;
        $superadmin = new User;

        $member
            ->setLastName("MEMBER")
            ->setFirstName("Member")
            ->setGender("masculin")
            ->setEmail("member@mail.com")
            ->setPassword($this->hasher->hashPassword($member, "password"))
            ->setRoles(["ROLE_MEMBER"])
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($member);

        $admin
            ->setLastName("ADMIN")
            ->setFirstName("Admin")
            ->setGender("masculin")
            ->setEmail("admin@mail.com")
            ->setPassword($this->hasher->hashPassword($admin, "password"))
            ->setRoles(["ROLE_ADMIN"])
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);

        $superadmin
            ->setLastName("BIG")
            ->setFirstName("Boss")
            ->setGender("masculin")
            ->setEmail("superadmin@mail.com")
            ->setPassword($this->hasher->hashPassword($superadmin, "password"))
            ->setRoles(["ROLE_SUPERADMIN"])
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($superadmin);

        $manager->flush();
    }
}
