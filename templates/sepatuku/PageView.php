<?php
class PageView
{
    public $file_ext = 'php';

    function resource($path,$skipHash=false)
    {
        // skip hash default to false
        if (!isset($skipHash) || $skipHash == null) {
            $skipHash = false;
        }

        // skipHash set to true if hashValue is not set properly
        if (!isset($this->hashValue) || strlen($this->hashValue . "") == 0) {
            $skipHash = true;
        }

        if (isset($this->staticResourceUrl)) {
            // use static resource url as base url
            $result = $this->staticResourceUrl;

            // static resource url we dont use hash anymore
            $skipHash = true;
        } else {
            // use resource_url as base url
            $result = $this->resource_url;
        }

        // construct result from base resource url and path
        $result = $result . '/' . $path;

        // add hash value to resource url
        if (!$skipHash) {
            $result = $result . '?hash=' . $this->hashValue;
        }
        return $result;
    }

    function compose_url($path)
    {
        return $this->base_url . $path;
    }

    function htmlTag()
    {
        $s = '';
        $s .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
        $s .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
        return $s;
    }

    function renderMessage($is_html_escape=TRUE)
    {
        $s = '';
        if (isset($this->message['text']) && isset($this->message['type'])) {
            $this->data['message'] = $this->message['text'];
            $this->data['message_type'] = $this->message['type'];
        } 
        if (isset($this->data['message']) && is_array($this->data['message']) && isset($this->data['message']['type']) && isset($this->data['message']['text'])) {
            $temp = $this->data['message'];
            $this->data['message'] = $temp['text'];
            $this->data['message_type'] = $temp['type'];
        }
        if (isset($this->data['message_type']) && isset($this->data['message'])) {
            $type = $this->data['message_type'];
            $messages = $this->data['message'];
            if (is_string($messages)) {
                $messages = array($messages);
            }
            if ($is_html_escape) {
                $messages = array_map(function ($x) { return htmlspecialchars($x);}, $messages);
            }
            $message_str = implode("<br />", $messages);
            $s .= "<div class=\"message $type\">\n";
            $s .= "<p>$message_str</p>\n";
            $s .= "</div>\n";
        }
        return $s;
    }

    function htmlForm($form)
    {
        $s = '';
        $id = $form['id'];
        $s .= "<form id=\"$id\" novalidate=\"novalidate\" action=\"\" method=\"post\">\n";
        $message = '';
        if (isset($form['message'])) {
            $message = $form['message'];
        }
        if ($message) {
        $s .= "<div class=\"form-message\">\n";
        $s .= "$message\n";
        $s .= "</div>\n";
        }
        $s .= "<ul>\n";
        $fields = $form['fields'];
        foreach ($fields as $field) {
            $s .= "<li>\n";
            $label = $field['label'];
            $star = '';
            $required = '';
            if (isset($field['is_required']) && $field['is_required']) {
                $star = " <span class=\"field-required\">*</span>";
                $required = " required=\"required\"";
            }
            $s .= "<label>$label$star</label>\n";
            $type = $field['type'];
            $name = $field['name'];
            if ($type == 'text') {
                $s .= "<input name=\"$name\" type=\"text\"$required/>\n";
            } else if ($type == 'email') {
                $s .= "<input name=\"$name\" type=\"email\"$required/>\n";
            } else if ($type == 'textarea') {
                $s .= "<textarea name=\"$name\"$required></textarea>\n";
            }
            $s .= "</li>\n";
        }
        $s .= "<li class=\"form-notes\">Fields marked with <span class=\"field-required\">*</span> are required.</li>\n";
        $submit_text = $form['submit_text'];
        if (!isset($form['image_buttons'])) {
            $s .= "<li>\n";
            $s .= "<input type=\"submit\" value=\"$submit_text\"/>\n";
            $s .= "</li>\n";
        }
        $s .= "</ul>\n";
        if (isset($form['image_buttons'])) {
            $s .= "<div class=\"hover-fade\" id=\"form-btn-send\">\n";
            $image_active = $form['image_buttons']['active'];
            $image_normal = $form['image_buttons']['normal'];
            $s .= "<img class=\"after\" src={sirclo_resource file=$image_active} alt=\"$submit_text\"/>\n";
            $s .= "<input type=\"image\" class=\"before\" src=\"$image_normal\" alt=\"$submit_text\"/>\n";
            $s .= "</div>\n";
        }
        $s .= "</form>\n";
        return $s;
    }

