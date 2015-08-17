<?php
class Helper_Xml
{
    static function xmlDoctypeHtmlTransitional()
    {
        $s = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
        return $s;
    }

    static function xmlDoctypeHtmlStrict()
    {
        $s = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
        return $s;
    }
    
    static function xmlHtml($content, $attrs=array())
    {
        return self::xmlTag('html', $content, $attrs + array('xmlns' => 'http://www.w3.org/1999/xhtml'));
    }

    static function xmlHead($content)
    {
        return self::xmlTag('head', $content);
    }

    static function buildXmlAttrStr($attrs)
    {
        $s = '';
        if (is_array($attrs)) {
            foreach ($attrs as $key => $val) {
                if (is_null($val)) {
                    $s .= " $key";
                } else {
                    $val = htmlspecialchars($val);
                    $s .= " $key=\"$val\"";
                }
            }
        }
        return $s;
    }

    static function xmlTag($tag, $content, $attrs=array(), $is_empty=FALSE, $isInline=FALSE)
    {
        $s = '';
        $attr_str = self::buildXmlAttrStr($attrs);
        if ($is_empty) {
            $s .= "<$tag$attr_str />";
        } else {
            $s .= "<$tag$attr_str>";
            $one_line = strpos($content, "\n") === FALSE;
            if (!$one_line) {
                $s .= "\n";
            }
            $s .= $content;
            $ctag_suf = '';
            if (!$one_line && is_array($attrs) && isset($attrs['id'])) {
                $id = $attrs['id'];
                $ctag_suf = " <!-- $id -->";
            }
            $s .= "</$tag>$ctag_suf";
        }
        if (!$isInline) {
            $s .= "\n";
        }
        return $s;
    }

    static function xmlDiv($content, $attrs=array())
    {
        return self::xmlTag('div', $content, $attrs);
    }

    static function xmlSpan($content, $attrs=array())
    {
        return self::xmlTag('span', $content, $attrs);
    }

    static function xmlForm($content, $attrs=array())
    {
        return self::xmlTag('form', self::xmlDiv($content), $attrs);
    }

    static function xmlLabel($content, $attrs=array())
    {
        return self::xmlTag('label', $content, $attrs);
    }

    static function xmlBody($content, $attrs=array())
    {
        return self::xmlTag('body', $content, $attrs);
    }

    static function xmlMeta($name, $content)
    {
        return self::xmlTag('meta', '', array('name' => $name, 'content' => $content), TRUE);
    }

    static function xmlImg($src, $alt, $attrs=array())
    {
        return self::xmlTag('img', '', $attrs + array('src' => $src, 'alt' => $alt), TRUE);
    }

    static function xmlBr($attrs=array())
    {
        return self::xmlTag('br', '', $attrs, TRUE);
    }

    static function xmlHr($attrs=array())
    {
        return self::xmlTag('hr', '', $attrs, TRUE);
    }

    static function xmlP($content, $attrs=array())
    {
        return self::xmlTag('p', $content, $attrs);
    }

    static function xmlA($content, $url, $attrs=array())
    {
        if ($url) {
            $attrs += array(
                'href' => $url,
            );
        }
        return self::xmlTag('a', $content, $attrs);
    }

    static function xmlInputText($name, $value, $attrs=array())
    {
        $input = self::xmlTag('input', '', $attrs+array('type' => 'text', 'name' => $name, 'title' => $value), TRUE);
        return $input;
    }

