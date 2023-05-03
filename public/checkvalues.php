<?php

$pdo = new PDO('mysql:host=localhost;dbname=reklamation', 'root', 'dragut21');

$sql = "SELECT * FROM rechnung inner join kunden on kunden.kd_nr = rechnung.kd_nr where rechnung.rechnungsnummer = ? and kunden.plz = ?";
$statement = $pdo->prepare($sql);
$statement->execute(array($_POST['rechnungsnummer'], $_POST['PLZ']));

while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $result = $row;
}



$sql = "SELECT * FROM artikel where be_nr = '".$result['be_nr']."'";
foreach ($pdo->query($sql) as $row) {
    $result['produkte'][]['bezeichnung'] = $row['bezeichnung'];
}

echo json_encode($result);