<?php
class Renderer_Cart extends PageView
{
    public $messages = array();

    function __construct()
    {
        $this->withPoints = TRUE;
    }

    function dol($amount)
    {
        $finalAmount = $amount;
        $curSymbol = $this->cart['currency_symbol'];
        if (isset($this->currencies)) {
            $activeCur = Helper_Cart::getActiveCurrency($this->currencies);
            if ($activeCur) {
                $rate = $activeCur['exchange_rate'];
                $finalAmount = $rate * $amount;
                $curSymbol = $activeCur['currency'];
                if (!empty($this->withCurrencySymbol)) {
                    $curSymbol = $activeCur['symbol'];
                }
            }
        }
        $space = ' ';
        if (isset($this->withCurrencySpace) && ($this->withCurrencySpace === FALSE)) {
            $space = '';
        }
        return $curSymbol . $space . number_format($finalAmount, 2);
    }

    function optionsToHtmlPairs($item)
    {
        $options = array();
        if (isset($item['options'])) {
            foreach ($item['options'] as $key => $val) {
                $options["option_$key"] = $val;
            }
        }
        return $options;
    }

    function getItemTitle($item, $withHtml=FALSE, $options=array(), $renderer=NULL)
    {
        return Helper_Cart::getItemTitle($item, $withHtml, $options, $renderer, $this);
    }

    function _getImageName($item)
    {
        $suffix = '_tn';
        if (isset($this->thumbnailSuffix)) {
            $suffix = $this->thumbnailSuffix;
        }
        $imageName = '';
        if ($item['image']) {
            $imageName = Helper_File::addSuffix($item['image'], $suffix);
        }
        if (!empty($item['options']['_image_url'])) {
            $imageName = $item['options']['_image_url'];
        }
        return $imageName;
    }

    function _renderRow($desc, $price, $quantity, $amount)
    {
        $tds = '';
        if ($this->_withImage) {
            $tds .= $this->xmlTag('td', '&nbsp;', array('class' => 'cart-cell-empty'));
        }
        $attrs = array('class' => 'number');
        if ($this->_getTableMode() != 'mini') {
            $attrs['colspan'] = 3;
        }
        $tds .= $this->xmlTag('td', $quantity, $attrs);
        $tds .= $this->xmlTag('td', $amount, array('class' => 'number dollar-value'));
        $row = $this->xmlTag('tr', $tds, array('class' => 'cart-total-row'));
        return $row;
    }

    function _getCountriesRaw()
    {
        $countriesRaw = array();
        if (isset($this->countries)) {
            $countriesRaw = $this->countries;
        }
        return $countriesRaw;
    }

    function _getCountries($shippingForm, $optionLabel)
    {
        $countriesRaw = $this->_getCountriesRaw();
        $countries = Helper_Paypal::getPaypalCountryOptions($countriesRaw);
        if (isset($shippingForm[$optionLabel])) {
            $countries = $shippingForm[$optionLabel];
        }
        return $countries;
    }

    function _getBackorderShippingForm($cart, $shippingForm)
    {
        if (isset($this->withBackorderShipping) && $this->withBackorderShipping) {
            return $this->_getGeneralShippingForm($cart, $shippingForm, 'backorder_shipping_destination', 'backorder_shipping', 'backorder_shipping_label', 'Ship backordered items separately via', 'options', "-- None --");
        }
        return '';
    }

    function _getShippingCountryForm($cart, $shippingForm)
    {
        if (isset($this->withShippingCountry) && $this->withShippingCountry) {
            return $this->_getGeneralShippingForm($cart, $shippingForm, 'shipping_country', 'shipping_country', 'shipping_country_label', 'Shipping Country', 'shipping_country_options');
        }
        return '';
    }

    function _getShippingCityForm($cart, $shippingForm)
    {
        return '';
        if (isset($this->withShippingCity) && $this->withShippingCity) {
            return $this->_getGeneralShippingForm($cart, $shippingForm, 'shipping_city', 'shipping_city', 'shipping_city_label', 'Shipping City', 'shipping_city_options');
        }
        return '';
    }

    function _getShippingForm($cart, $shippingForm)
    {
        if (!isset($this->withShipping) || $this->withShipping) {
            return $this->_getGeneralShippingForm($cart, $shippingForm, 'shipping_destination', 'shipping', 'label', 'Shipping');
        }
        return '';
    }

