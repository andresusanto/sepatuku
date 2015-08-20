<?php
class Helper_Renderer
{

    static function getOrderTableTd($tag, $val, $color, $options, $withKey)
    {
        if (empty($options[$withKey])) {
            return '';
        }
        $options = "style='border-top:2px solid #FFFFFF; background-color:$color; padding:5px 10px; font-size:13px; font-weight:bold;'";
        if ($tag == 'th') {
            $options = "style='text-align:left; background-color:$color; color:#FFFFFF; padding:5px 10px; font-size:13px; font-weight:bold;'";
        }
        $td = "<$tag $options>$val</$tag>";
        return $td;
    }

    static function getOrderTableItemTr($order, $colors, $options, $item)
    {
        $color1 = Helper_Structure::getArrayValue($colors, 'color1', '#333333');
        $color2 = Helper_Structure::getArrayValue($colors, 'color2', '#EEEEEE');
        $image = Helper_Structure::getArrayValue($item, 'image');
        $itemPrice = $item['price']*$item['quantity'];
        $img = '';
        $curency = $order['currency'];
        if ($image) {
            $img = "<a target=\"_blank\" href=\"$image\"><img style=\"max-width: 64px;\" src=\"$image\"/></a>";
        }
        $tr = '';
        $tr .= "<tr>\n";
        $tr .= self::getOrderTableTd('td', $img, $color2, $options, 'with_column_image') . "\n";
        $tr .= self::getOrderTableTd('td', Helper_Structure::getArrayValue($item, 'product_code'), $color2, $options, 'with_column_sku') . "\n";
        $tr .= "<td style='border-top:2px solid #FFFFFF; background-color:$color2; padding:5px 10px; font-size:13px; font-weight:bold;'>".Helper_String::getFriendlyItemTitle($item['title'])."</td>
<td style='border-top:2px solid #FFFFFF; background-color:$color2; text-align:right; width:115px;  padding:5px 10px; font-size:13px; '>".$curency." ".number_format($item['price'], 2, '.', ',')."</td>
<td style='text-align:right; width:80px; border-top:2px solid #FFFFFF; background-color:$color2;  padding:5px 10px; font-size:13px; '>".$item['quantity']."</td>
<td style='border-top:2px solid #FFFFFF; background-color:$color2; text-align:right; width:115px;padding:5px 10px; font-size:13px;'>".$curency." ".number_format($itemPrice, 2, '.', ',')."</td>
";
        $tr .= "</tr>";
        return $tr;
    }

    static function renderOrderTable($order, $colors=NULL, $options=array())
    {
        $color1 = Helper_Structure::getArrayValue($colors, 'color1', '#333333');
        $color2 = Helper_Structure::getArrayValue($colors, 'color2', '#EEEEEE');
        $id = $order['order_id'];
        $name = $order['billing_info']['name'];

        $itemsBuy = $order['detailed_items'];
        $curency = $order['currency'];

        $shiping = $order['shipping'];
        $discount = $order['discount'];
        $tax = 0; if (isset($order['tax'])) $tax = $order['tax'];

        $dp = $order['down_payment'];

        $withColumnImage = FALSE;
        $withColumnSku = FALSE;
        foreach ($itemsBuy as $item) {
            if (!empty($item['image'])) {
                $withColumnImage = TRUE;
            }
            if (!empty($item['product_code'])) {
                $withColumnSku = TRUE;
            }
        }
        $options['with_column_image'] = $withColumnImage;
        $options['with_column_sku'] = $withColumnSku;

        $textHeader = "<table style='border-collapse:collapse' width='100%'>\n";

        $imageTh = self::getOrderTableTd('th', 'Picture', $color1, $options, 'with_column_image');
        $skuTh = self::getOrderTableTd('th', 'SKU', $color1, $options, 'with_column_sku');

        $headerTr = <<<EOD
<tr>
$imageTh
$skuTh
<th style='text-align:left; background-color:$color1; color:#FFFFFF; padding:5px 10px; font-size:13px; font-weight:bold;'>Description</th>
<th style='text-align:right; width:115px; background-color:$color1; color:#FFFFFF; padding:5px 10px; font-size:13px; font-weight:bold;'>Price</th>
<th style='text-align:right; width:80px; background-color:$color1; color:#FFFFFF; padding:5px 10px; font-size:13px; font-weight:bold;'>Quantity</th>
<th style='text-align:right; width:115px; background-color:$color1; color:#FFFFFF; padding:5px 10px; font-size:13px; font-weight:bold;'>Amount</th>
</tr>

EOD;
        $textHeader .= $headerTr;

        $total = 0;
        $textData = "";
        foreach ($itemsBuy as $item){
            $textData .= self::getOrderTableItemTr($order, $colors, $options, $item);
            $itemPrice = $item['price']*$item['quantity'];
            $total += $itemPrice;
        }

        $dataShiping="";
        if($shiping){
            $dataShiping ="
<tr>
<td style='padding:5px 0px; text-align:right; font-size:13px; font-weight:bold;'>Shipping and Handling: </td>
<td style='padding:5px 10px; width:123px; text-align:right; font-size:13px; '>".$curency." ".number_format($shiping, 2, '.', ',')."</td>
</tr>";
        }

        $dataDiscount="";
        if($discount){
            $dataDiscount="
<tr>
<td style='padding:5px 0px; text-align:right; font-size:13px; font-weight:bold;'>Discount: </td>
<td style='padding:5px 10px; width:123px; text-align:right; font-size:13px; '>".$curency." ".number_format($discount, 2, '.', ',')."</td>
</tr>";
        }

        $dataTax="";
        if($tax){
            $dataTax="
<tr>
<td style='padding:5px 0px; text-align:right; font-size:13px; font-weight:bold;'>Tax: </td>
<td style='padding:5px 10px; width:123px; text-align:right; font-size:13px; '>".$curency." ".number_format($tax, 2, '.', ',')."</td>
</tr>";
        }



        $itemTotalText = "Item Total: ";
        $fontWeight = "normal";

        if(!$shiping && !$discount && !$tax){
            $itemTotalText="Total: ";
            $fontWeight="bold";
        }

        $grandTotal = $total + $shiping + $discount + $tax;
        $dataGrandTotal="";

        if($shiping || $discount || $tax){
            $dataGrandTotal="
<tr>
<td style='padding:5px 0px; text-align:right; font-size:13px; font-weight:bold;'>Total: </td>
<td style='padding:5px 10px; width:123px; text-align:right; font-size:13px; font-weight:bold;'>".$curency." ".number_format($grandTotal , 2, '.', ',')."</td>
</tr>
            ";
        }

        $dataDp="";
        if($dp){
            $balance=$grandTotal - $dp;
            $dataDp="
<tr>
<td style='padding:5px 0px; text-align:right; font-size:13px; font-weight:bold;'>Down Payment: </td>
<td style='padding:5px 10px; width:123px; text-align:right; font-size:13px; font-weight:bold;'>".$curency." ".number_format($dp , 2, '.', ',')."</td>
</tr>
<tr>
<td style='padding:5px 0px; text-align:right; font-size:13px; font-weight:bold;'>Balance: </td>
<td style='padding:5px 10px; width:123px; text-align:right; font-size:13px; font-weight:bold;'>".$curency." ".number_format($balance , 2, '.', ',')."</td>
</tr>
            ";
        }

        $textFooter = "</table>
<table style='border-collapse:collapse;' width='100%'>
<tr>
<td style='padding:5px 0px; text-align:right; font-size:13px; font-weight:bold;'>".$itemTotalText."</td>
<td style='padding:5px 10px; width:123px; text-align:right; font-size:13px; font-weight:".$fontWeight.";'>".$curency." ".number_format($total, 2, '.', ',')."</td>
</tr>".$dataShiping.$dataDiscount.$dataTax.$dataGrandTotal.$dataDp."
</table>
";
        return $textHeader.$textData.$textFooter;
    }

    static function renderOrderInfo($order, $colors=NULL){

        $color1 = Helper_Structure::getArrayValue($colors, 'color1', '#333333');
        $color2 = Helper_Structure::getArrayValue($colors, 'color2', '#EEEEEE');

        $billInfo = $order['billing_info'];
        $deliverInfo = $order['delivery_info'];

        $infoHeader="
<table style='border-collapse:collapse; width:100%; margin-top:15px;'>
<tr>
<th style='width:50%; background-color:$color1; color:#FFFFFF; padding:5px 10px; font-size:13px; font-weight:bold; text-align:left;'>Delivery Address</th>
<th style='width:50%; background-color:$color1; color:#FFFFFF; padding:5px 10px; font-size:13px; font-weight:bold; text-align:left;'>Billing Address</th>
</tr>";

        $deliveryCell = self::getAddressCell($deliverInfo, $color1, $color2);
        $billingCell = self::getAddressCell($billInfo, $color1, $color2);
        $infoData="
<tr>
$deliveryCell
$billingCell
</tr>";

        return $infoHeader.$infoData . "\n</table>";

    }

