<?php
class Helper_Krco
{
    static function getKrcoObjLink($controller, $obj, $keyPl)
    {
        $objConfig = $controller->getKrcoConfigValue($keyPl);
        $segment = $objConfig['segment'];
        $fid = $obj->getFriendlyId();
        if (method_exists($obj, 'getProductType') && ($obj->getProductType() == 'simple')) {
            $id = $obj->getId();
            $aggId = $obj->getAggregatorId();
            $db = $objConfig['db'];
            $dbName = $objConfig['db_name'];
            $getMethod = 'get' . $dbName . 'ById';
            $agg = $controller->getSingleObject($db, $getMethod, array($aggId));
            if ($agg) {
                $fid = $agg->getFriendlyId();
            }
        }
        $base_url = str_replace("https://", "http://", $controller->base_url);
        $link = $base_url . ("/$segment/details/"). rawurlencode($fid);
        return $link;
    }

    static function getPaymentMethodOfOrder($order)
    {
        $method = $order->getPaymentMethod();
        if (($pos = strpos($method, ':')) !== FALSE) {
            $method = substr($method, 0, $pos);
        }
        return $method;
    }

    static function getPaymentMethodAccountOfOrder($order)
    {
        $account = NULL;
        $method = $order->getPaymentMethod();
        if (($pos = strpos($method, ':')) !== FALSE) {
            $account = substr($method, $pos+1);
        }
        return $account;
    }

    static function _setupKrcoConfigWithName($class, $request, $config, $preparer, $app)
    {
        $krcoConfig = NULL;
        $configArr = new PhpwebArray($config);
        if (!empty($config['config_class'])) {
            $class = $config['config_class'];
        }
        try {
            $krcoConfig = new $class();
            $krcoConfig->deployment_config = $config;
            $krcoConfig->lang = $configArr->get('lang', 'en');
            $krcoMessages = array(); if (method_exists($krcoConfig, 'getMessages')) $krcoMessages = $krcoConfig->getMessages();
            $krcoConfig->messages = new Messages($krcoMessages);
            $krcoLang = $krcoConfig->lang; if (method_exists($krcoConfig, 'getLang')) $krcoLang = $krcoConfig->getLang($request);
            $krcoConfig->messages->lang = $krcoLang;
        } catch (ClassNotFoundException $e) {
        }
        if (isset($krcoConfig)) {
            $langOptions = array(); if (method_exists($krcoConfig, 'getLanguageOptions')) $langOptions = $krcoConfig->getLanguageOptions();
            $preparer->languageOptions = $langOptions;
            $preparer->lang = $krcoLang;
            $preparer->messages = $krcoMessages;
            $preparer->siteConfig = $krcoConfig->getSiteConfig();
            $app->siteConfig = $krcoConfig->getSiteConfig();
            $krcoFunctions = NULL;
            if (method_exists($krcoConfig, 'getKrcoFunctions')) {
                $krcoFunctions = $krcoConfig->getKrcoFunctions();
                $krcoFunctions->lang = $preparer->lang;
            }
            if (method_exists($krcoConfig, 'getKrcoPlugin')) {
                $app->router->plugin = $krcoConfig->getKrcoPlugin();
            }
            $preparer->krcoFunctions = $krcoFunctions;
            $routes = $krcoConfig->getRoutes();
            $app->router->routes = $routes;
            $preparer->routes = $routes;
            $preparer->allImplementations = Phpweb_Application::getImplementationsFromRoutes($routes);
            $preparer->manageableNames = $krcoConfig->getManageableNames();
            $preparer->controllerNames = Phpweb_Application::getControllerNamesFromRoutes($routes);
            if (method_exists($krcoConfig, 'getRedirects')) $preparer->redirects = $krcoConfig->getRedirects();
            if (method_exists($krcoConfig, 'getAlwaysDbs')) $preparer->always_dbs = $krcoConfig->getAlwaysDbs();
        }
        return $krcoConfig;
    }

    static function setupKrcoConfig($request, $config, $preparer, $app)
    {
        self::_setupKrcoConfigWithName('KrcoConfig', $request, $config, $preparer, $app);
    }

    static function getNoReplyFrom($controller)
    {
        $from = '';
        $siteName = 'Default Site';
        $noreply = 'noreply@example.com';
        $depNoreply = $controller->getDepConfigValue('noreply');
        if ($depNoreply) {
            $siteName = $controller->getSiteName();
            $noreply = $depNoreply;
        } else if (!is_null($controller->getKrcoConfigValue('noreply'))) {
            $siteName = $controller->getSiteName();
            $noreply = $controller->getKrcoConfigValue('noreply');
        } else if (isset($controller->mail_info['noreply'])) {
            $siteName = $controller->site_name;
            $noreply = $controller->mail_info['noreply'];
        }
        $from = "$siteName <$noreply>";
        return $from;
    }

    static function getAdminInfoFromConfig($config)
    {
        if (isset($config['admin_name']) && isset($config['admin_email'])) {
            $admin = array(
                'name' => $config['admin_name'],
                'email' => $config['admin_email'],
            );
            return $admin;
        }
        return array(
            'name' => NULL,
            'email' => NULL,
        );
    }

