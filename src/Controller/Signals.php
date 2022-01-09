<?php

namespace App\Controller;

use App\Core\Arrays;
use App\Core\CheckWorkTime;
use App\Entity\SignalsData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class Signals extends AbstractController
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Route("/getsignal", methods={"POST"})
     */
    public function getSignal(Request $request, EntityManagerInterface $entityManager)
    {
        $dataToSave = explode("||", $request->getContent());

        if(Arrays::checkArr($dataToSave)) {

            $currencies = $dataToSave[2];
            $currencies = explode("+", $currencies);
            $currencies = $currencies[0];
            if(!CheckWorkTime::checkWorkTime($currencies, $dataToSave[0])){
                return new Response("good");
            }
            $newData = new SignalsData();
            $newData->setSignalTime($dataToSave[0]);
            $newData->setSignalType($dataToSave[1]);
            $newData->setSignalCurrencies($currencies);
            $newData->setSignalData($dataToSave[3]);

            $entityManager->persist($newData);
            $entityManager->flush();
        }
        return new Response("good");
    }
}