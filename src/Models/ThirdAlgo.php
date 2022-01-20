<?php

namespace App\Models;

use App\Core\Arrays;
use App\Core\DirsFiles;
use App\Core\Telegram;
use App\Entity\SignalsData;

class ThirdAlgo extends Algos
{

    public function goAlgo()
    {
        $this->repo = $this->entityManager->getRepository(SignalsData::class);
        $thirds = $this->getThirds();
        if (Arrays::checkArr($thirds)) {
            foreach ($thirds as $oneThird) {
                $allData = $this->getFirstSecond($oneThird->getSignalTime(), $oneThird->getSignalCurrencies());
                if (!$allData) {
                    continue;
                }
                $allData["third"] = $oneThird;
                $signalData = explode("::", $oneThird->getSignalData());
                $urgent = "";

                $currencies = $oneThird->getSignalCurrencies();
                if($currencies == "XAUUSD"){
                    if($signalData[0] == "UP") {
                        $currencies = "Золото\n(AUDUSD ⬆️⬆️⬆️️)";
                    } else {
                        $currencies = "Золото\n(AUDUSD ⬇️⬇️⬇️)";
                    }
                }
                if($currencies == "XBRUSD" || $currencies == "BRTUSD"){
                    if($signalData[0] == "UP") {
                        $currencies = "Нефть\n(USDCAD ⬆️⬆️⬆️️)";
                    } else {
                        $currencies = "Нефть\n(USDCAD ⬇️⬇️⬇️)";
                    }
                }

                if($signalData[0] == "UP"){
                    exec("nohup php /var/www/html/trading/bin/console trading ".$currencies." UP ".$oneThird->getSignalTime()."  > ".$this->kernel->getProjectDir()."/TradingLog/".$currencies."_".$oneThird->getSignalTime().".txt &");
                } else {
                    exec("nohup php /var/www/html/trading/bin/console trading ".$currencies." DOWN ".$oneThird->getSignalTime()."  > ".$this->kernel->getProjectDir()."/TradingLog/".$currencies."_".$oneThird->getSignalTime().".txt &");
                }
                Telegram::sendOrderMessage($currencies." ".$oneThird->getSignalTime());

                if(intval($signalData[1]) == -165 && intval($signalData[2]) == 570 && $signalData[0] == "UP" && $currencies != "XAUUSD" && $currencies != "XBRUSD"){
                    $urgent = "\n 🔴🔴🔴🔴🔴🔴️ \n";
                }
                $message = $urgent. "<b>🟪 Третий алгоритм 🟪</b>\n\n";
                $message .= "<i>".date("d.m.Y H:i:s", $oneThird->getSignalTime() / 1000). "</i>\nВалюта: <b>".$currencies."</b>\n\n";

                foreach ($allData as $graphNum => $oneSignalData){
                    $message .= MessageView::convertToMessage($graphNum, $oneSignalData);
                }
                $message .= "\n<b>🟢 Длина хвоста: ".intval($signalData[5])."</b>";
                if(!file_exists($this->kernel->getProjectDir() . "/Results/" . $oneThird->getSignalCurrencies() . "_" . $allData["third"]->getSignalTime())) {
                    DirsFiles::createWriteFile($this->kernel->getProjectDir() . "/Results/" . $oneThird->getSignalCurrencies() . "_" . $allData["third"]->getSignalTime(), "w+", serialize($allData));
                }

                Telegram::sendMessage($message);

                foreach ($allData as $oneSignalData) {
                    $this->entityManager->remove($oneSignalData);
                    $this->entityManager->flush();
                }
            }
        }
    }
//"||UP::" + str.tostring(array.get(needValues, 2)) + "::" + str.tostring(array.get(needValues, 1)) + "::" + str.tostring(array.get(timeArr, 1)) + "::" + str.tostring(array.get(timeArr, 2)) + "::" + str.tostring(allCornerLengthUP), alert.freq_once_per_bar)

    private function getFirstSecond($time, $currencies){
        $first = "";
        $second = $this->repo->findOneBy([
            "signal_type" => "i2s1s2",
            "signal_time" => $time,
            "signal_currencies" => $currencies
        ]);

        if(!empty($second)){
            $first = $this->repo->findOneBy([
                "signal_type" => "i1s3",
                "signal_time" => $time,
                "signal_currencies" => $currencies
            ]);
        }
        if(empty($first) || empty($second)){
            return false;
        }
        return array(
            "first" =>  $first,
            "second" => $second
        );
    }

    private function getThirds(){
        return $this->repo->findBy([
            "signal_type" => "i3s3"
        ]);
    }
}