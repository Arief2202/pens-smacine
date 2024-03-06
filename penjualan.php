<?php
  include "koneksi.php";
  date_default_timezone_set("Asia/Jakarta");
  session_start();
  if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin'){
    header("Location: /login.php");
    exit();
  }

  if(isset($_POST['logout'])){
    $_SESSION['username'] = null;
    header("Location: /");
    exit();
  }

  if(isset($_POST['updateJumlah'])){
    mysqli_query($koneksi, "UPDATE `data_penjualan` SET `jumlah` = '".$_POST['jumlah']."' WHERE `data_penjualan`.`id` = ".$_POST['id'].";");
    header("Location: /penjualan.php");
  }
  if(isset($_POST['checkout'])){
    mysqli_query($koneksi, "UPDATE `data_penjualan` SET `checked_out` = 1 WHERE `data_penjualan`.`checked_out` = 0;");
    header("Location: /penjualan.php");
  }

  function cek_kadaluarsa($stop){
    $date1 = new DateTime($stop);
    $date2 = new DateTime(date('Y/m/d H:i:s'));
    $interval = $date1->diff($date2);
    if($date1 > $date2){
        return intval($interval->days);
    }
    else{
        return intval($interval->days)*-1;
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMACINE | Data Obat</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/jquery.dataTables.css" rel="stylesheet">
  
    <style>
      .center{
        text-align: center;
      }
      .nav-link .active b{
        color: white;
      }
    </style>
  </head>
  <body style="background-color: rgba(245,244,242,1);">
    <nav class="navbar navbar-expand-lg" style="background-color: rgba(245,244,242,1); font-size:26px;">
        <div class="container-xl" style="border-bottom-style: solid;border-bottom-color: rgba(135,103,78,1); width:100%;">
            <a class="navbar-brand" href="#" style="font-size:30px;">Apotek</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link me-3" href="/beranda.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" aria-current="page"  href="/data-gudang.php">Data Gudang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3 active" href="/data-apotek.php">Data Apotek</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3 active" href="/penjualan.php"><b>Penjualan</b></a>
                </li>
                <li class="nav-item">
                    <form method="POST">
                        <button class="nav-link" type="submit" name="logout">Logout</button>
                    </form>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-5 mt-2">
            <div class="col">
                <h2 style="color:rgba(135,103,78,1);"><b>DATA OBAT</b></h2>
            </div>
            <div class="col d-flex justify-content-end">
                <button class="btn btn-primary" href="tambah-obat.php" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambahkan Keranjang</button>
            </div>
        </div>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Kartu</th>
                    <th>Nama Obat</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Harga Per Pcs</th>
                    <th>Harga Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $result = mysqli_query($koneksi, "SELECT * FROM data_penjualan WHERE checked_out = 0 AND card != 'scan'");
                $totalAll = 0;
                while($data = mysqli_fetch_object($result)){
                    $card = mysqli_fetch_object(mysqli_query($koneksi, "SELECT * FROM card WHERE card = '".$data->card."'"));
                    $obat = mysqli_fetch_object(mysqli_query($koneksi, "SELECT * FROM data_gudang WHERE id = ".$card->obat_id));
                    $totalAll += $obat->harga*$data->jumlah;
                ?>
                <tr>
                    <td><?=$a+1?></td>
                    <td><?=$data->card?></td>
                    <td><?=$obat->nama_obat?></td>
                    <td><?=date("d M Y", strtotime($obat->tanggal_kadaluarsa));?></td>
                    <?php 
                        if(cek_kadaluarsa($obat->tanggal_kadaluarsa) < 0){
                            echo '<td style="width:180px;"><div class="alert alert-danger" role="alert">Kadaluwarsa</div></td>';
                        }
                        else if(cek_kadaluarsa($obat->tanggal_kadaluarsa) < 7){
                            echo '<td style="width:180px;"><div class="alert alert-warning" role="alert">Hampir Kadaluwarsa</div></td>';
                        }
                        else{
                            echo '<td style="width:180px;"><div class="alert alert-success" role="alert">Aman</div></td>';
                        }
                    ?>
                    <td style="width:200px">
                        <form method="POST">
                            <div class="input-group mb-3">
                                <input type="hidden" name="id" value="<?=$data->id?>">
                                <input type="number" class="form-control" style="width:100px" name="jumlah" value="<?=$data->jumlah?>" min="1" max="<?=$obat->jumlah_perbox?>" required>
                                <button class="btn btn-outline-secondary" type="submit" name="updateJumlah" id="button-addon2">Update</button>
                            </div>
                        </form>
                    </td>
                    <td style="width:150px">Rp. <?=number_format($obat->harga, 2, ",", ".")?></td>
                    <td style="width:150px">Rp. <?=number_format($obat->harga*$data->jumlah, 2, ",", ".")?></td>
                    <td style="width:150px;"><button class="btn btn-danger">Hapus</button></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="row mb-5 mt-2">
            <div class="col d-flex justify-content-end">                
                <div>

                    <h2 style="color:rgba(135,103,78,1);"><b>Rincian Pembelanjaan</b></h2>
                    <h4 style="color:black;"><b>Total Harga : Rp. <?=number_format($totalAll, 2, ",", ".")?></td></b></h4>
                    <form method="POST">
                        <div class="col d-flex justify-content-end">     
                            <button class="btn btn-success" name="checkout" type="submit">Checkout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Scan Kartu</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id = "card_area">
            Silahkan Scan Kartu...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
        </div>
    </div>
    </div>


    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {            
            var xhttp = new XMLHttpRequest();      
            xhttp.open("GET", "deleteScanTambahPenjualan.php", true);
            xhttp.send(null);
            var interval = null;            
            var id_table = null;
            function functionDetectCard(id){
                var xhttp = new XMLHttpRequest();        
                function stateck() {
                    if(xhttp.readyState == 4){
                        if(xhttp.responseText == 'null') id_table=null;
                        const res = JSON.parse(xhttp.responseText);
                        if(id_table == null) id_table = res.id;
                        else{
                            if(res.card != 'scan'){
                                document.getElementById("card_area").innerHTML = res.card;
                                setTimeout(function(){
                                    $('#staticBackdrop').modal('hide');
                                    setTimeout(function(){
                                        window.location.replace("/penjualan.php");
                                    },1000);
                                },3000);
                            }
                        }
                        
                        console.log(id);
                    }
                }
                xhttp.onreadystatechange = stateck;
                if(id_table == null) xhttp.open("GET", "ajaxScanTambahPenjualan.php", true);
                else xhttp.open("GET", "ajaxScanTambahPenjualan.php?id="+id, true);
                xhttp.send(null);
            }

            $('#myTable').DataTable({
                scrollX:true,
            });

            $('#staticBackdrop').on('shown.bs.modal', function (e) {
                console.log("Modal Opened, close in 3 seconds");
                
                // $('#staticBackdrop').modal('hide');

                interval = setInterval(function() {
                    functionDetectCard(id_table);
                }, 1000);
            });

            $('#staticBackdrop').on('hidden.bs.modal', function (e) {                
                var xhttp = new XMLHttpRequest();      
                xhttp.open("GET", "deleteScanTambahPenjualan.php", true);
                xhttp.send(null);
                clearInterval(interval);
            });
        } );
    </script>
  </body>
</html>