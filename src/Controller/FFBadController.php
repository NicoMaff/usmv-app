<?php

namespace App\Controller;

use App\Entity\FFBadStat;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FFBadController extends AbstractController
{
    #[Route('/ffbad', name: 'app_ffbad_index')]
    public function index(UserRepository $userRepository): Response
    {
        $data = json_decode(file_get_contents("https://api.ffbad.org/club/?TokenClub=30908181&Mode=smart"));
        // dd($data[0]);
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

            // $user = $userRepository->findBy(["lastName" => "MEMBER", "firstName" => "Member"]);
            $user = $userRepository->findBy(["lastName" => $stat->getLastName(), "firstName" => $stat->getFirstName()]);
            if ($user) {
                $stat->setUser($user[0]);
            }

            dd($stat, $user);
        }
        dd();

        return $this->render('ff_bad/index.html.twig', [
            "stats" => $data
        ]);
    }
}