    function _getAlternatePageNames($filename)
    {
        $alternateNames = array();
        $altFilenames = array($filename);
        if (strpos($filename, 'store/') !== FALSE) {
            $alt = str_replace('store/', 'krco/', $filename);
            $altFilenames[] = $alt;
        }
        foreach ($altFilenames as $altFilename) {
            $alternateNames[] = "$this->page_dir/$altFilename";
            if (isset($this->fw_page_dir)) {
                $alternateNames[] = "$this->fw_page_dir/$altFilename";
            }
        }
        return $alternateNames;
    }

    function getFileNameOfPageRaw($viewName)
    {
        $fileName = NULL;
        $ext = $this->file_ext;
        $alternateNames = $this->_getAlternatePageNames("$viewName.$ext");
        foreach ($alternateNames as $altName) {
            if (file_exists($altName)) {
                $fileName = $altName;
                break;
            }
        }
        return $fileName;
    }

    function _getFileNameOfPage($viewName)
    {
        $ext = $this->file_ext;
        $fileName = $this->getFileNameOfPageRaw($viewName);
        if (!$fileName && (!isset($this->withResortsToPageNotFound) || $this->withResortsToPageNotFound)) {
            $fileName = $this->page_dir . "/page_not_found.$ext";
        }
        return $fileName;
    }

    function loadView($view_name, $addData=NULL)
    {

        if (isset($this->data)) extract($this->data);
        if (isset($addData)) {
            extract($addData);
        }
        $fileName = $this->_getFileNameOfPage($view_name);
        if ($fileName) {
            include $fileName;
        }
    }

    function htmlMainNav($navs, $classes="")
    {
        $s = $this->htmlNav($navs, $classes, 'main-nav');
        return $s;
    }

    function htmlNav($navs, $classes="", $id=NULL, $active_clickable=FALSE, $withSubNav=FALSE)
    {
        $s = '';
        $as = '';
        foreach ($navs as $nav) {
            $active_class = '';
            $is_active = FALSE;
            if (isset($nav['is_active']) && $nav['is_active']) {
                $active_class = ' nav-active';
                $is_active = TRUE;
            }
            $attrs = array(
                'class' => "nav-item$active_class",
            );
            if (isset($nav['is_link_ext']) && $nav['is_link_ext']) {
                $attrs['target'] = "_blank";
            }
            $subNav = '';
            if ($withSubNav && isset($nav['sub_nav'])) {
                $subNav = $this->htmlNav($nav['sub_nav'], 'level-2');
                $attrs['class'] .= " has-sub-nav";
            }
            if (isset($nav['class']) && $nav['class']) {
                $attrs['class'] .= " " . $nav['class'];
            }
            $a = $this->xmlA($nav['title'], (!$is_active || $active_clickable) ? $nav['link'] : '', $attrs);
            if ($withSubNav) {
                $as .= $this->xmlDiv($a . $subNav, array('class' => 'nav-link'));
            } else {
                $as .= $a;
            }
        }
        $attrs = array();
        if ($id) $attrs['id'] = $id;
        if ($classes) $attrs['class'] = $classes;
        $s = $this->xmlDiv($as, $attrs);
        return $s;
    }

    function echos($s)
    {
        echo htmlspecialchars($s);
    }

    function addGetToUrl($url, $get)
    {
        return Helper_URL::addGetToUrl($url, $get);
    }

    function paging($paging, $stretch_lim=7)
    {
        $options = array();
        if (isset($this->messages['paging_prev'])) {
            $options['text_prev'] = $this->getMessage('paging_prev');
        }
        if (isset($this->messages['paging_next'])) {
            $options['text_next'] = $this->getMessage('paging_next');
        }
        return Helper_Renderer::renderPaging($paging, $stretch_lim, $options);
    }

    function _prepareData()
    {
        if (isset($this->message['text']) && isset($this->message['type'])) {
            $this->data['message'] = $this->message['text'];
            $this->data['message_type'] = $this->message['type'];
        }
    }

