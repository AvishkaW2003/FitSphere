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
         <h1>WEDDING SUITS</h1>
    </div>
   

    <div class="row row-cols-1 row-cols-md-3 g-4" id="container">

 <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\w01.jpg" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Classic Black Wedding Suit</h3>
    <p class="card-text">Rs3500.00</p>
    <a class="btn" href="rentNow.php?id=301&name=Classic Black Wedding Suit&price=3500&image=w01.jpg">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\w02.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Navy Blue Suit</h3>
    <p class="card-text">Rs4500.00</p>
    <a class="btn" href="rentNow.php?id=302&name=Navy Blue Suit&price=4500&image=w02.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\w03.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Light Grey or Beige Suit</h3>
    <p class="card-text">Rs4500.00</p>
    <a class="btn" href="rentNow.php?id=303&name=Light Grey or Beige Suit&price=4500&image=w03.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\w04.jpg" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Three-Piece Suit (with Vest)</h3>
    <p class="card-text">Rs4000.00</p>
    <a class="btn" href="rentNow.php?id=304&name=Three-Piece Suit with Vest&price=4000&image=w04.jpg">Rent Now</a>
  </div>
</div>


</div>
 
<?php include 'includes/footer.php'; ?>
</body>