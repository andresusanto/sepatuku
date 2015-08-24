<?php
class Helper_Objects
{
    public static $orderSalt = 'a432e3c5d1ee4c54';

    static function generateGeneralValidator($controller, $obj, $fieldName=NULL, $salt=NULL)
    {
        $getField = 'getRecipientEmail';
        if (isset($fieldName)) {
            $getField = 'get' . $fieldName;
        }
        $usedSalt = self::$orderSalt;
        if (isset($salt)) {
            $usedSalt = $salt;
        }
        $valStr = substr(md5($obj->getLongId() . $obj->$getField() . $controller->getSiteName() . $usedSalt), 0, 8);
        return $valStr;
    }

    static function generateOrderValidator($controller, $subObj)
    {
        return Helper_Objects::generateGeneralValidator($controller, $subObj);
    }

    static function generateSubscriptionValidator($controller, $subObj)
    {
        return Helper_Objects::generateGeneralValidator($controller, $subObj, 'Email', 'saltforsubscription');
    }


    static function _getInvoiceLinkOfOrder($controller, $obj)
    {
        if (!$obj->getInvoiceId()) {
            return NULL;
        }
        return self::getOrderDetailsLink($controller, $obj, 'invoice');
    }

    static function getOrderDetailsLink($controller, $obj, $segment)
    {
        $id = $obj->getLongId();
        $valStr = self::generateOrderValidator($controller, $obj);
        return $controller->composeLink("/orders/$id/$segment/$valStr", NULL, FALSE);
    }

    static function _getOrderStatusName($controller, $raw)
    {
        $name = $raw;
        $aliases = $controller->getKrcoConfigValue('cart', 'order_status_aliases');
        if (isset($aliases[$raw])) {
            $name = $aliases[$raw];
        }
        return $name;
    }

    static function orderToOrderArr($controller, $obj)
    {
        $itemObjs = $obj->getOrderedProducts();
        $items = self::objsToArrs($controller, $itemObjs, 'orderItemToArr');
        $detailedItems = self::objsToArrs($controller, $itemObjs, 'orderItemToDetailedArr');
        $expirySeconds = $controller->_getOrderExpirySeconds();
        $expiryTs = strtotime($obj->getOrderDate()) + $expirySeconds;
        $arr = array(
            'order_id' => $obj->getLongId(),
            'invoice_id' => $obj->getLongId(),
            'order_timestamp' => strtotime($obj->getOrderDate()),
            'expiry_timestamp' => $expiryTs,
            'expiry_hours' => (int)($expirySeconds/3600),
            'invoice_link' => self::_getInvoiceLinkOfOrder($controller, $obj),
            'payment_notif_message' => $obj->getPaymentNotifMessage(),
            'prefered_delivery_timestamp' => strtotime($obj->getPreferredDeliveryDate()),
            'coupon_code' => $obj->getCouponCode(),
            'n_points_earned' => $obj->getPointEarned(),
            'n_points_used' => $obj->getPointUsed(),
            'currency' => $obj->getCurrency(),
            'item_total_amount_raw' => Helper_Cart::getItemTotalCostOfOrder($obj),
            'total_amount_raw' => Helper_Cart::getTotalCostOfOrder($obj),
            'total_amount' => $obj->getCurrency() . ' ' . number_format(Helper_Cart::getTotalCostOfOrder($obj), 2),
            'status' => self::_getOrderStatusName($controller, $obj->getOrderStatus()),
            'merchant_remarks' => $obj->getRemarks(),
            'message' => $obj->getMessage(),
            'items' => $items,
            'detailed_items' => $detailedItems,
            'shipping' => $obj->getShippingCost(),
            'discount' => $obj->getDiscount(),
            'tax' => $obj->getTax(),
            'down_payment' => $obj->getDownPayment(),
            'shipping_info' => $obj->getShippingDestination(),
            'backorder_shipping_info' => $obj->getBackorderShippingDestination(),
            'payment_method' => $obj->getPaymentMethod(),
            'billing_info' => self::_getBillingInfoOfOrder($obj),
            'delivery_info' => self::_getDeliveryInfoOfOrder($obj),
            'payment_instruction' => self::getPaymentInstructionOfPaymentMethod($controller, $obj->getPaymentMethod()),
            'attribute1' => $obj->getAttribute1(),
            'attribute2' => $obj->getAttribute2(),
            'attribute3' => $obj->getAttribute3(),
            'attribute4' => $obj->getAttribute4(),
            'attribute5' => $obj->getAttribute5(),
        );
        $addData = array();
        if (method_exists($obj, 'getEventName')) $addData['event_name'] = $obj->getEventName();
        if (method_exists($obj, 'getEventVenue')) $addData['event_venue'] = $obj->getEventVenue();
        if (method_exists($obj, 'getEventDate')) $addData['event_timestamp'] = strtotime($obj->getEventDate());
        $arr += $addData;
        return $arr;
    }

