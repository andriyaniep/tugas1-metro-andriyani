<?php
$mahasiswa = [
    ["nama"=>"yaya", "nilai"=>95],
    ["nama"=>"kinan", "nilai"=>90],
    ["nama"=>"dinda", "nilai"=>75],
    ["nama"=>"fad", "nilai"=>70],
    ["nama"=>"beni", "nilai"=>40]
];

foreach ($mahasiswa as $jadi){
    $nama= $jadi ['nama'];
    $nilai= $jadi ['nilai'];
    $grade = "";
if ($nilai>=80){
    echo "grade A";
} elseif ($nilai>=70){
    echo "grade B";
} elseif ($nilai>=60){
    echo "grade C";
} else {
    echo "grade D";
}
    echo " |Nama mahasiswa: ".$nama. "| Nilai: ".$nilai. " | ".$grade."<br>";
}
?>