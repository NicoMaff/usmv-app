<?php

namespace App\Command;

use App\Entity\FFBadStat;
use App\Repository\FFBadStatRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ffbad:extract',
    description: 'Make a data extraction from the FFBaD API',
)]
class FFBadExtractCommand extends Command
{
    public function __construct(
        private FFBadStatRepository $ffbadStatRepository,
        private UserRepository $userRepository
    ) {
        $this->ffbadStatRepository = $ffbadStatRepository;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $data = json_decode(file_get_contents("https://api.ffbad.org/club/?TokenClub=30908181&Mode=smart"));

        foreach ($data as $d) {
            $stat = new FFBadStat();
            $arrayDate = explode("-", $d->DATE);
            $stat->setRankingsDate($arrayDate[2] . "/" . $arrayDate[1] . "/" . $arrayDate[0]);
            $stat
                ->setApiId($d->PER_ID)
                ->setLicense($d->PER_LICENCE)
                ->setLastName($d->PER_NOM)
                ->setBirthLastName($d->PER_NOM_JEUNE_FILLE)
                ->setFirstName($d->PER_PRENOM)
                ->setBirthDate($d->PER_NAISSANCE)
                ->setNationality($d->PAN_NOM)
                ->setCountry($d->PAY_NOM)
                ->setCountryISO($d->PAY_ISO3)
                ->setCategoryGlobal($d->JOC_NOM_GLOBAL)
                ->setCategoryShort($d->JOC_NOM_COURT)
                ->setCategoryLong($d->JOC_NOM_LONG)
                ->setClubReference($d->INS_NUMERO_CLUB)
                ->setClubAcronym($d->INS_SIGLE)
                ->setClubId($d->INS_ID)
                ->setClubName($d->INS_NOM)
                ->setClubDepartment($d->INS_NUMERO_DEPT)
                ->setFeather($d->PLUME_NOM)
                ->setSingleCPPH($d->SIMPLE_COTE_FFBAD)
                ->setSingleRankNumber($d->SIMPLE_RANG)
                ->setSingleFrenchRankNumber($d->SIMPLE_RANG_FR)
                ->setSingleRankName($d->SIMPLE_NOM)
                ->setDoubleCPPH($d->DOUBLE_COTE_FFBAD)
                ->setDoubleRankNumber($d->DOUBLE_RANG)
                ->setDoubleFrenchRankNumber($d->DOUBLE_RANG_FR)
                ->setDoubleRankName($d->DOUBLE_NOM)
                ->setMixedCPPH($d->MIXTE_COTE_FFBAD)
                ->setMixedRankNumber($d->MIXTE_RANG)
                ->setMixedFrenchRankNumber($d->MIXTE_RANG_FR)
                ->setMixedRankName($d->MIXTE_NOM)
                ->setCPPHSum($d->ThisOrder);

            if ($d->JOU_IS_MUTE === "1") {
                $stat->setIsPlayerTransferred(true);
            } else {
                $stat->setIsPlayerTransferred(false);
            }
            if ($d->IS_ACTIF === "1") {
                $stat->setIsPlayerActive(true);
            } else {
                $stat->setIsPlayerActive(false);
            }
            if ($d->PER_IS_DATA_PUBLIC === "1") {
                $stat->setIsDataPlayerPublic(true);
            } else {
                $stat->setIsDataPlayerPublic(false);
            }

            $datetime = new DateTime($d->DATE);
            $stat
                ->setWeekNumber($datetime->format("W"))
                ->setYear($arrayDate[0]);

            if (in_array($arrayDate[1], ["09", "10", "11", "12"])) {
                $stat->setSeason($arrayDate[0] . "/" . (string) (((int) $arrayDate[0]) + 1));
            } else if (in_array($arrayDate[1], ["01", "02", "03", "04", "05", "06", "07", "08"])) {
                $stat->setSeason((string) (((int) $arrayDate[0]) - 1) .  "/" . $arrayDate[0]);
            }

            // $user = $this->userRepository->findBy(["lastName" => $stat->getLastName(), "firstName" => $stat->getFirstName()]);
            // if ($user) {
            //     $stat->setUser($user[0]);
            // }

            $this->ffbadStatRepository->add($stat, true);
        }

        $io->success("The extraction has succeeded! ");

        return Command::SUCCESS;
    }
}
