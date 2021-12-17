<?php
$conn = new mysqli('localhost', 'root', 'TKL@wS0CF', 'apdecede');

if(!$conn){
    die("Error: Erreur de connexion à la base de données !!!");
}

$search = $_GET['term'];

$query = $conn->query("SELECT * FROM `centre_etat_civil` 
    WHERE `libelle` 
    LIKE '%".$search."%' 
    ORDER BY `libelle` ASC") or die(mysqli_connect_errno());

$list = array();
$rows = $query->num_rows;

if($rows > 0){
    while($fetch = $query->fetch_assoc()){
        $data['value'] = $fetch['libelle'];
        array_push($list, $data);
    }
}
echo json_encode($list);
?>
