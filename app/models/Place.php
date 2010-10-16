<?php

namespace app\models;

class Place extends \lithium\data\Model {
    var $id = "";
    var $name = "";
    var $average_rating = 0;
    var $review_count = 0;
    var $category = null;
    var $subcategory = null;
    var $address = null;
    var $point = null;
    var $main_url = "";
    var $icon_url = "";
    var $other_url = "";
    
    public function get() {
        return $this;
    }

    public function set($place) {
        $this = $place;
    }
}