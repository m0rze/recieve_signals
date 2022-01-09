<?php

namespace App\Core;

use Root\TelegramAccounts;

class Telegram
{
    static public function sendMessage($message)
    {
        foreach (TelegramAccounts::$accounts as $oneAccount) {
//            $message = self::telegram_emoji($message);
//            var_dump(urlencode($message));die();
            file_get_contents("https://api.telegram.org/bot$oneAccount[0]/sendMessage?chat_id=".$oneAccount[1]."&text=".urlencode($message)."&parse_mode=html");
        }

    }

    static function telegram_emoji($utf8emoji) {
        preg_replace_callback(
            '@\\\x([0-9a-fA-F]{2})@x',
            function ($captures) {
                return chr(hexdec($captures[1]));
            },
            $utf8emoji
        );

        return $utf8emoji;
    }
}