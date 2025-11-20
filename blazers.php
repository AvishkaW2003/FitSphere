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
         <h1>BLAZERS</h1>
    </div>
   

    <div class="row row-cols-1 row-cols-md-3 g-4" id="container">

 <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b1.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Single-Breasted Blazer</h3>
    <p class="card-text">Rs2000.00</p>
    <a class="btn" href="rentNow.php?id=601&name=Single-Breasted Blazer&price=2000&image=b1.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b2.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Double-Breasted Blazer</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=602&name=Double-Breasted Blazer&price=2500&image=b2.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b3.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Sports Blazer (Casual Blazer)</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=603&name=Sports Blazer&price=2500&image=b3.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b4.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Velvet or Tuxedo Blazer</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=604&name=Velvet Tuxedo Blazer&price=2500&image=b4.webp">Rent Now</a>
  </div>
</div>


</div>
 
<?php include 'includes/footer.php'; ?>
</body>