    static function getPaymentInstructionOfPaymentMethod($controller, $paymentMethod)
    {
        $instruction = '';
        if ($paymentMethod == 'bank-transfer') {
            return self::getPaymentInstructionBankTransfer($controller);
        }
        return $instruction;
    }

    static function getPaymentInstructionBankTransfer($controller)
    {
        $bankAccounts = $controller->getObjects('bank_accounts', 'getAllBankAccounts', array());
        $bankString = self::getBankHtml($bankAccounts);
        $instruction = $bankString;
        return $instruction;
    }

    static function getBankHtml($bankArrs)
    {
        $bankHtml = '';
        if ($bankArrs) {
            $bankHtml .= "<ul>\n";
            $bankHtml .= implode('', array_map(function ($obj) { return "<li>" . Helper_Objects::getBankAccountString($obj) . "</li>\n"; }, $bankArrs));
            $bankHtml .= "</ul>\n";
        }
        return $bankHtml;
    }

    static function getBankAccountString($bank)
    {
        $str = $bank->getTitle() . ' ' . $bank->getAccountNumber();
        if ($addInfo = $bank->getAdditionalInfo()) {
            $str .= " ($addInfo)";
        }
        return $str;
    }

    static function _getBillingInfoOfOrder($obj)
    {
        $info = array(
            'name' => $obj->getRecipientName(),
            'address' => $obj->getRecipientAddress(),
            'city' => $obj->getRecipientCity(),
            'postal_code' => $obj->getRecipientPostalCode(),
            'state' => $obj->getRecipientState(),
            'country' => Helper_String::getCountryNameOfCode($obj->getRecipientCountry()),
        );
        if (method_exists($obj, 'getRecipientPhoneNumber')) $info['phone'] = $obj->getRecipientPhoneNumber();
        if (method_exists($obj, 'getRecipientEmail')) $info['email'] = $obj->getRecipientEmail();
        if (method_exists($obj, 'getSalutation')) $info['salutation'] = $obj->getSalutation();
        if (method_exists($obj, 'getIdNo')) $info['id_number'] = $obj->getIdNo();
        if (method_exists($obj, 'getNationality')) $info['nationality'] = $obj->getNationality();
        if (method_exists($obj, 'getRecipientPhoneNumber2')) $info['phone2'] = $obj->getRecipientPhoneNumber2();
        if (method_exists($obj, 'getDateOfBirth')) $info['dob_timestamp'] = strtotime($obj->getDateOfBirth());
        return $info;
    }

    static function _getDeliveryInfoOfOrder($obj)
    {
        $info = array(
            'name' => $obj->getDeliveryName(),
            'address' => $obj->getDeliveryAddress(),
            'city' => $obj->getDeliveryCity(),
            'postal_code' => $obj->getDeliveryPostalCode(),
            'state' => $obj->getDeliveryState(),
            'country' => Helper_String::getCountryNameOfCode($obj->getDeliveryCountry()),
            'phone' => $obj->getDeliveryPhoneNumber(),
            'email' => $obj->getDeliveryEmail(),
        );
        $isEmpty = TRUE;
        foreach ($info as $x) {
            if ($x) {
                $isEmpty = FALSE;
            }
        }
        if ($isEmpty) {
            $info = self::_getBillingInfoOfOrder($obj);
        }
        return $info;
    }

    static function orderItemToDetailedArr($controller, $item)
    {
        $arr = array(
            'product_code' => method_exists($item, 'getProductCode') ? $item->getProductCode() : NULL,
            'image' => method_exists($item, 'getImageUrl') ? $item->getImageUrl() : NULL,
            'title' => $item->getTitle(),
            'quantity' => $item->getQuantity(),
            'price' => $item->getPrice(),
        );
        return $arr;
    }

