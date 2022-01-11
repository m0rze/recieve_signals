<?php

namespace App\Command;

use App\Core\Arrays;
use App\Core\DirsFiles;
use App\Core\Telegram;
use App\Entity\SignalsData;
use App\Models\FirstAlgo;
use App\Models\SecondAlgo;
use App\Models\ThirdAlgo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CronCommand extends Command
{
    protected static $defaultName = 'cron';
    protected static $defaultDescription = 'Add a short description for your command';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(string $name = null, EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if($this->checkInWork()){
            $this->resetInWork();
            die();
        }
        $this->createInWork();
        $this->whileWork();

        return Command::SUCCESS;
    }

    private function whileWork(){
        $sendCheckTime = 0;
        while (true) {
            if(!$this->checkInWork()){
                die();
            }
            $this->createInWork();
            $this->deleteOld();
            $checkWorkTime = file_get_contents($this->kernel->getProjectDir()."/Temp/inwork");
            if($checkWorkTime - $sendCheckTime > 3600 || empty($sendCheckTime)){
                Telegram::sendMessage(date("d.m.Y H:i:s")."\n 🆗 В норме 🆗");
                $sendCheckTime = time();
            }
            sleep(3);


            $firstAlgo = new FirstAlgo($this->kernel, $this->entityManager);
            $firstAlgo->goAlgo();

            $secondAlgo = new SecondAlgo($this->kernel, $this->entityManager);
            $secondAlgo->goAlgo();

            $thirdAlgo = new ThirdAlgo($this->kernel, $this->entityManager);
            $thirdAlgo->goAlgo();


        }
    }

    private function deleteOld(){
        $allSignals = $this->entityManager->getRepository(SignalsData::class)->findAll();
        if(Arrays::checkArr($allSignals)){
            foreach ($allSignals as $oneSignal){
                $signalTime = $oneSignal->getSignalTime() / 1000;
                if(time() - $signalTime > 600){
                    $this->entityManager->remove($oneSignal);
                    $this->entityManager->flush();
                }
            }
        }
    }

    private function checkInWork(){
        if(file_exists($this->kernel->getProjectDir()."/Temp/inwork")){
            return true;
        }
        return false;
    }

    private function createInWork(){
        DirsFiles::createWriteFile($this->kernel->getProjectDir()."/Temp/inwork", "w+", time());
    }

    private function resetInWork(){
        if(file_exists($this->kernel->getProjectDir()."/Temp/inwork")){
            $inWorkTime = file_get_contents($this->kernel->getProjectDir()."/Temp/inwork");
            if(!empty($inWorkTime)){
                if(time() - $inWorkTime > 60){
                    $this->deleteInWork();
                }
            }
        }
    }

    private function deleteInWork(){
        if(file_exists($this->kernel->getProjectDir()."/Temp/inwork")){
            unlink($this->kernel->getProjectDir()."/Temp/inwork");
        }
    }
}
