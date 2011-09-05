<?php

namespace app\models;

class ApontadorExtras
{

    public function getPlayerProfile($userId = null)
    {
        $url = "http://www.apontador.com.br/player/get/{$userId}";
        $playerProfileJson = $this->simpleHttpGet($url);
        $jsonObject = json_decode($playerProfileJson);
        $playerProfile = new PlayerProfile($jsonObject);
        
        return $playerProfile;
    }

    private function simpleHttpGet($url = null)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

}