    static function getAdditionalFields($controller, $configKey, $obj)
    {
        $addFields = array();
        $formFields = $controller->getKrcoConfigValue($configKey, 'form_fields');
        if ($formFields) {
            foreach ($formFields as $formField) {
                $objMap = NULL;
                if (isset($formField['obj_map'])) {
                    $objMap = $formField['obj_map'];
                }
                if (isset($formField['view_obj_map'])) {
                    $objMap = $formField['view_obj_map'];
                }
                if (isset($formField['pass_to_view']) && $formField['pass_to_view'] && isset($objMap)) {
                    $fieldKey = $formField['pass_to_view'];
                    $val = self::getObjAttrByMap($controller, $obj, $objMap);
                    // var_dump($val);
                    if (isset($formField['rel'])) {
                        $rel = $formField['rel'];
                        $objs = $controller->_indicesToObjects($val, $formField['rel'], 'getActiveItemById');
                        $key_si = $controller->getKrcoConfigValue($formField['rel'], 'key_si');
                        $toArr = "${key_si}ToArr";
                        $val = $controller->objsToArrs($objs, $toArr, FALSE, $rel);
                    }
                    $addFields[$fieldKey] = $val;
                }
            }
        }
        return $addFields;
    }

    static function getObjAttrByMap($controller, $obj, $map)
    {
        $val = NULL;
        if (is_string($map)) {
            $lang = isset($controller->lang) ? $controller->lang : NULL;
            $method_name = 'get' . $map;
            $val = NULL;
            if (is_callable(array($obj, $method_name))) {
                $val = $obj->$method_name($lang);
            }
        } else if (is_callable($map)) {
            $val = $map($obj, NULL, $controller);
        } else if (is_array($map) && isset($map['image_dir'])) {
            $image = NULL;
            $val = NULL;
            if (is_callable(array($obj, 'getImage'))) {
                $image = $obj->getImage();
            } else if (is_callable(array($obj, 'getImages'))) {
                $images = $obj->getImages();
                if (isset($images[0])) {
                    $image = $images[0];
                }
            }
            if ($image) {
                $imageDir = $map['image_dir'];
                $val = $controller->composeResource("/content/$imageDir/" . $image);
            }
            return $val;
        } else if (is_array($map)) {
            if (!empty($map['attr'])) {
                $val = $obj->getAttr($map['attr']);
            } else if (!empty($map['extra_attribute'])) {
                $extra = $obj->getExtraAttribute();
                if ($extra) {
                    $getMethod = 'get' . $map['extra_attribute'];
                    $val = $extra->$getMethod();
                }
            }
        }
        return $val;
    }

    static function getTranslateArrFuncName($controller, $configKey)
    {
        $keySi = $controller->getKrcoConfigValue($configKey, 'key_si');
        if (!$keySi) {
            return NULL;
        }
        $func_name = $keySi . 'ToArr';
        return $func_name;
    }

    static function translateObjectToArrByType($controller, $obj, $configKey)
    {
        $func_name = self::getTranslateArrFuncName($controller, $configKey);
        if (!isset($func_name)) {
            return NULL;
        }
        return self::translateObjectToArr($controller, $obj, $func_name, $configKey);
    }

    static function translateObjectToArr($controller, $obj, $func_name, $configKey=NULL)
    {
        $objTranslator = $controller;
        if (isset($controller->objTranslator)) {
            $objTranslator = $controller->objTranslator;
        }
        if (is_callable($func_name)) {
            $arr = $func_name($controller, $obj);
        } else if (is_callable(array($objTranslator, $func_name))) {
            $arr = $objTranslator->$func_name($obj, $controller->getRequest('get'));
        } else {
            if (!method_exists('Helper_Objects', $func_name)) {
                throw new Exception("no translator $func_name");
            }
            $arr = self::$func_name($controller, $obj);
        }
        if (method_exists($obj, 'getId') && is_array($arr)) {
            $arr['id'] = $obj->getId();
        }
        if ($configKey) {
            $arr += self::getAdditionalFields($controller, $configKey, $obj);
        }
        self::_filterHtmlElements($controller, $arr);
        return $arr;
    }

    public static $filteredHtmlKeys = array(
        'title',
        'description',
        'short_description',
        'specification',
        'snippet',
        'details',
        'content',
        'short_content',
        'author_description',
    );