    static function getAddressCell($info, $color1, $color2)
    {
        $name = $info['name'];
        $address = $info['address'];
        $city = $info['city'];
        $postal_code = $info['postal_code'];
        $state_country = Helper_String::implodeIfNotEmpty(', ', array($info['state'], $info['country']));
        $phone = $info['phone'];
        $email = $info['email'];
        $cell = '';
        $cell .= "<td style='width:50%; border-top:2px solid #FFFFFF; background-color:$color2; color:#000000; padding:10px 10px; font-size:13px;'>\n";
        if ($name) $cell .= "    <strong>$name</strong><br />\n";
        if ($address) $cell .= "    $address<br />\n";
        if ($city || $postal_code) $cell .= "    $city $postal_code<br />\n";
        if ($state_country) $cell .= "    $state_country<br />\n";
        if ($phone) $cell .= "    Tel: $phone<br />\n";
        if ($email) $cell .= "    Email: $email<br />\n";
        $cell .= "</td>";
        return $cell;
    }

    static function renderAjaxInfo($view)
    {
        if (isset($view->isFakeView) && $view->isFakeView) {
            $script = <<<EOD
<script type="text/javascript">
window.isFakeView = true;
</script>

EOD;
            return $script;
        }
    }

    static function renderForm($options)
    {
        $_html = "";

        $_form_class = "sirclo-form";
        $_action = "";
        $_lang = "en";
        $_method = "post";

        if (!empty($options['form_class'])) $_form_class = $options['form_class'];
        if (!empty($options['action'])) $_action = $options['action'];
        if (!empty($options['lang'])) $_lang = $options['lang'];

        $_html .= "<form class='$_form_class' action='$_action' novalidate='novalidate' method='$_method' enctype='multipart/form-data'>";

        if (!empty($options['fields'])) {
            foreach ($options['fields'] as $_field) {
                $_html .= self::renderFormRow($_field, $_lang);
            }
        }

        $_html .= "</form>";
        return $_html;

        return 'hello';
    }

    static function renderFormRow($options, $lang="en")
    {
        $_name = "";
        $_sanitized_name = "";
        $_label = "";
        $_type = "text";
        $_attribute = "";
        $_value = "";
        $_value_empty = "";
        $_html_input = "";
        $_html = "";
        $_options = array();
        $_selected = "";

        if (!empty($options['name'])) $_name = $options['name'];
        if (!empty($options['name'])) $_sanitized_name = preg_replace("/[^a-zA-Z0-9\-_]+/", "", $options['name']);
        if (!empty($options['label'])) $_label = $options['label'];
        if (!empty($options['type'])) $_type = $options['type'];
        if (!empty($options['attribute'])) $_attribute = $options['attribute'];
        if (!empty($options['value'])) $_value = $options['value'];
        if (!empty($options['value_empty'])) $_value_empty = $options['value_empty'];
        if (!empty($options['options'])) $_options = $options['options'];
        if (!empty($options['selected'])) $_selected = $options['selected'];

        $_required = "";
        if (strpos($_attribute,'required') !== false) {
            $_required .= "<span class='required'>*</span>";
        }
		/*
		<div class="form-group has-feedback">
					    <input type="text" class="form-control" placeholder="Email" />
					    <i class="fa fa-envelope s-top-less-margin form-control-feedback"></i>
					</div> */
					
		if ($_type == "emailLogin") 
		{
			$_html_input .= "<input id='input_$_name' class='form-control' type='$_type' name='$_name' $_attribute value='$_value' placeholder='Email'>
							 <i class='fa fa-envelope s-top-less-margin form-control-feedback'></i>";
			$_html .= 
			'<div id="form-row-$_sanitized_name" class="form-group">
				<div class="form-group has-feedback sirclo-form-input">
					'.$_html_input.'
				</div>
			</div>';
		}
		else if ($_type == "passwordLogin") 
		{
			$_html_input .= "<input id='input_$_name' class='form-control' type='$_type' name='$_name' $_attribute value='$_value' placeholder='Password'>
							 <i class='fa fa-lock s-top-less-margin form-control-feedback'></i>";
			$_html .= 
			'<div id="form-row-$_sanitized_name" class="form-group">
				<div class="form-group has-feedback sirclo-form-input">
					'.$_html_input.'
				</div>
			</div>';
		}
		
		else if ($_type == "text"  || $_type == "password" || $_type == "email" || $_type == "tel" || $_type == "date") {
			$_html_input .= "<input id='input_$_name' type='$_type' name='$_name' $_attribute value='$_value'>";
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                <div class='sirclo-form-label'>
                    <label for='$_name'>
                        $_label
                        $_required
                    </label>
                </div>
                <div class='sirclo-form-input'>
                    $_html_input
                </div>
            </div>";
        }
        else if ($_type == "textarea") {
            $_html_input .= "<textarea name='$_name' $_attribute></textarea>";
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                <div class='sirclo-form-label'>
                    <label for='$_name'>
                        $_label
                        $_required
                    </label>
                </div>
                <div class='sirclo-form-input'>
                    $_html_input
                </div>
            </div>";
        }
        else if ($_type == "file") {
            $_html_input .= "<input id='input_$_name' type='file' name='$_name' $_attribute>";
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                <div class='sirclo-form-label'>
                    <label for='$_name'>
                        $_label
                        $_required
                    </label>
                </div>
                <div class='sirclo-form-input'>
                    $_html_input
                </div>
            </div>";
        }
        else if ($_type == "country") {
            $_countries = Helper_Paypal::getPaypalCountriesAssoc();
            $_html_input_option = "<option value=\"\">-- Select Country --</option>";
            foreach ($_countries as $_country_key => $_country_value) {
                if (!empty($_value) && $_value == $_country_key) {
                    $_html_input_option .= "<option value='$_country_key' selected='selected'>$_country_value</option>";
                }
                else {
                    $_html_input_option .= "<option value='$_country_key'>$_country_value</option>";
                }
            }

            $_html_input .= "<select name='$_name' $_attribute>$_html_input_option</select>";
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                <div class='sirclo-form-label'>
                    <label for='$_name'>
                        $_label
                        $_required
                    </label>
                </div>
                <div class='sirclo-form-input'>
                    $_html_input
                </div>
            </div>";
        }
        else if ($_type == "salutation") {
            if ($lang == "id") {
                $_salutations = array(
                    '' => '-- Harap Dipilih --',
                    'Mr' => 'Tuan',
                    'Mrs' => 'Nyonya',
                    'Miss' => 'Nona',
                    'Dr' => 'Dr'
                );
            }
            else {
                $_salutations = array(
                    '' => '-- Please Select --',
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Miss' => 'Miss',
                    'Ms' => 'Ms',
                    'Dr' => 'Dr'
                );
            }

            $_html_input_option = "";
            foreach ($_salutations as $_salutation_key => $_salutation_value) {
                if (!empty($_value) && $_value == $_salutation_value) {
                    $_html_input_option .= "<option value='$_salutation_key' selected='selected'>$_salutation_value</option>";
                }
                else {
                    $_html_input_option .= "<option value='$_salutation_key'>$_salutation_value</option>";
                }
            }

            $_html_input .= "<select name='$_name' $_attribute>$_html_input_option</select>";
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                <div class='sirclo-form-label'>
                    <label for='$_name'>
                        $_label
                        $_required
                    </label>
                </div>
                <div class='sirclo-form-input'>
                    $_html_input
                </div>
            </div>";
        }
        else if ($_type == "checkbox") {
            $_html_input .= "<input name='$_name' $_attribute type='checkbox'>";
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group checkbox'>
                <label for='$_name'>
                    <input type='checkbox' value=''>
                    $_label
                    $_required
                </label>
            </div>";
        }
        else if ($_type == "radio") {
            $_html_input = "";
            if (!empty($_value)) {
                foreach ($_value as $_v) {
                    $_html_image = '';
                    if (!empty($_v['image'])) {
                        foreach ($_v['image'] as $img) {
                            if ($_html_image == '') {
                                $_html_image .= '<br/>';
                            }
                            $_html_image .= '<img class="s-icon-pay" src="'.$img.'"/>';
                        }
                    }
                    if (isset($_selected) && $_v['value'] == $_selected) {
                        $_html_input .= "<label><input value='".$_v['value']."' name='".$_name."' type='radio' ".$_attribute." checked='checked'>".$_v['title'].$_html_image."</label>\n";
                    }
                    else {
                        $_html_input .= "<label><input value='".$_v['value']."' name='".$_name."' type='radio' ".$_attribute.">".$_v['title'].$_html_image."</label>\n";
                    }
                }
            }
            else {
                if (!empty($_value_empty)) {
                    $_html_input .= "<span class='value-empty'>$_value_empty</span>";
                }
            }

            $_html .= <<<EOD
<div id='form-row-$_sanitized_name' class='form-group radio'>
    $_html_input
</div>

EOD;
        }
        else if ($_type == "checkbox_multiple") {
            $_html_input = "";
            if (!empty($_value)) {
                foreach ($_value as $_v) {
                    if (!empty($_selected) && in_array($_v['value'], $_selected)) {
                        $_html_input .= "<div class='checkbox-multiple'><input value='".$_v['value']."' name='".$_name."' type='checkbox' ".$_attribute." checked='checked'>".$_v['title']."</div>";
                    }
                    else {
                        $_html_input .= "<div class='checkbox-multiple'><input value='".$_v['value']."' name='".$_name."' type='checkbox' ".$_attribute.">".$_v['title']."</div>";
                    }
                }
            }
            else {
                if (!empty($_value_empty)) {
                    $_html_input .= "<span class='value-empty'>$_value_empty</span>";
                }
            }

            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                <div class='sirclo-form-input checkbox-multiple'>
                    $_html_input
                </div>
            </div>";
        }
        else if ($_type == "subheader") {
            $_html .= "<h2 class='$_sanitized_name'>".$_value."</h2>";
        }
        else if ($_type == "hidden") {
            $_html .= "<input id='input_$_sanitized_name' type='$_type' name='$_name' $_attribute value='$_value'>";
        }
        else if ($_type == "div") {
            $_html .= "<div $_attribute>";
        }
        else if ($_type == "div_close") {
            $_html .= "</div>";
        }
		
        else if ($_type == "submit") {
			if ($_value == "LOGIN") {
				$_html_input .= '<button class="btn btn-lg blue-button s-fullwidth s-bottom-margin s-top-less-margin s-login-button">LOGIN</button>';
			}
			else if ($_value == "REGISTER") {
				$_html_input .= '<button type="submit" class="btn btn-lg blue-button"> REGISTER </button>';
			}
			else if ($_value == "SEND") {
				$_html_input .= '<button type="submit" class="btn btn-lg blue-button">SEND</button>';
			}
			else if ($_value == "CHANGE PASSWORD") {
				$_html_input .= '<button type="submit" class="btn btn-lg blue-button s-change-password-submit">CHANGE PASSWORD</button>';
			}
			else {
				/*
				if ($lang == "id") {
					$_html_input .= "<div class='form-group notice'><span class='required'>*</span> wajib diisi.</div>";
				}
				else {
					$_html_input .= "<div class='form-group notice'>Fields marked with <span class='required'>*</span> are required.</div>";
				}*/
				$_html_input .= "<input type='submit' value='$_value' $_attribute>";
			}
			
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                $_html_input
            </div>";
        }
        else if ($_type == 'dropdown') {
            $_attrs = array();
            if ($_required) {
                $_attrs['required'] = 'required';
            }
            $_html_input .= Helper_Xml::xmlSelect($_name, $_options, $_value, $_attrs);
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                <div class='sirclo-form-label'>
                    <label for='$_name'>
                        $_label
                        $_required
                    </label>
                </div>
                <div class='sirclo-form-input'>
                    $_html_input
                </div>
            </div>";
        }
        else if ($_type == "plain_text") {
            $_html_input .= "<p>$_value</p>";
            $_html .= "
            <div id='form-row-$_sanitized_name' class='form-group'>
                $_html_input
            </div>";
        }

        return $_html;
    }

