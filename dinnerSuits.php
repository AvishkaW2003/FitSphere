<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets\css\seeMore.css">
</head>
<body>

<?php include 'includes/header.php'; ?>
    <div id="heading">
         <h1>DINNER SUITS</h1>
    </div>
   

    <div class="row row-cols-1 row-cols-md-3 g-4" id="container">

 <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\d01.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Classic Black Tuxedo</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=201&name=Classic Black Tuxedo&price=2500&image=d01.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\d02.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">White Dinner Jacket</h3>
    <p class="card-text">Rs3000.00</p>
    <a class="btn" href="rentNow.php?id=202&name=White Dinner Jacket&price=3000&image=d02.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\d3.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Midnight Blue Tuxedo</h3>
    <p class="card-text">Rs3500.00</p>
    <a class="btn" href="rentNow.php?id=203&name=Midnight Blue Tuxedo&price=3500&image=d3.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\d04.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Velvet Dinner Jacket</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=204&name=Velvet Dinner Jacket&price=2500&image=d04.webp">Rent Now</a>
  </div>
</div>


</div>
 
<?php include 'includes/footer.php'; ?>
</body>