    function _getGeneralShippingForm($cart, $shippingForm, $dataKey, $inputKey, $labelKey, $defaultShippingLabel, $optionLabel='options', $pleaseSelectLabel='-- Please Select --')
    {
        $inputs = '';
        $country = Helper_Structure::getArrayValue($cart, $dataKey);
        $countryOptions = array(
            array(
                'title' => "$pleaseSelectLabel",
                'value' => '',
            ),
        );
        $countries = $this->_getCountries($shippingForm, $optionLabel);
        if (!$countries) {
            return '';
        }
        $countryOptions = array_merge($countryOptions, $countries);
        $inputs .= Helper_Xml::xmlInputHidden('cmd', $inputKey);
        $inputs .= Helper_Xml::xmlSelect('shipping_value', $countryOptions, $country);
        $inputs .= Helper_Xml::xmlEmptyTag('input', array(
            'type' => 'submit',
            'value' => 'Update',
        ));
        $shippingLabel = $defaultShippingLabel;
        if (isset($shippingForm[$labelKey])) {
            $shippingLabel = $shippingForm[$labelKey];
        }
        $label = Helper_Xml::xmlTag('span', $shippingLabel . ': ');
        $shippingForm = Helper_Xml::xmlTag('form', $label . $inputs, array(
            'action' => $cart['link'],
            'method' => 'post',
            'class' => 'cart-shipping',
        ));
        return $shippingForm;
    }

    function _getDiscountForm($cart)
    {
        if (isset($this->withDiscount) && !$this->withDiscount) {
            return '';
        }
        $inputs = '';
        $inputs .= Helper_Xml::xmlInputHidden('cmd', 'coupon');
        $codes = Helper_Cart::getDiscountCodes($cart['discounts']);
        $couponCode = $codes;
        $inputs .= Helper_Xml::xmlEmptyTag('input', array(
            'name' => 'coupon_code',
            'type' => 'text',
            'value' => $couponCode,
        ));
        $inputs .= Helper_Xml::xmlEmptyTag('input', array(
            'type' => 'submit',
            'value' => 'Update',
            'class' => 'btn btn-info'
        ));
        $discLabel = 'Discount Coupon Code';
        if (isset($this->discountLabel)) {
            $discLabel = $this->discountLabel;
        }
        $label = Helper_Xml::xmlTag('span', $discLabel . ': ');
        $discountForm = Helper_Xml::xmlTag('form', $label . $inputs, array(
            'action' => $cart['link'],
            'method' => 'post',
            'class' => 'cart-coupon',
        ));
        return $discountForm;
    }

    function _isShowDiscount($cart)
    {
        $isShow = (isset($cart['discounts']) && $cart['discounts']);
        return $isShow;
    }

    function _getLoyaltyForm($cart)
    {
        $pointRule = Helper_Structure::getArrayValue($cart, 'point_rule');
        if (isset($this->withPoints) && $this->withPoints && Helper_Structure::getArrayValue($pointRule, 'dollar_per_point')) {
            $inputs = '';
            $inputs .= Helper_Xml::xmlInputHidden('cmd', 'points');
            $npoints = $cart['npoints'];
            $inputs .= Helper_Xml::xmlEmptyTag('input', array(
                'type' => 'text',
                'name' => 'npoints',
                'value' => $npoints,
            ));
            $inputs .= Helper_Xml::xmlEmptyTag('input', array(
                'type' => 'submit',
                'value' => 'Update',
            ));
            $maxPoints = 0;
            if (isset($this->member['npoints'])) {
                $maxPoints = $this->member['npoints'];
            }
            $pointsLabel = $this->_getPointsLabel($maxPoints);
            $label = Helper_Xml::xmlTag('span', "$pointsLabel: ");
            $shippingForm = Helper_Xml::xmlTag('form', $label . $inputs, array(
                'action' => $cart['link'],
                'method' => 'post',
                'class' => 'cart-points',
            ));
            return $shippingForm;
        }
        return '';
    }

