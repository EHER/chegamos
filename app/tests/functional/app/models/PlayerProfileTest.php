<?php

namespace app\models;

class PlayerProfileTest extends \PHPUnit_Framework_TestCase
{

    private $playerProfileJson = '{"pointsDay":700,"pointsWeek":701,"pointsMonth":1670,"pointsAll":33575,"level":{"name":"VIP","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4dc29c23c47eed6b7c008683\/original.png?1313015265","startPoints":10000},"nextLevel":{"name":"VIP Gold","image":"http:\/\/s3.amazonaws.com\/badgeville-production-reward-definitions\/images\/4e25ecfdc47eed21e6005578\/original.png?1313015277","startPoints":50000}}';

    public function testPopulatePlayerProfile()
    {
        $jsonObject = json_decode($this->playerProfileJson);
        $playerProfile = new PlayerProfile($jsonObject);
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
    }

}

