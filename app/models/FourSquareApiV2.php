<?php
/**
 * FourSquareApiV2.php
 *
 * Encapsulamento (bem simplificado) dos mecanismos de chamada oAuth à Apontador API.
 * Configure os dados da sua aplicação no ApontadorApiConfig antes de usar.
 *
 * Copyright 2010 Rafael Siqueira (@rafaelsiqueira)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http: *www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

namespace app\models;

class FourSquareApiException //extends Exception
{

	public $status_code;
	public $response;

}

class FourSquareApiV2
{

    /* Contains the last HTTP status code returned. */
    public $http_code;
    /* Contains the last API call. */
    public $url;
    /* Set up the API root URL. */
    public $host_api = "https://api.foursquare.com/v2/";
    /* Set timeout default. */
    public $timeout = 30;
    /* Set connect timeout. */
    public $connecttimeout = 30;
    /* Verify SSL Cert. */
    public $ssl_verifypeer = FALSE;
    /* Decode returned json data. */
    public $http_info;
    /* Set the useragent. */
    public $useragent = 'FoursquareOAuth2 v.1.0.beta';
    /* OAuth2 Parameters */
    public $key, $secret, $callback, $access_token;

    public function __construct($key, $secret, $callback) {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback = $callback;
    }

    function setOAuth2Token($access_token)
    {
        $this->access_token = $access_token;
        return;
    }

    function setKeySecret($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
        return;
    }
    
    function authenticate() {
        $params['client_id'] = $this->key;
        $params['response_type'] = "code";
        $params['redirect_uri'] = $this->callback;


        $queryparams = http_build_query($params);
        $endpoint = "https://foursquare.com/oauth2/authenticate";
        header("Location:$endpoint?$queryparams");
    }

    function accessToken($code) {
        $params['client_id'] = $this->key;
        $params['client_secret'] = $this->secret;
        $params['grant_type'] = "authorization_code";
        $params['code'] = $code;
        $params['redirect_uri'] = $this->callback;

        $queryparams = http_build_query($params);
        $endpoint = "https://foursquare.com/oauth2/access_token";
        $return = $this->http("$endpoint?$queryparams", "GET");
        $this->access_token = json_decode($return)->access_token;

        return $this->access_token;
    }

    function http($url, $method, $postfields = NULL) {
        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {
          case 'POST':
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if (!empty($postfields)) {
              curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
            }
            break;
          case 'DELETE':
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if (!empty($postfields)) {
              $url = "{$url}?{$postfields}";
            }
        }

        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;
        curl_close ($ci);

        if ($this->http_code != "200") {
            	$ex = new FourSquareApiException("$status_code $msg");
            	$ex->status_code = $status_code;
            	$ex->response = $response;
            	throw $ex;
        }

        return $response;
    }

    function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if (!empty($i)) {
          $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
          $value = trim(substr($header, $i + 2));
          $this->http_header[$key] = $value;
        }
        return strlen($header);
    }

    function _oauthRequest($endpoint, $method, $params = array()) {
       if (!isset($params['oauth_token'])) {
          $params['oauth_token'] = $this->access_token;
       }

       $url = $this->host_api . $endpoint;
       if($method == "")$method = 'GET';

       // json_encode all params values that are not strings
       foreach ($params as $key => $value) {
          if (!is_string($value)) {
            $params[$key] = json_encode($value);
          }
       }
       if($method == 'GET')
       {
           $queryparams = http_build_query($params);
           $params = NULL;
           $url .= "?". $queryparams;

       }
       
       
       //die($url);

       $return = $this->http($url, $method, $params);
       //die($return);
       return json_decode($return);

  }


  function _getRequest($endpoint, $params = array()) {
       $url = $this->host_api . $endpoint;
       $method = 'GET';

       // json_encode all params values that are not strings
       foreach ($params as $key => $value) {
          if (!is_string($value)) {
            $params[$key] = json_encode($value);
          }
       }

       $queryparams = http_build_query($params);
       $params = NULL;
       $url .= "?". $queryparams;

       die($url);
       return json_decode($this->http($url, $method, $params));

  }

  function getUser($userid = "")
  {

       //Search by Point
       $params = array();

       if($userid == "")$endpoint = "users/self";
       else $endpoint = "users/$userid";
       $return = $this->_oauthRequest($endpoint, "GET", $params);
       /*
            {
                meta: {
                       code: 200
                      }
                response: {
                        user: {
                             id: "51599"
                             firstName: "Rafael"
                             lastName: "Siqueira"
                             photo: "http://playfoursquare.s3.amazonaws.com/userpix_thumbs/4ac18d8f6efcf.png"
                             gender: "male"
                             homeCity: "Palo Alto, CA"
                             relationship: "self"
                             type: "user"
                             pings: false
                        contact: {
                             phone: "9177449960"
                             email: "rafsiqueira@me.com"
                             twitter: "rafaelsiqueira"
                        }
            }
        *
        *
        *
       */
       $http_code = $return->meta->code;
       $user_info = $return->response;
       $user['id'] = $user_info->user->id;
       $user['name'] = $user_info->user->firstName . " " . $user_info->user->lastName;
       $user['photo'] = $user_info->user->photo;
       $user['gender'] = $user_info->user->gender;
       $user['homecity'] = $user_info->user->homeCity;
       $user['phone'] = $user_info->user->contact->phone;
       $user['email'] = $user_info->user->contact->email;
       $user['twitter'] = $user_info->user->contact->twitter;
       $user['badges'] = $user_info->user->badges->count;
       $user['mayorships'] = $user_info->user->mayorships->count;
       $user['checkins'] = $user_info->user->checkins->count;
       $user['friends'] = $user_info->user->friends->count;
       $user['tips'] = $user_info->user->tips->count;
       $user['todos'] = $user_info->user->todos->count;

       foreach($return->response->user->checkins->items as $k=>$checkin_info){
           $user['checkin_id'] = $checkin_info->id;
           $user['checkin_date'] = $checkin_info->createdAt;
           $user['checkin_timezone'] = $checkin_info->timeZone;

           $place = $checkin_info->venue;
           $user['checkin_venue_id'] = $place->id;
           $user['checkin_venue_name'] = $place->name;
           foreach($place->categories as $k=>$categories){
               $user['checkin_venue_subcategory'] = $categories->name;
               $user['checkin_venue_subcategory_id'] = $categories->id;
               $user['checkin_venue_subcategory_icon'] = $categories->icon;
               foreach($categories->parents as $k=>$category){
                   $user['checkin_venue_category'] = $category;
               }
           }
           $user['checkin_venue_lat'] = $place->location->lat;
           $user['checkin_venue_lng'] = $place->location->lng;
           $user['checkin_venue_address'] = $place->location->address;
           $user['checkin_venue_city'] = $place->location->city . ' - ' . $place->location->state;
       }



        //var_dump($user);
        return $user;
  }




  function searchVenues($lat, $lng, $radius_mt, $term, $limit, $intent)
  {

       //Search by Point
       if($radius_mt=="") $radius_mt = 200;

       $params = array();
       if($lat != "" && $lng != "")$params['ll'] = $lat.",".$lng;
       if($radius_mt != "") $params['llAcc'] = $radius_mt;
       if($term != "") $params['query'] = $term;
       if($limit != "") $params['limit'] = $limit;
       if($intent != "") $params['intent'] = $intent;

       //var_dump($params);
       $endpoint = "venues/search";
       $return = $this->_oauthRequest($endpoint, "GET", $params);
       /*
            {
                "meta":{"code":200},
                "response":
                    {"groups":
                             [{"type":"matches",
                               "name":"Best Matches",
                               "items":
                                     [{"id":"4bd18da1462cb7138a58da07",
                                       "name":"Apontador",
                                       "contact":{"twitter":"apontador"},
                                       "location":{ "address":"Rua Funchal 129",
                                                    "city":"São Paulo",
                                                    "state":"São Paulo",
                                                    "lat":-23.5924511,
                                                    "lng":-46.6867616,
                                                    "distance":135},
                                       "categories":
                                                   [{"id":"4bf58dd8d48988d121941735",
                                                     "name":"Lounge",
                                                     "icon":"http://foursquare.com/img/categories/nightlife/lounge.png",
                                                     "parents":["Nightlife"],
                                                     "primary":true}],
                                       "verified":true,
                                       "stats":{"checkinsCount":111,
                                                "usersCount":30},
                                       "todos":{"count":0},
                                       "hereNow":{"count":0}
                                     }]
                              }]
                     }
            }
       */
       $places = array();
       $http_code = $return->meta->code;
       foreach($return->response->groups as $k=>$groups){
           foreach($groups->items as $k=>$place){
               $places[$k]['id'] = $place->id;
               $places[$k]['name'] = $place->name;
               foreach($place->categories as $k=>$categories){
                   $places[$k]['subcategory'] = $categories->name;
                   $places[$k]['subcategory_id'] = $categories->id;
                   $places[$k]['subcategory_icon'] = $categories->icon;
                   foreach($categories->parents as $k=>$category){
                       $places[$k]['category'] = $category;
                   }
               }
               $places[$k]['lat'] = $place->location->lat;
               $places[$k]['lng'] = $place->location->lng;
               $places[$k]['address'] = $place->location->address;
               $places[$k]['city'] = $place->location->city . ' - ' . $place->location->state;
            }
        }
        //var_dump($places);
        return $places;
    }

    function checkinVenue($venueId, $shout, $broadcast)
    {

       //97156170
       $params = array();
       $params['venueId'] = $venueId;
       $params['broadcast'] = $broadcast;
       if($shout != "") $params['shout'] = $shout;
       
       $endpoint = "checkins/add";
       $return = $this->_oauthRequest($endpoint, "POST", $params);
       /*
        *    {
        *       "meta":{"code":200},
        *       "notifications":
        *                       [{"type":"message",
        *                         "item":{
        *                                "message":"OK! We've got you @ Apontador. You've been here 3 times."
        *                                }
        *                        },
        *                        {"type":"mayorship",
        *                         "item":{
        *                                 "type":"nochange",
        *                                 "checkins":2,
        *                                 "daysBehind":2,
        *                                 "user":{
        *                                         "id":"4315342",
        *                                         "firstName":"Reinaldo",
        *                                         "lastName":"L.",
        *                                         "photo":"http://playfoursquare.s3.amazonaws.com/userpix_thumbs/OJATF1FCUQIIZISL.jpg",
        *                                         "gender":"male",
        *                                         "homeCity":"SÃ£o Paulo, Brasil"
        *                                         },
        *                                 "message":"Reinaldo L. is the Mayor of Apontador.",
        *                                 "image":"http://playfoursquare.s3.amazonaws.com/userpix_thumbs/OJATF1FCUQIIZISL.jpg"
        *                               }
        *                         },
        *                         {"type":"special",
        *                          "item":{
        *                                  "special":{
        *                                             "id":"4bd190a986ba62b5436488b3",
        *                                             "type":"mayor",
        *                                             "message":"Vai ganhar a revista do Apontador.",
        *                                             "description":"for the mayor",
        *                                             "unlocked":false
        *                                             }
        *                                  }
        *                         },
        *                         {"type":"score",
        *                          "item":{
        *                                  "scores":[{
        *                                             "points":3,
        *                                             "icon":"/img/scoring/3.png",
        *                                             "message":"Travel bonus: 3 stops"
        *                                            },
        *                                            {
        *                                             "points":5,
        *                                             "icon":"/img/scoring/1.png",
        *                                             "message":"First time @ Clube Do Desconto!"
        *                                           }],
        *                                  "total":8
        *                                  }
        *                         }],
        *       "response":{
        *                   "checkin":{
        *                               "id":"4d17f024816af04d14d852c2",
        *                               "createdAt":1293414436,
        *                               "type":"checkin",
        *                               "timeZone":"America/Sao_Paulo",
        *                               "venue":{
        *                                        "id":"4bd18da1462cb7138a58da07",
        *                                        "name":"Apontador",
        *                                        "contact":{
        *                                                   "twitter":"apontador"
        *                                                  },
        *                                        "location":{
        *                                                    "address":"Rua Funchal 129",
        *                                                    "city":"SÃ£o Paulo",
        *                                                    "state":"SÃ£o Paulo",
        *                                                    "lat":-23.5924511,
        *                                                    "lng":-46.6867616
        *                                                   },
        *                                        "categories":[
        *                                                      {"id":"4bf58dd8d48988d121941735",
        *                                                       "name":"Lounge",
        *                                                       "icon":"http://foursquare.com/img/categories/nightlife/lounge.png",
        *                                                       "parents":["Nightlife"],
        *                                                       "primary":true
        *                                                     }],
        *                                        "verified":true,
        *                                        "stats":{
        *                                                 "checkinsCount":122,
        *                                                 "usersCount":32
        *                                                 },
        *                                        "todos":{
        *                                                 "count":0
        *                                                }
        *                                        }
        *                               }
        *                   }
        * }
       */
       $checkin = array();
       $checkin['http_code'] = $return->meta->code;
       foreach($return->notifications as $k=>$notification){
           if($notification->type == "message")
               $checkin['message'] = $notification->item->message;
           if($notification->type == "mayorship"){
               $checkin['mayorship_type'] = $notification->item->type;
               $checkin['mayorship_message'] = $notification->item->message;
               $checkin['mayorship_photo'] = $notification->item->image;
               $checkin['mayorship_userid'] = $notification->item->user->id;
               $checkin['mayorship_name'] = $notification->item->user->firstName . " " . $notification->item->user->lastName;
               $checkin['mayorship_gender'] = $notification->item->user->gender;
           }
           if($notification->type == "special"){
               $checkin['special_id'] = $notification->item->special->id;
               $checkin['special_type'] = $notification->item->special->type;
               $checkin['special_message'] = $notification->item->special->message;
               $checkin['special_description'] = $notification->item->special->description;
               $checkin['special_unlocked'] = $notification->item->special->false;
           }
           if($notification->type == "score"){
               $checkin['score'] = $notification->item->scores;
               foreach($notification->item->scores as $k=>$score){
                   $checkin['score_points'][$k] = $score->points;
                   $checkin['score_message'][$k] = $score->message;

               }
               $checkin['score_total'] = $notification->item->total;
           }
       }

       $checkin_info = $return->response->checkin;
       $checkin['id'] = $checkin_info->id;
       $checkin['date'] = $checkin_info->createdAt;
       $checkin['timezone'] = $checkin_info->timeZone;

       $place = $checkin_info->venue;
       $checkin['venue_id'] = $place->id;
       $checkin['venue_name'] = $place->name;
       foreach($place->categories as $k=>$categories){
           $checkin['venue_subcategory'] = $categories->name;
           $checkin['venue_subcategory_id'] = $categories->id;
           $checkin['venue_subcategory_icon'] = $categories->icon;
           foreach($categories->parents as $k=>$category){
               $checkin['venue_category'] = $category;
           }
       }
       $checkin['venue_lat'] = $place->location->lat;
       $checkin['venue_lng'] = $place->location->lng;
       $checkin['venue_address'] = $place->location->address;
       $checkin['venue_city'] = $place->location->city . ' - ' . $place->location->state;
       return $checkin;
    }

    function listFriends($userid)
    {

       //Search by Point
       $params = array();

       if($userid == "")$endpoint = "users/self/friends";
       else $endpoint = "users/$userid/friends";
       $return = $this->_oauthRequest($endpoint, "GET", $params);
       /*
            {
                meta: {
                       code: 200
                      }
                response: {
                            friends: {
                                        count: 107
                                        items: [
                                                {
                                                    id: "3533644"
                                                    firstName: "Aaron David"
                                                    lastName: "Aznar"
                                                    photo: "http://playfoursquare.s3.amazonaws.com/userpix_thumbs/3G1JGXCDNQFVZAJ0.jpg"
                                                    gender: "male"
                                                    homeCity: "Curitiba, Brasil"
                                                    relationship: "friend"
                                                }
                                                {
                                                    id: "574847"
                                                    firstName: "Abubox"
                                                    photo: "http://playfoursquare.s3.amazonaws.com/userpix_thumbs/JZPPA2PKA1BCRBYV.jpg"
                                                    gender: "male"
                                                    homeCity: "Sao Paulo, Brazil"
                                                    relationship: "friend"
                                                }
                                                {
                                                    id: "1593383"
                                                    firstName: "Yanira"
                                                    lastName: "Nasser"
                                                    photo: "http://playfoursquare.s3.amazonaws.com/userpix_thumbs/IYBMY0ZHP5MSM054.jpg"
                                                    gender: "female"
                                                    homeCity: "Rio de Janeiro, RJ/Brasil"
                                                    relationship: "friend"
                                                }
                                               ]
                                        }
                            }
            }
        *
        *
        *
       */
       $friends = array();
       $http_code = $return->meta->code;
       foreach($return->response->friends->items as $k=>$friend){
           $friends[$k]['id'] = $friend->id;
           $friends[$k]['name'] = $friend->firstName . " " . $friend->lastName;
           $friends[$k]['photo'] = $friend->photo;
           $friends[$k]['gender'] = $friend->gender;
           $friends[$k]['homeCity'] = $friend->homeCity;
        }
        //var_dump($friends);
        return $friends;
    }


    function checkinFriendRecents($lat = "", $lng = "", $limit = "", $offset = "", $afterTimestamp = "")
    {

        //Search by Point
       $params = array();
       if($lat != "" && $lng != "")$params['ll'] = $lat.",".$lng;
       if($afterTimestamp != "") $params['afterTimestamp'] = $afterTimestamp;
       if($offset != "") $params['offset'] = $offset;
       if($limit != "") $params['limit'] = $limit; else $params['limit'] = "500";

       $endpoint = "checkins/recent";
       $return = $this->_oauthRequest($endpoint, "GET", $params);

       /*
        {
meta: {
code: 200
}
response: {
recent: [
{
id: "4d18024dbb64224bbf8ac465"
createdAt: 1293419085
type: "checkin"
timeZone: "America/Sao_Paulo"
user: {
id: "51599"
firstName: "Rafael"
lastName: "Siqueira"
photo: "http://playfoursquare.s3.amazonaws.com/userpix_thumbs/4ac18d8f6efcf.png"
gender: "male"
homeCity: "Palo Alto, CA"
relationship: "self"
}
venue: {
id: "4bd72508637ba5938d63f970"
name: "All Match"
contact: { }
location: {
address: "Rua Helena, 275 - 12 andar"
city: "São Paulo"
state: "São Paulo"
lat: -23.593414
lng: -46.6871407
}
categories: [
{
id: "4bf58dd8d48988d124941735"
name: "Corporate / Office"
icon: "http://foursquare.com/img/categories/building/default.png"
parents: [
"Home / Work / Other"
]
primary: true
}
]
verified: false
stats: {
checkinsCount: 20
usersCount: 3
}
todos: {
count: 0
}
specials: [ ]
}
source: {
name: "Plocr V2"
url: "http://www.apontador.com.br"
}
photos: {
count: 0
items: [ ]
}
comments: {
count: 0
items: [ ]
}
}
        *
        *
        *
       */
       $checkins = array();
       $http_code = $return->meta->code;
       foreach($return->response->recent as $k=>$checkin){
           $checkins[$k]['id'] = $checkin->id;
           $checkins[$k]['date'] = $checkin->createdAt;
           $checkins[$k]['timezone'] = $checkin->timeZone;
           $checkins[$k]['source_name'] = $checkin->source->name;
           $checkins[$k]['message'] = $checkin->shout;
           $checkins[$k]['source_url'] = $checkin->source->url;
           $friend = $checkin->user;
           $checkins[$k]['userid'] = $friend->id;
           $checkins[$k]['name'] = $friend->firstName . " " . $friend->lastName;
           $checkins[$k]['photo'] = $friend->photo;
           $checkins[$k]['gender'] = $friend->gender;
           $checkins[$k]['homeCity'] = $friend->homeCity;
           $place = $checkin->venue;
           $checkins[$k]['venue_id'] = $place->id;
           $checkins[$k]['venue_name'] = $place->name;
           $checkins[$k]['venue_lat'] = $place->location->lat;
           $checkins[$k]['venue_lng'] = $place->location->lng;
           $checkins[$k]['venue_address'] = $place->location->address;
           $checkins[$k]['venue_city'] = $place->location->city . ' - ' . $place->location->state;
           foreach($place->categories as $k=>$categories){
               $checkins[$k]['venue_subcategory'] = $categories->name;
               $checkins[$k]['venue_subcategory_id'] = $categories->id;
               $checkins[$k]['venue_subcategory_icon'] = $categories->icon;
               foreach($categories->parents as $k=>$category){
                   $checkins[$k]['venue_category'] = $category;
               }
           }
       }
       //var_dump($checkins);
       return $checkins;
    }


}
?>