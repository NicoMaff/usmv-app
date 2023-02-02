<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Event;
use App\Entity\Tournament;
use App\Entity\TournamentRegistration;
use App\Entity\User;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher, UserRepository $userRepo, TournamentRepository $tournamentRepo, EntityManagerInterface $entityManager)
    {
        $this->hasher = $hasher;
        $this->userRepo = $userRepo;
        $this->tournamentRepo = $tournamentRepo;
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        // $connection = $this->entityManager->getConnection();
        // $stmt = $connection->prepare('
        // CREATE trigger addStats
        // AFTER
        // INSERT ON `user` FOR EACH ROW BEGIN
        // UPDATE ffbad_stat
        // SET user_id = new.id
        // WHERE (
        //     new.last_name = ffbad_stat.last_name
        //     and new.first_name = ffbad_stat.first_name);
        // END;
        // ');
        // $stmt->executeQuery();

        // $stmt2 = $connection->prepare("
        //     Select *
        //     from ffbad_data.ffbad_stat;
        //     insert into usmv_app.ffbad_stat (
        //         id,
        //         extraction_date,
        //         rankings_date,
        //         week_number,
        //         `year`,
        //         season,
        //         api_id,
        //         license,
        //         last_name,
        //         birth_last_name,
        //         first_name,
        //         birth_date,
        //         nationality,
        //         country,
        //         country_iso,
        //         category_global,
        //         category_short,
        //         category_long,
        //         club_reference,
        //         club_acronym,
        //         club_id,
        //         club_name,
        //         club_department,
        //         is_player_transferred,
        //         is_data_player_public,
        //         is_player_active,
        //         feather,
        //         single_cpph,
        //         single_rank_number,
        //         single_french_rank_number,
        //         single_rank_name,
        //         double_cpph,
        //         double_rank_number,
        //         double_french_rank_number,
        //         double_rank_name,
        //         mixed_cpph,
        //         mixed_rank_number,
        //         mixed_french_rank_number,
        //         mixed_rank_name,
        //         cpphsum
        //     ) (
        //         select id,
        //         extraction_date,
        //         rankings_date,
        //         week_number,
        //         `year`,
        //         season,
        //         api_id,
        //         license,
        //         last_name,
        //         birth_last_name,
        //         first_name,
        //         birth_date,
        //         nationality,
        //         country,
        //         country_iso,
        //         category_global,
        //         category_short,
        //         category_long,
        //         club_reference,
        //         club_acronym,
        //         club_id,
        //         club_name,
        //         club_department,
        //         is_player_transferred,
        //         is_data_player_public,
        //         is_player_active,
        //         feather,
        //         single_cpph,
        //         single_rank_number,
        //         single_french_rank_number,
        //         single_rank_name,
        //         double_cpph,
        //         double_rank_number,
        //         double_french_rank_number,
        //         double_rank_name,
        //         mixed_cpph,
        //         mixed_rank_number,
        //         mixed_french_rank_number,
        //         mixed_rank_name,
        //         cpphsum
        //         from ffbad_data.ffbad_stat
        //     );
        // ");
        // $stmt2->executeQuery();

        for ($i = 1; $i <= 30; $i++) {
            $article = new Article();
            $article->setTitle($faker->realText(mt_rand(50, 70)))
                ->setContent($faker->text(300))
                ->setMainImageUrl(mt_rand(1, 20) === 1 ? "assets/img/news-by-default.jpg" : "https://picsum.photos/640/480")
                ->setFirstAdditionalImageUrl(mt_rand(0, 2) === 1 ? "https://picsum.photos/640/480" : null)
                ->setSecondAdditionalImageUrl(mt_rand(0, 4) === 1 ? "https://picsum.photos/640/480" : null)
                ->setThirdAdditionalImageUrl(mt_rand(0, 9) === 1 ? "https://picsum.photos/640/480" : null)
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($article);
        }

        for ($i = 1; $i <= 20; $i++) {
            $event = new Event();
            $event
                ->setStartDate($faker->dateTimeBetween("-1 week", "+2 weeks"))
                ->setEndDate(mt_rand(1, 2) === 1 ? null : $faker->dateTimeBetween("+1 week", "+4 weeks"))
                ->setContent($faker->realText(mt_rand(40, 60)))
                ->setImageUrl("https://picsum.photos/640/480")
                ->setVisible(mt_rand(0, 3) !== 3 ? true : false)
                ->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($event);
        }

        $member = new User();
        $admin = new User();
        $superadmin = new User();
        $player = new User();

        $member
            ->setLastName("MEMBER")
            ->setFirstName("Member")
            ->setGender("male")
            ->setEmail("member@mail.com")
            ->setPassword($this->hasher->hashPassword($member, "password"))
            ->setRoles(["ROLE_MEMBER"])
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($member);

        $admin
            ->setLastName("ADMIN")
            ->setFirstName("Admin")
            ->setGender("male")
            ->setEmail("admin@mail.com")
            ->setPassword($this->hasher->hashPassword($admin, "password"))
            ->setRoles(["ROLE_ADMIN"])
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);

        $superadmin
            ->setLastName("BIG")
            ->setFirstName("Boss")
            ->setGender("male")
            ->setEmail("superadmin@mail.com")
            ->setPassword($this->hasher->hashPassword($superadmin, "password"))
            ->setRoles(["ROLE_SUPERADMIN"])
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($superadmin);

        $player
            ->setLastName("GANCI")
            ->setFirstName("Charlotte")
            ->setGender("female")
            ->setEmail("ganci@mail.com")
            ->setRoles(["ROLE_MEMBER"])
            ->setPassword($this->hasher->hashPassword($superadmin, "password"))
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($player);

        for ($i = 1; $i <= 5; $i++) {
            $tournament = new Tournament();
            $tournament
                ->setName(mt_rand(1, 3) === 1 ? null : $faker->realText(mt_rand(40, 60)))
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

            for ($j = 1; $j <= mt_rand(1, 2); $j++) {
                $registration = new TournamentRegistration();
                $registration
                    ->setUser((mt_rand(1, 5) === 1 ? (mt_rand(1, 5) === 1 ? $superadmin : $admin) : $member))
                    ->setTournament($tournament)
                    ->setParticipationSingle(mt_rand(1, 3) === 1 ? false : true)
                    ->setParticipationDouble(mt_rand(1, 3) === 1 ? false : true)
                    ->setParticipationMixed(mt_rand(1, 3) === 1 ? false : true)
                    ->setDoublePartnerName($faker->name())
                    ->setMixedPartnerName($faker->name())
                    ->setComment($faker->text(mt_rand(100, 300)));
                $manager->persist($registration);
            }
        }

        $manager->flush();
    }
}
