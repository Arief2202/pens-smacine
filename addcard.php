<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['card'])){
        $sql = "SELECT * FROM card WHERE card=\"".$_GET['card']."\";";
        $result = mysqli_fetch_object(mysqli_query($koneksi, $sql));
        if(!$result){
            $sql = "INSERT INTO `card` (`id`, `obat_id`, `card`) VALUES (NULL, '0', '".$_GET['card']."');";
            $result = mysqli_query($koneksi, $sql);
            echo json_encode([
                'success' => $result
            ]);
        }
        else{            
            echo json_encode([
                'success' => 'false',
                'error' => 'kartu sudah ada di database'
            ]);
        }
        // $sql = "DELETE FROM `card` WHERE `card`.`id` = ".$result->id;
        // $result = mysqli_query($koneksi, $sql);
        // echo json_encode($result);
    }
    else{
        $sql = "SELECT * FROM card WHERE card=\"Scan Kartu ...\";";
        $result = mysqli_fetch_object(mysqli_query($koneksi, $sql));
        if(!$result){
            $sql = "INSERT INTO `card` (`id`, `obat_id`, `card`) VALUES (NULL, '0', 'Scan Kartu ...');";
            $result = mysqli_query($koneksi, $sql);
            echo json_encode([
                'success' => $result
            ]);
        }
        else{            
            echo json_encode([
                'success' => 'false',
                'error' => 'kartu belum di scan'
            ]);
        }
    }