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
         <h1>BUSINESS SUITS</h1>
    </div>
   

    <div class="row row-cols-1 row-cols-md-3 g-4" id="container">

 <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b01.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Single-Breasted Suit</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=101&name=Single-Breasted Suit&price=2500&image=b01.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b02.jpg" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Double-Breasted Suit</h3>
    <p class="card-text">Rs3500.00</p>
    <a class="btn" href="rentNow.php?id=102&name=Double-Breasted Suit&price=3500&image=b02.jpg">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b03.jpg" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Three-Piece Suit</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=103&name=Three-Piece Suit&price=2500&image=b03.jpg">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\b04.jpg" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Slim-Fit Suit</h3>
    <p class="card-text">Rs4000.00</p>
    <a class="btn" href="rentNow.php?id=104&name=Slim-Fit Suit&price=4000&image=b04.jpg">Rent Now</a>
  </div>
</div>


</div>
 
<?php include 'includes/footer.php'; ?>
</body>