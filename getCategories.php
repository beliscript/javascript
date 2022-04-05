<?php 

require 'config.php';

$apiCUrl = curlApi('https://satudata.jabarprov.go.id/api-backend//bigdata/dinkes/od_15963_jml_tempat_umum_memenuhi_syarat_kesehatan__kategori_te?limit=10&skip=0');
$categories = json_decode($apiCUrl);
if($categories->error == 0) {
    foreach ($categories->metadata_filter[5]->value as $category) {
        $check = mysqli_query($db, "SELECT * FROM categories WHERE name = '".$category."'");
        //if district not found
        if($check->num_rows == 0) {
            $sql = "INSERT INTO categories (name, created_at) VALUES ('$category',  NOW())";
            if(mysqli_query($db, $sql)) {
                echo "Berhasil menambahkan kategory ".$category."\n";
                echo  "=========================== \n";
            } else {
                echo "Gagal menambahkan Kategori \n";
            }
        }
    }
}