    function _getPointsLabel($maxPoints)
    {
        $pointsLabel = "Points to Use (You have $maxPoints points)";
        if (isset($this->pointsLabel)) {
            if (is_string($this->pointsLabel)) {
                $pointsLabel = str_replace('{max}', $maxPoints, $this->pointsLabel);
            } else {
                $labelGen = $this->pointsLabel;
                $pointsLabel = $labelGen($maxPoints);
            }
        }
        return $pointsLabel;
    }

    function _getDeliveryDateForm($cart)
    {
        if (isset($this->withDeliveryDate) && $this->withDeliveryDate) {
            $inputs = '';
            $inputs .= Helper_Xml::xmlInputHidden('cmd', 'delivery_date');
            $delDate = '';
            if ($cart['delivery_timestamp']) {
                $delDate = Helper_Date::formatSgpDatetime($cart['delivery_timestamp'], FALSE);
                if ($this->withDeliveryDate === 'date') {
                    $delDate = Helper_Date::formatSgpDate($cart['delivery_timestamp'], FALSE);
                }
            }
            $inputs .= Helper_Xml::xmlEmptyTag('input', array(
                'type' => 'text',
                'name' => 'delivery_date',
                'value' => $delDate,
            ));
            $inputs .= Helper_Xml::xmlEmptyTag('input', array(
                'type' => 'submit',
                'value' => 'Update',
            ));
            $ddLabel = 'Delivery Date';
            if (isset($this->deliveryDateLabel) && $this->deliveryDateLabel) {
                $ddLabel = $this->deliveryDateLabel;
            }
            $label = Helper_Xml::xmlTag('span', "$ddLabel: ");
            $shippingForm = Helper_Xml::xmlTag('form', $label . $inputs, array(
                'action' => $cart['link'],
                'method' => 'post',
                'class' => 'cart-delivery-date',
            ));
            return $shippingForm;
        }
        return '';
    }

    function renderCartEditForm($cart, $shippingFormOptions=array(), $options=array())
    {
        // $couponForm = $this->_getDiscountForm($cart);
        $shippingForm = $this->_getShippingForm($cart, $shippingFormOptions);
        $shippingCountryForm = $this->_getShippingCountryForm($cart, $shippingFormOptions);
        $shippingCityForm = $this->_getShippingCityForm($cart, $shippingFormOptions);
        $backorderShippingForm = $this->_getBackorderShippingForm($cart, $shippingFormOptions);
        $deliveryDateForm = $this->_getDeliveryDateForm($cart);
        $loyaltyForm = $this->_getLoyaltyForm($cart);
        $forms = /*$couponForm .*/ $shippingCountryForm . $shippingCityForm . $shippingForm . $backorderShippingForm . $deliveryDateForm . $loyaltyForm;
        $s = $this->xmlDiv($forms, array('class' => 'cart-edit-form'));
        return $s;
    }

    function _getCountryNameFromCountryCode($code)
    {
        $countries = $this->_getCountriesRaw();
        $revCountries = Helper_Structure::arrayKeyReverse($countries);
        $countryName = $code;
        if (isset($revCountries[$code])) {
            $countryName = $revCountries[$code];
        }
        return $countryName;
    }

    function _renderShippingRow($cart)
    {
        $countries = $this->_getCountriesRaw();
        $revCountries = Helper_Structure::arrayKeyReverse($countries);
        $shippingDesc = $this->_getShippingDesc($cart);
        $shippingPar = '';
        if ($shippingDesc) {
            $shippingPar = " ($shippingDesc)";
        }
        $shippingCell = "Shipping and Handling$shippingPar:";
        if (isset($this->withNewLineSeparator) && $this->withNewLineSeparator) {
            $shippingCell = "Shipping and Handling<br />$shippingDesc";
        }
        $row = $this->_renderRow('&nbsp;', '&nbsp;', $shippingCell, $this->dol($cart['shipping']));
        return $row;
    }

    function _getShippingCountryDesc($cart)
    {
        $desc = NULL;
        if (isset($this->withShippingCountry) && $this->withShippingCountry && $shippingCountry = Helper_Structure::getArrayValue($cart, 'shipping_country')) {
            $desc = $this->_getCountryNameFromCountryCode($shippingCountry);
            if (!empty($cart['shipping_city'])) {
                $desc = $desc . ' - ' . $cart['shipping_city'];
            }
        }
        return $desc;
    }

