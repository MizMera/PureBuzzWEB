<?php
include_once "../../Controllers/harvestsC.php";

$harvestC = new HarvestC();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $harvestC->supprimerHarvest($id);
    header("Location: harvests.php");
    exit();
}
?>
