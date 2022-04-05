<?php 

require 'config.php';



$sql = "SELECT * FROM districts order by id asc";

$districts = mysqli_query($db, $sql);
//loop through the cities
foreach ($districts as $district) {
    $curlApi = curlApi('https://satudata.jabarprov.go.id/api-backend//bigdata/diskominfo/od_kode_wilayah_dan_nama_wilayah_desa_kelurahan?limit=9999&skip=0&where_or=%7B%22kemendagri_kecamatan_nama%22%3A%22'.$district['name'].'%22%7D');
    $villages = json_decode($curlApi);
    if($villages != NULL) {
        if($villages->error == 0) {
            foreach ($villages->data as $village) {
                $check = mysqli_query($db, "SELECT * FROM villages WHERE kemendagri_kelurahan_kode = '".$village->kemendagri_kelurahan_kode."'");
                //if district not found
                if($check->num_rows == 0) {
                    $sql = "INSERT INTO villages (name, lat, longitude, district_id, kemendagri_kelurahan_kode, created_at) VALUES ('$village->kemendagri_kelurahan_nama', '$village->latitude', '$village->longitude', '".$district['id']."','$village->kemendagri_kelurahan_kode', NOW())";
                    if(mysqli_query($db, $sql)) {
                        echo "Berhasil menambahkan kelurahan ".$village->kemendagri_kelurahan_nama."\n";
                        echo "Lat : ".$village->latitude."\n";
                        echo "Long : ".$village->longitude."\n";
                        echo  "=========================== \n";
                    } else {
                    
                        echo "Gagal menambahkan kelurahan ".$village->kemendagri_kelurahan_nama."\n";
                 
                    }
                }
            } 
        } else {
            echo $villages->message;
        }
  }else {
    echo  "Gagal Sini \n";
  }

}