<?php
include 'amazon.php';

$product_id = !isset($_POST['product_id']) ? "B0985X2YR1" : $_POST['product_id'];

$amazonApi = new AmazonApi();
$amazonApi->changeZipCode("10036");
$amazonApi->setCountry("com");
$product = $amazonApi->getProduct($product_id);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Api Test</title>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 500px;
            border: 2px solid #333;
            border-radius: 10px;
        }

        .parent {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="parent">
        <div class="container">
            <form method="post">
                <label for="product_id">Product ID:</label>
                <input type="text" id="product_id" name="product_id"><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
    <div class="parent">
        <div class="container">
            <h3>
                <?php echo $product->title; ?>
            </h3>
            <img src="<?php echo $product->image; ?>" alt="productImage" width="250" style="height: auto;">
            
            <br />
            <span>Price US: <?php echo $product->price; ?></span>
            <span>Price Germany: <?php echo $product->getPriceInCountry("de"); ?></span>
            <span>Currency: <?php echo $product->currency; ?></span>

            <h4>Description</h4>
            <span><?php echo $product->description; ?></span>

            <h4>Total Ratings Count</h4>
            <span><?php echo $product->ratingsCount; ?></span>

            <h4>Is have stock ?</h4>
            <span><?php echo $product->isStockAvailable ? "Yes" : "No"; ?></span>

            <h4>Product Link</h4>
            <a href="<?php echo $product->product_link; ?>"><?php echo $product->product_link; ?></a>

            <h4>Product ID</h4>
            <span><?php echo $product->id; ?></span>
            <br />
        </div>
    </div>
</body>

</html>