    static function getDefaultProductOptionKeys()
    {
        return array('size', 'color');
    }

    static function getDb($controller, $name)
    {
        $db = $controller->$name;
        if (!isset($db->isDbFake) && !isset($db->pdo)) {
            throw new Exception("Database $$name is not connected.");
        }
        return $db;
    }

    static function dbCall($controller, $db, $method, $attrs, $isAllowError=FALSE, $isRawReturn=FALSE)
    {
        if (!isset($controller->$db)) {
            return NULL;
        }
        $theDb = self::getDb($controller, $db);
        if (!is_callable(array($theDb, $method))) {
            $dbClass = get_class($theDb);
            throw new Exception("$$db ($dbClass) has no method $method.");
        }
        if (count($attrs) == 7) {
            $result = $theDb->$method($attrs[0], $attrs[1], $attrs[2], $attrs[3], $attrs[4], $attrs[5], $attrs[6]);
        } else if (count($attrs) == 6) {
            $result = $theDb->$method($attrs[0], $attrs[1], $attrs[2], $attrs[3], $attrs[4], $attrs[5]);
        } else if (count($attrs) == 5) {
            $result = $theDb->$method($attrs[0], $attrs[1], $attrs[2], $attrs[3], $attrs[4]);
        } else if (count($attrs) == 4) {
            $result = $theDb->$method($attrs[0], $attrs[1], $attrs[2], $attrs[3]);
        } else if (count($attrs) == 3) {
            $result = $theDb->$method($attrs[0], $attrs[1], $attrs[2]);
        } else if (count($attrs) == 2) {
            $result = $theDb->$method($attrs[0], $attrs[1]);
        } else if (count($attrs) == 1) {
            $result = $theDb->$method($attrs[0]);
        } else if (count($attrs) == 0) {
            $result = $theDb->$method();
        }
        if ($isRawReturn) {
            return $result;
        }
        $ret = NULL;
        if ($result->isOK()) {
            $ret = $result->getReturnedObj();
            if (is_null($ret)) {
                $ret = 'success';
            }
        } else if (!$isAllowError) {
            $result = $result->getReturnedObj();
            if (is_array($result)) {
                $result = implode(",",$result);
            }
            throw new Exception("Database $$db error: " . $result);
        } else {
            $controller->_lastDbError = $result->getReturnedObj();
        }
        return $ret;
    }

    static function copyMemberToOrderAttrs($member, $order)
    {
        if ($member) {
            self::_copyAttrIfEmpty($order, 'Salutation', $member->getSalutation());
            self::_copyAttrIfEmpty($order, 'RecipientName', $member->getFirstName() . ' ' . $member->getLastName());
            self::_copyAttrIfEmpty($order, 'RecipientAddress', $member->getAddressLine1() . ' ' . $member->getAddressLine2());
            self::_copyAttrIfEmpty($order, 'RecipientCity', $member->getCity());
            self::_copyAttrIfEmpty($order, 'RecipientPostalCode', $member->getPostalCode());
            self::_copyAttrIfEmpty($order, 'RecipientState', $member->getState());
            self::_copyAttrIfEmpty($order, 'RecipientCountry', $member->getCountry());
            self::_copyAttrIfEmpty($order, 'RecipientEmail', $member->getEmail());
            self::_copyAttrIfEmpty($order, 'RecipientPhoneNumber', $member->getPhoneNumber());
            $order->setMemberId($member->getId());
        }
    }

    static function _copyAttrIfEmpty($toObj, $fieldName, $val)
    {
        $setMethod = 'set' . $fieldName;
        $toObj->$setMethod($val);
    }

    static function getMailer($config)
    {
        if (Helper_Structure::getArrayValue($config, 'fake_mail') == 'yes') {
            $mailer = new ExternalInterface_TestMailer();
            return $mailer;
        }
        if (Helper_Structure::getArrayValue($config, 'fake_mail') == 'dev') {
            $mailer = new ExternalInterface_Fake_Mailer();
            return $mailer;
        }
        $mailer = new ExternalInterface_Mailer();
        $mailer->now = time();
        return $mailer;
    }

    static function getNewRelic($config)
    {
        $relic = new ExternalInterface_Fake_NewRelic();
        if (Helper_Structure::getArrayValue($config, 'newrelic') == 'yes') {
            $relic = new ExternalInterface_NewRelic();
        }
        return $relic;
    }

    static function getKissMetrics($config)
    {
        $metrics = new ExternalInterface_Fake_KissMetrics();
        if ($kmKey = Helper_Structure::getArrayValue($config, 'kissmetrics')) {
            $metrics = new ExternalInterface_KissMetrics($kmKey);
        }
        return $metrics;
    }

    static function getSmser($config)
    {
        $class = 'ExternalInterface_Fake_Smser';
        if (Helper_Structure::getArrayValue($config, 'smser') == 'yes') {
            $class = 'ExternalInterface_Smser';
        }
        if (Helper_Structure::getArrayValue($config, 'smser') == 'log') {
            $class = 'ExternalInterface_TestSmser';
        }
        $smser = new $class();
        $smser->now = time();
        return $smser;
    }

