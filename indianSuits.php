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
         <h1>INDIAN SUITS</h1>
    </div>
   

    <div class="row row-cols-1 row-cols-md-3 g-4" id="container">

 <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\i01.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Sherwani</h3>
    <p class="card-text">Rs3500.00</p>
    <a class="btn" href="rentNow.php?id=501&name=Sherwani&price=3500&image=i01.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\i2.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Kurta Pajama</h3>
    <p class="card-text">Rs2500.00</p>
    <a class="btn" href="rentNow.php?id=502&name=Kurta Pajama&price=2500&image=i2.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\i03.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Bandhgala (Jodhpuri Suit)</h3>
    <p class="card-text">Rs3000.00</p>
    <a class="btn" href="rentNow.php?id=503&name=Bandhgala (Jodhpuri Suit)&price=3000&image=i03.webp">Rent Now</a>
  </div>
</div>

  <div class="card" style="width: 18rem;">
  <img src="assets\images\suits\i04.webp" class="card-img-top" id="cardImage" alt="...">
  <div class="card-body">
    <h3 class="card-title">Achkan</h3>
    <p class="card-text">Rs3500.00</p>
    <a class="btn" href="rentNow.php?id=504&name=Achkan&price=3500&image=i04.webp">Rent Now</a>
  </div>
</div>

</div>
 
<?php include 'includes/footer.php'; ?>
</body>