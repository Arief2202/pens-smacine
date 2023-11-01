<?php
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
                    <th>Kandungan Obat</th>
                    <th>Tanggal Penerimaan</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php for($a=0; $a<3; $a++){?>
                <tr>
                    <td><?=$a+1?></td>
                    <td>AA:BB:CC:DD:EE:FF</td>
                    <td>Data 2</td>
                    <td>Data 3</td>
                    <td>Data 4</td>
                    <td>Data 5</td>
                    <td style="width:180px;"><div class="alert alert-success" role="alert">Aman</div></td>
                    <td><input type="number" value="1" style="width:50px;"></td>
                    <td style="width:100px;">Rp. 25.000</td>
                    <td style="width:150px;"><button class="btn btn-danger">Hapus</button></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="row mb-5 mt-2">
            <div class="col d-flex justify-content-end">                
                <div>

                    <h2 style="color:rgba(135,103,78,1);"><b>Rincian Pembelanjaan</b></h2>
                    <h4 style="color:black;"><b>Total Harga : Rp. 75.000</b></h4>
                    <div class="col d-flex justify-content-end">     
                        <button class="btn btn-success" href="tambah-obat.php" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Checkout</button>
                    </div>
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
            $('#myTable').DataTable({
                scrollX:true,
            });
        } );
    </script>
  </body>
</html>