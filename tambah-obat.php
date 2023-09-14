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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
  
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
                    <a class="nav-link me-3" href="/scanning.php">Scanning</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="/rekapitulasi.php">Rekapitulasi</a>
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
            <div class="col d-flex justify-content-end">
                <a class="btn btn-primary" href="tambah-obat.php">Tambahkan Kartu</a>
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
                <?php for($a=0; $a<100; $a++){?>
                <tr>
                    <td><?=$a+1?></td>
                    <td>AA:BB:CC:DD:EE</td>
                    <td><button class="btn btn-secondary me-2">Edit</button><button class="btn btn-danger">Hapus</button></td>
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
                            <td>: Data 1</td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Kandungan Obat</td>
                            <td>: Data 1</td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Jumlah Penerimaan</td>
                            <td>: Data 1</td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Tanggal Penerimaan</td>
                            <td>: Data 1</td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Tanggal Kadaluarsa</td>
                            <td>: Data 1</td>
                        </tr>
                        <tr>
                            <td style="width:180px;">Status</td>
                            <td>: Data 1</td>
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
                    <tbody>
                        <?php for($a=0; $a<100; $a++){?>
                        <tr>
                            <td style="font-size:14px;"><?=$a+1?></td>
                            <td style="font-size:14px;">AA:BB:CC:DD:EE</td>
                            <!-- <td style="font-size:10px;"><button class="btn btn-secondary me-2">Edit</button><button class="btn btn-danger">Hapus</button></td> -->
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#myTable').DataTable( {
                pageLength : 3,
                lengthMenu: [[3, 5, 10], [3, 5, 10]]
            });
            $('#myTable2').DataTable();
        } );
    </script>
  </body>
</html>