    function _getShippingDesc($cart)
    {
        $descs = array();
        $shippingDest = $this->_getCountryNameFromCountryCode($cart['shipping_destination']);
        $normSuff = '';
        $backSuff = '';
        if (isset($cart['shipping_details'])) {
            $norm = $this->dol($cart['shipping_details']['normal']);
            $back = $this->dol($cart['shipping_details']['backorder']);
            $normSuff = " ($norm)";
            $backSuff = " ($back)";
        }
        $countryDesc = $this->_getShippingCountryDesc($cart);
        if ($countryDesc) {
            $descs[] = $countryDesc;
        }
        $normDesc = $shippingDest;
        if (isset($this->withBackorderShipping) && $this->withBackorderShipping && ($backorderDest = Helper_Structure::getArrayValue($cart, 'backorder_shipping_destination')) /*&& ($backorderDest != $shippingDest)*/) {
            $normDesc .= $normSuff;
            $backDesc = 'Backorder: ' . $this->_getCountryNameFromCountryCode($backorderDest) . $backSuff;
        }
        $descs[] = $normDesc;
        if (isset($backDesc)) {
            $descs[] = $backDesc;
        }
        $shippingDesc = implode(' - ', $descs);
        if (isset($this->withNewLineSeparator)) {
            $shippingDesc = implode('<br />', $descs);
        }
        $shippingDesc = preg_replace('/\[.*?\]/', '', $shippingDesc);
        $shippingDesc = trim(preg_replace('/  +/', ' ', $shippingDesc));
        return $shippingDesc;
    }

    function _renderDiscountRows($cart)
    {
        $trs = '';
        $discTotal = 0;
        foreach ($cart['discounts'] as $discount) {
            $discountCell = $discount['title'];
            if (Helper_Structure::getArrayValue($discount, 'type') !== 'special') {
                $discountCell .= " (" . $discount['code'] . ")";
            }
            $discountCell .= ":";
            $discountVal = $discount['value'];
            $trs .= $this->_renderRow('&nbsp;', '&nbsp;', $discountCell, $this->dol($discountVal));
            $discTotal += $discountVal;
        }
        if ($discTotal && isset($this->withTotalAfterDiscounts) && $this->withTotalAfterDiscounts) {
            $itemTotal = Helper_Cart::getItemTotal($this->cart['items']);
            $trs .= $this->_renderRow('&nbsp;', '&nbsp;', $this->xmlTag('strong', 'Total after Discounts:'), $this->xmlTag('strong', $this->dol($itemTotal+$discTotal)));
        }
        return $trs;
    }

    function _renderTotalRows($cart)
    {
        $trs = '';
        $this->_withGrandTotal = FALSE;
        if ($this->_isShowDiscount($cart)) {
            $trs .= $this->_renderDiscountRows($cart);
            $this->_withGrandTotal = TRUE;
        }
        if ($cart['shipping'] || (isset($this->alwaysShowShipping) && $this->alwaysShowShipping)) {
            $trs .= $this->_renderShippingRow($cart);
            $this->_withGrandTotal = TRUE;
        }
        if (isset($cart['shipping_discount']) && $cart['shipping_discount']) {
            $trs .= $this->_renderRow('&nbsp;', '&nbsp;', 'Shipping Discount:', $this->dol($cart['shipping_discount']));
            $this->_withGrandTotal = TRUE;
        }
        if (isset($cart['tax']) && $cart['tax']) {
            $taxVal = $cart['tax'];
            $taxLabel = 'Tax';
            if (isset($this->taxLabel) && $this->taxLabel) {
                $taxLabel = $this->taxLabel;
            }
            $trs .= $this->_renderRow('&nbsp;', '&nbsp;', "$taxLabel: ", $this->dol($taxVal));
            $this->_withGrandTotal = TRUE;
        }
        if ($this->_withGrandTotal) {
            $total_tds = '';
            if ($this->_withImage) {
                $total_tds .= $this->xmlTag('td', '&nbsp;', array('class' => 'cart-cell-empty'));
            }
            if ($this->_getTableMode() != 'mini') {
                $total_tds .= $this->xmlTag('td', '&nbsp;', array('class' => 'cart-cell-empty'));
                $total_tds .= $this->xmlTag('td', '&nbsp;', array('class' => 'cart-cell-empty'));
            }
            $total_tds .= $this->xmlTag('td', $this->xmlTag('strong', 'Total:'), array('class' => 'number'));
            $grandTotal = Helper_Cart::getGrandTotal($cart);
            $total_tds .= $this->xmlTag('td', $this->xmlTag('strong', $this->dol($grandTotal)), array('class' => 'number dollar-value'));
            $trs .= $this->xmlTag('tr', $total_tds, array('class' => 'cart-grand-total-row'));
        }
        return $trs;
    }


