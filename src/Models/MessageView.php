<?php

namespace App\Models;

use App\Entity\SignalsData;

class MessageView
{
    static public function convertToMessage($graphNum, SignalsData $signal)
    {
        $message = "";
        $signalData = explode("::", $signal->getSignalData());
        if($signalData[0] == "DOWN"){
            $signalData[0] = "🔻🔻🔻️ Понижение";
        }
        if($signalData[0] == "UP"){
            $signalData[0] = "⬆️⬆️⬆️ Повышение";
        }
        switch ($graphNum){
            case "first":
                $message  .= "<b>Первый индикатор</b>\n";
                break;
            case "second":
                $message  .= "<b>Второй индикатор</b>\n";
                break;
            case "third":
                $message  .= "<b>Третий индикатор</b>\n";
                break;
            default:
                return false;
        }
        $message .= $signalData[0]." ";
        $message .= "с ".intval($signalData[1])." до ".intval($signalData[2])."\n";
        return $message;
    }

}