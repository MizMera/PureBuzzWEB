<?php
include_once "../../Controllers/apiariesC.php";

$apiaryC = new ApiaryC();

if (isset($_GET['id'])) {
    $idApiary = $_GET['id'];
    $apiaryC->supprimerApiary($idApiary);
    header("Location: apiaries.php");
    exit();
}
?>
