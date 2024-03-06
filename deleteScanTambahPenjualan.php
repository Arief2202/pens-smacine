<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    $result = mysqli_query($koneksi, "DELETE FROM data_penjualan WHERE card = \"scan\"");