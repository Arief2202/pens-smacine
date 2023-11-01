<?php
    include "koneksi.php";
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_GET['id'])){
        $sql = "SELECT * FROM `card` WHERE obat_id = ".$_GET['id'];
        $query = mysqli_query($koneksi, $sql);
        // $datas;
        $index = 0;
        while($data = mysqli_fetch_object($query)){
            // foreach($data as $key => $dataa){
            //     $dataas[] = $dataa;
            // }
            $datas['data'][$index][0] =  $index+1;
            $datas['data'][$index][1] =  $data->card != 'Scan Kartu ...' ? $data->card : "<div class=\"alert alert-primary m-0 w-50\" style=\"\" role=\"alert\">Scan Kartu ...</div>";
            $index++;
        }
        echo json_encode($datas);

    }
    else{

        $sql = "SELECT * FROM `card` WHERE obat_id = 0";
        $query = mysqli_query($koneksi, $sql);
        // $datas;
        $index = 0;
        while($data = mysqli_fetch_object($query)){
            // foreach($data as $key => $dataa){
            //     $dataas[] = $dataa;
            // }
            $datas['data'][$index][0] =  $index+1;
            $datas['data'][$index][1] =  $data->card != 'Scan Kartu ...' ? $data->card : "<div class=\"alert alert-primary m-0 w-50\" style=\"\" role=\"alert\">Scan Kartu ...</div>";
            $datas['data'][$index][2] =  "<button class=\"btn btn-danger\" onclick=\"delCard(".$data->id.")\">Delete</button>";
            $index++;
        }
        echo json_encode($datas);
    }