    function cdn_base_url() {

        return $cdn_base_url;        
    }

    static function sircloRenderJs($params) {

        $sircloEcomm = new Config_SircloEcommerce();
        $sirclcoEcommConfig = $sircloEcomm->getGeneralConfig();
        if ( isset($sirclcoEcommConfig['cdn_base_url']) ){
            $cdn_base_url = $sirclcoEcommConfig['cdn_base_url'];    
        }else{
            $cdn_base_url = "//cdn.sirclo.com";
        }

        $_is_sirclo_only = !empty($params['is_sirclo_only']) ? $params['is_sirclo_only'] : false;

        $_html = '';


        if (!$_is_sirclo_only) {
            $_html .= '
            <script type="text/javascript" src="'.$cdn_base_url.'/jquery.min.js"></script>
            <script type="text/javascript" src="'.$cdn_base_url.'/jquery.validate.min.js"></script>
            <script type="text/javascript" src="'.$cdn_base_url.'/additional-methods.min.js"></script>
            <script type="text/javascript" src="'.$cdn_base_url.'/jquery-ui.min.js"></script>';
        }

        $_html .= '
        <script type="text/javascript" src="'.$cdn_base_url.'/sirclo.js"></script>
        <script type="text/javascript" src="'.$cdn_base_url.'/ajax.js"></script>
        <script type="text/javascript" src="'.$cdn_base_url.'/area_autocomplete.js"></script>';

        return $_html;
    }

    static function sircloRenderCss($params) {

        $sircloEcomm = new Config_SircloEcommerce();
        $sirclcoEcommConfig = $sircloEcomm->getGeneralConfig();
        if ( isset($sirclcoEcommConfig['cdn_base_url']) ){
            $cdn_base_url = $sirclcoEcommConfig['cdn_base_url'];    
        }else{
            $cdn_base_url = "//cdn.sirclo.com";
        }

        $_html = '
        <link rel="stylesheet" href="'.$cdn_base_url.'/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="'.$cdn_base_url.'/sirclo.css" type="text/css">';
        return $_html;
    }

