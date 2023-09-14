<?php
  session_start();
  if(isset($_SESSION['username'])){
    header("Location: /beranda.php");
    exit();
  }

  if(isset($_POST['username']) && isset($_POST['password'])){
    if($_POST['username'] == "admin" && $_POST['password'] == "admin"){      
      $_SESSION['username'] = "admin";
      header("Location: /beranda.php");
      exit();
    }
    else{
      echo "Username dan Password Salah";
    }
  }

?>

<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMACINE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
      .bg-login{
        background-image: url("/img/bg_login.jpeg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
      }
      .login-left{
        background-color: rgba(193, 181, 165, 1);
      }
      .myinput{
        height:50px;
        border-radius: 50px;
      }
      .center{
        text-align: center;
      }
      .form{
        margin:20%;
      }
      .submitButton{
        width: 100%;
        height: 50px;
        border-radius: 50px;
        background-color: rgba(79, 66, 57, 1);
        border-color: transparent;
        color:white;
      }
      .submitButton:hover{
        background-color: rgb(40, 34, 29);
        color:white;
      }
    </style>
  </head>

  <body class="h-100">
    
    <div class="row h-100">
      <div class="col-5 h-100 login-left">

        <div class="d-flex justify-content-center align-items-center h-100 ">

            <form class="w-100 form" method="POST">
              <h1 class="center mb-5">Login</h1>
              <div class="mb-3">
                <h5 for="exampleInputEmail1" class="form-label ms-2">Username</h5>
                <input type="text" class="form-control myinput" id="exampleInputEmail1" name="username" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                <h5 for="exampleInputPassword1" class="form-label ms-2">Password</h5>
                <input type="password" class="form-control myinput"  name="password" id="exampleInputPassword1">
              </div>
              <button type="submit" class="btn submitButton mt-5">Login</button>
            </form>

        </div>  

      </div>
      <div class="col-7 h-100 bg-login">
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>