    static function getIntercom($config)
    {
        $app_id = Helper_Structure::getArrayValue($config, 'intercom_app_id');
        $api_key = Helper_Structure::getArrayValue($config, 'intercom_api_key');

        $class = 'ExternalInterface_Fake_Intercom';

        if ($app_id == 'log') {
            $class = 'ExternalInterface_TestIntercom';
        } else if ($app_id != NULL && $api_key != NULL ) {
            $intercom = new ExternalInterface_Intercom($app_id, $api_key);
            $intercom->now = time();
            return $intercom;
        }
        $intercom = new $class();
        $intercom->now = time();
        return $intercom;
    }

    static function setBaseUrl($obj, $request)
    {
        if (!isset($obj->baseUrl)) {
            $obj->baseUrl = self::_getBaseUrlFromServerVar($request['server']);
        }
    }

    static function _getUrlSchemeFromServerVar($server)
    {
        return Helper_URL::getUrlSchemeFromServerVar($server);
    }

    static function _getBaseUrlFromServerVar($server)
    {
        $httpHost = 'host';
        if (isset($server['HTTP_HOST'])) {
            $httpHost = $server['HTTP_HOST'];
        }
        $scheme = self::_getUrlSchemeFromServerVar($server);
        $baseUrl = "$scheme://$httpHost";
        return $baseUrl;
    }

    static function sendEmailStatusChanged($controller, $subject, $page, $emails, $addData)
    {
        $from = $controller->getNoReplyFrom();
        $to = $emails['to'];
        $cc = $emails['cc'];
        $bcc = NULL;
        if (isset($emails['bcc'])) {
            $bcc = $emails['bcc'];
        }
        $body = $controller->_getEmailBodyFromViewPage($page, $addData);
        $smsBody = $controller->_getEmailBodyFromViewPage(str_replace('email/', 'sms/', $page), $addData);
        Helper_Mail::sendEmailWithFooter($controller, $from, $to, $cc, $bcc, $subject, $body);
        Helper_Mail::sendSms($controller, $emails['phone'], $smsBody);
    }

    static function sendOrderEmailStatusChanged($controller, $subject, $page, $obj)
    {
        $addData = array(
            'order' => Helper_Objects::translateOrderToArr($controller, $obj),
        );
        $bcc = $controller->getDepConfigValue('orders_email_bcc');
        $emails = array(
            'to' => $obj->getRecipientEmail(),
            'cc' => Helper_Krco::getOrdersEmailCc($controller, $obj),
            'bcc' => $controller->getDepConfigValue('orders_email_bcc'),
            'phone' => $obj->getRecipientPhoneNumber(),
        );
        self::sendEmailStatusChanged($controller, $subject, $page, $emails, $addData);
    }

    static function getOrdersEmailCc($controller, $obj)
    {
        $ccArr = array();
        if (method_exists($obj, 'getEmailCc')) {
            $ccArr = array($obj->getEmailCc());
        }
        if (!empty($controller->krco_config['orders_email_cc'])) {
            $ccArr[] = $controller->krco_config['orders_email_cc'];
        }
        $cc = implode(', ', array_filter($ccArr, function ($x) {return (bool)$x;}));
        return $cc;
    }

    static function renderFieldsText($fields)
    {
        $text = implode(array_map(function ($x) {
            $line = '';
            if (isset($x['value'])) {
                $name = $x['label'];
                $value = Form_Processor::formattedFieldValue($x);
                if (strpos($value, "\n") !== FALSE) {
                    $value = "\n  " . str_replace("\n", "\n  ", $value);
                }
                $line = "$name: $value\n";
            }
            return $line;
        }, $fields));
        return $text;
    }

    static function getGeneralFields($fields, $post)
    {
        array_walk($fields, function (&$x) {$x['type'] = 'text'; $x['is_required'] = FALSE;});
        $proc = new Form_Processor();
        $proc->form = array(
            'fields' => $fields,
        );
        $request = array('post' => $post);
        $proc->fillForm($request);
        return $proc->form['fields'];
    }

    static function sendErrorLog($obj, $config, $error, $request)
    {
        if ($error instanceof Exception) {
            $newrelic = self::getNewRelic($config);
            $newrelic->callNewRelic('newrelic_notice_error', array($error->getMessage(), $error));
            $obj->newrelic = $newrelic;
        }

        $to = NULL;
        if (isset($config['developer_email'])) {
            $to = $config['developer_email'];
        }
        if (!$to) {
            return ;
        }
        $mailer = self::getMailer($config);
        $from = 'SIRCLO <noreply@sirclo.com>';
        $requestArr = print_r($request, TRUE);
        $body = <<<EOD
Error Message:
$error

Request dump:
$requestArr

EOD;
        $reqArr = new PhpwebArray($request);
        $httpHost = $reqArr->get(array('server', 'HTTP_HOST'), 'Unknown Site');
        $requestUri = $reqArr->get(array('server', 'REQUEST_URI'));
        $mailer->send($from, $to, NULL, NULL, "Internal Server Error Report ($httpHost$requestUri)", $body);
        $obj->mailer = $mailer;
    }

