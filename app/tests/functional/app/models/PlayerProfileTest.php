<?php

namespace app\models;

class PlayerProfileTest extends \PHPUnit_Framework_TestCase
{

    private $playerProfileJson = '{"pointsDay":700,"pointsWeek":701,"pointsMonth":1670,"pointsAll":33575,"level":{"name":"VIP","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4dc29c23c47eed6b7c008683\/original.png?1313015265","startPoints":10000},"nextLevel":{"name":"VIP Gold","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25ecfdc47eed21e6005578\/original.png?1313015277","startPoints":50000},"badges":[{"name":"InfoTrends 2011","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e5e8696abb57514ab0008dd\/original.png?1314817757","startPoints":0},{"name":"Celebridade","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f157c47eed21e3005026\/original.png?1314286523","startPoints":0},{"name":"Apontador 11 anos!","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e5837298a73614c3100d568\/original.png?1314404136","startPoints":0},{"name":"Paparazzo","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f266c47eed21e300504c\/original.png?1314286377","startPoints":0},{"name":"Fot\u00f3grafo","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f249c47eed21e6005609\/original.png?1314286399","startPoints":0},{"name":"Turista japon\u00eas","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f225c47eed21e6005607\/original.png?1314286419","startPoints":0},{"name":"Polaroid","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f205c47eed21e3005045\/original.png?1314286442","startPoints":0},{"name":"8 Megapixels","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f1dac47eed21e60055fc\/original.png?1314286461","startPoints":0},{"name":"3x4","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f1b2c47eed21e60055ee\/original.png?1314286483","startPoints":0},{"name":"McDia Feliz","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e58243adbb61a53f800349d\/original.png?1314399290","startPoints":0},{"name":"Primeira Foto","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f188c47eed21e300502b\/original.png?1314286502","startPoints":0},{"name":"Guia da Cidade","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4dc2a073c47eed6b7c008687\/original.png?1314286850","startPoints":0},{"name":"Explorador","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4dc2a0ddc47eed6b8500985d\/original.png?1314286835","startPoints":0},{"name":"Aventureiro","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4dd3f5f7c47eed2fbd0000d8\/original.png?1314286768","startPoints":0},{"name":"Descobridor","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4dd3f644c47eed2fc2000113\/original.png?1314286745","startPoints":0},{"name":"Curioso","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e151eadc47eed6684000094\/original.png?1314286697","startPoints":0},{"name":"Visitante","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e151fb4c47eed667f000092\/original.png?1314286681","startPoints":0},{"name":"Primeiro Checkin","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e151ffbc47eed667f000095\/original.png?1314286660","startPoints":0},{"name":"Escritor","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f3bfc47eed21e600563e\/original.png?1314286250","startPoints":0},{"name":"Colunista","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f399c47eed21e6005636\/original.png?1314286270","startPoints":0},{"name":"Blogueiro","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f358c47eed21e6005632\/original.png?1314286288","startPoints":0},{"name":"Avaliador","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f332c47eed21e3005061\/original.png?1314286308","startPoints":0},{"name":"Avaliador Jr.","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f309c47eed21e6005626\/original.png?1314286333","startPoints":0},{"name":"Primeira Avalia\u00e7\u00e3o","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f2b6c47eed21e3005052\/original.png?1314286354","startPoints":0},{"name":"Famoso","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f12ac47eed21e300501d\/original.png?1314286539","startPoints":0},{"name":"Popular","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f104c47eed21e60055dc\/original.png?1314286558","startPoints":0},{"name":"Da Galera","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f0dbc47eed21e60055d7\/original.png?1314286573","startPoints":0},{"name":"Conhecido","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25f0b1c47eed21e60055d0\/original.png?1314286589","startPoints":0},{"name":"Amig\u00e1vel","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25efb2c47eed21e3005006\/original.png?1314286605","startPoints":0},{"name":"Elegante","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e53f3f6636c94244f01a187\/original.png?1314124789","startPoints":0},{"name":"Primeiro Seguidor","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25eef5c47eed21e3004fdd\/original.png?1314286634","startPoints":0},{"name":"Bem-vindo","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e53f529abb575319e017c88\/original.png?1314359042","startPoints":0}]}';

    public function testPopulatePlayerProfile()
    {
        $jsonObject = json_decode($this->playerProfileJson);
        $playerProfile = new PlayerProfile($jsonObject);
        $badges = $playerProfile->getBadges();
        $this->assertEquals(700, $playerProfile->getPointsDay());
        $this->assertEquals(701, $playerProfile->getPointsWeek());
        $this->assertEquals(1670, $playerProfile->getPointsMonth());
        $this->assertEquals(33575, $playerProfile->getPointsAll());
        $this->assertEquals("VIP", $playerProfile->getLevel()->getName());
        $this->assertEquals(
                "http://s3.amazonaws.com/badgeville-production-reward-definitions/images/4dc29c23c47eed6b7c008683/original.png?1313015265", 
                $playerProfile->getLevel()->getImage()
                );
        $this->assertEquals(10000, $playerProfile->getLevel()->getStartPoints());
        $this->assertEquals("VIP Gold", $playerProfile->getNextLevel()->getName());
        $this->assertEquals(
                "http://s3.amazonaws.com/badgeville-production-reward-definitions/images/4e25ecfdc47eed21e6005578/original.png?1313015277", 
                $playerProfile->getNextLevel()->getImage()
                );
        $this->assertEquals(50000, $playerProfile->getNextLevel()->getStartPoints());
        $this->assertEquals("InfoTrends 2011", $badges[0]->getName());
        $this->assertEquals(
                "http://s3.amazonaws.com/badgeville-production-reward-definitions/images/4e5e8696abb57514ab0008dd/original.png?1314817757", 
                $badges[0]->getImage()
                );
        $this->assertEquals("Apontador 11 anos!", $badges[2]->getName());
        $this->assertEquals(
                "http://s3.amazonaws.com/badgeville-production-reward-definitions/images/4e5837298a73614c3100d568/original.png?1314404136", 
                $badges[2]->getImage()
                );
        $this->assertEquals("Bem-vindo", $badges[31]->getName());
        $this->assertEquals(
                "http://s3.amazonaws.com/badgeville-production-reward-definitions/images/4e53f529abb575319e017c88/original.png?1314359042", 
                $badges[31]->getImage()
                );
    }

}