    function render()
    {
        $this->_prepareData();
        ob_start();
        try {
            if (isset($this->page)) {
                $this->loadView($this->page);
            }
        } catch (Exception $e) {
            echo "internal server error\n";
            $this->_errorLog = (string)($e);
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    function getText($index)
    {
        return $this->getMessage($index);
    }

    function getMessage($index)
    {
        return Helper_Krco::getText(isset($this->messages) ? $this->messages : array(), $index);
    }

    function button($btn_file)
    {
        $ls = '';
        if (isset($this->lang) && ($this->lang != 'en')) {
            $ls = '_' . $this->lang;
        }
        $s = $this->resource("images$ls/$btn_file");
        return $s;
    }

    function button_location($btn_file)
    {
        $ls = '';
        if (isset($this->site_location) && ($this->site_location != 'sg')) {
            $ls = '_' . $this->site_location;
        }
        $s = $this->resource("images$ls/$btn_file");
        return $s;
    }
    
    function htmlBreadcrumb($bc, $prefix=NULL)
    {
        $s = '';
        $s .= "<div class=\"breadcrumb\">\n";
        if (isset($prefix)) {
            $s .= "$prefix\n";
        }
        $s .= "<ul>\n";
        foreach ($bc as $key => $bcitem) {
            $link = $bcitem['link'];
            $title = $bcitem['title'];
            $escTitle = htmlspecialchars($title);
            $quo = '';
            if ($key > 0) {
                $quo = '&raquo; ';
            }
            $s .= "<li itemscope itemtype=\"http://data-vocabulary.org/Breadcrumb\">$quo<a itemprop=\"url\" href=\"$link\"><span itemprop=\"title\">$escTitle</span></a></li>\n";
        }
        $s .= "</ul>\n";
        $s .= "</div>\n";
        return $s;
    }

    function _getMenuButtonDropdown($item, $ddTitleText=NULL)
    {
        $dd = '';
        if (isset($item['sub_buttons'])) {
            $ddTitle = '';
            if ($ddTitleText) {
                $ddTitle = "$ddTitleText ";
            }
            $caret = Helper_Xml::xmlTag('span', '', array('class' => 'caret'));
            $ddToggle = Helper_Xml::xmlTag('button', $ddTitle . $caret, array(
                'class' => 'btn btn-mini dropdown-toggle',
                'data-toggle' => 'dropdown',
            ));
            $ddLis = '';
            foreach ($item['sub_buttons'] as $subbut) {
                $butAttrs = array();
                if (isset($subbut['class'])) {
                    $butAttrs['class'] = $subbut['class'];
                }
                $butAttrs += $this->getMenuItemAttrs($subbut);
                $ddLis .= Helper_Xml::xmlTag('li', Helper_Xml::xmlA($subbut['title'], $subbut['link'], $butAttrs));
            }
            $ddUl = Helper_Xml::xmlTag('ul', $ddLis, array(
                'class' => 'dropdown-menu',
            ));
            $dd = $ddToggle . $ddUl;
        }
        return $dd;
    }

    function getMenuItemAttrs($item)
    {
        $attrs = array();
        if (!empty($item['attrs'])) {
            $attrs = $item['attrs'];
        }
        return $attrs;
    }

    function htmlMenuBar($menu)
    {
        if (count($menu) == 0) {
            return NULL;
        }
        $lis = '';
        foreach ($menu as $item) {
            $link = $item['link'];
            $title = $item['title'];
            $s = '';
            $itemAttrs = $this->getMenuItemAttrs($item);
            if ($item['type'] == 'button') {
                if (isset($item['action'])) {
                    $action = $item['action'];
                    $formContent = '';
                    if (isset($item['fields'])) {
                        foreach ($item['fields'] as $field) {
                            $name = $field['name'];
                            $rawValue = '';
                            if (isset($field['value'])) {
                                $rawValue = $field['value'];
                            }
                            $formContent .= Helper_Xml::xmlEmptyTag('input', array(
                                'type' => 'hidden',
                                'name' => $name,
                                'value' => $rawValue,
                            ));
                        }
                    }
                    $classContent = 'btn btn-mini';
                    if (isset($item['class'])) {
                        $classContent .= ' ' . $item['class'];
                    }
                    $formContent .= Helper_Xml::xmlTag('button', $title, array(
                        'type' => 'button',
                        'class' => $classContent,
                    ) + $itemAttrs);
                    $xmlForm = Helper_Xml::xmlTag('form', $formContent, array(
                        'action' => $action,
                        'method' => 'post',
                    ));
                    $s .= $xmlForm;
                } else {
                    $classContent = 'btn btn-mini';
                    if (isset($item['class'])) {
                        $classContent .= ' ' . $item['class'];
                    }
                    $mainA = Helper_Xml::xmlA($title, $link, array(
                        'class' => $classContent,
                    ) + $itemAttrs);
                    $ddTitle = NULL;
                    if (!$item['link']) {
                        $mainA = '';
                        $ddTitle = $item['title'];
                    }
                    $dd = $this->_getMenuButtonDropdown($item, $ddTitle);
                    $s .= Helper_Xml::xmlTag('div', $mainA . $dd, array(
                        'class' => 'btn-group dropdown',
                    ));
                }
            } else if ($item['type'] == 'text') {
                $formContent = '';
                $val = $item['value'];
                if (isset($item['fields'])) {
                    foreach ($item['fields'] as $field) {
                        $name = $field['name'];
                        $value = '';
                        if (isset($field['value'])) {
                            $value = htmlspecialchars($field['value']);
                        }
                        $formContent .= Helper_Xml::xmlEmptyTag('input', array(
                            'type' => 'hidden',
                            'name' => $name,
                            'value' => $value,
                        ));
                    }
                }
                $name = $item['name'];
                $formContent .= Helper_Xml::xmlEmptyTag('input', array(
                    'type' => 'text',
                    'name' => $name,
                    'value' => $val,
                ));
                $formContent .= Helper_Xml::xmlTag('button', $title, array(
                    'type' => 'submit',
                    'class' => 'btn btn-mini',
                ));
                $xmlForm = Helper_Xml::xmlTag('form', $formContent, array(
                    'action' => $link,
                    'method' => 'get',
                    'class' => 'input-append',
                ));
                $s .= $xmlForm;
            } else if ($item['type'] == 'dropdown') {
                $formContent = '';
                $val = $item['value'];
                if (isset($item['fields'])) {
                    foreach ($item['fields'] as $field) {
                        $name = $field['name'];
                        $value = '';
                        if (isset($field['value'])) {
                            $value = htmlspecialchars($field['value']);
                        }
                        $formContent .= Helper_Xml::xmlEmptyTag('input', array(
                            'type' => 'hidden',
                            'name' => $name,
                            'value' => $value,
                        ));
                    }
                }
                $name = $item['name'];
                $selectContent = '';
                $selectContent .= Helper_Xml::xmlTag('option', "-- $title --", array(
                    'value' => '',
                ));
                foreach ($item['options'] as $opt) {
                    $opt_val = $opt['value'];
                    $opt_tit = $opt['title'];
                    $optionAttr = array();
                    if ($opt_val == $val) {
                        $optionAttr = array(
                            'selected' => 'selected',
                        );
                    }
                    $optionAttr += array(
                        'value' => $opt_val,
                    );
                    $selectContent .= Helper_Xml::xmlTag('option', $opt_tit, $optionAttr);
                }
                $formContent .= Helper_Xml::xmlTag('select', $selectContent, array(
                    'type' => 'text',
                    'name' => $name,
                    'value' => $val,
                ));
                $xmlForm = Helper_Xml::xmlTag('form', $formContent, array(
                    'action' => $link,
                    'method' => 'get',
                ));
                $s .= $xmlForm;
            }
            $liAttr = array();
            if (isset($item['class'])) {
                $liAttr = array(
                    'class' => $item['class'],
                );
            }
            $li = Helper_Xml::xmlTag('li', $s, $liAttr);
            $lis .= $li;
        }
        $ul = Helper_Xml::xmlTag('ul', $lis);
        $menuDiv = Helper_Xml::xmlTag('div', $ul, array(
            'id' => 'menu-bar',
        ));
        return $menuDiv;
    }

    function htmlList($list, $id=NULL)
    {
        $s = '';
        $div_id = '';
        if (isset($id)) {
            $div_id = " id=\"$id\"";
        }
        $s .= "<div$div_id>\n";
        $s .= "<ul>\n";
        foreach ($list as $item) {
            $itemTitle = $item;
            $itemId = $item;
            if (is_array($item)) {
                $itemTitle = $item['title'];
                $itemId = $item['id'];
            }
            $s .= "<li data-item-id=\"$itemId\">$itemTitle</li>\n";
        }
        $s .= "</ul>\n";
        $s .= "</div>\n";
        return $s;
    }

    function htmlMultiCheckbox($cb)
    {
        $s = '';
        $s .= "<ul>\n";
        foreach ($cb['options'] as $opt) {
            $name = $cb['http_name'] . '_' . $opt['friendly_id'];
            $title = $opt['title'];
            $checked = '';
            if (in_array($opt['friendly_id'], $cb['value'])) {
                $checked = ' checked="checked"';
            }
            $s .= "<li><input type=\"checkbox\" name=\"$name\"$checked/>$title</li>\n";
        }
        $s .= "</ul>\n";
        return $s;
    }

    function htmlStatusMessage($msg)
    {
        $msg_text = $msg['text'];
        $msg_type = $msg['type'];
        $html = "<div id=\"status-message\" class=\"$msg_type\">$msg_text</div>\n";
        return $html;
    }

    function renderPagesTxt($pages)
    {
        $s = '';
        foreach ($pages as $page) {
            $link = $page['link'];
            if ($link) {
                $s .= "$link\n";
            }
            if (isset($page['pages']) && is_array($page['pages'])) {
                $s .= $this->renderPagesTxt($page['pages']);
            }
        }
        return $s;
    }

    function renderSitemapTxt($pages)
    {
        $s = '';
        $s .= $this->renderPagesTxt($pages);
        return $s;
    }

    function xmlDoctypeHtmlTransitional()
    {
        return Helper_Xml::xmlDoctypeHtmlTransitional();
    }

    function xmlDoctypeHtmlStrict()
    {
        return Helper_Xml::xmlDoctypeHtmlStrict();
    }
    
    function xmlHtml($content, $attrs=array())
    {
        return Helper_Xml::xmlHtml($content, $attrs);
    }

    function xmlHead($content)
    {
        return Helper_Xml::xmlHead($content);
    }

    function xmlTag($tag, $content, $attrs=array(), $is_empty=FALSE, $isInline=FALSE)
    {
        return Helper_Xml::xmlTag($tag, $content, $attrs, $is_empty, $isInline);
    }

    function xmlDiv($content, $attrs=array())
    {
        return Helper_Xml::xmlDiv($content, $attrs);
    }

    function xmlSpan($content, $attrs=array())
    {
        return Helper_Xml::xmlSpan($content, $attrs);
    }

    function xmlForm($content, $attrs=array())
    {
        return Helper_Xml::xmlForm($content, $attrs);
    }

    function xmlLabel($content, $attrs=array())
    {
        return Helper_Xml::xmlLabel($content, $attrs);
    }

    function xmlBody($content, $attrs=array())
    {
        return Helper_Xml::xmlBody($content, $attrs);
    }

    function xmlMeta($name, $content)
    {
        return Helper_Xml::xmlMeta($name, $content);
    }

    function xmlImg($src, $alt, $attrs=array())
    {
        return Helper_Xml::xmlImg($src, $alt, $attrs);
    }

    function xmlBr($attrs=array())
    {
        return Helper_Xml::xmlBr($attrs);
    }

    function xmlHr($attrs=array())
    {
        return Helper_Xml::xmlHr($attrs);
    }

    function xmlP($content, $attrs=array())
    {
        return Helper_Xml::xmlP($content, $attrs);
    }

    function xmlA($content, $url, $attrs=array())
    {
        return Helper_Xml::xmlA($content, $url, $attrs);
    }

    function xmlInputText($name, $value, $attrs=array())
    {
        return Helper_Xml::xmlInputText($name, $value, $attrs);
    }

    function xmlDeclaration()
    {
        return Helper_Xml::xmlDeclaration();
    }

    function xmlCsses($csses)
    {
        return Helper_Xml::xmlCsses($csses);
    }

    function xmlJses($jses)
    {
        return Helper_Xml::xmlJses($jses);
    }

    function xmlJs($code)
    {
        return Helper_Xml::xmlJs($code);
    }

    function xmlHeadCommon($options, $additional=NULL)
    {
        return Helper_Xml::xmlHeadCommon($options, $additional);
    }

    function xmlOption($title, $value=NULL)
    {
        return Helper_Xml::xmlOption($title, $value);
    }

    function xmlEmptyTag($tag, $attrs)
    {
        return Helper_Xml::xmlEmptyTag($tag, $attrs);
    }

    function xmlTel($tel)
    {
        return Helper_Xml::xmlTel($tel);
    }

    function xmlClear()
    {
        return Helper_Xml::xmlClear();
    }

    function xmlInputHidden($name, $val)
    {
        return Helper_Xml::xmlInputHidden($name, $val);
    }

    function xmlInputsHidden($pairs)
    {
        return Helper_Xml::xmlInputsHidden($pairs);
    }

    function htmlCommon($content, $extraHead='', $options=array())
    {
        $s = '';
        $s .= $this->xmlDeclaration();
        $s .= $this->xmlDoctypeHtmlTransitional();
        $head = $this->xmlHeadCommon($this->data, $extraHead);
        $bodyAttrs = array();
        if (isset($options['body_attrs'])) {
            $bodyAttrs = $options['body_attrs'];
        }
        $body = $this->xmlBody($content, $bodyAttrs);
        $attrs = array();
        if (isset($this->lang)) {
            $attrs['lang'] = $this->lang;
        }
        $s .= $this->xmlHtml($head . $body, $attrs);
        return $s;
    }

    function getBodyContent($middle, $options=NULL)
    {
        $optionstr = $options;
        if (is_array($options)) {
            $optionstr = 'Array';
        }
        return "body content $middle$optionstr\n";
    }

    function getJses()
    {
        return array(
        );
    }

    function _getDefaultOptions()
    {
        $options = array();
        if (isset($this->defaultOptions)) {
            $options = $this->defaultOptions;
        }
        return $options;
    }

    function htmlSite($middle, $options=NULL)
    {
        $this->data['csses'] = array(
            array(
                'url' => $this->resource('css/blueprint/screen.css'),
                'media' => 'screen, projection',
            ),
        );
        $this->data['ie_csses'] = array(
            array(
                'url' => $this->resource('css/blueprint/ie.css'),
                'media' => 'screen, projection',
            ),
        );
        if (!isset($options)) {
            $options = $this->_getDefaultOptions();
        }
        $this->data['meta_http'] = array(
            'Content-Type' => 'text/html; charset=UTF-8',
        );
        $jses = array(
            $this->resource('js/jquery.min.js'),
        );
        $jses = array_merge($jses, $this->getJses());
        $extraHead = '';
        if (isset($this->extraHead)) {
            $extraHead = $this->extraHead;
        }
        $extraHead .= Helper_Renderer::renderAjaxInfo($this);
        return $this->htmlCommon($this->getBodyContent($middle, $options) . $this->xmlJses($jses), $extraHead, $options);
    }

    function xmlCcSiteCredit($design='Web Design')
    {
        return Helper_Xml::xmlCcSiteCredit($design);
    }

    function setData($key, $value)
    {
        if (!isset($this->data)) {
            $this->data = array();
        }
        $this->data[$key] = $value;
    }

    function addData($arr)
    {
        foreach ($arr as $key => $value) {
            $this->setData($key, $value);
        }
    }

    function xmlFooterLinks($links, $attrs=array())
    {
        return Helper_Xml::xmlFooterLinks($links, $attrs);
    }

    function xmlSelect($name, $options, $value, $attrs)
    {
        return Helper_Xml::xmlSelect($name, $options, $value, $attrs);
    }

    function getOptionsFromTexts($texts)
    {
        $options = array();
        foreach ($texts as $text) {
            $options[] = array(
                'title' => $text,
                'value' => $text,
            );
        }
        return $options;
    }

    function getOptionsFromAssoc($assoc)
    {
        $options = array();
        foreach ($assoc as $key => $val) {
            $options[] = array(
                'title' => $key,
                'value' => $val,
            );
        }
        return $options;
    }

    function xmlEmail($mail)
    {
        return Helper_Xml::xmlEmail($mail);
    }
}
