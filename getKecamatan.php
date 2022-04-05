<?php 

require 'config.php';



$sql = "SELECT * FROM cities order by id asc";

$cities = mysqli_query($db, $sql);
//loop through the cities
foreach ($cities as $city) {
    $curlApi = curlApi('https://satudata.jabarprov.go.id/api-backend//bigdata/diskominfo/od_kode_wilayah_dan_nama_wilayah_desa_kelurahan?limit=99999&skip=0&where=%7B%22kemendagri_kota_kode%22%3A%5B%22'.$city['kemendagri_kota_kode'].'%22%5D%7D');
    $districts = json_decode($curlApi);
    foreach ($districts->data as $district) {
        $check = mysqli_query($db, "SELECT * FROM districts WHERE kemendagri_kecamatan_kode = '".$district->kemendagri_kecamatan_kode."'");
        //if district not found
        if($check->num_rows == 0) {
            $sql = "INSERT INTO districts (name, lat, longitude, city_id, kemendagri_kecamatan_kode, created_at) VALUES ('$district->kemendagri_kecamatan_nama', '$district->latitude', '$district->longitude', '".$city['id']."','$district->kemendagri_kecamatan_kode', NOW())";
            if(mysqli_query($db, $sql)) {
                echo "Berhasil menambahkan kecamatan ".$district->kemendagri_kecamatan_nama."\n";
                echo "Lat : ".$district->latitude."\n";
                echo "Long : ".$district->longitude."\n";
                echo  "=========================== \n";
            } else {
                echo "Gagal menambahkan kecamatan ".$district->kemendagri_kecamatan_nama."\n";
            }
        }
    }
   
}