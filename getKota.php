<?php 
require_once "config.php";
$cities =curlApi('https://satudata.jabarprov.go.id/api-backend//bigdata/diskominfo/od_kode_wilayah_dan_nama_wilayah_desa_kelurahan?limit=5836&skip=0&where=%7B%22bps_provinsi_nama%22%3A%5B%22JAWA+BARAT%22%5D%7D');
$cities = json_decode($cities);
if($cities->error == 0) {
    foreach($cities->data as $city) {
        $check = mysqli_query($db, "SELECT * FROM cities WHERE kemendagri_kota_kode = '".$city->kemendagri_kota_kode."'");
        // if city not found
        if($check->num_rows == 0) {           
            $sql = "INSERT INTO cities (name, lat, longitude, kemendagri_kota_kode, created_at) VALUES ('$city->kemendagri_kota_nama', '$city->latitude', '$city->longitude', '$city->kemendagri_kota_kode', NOW())";
            if(mysqli_query($db, $sql)) {
                echo "Berhasil menambahkan kota ".$city->kemendagri_kota_nama."\n";
                echo "Lat : ".$city->latitude."\n";
                echo "Long : ".$city->longitude."\n";
                echo  "=========================== \n";
            } else {
                echo $sql . "\n";
                echo "Error: " . $sql . "<br>" . mysqli_error($db) . "\n";
                // echo "Gagal menambahkan kota ".$city->kemendagri_kota_nama."\n";
            }
        }
    }
}