    function _getWithHtml($options)
    {
        if (isset($options['with_item_link']) && !$options['with_item_link']) {
            return FALSE;
        }
        return TRUE;
    }

    function getCartText($text)
    {
        return Helper_Krco::getText($this->messages, $text);
    }

    function _getTableHeadRow($options)
    {
        $ths = '';
        if ($this->_withImage) {
            $ths .= $this->xmlTag('th', $this->getCartText(''));
        }
        $ths .= $this->xmlTag('th', $this->getCartText('Item'));
        $ths .= $this->xmlTag('th', $this->getCartText('Price'), array('class' => 'number cart-column-price'));
        $ths .= $this->xmlTag('th', $this->getCartText('Quantity'), array('class' => 'number cart-column-quantity'));
        $ths .= $this->xmlTag('th', $this->getCartText('Total'), array('class' => 'number cart-column-amount'));
        $trs = $this->xmlTag('tr', $ths);
        return $trs;
    }

    function _getQuantityEditCell($item, $options)
    {
        $itemOptions = $this->optionsToHtmlPairs($item);
        if (isset($options['with_quantity_form']) && !$options['with_quantity_form']) {
            return '';
        }
        $isEditable = TRUE;
        if (isset($options['with_quantity_editable'])) {
            $isEditable = $options['with_quantity_editable'];
        }
        $q_edit_content = $item['quantity'];
        if ($isEditable) {
            $itemOptions = $this->optionsToHtmlPairs($item);
            $q_edit_content = $this->xmlEmptyTag('input', array(
                'name' => 'quantity',
                'type' => 'text',
                'value' => $item['quantity'],
            ));
            $q_edit_content .= $this->xmlInputsHidden(array(
                'item_id' => $item['fid'],
                'cmd' => 'update',
            ) + $itemOptions);
            $q_edit_content .= $this->xmlEmptyTag('input', array(
                'type' => 'submit',
                'value' => 'Update',
            ));
        }
        return $q_edit_content;
    }

    function _getQuantityCell($item, $options)
    {
        $itemOptions = $this->optionsToHtmlPairs($item);
        $q_edit_content = $this->_getQuantityEditCell($item, $options);
        $q_edit = $this->xmlTag('form', $q_edit_content, array(
            'action' => $this->cart['link'],
            'method' => 'post',
            'class' => 'cart-update-quantity',
        ));
        $remove_content = $this->xmlInputsHidden(array(
            'item_id' => $item['fid'],
            'cmd' => 'remove',
        ) + $itemOptions);
        $remove_content .= $this->xmlEmptyTag('input', array(
            'type' => 'submit',
            'value' => 'Remove',
        ));
        $remove_form = $this->xmlTag('form', $remove_content, array(
            'action' => $this->cart['link'],
            'method' => 'post',
        ));
        return $q_edit . $remove_form;
    }

    function _getTableItemRow($item, $options, $renderer)
    {
        $tds = '';
        if ($this->_withImage) {
            $imageName = $this->_getImageName($item);
            $image = '';
            if ($imageName) {
                $image = $this->xmlImg($imageName, $item['name']);
            }
            $tds .= $this->xmlTag('td', $image, array('class' => 'cart-item-image'));
        }
        $withHtml = $this->_getWithHtml($options);
        $tableMode = $this->_getTableMode();
        $quantityPrefix = '';
        if ($tableMode == 'mini') {
            $quantityPrefix = $item['quantity'] . ' x ';
        }
        $tds .= $this->xmlTag('td', $quantityPrefix . $this->getItemTitle($item, $withHtml, $options, $renderer));
        if ($tableMode != 'mini') {
            $tds .= $this->xmlTag('td', $this->dol($item['price']), array('class' => 'number dollar-value'));
        }
        if ($tableMode != 'mini') {
            $q_cell = $this->_getQuantityCell($item, $options);
            $tds .= $this->xmlTag('td', $q_cell, array('class' => 'number'));
        }
        $amount = $item['quantity'] * $item['price'];
        $tds .= $this->xmlTag('td', $this->dol($amount), array('class' => 'number dollar-value'));
        $tr = $this->xmlTag('tr', $tds, array('class' => 'shopping-cart-item'));
        return $tr;
    }