    static function _filterHtmlElements($controller, &$arr)
    {
        $filteredKeys = self::$filteredHtmlKeys;
        if (is_array($arr)) {
            foreach ($arr as $key => &$value) {
                if (in_array($key, $filteredKeys)) {
                    $value = self::filterHtml($controller, $value);
                }
            }
        }
    }

    static function objsToArrsByType($controller, $objs, $type)
    {
        $func_name = self::getTranslateArrFuncName($controller, $type);
        return self::objsToArrs($controller, $objs, $func_name, FALSE, $type);
    }

    static function objsToArrs($controller, $objs, $func_name, $with_slug=FALSE, $configKey=NULL)
    {
        $arrs = array();
        if (is_array($objs)) {
            foreach ($objs as $obj) {
                if ($obj) {
                    if($configKey == "orders"){
                        $arr = self::translateOrderToArr($controller, $obj);
                    }else{
                        $arr = self::translateObjectToArr($controller, $obj, $func_name, $configKey);
                    }
                    if ($with_slug) {
                        $slug = $obj->getFriendlyId();
                        $arrs[$slug] = $arr;
                    } else {
                        $arrs[] = $arr;
                    }
                } else {
                    $arrs[] = $obj;
                }
            }
        }
        return $arrs;
    }

    static function account_addressToArr($controller, $obj)
    {
        $arr = array(
            'title' => $obj->getTitle(),
            'first_name' => $obj->getFirstName(),
            'last_name' => $obj->getLastName(),
            'address_line1' => $obj->getAddressLine1(),
            'address_line2' => $obj->getAddressLine2(),
            'postal_code' => $obj->getPostalCode(),
            'city' => $obj->getCity(),
            'state' => $obj->getState(),
            'country' => $obj->getCountry(),
            'phone' => $obj->getPhone(),
            'email' => $obj->getEmail(),
        );
        return $arr;
    }

    static function press_releaseToArr($controller, $obj)
    {
        $arr = array(
            'fid' => $obj->getFriendlyId(),
            'title' => $obj->getTitle($controller->lang),
            'snippet' => $controller->_markdownIfOld($obj->getSnippet($controller->lang)),
            'details' => $obj->getDetails($controller->lang),
            'image' => $controller->composeImage($obj, 'press_releases'),
            'images' => self::composeImages($controller, $obj, 'press_releases'),
            'source' => $obj->getSource(),
            'link' => $obj->getLink(),
            'ext_link' => $obj->getLink(),
            'label' => $obj->getLabel(),
            'details_link' => $controller->composeDetailsLink($obj, 'press_releases'),
            'timestamp' => strtotime($obj->getDatePublished()),
        );
        return $arr;
    }

    static function orderItemToArr($controller, $obj)
    {
        $s = $obj->getTitle();
        return $s;
    }

    static function composeImage($controller, $obj, $dir)
    {
        $image = NULL;
        if ($imageFile = $obj->getImage()) {
            $image = $controller->composeResource("/content/$dir/" . rawurlencode($imageFile));
        }
        return $image;
    }

    static function composeImages($controller, $obj, $dir, $fieldName='Images')
    {
        $getMethod = 'get' . $fieldName;
        $imgs = array();
        if (is_callable(array($obj, $getMethod))) {
            $imgs = $obj->$getMethod();
        }
        $images = array();
        if ($imgs) {
            foreach ($imgs as $img) {
                $relPath = "/content/$dir/" . rawurlencode($img);
                if (strpos($img, '/') === 0) {
                    $relPath = $img;
                }
                $image = $img;
                if (!Helper_URL::isURL($img)) {
                    $image = $controller->composeResource($relPath);
                }
                $images[] = $image;
            }
        }
        return $images;
    }

    static function _getColorFromImage($image)
    {
        $color = NULL;
        preg_match('/_color-(.*)\./i', $image, $matches);
        if (isset($matches[1])) {
            $color = rawurldecode($matches[1]);
        }
        return $color;
    }

    static function _getBasenameFromImage($image)
    {
        $filename = pathinfo($image, PATHINFO_FILENAME);
        $upos = strpos($filename, '_');
        $basename = $filename;
        if ($upos !== FALSE) {
            $basename = substr($filename, $upos+1);
        }
        return rawurldecode($basename);
    }

