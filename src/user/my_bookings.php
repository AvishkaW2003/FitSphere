<?php
include '../../includes/header.php';
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('user');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>

                    .card{
                margin: auto;
                box-shadow: 0 4px 15px rgba(0,0,0,0.15);  
                transition: 0.3s ease;  
                margin-top: 30px; 
            }

            .card:hover {
                transform: translateY(-8px); 
                box-shadow: 0 8px 25px rgba(0,0,0,0.25); 
            }

            h1{
                text-align: center;
                margin-top: 50px;
                margin-bottom: 50px;
            }


            .titleAndDate{
                display: flex;
                justify-content: space-between; 
                align-items: center;            
                width: 100%;
            }

            .titleAndDate>p{
                opacity: 60%;
            }

            .btn{
                float: right;
                position: absolute;
                bottom: 15%;
                right:5%;
            }

            .imageAndDetails{
                display: inline-flex;
                
            }

            #clothImage{
                width: 80px;
                height:fit-content;
            }

            li{
                list-style-type: none;
            }

            .list-group{
                border: none;
            }
            .list-group-horizontal{
                border:none;
            }
            .list-group-item{
                border:none;
                padding-top: 4px;
                padding-bottom: 4px;
                padding-left: 10px;
            }

            .list-group li:first-child{
                padding-top: 0px;
            }

            .list-group li:nth-child(2){
                padding-top: 0px;
            }

            .nav-link {
                color: #6e6e59; 
                text-decoration: none;
                font-size: 18px;
                font-weight: 500;
                transition: 0.3s ease;
            }

            .nav-link:hover {
                color: #FFC107; 
            }

            #card2{
                background-color: rgb(232, 251, 236);
            }
            .color1{
                background-color: rgb(232, 251, 236);
            }

            #card3{
                background-color: rgb(250, 226, 226);
            }
            .color2{
                background-color: rgb(250, 226, 226);
            }




            @media (max-width: 770px) {

                .card {
                    width: 70% !important;
                }

                .imageAndDetails {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 15px;
                }

                #clothImage {
                    width: 120px;
                }

                .details {
                    width: 100%;
                    text-align: center;
                }

                .btn {
                    position: static;
                    float: none;
                    display: block;
                    margin: 20px auto 0 auto;
                }

                .titleAndDate {
                    flex-direction: column;
                    text-align: center;
                    gap: 5px;
                }
            }


            @media (max-width: 576px) {

                .nav-link {
                    font-size: 16px;
                }

                .card-body {
                    padding: 15px;
                }

                #clothImage {
                    width: 100px;
                }

                .titleAndDate h5 {
                    font-size: 16px;
                }

                .titleAndDate p {
                    font-size: 13px;
                }

                .list-group-item {
                    font-size: 14px;
                    padding: 2px 5px;
                }
                .details{
                    width: 100%;
                    text-align: center;
                }
            }

    </style>
</head>

<body style="margin-top: 8rem;">

    <h1>Hello User!</h1>

    

    <div>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link " href="#">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#">Returned</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#">Cancelled</a>
            </li>
             <li class="nav-item">
                <a class="nav-link " href="#">Overdue</a>
            </li>
        </ul>
    </div>





    <div class="card w-75 mb-3">
    <div class="card-body">
        <div class="titleAndDate">
            <h5 class="card-title">Classic Charcoal Executive Suit </h5>
            <p>Oct 29,2025 - Oct 31,2025</p>
        </div>

        <div class="imageAndDetails">

            <img src="../../assets/images/suits/b01.webp" class="rounded float-start" id="clothImage" alt="...">

            <div class="details">
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item">Rent Fee :</li>
                    <li class="list-group-item">Rs.4000.00</li> 
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item">Deposit :</li>
                    <li class="list-group-item">Rs.2000.00</li>
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item">Total price :</li>
                    <li class="list-group-item">Rs.6000.00</li>   
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item">Status :</li>
                    <li class="list-group-item">Active</li>
                </ul>
            </div>
        </div>    
        <button type="button" class="btn btn-danger">Cancel</button>
    </div>
    </div>





    <div class="card w-75 mb-3" id="card2">
    <div class="card-body">
        <div class="titleAndDate">
            <h5 class="card-title">Classic Charcoal Executive Suit</h5>
            <p>Oct 29,2025 - Oct 31,2025</p>
        </div>

        <div class="imageAndDetails">

            <img src="../../assets\images\suits\b1.webp" class="rounded float-start" id="clothImage" alt="...">

            <div class="details">
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color1">Rent Fee :</li>
                    <li class="list-group-item color1">Rs.4000.00</li> 
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color1">Deposit :</li>
                    <li class="list-group-item color1">Rs.2000.00</li>
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color1">Total price :</li>
                    <li class="list-group-item color1">Rs.6000.00</li>   
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color1">Deposit Refunded :</li>
                    <li class="list-group-item color1">Rs.2000.00</li>
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color1">Status :</li>
                    <li class="list-group-item color1">Returned</li>
                </ul>
            </div>
        </div>    
    </div>
    </div>








    <div class="card w-75 mb-3" id="card3">
    <div class="card-body">
        <div class="titleAndDate">
            <h5 class="card-title">Classic Charcoal Executive Suit</h5>
            <p>Oct 29,2025 - Oct 31,2025</p>
        </div>

        <div class="imageAndDetails">

            <img src="../../assets\images\suits\b1.webp" class="rounded float-start" id="clothImage" alt="...">

            <div class="details">
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color2">Rent Fee :</li>
                    <li class="list-group-item color2">Rs.4000.00</li> 
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color2">Deposit :</li>
                    <li class="list-group-item color2">Rs.2000.00</li>
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color2">Total price :</li>
                    <li class="list-group-item color2">Rs.6000.00</li>   
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color2">Late Fee :</li>
                    <li class="list-group-item color2">Rs.1000.00</li>
                </ul>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item color2">Status :</li>
                    <li class="list-group-item color2">Overdue</li>
                </ul>
            </div>
        </div>    
    </div>
    </div>


   
</body>
</html>