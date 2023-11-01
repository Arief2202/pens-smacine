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
    if(isset($_POST['delete'])){
        $sql = "DELETE FROM `data_gudang` WHERE `data_gudang`.`id` = ".$_POST['delete'].";";
        mysqli_query($koneksi, $sql);
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
                    <a class="nav-link me-3 active" aria-current="page" href="/data-gudang.php"><b>Data Gudang</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" aria-current="page" href="/data-apotek.php">Data Apotek</a>
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
                <!-- <button class="btn btn-secondary me-3" href="tambah-obat.php" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">Cari Obat</button> -->
                <a class="btn btn-primary" href="tambah-obat.php">Tambahkan Data</a>
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
                    <th>Jumlah Penerimaan</th>
                    <th>Tanggal Penerimaan</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * FROM `data_gudang`";
                    $result = mysqli_query($koneksi, $sql);
                    while($data = mysqli_fetch_object($result)){
                ?>
                <tr>
                    <td><?=$a+1?></td>
                    <!-- <td>
                        <button 
                            <?php 
                                // echo "onclick=\"showModal(\"".$data->nama_obat."\")\" ";
                            ?>
                            class="btn" style="background-color:rgba(135,103,78,1); color:white;">Lihat
                        </button>
                    </td> -->
                    <td><button onclick='showModal(this)' data-obat='<?= json_encode($data) ?>' data-expired='<?= cek_kadaluarsa($data->tanggal_kadaluarsa)?>' class="btn" style="background-color:rgba(135,103,78,1); color:white;">Lihat</button></td>
                    <td><?=$data->nama_obat?></td>
                    <td><?=$data->jenis_obat?></td>
                    <td><?=$data->kandungan_obat?></td>
                    <td><?=$data->jumlah_penerimaan?></td>
                    <td><?=date("d M Y", strtotime($data->tanggal_penerimaan))?></td>
                    <td><?=date("d M Y", strtotime($data->tanggal_kadaluarsa))?></td>
                    <?php 
                        if(cek_kadaluarsa($data->tanggal_kadaluarsa) < 0){
                            echo '<td style="width:180px;"><div class="alert alert-danger" role="alert">Kadaluwarsa</div></td>';
                        }
                        else if(cek_kadaluarsa($data->tanggal_kadaluarsa) < 7){
                            echo '<td style="width:180px;"><div class="alert alert-warning" role="alert">Hampir Kadaluwarsa</div></td>';
                        }
                        else{
                            echo '<td style="width:180px;"><div class="alert alert-success" role="alert">Aman</div></td>';
                        }
                    ?>
                    <td style="width:150px;">
                        <form method="post">
                            <a class="btn btn-secondary me-2" href="/tambah-obat.php?id=<?=$data->id?>">Edit</a>
                            <button type="submit" class="btn btn-danger" name="delete" value="<?=$data->id?>">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Obat</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td style="width:180px;">Nama Obat</td>
                            <td>: <p id="m_namaObat" style="display:inline;"></p></td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Jenis Obat</td>
                            <td>: <p id="m_jenisObat" style="display:inline;"></p></td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Kandungan Obat</td>
                            <td>: <p id="m_kandunganObat" style="display:inline;"></p></td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Jumlah Penerimaan</td>
                            <td>: <p id="m_jumlahPenerimaan" style="display:inline;"></p></td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Tanggal Penerimaan</td>
                            <td>: <p id="m_tanggalPenerimaan" style="display:inline;"></p></td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Tanggal Kadaluarsa</td>
                            <td>: <p id="m_tanggalKadaluarsa" style="display:inline;"></p></td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Status</td>
                            <td>: <p id="m_status" style="display:inline;"></p></td>
                        </tr>
                    </tbody>
                </table>
                <h3>No Kartu</h3>                
                <table id="myTable2" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Kartu</th>
                            <!-- <th>Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody id="m_card">                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    
    <!-- Modal2 -->
    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Scan Kartu</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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
            $('#myTable').DataTable();
        } );
        var table = $('#myTable2').DataTable( {
            pageLength : 5,
            lengthMenu: [[5, 10, 20], [5, 10, 20]]
        });

        function showModal(test){
            var obat = JSON.parse(test.getAttribute('data-obat'));
            var data_expired = parseInt(test.getAttribute('data-expired'));

            table.ajax.url('/ajaxTable.php?id='+obat.id).load();
            console.log(obat);
            document.getElementById("m_namaObat").innerHTML=obat.nama_obat;
            document.getElementById("m_jenisObat").innerHTML=obat.jenis_obat;
            document.getElementById("m_kandunganObat").innerHTML=obat.kandungan_obat;
            document.getElementById("m_jumlahPenerimaan").innerHTML=obat.jumlah_penerimaan;
            document.getElementById("m_tanggalPenerimaan").innerHTML=obat.tanggal_penerimaan;
            document.getElementById("m_tanggalKadaluarsa").innerHTML=obat.tanggal_kadaluarsa;
            document.getElementById("m_status").innerHTML=data_expired < 0 ? "Kadaluarsa" : (data_expired < 7 ? "Hampir Kadaluarsa" : "Aman");
            $('#exampleModal').modal('show');
        }
    </script>
  </body>
</html>