    static function getImageAttrsFromBasename($basename)
    {
        $segments = explode('_', $basename);
        $attrs = array();
        foreach ($segments as $str) {
            $pos = strpos($str, '-');
            if ($pos !== FALSE) {
                $key = substr($str, 0, $pos);
                $val = substr($str, $pos+1);
                $attrs[strtolower($key)] = $val;
            }
        }
        if (!isset($attrs['color'])) {
            $attrs['color'] = NULL;
        }
        return $attrs;
    }

    static function composeDetailedImages($controller, $obj, $dir)
    {
        $detailed_images = array();
        $images = self::composeImages($controller, $obj, $dir);
        foreach ($images as $image) {
            $basename = self::_getBasenameFromImage($image);
            $detailed_image = array(
                'url' => $image,
                'file_basename' => $basename,
            );
            $imageAttrs = self::getImageAttrsFromBasename($basename);
            $detailed_image += $imageAttrs;
            $detailed_images[] = $detailed_image;
        }
        return $detailed_images;
    }
    
    static function _basicProductToArr($controller, $obj)
    {
        if (!$obj) {
            return NULL;
        }
        $nameLow = Helper_String::toUnderscored($controller->getKrcoConfigValue('products', 'db_name_pl'));
        $product_slug = $obj->getFriendlyId();
        $images = self::composeImages($controller, $obj, $nameLow);
        $detailed_images = self::composeDetailedImages($controller, $obj, $nameLow);
        $md = $controller->getMarkup();
        $lang = $controller->lang;
        $currency = '$';
        if (!is_null($controller->getCurrencySymbol())) {
            $currency = $controller->getCurrencySymbol();
        }
        $attachment = NULL;
        if (method_exists($obj, 'getBrochure')) {
            $attachment = $obj->getBrochure();
        }
        $arr = array(
            'fid' => $obj->getFriendlyId(),
            'product_code' => Helper_Structure::_getAttrFromObj('ProductCode', $obj),
            'title' => $obj->getTitle($lang),
            'short_description' => $obj->getShortDescription($lang),
            'description' => $md->mark($obj->getDescription($lang)),
            'specification' => $md->mark($obj->getSpecification($lang)),
            'link' => $controller->composeDetailsLink($obj, 'products'),
            'attachment' => $attachment,
            'label' => $controller->_getCategoryTitleOfObj($obj, 'products'),
            'label_fid' => $controller->_getCategoryFidOfObj($obj, 'products'),
            'brand' => $obj->getBrandLabel(),
            'is_new' => $obj->getIsNew(),
            'is_featured' => $obj->getIsFeatured(),
            'images' => $images,
            'detailed_images' => $detailed_images,
        );
        $controller->_processInStock($arr, $obj);
        $arr['price'] = $currency . ' ' . $obj->getPrice();
        $arr['price_raw'] = $obj->getPrice();
        if (self::_isSalePriceApplicable($controller, $obj)) {
            $arr['usual_price'] = $arr['price'];
            $arr['usual_price_raw'] = $arr['price_raw'];
            $arr['price'] = $currency . ' ' . $obj->getSalePrice();
            $arr['price_raw'] = $obj->getSalePrice();
            $arr['price_saving'] = $obj->getPrice() - $obj->getSalePrice();
            $arr['sale_expiry_timestamp'] = strtotime($obj->getSaleExpiryDate());
        }
        $controller->addKrcoFunction($arr, $obj, 'productToArr');
        return $arr;
    }

