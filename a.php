<?php 

$mahasiswa = array(
    array("Ajeng","IF-SI S1",2),
    array("Amel","IF-SI S1",2),
    array("Dhea","IF-SI S1",2),
    array("Noza","IF-SI S1",2),
);


//Mengakses Array menggunakan foreach
foreach($mahasiswa as $mhs){
    echo "Nama : ".$mhs[0]." \n";
    echo "Prodi : ".$mhs[1]."\n";
    echo "Semester : ".$mhs[2]."\n";
    echo "================== \n";
}
// menampilkan array dengan perulangan for
// Looping baris
for($i=0;$i<count($mahasiswa);$i++){
    //looping kolom
    for($j=0;$j<count($mahasiswa[$i]);$j++){
        //menampilkan isi array
        //[0] = nama
        //[1] = prodi
        //[2] = semester
        if($j==0){
            echo "Nama : ".$mahasiswa[$i][$j]."\n";
        } else if($j == 1) {
            echo "Prodi : ".$mahasiswa[$i][$j]."\n";
        } else if($j == 2) {
            echo "Semester : ".$mahasiswa[$i][$j]."\n";
        }
    }
} 