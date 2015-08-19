<?php
class Helper_Analytics
{
    static function renderExecuteFunction($content)
    {
        $func = "(function () {\n$content})();\n";
        return $func;
    }
    
    static function renderEcommerceScript($order)
    {
        $functionContent = '';
        $anContent = '';
        $functionContent .= "var _gaq = _gaq || [];\n";
        $revenue = $order['total_amount_raw'] - $order['shipping'] - $order['tax'];
        $addTrans = array('_addTrans', $order['order_id'], '', $revenue, $order['tax'], $order['shipping'], '', '', '');
        $addTransJson = json_encode($addTrans);
        $functionContent .= "_gaq.push($addTransJson);\n";
        $newAddTrans = array(
            'id' => $order['order_id'],
            'revenue' => $revenue,
            'tax' => $order['tax'],
            'shipping' => $order['shipping'],
        );
        $newAddTransJson = json_encode($newAddTrans);
        $anContent .= "ga('require', 'ecommerce', 'ecommerce.js');\n";
        $anContent .= "ga('ecommerce:addTransaction', $newAddTransJson);\n";
        foreach ($order['detailed_items'] as $item) {
            $sku = $item['title'];
            if (isset($item['product_code']) && $item['product_code']) {
                $sku = $item['product_code'];
            }
            $addItem = array('_addItem', $order['order_id'], $sku, $item['title'], '', $item['price'], $item['quantity']);
            $newItem = array(
                'id' => $order['order_id'],
                'sku' => $sku,
                'name' => $item['title'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            );
            $addItemJson = json_encode($addItem);
            $newAddItemJson = json_encode($newItem);
            $functionContent .= "_gaq.push($addItemJson);\n";
            $anContent .= "ga('ecommerce:addItem', $newAddItemJson);\n";
        }
        $functionContent .= "_gaq.push(['_trackTrans']);\n";
        $anContent .= "ga('ecommerce:send');\n";
        $scriptContent = $functionContent;
        $s = Helper_Xml::xmlTag('script', $scriptContent, array('type' => 'text/javascript'));
        $gaContent = "";
        $newContent = <<<EOD
if (window.ga) {
$anContent
}

EOD;
        $new = Helper_Xml::xmlTag('script', $newContent, array('type' => 'text/javascript'));
        return $s . $new;
    }
}