    static function getOrderManageLine($controller, $order, $isManage=FALSE)
    {
        if (!$order) {
            return '';
        }
        $id = $order->getId();
        $adminPath = $controller->getKrcoConfigValue('admin_path');
        $orderSeg = 'orders';
        if ($order instanceof SubscriptionOrder) {
            $orderSeg = 'subscription_orders';
        }
        $orderManageLink = $controller->composeLink("/$adminPath/content/$orderSeg/$id", array(), FALSE);
        $path = "/$adminPath/content/$orderSeg/$id";
        $attrs = array();
        if ($isManage) {
            $path = "/$adminPath/content/$orderSeg";
            $attrs = array('filter_order_id' => $order->getLongId());
        }
        $orderManageLink = $controller->composeLink($path, $attrs, FALSE);
        $orderManageLine = "Click <a href=\"$orderManageLink\">here</a> to manage the order.";
        return $orderManageLine;
    }

    static function getObjAttributeWithDefault($controller, $obj, $attr, $configKey)
    {
        $val = NULL;
        $method = 'get' . $attr;
        if (method_exists($obj, $method)) {
            $val = $obj->$method();
            if (!$val) {
                $attributes = $controller->getKrcoConfigValue($configKey, 'default_attributes');
                if (isset($attributes[$attr])) {
                    $defaultFunc = $attributes[$attr];
                    if (is_callable($defaultFunc)) {
                        $val = $defaultFunc($obj);
                    }
                }
            }
        }
        return $val;
    }

    static function getRemoteAddr($request)
    {
        $addr = NULL;
        if (isset($request['server']['REMOTE_ADDR'])) {
            $addr = $request['server']['REMOTE_ADDR'];
        }
        if (isset($request['server']['HTTP_X_FORWARDED_FOR'])) {
            $addrs = $request['server']['HTTP_X_FORWARDED_FOR'];
            $addrsExpl = explode(',', $addrs);
            $addr = trim($addrsExpl[0]);
        }
        return $addr;
    }

    static function getSimpleProductsByAggregatorId($controller, $id)
    {
        $simpleProducts = array();
        if (isset($controller->products) && method_exists($controller->products, 'getSimpleProductsOfAggregator')) {
            $simpleProducts = $controller->getObjects('products', 'getSimpleProductsOfAggregator', array($id));
        }
        return $simpleProducts;
    }

    static function addMemberPoint($controller, $email, $addPoint)
    {
        $member = $controller->getSingleObject('members', 'getMemberByEmail', array($email));
        if (isset($member) && $addPoint) {
            $member->setPoint($member->getPoint() + $addPoint);
            $controller->dbUpdateObject('members', 'updateMember', array($member));
            return $member;
        }
        return NULL;
    }

    static function setMemberLevel($controller, $email, $newLevel)
    {
        $member = $email;
        if (!($email instanceof Member)) {
            $member = $controller->getSingleObject('members', 'getMemberByEmail', array($email));
        }
        if (isset($member) && $newLevel != $member->getMemberLevel()) {
            $member->setMemberLevel($newLevel);
            $controller->dbUpdateObject('members', 'updateMember', array($member));
            $viewPageName = 'email/member_level_changed';
            if ($controller->_isViewPageExists($viewPageName)) {
                $body = $controller->_getMemberEmailBodyFromViewPage($viewPageName, $member);
                $smsBody = $controller->_getMemberEmailBodyFromViewPage('sms/member_level_changed', $member);
                $from = $controller->getNoReplyFrom();
                $email = $member->getEmail();
                $name = $member->getFirstName() . ' ' . $member->getLastName();
                $to = "$name <$email>";
                Helper_Mail::sendEmailWithFooter($controller, $from, $to, NULL, NULL, "Change in Member Level ($newLevel)", $body);
                Helper_Mail::sendSms($controller, $member->getPhoneNumber(), $smsBody);
            }
            return $member;
        }
        return NULL;
    }

    static function getLastConfirmedOrdersOfMember($controller, $member, $startTs=NULL, $limit=NULL)
    {
        $orders = array();
        if ($member) {
            $memberFilter = new SearchFilter(Order::$FIELD_META_MEMBER, SearchFilter::$EQUAL, $member->getId());
            $statusFilter = new SearchFilter(Order::$FIELD_META_ORDER_STATUS, SearchFilter::$NOT_EQUAL, 'Pending');
            $filters = array($memberFilter, $statusFilter);
            if ($startTs) {
                $timeFilter = new SearchFilter(Order::$FIELD_META_ORDER_DATE, SearchFilter::$GREATER_THAN_EQUAL, Helper_Date::formatSqlDatetime($startTs));
                $filters[] = $timeFilter;
            }
            $sortField = Order::$FIELD_META_ORDER_DATE;
            $orders = $controller->getObjects('orders', 'getOrders', array(NULL, $filters, $sortField, TRUE, $limit));
        }
        return $orders;
    }

