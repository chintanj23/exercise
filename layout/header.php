<!DOCTYPE html>
<html lang="en" id="fullscreen">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    
    <title>Employee</title>
   	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  
    </head>
    <style type="text/css">
      .error{
        color: red;
        margin: 0px;
      }
    </style>
<body>

 
<div >

    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
          <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Employee Area</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php 
                if(!empty($_SESSION['user'])){
                    ?>
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="tasks.php">Tasks</a>
                </li> 
                <li class="nav-item">
                  <a class="nav-link" href="logout.php">Logout</a>
                </li>
                <?php 
                }
                ?>
              </ul>
              
            </div>
         </div>
        </nav>
    </nav>
 