    function _getTableMode()
    {
        $tableMode = '';
        if (isset($this->cartTableMode)) {
            $tableMode = $this->cartTableMode;
        }
        return $tableMode;
    }

    function _getItemTotalRow()
    {
        $item_total_tds = '';
        if ($this->_withImage) {
            $item_total_tds .= $this->xmlTag('td', '&nbsp;', array('class' => 'cart-cell-empty'));
        }
        if ($this->_getTableMode() != 'mini') {
            $item_total_tds .= $this->xmlTag('td', '&nbsp;', array('class' => 'cart-cell-empty'));
            $item_total_tds .= $this->xmlTag('td', '&nbsp;', array('class' => 'cart-cell-empty'));
        }
        $item_total_tds .= $this->xmlTag('td', $this->xmlTag('strong', 'Item Total'), array('class' => 'number'));
        $itemTotal = Helper_Cart::getItemTotal($this->cart['items']);
        $item_total_tds .= $this->xmlTag('td', $this->xmlTag('strong', $this->dol($itemTotal)), array('class' => 'number dollar-value'));
        $tr = $this->xmlTag('tr', $item_total_tds, array('class' => 'cart-item-total-row'));
        return $tr;
    }

    function renderCartTable($cart, $options=array(), $renderer=NULL)
    {
        $this->cart = $cart;
        $trs = '';
        $this->_withImage = FALSE;
        if (isset($options['with_image']) && $options['with_image']) {
            $this->_withImage = TRUE;
        }
        if (!isset($this->withItemTable) || $this->withItemTable) {
            $tableMode = $this->_getTableMode();
            if ($tableMode != 'mini') {
                $trs .= $this->_getTableHeadRow($options);
            }
            if ($cart['items']) {
                foreach ($cart['items'] as $item) {
                    if ($item['quantity']) {
                        $trs .= $this->_getTableItemRow($item, $options, $renderer);
                    }
                }
            }
        }

        if (!isset($this->withTotalRows) || $this->withTotalRows) {
            $trs .= $this->_getItemTotalRow();
            $this->_total = Helper_Cart::getItemTotal($this->cart['items']);
            $trs .= $this->_renderTotalRows($cart);
        }
        $table_content = $trs;
        $empty_div = $this->xmlDiv('Your shopping cart is empty.');
        $cart_table = $empty_div;
        $is_empty = count($cart['items']) == 0;
        if (!$is_empty) {
            $cart_table = $this->xmlTag('table', $table_content, array());
        }
        return $cart_table;
    }

    function renderShoppingCart($cart, $continue_url, $paypal, $is_sandbox=FALSE, $fields=NULL, $info=NULL)
    {
        $this->cart = $cart;
        $is_empty = count($cart['items']) == 0;
        $cart_table = $this->renderCartTable($cart);

        $checkout = '';
        if (!$is_empty) {
            $checkout = $this->getCheckout($cart, $paypal, $is_sandbox, $fields);
        }

        $continue = $this->xmlDiv('&laquo; ' . $this->xmlA('Continue Shopping', $continue_url), array('class' => 'shopping-cart-continue'));
        $infoDiv = '';
        if (isset($info) && !$is_empty) {
            $infoDiv = $this->xmlDiv($info, array('class' => 'shopping-cart-info'));
        }
        $s = $this->xmlDiv($continue . $cart_table . $checkout . $infoDiv, array('class' => 'shopping-cart'));
        return $s;
    }