    static function sircloRenderPlaceOrderForm($params) {
        $_member_salutation = !empty($params['member']['salutation']) ? $params['member']['salutation'] : '';
        $_member_name = !empty($params['member']['first_name']) ? $params['member']['first_name'] : '';
        $_member_address = !empty($params['member']['address_line1']) ? $params['member']['address_line1'] : '';
        $_member_city = !empty($params['member']['city']) ? $params['member']['city'] : '';
        $_member_postal_code = !empty($params['member']['postal_code']) ? $params['member']['postal_code'] : '';
        $_member_state = !empty($params['member']['state']) ? $params['member']['state'] : '';
        $_member_country = !empty($params['member']['country']) ? $params['member']['country'] : '';
        if (!$_member_country && !empty($params['default_country'])) {
            $_member_country = $params['default_country'];
        }
        $_member_phone = !empty($params['member']['phone']) ? $params['member']['phone'] : '';
        $_member_email = !empty($params['member']['email']) ? $params['member']['email'] : '';

        $_shipping_city = !empty($params['shipping_city']) ? $params['shipping_city'] : '';
        $_shipping_country = !empty($params['shipping_country']) ? $params['shipping_country'] : '';

        $_link_terms = !empty($params['link_terms']) ? $params['link_terms'] : '';
        $_link_privacy = !empty($params['link_privacy']) ? $params['link_privacy'] : '';

        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = 'en';
        if (!empty($params['lang'])) {
            $_lang = $params['lang'];
        } else if(!empty($params['configs']) && isset($params['configs']['default_lang'])) {
            $_lang = $params['configs']['default_lang'];
        }

        if ($_lang == 'id') {
            $_label_salutation = 'Sapaan';
            $_label_name = 'Nama';
            $_label_phone = 'No HP';
            $_label_address = 'Alamat';
            $_label_city = 'Kota/Kecamatan';
            $_label_postal_code = 'Kode Pos';
            $_label_state = 'Provinsi';
            $_label_country = 'Negara';
            $_label_email = 'E-mail';

            $_label_shipping = "Alamat Pengiriman";
            $_label_shipping_methods = "Metode Pengiriman";
            $_label_backorder_shipping_methods = "Metode Pengiriman Backorder";
            $_label_different_address = 'Kirim ke alamat berbeda?';

            $_label_payment_method = 'Metode Pembayaran';
            $_label_message_subheader = 'Pesan ke Penjual';
            $_label_message = 'Pesan';
            $_label_bank_transfer = !empty($params['label_bank_transfer']) ? $params['label_bank_transfer'] : 'Transfer Bank';
            $_label_credit_card = 'Kartu Kredit / PayPal';
            //$_label_agreement = "Saya telah membaca dan menyetujui <a href='$_link_terms'>Syarat dan Ketentuan</a> dan <a href='$_link_privacy'>Kebijakan Privasi</a>";
        }
        else {
            $_label_salutation = 'Salutation';
            $_label_name = 'Name';
            $_label_phone = 'Mobile Phone';
            $_label_address = 'Address';
            $_label_city = 'City/District';
            $_label_postal_code = 'Postal Code';
            $_label_state = 'State/Province';
            $_label_country = 'Country';
            $_label_email = 'E-mail';

            $_label_shipping = "Shipping Address";
            // $_label_shipping_methods = "Shipping Method";
            $_label_backorder_shipping_methods = "Backorder Shipping Method";
            $_label_different_address = 'Ship to different address';

            $_label_payment_method = 'Payment Method';
            $_label_message_subheader = 'Note to Seller';
            $_label_message = 'Message';
            $_label_bank_transfer = !empty($params['label_bank_transfer']) ? $params['label_bank_transfer'] : 'Bank Transfer';
            $_label_credit_card = 'Credit Card / PayPal';
            //$_label_agreement = "I agree to the <a href='$_link_terms'>Terms of Use</a> and <a href='$_link_privacy'>Privacy Policy</a>";
        }

        $_label_agreement = 'I agree to the Privacy Policy';
        if (!empty($params['configs']['text_agreement'])) {
            $_label_agreement = strip_tags($params['configs']['text_agreement'], '<a>');
        }

        if (empty($params['_payment_methods'])) {
            $_payment_methods = array();
            if (!empty($params['paypal'])) {
                array_push($_payment_methods, array('title' => $_label_credit_card, 'value' => 'paypal'));
            }
            array_push($_payment_methods, array('title' => $_label_bank_transfer, 'value' => 'ibanking'));
        } else {
            $_payment_methods = $params['_payment_methods'];
        }

        $params['fields'] = array();
        $params['fields'][] = array('name' => '', 'type' => 'div', 'value' => '', 'label' => '', 'attribute' => 'class="address address-autocomplete"');
        if (!empty($params['with_salutation'])) {
            $params['fields'][] = array('name' => 'salutation', 'type' => 'salutation', 'value' => $_member_salutation, 'label' => $_label_salutation, 'attribute' => 'required');
        }
        $params['fields'][] = array('name' => 'first_name', 'type' => 'text', 'value' => $_member_name, 'label' => $_label_name, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'address_line1', 'type' => 'text', 'value' => $_member_address, 'label' => $_label_address, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'postal_code', 'type' => 'text', 'value' => $_member_postal_code, 'label' => $_label_postal_code, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'city', 'type' => 'text', 'value' => $_member_city, 'label' => $_label_city, 'attribute' => 'required data-area-autocomplete="city"');
        $params['fields'][] = array('name' => 'state', 'type' => 'text', 'value' => $_member_state, 'label' => $_label_state, 'attribute' => 'required data-area-autocomplete="state"');
        $params['fields'][] = array('name' => 'country', 'type' => 'country', 'value' => $_member_country, 'label' => $_label_country, 'attribute' => 'required data-area-autocomplete="country"');
        $params['fields'][] = array('name' => 'email', 'type' => 'email', 'value' => $_member_email, 'label' => $_label_email, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'phone', 'type' => 'text', 'value' => $_member_phone, 'label' => $_label_phone, 'attribute' => 'required');
        $params['fields'][] = array('name' => '', 'type' => 'div_close', 'value' => '', 'label' => '', 'attribute' => '');

        $params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_shipping, 'label' => '', 'attribute' => '');
        $params['fields'][] = array('name' => 'is_set_delivery', 'type' => 'checkbox', 'value' => '', 'label' => $_label_different_address, 'attribute' => '');
        $params['fields'][] = array('name' => '', 'type' => 'div', 'value' => '', 'label' => '', 'attribute' => 'class="delivery-address address-autocomplete" style="display:none;"');
        $params['fields'][] = array('name' => 'delivery_first_name', 'type' => 'text', 'value' => $_member_name, 'label' => $_label_name, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'delivery_phone', 'type' => 'text', 'value' => $_member_phone, 'label' => $_label_phone, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'delivery_address_line1', 'type' => 'text', 'value' => $_member_address, 'label' => $_label_address, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'delivery_country', 'type' => 'country', 'value' => $_member_country, 'label' => $_label_country, 'attribute' => 'required data-area-autocomplete="country"');
        $params['fields'][] = array('name' => 'delivery_state', 'type' => 'text', 'value' => $_member_state, 'label' => $_label_state, 'attribute' => 'required data-area-autocomplete="state"');
        $params['fields'][] = array('name' => 'delivery_city', 'type' => 'text', 'value' => $_member_city, 'label' => $_label_city, 'attribute' => 'required data-area-autocomplete="city"');
        $params['fields'][] = array('name' => 'delivery_postal_code', 'type' => 'text', 'value' => $_member_postal_code, 'label' => $_label_postal_code, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'delivery_email', 'type' => 'email', 'value' => $_member_email, 'label' => $_label_email, 'attribute' => 'required');
        $params['fields'][] = array('name' => '', 'type' => 'div_close', 'value' => '', 'label' => '', 'attribute' => '');

        // if (!empty($params['with_shipping_methods'])) {
        //     $params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_shipping_methods, 'label' => '', 'attribute' => '');
        //     $params['fields'][] = array('name' => 'shipping_value_wrapper', 'type' => 'plain_text', 'value' => '', 'label' => '', 'attribute' => '');
        // }

