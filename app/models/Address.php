<?php

namespace app\models;

class Address extends \lithium\data\Model {

    var $street = "";
    var $number = 0;
    var $complement = "";
    var $district = "";
    var $zipcode = "";
    var $city = null;

}