    function getCheckout($cart, $paypal, $is_sandbox, $fields=NULL)
    {
        $item_arr = array();
        $item_total = 0;
        $i = 1;
        $weight_total = 0;
        foreach ($cart['items'] as $item) {
            if ($item['quantity']) {
                $item_arr["item_name_$i"] = $this->getItemTitle($item, FALSE, array(), NULL);
                $item_arr["amount_$i"] = $item['price'];
                $item_arr["quantity_$i"] = $item['quantity'];
                $weight_total += $item['weight'];
                $i++;
            }
        }
        $item_arr['weight_total'] = $weight_total;

        $total = $item_total;
        $shipping_fee = 0;
        if ($cart['shipping']) {
            $shipping_fee = $cart['shipping'];
        }
        $item_arr['shipping_1'] = $shipping_fee;
        $sandbox = '';
        if ($is_sandbox) {
            $sandbox = 'sandbox.';
        }
        $inputs = '';
        if (isset($fields)) {
            $shipping_fields = '';
            $proc = new Form_Processor();
            foreach ($fields as $field) {
                $shipping_fields .= $proc->htmlRenderField($field);
            }
            $shipping_form = $this->xmlDiv($this->xmlTag('h4', 'Enter shipping information:') . $this->xmlTag('ul', $shipping_fields), array(
                'class' => 'shopping-cart-shipping-form',
            ));
            $inputs .= $shipping_form;
        }
        $inputs .= $this->xmlInputsHidden(array(
            'cmd' => '_cart',
            'upload' => '1',
            'custom' => $cart['code'],
        ) + $paypal + $item_arr);
        $inputs .= $this->xmlEmptyTag('input', array(
            'type' => 'image',
            'src' => 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif',
        ));
        $checkout_form = $this->xmlTag('form', $inputs, array(
            'action' => "https://www.${sandbox}paypal.com/cgi-bin/webscr",
            'method' => 'post',
            'novalidate' => 'novalidate',
        ));
        $checkout = $this->xmlDiv($this->xmlTag('h3', 'Checkout') . $checkout_form, array('class' => 'shopping-cart-checkout'));
        return $checkout;
    }

    static function getInstanceFromSmartyParams($params, $template=NULL)
    {
        $renderer = new Renderer_Cart();
        $messages = array();
        if ($template) {
            $view = $template->smarty->getTemplateVars('__view');
            if (isset($view['messages'])) $messages = $view['messages'];
        }
        $renderer->messages = $messages;
        $renderer->countries = Helper_Paypal::getPaypalCountries();
        if (isset($params['with_product_link'])) $renderer->withProductLink = $params['with_product_link'];
        if (isset($params['with_currency_symbol'])) $renderer->withCurrencySymbol = $params['with_currency_symbol'];
        if (isset($params['with_currency_space'])) $renderer->withCurrencySpace = $params['with_currency_space'];
        if (isset($params['member'])) $renderer->member = $params['member'];
        if (isset($params['currencies'])) $renderer->currencies = $params['currencies'];
        if (isset($params['with_delivery_date'])) $renderer->withDeliveryDate = $params['with_delivery_date'];
        if (isset($params['with_discount'])) $renderer->withDiscount = $params['with_discount'];
        if (isset($params['with_points'])) $renderer->withPoints = $params['with_points'];
        if (isset($params['points_label'])) $renderer->pointsLabel = $params['points_label'];
        if (isset($params['with_item_table'])) $renderer->withItemTable = $params['with_item_table'];
        if (isset($params['with_total_rows'])) $renderer->withTotalRows = $params['with_total_rows'];
        if (isset($params['discount_label'])) $renderer->discountLabel = $params['discount_label'];
        if (isset($params['shows_discount'])) $renderer->showsDiscount = $params['shows_discount'];
        if (isset($params['always_show_shipping'])) $renderer->alwaysShowShipping = $params['always_show_shipping'];
        if (isset($params['with_shipping'])) $renderer->withShipping = $params['with_shipping'];
        if (isset($params['with_shipping_country'])) $renderer->withShippingCountry = $params['with_shipping_country'];
        if (isset($params['with_shipping_city'])) $renderer->withShippingCity = $params['with_shipping_city'];
        if (isset($params['thumbnail_suffix'])) $renderer->thumbnailSuffix = $params['thumbnail_suffix'];
        if (isset($params['with_backorder_shipping'])) $renderer->withBackorderShipping = $params['with_backorder_shipping'];
        if (isset($params['cart_table_mode'])) $renderer->cartTableMode = $params['cart_table_mode'];
        if (isset($params['with_new_line_separator'])) $renderer->withNewLineSeparator = $params['with_new_line_separator'];
        return $renderer;
    }
}
