<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Event;
use App\Entity\Tournament;
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
            $article->setTitle($faker->realText(mt_rand(50, 70)))
                ->setContent($faker->text(300))
                ->setUrlImageMain(mt_rand(1, 20) === 1 ? "assets/img/news-by-default.jpg" : "https://picsum.photos/640/480")
                ->setUrlImageAdditional1(mt_rand(0, 2) === 1 ? "https://picsum.photos/640/480" : null)
                ->setUrlImageAdditional2(mt_rand(0, 4) === 1 ? "https://picsum.photos/640/480" : null)
                ->setUrlImageAdditional2(mt_rand(0, 9) === 1 ? "https://picsum.photos/640/480" : null)
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($article);
        }

        for ($i = 1; $i <= 20; $i++) {
            $event = new Event;
            $event
                ->setStartDate($faker->dateTimeBetween("-1 week", "+2 weeks"))
                ->setEndDate(mt_rand(1, 2) === 1 ? null : $faker->dateTimeBetween("+1 week", "+4 weeks"))
                ->setContent($faker->realText(mt_rand(40, 60)))
                ->setUrlImage("https://picsum.photos/640/480")
                ->setIsVisible(mt_rand(0, 3) !== 3 ? true : false)
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

        for ($i = 1; $i <= 10; $i++) {
            $tournament = new Tournament();
            $tournament
                ->setName(mt_rand(1, 3) === 1 ? $faker->realText(mt_rand(40, 60)) : null)
                ->setCity($faker->city())
                ->setStartDate($faker->dateTimeBetween("now", "+6 months"))
                ->setEndDate(mt_rand(1, 5) === 1 ? $faker->dateTimeInInterval($tournament->getStartDate(), "+2 days") : $faker->dateTimeInInterval($tournament->getStartDate(), "+1 day"))
                ->setSeason("2022/2023")
                ->setStandardPrice1(mt_rand(15, 17))
                ->setStandardPrice2(mt_rand(5, 8))
                ->setStandardPrice3(mt_rand(1, 10) === 1 ? mt_rand(4, 6) : null)
                ->setRegistrationClosingDate($faker->dateTimeInInterval($tournament->getStartDate(), "-1 month"))
                ->setRandomDraw($faker->dateTimeInInterval($tournament->getStartDate(), "-2 weeks"))
                ->setEmailContact($faker->email())
                ->setTelContact($faker->phoneNumber())
                ->setRegistrationMethod(mt_rand(1, 3) === 1 ? (mt_rand(1, 2) === 1 ? "Courrier" : "Autres") : "Badnet")
                ->setPaymentMethod(mt_rand(1, 3) === 1 ? (mt_rand(1, 3) === 1 ? "Chèque" : "CB") : "Badnet")
                ->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($tournament);
        }

        $manager->flush();
    }
}
