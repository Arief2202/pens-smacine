<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['card']) || isset($_POST['card'])){
        $card = isset($_GET['card']) ? $_GET['card'] : (isset($_POST['card']) ? $_POST['card'] : '');
        $sqlSearchCard = "SELECT * FROM card WHERE card=\"".$card."\";";
        $resultSearchCard = mysqli_fetch_object(mysqli_query($koneksi, $sqlSearchCard));

        $sql = "SELECT * FROM card WHERE card=\"Scan Kartu ...\";";
        $result = mysqli_fetch_object(mysqli_query($koneksi, $sql));
        if($result){
            if($resultSearchCard){
                echo json_encode([
                    'status' => 'failed',
                    'pesan' => "Gagal input kartu ke data baru, kartu sudah ada di database"
                ]);

            }
            else{
                $sql = "UPDATE `card` SET `card` = '".$card."' WHERE `card`.`id` = ".$result->id.";";
                $result = mysqli_query($koneksi, $sql);
                
                echo json_encode([
                    'status' => 'success',
                    'pesan' => "Berhasil input kartu ke data baru"
                ]);
            }
        }
        else{
            if($resultSearchCard && $resultSearchCard->obat_id != 0){                
                $sqlSearchObat = "SELECT * FROM data_gudang WHERE id=\"".$resultSearchCard->obat_id."\";";
                $resultSearchObat = mysqli_fetch_object(mysqli_query($koneksi, $sqlSearchObat));
                echo json_encode([
                    'status' => 'success',
                    'card' => $resultSearchCard,
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