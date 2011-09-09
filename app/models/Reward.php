<?php

namespace app\models;

class Reward
{

    private $id;
    private $name;
    private $image;
    private $message;
    private $startPoints;

    public function __construct($data = null)
    {
        if (!empty($data)) {
            $this->populate($data);
        }
    }

    public function populate($data)
    {
        if (isset($data->id)) {
            $this->setId($data->id);
        }
        if (isset($data->name)) {
            $this->setName($data->name);
        }
        if (isset($data->image)) {
            $this->setImage($data->image);
        }
        if (isset($data->message)) {
            $this->setMessage($data->message);
        }
        if (isset($data->startPoints)) {
            $this->setStartPoints($data->startPoints);
        }
    }

    public function getId()     {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getStartPoints()
    {
        return $this->startPoints;
    }

    public function setStartPoints($startPoints)
    {
        $this->startPoints = $startPoints;
    }

}