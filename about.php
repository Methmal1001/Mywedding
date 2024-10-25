<!-- connect php files -->
<?php
include('includes/connect.php');
include('functions/common_function.php');

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyWedding</title>
    <!-- bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" 
    crossorigin="anonymous">
    <!-- Fontawesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  
    <!-- css -->
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="images/newlogo.jpg" type="image/icon type">
    
    <style>
        body{
            overflow-x: hidden;
        }
        section{
            min-height: 100%;
        }

        .contact-container{
            max-width: 1000px;
            margin: auto;
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            background: #606379;
            box-shadow: 0 0 1rem hsla(0, 0%, 100%, 0.16);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .form-container{
            padding: 20px;
        }

        .form-container h3{
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #fff;
        }

        .contact-form{
            display: grid;
            row-gap: 1rem;
        }

        .contact-form input,
        .contact-form textarea{
            width: 100%;
            border: none;
            outline: none;
            background: #3f414e;
            padding: 10px;
            font-size: 0.9rem;
            color: #fff;
            border-radius: 0.4rem;
        }

        .contact-form textarea{
            resize: none;
            height: 200px;
            width: 450px;
        }

        .contact-form .send-button{
            border: none;
            outline: none;
            background: #0597BE;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            cursor: pointer;
        }

        .contact-form .send-button:hover{
            background: hsl(181, 100%, 44%, 0.8);
            transition: 0.3s all linear;
        }

        /* .map iframe{
            width: 100%;
            height: 100%;
        } */

        @media (max-width: 964px){
            .contact-container{
                margin: 0 auto;
                width: 100%;
            }
        }

        @media (max-width: 700px){
            .contact-container{
                grid-template-columns: 1fr;
                gap: 1rem;
                margin-top: 0rem !important;
            }
            .map iframe{
                height: 400px;
            }
        }
        /* hero image */
        .hero-text h1{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 40px;
            text-align: center;
            margin-top: 100px;
            color: #ffffff;
        }

        .hero-text p{
            color: rgb(221, 223, 224);
            text-align: center;
            margin-top: 100px;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            font-size: 25px;
        }

        
        /* Contact information*/
        .con-intro{
            text-align: center;
            margin: 30px 0px;
        }

        .con-intro h3{
            font-family: sans-serif;
            font-size: 23px;
            letter-spacing: 0.5px;
        }

        .con-intro h3 span{
            color: red;
        }

        .con-intro p{
            font-family: sans-serif;
            font-weight: 200;
            color: rgb(90, 90, 114);
            font-size: 15px;
            padding-top: 10px;
        }


        /* Description */
        .des{
            margin: 20px 0px;
            text-align: center;
        }

        .des h2{
            font-size: 25px;
            font-weight: 400;
            font-family: 'Times New Roman', Times, serif;
        }

        .des h2 span{
            font-size: 30px;
            color: red;
            font-weight: bold;
        }

        @media (max-width: 2700px){
            .des{
                margin-top: -180px;
            }
        }
        @media (max-width: 1564px){
            .des{
                margin: 50px auto;
                margin-top: -200px;
                width: 90%;
            }
        }
        @media (max-width: 700px){
            .des{
                margin-top: 250px;
                margin-bottom: 100px;
            }
        }

        /* contact boxes */
        .contact-box{
            background-color: #f1f1f1;
            min-height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .contact-info{
            display: flex;
            width: 100%;
            max-width: 1200px;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
        }

        .card{
            background: #2f3542;
            padding: 0 20px;
            margin: 0 10px;
            width: calc(33% - 20px);
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .card-icon{
            font-size: 28px;
            background: #ff6348;
            width: 60px;
            height: 60px;
            text-align: center;
            line-height: 60px !important;
            border-radius: 50%;
            transition: 0.3s linear;
        }

        .card:hover .card-icon{
            background: none;
            color: #ff6348;
            transform: scale(1.6);
        }

        .card h3{
            text-align: center;
            color: #f1f1f1;
            padding: 10px 0px;
            font-family: sans-serif;
        }

        .card p{
            margin-top: 10px;
            font-weight: 300;
            letter-spacing: 2px;
            max-height: 0;
            opacity: 0;
            transition: 0.3s linear;
            text-align: center;
        }

        .card:hover p{
            max-height: 40px;
            opacity: 1;
        }

        @media screen and (max-width:800px) {
            .contact-info{
                flex-direction: column;
            }
            .card{
                width: 100%;
                max-width: 300px;
                margin: 10px 0;
            }
        }

    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="container-fluid p-0">
        <!-- first child -->
        <nav class="navbar navbar-expand-lg navbar-light bg-info">
  <div class="container-fluid">
    <img src="images//logo.jpg" alt="" class="logo">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="display_all.php">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./users_area/profile.php">My Account</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#publish">Publish Ad</a>
        </li>
        
      </ul>
      <form class="d-flex" action="search_product.php" method="get">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
        name="search_data">
        <!-- <button class="btn btn-outline-light" type="submit">Search</button> -->
        <input type="submit" value="Search" name="search_data_product" class="btn btn-outline-light">
      </form>
    </div>
  </div>
</nav>
    

<!-- second child -->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
  <ul class="navbar-nav me-auto">
  
        <?php
        if(!isset($_SESSION['username'])){
          echo "<li class='nav-item'>
          <a class='nav-link' href='#'>Welcome Guest</a>
        </li>";
        }else{
          echo "<li class='nav-item'>
          <a class='nav-link' href='#'>Welcome ".$_SESSION['username']."</a>
        </li>";
        }
        
        if(!isset($_SESSION['username'])){
          echo "<li class='nav-item'>
          <a class='nav-link' href='./users_area/user_login.php'>Login</a>
        </li>";
        }else{
          echo "<li class='nav-item'>
          <a class='nav-link' href='./users_area/logout.php'>Logout</a>
        </li>";
        }
        
        ?>
        <!-- <li class="nav-item">
          <a class="nav-link" href="./users_area/user_login.php">Login</a>
        </li> -->
</ul>
</nav>



<!-- Details -->
    <div class="about mt-5 text-center mb-5 w-50" style="background-color:#555B5D; padding:20px; margin-left:auto; margin-right:auto;">
        <h3 style="color:#0AA7DB;">About Us</h3>

        <div class="details mt-2 text-center">
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>We are offering you to new and better experience to select a better service provider to decorate your special day.</p>
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>MyWedding website is not 
        responsible for any concern regarding customer and their suppliers issues.</p>
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>We are only supporting 
        to make a combination platform between customer and their suppliers only.</p>
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>

        </p>
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>

        </p>
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>

        </p>
        </div>
    </div>


<!-- Terms & Conditions -->
    <div class="about mt-5 text-center mb-5 w-50" style="background-color:#555B5D; padding:20px; margin-left:auto; margin-right:auto;">
        <h3 style="color:#CC6258;">Terms & Conditions</h3>

        <div class="details mt-2 text-center">
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>We are offering you to new and better experience to select a better service provider to decorate your special day.</p>
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>MyWedding website is not 
        responsible for any concern regarding customer and their suppliers issues.</p>
        <p style="background-color:grey; margin-left:auto; margin-right:auto; padding:20px; color:#E6E7E7;"><span style="color:white; font-weight:bolder;">> </span>We are only supporting 
        to make a combination platform between customer and their suppliers only.</p>
        </div>
    </div>



<!-- last child -->
<?php
    include("./includes/footer.php")
?>
  </div>
  </div>

<!-- bootstrap js link -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" 
crossorigin="anonymous"></script>

</body>
</html>