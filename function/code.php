<?php

function clean($message) {
    return htmlspecialchars(trim($message));
}

function random_strings($length_of_string) {
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZIJKLMNOP28427JEKUBKQOPDH';
    $str_shuffled = str_shuffle($str_result);
    return substr($str_shuffled, 0, $length_of_string);
}

if ((strpos($message, "/code") === 0) || (strpos($message, "!code") === 0) || (strpos($message, ".code") === 0)) {
    $owners = file_get_contents('Database/owner.txt');
    $admins = explode("\n", $owners);
    if (!in_array($userId, $admins)) {
        sendMessage($chatId, "You are Not ADMIN ! ", $messageId);
    } else {
        $command = substr($message, 6);
        $command = clean($command);

        if ($command == ' ' || $command == '') {
            $expiryDays = 1;
            $amountOfCodes = 1;
        } else {
            $cmdExplode = explode('-', $command); // assuming the format is {expiry_days}-{amount_of_codes}
            $expiryDays = (int)$cmdExplode[0];
            $amountOfCodes = (int)$cmdExplode[1];
        }

        $expiryDate = date('d M Y', strtotime("+$expiryDays days")); // setting the key expiry date as provided by the owner

        $credt = array();
        while ($amountOfCodes > 0) {
            $rnds = 'MASH-' . random_strings(4) . '-' . random_strings(4) . '-' . random_strings(4);
            $credt[] = $rnds;
            $amountOfCodes = $amountOfCodes - 1;
        }

        foreach ($credt as $code) {
            $credtf = fopen('Database/codes.txt', 'a');
            fwrite($credtf, "[$code|$expiryDays],\n");
            fclose($credtf);
            $formattedCode = "<code>$code</code>";
            $messageToSend = urlencode(
                "↳ 2𝙣𝙙 𝙇𝙄𝙉𝙀𝙍 𝙆𝙀𝙔 ↲

<b>𖤐 BOT </b>- <a href='t.me/MASHLECHKBOT'>ϟ 𝙈𝘼𝙎𝙃𝙇𝙀 𝘾𝙃𝙆  ϟ</a>
<b>𖤐 RANK - <code>2 𝙇𝙄𝙉𝙀𝙍</code>
𖤐  𝙆𝙀𝙔 - <code>$formattedCode</code>
𖤐 ACTIVATION CODE - <code>$expiryDays</code>
𖤐 EXPIRE DATE - <code> $expiryDate</code>

𖤐 USAGE -<code>/magic $formattedCode</code>

</b>"
            );
            sendMessage($chatId, $messageToSend, $messageId); // using $messageId instead of $message_id_1
        }
    }
}
?>