    static function _isSalePriceApplicable($controller, $obj)
    {
        if (method_exists($obj, 'getSalePrice') && $obj->getSalePrice()) {
            if (isset($controller->now) && $obj->getSaleExpiryDate() && ($controller->now > strtotime($obj->getSaleExpiryDate()))) {
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }

    static function _ecommerceProductToArr($controller, $obj)
    {
        $arr = self::_basicProductToArr($controller, $obj);
        if (!($obj instanceof EcommerceProduct)) {
            return $arr;
        }
        $tempSimpleProducts = Helper_Krco::getSimpleProductsByAggregatorId($controller, $obj->getId());
        $simpleProducts = array();
        foreach ($tempSimpleProducts as $simProd) {
            $newSimProd = Helper_Cart::_combineSimpleFromAggregator($simProd, $obj);
            $simpleProducts[] = $newSimProd;
        }
        $arr['decoration'] = $obj->getRibbon();
        $arr['wish_count'] = (int)$obj->getWishCount();
        $arr['options'] = $controller->_getProductOptions($obj);
        $arr['general_options'] = $controller->_getProductGeneralOptions($arr['options']);
        $arr['is_backorder'] = Helper_Structure::_getAttrFromObj('IsAllowBackorder', $obj);
        $arr['variants'] = $controller->_getProductVariants($obj, $simpleProducts);
        $arr['detailed_variants'] = $controller->_getProductVariants($obj, $simpleProducts, TRUE);
        $arr['prices_by_color'] = $controller->_getPricesByColor($obj, $simpleProducts);
        $arr['advanced_pricing_rules'] = json_decode($obj->getAdvancedPricingRule(), TRUE);
        $arr['size'] = $obj->getAttribute1();
        $arr['color'] = $obj->getAttribute2();
        //$arr['material'] = $obj->getAttribute3();
        $options = $controller->getObjKrcoConfig('options', 'products');
        if (is_array($options)) {
            $idx = 3;
            $defKeys = Helper_Krco::getDefaultProductOptionKeys();
            foreach ($options as $key => $opt) {
                if (in_array($key, $defKeys)) {
                    continue;
                }
                $getAttr = "getAttribute$idx";
                $arr[$key] = $obj->$getAttr();
                $idx++;
            }
        }
        return $arr;
    }

    static function markdownMark($text)
    {
        $md = new Markup_Markdown();
        $ret = '';
        if ($text) {
            $ret = $md->mark($text);
        }
        return $ret;
    }

    static function bannerToArr($controller, $obj)
    {
        $prefix = '_med';
        if ($controller->getKrcoConfigVersion() >= 1) {
            $prefix = '';
        }
        $image = Helper_File::addSuffix($obj->getImage(), $prefix);
        $youtubeId = Helper_String::extractYoutubeId($obj->getYoutubeLink());
        $arr = array(
            'title' => $obj->getTitle($controller->lang),
            'description' => self::markdownMark($obj->getDescription($controller->lang)),
            'image' => $controller->composeResource("/content/banners/" . rawurlencode($image)),
            'link' => $obj->getLink(),
            'youtube_id' => $youtubeId,
            'cta_text' => $obj->getButtonText(),
        );
        return $arr;
    }

    static function transactionToOrderArr($controller, $obj)
    {
        $itemObjs = $obj->getSoldProducts();
        $items = self::objsToArrs($controller, $itemObjs, 'orderItemToArr');
        $detailedItems = self::objsToArrs($controller, $itemObjs, 'orderItemToDetailedArr');
        $arr = array(
            'order_id' => $obj->getLongId(),
            'total_amount' => $obj->getCurrency() . ' ' . number_format($obj->getTotalCost(), 2),
            'total_amount_raw' => $obj->getTotalCost(),
            'status' => $obj->getOrderStatus(),
            'currency' => $obj->getCurrency(),
            'shipping' => $obj->getShippingCost(),
            'down_payment' => 0,
            'discount' => $obj->getDiscount(),
            'shipping_info' => NULL,
            'merchant_remarks' => $obj->getRemarks(),
            'items' => $items,
            'billing_info' => self::_getBillingInfoOfOrder($obj),
            'detailed_items' => $detailedItems,
        );
        return $arr;
    }

    static function _general_categoryToArr($controller, $obj, $key_pl=NULL, $prefixFid=NULL, $configKey='general_categories')
    {
        $catFid = $obj->getFriendlyId();
        $segment = $controller->getKrcoConfigValue($configKey, 'obj_segment');
        if ($objSegment = $controller->getObjKrcoConfig('segment', $key_pl)) {
            $segment = $objSegment;
        }
        $pref = '';
        if ($prefixFid) {
            $pref = "$prefixFid/";
        }
        $link = $controller->composeLink("/$segment/category/$pref$catFid", array(), FALSE);
        $arr = array(
            'title' => $obj->getTitle($controller->lang),
            'fid' => $obj->getFriendlyId(),
            'parent_id' => $obj->getParentId(),
            'page_name' => $obj->getPageName(),
            'id_for_coupon' => $obj->getId(),
            'description' => $obj->getDescription($controller->lang),
            'short_description' => $obj->getShortDescription($controller->lang),
            'images' => self::composeImages($controller, $obj, 'general_categories'),
            'is_hidden' => $obj->getIsHidden(),
            'is_special' => self::isGenCatSpecial($obj),
            'link' => $link,
        );
        if ($configKey) {
            $arr += self::getAdditionalFields($controller, $configKey, $obj);
        }
        return $arr;
    }

    static function general_categoryToArr($controller, $obj, $key_pl=NULL, $prefixFid=NULL)
    {
        return self::_general_categoryToArr($controller, $obj, $key_pl, $prefixFid);
    }

    static function general_category2ToArr($controller, $obj, $key_pl=NULL, $prefixFid=NULL)
    {
        return self::_general_categoryToArr($controller, $obj, $key_pl, $prefixFid, 'general_categories2');
    }

    static function general_category3ToArr($controller, $obj, $key_pl=NULL, $prefixFid=NULL)
    {
        return self::_general_categoryToArr($controller, $obj, $key_pl, $prefixFid, 'general_categories3');
    }

    static function setMemberAddressObjProperties($controller, $obj)
    {
        $obj->setTitle($controller->getRequest('post', 'title'));
        $obj->setFirstName($controller->getRequest('post', 'first_name'));
        $obj->setLastName($controller->getRequest('post', 'last_name'));
        $obj->setAddressLine1($controller->getRequest('post', 'address_line1'));
        $obj->setAddressLine2($controller->getRequest('post', 'address_line2'));
        $obj->setPostalCode($controller->getRequest('post', 'postal_code'));
        $obj->setCity($controller->getRequest('post', 'city'));
        $obj->setState($controller->getRequest('post', 'state'));
        $obj->setCountry($controller->getRequest('post', 'country'));
        $obj->setPhone($controller->getRequest('post', 'phone'));
        $obj->setEmail($controller->getRequest('post', $controller->getEmailInputName()));
    }

	public static $FIELD_META_OBJECT_OWNER = Array(
		'type' => 'varchar',
		'db_col' => 'object_owner',
	);

    static function _getExpiryStatus($controller, $ts)
    {
        $status = 'active';
        $expSecondsArr = $controller->_getSubscriptionExpirySeconds();
        $expSec = $expSecondsArr[0];
        if ($controller->now > $ts) {
            $status = 'expired';
        } else if ($controller->now > ($ts - $expSec)) {
            $status = 'expiring';
        }
        return $status;
    }

    static function subscriptionToArr($controller, $sub)
    {
        $end_timestamp = Helper_Subscription::getExpiryTimeOfSubscription($sub);
        $next_end_timestamp = Helper_Subscription::getExpiryTimeOfSubscription($sub, 1);
        $arr = array(
            'first_name' => $sub->getFirstName(),
            'last_name' => $sub->getLastName(),
            'subscription_id' => $sub->getLongId(),
            'product_name' => $sub->getProduct(),
            'start_timestamp' => strtotime($sub->getStartDate()),
            'end_timestamp' => $end_timestamp,
            'next_end_timestamp' => $next_end_timestamp,
            'expiry_status' => self::_getExpiryStatus($controller, $end_timestamp),
            'hash' => Helper_Objects::generateSubscriptionValidator($controller, $sub),
            'is_subscription_archived' => (bool)$sub->getIsSubscriptionArchived(),
        );
        return $arr;
    }

    static function enquiryToArr($controller, $enq)
    {
        $arr = array(
            'enquiry_timestamp' => strtotime($enq->getEnquiryDate()),
            'name' => $enq->getName(),
            'email' => $enq->getEmail(),
            'ip_address' => $enq->getIpAddress(),
            'phone' => $enq->getMobilePhone(),
            'company' => $enq->getCompany(),
            'subject' => $enq->getPurpose(),
            'message' => $enq->getMessage(),
        );
        return $arr;
    }

    static function filterHtml($controller, $html)
    {
        $filtered = str_replace('"/resources', "\"$controller->resource_url", $html);
        return $filtered;
    }

    static function filterAhref($controller, $html)
    {
        $filtered = str_replace('href="/', "href=\"$controller->base_url/", $html);
        return $filtered;
    }

    static function _general_objectToArr($controller, $obj, $key)
    {
        $arr = array(
            'fid' => $obj->getFriendlyId(),
            'title' => $obj->getTitle($controller->lang),
            'label' => $controller->_getCategoryTitleOfObj($obj, $key),
            'label_fid' => $controller->_getCategoryFidOfObj($obj, $key),
            'short_description' => $obj->getShortDescription($controller->lang),
            'description' => $obj->getDescription($controller->lang),
            'images' => Helper_Objects::composeImages($controller, $obj, $key),
            'link' => $controller->composeDetailsLink($obj, $key),
        );
        return $arr;
    }

    static function general_objectToArr($controller, $obj)
    {
        return self::_general_objectToArr($controller, $obj, 'general_objects');
    }

    static function general_object2ToArr($controller, $obj)
    {
        return self::_general_objectToArr($controller, $obj, 'general_objects2');
    }

    static function general_object3ToArr($controller, $obj)
    {
        return self::_general_objectToArr($controller, $obj, 'general_objects3');
    }

    static function general_object4ToArr($controller, $obj)
    {
        return self::_general_objectToArr($controller, $obj, 'general_objects4');
    }

    static function general_object5ToArr($controller, $obj)
    {
        return self::_general_objectToArr($controller, $obj, 'general_objects5');
    }

    static function general_object6ToArr($controller, $obj)
    {
        return self::_general_objectToArr($controller, $obj, 'general_objects6');
    }
    
    static function general_object7ToArr($controller, $obj)
    {
        return self::_general_objectToArr($controller, $obj, 'general_objects7');
    }

    static function translateOrderToArr($controller, $orderObj)
    {
        $order = Helper_Objects::translateObjectToArr($controller, $orderObj, 'orderToOrderArr', 'orders');
        return $order;
    }

    static function invitationToArr($controller, $obj)
    {
        $arr = array(
            'email' => $obj->getEmail(),
            'referer_email' => $obj->getRefererEmail(),
        );
        return $arr;
    }

    static function couponToArr($controller, $coupon)
    {
        $arr = array(
            'coupon_type' => $coupon->getCouponType(),
            'coupon_code' => $coupon->getCouponCode(),
            'discount_percentage' => $coupon->getDiscountPercentage(),
            'discount_amount' => $coupon->getDiscountAmount(),
        );
        return $arr;
    }

    static function manageableToItemArr($controller, $man)
    {
        $navItem = array(
            'title' => $man->display_name_pl,
            'display_name' => isset($man->display_name) ? $man->display_name : '',
            'link' => $controller->composeLink('/content/' . $man->friendly_id),
        );
        if (isset($man->section_title)) {
            $navItem['section_title'] = $man->section_title;
        }
        if (isset($man->icon_filename)) {
            $navItem['icon_url'] = "$controller->admin_resource_url/images/admin_icons/$man->icon_filename";
        }
        if (isset($man->help_link)) {
            $navItem['help_link'] = $man->help_link;;
        }
        return $navItem;
    }

    static function isGenCatSpecial($catObj)
    {
        return $catObj->getIsSpecial() || (bool)$catObj->getSpecialMethod() || (bool)$catObj->getSpecialTagNames();
    }

    static function systemEventObjToArr($obj)
    {
        $arr = array(
            'title' => $obj->getTitle(),
            'type' => $obj->getTriggerType(),
            'filters' => $obj->getTriggerParams(),
            'action' => array(
                'type' => $obj->getActionType(),
                'attrs' => $obj->getActionParams(),
            ),
        );
        return $arr;
    }

    static function setSystemEventObjProperties($obj, $val)
    {
        $obj->setTitle(Helper_Structure::getArrayValue($val, 'title'));
        $obj->setTriggerType(Helper_Structure::getArrayValue($val, 'type'));
        $obj->setTriggerParams(Helper_Structure::getArrayValue($val, 'filters'));
        $action = Helper_Structure::getArrayValue($val, 'action');
        $obj->setActionType(Helper_Structure::getArrayValue($action, 'type'));
        $obj->setActionParams(Helper_Structure::getArrayValue($action, 'attrs'));
    }

    static function bankAccountToArr($controller, $obj)
    {
        $arr = array(
            'title' => $obj->getTitle(),
            'display' => self::getBankAccountString($obj),
        );
        return $arr;
    }

    static function _exceptionTestToArr($controller, $obj)
    {
        throw new Exception('test exception');
    }
}
