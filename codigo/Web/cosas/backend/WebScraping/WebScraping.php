<?php
include_once('simplehtmldom_1_9_1/simple_html_dom.php');

function get_amazon2($product_id){
    $url_amazon = "https://www.amazon.es/";
    $url_buscar = $url_amazon . "s?k=" . $product_id;
    $html = file_get_html('https://www.amazon.es/s?k=6972453163820');
    $link = $html->find('a.a-link-normal', 0)->href;
    return $link;
        
        
        
        
        
        
        
        $url_product = $html->find("h2 > a", 0)->href;
        
        $url_final = $url_amazon . $url_product;
        return $url_final;
    return $urlProduct;
    if ($urlProduct != "") {
        $data = get_product_data_amazon($urlProduct);
        return $data;
    } else {
        return array("product_name", "img", "descriptor", "specs", "price");
    }
}


function get_product_data_amazon($url){
    try {
        $html = get_html($url);
        $product_name = $html->find("#productTitle", 0)->plaintext;
        $img = $html->find("#imgTagWrapperId img", 0)->src;
        $descriptor = $html->find("#feature-bullets > ul > li", 0)->plaintext;
        $decimal = explode(",", $html->find(".a-price-whole", 0)->plaintext)[0];
        $fraction = explode(" ", $html->find(".a-price-fraction", 0)->plaintext)[0];
        $price = floatval($decimal . "." . $fraction);
        $specs = "";
        try {
            $table = $html->find("#productDetails_techSpec_section_1", 0);
            $rows = $table->find("tr");
            foreach ($rows as $row) {
                $cells = $row->find("td, th");
                $attribute = $cells[0]->plaintext;
                $value = $cells[1]->plaintext;
                $specs .= $attribute . ": " . $value . "\n";
            }
        } catch (Exception $e) {
            $specs = "The table does not exist on the page";
        }
        return array($product_name, $img, $descriptor, $specs, $price);
    } catch (Exception$e) {
        echo$e;
        return array("product_name", "img", "descriptor", "specs", "price");
    }
}
function get_hola(){
    return array("product_name", "img", "descriptor", "specs", "price");
}


include_once('simplehtmldom_1_9_1/simple_html_dom.php');

function get_amazon($url){
    $html = file_get_contents($url);
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $xpath = new DOMXPath($doc);
    $links = $xpath->query('//*[@id="search"]/div[1]/div[1]/div/span[1]/div[1]/div[3]/div/div/div/div/div[2]/div[1]/h2/a');
    if ($links->length > 0) {
        $href = $links[0]->getAttribute('href');
        return $href;
    }
    return null;
}

$product_id = $argv[2];
$url_amazon = "https://www.amazon.es/";
$url_buscar = $url_amazon . "s?k=" . $product_id;
echo "Url buscar: ".$product_id;
$link = get_amazon($url_buscar);
echo "\nUrl producto:".$link;
?>
