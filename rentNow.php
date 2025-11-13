<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent | FitSphere</title>
    <link rel="stylesheet" href="assets/css/RentNow.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="rent-page">
    <div class="rent-container">
        
        <div class="rent-image">
            <img src="assets/images/suits/b01.webp" alt="Classic Charcoal Executive Suit">
        </div>

    
        <div class="rent-details">
            <h2>Classic Charcoal Executive Suit</h2>

            <div class="rent-options">
                <div class="option">
                    <label>Quantity:</label>
                    <button class="qty-btn" id="decrease">-</button>
                    <input type="number" id="quantity" value="1" min="1" readonly>
                    <button class="qty-btn" id="increase">+</button>
                </div>

                <div class="option">
                    <label>Size:</label>
                    <select>
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                        <option>XXL</option>
                    </select>
                </div>

                <div class="option">
                    <label>Start Date:</label>
                    <input type="date">
                </div>

                <div class="option">
                    <label>End Date:</label>
                    <input type="date">
                </div>

                <div class="option">
                    <label>Deposit:</label>
                    <span class="price">Rs. 2000.00</span>
                </div>

                <div class="option">
                    <label>Total Price:</label>
                    <span class="price">Rs. 4500.00</span>
                </div>
            </div>

            <div class="rent-buttons">
                <a href="#" class="add-cart">Add to Cart <i class="fa fa-shopping-cart"></i></a>
                <a href="#" class="confirm">Confirm</a>
                <a href="collection.php" class="cancel">Cancel</a>
            </div>

            <p class="description">
                Step into sophistication with this timeless charcoal three-piece suit. 
                Tailored for comfort and elegance, it’s the perfect choice for corporate events, weddings, and formal gatherings.
            </p>
        </div>
    </div>


    <div class="size-chart">
        <h3>Size Chart</h3>
        <table>
            <tr>
                <th>Size</th>
                <th>Chest</th>
                <th>Waist</th>
                <th>Hip</th>
                <th>Height</th>
                <th>Fit Type</th>
            </tr>
            <tr>
                <td>M</td>
                <td>36"-38"</td>
                <td>30"-32"</td>
                <td>37"-39"</td>
                <td>5’6”-5’9”</td>
                <td>Slim Fit</td>
            </tr>

            <tr>
                <td>L</td>
                <td>39"-41"</td>
                <td>33"-35"</td>
                <td>40"-42"</td>
                <td>5’9”-6’0”</td>
                <td>Regular Fit</td>
            </tr>

            <tr>
                <td>XL</td>
                <td>42"-44"</td>
                <td>36"-38"</td>
                <td>43"-45"</td>
                <td>6’0”-6’3”</td>
                <td>Broad Fit</td>
            </tr>
    
        </table>
    </div>

  
    <div class="review-section">
        <h3>Reviews</h3>
        <p>No reviews yet.</p>
        <button class="add-review">+ Add Review</button>
    </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/rent.js"></script>

</body>
</html>