    static function xmlDeclaration()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    }

    static function xmlCsses($csses)
    {
        $s = '';
        foreach ($csses as $css) {
            $attrs = array(
                'rel' => 'stylesheet',
                'type' => 'text/css',
                'href' => $css['url'],
            );
            if (isset($css['media'])) {
                $attrs['media'] = $css['media'];
            }
            $s .= self::xmlTag('link', '', $attrs, TRUE);
        }
        return $s;
    }

    static function xmlJses($jses)
    {
        $s = '';
        foreach ($jses as $js) {
            if (is_array($js)) {
                $attrs = $js + array(
                    'type' => 'text/javascript',
                );
            } else {
                $attrs = array(
                    'type' => 'text/javascript',
                    'src' => $js,
                );
            }
            $s .= self::xmlTag('script', '', $attrs);
        }
        return $s;
    }

    static function xmlJs($code)
    {
        $s = self::xmlTag('script', $code, array(
            'type' => 'text/javascript',
        ));
        return $s;
    }

    static function xmlHeadCommon($options, $additional=NULL)
    {
        $s = '';
        $s .= self::xmlTag('title', isset($options['title']) ? htmlspecialchars($options['title']) : '');
        if (isset($options['meta_http'])) {
            foreach ($options['meta_http'] as $name => $content) {
                $s .= self::xmlEmptyTag('meta', array(
                    'http-equiv' => $name,
                    'content' => $content,
                ));
            }
        }
        if (isset($options['meta'])) {
            foreach ($options['meta'] as $name => $content) {
                if (is_array($content)) {
                    $content = implode(', ', $content);
                }
                if ($content) {
                    $s .= self::xmlMeta($name, $content);
                }
            }
        }
        if (isset($options['image_src'])) {
            $s .= self::xmlEmptyTag('link', array(
                'rel' => 'image_src',
                'href' => $options['image_src'],
            ));
        }
        if (isset($options['video_src'])) {
            $s .= self::xmlEmptyTag('link', array(
                'rel' => 'video_src',
                'href' => $options['video_src'],
            ));
        }
        if (isset($options['favicon'])) {
            $s .= self::xmlEmptyTag('link', array(
                'rel' => 'shortcut icon',
                'href' => $options['favicon'],
            ));
        }
        if (isset($options['csses'])) {
            $s .= self::xmlCsses($options['csses']);
        }
        if (isset($options['ie_csses'])) {
            $s .= "<!--[if lt IE 8]>\n";
            $s .= self::xmlCsses($options['ie_csses']);
            $s .= "<![endif]-->\n";
        }
        if (isset($additional)) {
            $s .= $additional;
        }
        return self::xmlHead($s);
    }

    static function xmlOption($title, $value=NULL)
    {
        if (!isset($value)) {
            $value = $title;
        }
        return self::xmlTag('option', htmlspecialchars($title), array('value' => $value));
    }

    static function xmlEmptyTag($tag, $attrs)
    {
        return self::xmlTag($tag, '', $attrs, TRUE);
    }

    static function xmlTel($tel)
    {
        return self::xmlA($tel, ('tel:' . rawurlencode($tel)));
    }

    static function xmlClear()
    {
        return self::xmlDiv('', array('class' => 'clearer'));
    }

    static function xmlInputHidden($name, $val)
    {
        return self::xmlEmptyTag('input', array(
            'type' => 'hidden',
            'name' => $name,
            'value' => $val,
        ));
    }

    static function xmlInputsHidden($pairs)
    {
        $s = '';
        foreach ($pairs as $key => $val) {
            $s .= self::xmlInputHidden($key, $val);
        }
        return $s;
    }

    static function xmlCcSiteCredit($design='Web Design')
    {
        $cc_link = 'http://www.closelycoded.com';
        return self::xmlA($design, $cc_link, array('target' => '_blank')) . 'by ' . self::xmlA('Closely Coded', $cc_link, array('target' => '_blank'));
    }

    static function xmlFooterLinks($links, $attrs=array())
    {
        $linkStr = '';
        $first = TRUE;
        foreach ($links as $key => $val) {
            if (!$first) {
                $linkStr .= "|\n";
            }
            $first = FALSE;
            $linkStr .= self::xmlA($key, $val);
        }
        return self::xmlDiv($linkStr, $attrs);
    }

    static function xmlSelect($name, $options, $value, $attrs=array())
    {
        $optionsStr = '';
        foreach ($options as $opt) {
            if (is_string($opt)) {
                $opt = array(
                    'title' => $opt,
                    'value' => $opt,
                );
            }
            $optAttrs = array();
            if (isset($opt['attrs'])) {
                $optAttrs = $opt['attrs'];
            }
            if ($value == $opt['value']) {
                $optAttrs['selected'] = 'selected';
            }
            $optionsStr .= self::xmlTag('option', $opt['title'], array(
                'value' => $opt['value'],
            ) + $optAttrs);
        }
        $s = self::xmlTag('select', $optionsStr, array('name' => $name) + $attrs);
        return $s;
    }

    static function xmlEmail($mail)
    {
        return self::xmlA($mail, "mailto:$mail");
    }
}
