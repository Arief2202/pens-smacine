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
    header("Location: /login.php");
    exit();
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
                    <a class="nav-link me-3" aria-current="page"  href="/data-gudang.php">Data Gudang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3 active" aria-current="page"  href="/data-apotek.php"><b>Data Apotek</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="/penjualan.php">Penjualan</a>
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
                <!-- <button class="btn btn-secondary me-3" href="tambah-obat.php" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Cari Obat</button> -->
                <button class="btn btn-primary" href="tambah-obat.php" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambahkan Data</button>
            </div>
        </div>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Kartu</th>
                    <th>Nama Obat</th>
                    <th>Jenis Obat</th>
                    <th>Kandungan Obat</th>
                    <th>Jumlah Perbox</th>
                    <th>Sisa Obat Perbox</th>
                    <th>Tanggal Penerimaan</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $result = mysqli_query($koneksi, "SELECT * FROM data_apotek");
                $index = 1;
                while($data = mysqli_fetch_object($result)){
                    $kartu = mysqli_fetch_object(mysqli_query($koneksi, "SELECT * FROM card WHERE card = \"".$data->card."\""));
                    $obat = mysqli_fetch_object(mysqli_query($koneksi, "SELECT * FROM data_gudang WHERE id = \"".$kartu->obat_id."\""));
                    $result2 = mysqli_query($koneksi, "SELECT * FROM data_penjualan WHERE card = '".$data->card."' AND checked_out = 1");
                    $totalPenjualan = 0;
                    while($data2 = mysqli_fetch_object($result2)){
                        $totalPenjualan += $data2->jumlah;
                    }
                ?>
                <tr>
                    <td><?=$index++?></td>
                    <td><?=$kartu->card?></td>
                    <td><?=$obat->nama_obat?></td>
                    <td><?=$obat->jenis_obat?></td>
                    <td><?=$obat->kandungan_obat?></td>
                    <td><?=$obat->jumlah_perbox?></td>
                    <td><?=$obat->jumlah_perbox - $totalPenjualan?></td>
                    <td><?=date("d M Y", strtotime($obat->tanggal_penerimaan))?></td>
                    <td><?=date("d M Y", strtotime($obat->tanggal_kadaluarsa))?></td>                    
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
                    <td style="width:150px;"><a class="btn btn-danger" href="/delObatApotik.php?id=<?=$data->id?>">Hapus</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
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
            xhttp.open("GET", "deleteScanTambahApotek.php", true);
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
                                        window.location.replace("/data-apotek.php");
                                    },1000);
                                },3000);
                            }
                        }
                        
                        console.log(id);
                    }
                }
                xhttp.onreadystatechange = stateck;
                if(id_table == null) xhttp.open("GET", "ajaxScanTambahApotek.php", true);
                else xhttp.open("GET", "ajaxScanTambahApotek.php?id="+id, true);
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
                xhttp.open("GET", "deleteScanTambahApotek.php", true);
                xhttp.send(null);
                clearInterval(interval);
            });
        } );
    </script>
  </body>
</html>