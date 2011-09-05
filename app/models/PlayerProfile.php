<?php

namespace app\models;

class PlayerProfile
{

    private $pointsMonth;
    private $pointsWeek;
    private $pointsDay;
    private $pointsAll;
    private $level;
    private $nextLevel;

    public function __construct($data = null)
    {
        if (!empty($data)) {
            $this->populate($data);
        }
    }

    public function populate($data)
    {
        if(isset($data->pointsMonth)) {
           $this->setPointsMonth($data->pointsMonth);
        }
        if(isset($data->pointsWeek)) {
           $this->setPointsWeek($data->pointsWeek);
        }
        if(isset($data->pointsDay)) {
           $this->setPointsDay($data->pointsDay);
        }
        if(isset($data->pointsAll)) {
           $this->setPointsAll($data->pointsAll);
        }
        if(isset($data->level)) {
           $this->setLevel(new Reward($data->level));
        }
        if(isset($data->nextLevel)) {
           $this->setNextLevel(new Reward($data->nextLevel));
        }
    }

    /*
     * @TODO: fazer a listagem de medalhas
     */
    public function getBadges() {
        return null;
    }
    
    public function getPointsMonth()
    {
        return $this->pointsMonth;
    }

    public function setPointsMonth($pointsMonth)
    {
        $this->pointsMonth = $pointsMonth;
    }

    public function getPointsWeek()
    {
        return $this->pointsWeek;
    }

    public function setPointsWeek($pointsWeek)
    {
        $this->pointsWeek = $pointsWeek;
    }

    public function getPointsDay()
    {
        return $this->pointsDay;
    }

    public function setPointsDay($pointsDay)
    {
        $this->pointsDay = $pointsDay;
    }

    public function getPointsAll()
    {
        return $this->pointsAll;
    }

    public function setPointsAll($pointsAll)
    {
        $this->pointsAll = $pointsAll;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getNextLevel()
    {
        return $this->nextLevel;
    }

    public function setNextLevel($nextLevel)
    {
        $this->nextLevel = $nextLevel;
    }
}