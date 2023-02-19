# simple-amazon-api

This code is a class for the Amazon API written in PHP. It is used to retrieve information about products from Amazon. By taking the product ID, it sends it to the Product class and retrieves the relevant product information there. The Product class uses the curl library to retrieve information about the product from the web page. The information includes the product name, description, image, price, number of reviews, and stock status, among others. The API also supports country information and product price units.

# Note
This code is an Amazon API class that I did not prepare as a PHP developer and does not provide update support. I created this code to use in my own projects and wanted to share it on GitHub. I did not spend extra time to improve the quality of the code and created it in a short time. However, in my simple tests, I observed that the code works and performs the desired function. Therefore, those who want to use the code should test it according to their own needs and make necessary adjustments if needed.

# Example
It contains an [example](https://github.com/rucesocial/simple-amazon-api/blob/main/apitest.php) file.

```php
include 'amazon.php';

$product_id = "B0985X2YR1"; // Random Product ID

$amazonApi = new AmazonApi();
$amazonApi->changeZipCode("10036"); //Random Zip Code
$amazonApi->setCountry("com");

$product = $amazonApi->getProduct($product_id);
$title = $product->title;

echo $title;

```

![alt text](https://github.com/rucesocial/simple-amazon-api/blob/main/exampleImage.png?raw=true)
![](https://github.com/rucesocial/simple-amazon-api/blob/main/gif.gif)

# Terms and conditions
You will NOT use this API for marketing purposes (spam, botting, harassment, massive bulk messaging...).
We do NOT give support to anyone who wants to use this API to send spam or commit other crimes.
We reserve the right to block any user of this repository that does not meet these conditions.
# Legal
This code is in no way affiliated with, authorized, maintained, sponsored or endorsed by Amazon or any of its affiliates or subsidiaries. This is an independent and unofficial API. Use at your own risk.
