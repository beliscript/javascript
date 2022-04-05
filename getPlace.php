<?php 
require 'config.php';

$villages = mysqli_query($db, "SELECT * FROM villages order by name");
$categories = mysqli_query($db, "SELECT * FROM categories order by name");
foreach ($villages as $village) {
    $check = mysqli_query($db, "SELECT * FROM places WHERE village_id = '$village[id]'");
    if (mysqli_num_rows($check) == 0) {
       //lat
       $lat = $village['lat'];
       //longitude
       $longitude = $village['longitude'];   
       foreach ($categories as $category) {
           $api_key = "0270b36fdd7cd7c946ded87780d50fa4fc72bbfd64e9228bbf03d81a58070364";
           $api = curlApi('https://serpapi.com/search.json?engine=google_maps&q='.$category['name'].'&ll=%40'.$village['lat'].'%2C'.$village['longitude'].'%2C15.1z&type=search&api_key=' . $api_key.'');
           $result = json_decode($api);
           for()
       }    
    } else{
      echo "Data already exists";
    }
}