    static function addPointToReferer($controller, $refereeEmail, $addPoint)
    {
        $inv = $controller->getSingleObject('member_invitations', 'getMemberInvitationByEmail', array($refereeEmail));
        if ($inv) {
            $refererEmail = $inv->getRefererEmail();
            $member = $controller->getSingleObject('members', 'getMemberByEmail', array($refererEmail));
            self::addMemberPoint($controller, $refererEmail, $addPoint);
            self::_sendMemberInvitationAccepted($controller, $member, $inv);
        }
    }

    static function deactivateMemberInvitationByEmail($controller, $refereeEmail)
    {
        $inv = $controller->getSingleObject('member_invitations', 'getMemberInvitationByEmail', array($refereeEmail));
        if ($inv) {
            $inv->setIsActive(FALSE);
            $controller->dbUpdateObject('member_invitations', 'updateMemberInvitation', array($inv));
        }
    }

    static function _sendMemberInvitationAccepted($controller, $member, $inv)
    {
        $viewPageName = 'email/member_invitation_accepted';
        $invArr = Helper_Objects::translateObjectToArr($controller, $inv, 'invitationToArr');
        $body = $controller->_getMemberEmailBodyFromViewPage($viewPageName, $member, array('invitation' => $invArr));
        $smsBody = $controller->_getMemberEmailBodyFromViewPage('sms/member_invitation_accepted', $member, array('invitation' => $invArr));
        $from = $controller->getNoReplyFrom();
        $to = $member->getEmail();
        $siteName = $controller->getSiteName();
        $refereeEmail = $inv->getEmail();
        Helper_Mail::sendEmailWithFooter($controller, $from, $to, NULL, NULL, "$refereeEmail joined $siteName", $body);
        Helper_Mail::sendSms($controller, $member->getPhoneNumber(), $smsBody);
    }

    static function createArticleRoute($segment)
    {
        $route = array(
            'controller' => 'Krco_Articles',
            'attrs' => array(
                'articles_config' => array(
                    'label' => $segment,
                    'title' => str_replace('_', ' ', $segment),
                ),
            ),
        );
        return $route;
    }

    static function isProductWatchNotifiable($product, $isIgnoreStock)
    {
        if ($product->getIsActive() && ($product->getTotalInventory()>0 || $isIgnoreStock)) {
            return TRUE;
        }
        return FALSE;
    }

    static function handleWatchList($controller, $product, $isIgnoreStock)
    {
        if (self::isProductWatchNotifiable($product, $isIgnoreStock)) {
            $objs = $controller->getObjects('watches', 'getWatchesByItemId', array($product->getId()));
            if (is_array($objs)) {
                foreach ($objs as $watch) {
                    self::sendWatchEmail($controller, $watch, $product);
                    $watch->setIsNotified(TRUE);
                    $controller->watches->updateWatch($watch);
                }
            }
        }
    }

    static function _createFakeMemberFromEmail($email)
    {
        $member = new Member();
        $member->setFirstName('Customer');
        $member->setEmail($email);
        return $member;
    }

    static function sendWatchEmail($controller, $watch, $product)
    {
        $itemTitle = $product->getTitle();
        $from = $controller->getNoReplyFrom();
        $memberId = $watch->getMemberId();
        if (is_numeric($memberId)) {
            $member = $controller->getSingleObject('members', 'getMemberById', array($memberId));
        } else {
            $member = $controller->getSingleObject('members', 'getMemberByEmail', array($memberId));
            if (!$member) {
                $member = self::_createFakeMemberFromEmail($memberId);
            }
        }
        if (!$member) {
            return ;
        }
        $to = $member->getEmail();
        $body = self::getStockAvailableEmailBody($controller, $product, $member);
        Helper_Mail::sendEmailWithFooter($controller, $from, $to, NULL, NULL, "Item Available: $itemTitle", $body);
    }

    static function getStockAvailableEmailBody($controller, $product, $member)
    {
        $viewPageName = 'email/product_available';
        if ($controller->_isViewPageExists($viewPageName)) {
            $body = $controller->_getProductEmailBodyFromViewPage($viewPageName, $product);
            return $body;
        }
        $itemTitle = $product->getTitle();
        $prodLink = Helper_Krco::getKrcoObjLink($controller, $product, 'products');
        $signature = $controller->_getEmailSignature();
        $name = $member->getFirstName();
        $body = <<<EOD
<p>
Dear $name,
</p>
<p>
You received this email because you requested to be notified when an item is available. $itemTitle is now available for online purchase here:<br />
$prodLink
</p>
$signature

EOD;
        return $controller->_getHtmlEmailBody($body);
    }

    static function afterUpdateProduct($controller, $obj)
    {
        if (method_exists($obj, 'getIsIgnoreStock')) {
            $isIgnoreStock = $obj->getIsIgnoreStock();
            self::handleWatchList($controller, $obj, $isIgnoreStock);
            $simples = Helper_Krco::getSimpleProductsByAggregatorId($controller, $obj->getId());;
            foreach ($simples as $simple) {
                self::handleWatchList($controller, $simple, $isIgnoreStock);
            }
        }
    }

