<?php 

$conn = new mysqli('localhost','root','','fitsphere');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $Email = $_POST['email'] ?? ''; 
    $Name = $_POST['name'] ?? '';
    $Account_Number = $_POST['account_number'] ?? '';
    $Account_Holder_Name = $_POST['account_name'] ?? '';
    $Payment_method  = $_POST['payment_method'] ?? '';
    $CVV =  $_POST['cvv'] ?? '';
    $Month =  $_POST['month'] ?? '';
    $Year =  $_POST['year'] ?? '';

    $sql = "INSERT INTO orders (email,name,account_number,account_name,payment_method,cvv,month,year)
    VALUES('$Email','$Name','$Account_Number','$Account_Holder_Name','$Payment_method','$CVV','$Month','$Year')";
    
    $result = mysqli_query($conn,$sql);
    if($result){
        echo "";
    }else{
        die(mysqli_error($conn));
    }
   
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

    <style>

        body{
            display:flex;
            justify-content:center;
            align-items:center;
            background: #f3f2f2ff;
        }


       .form-group {

        margin: 50px;
        width: 800px;
        display: flex;
        justify-content: center;
        padding: 30px 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 10px 8px 20px rgba(0,0,0,0.1);

    }

    </style>

<body>

<div class="form-group " >
    <form class="row g-3" action="credit_card.php" method="POST">
  <div class="col-md-6">
    <label for="firstName" class="form-label">First Name</label>
    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter your first name">
  </div>

  <div class="col-md-6">
    <label for="last_name" class="form-label">Last Name</label>
    <input type="last_name" name="last_name" class="form-control" id="last_name" placeholder="Enter your last name">
  </div>

  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter your phone number">
  </div>

  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Email</label>
    <input type="email" name="email" class="form-control" id="inputEmail4" placeholder="Enter your email">
  </div>

  <div class="col-md-6">
    <label for="name" class="form-label">Number of Quantity</label>
    <input type="number" min='1' max='5' name="qty" class="form-control" id="qty" oninput="totalCalculation()">
  </div>
  <div class="col-md-6">
    <label for="name" class="form-label">Number of Dates</label>
    <input type="number" min='1' max='5' name="date" class="form-control" id="date" oninput="totalCalculation()">
  </div>
  <div class="col-12">
    <label for="inputAddress" class="form-label">Account Number</label>
    <input type="text" name="account_number" class="form-control" id="account_number" placeholder="Enter your account number">
  </div>
  <div class="col-12">
    <label for="holder_name" class="form-label">Account Holder Name</label>
    <input type="text" name="account_name" class="form-control" id="holder_name" placeholder="Enter account holder name">
  </div>

    <div class="col-md-3">
    <label for="inputState" class="form-label">Payment Method</label>
    <select id="inputState" name = "payment_method" class="form-select">
      <option selected>Choose</option> 
      <option>Credit card</option>
      <option>Debit card</option>
    </select>

  </div>

  <div class="col-md-3">
    <label for="inputCity" class="form-label">CVV</label>
    <input type="text" class="form-control" id="cvv" name="cvv" required>
  </div>

  <div class="col-md-3">
    <label for="month" class="form-label">Month</label>
        <select id="month" name="month" class="form-select" required>
        <option value="">MM</option>
        <option value="01">January</option>
        <option value="02">February</option>
        <option value="03">March</option>
        <option value="04">April</option>
        <option value="05">May</option>
        <option value="06">June</option>
        <option value="07">July</option>
        <option value="08">August</option>
        <option value="09">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select>
    
  </div>

  <div class="col-md-3">
    <label for="year" class="form-label">Year</label>
    <select id="year" name="year" class="form-select" required>
        <option value="">YY</option>
        <?php 
            $currentYear = date("Y");
            for ($i = 0; $i < 15; $i++) {
                echo "<option value='" . ($currentYear + $i) . "'>" . ($currentYear + $i) . "</option>";
            }
        ?>
    </select>

   
  </div>

  <div class="col-md-12">
    <label>Total Amount(LKR)</label>
    <input type="text" name="total" class="form-control" id="total" readonly>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">pay now</button>
    <button type="reset" class="btn btn-primary">reset</button>
  </div>
</form>
</div>

<script>
    function totalCalculation() {

        let qty = parseInt(document.getElementById("qty").value) || 0;
        let dates = parseInt(document.getElementById("date").value) || 0;

        // FIXED Formula — Change price if needed
        let pricePerDay = 1000;

        let total = (qty + dates) * pricePerDay;

        document.getElementById("total").value = total + " LKR";
    }
</script>


    
</body>
</html>
