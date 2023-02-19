<?php
class AmazonApi
{
    private $ch;
    private $country;
    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt_array($this->ch, [
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 6.0.1; SM-G935S Build/MMB29K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_HTTPHEADER => array(
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: en-US,en;q=0.9',
                'Connection: keep-alive',
            ),
            CURLOPT_COOKIEJAR => 'cookie.txt',
        ]);
    }

    public function getProduct($id)
    {
        $product = new Product($id, $this->country);

        return $product;
    }

    public function getCountry()
    {
        return $this->country;
    }
    public function setCountry($domain)
    {
        $this->country = $domain;
    }
    public function __destruct()
    {
        curl_close($this->ch);
    }

    public function changeZipCode($zipCode)
    {
        curl_setopt_array($this->ch, [
            CURLOPT_URL => 'https://www.amazon.com/gp/delivery/ajax/address-change.html',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'locationType=LOCATION_INPUT&zipCode=' . urlencode($zipCode) . '&storeContext=office-products&deviceType=web&pageType=Detail&actionSource=glow&almBrandId=VUZHIFdob2xlIEZvb2Rz&merchantId=&language=en_US&sessionID=144-5409464-4532734&marketplaceId=ATVPDKIKX0DER&ref_=dtl_psb_continue&refRID=VAJWNZM7VQKHVRP82NWB',
            CURLOPT_COOKIEJAR => 'cookie.txt',
        ]);
        curl_exec($this->ch);
    }
}
class Product
{
    public $id;
    public $product_link;

    public $title;
    public $description;
    public $image;
    public $ratingsCount;

    public $isStockAvailable;

    public $price;
    public $currency;
    //curlOutput
    private $output;

    public function __construct($id, $country)
    {
        $this->id = $id;

        $this->product_link = 'https://www.amazon.' . $country . '/dp/' . $id;
        $this->output = $this->getOutput($this->product_link);


        $this->title = $this->getTitle($this->output);

        $this->image = $this->getImages($this->output);
        $this->description = $this->getProductDescription($this->output);
        $this->ratingsCount = $this->getRatingsCount($this->output);
        $this->isStockAvailable = $this->checkStock($this->output);

        $this->price = $this->getPrice($this->output);
        $this->currency =  $this->getCurrency($this->output);
    }
    private function getOutput($url)
    {

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_HTTPHEADER => array(
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: en-US,en;q=0.9',
                'Connection: keep-alive',
            ),
            CURLOPT_COOKIEFILE => 'cookie.txt',
        ]);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function getPrice($output)
    {
        if (preg_match('/<span class="a-offscreen">(.*?)<\/span>/', $output, $matches)) {
            $price = str_replace(',', '', $matches[1]);
            return $price;
        }
        return null;
    }
    private function getCurrency($output)
    {
        $price = $this->getPrice($output);
        $currency = mb_substr($price, 0, 1, 'UTF-8');
        return $currency;
    }
    private function getTitle($output)
    {
        $matches = [];
        if (preg_match('/<input type="hidden" name="productTitle" value="([^"]+)"/', $output, $matches)) {
            return  $value = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        } else {
            $value = null;
        }
        return null;
    }
    private function getImages($output)
    {
        $regex = '@{"hiRes":"(.*?)"@';
        preg_match($regex, $output, $matches);

        $image_url = null;

        if (isset($matches[1]))
            $image_url = $matches[1];
        else {
            $regex = '/<img.* id="imgBlkFront".* src="(.*?)"/';

            preg_match('/<img[^>]+src="([^"]+)"[^>]+id="imgBlkFront"[^>]*>/i', $output, $matches);
            $image_url = isset($matches[1]) ? $matches[1] : '';
        }
        return $image_url;
    }

    private function getRatingsCount($output)
    {
        preg_match('/<span\s+id="acrCustomerReviewText"\s+class="a-size-base">([\d,]+)\s+ratings<\/span>/', $output, $matches);

        if ($matches) {
            $ratings_count = (int) str_replace(',', '', $matches[1]);
            return $ratings_count;
        }

        return null;
    }
    private  function checkStock($output)
    {
        $regex = '/<span class="a-color-attainable">In Stock.<\/span>/';
        $is_in_stock = preg_match($regex, $output);
        return $is_in_stock == 1;
    }

    private function getProductDescription($output)
    {
        $pattern = '/<div id="productDescription".*?>\s*<p>\s*<span>(.*?)<\/span>\s*<\/p>\s*<\/div>/s';
        preg_match($pattern, $output, $matches);
        return isset($matches[1]) ? trim($matches[1]) : null;
    }

    public function getPriceInCountry($domain)
    {
        $url = 'https://www.amazon.' . $domain . '/dp/' . $this->id;
        $output = $this->getOutput($url);
        $price = $this->getPrice($output);
        return $this->getPrice($output);
    }
}
