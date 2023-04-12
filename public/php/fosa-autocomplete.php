<?php
$conn = new mysqli('localhost', 'root', 'lolo', 'apdecede');

if(!$conn){
    die("Error: Erreur de connexion à la base de données !!!");
}

$search = $_GET['term'];

$query = $conn->query("SELECT * FROM `morgue` 
    WHERE `fosa` 
    LIKE '%".$search."%' 
    ORDER BY `fosa` ASC") or die(mysqli_connect_errno());

$list = array();
$rows = $query->num_rows;

if($rows > 0){
    while($fetch = $query->fetch_assoc()){
        $data['value'] = $fetch['fosa'];
        array_push($list, $data);
    }
}
echo json_encode($list);
?>
