<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['id']) || isset($_POST['id'])){
        $id = null;
        if(isset($_GET['id'])) $id = $_GET['id'];
        if(isset($_POST['id'])) $id = $_POST['id'];
        $result = mysqli_query($koneksi, "SELECT * FROM data_apotek WHERE id = \"$id\"");
        echo json_encode(mysqli_fetch_object($result));

    }
    else{
        $result = mysqli_query($koneksi, "SELECT * FROM data_apotek WHERE card = \"scan\"");
        if($result->num_rows < 1) mysqli_query($koneksi, "INSERT INTO `data_apotek` (`id`, `card`) VALUES (NULL, 'scan');");
    
        $result = mysqli_query($koneksi, "SELECT * FROM data_apotek WHERE card = \"scan\"");
        echo json_encode(mysqli_fetch_object($result));
    }