    static function getCached($controller, $method, $attrs=array())
    {
        $cacheKey = $method . implode('', $attrs);
        if (isset($controller->cache->$cacheKey)) {
            return $controller->cache->$cacheKey;
        }
        $attr0=NULL; if (isset($attrs[0])) $attr0 = $attrs[0];
        $val = $controller->$method($attr0);
        if (!isset($controller->cache)) {
            $controller->cache = new stdClass();
        }
        $controller->cache->$cacheKey = $val;
        return $val;
    }

    static function addUserToSite($db_users, $siteid, $name, $email, $company, $password)
    {
        if (!$password) {
            return new Result(Result::$OK, NULL);
        }
        $result = $db_users->getUserByEmail($email);
        if ($result->isOK()) {
            $user = $result->getReturnedObj();
            $newIds = $user->getSiteIds();
            $newIds[] = $siteid;
            $user->setSiteIds($newIds);
            $result = $db_users->updateUser($user);
        } else {
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $user->setCompany($company);
            $user->setSiteIds(array($siteid));
            $user->setPassword($password);
            $user->setRawAcl('');
            $result = $db_users->addUser($user);
        }
        return $result;
    }

    static function getText($messages, $index)
    {
        $msg = $index;
        if (isset($messages[$index])) {
            $msg = $messages[$index];
        }
        return $msg;
    }

    static function getLocationByIp($ip_country, $addr)
    {
        $location = NULL;
        if (isset($ip_country->pdo) && ($ip_country->pdo != 'fake')) {
            $location = $ip_country->getCountryByIp($addr);
        }
        return $location;
    }

    static function getAdminPanelLoginEndpoint($controller)
    {
        $ep = NULL;
        if (!empty($controller->krco_config['admin_panel']['login_endpoint'])) {
            $ep = $controller->krco_config['admin_panel']['login_endpoint'];
        }
        if (!empty($controller->deployment_config['admin_panel_login_endpoint'])) {
            $ep = $controller->deployment_config['admin_panel_login_endpoint'];
        }
        if (!empty($controller->deployment_config['login_endpoint'])) {
            $ep = $controller->deployment_config['login_endpoint'];
        }
        return $ep;
    }

    static function productToVariantArr($obj)
    {
        $arr = NULL;
        if ($obj) {
            $arr = array(
                'option1' => $obj->getAttribute1(),
                'option2' => $obj->getAttribute2(),
                'product_code' => $obj->getProductCode(),
                'stock' => $obj->getTotalInventory(),
                'price' => $obj->getPrice(),
            );
        }
        return $arr;
    }

    static function productsToVariantArrs($objs)
    {
        $arrs = array();
        if (is_array($objs)) {
            foreach ($objs as $obj) {
                $arr = self::productToVariantArr($obj);
                if ($arr) {
                    $arrs[] = $arr;
                }
            }
        }
        return $arrs;
    }

    static function variantToOptions($controller, $var)
    {
        $flipped = self::getFlippedOptionMap($controller);
        $options = array();

        if (!empty($var['option1']) && !empty($flipped['Attribute1'])) {
            $options[$flipped['Attribute1']] = $var['option1'];
        }
        if (!empty($var['option2']) && !empty($flipped['Attribute2'])) {
            $options[$flipped['Attribute2']] = $var['option2'];
        }
        return $options;
    }

    static function setProductVariantAttributes($obj, $var)
    {
        //$obj->setProductCode(Helper_Structure::getArrayValue($var, 'product_code'));
        $obj->setPrice(Helper_Structure::getArrayValue($var, 'price'));
        $obj->setTotalInventory(Helper_Structure::getArrayValue($var, 'stock'));
        $obj->setAttribute1(Helper_Structure::getArrayValue($var, 'option1'));
        $obj->setAttribute2(Helper_Structure::getArrayValue($var, 'option2'));
    }

    static function setSimpleProductTitle($simple, $var, $agg)
    {
        $simple->setTitle(self::generateSimpleProductTitle($agg, Helper_Structure::getArrayValue($var, 'option1'), Helper_Structure::getArrayValue($var, 'option2')));
    }

    static function setProductVariants($man, $obj, $desc)
    {
        $simpleProducts = $man->controller->products->getSimpleProductsOfAggregator($obj->getId())->getReturnedObj();
        $usedIds = array();
        if (!isset($desc['variants'])) {
            return ;
        }
        $variants = $desc['variants'];
        foreach ($variants as $var) {
            $options = self::variantToOptions($man->controller, $var);
            $simple = self::chooseSimpleProduct($man->controller, $simpleProducts, $options);
            if ($simple) {
                $usedIds[] = $simple->getId();
                $newSimple = clone $simple;
                self::setProductVariantAttributes($newSimple, $var);
                self::setSimpleProductTitle($newSimple, $var, $obj);
                $vals = array(
                    'aggregator_id' => $obj->getId(),
                    'item_code' => $var['product_code'],
                );
                $man->setValuesToObj($vals, $newSimple);
                $man->updateObj($newSimple, $simple);
            } else {
                $newSimple = $man->newObj();
                self::setProductVariantAttributes($newSimple, $var);
                self::setSimpleProductTitle($newSimple, $var, $obj);
                $newSimple->setProductType('simple');
                $vals = array(
                    'aggregator_id' => $obj->getId(),
                    'item_code' => $var['product_code'],
                );
                $man->setValuesToObj($vals, $newSimple);
                $man->addObj($newSimple);
            }
        }
        foreach ($simpleProducts as $leftSimple) {
            if (!in_array($leftSimple->getId(), $usedIds)) {
                $man->deleteObject($leftSimple->getId());
            }
        }
    }

