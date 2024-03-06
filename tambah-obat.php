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
  if(isset($_POST['create']) && $_POST['create'] == '1'){
    $sql = "INSERT INTO `data_gudang` 
    (`id`, `nama_obat`, `jenis_obat`, `kandungan_obat`, `jumlah_perbox`, `lokasi_rak`, `harga`, `tanggal_penerimaan`, `tanggal_kadaluarsa`, `created_at`) VALUES 
    (NULL, '".$_POST['nama_obat']."', '".$_POST['jenis_obat']."', '".$_POST['kandungan_obat']."', '".$_POST['jumlah_perbox']."', '".$_POST['lokasi_rak']."', '".$_POST['harga']."', '".$_POST['tanggal_penerimaan']."', '".$_POST['tanggal_kadaluarsa']."', current_timestamp());";
    $result = mysqli_query($koneksi, $sql);
    if($result){
        $sql = "SELECT * FROM `data_gudang` ORDER BY id DESC";
        $result = mysqli_fetch_object(mysqli_query($koneksi, $sql));
        if($result){
            $sql = "UPDATE card SET obat_id = ".$result->id." WHERE obat_id=0 AND card != \"Scan Kartu ...\";";
            $result = mysqli_query($koneksi, $sql);
            if($result) header("Location: /data-gudang.php");   
        }
    }
    else{
        header("Location: /tambah-obat.php");
    }
    exit();
    // if($result) header("Location: /data-gudang.php");
  }
  if(isset($_POST['update']) && $_POST['update'] == '1'){
    $sql = "UPDATE `data_gudang` SET 
    `nama_obat` = '".$_POST['nama_obat']."', 
    `jenis_obat` = '".$_POST['jenis_obat']."', 
    `kandungan_obat` = '".$_POST['kandungan_obat']."',
    `jumlah_perbox` = '".$_POST['jumlah_perbox']."',
    `lokasi_rak` = '".$_POST['lokasi_rak']."',
    `harga` = '".$_POST['harga']."',
    `tanggal_penerimaan` = '".$_POST['tanggal_penerimaan']."',
    `tanggal_kadaluarsa` = '".$_POST['tanggal_kadaluarsa']."'     
    WHERE `data_gudang`.`id` = ".$_POST['id'].";";
    $result = mysqli_query($koneksi, $sql);
    if($result) header("Location: /data-gudang.php");
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
        <div class="row mb-3 mt-2">
            <div class="col">
                <h2 style="color:rgba(135,103,78,1);"><b>TAMBAHKAN OBAT</b></h2>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <h4 style="color:rgba(135,103,78,1);"><b>Kartu</b></h4>
            </div>
            <div class="col d-flex justify-content-end">
                <input id="card" class="me-3">
                <button class="btn btn-primary" onclick="test()">Tambahkan Kartu</button>
            </div>
        </div>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Kartu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- <?php for($a=0; $a<100; $a++){?>
                <tr>
                    <td><?=$a+1?></td>
                    <td>AA:BB:CC:DD:EE</td>
                    <td><button class="btn btn-secondary me-2">Edit</button><button class="btn btn-danger">Hapus</button></td>
                </tr>
                <?php } ?> -->
            </tbody>
        </table>
        <form method="post" action="">
        <?php
            $result=null;
            if(isset($_GET['id'])){
                echo '<input type="hidden" class="form-control" name="id" value="'.$_GET['id'].'">';
                $result = mysqli_fetch_object(mysqli_query($koneksi, "select * from data_gudang where id=".$_GET['id']));
            
            }
        ?>
        <div class="col">
            <h4 style="color:rgba(135,103,78,1);"><b>Data Obat</b></h4>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Obat</label>
            <input type="text" class="form-control" name="nama_obat" <?php if(isset($_GET['id'])) echo "value=\"".$result->nama_obat."\""; ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Obat</label>
            <input type="text" class="form-control" name="jenis_obat" <?php if(isset($_GET['id'])) echo "value=\"".$result->jenis_obat."\""; ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Kandungan Obat</label>
            <textarea class="form-control" name="kandungan_obat"><?php if(isset($_GET['id'])) echo $result->kandungan_obat; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Perbox</label>
            <input type="number" class="form-control" name="jumlah_perbox" <?php if(isset($_GET['id'])) echo "value=\"".$result->jumlah_perbox."\""; ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Lokasi Rak</label>
            <input type="text" class="form-control" name="lokasi_rak" <?php if(isset($_GET['id'])) echo "value=\"".$result->lokasi_rak."\""; ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" <?php if(isset($_GET['id'])) echo "value=\"".$result->harga."\""; ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Penerimaan</label>
            <input type="date" class="form-control" name="tanggal_penerimaan" <?php if(isset($_GET['id'])) echo "value=\"".date('Y-m-d', strtotime($result->tanggal_penerimaan))."\""; ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Kadaluarsa</label>
            <input type="date" class="form-control" name="tanggal_kadaluarsa" <?php if(isset($_GET['id'])) echo "value=\"".date('Y-m-d', strtotime($result->tanggal_kadaluarsa))."\""; ?>>
        </div>
        <div class="d-flex justify-content-end">
            <?php
                if(isset($_GET['id'])) echo '<button type="submit" class="btn btn-primary" name="update" value="1">Update</button>';
                else echo '<button type="submit" class="btn btn-primary" name="create" value="1">Tambahkan</button>';
            ?>
            
        </div>
        </form>
        <br><br><br>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
            var table = $('#myTable').DataTable( {
                pageLength : 10,
                lengthMenu: [[10, 20, 50], [10, 20, 50]],
                ajax: '/ajaxTable.php'
            });
            setInterval(function() {
            table.ajax.url('/ajaxTable.php').load();
            }, 500);
        } );

        function delCard(id){
            var xhttp = new XMLHttpRequest();      
            xhttp.open("GET", "delcard.php?id="+id, true);
            xhttp.send(null);
        }
        function addCard(card){
            var xhttp = new XMLHttpRequest();      
            xhttp.open("GET", "addcard.php?card="+card, true);
            xhttp.send(null);
        }
        function test(){
            var input_card = document.getElementById('card').value;
            addCard(input_card ? input_card : "Scan Kartu ...");
        }
    </script> 
  </body>
</html>