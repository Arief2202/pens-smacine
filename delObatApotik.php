<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    $id = null;
    if(isset($_GET['id'])) $id = $_GET['id'];
    else if(isset($_POST['id'])) $id = $_POST['id'];
    else die;
    mysqli_query($koneksi, "DELETE FROM `data_apotek` WHERE `data_apotek`.`id` = ".$id);
    header("Location: /data-apotek.php");