    static function getFilteredProductOptions($map, $options, $isReversed=FALSE)
    {
        $filteredOptions = array();
        if (!is_array($map)) {
            $map = array();
        }
        $optKeys = array_keys($map);
        foreach ($options as $key => $val) {
            if ($isReversed xor in_array($key, $optKeys)) {
                $filteredOptions[$key] = $val;
            }
        }
        return $filteredOptions;
    }

    static function isOptionsCorrect($controller, $prod, $options)
    {
        $map = $controller->getKrcoConfigValue('products', 'optionMap');
        return self::isOptionsCorrectWithMap($map, $prod, $options);
    }

    static function isOptionsCorrectWithMap($map, $prod, $options)
    {
        $prodOptionArr = Helper_Structure::mapObjToArr($prod, $map);
        $filteredOptions = self::getFilteredProductOptions($map, $options);
        $isCorrect = (array_intersect_assoc($prodOptionArr, $options) == $filteredOptions);
        return $isCorrect;
    }

    static function chooseSimpleProduct($controller, $simpleProds, $options)
    {
        $prod = NULL;
        foreach ($simpleProds as $simpleProd) {
            if (self::isOptionsCorrect($controller, $simpleProd, $options)) {
                $prod = $simpleProd;
                break;
            }
        }
        return $prod;
    }

    static function generateSimpleProductTitle($agg, $attribute1, $attribute2)
    {
        $optionStr = implode(', ', array_filter(array($attribute1, $attribute2), function ($x) {return (bool)$x;}));
        $simple_title = $agg->getTitle() . " ($optionStr)";
        return $simple_title;
    }

    static function getFlippedOptionMap($controller)
    {
        $flipped = array();
        $map = $controller->getKrcoConfigValue('products', 'optionMap');
        if ($map) {
            $flipped = array_flip($controller->getKrcoConfigValue('products', 'optionMap'));
        }
        return $flipped;
    }

    static function getVariantsFieldDesc($controller, $obj)
    {
        $simpleProductObjs = $controller->products->getSimpleProductsOfAggregator($obj->getId())->getReturnedObj();
        $simpleProductArrs = Helper_Krco::productsToVariantArrs($simpleProductObjs);
        $flipped = self::getFlippedOptionMap($controller);
        return array(
            'option1_title' => $controller->getProductOptionTitleByKey(Helper_Structure::getArrayValue($flipped, 'Attribute1')),
            'option2_title' => $controller->getProductOptionTitleByKey(Helper_Structure::getArrayValue($flipped, 'Attribute2')),
            'with_price_per_variant' => !empty($controller->deployment_config['with_price_per_variant']),
            'variants' => $simpleProductArrs,
        );
    }

    static function setProductOptionsToConfig($controller, $obj)
    {
        $map = $controller->getKrcoConfigValue('products', 'optionMap');
        if (is_array($map)) {
            foreach ($map as $key => $fieldName) {
                $getMethod = 'get' . $fieldName;
                $val = $obj->$getMethod();
                $depConfigKey = 'product_option_' . $key;
                $depConfig = $controller->getSingleObject('depconfigs', 'getDepConfigByTitle', array($depConfigKey));
                if ($depConfig) {
                    $options = Helper_String::commaStrToArr($depConfig->getDescription());
                    if ($val && !in_array(trim($val), $options)) {
                        $options[] = $val;
                        $depConfig->setDescription(Helper_String::formatCommaArray($options));
                        $controller->depconfigs->updateDepConfig($depConfig);
                    }
                }
            }
        }
    }

    static function getPhoneOfObj($obj)
    {
        $to = '';
        if (method_exists($obj, 'getRecipientPhoneNumber')) {
            $to = $obj->getRecipientPhoneNumber();
        }
        if (method_exists($obj, 'getPhone')) {
            $to = $obj->getPhone();
        }
        return $to;
    }

    static function buildNavFromArticles($controller, $articleLabel, $articles, $articleFid=NULL)
    {
        $tempNav = array();
        $nArticles = count($articles);
        foreach ($articles as $obj) {
            $objFid = $obj->getFriendlyId();
            $item = array(
                'title' => $obj->getTitle($controller->lang),
                'link' => Helper_Structure::composeArticleLink($controller, $articleLabel, (($nArticles > 1) ? $objFid : '')),
                '_fid' => $objFid,
            );
            $isItemActive = FALSE;
            if ($obj->getFriendlyId() == $articleFid) {
                $isItemActive = TRUE;
                $item['is_active'] = TRUE;
            }
            $navKey = $obj->getTitle();
            if ($group = $obj->getGroup()) {
                $navKey = $group;
                if (!isset($tempNav[$navKey])) {
                    $tempNav[$navKey] = array(
                        'title' => $group,
                        'link' => '',
                        '_fid' => $group,
                    );
                }
                if ($isItemActive) {
                    $tempNav[$navKey]['is_active'] = TRUE;
                }
            }
            if (isset($tempNav[$navKey])) {
                if ($navKey == $obj->getTitle()) {
                    $tempNav[$navKey] = $item + $tempNav[$navKey];
                } else {
                    $subFid = "$articleLabel/" . $item['_fid'];
                    unset($item['_fid']);
                    $tempNav[$navKey]['sub_nav'][$subFid] = $item;
                }
            } else {
                $tempNav[$navKey] = $item;
            }
        }
        $nav = array();
        foreach ($tempNav as $item) {
            $key = "$articleLabel/" . $item['_fid'];
            unset($item['_fid']);
            $nav[$key] = $item;
        }
        return $nav;
    }