        if (!empty($params['with_backorder_shipping_methods'])) {
            $params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_backorder_shipping_methods, 'label' => '', 'attribute' => '');
            $params['fields'][] = array('name' => 'backorder_shipping_value_wrapper', 'type' => 'plain_text', 'value' => '', 'label' => '', 'attribute' => '');
        }

        $params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_payment_method, 'label' => '', 'attribute' => '');
        $params['fields'][] = array('name' => 'payment_method', 'type' => 'radio', 'value' => $_payment_methods, 'label' => '', 'attribute' => 'required');
        // $params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_message_subheader, 'label' => '', 'attribute' => '');
        // $params['fields'][] = array('name' => 'message', 'type' => 'textarea', 'value' => '', 'label' => $_label_message, 'attribute' => '');
        if (!empty($_label_agreement)) {
            $params['fields'][] = array('name' => 'agreement', 'type' => 'checkbox', 'value' => '', 'label' => $_label_agreement, 'attribute' => 'required');
        }
        $params['fields'][] = array('name' => '', 'type' => 'plain_text', 'value' => 'We accept payment in SGD. Total amount payable: SGD 19.80', 'label' => '', 'attribute' => '');
        $params['fields'][] = array('name' => '', 'type' => 'submit', 'value' => 'Checkout', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"');

        $renderer = Renderer_Cart::getInstanceFromSmartyParams($params);
        // $couponForm = $renderer->_getDiscountForm(array('link' => '/cart?return='.htmlentities($_SERVER['REQUEST_URI']),'discounts' => ''));
        $html = Helper_Renderer::renderForm($params);
        return $html;
    }

    static function sircloRenderAccountInfo($params) {
        $html = "";
        $_member_name = !empty($params['member']['first_name']) ? $params['member']['first_name'] : '';
        $_member_phone = !empty($params['member']['phone']) ? $params['member']['phone'] : '';
        $_member_address = !empty($params['member']['address_line1']) ? $params['member']['address_line1'] : '';
        $_member_postal_code = !empty($params['member']['postal_code']) ? $params['member']['postal_code'] : '';
        $_member_city = !empty($params['member']['city']) ? $params['member']['city'] : '';
        $_member_state = !empty($params['member']['state']) ? $params['member']['state'] : '';
        $_member_country = !empty($params['member']['country']) ? $params['member']['country'] : '';
        $_member_email = !empty($params['member']['email']) ? $params['member']['email'] : '';
		$_member_dob = !empty($params['member']['dob_timestamp']) ? date('d/m/Y',$params['member']['dob_timestamp']) : '';
        $_table_class = !empty($params['table_class']) ? $params['table_class'] : '';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
		
		$_label_halo = $_lang == "id" ? "Halo, ".$_member_name."!" : "Hello, ".$_member_name."!";
		$_label_description = $_lang == "id" ? "Kamu dapat melihat profil akunmu serta mengubah informasi pada profilmu." : "From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link to view or edit information.";
        $_label_name = $_lang == "id" ? "Nama" : "Name";
        $_label_phone = $_lang == "id" ? "No HP" : "Phone";
        $_label_address = $_lang == "id" ? "Alamat" : "Address";
        $_label_postal_code = $_lang == "id" ? "Kode Pos" : "Postal Code";
        $_label_city = $_lang == "id" ? "Kota/Kecamatan" : "City";
        $_label_state = $_lang == "id" ? "Provinsi" : "State";
        $_label_country = $_lang == "id" ? "Negara" : "Country";
        $_label_email = $_lang == "id" ? "E-mail" : "E-mail";
		
		$_label_dob = $_lang == "id" ? "Tanggal lahir" : "Date of Birth";
		
		$html .= 
		"
			<div class='s-bottom-margin'>$_label_halo</div>
			<p>$_label_description</p>
			<br/>

			<div id='ai' class='s-bottom-margin'><big>ACCOUNT INFO</big>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<a class='attention' href=''>edit</a>)</div>
			<span class='fixed-width'>$_label_name</span>: $_member_name<br/>
			<span class='fixed-width'>$_label_address</span>: $_member_address<br/>
			<span class='fixed-width'>$_label_postal_code</span>: $_member_postal_code<br/>
			<span class='fixed-width'>$_label_city</span>: $_member_city<br/>
			<span class='fixed-width'>$_label_state</span>: $_member_state<br/>
			<span class='fixed-width'>$_label_country</span>: $_member_country<br/>
			<span class='fixed-width'>$_label_phone</span>: $_member_phone<br/>
			<span class='fixed-width'>$_label_dob</span>: $_member_dob<br/><br/>

			<br/>";
		
		/*
        $html .= "
        <div id='account-detail'>
            <table class='$_table_class'>
                <tr>
                    <td class='strong' width='20%'>$_label_name</td>
                    <td>$_member_name</td>
                </tr>
                <tr>
                    <td class='strong'>$_label_phone</td>
                    <td>$_member_phone</td>
                </tr>
                <tr>
                    <td class='strong'>$_label_address</td>
                    <td>$_member_address</td>
                </tr>
                <tr>
                    <td class='strong'>$_label_postal_code</td>
                    <td>$_member_postal_code</td>
                </tr>
                <tr>
                    <td class='strong'>$_label_city</td>
                    <td>$_member_city</td>
                </tr>
                <tr>
                    <td class='strong'>$_label_state</td>
                    <td>$_member_state</td>
                </tr>
                <tr>
                    <td class='strong'>$_label_country</td>
                    <td>$_member_country</td>
                </tr>
                <tr>
                    <td class='strong'>$_label_email</td>
                    <td>$_member_email</td>
                </tr>
            </table>
        </div>"; */

        return $html;
    }

    static function sircloRenderAccountEditInfo($params) {
        $_member_salutation = !empty($params['member']['salutation']) ? $params['member']['salutation'] : '';
        $_member_name = !empty($params['member']['first_name']) ? $params['member']['first_name'] : '';
        $_member_phone = !empty($params['member']['phone']) ? $params['member']['phone'] : '';
        $_member_address = !empty($params['member']['address_line1']) ? $params['member']['address_line1'] : '';
        $_member_postal_code = !empty($params['member']['postal_code']) ? $params['member']['postal_code'] : '';
        $_member_city = !empty($params['member']['city']) ? $params['member']['city'] : '';
        $_member_country = !empty($params['member']['country']) ? $params['member']['country'] : '';
        if (!$_member_country && !empty($params['default_country'])) {
            $_member_country = $params['default_country'];
        }
        $_member_state = !empty($params['member']['state']) ? $params['member']['state'] : '';
        $_member_email = !empty($params['member']['email']) ? $params['member']['email'] : '';
        $_member_birthday = !empty($params['member']['dob_timestamp']) ? date('j F Y', $params['member']['dob_timestamp']) : '';
        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_salutation = $_lang == "id" ? "Sapaan" : "Salutation";
        $_label_name = $_lang == "id" ? "Nama" : "Name";
        $_label_phone = $_lang == "id" ? "No HP" : "Mobile Phone";
        $_label_address = $_lang == "id" ? "Alamat" : "Address";
        $_label_postal_code = $_lang == "id" ? "Kode Pos" : "Postal Code";
        $_label_city = $_lang == "id" ? "Kota/Kecamatan" : "City/District";
        $_label_state = $_lang == "id" ? "Provinsi" : "State/Province";
        $_label_country = $_lang == "id" ? "Negara" : "Country";
        $_label_email = $_lang == "id" ? "E-mail" : "E-mail";
        $_label_birthday = $_lang == "id" ? "Tanggal Lahir" : "Birthdate";

        if (!empty($params['address_label']) && $params['address_label'] == 'billing') {
            $_label_address_subheader = $_lang == "id" ? "Alamat" : "Address";
        }
        else {
            $_label_address_subheader = $_lang == "id" ? "Alamat Pengiriman" : "Delivery Address";
        }

        $params['fields'] = array();
        if (!empty($params['with_salutation'])) {
            $params['fields'][] = array('name' => 'salutation', 'type' => 'salutation', 'value' => $_member_salutation, 'label' => $_label_salutation, 'attribute' => 'required');
        }
        $params['fields'][] = array('name' => 'first_name', 'type' => 'text', 'value' => $_member_name, 'label' => $_label_name, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'phone', 'type' => 'text', 'value' => $_member_phone, 'label' => $_label_phone, 'attribute' => 'required');

        if (!empty($params['with_birthday'])) {
            $params['fields'][] = array('name' => 'dob', 'type' => 'text', 'value' => $_member_birthday, 'label' => $_label_birthday, 'attribute' => '');
            $params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_address_subheader, 'label' => '', 'attribute' => '');
        }

        $params['fields'][] = array('name' => 'address_line1', 'type' => 'text', 'value' => $_member_address, 'label' => $_label_address, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'country', 'type' => 'country', 'value' => $_member_country, 'label' => $_label_country, 'attribute' => 'required data-area-autocomplete="country"');
        $params['fields'][] = array('name' => 'state', 'type' => 'text', 'value' => $_member_state, 'label' => $_label_state, 'attribute' => 'required data-area-autocomplete="state"');
        $params['fields'][] = array('name' => 'city', 'type' => 'text', 'value' => $_member_city, 'label' => $_label_city, 'attribute' => 'required data-area-autocomplete="city"');
        $params['fields'][] = array('name' => 'postal_code', 'type' => 'text', 'value' => $_member_postal_code, 'label' => $_label_postal_code, 'attribute' => 'required');
        $params['fields'][] = array('name' => 'email', 'type' => 'email', 'value' => $_member_email, 'label' => $_label_email, 'attribute' => 'disabled');
        $params['fields'][] = array('name' => '', 'type' => 'submit', 'value' => 'Update Account Info', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"');

        $html = Helper_Renderer::renderForm($params);
        return $html;
    }

    static function sircloRenderAccountEditPassword($params) {
        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_old_password = $_lang == "id" ? "Password Sekarang" : "Current Password";
        $_label_new_password = $_lang == "id" ? "Password Baru" : "New Password";
        $_label_new_password_confirmation = $_lang == "id" ? "Konfirmasi Password Baru" : "Confirm New Password";

        $params['fields'] = array(
            array('name' => 'old_password', 'type' => 'password', 'value' => '', 'label' => $_label_old_password, 'attribute' => 'required class ="s-fullwidth"'),
            array('name' => 'new_password', 'type' => 'password', 'value' => '', 'label' => $_label_new_password, 'attribute' => 'required class ="s-fullwidth"'),
            array('name' => 'confirm_new_password', 'type' => 'password', 'value' => '', 'label' => $_label_new_password_confirmation, 'attribute' => 'required class ="s-fullwidth"'),
            array('name' => '', 'type' => 'submit', 'value' => 'CHANGE PASSWORD', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));

        $html = Helper_Renderer::renderForm($params);
        return $html;
    }

    static function sircloRenderLogin($params) {
        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_email = $_lang == "id" ? "E-mail" : "E-mail";
        $_label_password = $_lang == "id" ? "Password" : "Password";

        $params['fields'] = array(
            array('name' => 'username', 'type' => 'email', 'value' => '', 'label' => 'E-mail', 'attribute' => 'required'),
            array('name' => 'password', 'type' => 'password', 'value' => '', 'label' => 'Password', 'attribute' => 'required'),
            array('name' => '', 'type' => 'submit', 'value' => 'Submit', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));

        $_html = Helper_Renderer::renderForm($params);
        return $_html;
    }

    static function sircloRenderRegisterForm($params) {
        $_link_terms = !empty($params['terms']) ? $params['terms'] : '';
        $_link_privacy = !empty($params['privacy']) ? $params['privacy'] : '';

        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_name = $_lang == "id" ? "Nama" : "Name";
        $_label_salutation = $_lang == "id" ? "Sapaan" : "Salutation";
        $_label_phone = $_lang == "id" ? "No HP" : "Phone";
        $_label_password = $_lang == "id" ? "Password" : "Password";
        $_label_password_confirm = $_lang == "id" ? "Konfirmasi Password" : "Confirm Password";
        $_label_address = $_lang == "id" ? "Alamat" : "Address";
        $_label_postal_code = $_lang == "id" ? "Kode Pos" : "Postal Code";
        $_label_city = $_lang == "id" ? "Kota/Kecamatan" : "City";
        $_label_state = $_lang == "id" ? "Provinsi" : "State";
        $_label_country = $_lang == "id" ? "Negara" : "Country";
        $_label_email = $_lang == "id" ? "E-mail" : "Your Email";
        $_label_birthday = $_lang == "id" ? "Tanggal Lahir" : "Date of Birth";
        $_default_country = !empty($params['default_country']) ? $params['default_country'] : '';
		
		$_label_accountdetails_subheader = $_lang == "id" ? "Detail akun" : "Account Details";
		$_label_personalparticular_subheader = $_lang == "id" ? "Informasi Personal" : "Personal Particular";
		
        if (!empty($params['address_label']) && $params['address_label'] == 'billing') {
            $_label_address_subheader = $_lang == "id" ? "Alamat Penagihan" : "Address";
        }
        else {
            $_label_address_subheader = $_lang == "id" ? "Alamat Pengiriman" : "Delivery Address";
        }

        /*
        if ($_lang == "id") {
            $_label_agreement = "Saya telah membaca dan menyetujui <a href='$_link_terms'>Syarat dan Ketentuan</a> dan <a href='$_link_privacy'>Kebijakan Privasi</a>";
        }
        else {
            $_label_agreement = "I agree to the <a href='$_link_terms'>Terms of Use</a> and <a href='$_link_privacy'>Privacy Policy</a>";
        } */
        
        $_label_agreement = 'I agree to the Privacy Policy and Terms & Condition';
        if (!empty($params['configs']['text_agreement'])) {
            $_label_agreement = strip_tags($params['configs']['text_agreement'], '<a>');
        }
        $_label_newsletter = $_lang == 'id' ? 'Saya ingin menerima penawaran melalui email' : 'Keep me updated with latest news and promotions';

        $params['fields'] = array();
		$params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_accountdetails_subheader, 'label' => '', 'attribute' => '');
        $params['fields'][] = array('name' => '', 'type' => 'div', 'value' => '', 'label' => '', 'attribute' => 'class="address address-autocomplete"');
        $params['fields'][] = array('name' => 'email', 'type' => 'email', 'value' => '', 'label' => $_label_email, 'attribute' => 'class ="s-fullwidth"');

        $params['fields'][] = array('name' => 'password', 'type' => 'password', 'value' => '', 'label' => $_label_password, 'attribute' => 'class ="s-fullwidth"');
        $params['fields'][] = array('name' => 'confirm_password', 'type' => 'password', 'value' => '', 'label' => $_label_password_confirm, 'attribute' => 'class ="s-fullwidth"');
		
		$params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_personalparticular_subheader, 'label' => '', 'attribute' => '');
        
		
		/*
		if (!empty($params['with_salutation'])) {
            $params['fields'][] = array('name' => 'salutation', 'type' => 'salutation', 'value' => '', 'label' => $_label_salutation, 'attribute' => 'required');
        }*/
        $params['fields'][] = array('name' => 'first_name', 'type' => 'text', 'value' => '', 'label' => $_label_name, 'attribute' => 'required class ="s-fullwidth"');

        $params['fields'][] = array('name' => 'address_line1', 'type' => 'text', 'value' => '', 'label' => $_label_address, 'attribute' => 'required class ="s-fullwidth"');
        $params['fields'][] = array('name' => 'postal_code', 'type' => 'text', 'value' => '', 'label' => $_label_postal_code, 'attribute' => 'required class ="s-fullwidth"');
        $params['fields'][] = array('name' => 'city', 'type' => 'text', 'value' => '', 'label' => $_label_city, 'attribute' => 'required data-area-autocomplete="city" class ="s-fullwidth"');
        $params['fields'][] = array('name' => 'state', 'type' => 'text', 'value' => '', 'label' => $_label_state, 'attribute' => 'required data-area-autocomplete="state" class ="s-fullwidth"');
		$params['fields'][] = array('name' => 'country', 'type' => 'country', 'value' => $_default_country, 'label' => $_label_country, 'attribute' => 'required data-area-autocomplete="country" class ="s-fullwidth"');
        $params['fields'][] = array('name' => 'phone', 'type' => 'text', 'value' => '', 'label' => $_label_phone, 'attribute' => 'required class ="s-fullwidth"');
		if (!empty($params['with_birthday'])) {
            $params['fields'][] = array('name' => 'dob', 'type' => 'date', 'value' => '', 'label' => $_label_birthday, 'attribute' => 'required class ="s-fullwidth"');
            //$params['fields'][] = array('name' => '', 'type' => 'subheader', 'value' => $_label_address_subheader, 'label' => '', 'attribute' => '');
        }
		
		//$params['fields'][] = array('name' => 'is_subscribe_newsletter', 'type' => 'checkbox', 'label' => $_label_newsletter, 'attribute' => 'checked');
        if (!empty($_label_agreement)) {
            $params['fields'][] = array('name' => 'agreement', 'type' => 'checkbox', 'value' => '', 'label' => $_label_agreement, 'attribute' => 'required');
        }
        //$params['fields'][] = array('name' => 'is_subscribe_newsletter', 'type' => 'hidden', 'value' => '1', 'label' => '', 'attribute' => '');
        $params['fields'][] = array('name' => '', 'type' => 'div_close', 'value' => '', 'label' => '', 'attribute' => '');
        $params['fields'][] = array('name' => '', 'type' => 'submit', 'value' => 'REGISTER', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"');

        $_html = Helper_Renderer::renderForm($params);
        return $_html;
    }

    static function sircloRenderOrderList($params) {

        $html = "";
        $_orders = !empty($params['orders']) ? $params['orders'] : array();
        $_table_class = !empty($params['table_class']) ? $params['table_class'] : '';
        $_links_account = !empty($params['links_account']) ? $params['links_account'] : '';
        $_currency = !empty($params['currency']) ? $params['currency'] : '';
        $_digital = !empty($params['digital']);

        $_lang = !empty($params['lang']) ? $params['lang'] : 'en';
        $_label_order_id = $_lang == 'id' ? 'Order ID' : 'Order ID';
        $_label_date = $_lang == 'id' ? 'Tanggal' : 'Date';
        $_label_items = $_lang == 'id' ? 'Barang' : 'Items';
        $_label_total = $_lang == 'id' ? 'Total' : 'Total';
        $_label_payment = $_lang == 'id' ? 'Pembayaran' : 'Payment';
        $_label_shipment = $_lang == 'id' ? 'Pengiriman' : 'Shipment';
        $_label_remarks = $_lang == 'id' ? 'Pesan' : 'Remarks';
        $_label_invoice = $_lang == 'id' ? 'Lihat Invoice' : 'View Invoice';
        $_label_confirm_payment = $_lang == 'id' ? 'Konfirmasi Pembayaran' : 'Confirm Payment';
        $_label_back_to_account = $_lang == 'id' ? 'Kembali ke Akun Saya' : 'Back to My Account';

        if (!empty($_orders)) {
            $_html_table = "";
            foreach ($_orders as $_order) {
                $_payment_status = $_order['status'];
                $_shipment_status = $_order['status'];

                $_html_table_order_items = "";
                foreach ($_order['items'] as $_order_item) {
                    $_html_table_order_items .= "<li>$_order_item</li>";
                }

                if (($_order['payment_method'] != 'paypal') && ($_payment_status == 'Belum Dibayar' || $_payment_status == 'Not Yet Paid')) {
                    $_payment_status_link = '';
                    $_payment_status_link = "/payment_notif?".http_build_query(array('total_amount' => $_order['total_amount_raw'], 'order_id' => $_order['order_id'], 'order_email' => $_order['billing_info']['email']));
                    $_payment_status_html = "<a target=\"_blank\" href='".$_payment_status_link."'>$_label_confirm_payment</a>";
                }
                else {
                    $_payment_status_html = "&nbsp;";
                }

                if ($_digital) {
                    $_html_table .= "
                    <tr>
                        <td>".$_order['order_id']."</td>
                        <td>".date('j F Y', $_order['order_timestamp'])."</td>
                        <td><ul>".$_html_table_order_items."</ul></td>
                        <td>".Helper_String::dollarFormat($_order['total_amount_raw'], 2, $_currency)."</td>
                        <td>".$_payment_status."</td>
                        <td>".$_order['merchant_remarks']."</td>
                        <td class='invoice'><a target=\"_blank\" href='".$_order['invoice_link']."'>$_label_invoice</a></td>
                        <td class='confirm-payment'>$_payment_status_html</td>
                    </tr>";
                }
                else {
                    $_html_table .= "
                    <tr>
                        <td>".$_order['order_id']."</td>
                        <td><ul>".$_html_table_order_items."</ul></td>
                        <td>".Helper_String::dollarFormat($_order['total_amount_raw'], 2, $_currency)."</td>
                        <td>".$_payment_status."</td>
                        <td>".$_shipment_status."</td>
                        <td>".$_order['merchant_remarks']."</td>
                        <td class='invoice'><a target=\"_blank\" href='".$_order['invoice_link']."'>$_label_invoice</a></td>
                        <td class='confirm-payment'>$_payment_status_html</td>
                    </tr>";
                }

            }

            if ($_digital) {
                $html .= "
                <div id='account-order-history'>
                    <table class='".$_table_class."'>
                        <tr>
                            <th>$_label_order_id</th>
                            <th>$_label_date</th>
                            <th>$_label_items</th>
                            <th>$_label_total</th>
                            <th>$_label_payment</th>
                            <th>$_label_remarks</th>
                            <th class=''></th>
                            <th class=''></th>
                        </tr>".
                        $_html_table.
                    "</table>
                </div>
                <div class='footer-div'>
                    <a href='".$_links_account."' class='btn-flat'>$_label_back_to_account</a>
                </div>";
            }
            else {
                $html .= "
                <div id='account-order-history'>
                    <table class='".$_table_class."'>
                        <tr>
                            <th>$_label_order_id</th>
                            <th>$_label_items</th>
                            <th>$_label_total</th>
                            <th>$_label_payment</th>
                            <th>$_label_shipment</th>
                            <th>$_label_remarks</th>
                            <th class='invoice'></th>
                            <th class=''></th>
                        </tr>".
                        $_html_table.
                    "</table>
                </div>
                <div class='footer-div'>
                    <a href='".$_links_account."' class='btn-flat'>$_label_back_to_account</a>
                </div>";
            }
        }

        return $html;
    }

    static function sircloRenderResetPassword($params) {
        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_email = $_lang == "id" ? "E-mail" : "E-mail";

        $params['fields'] = array(
            array('name' => 'email', 'type' => 'email', 'value' => '', 'label' => $_label_email, 'attribute' => 'required'),
            array('name' => '', 'type' => 'submit', 'value' => 'Reset Password', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));

        $html = Helper_Renderer::renderForm($params);
        return $html;
    }

    static function sircloRenderResetPasswordEdit($params) {
        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_new_password = $_lang == "id" ? "Password Baru" : "New Password";
        $_label_new_password_confirmation = $_lang == "id" ? "Konfirmasi Password Baru" : "Confirm New Password";

        $params['fields'] = array(
            array('name' => 'new_password', 'type' => 'password', 'value' => '', 'label' => $_label_new_password, 'attribute' => 'required'),
            array('name' => 'confirm_new_password', 'type' => 'password', 'value' => '', 'label' => $_label_new_password_confirmation, 'attribute' => ''),
            array('name' => '', 'type' => 'submit', 'value' => 'Reset Password', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));

        $html = Helper_Renderer::renderForm($params);
        return $html;
    }

    static function sircloRenderPaymentNotifForm($params) {
        $_order_id = !empty($params['order_id']) ? $params['order_id'] : '';
        $_order_email = !empty($params['order_email']) ? $params['order_email'] : '';

        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_order_id = $_lang == "id" ? "Order ID" : "Order ID";
        $_label_transaction_date = $_lang == "id" ? "Tanggal Transaksi" : "Transaction Date";
        $_label_transaction_ref = $_lang == "id" ? "Nama Pengirim" : "Sender Name";
        $_label_amount_transferred = $_lang == "id" ? "Jumlah Ditransfer" : "Amount Transferred";
        $_label_email = $_lang == "id" ? "E-mail" : "Email";
        $_label_confirm_payment = $_lang == "id" ? "Konfirmasi Pembayaran" : "Confirm Payment";
        $_label_bank_to = $_lang == "id" ? "Pembayaran ke Bank" : "Payment to";
        $_label_receipt_file = $_lang == "id" ? "Bukti Transfer (opsional)" : "Receipt File (optional)";

        if (!empty($params['bank_accounts'])) {
            $_bank_accounts = array_map(function ($acc) { return $acc['title']; }, $params['bank_accounts']);
        } else if (!empty($params['configs']['theme_bank_accounts'])) {
            $_bank_accounts = Helper_String::commaStrToArr($params['configs']['theme_bank_accounts']);
        }
        if (!empty($_bank_accounts)) {
            $_bank_accounts_options = array(
                array(
                    'title' => '-- Select Bank Account --',
                    'value' => '',
                ),
            );
            foreach ($_bank_accounts as $_ba) {
                $_bank_accounts_options[] = array(
                    'title' => trim($_ba),
                    'value' => trim($_ba)
                );
            }
            $_bank_account_form = array('name' => 'bank_to', 'type' => 'dropdown', 'value' => '', 'label' => $_label_bank_to, 'attribute' => 'required', 'options' => $_bank_accounts_options);

            $params['fields'] = array(
                array('name' => 'order_id', 'type' => 'text', 'value' => $_order_id, 'label' => $_label_order_id, 'attribute' => 'required'),
                array('name' => 'transaction_date', 'type' => 'text', 'value' => '', 'label' => $_label_transaction_date, 'attribute' => 'required'),
                array('name' => 'transaction_reference', 'type' => 'text', 'value' => '', 'label' => $_label_transaction_ref, 'attribute' => 'required'),
                array('name' => 'amount_transfered', 'type' => 'text', 'value' => '', 'label' => $_label_amount_transferred, 'attribute' => 'required'),
                $_bank_account_form,
                array('name' => 'email', 'type' => 'email', 'value' => $_order_email, 'label' => $_label_email, 'attribute' => 'required'),
                array('name' => 'attachment', 'type' => 'file', 'label' => $_label_receipt_file),
                array('name' => '', 'type' => 'submit', 'value' => $_label_confirm_payment, 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));
        }
        else {
            $params['fields'] = array(
            array('name' => 'order_id', 'type' => 'text', 'value' => $_order_id, 'label' => $_label_order_id, 'attribute' => 'required'),
            array('name' => 'transaction_date', 'type' => 'text', 'value' => '', 'label' => $_label_transaction_date, 'attribute' => 'required'),
            array('name' => 'transaction_reference', 'type' => 'text', 'value' => '', 'label' => $_label_transaction_ref, 'attribute' => 'required'),
            array('name' => 'amount_transfered', 'type' => 'text', 'value' => '', 'label' => $_label_amount_transferred, 'attribute' => 'required'),
            array('name' => 'email', 'type' => 'email', 'value' => $_order_email, 'label' => $_label_email, 'attribute' => 'required'),
            array('name' => 'attachment', 'type' => 'file', 'label' => $_label_receipt_file),
            array('name' => '', 'type' => 'submit', 'value' => $_label_confirm_payment, 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));
        }

        $_html = Helper_Renderer::renderForm($params);
        return $_html;
    }

    static function sircloRenderContactForm($params) {
        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_name = $_lang == "id" ? "Nama" : "Your Name";
        $_label_email = $_lang == "id" ? "E-mail" : "Your Email";
		$_label_subject = $_lang == "id" ? "Subyek" : "Subject";
        $_label_message = $_lang == "id" ? "Pesan" : "Message";

        $params['fields'] = array(
        array('name' => 'name', 'type' => 'text', 'value' => '', 'label' => $_label_name, 'attribute' => ''),
        array('name' => 'email', 'type' => 'email', 'value' => '', 'label' => $_label_email, 'attribute' => ''),
        array('name' => 'subject', 'type' => 'text', 'value' => '', 'label' => $_label_subject, 'attribute' => ''),
        array('name' => 'message', 'type' => 'textarea', 'value' => '', 'label' => $_label_message, 'attribute' => 'rows = "5"'),
        array('name' => '', 'type' => 'submit', 'value' => 'SEND', 'label' => '', 'attribute' => 'class="btn-flat"'));

        $_html = Helper_Renderer::renderForm($params);
        return $_html;
    }

    static function sircloRenderShippingMethods($params) {
        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_options = array();
        foreach ($params['options'] as $o) {
            $_options[] = array('title' => $o, 'value' => $o);
        }
        $input_name = 'shipping_value';
        $_selected_key = 'shipping_destination';
        if (!empty($params['shipping_type'])) {
            $input_name = $params['shipping_type'] . "_$input_name";
            $_selected_key = 'backorder_shipping_destination';
            $_options = array_merge(array(array('title' => 'None', 'value' => '')), $_options);
        }
        $_selected = !empty($params['cart'][$_selected_key]) ? $params['cart'][$_selected_key] : '';

        if ($_lang == "id") {
            $_label_shipping_methods_empty = "Tidak ada metode pengiriman yang tersedia.";
        }
        else {
            $_label_shipping_methods_empty = "There are no available shipping methods.";
        }

        $_form_row = array('name' => $input_name, 'type' => 'radio', 'value' => $_options, 'value_empty' => $_label_shipping_methods_empty, 'label' => '', 'selected' => $_selected, 'attribute' => 'required');
        $html = Helper_Renderer::renderFormRow($_form_row);
        return $html;
    }

    static function sircloRenderTestimonialForm($params) {
        $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_name = $_lang == "id" ? "Nama Anda" : "Your Name";
        $_label_author_desc = $_lang == "id" ? "Profil Anda" : "Your Profile";
        $_label_testimonial = $_lang == "id" ? "Testimonial" : "Testimonial";
        $_label_email = $_lang == "id" ? "Email" : "Email";
        $_label_company = $_lang == "id" ? "Company" : "Company";
        $_label_website = $_lang == "id" ? "Website" : "Website";
        $_label_image = $_lang == "id" ? "Picture" : "Picture";

        $params['fields'] = array(
            array('name' => 'author', 'type' => 'text', 'value' => '', 'label' => $_label_name, 'attribute' => 'required'),
            //array('name' => 'author_description', 'type' => 'text', 'value' => '', 'label' => $_label_author_desc, 'attribute' => ''),
            array('name' => 'email', 'type' => 'text', 'value' => '', 'label' => $_label_email, 'attribute' => ''),
            array('name' => 'company', 'type' => 'text', 'value' => '', 'label' => $_label_company, 'attribute' => ''),
            array('name' => 'website', 'type' => 'text', 'value' => '', 'label' => $_label_website, 'attribute' => ''),
            array('name' => 'content', 'type' => 'textarea', 'value' => '', 'label' => $_label_testimonial, 'attribute' => 'required'),
            array('name' => 'picture', 'type' => 'file', 'label' => $_label_image),
            array('name' => '', 'type' => 'submit', 'value' => 'Submit Testimonial', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));

        $html = Helper_Renderer::renderForm($params);
        return $html;
    }

    static function sircloRenderProductAddToCart($params) {
        $_html = "";
        $_form_html = "";
        $_action = "";
        $_member_email = "";
        $_product = array();
        $_show_options = true;
        $_show_quantity = false;
        $_quantity_label = "";
        $_btn_class = "btn-flat";
        $_btn_onclick = "";

        $_lang = !empty($params['lang']) ? $params['lang'] : "en";
        $_label_select = $_lang == "id" ? 'Pilih' : 'Select';
        $_label_add_to_cart = $_lang == "id" ? 'Tambah ke Keranjang Belanja' : 'Add to Cart';

        if (!empty($params['action'])) $_action = $params['action'];
        if (!empty($params['member_email'])) $_member_email = $params['member_email'];
        if (!empty($params['product'])) $_product = $params['product'];
        if (!empty($params['show_options'])) $_show_options = $params['show_options'];
        if (!empty($params['show_quantity'])) $_show_quantity = $params['show_quantity'];
        if (!empty($params['quantity_label'])) $_quantity_label = $params['quantity_label'];
        if (!empty($params['btn_class'])) $_btn_class = $params['btn_class'];
        if (!empty($params['btn_onclick'])) $_btn_onclick = $params['btn_onclick'];

        if (!empty($_product['general_options']) && $_show_options) {
            foreach ($_product['general_options'] as $go_key => $go_value) {
                if (!empty($go_value['options'])) {
                    $_form_html .= "<select name='option_".$go_value['value']."' id='product-option-".$go_key."'>";
                    $_form_html .= "<option value=''>$_label_select ".$go_value['title']."</option>";
                    foreach ($go_value['options'] as $goo_value) {
                        $_form_html .= "<option value='$goo_value'>$goo_value</option>";
                    }
                    $_form_html .= "</select>";
                }
            }
        }
        if ($_show_quantity) {
            $_form_html .= $_quantity_label;
            $_form_html .= "<input type='number' name='quantity' min='1' step='1' value='1'>";
        }

        $_html .= "
        <div id='add-to-cart'>
            <form id='form-add-to-cart' action='$_action' method='post' novalidate>
                $_form_html
                <p id='product-stock-status'></p>
                <input type='hidden' name='cmd' value='add'>
                <input type='hidden' name='item_id' value='".$_product['fid']."'>
                <input type='hidden' name='member_email' value='$_member_email'>

                <button id='product-add-to-cart' class='btn btn-lg blue-button s-add-to-cart' value='$_label_add_to_cart' onclick='$_btn_onclick'>ADD TO CART</button>
                <span id='product-member-status' class='error'></span>
                <br/>
                <div class='s-stock-status s-bebas s-top-less-margin calm'>IN STOCK</div>
            </form>
        </div>";

        return $_html;
    }

    static function renderPaging($paging, $stretch_lim=7, $options=array())
    {
        $p_cur = $paging['current_page'];
        $p_tot = $paging['total_pages'];
        $p_link = $paging['link'];

        if ($p_tot <= 1) {
            return NULL;
        }

        $pg = '';
        $pg .= "<div class=\"paging\">\n";
        $pg .= "<p>\n";
        $prev = 'prev';
        $next = 'next';
        if (isset($options['text_prev'])) {
            $prev = $options['text_prev'];
        }
        if (isset($options['text_next'])) {
            $next = $options['text_next'];
        }
        if ($p_cur > 1) {
            $link = self::addPageToUrl($p_link, $p_cur-1);
            $pg .= "<a class=\"page-prev\" href=\"$link\">$prev</a>\n";
        } else {
            $pg .= "<span class=\"page-prev\">$prev</span>\n";
        }
        $start = 1;
        if ($p_cur-1 >= $stretch_lim) {
            $start = $p_cur - $stretch_lim;
        }
        $end = $p_tot;
        if ($p_tot-$p_cur >= $stretch_lim) {
            $end = $p_cur + $stretch_lim;
        }
        if ($start > 1) {
            $link = self::addPageToUrl($p_link, 1);
            $pg .= "<a href=\"$link\">1</a>\n";
            if ($start > 2) {
                $pg .= "<span>&hellip;</span>\n";
            }
        }
        for ($i = $start; $i <= $end; $i++) {
            $link = self::addPageToUrl($p_link, $i);
            if ($p_cur == $i) {
                $pg .= "<span class=\"page-active\">$i</span>\n";
            } else {
                $pg .= "<a href=\"$link\">$i</a>\n";
            }
        }
        if ($end < $p_tot) {
            if ($end < $p_tot-1) {
                $pg .= "<span>&hellip;</span>\n";
            }
            $link = self::addPageToUrl($p_link, $p_tot);
            $pg .= "<a href=\"$link\">$p_tot</a>\n";
        }
        if ($p_cur < $p_tot) {
            $link = self::addPageToUrl($p_link, $p_cur+1);
            $pg .= "<a class=\"page-next\" href=\"$link\">$next</a>\n";
        } else {
            $pg .= "<span class=\"page-next\">$next</span>\n";
        }
        $pg .= "</p>\n";
        $pg .= "</div>\n";
        return $pg;
    }

    static function addPageToUrl($url, $page)
    {
        return Helper_URL::addGetToUrl($url, ($page!=1) ? "page=$page" : NULL);
    }

}
