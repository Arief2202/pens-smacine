<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['card'])){
        $sql = "SELECT * FROM card WHERE card=\"".$_GET['card']."\";";
        $result = mysqli_fetch_object(mysqli_query($koneksi, $sql));
        $sql = "DELETE FROM `card` WHERE `card`.`id` = ".$result->id;
        $result = mysqli_query($koneksi, $sql);
        echo json_encode($result);
    }
    if(isset($_GET['id'])){
        $sql = "SELECT * FROM card WHERE id=\"".$_GET['id']."\";";
        $result = mysqli_fetch_object(mysqli_query($koneksi, $sql));
        $sql = "DELETE FROM `card` WHERE `card`.`id` = ".$result->id;
        $result = mysqli_query($koneksi, $sql);
        echo json_encode($result);
    }