    static function getWishObjsOfMember($controller, $member, $key_pl)
    {
        $ids = Helper_String::commaStrToArr($member->getWishProductIdStr());
        $objs = $controller->_indicesToObjects($ids, $key_pl, 'getActiveItemById');
        return $objs;
    }

    static function getCompleteRoutes($preparer, $routes)
    {
        $controller = new Krco_Articles();
        $preparer->prepareController($controller);
        $articleLabels = $controller->getObjects('articles', 'getAllLabels', array());
        foreach ($articleLabels as $label) {
            $segment = str_replace(' ', '_', $label);
            if (!isset($routes[$segment])) {
                $routes[$segment] = Helper_Krco::createArticleRoute($segment);
            }
        }
        return $routes;
    }

    static function findGeneralCode($controller, $wantedCode, $dbName, $getByCodeMethod, $oriObj, $constructCode, $suffix, $displayName, $withFindAlternateId)
    {
        if (!$wantedCode) {
            $wantedCode = $constructCode($displayName);
        }
        $code = $wantedCode;
        if (is_callable(array($controller->$dbName, $getByCodeMethod)) && $withFindAlternateId) {
            $code_suffix = 2;
            $obj = $controller->getSingleObject($dbName, $getByCodeMethod, array($code));
            while ($obj && ($code_suffix < 100) && ($obj->getId() != $oriObj->getId())) {
                $code = $wantedCode . "$suffix$code_suffix";
                $obj = $controller->getSingleObject($dbName, $getByCodeMethod, array($code));
                $code_suffix++;
            }
        }
        return $code;
    }

    static function getBirthdayConfigFromDb($controller)
    {
        $percentage = $controller->getDepConfigValue('auto_birthday_coupon_discount_percentage');
        if ($percentage) {
            $config = array(
                'discount_percentage' => $percentage,
            );
            return $config;
        }
        return NULL;
    }

    static function getAutoBirthdayConfig($controller)
    {
        $birthdayConfig = $controller->getKrcoConfigValue('cart', 'auto_birthday_coupons');
        if (!$birthdayConfig) {
            $birthdayConfig = self::getBirthdayConfigFromDb($controller);
        }
        return $birthdayConfig;
    }

    static function getObjTypeOfDb($controller, $db)
    {
        $objType = $db;
        if (!empty($controller->krco_config['obj_types_of_dbs'][$db])) {
            $objType = $controller->krco_config['obj_types_of_dbs'][$db];
        }
        return $objType;
    }

    static function isFeatureRequirementSatisfied($controller, $requirement)
    {
        if (!empty($requirement['requires'])) {
            $limit = $controller->getLimiterValue($requirement['requires']);
            $isUsed = FALSE;
            if (isset($limit)) {
                $isUsed = $limit;
            } else if (isset($requirement['default'])) {
                $isUsed = $requirement['default'];
            }
            return $isUsed;
        }
    }

    static function attachParentObjs($objs)
    {
        $tempObjs = array();
        foreach ($objs as $obj) {
            $tempObjs[$obj->getId()] = $obj;
        }

        foreach ($tempObjs as $tempObj) {
            $parentObj = NULL;
            if (($parentId = $tempObj->getParentId()) && isset($tempObjs[$parentId])) {
                $parentObj = $tempObjs[$parentId];
            }
            $tempObj->__parentObj = $parentObj;
        }
    }

    static function getObjTitleTraceParent($obj, $idx=0)
    {
        if (isset($obj->__parentObj) && $idx<4) {
            $thisTitle = $obj->getTitle();
            if ($obj->__parentObj === $obj) {
                return $thisTitle;
            }
            return self::getObjTitleTraceParent($obj->__parentObj, $idx+1) . ' - ' . $thisTitle;
        }
        return $obj->getTitle();
    }

    static function getDebugTraces()
    {
        $traces = array_map(function ($x) {
            $func = $x['function'];
            $file = $x['file'];
            $line = $x['line'];
            return "$func file $file line $line";
        }, debug_backtrace());
        return $traces;
    }

    static function calculatePriceGiftCard($controller, $cartItem, $member, $product)
    {
        $options = $cartItem->getOptions();
        $price = NULL;
        if (($cartItem->getProductFid() == '_gift-card') && isset($options['card-value'])) {
            $price = $options['card-value'];
        }
        return $price;
    }
}
