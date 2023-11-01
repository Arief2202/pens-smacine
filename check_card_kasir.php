<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['card']) || isset($_POST['card'])){
        $card = isset($_GET['card']) ? $_GET['card'] : (isset($_POST['card']) ? $_POST['card'] : '');
        $sqlSearchCard = "SELECT * FROM data_apotek WHERE card=\"".$card."\";";
        $resultSearchCard = mysqli_fetch_object(mysqli_query($koneksi, $sqlSearchCard));
        $sqlSearchCard2 = "SELECT * FROM card WHERE card=\"".$card."\";";
        $resultSearchCard2 = mysqli_fetch_object(mysqli_query($koneksi, $sqlSearchCard2));

        $sql = "SELECT * FROM data_apotek WHERE card=\"scan\";";
        $result = mysqli_fetch_object(mysqli_query($koneksi, $sql));
        if($result){
            if($resultSearchCard){
                echo json_encode([
                    'status' => 'failed',
                    'pesan' => "Gagal input kartu ke data baru, kartu sudah ada di database apotek"
                ]);

            }
            else if (!$resultSearchCard2){
                echo json_encode([
                    'status' => 'failed',
                    'pesan' => "Gagal input kartu ke data baru, Kartu tidak ditemukan di database gudang"
                ]);
            }
            else{
                $sql = "UPDATE `data_apotek` SET `card` = '".$card."' WHERE `data_apotek`.`id` = ".$result->id.";";
                $result = mysqli_query($koneksi, $sql);
                
                echo json_encode([
                    'status' => 'success',
                    'pesan' => "Berhasil input kartu ke data baru"
                ]);
            }
        }
        else{
            if(!$resultSearchCard){
                echo json_encode([
                    'status' => "failed",
                    'pesan' => "Kartu tidak ditemukan di database apotek"
                ]);
            } else 
            if($resultSearchCard2 && $resultSearchCard2->obat_id != 0){                
                $sqlSearchObat = "SELECT * FROM data_gudang WHERE id=\"".$resultSearchCard2->obat_id."\";";
                $resultSearchObat = mysqli_fetch_object(mysqli_query($koneksi, $sqlSearchObat));
                echo json_encode([
                    'status' => 'success',
                    'card' => $resultSearchCard2,
                    'obat' => $resultSearchObat
                ]);
            }
            else{
                echo json_encode([
                    'status' => "failed",
                    'pesan' => "Kartu tidak ditemukan di database"
                ]);
            }
        }
        
    }
    else{
        echo json_encode([
            'status' => "failed",
            'pesan' => "Masukkan paramater kartu"
        ]);
    }