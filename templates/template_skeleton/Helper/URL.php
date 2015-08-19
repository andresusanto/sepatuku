<?php
class Helper_URL
{
    static function constructFid($text)
    {
        if (!isset($text)) {
            return NULL;
        }
        $text = strtolower($text);
        $text = preg_replace('/[\'"]/', '', $text);
        $text = preg_replace('/[^a-z 0-9]/', ' ', $text);
        $text = trim($text);
        $text = preg_replace('/ +/', '-', $text);
        return $text;
    }

    static function replaceSubdomain($url, $sub)
    {
        if ($sub) {
            $url = preg_replace('/\:\/\/([a-z]+\.)?/', '://' . $sub . '.', $url);
        }
        return $url;
    }

    static function addGetToUrl($url, $get)
    {
        $connector = '';
        if ($get) {
            $connector = '?';
            if (is_numeric(strpos($url, '?'))) {
                $connector = '&';
            }
        }
        return $url . $connector . $get;
    }

    static function getSegmentsFromUri($uri, $suff=NULL, $start=NULL)
    {
        $parsedUri = parse_url($uri);
        $path = '';
        if (isset($parsedUri['path'])) {
            $path = $parsedUri['path'];
            if ($suff) {
                $path = Helper_String::removeSuffix($parsedUri['path'], $suff);
            }
        }
        $segments = array_map(function ($x) {
            return rawurldecode($x);
        }, explode('/', $path));
        if ($start) {
            $segments = array_slice($segments, $start);
        }
        return $segments;
    }

    static function getUrlSchemeFromServerVar($server)
    {
        $scheme = 'http';
        if (isset($server['HTTPS']) && $server['HTTPS']) {
            $scheme = 'https';
        } else if (isset($server['HTTP_X_FORWARDED_PROTO']) && $server['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $scheme = 'https';
        }
        return $scheme;
    }

    static function getFullUrlFromServerVar($server)
    {
        return self::getUrlSchemeFromServerVar($server) . "://" . Helper_Structure::getArrayValue($server, 'HTTP_HOST') . Helper_Structure::getArrayValue($server, 'REQUEST_URI');
    }

    static function formatHttps($link)
    {
        $link = str_replace('http://', 'https://', $link);
        return $link;
    }

    static function formatHttp($link)
    {
        $link = str_replace('https://', 'http://', $link);
        return $link;
    }

    static function _isDomainRemovable($part)
    {
        $isRemovable = ($part == 'www') || (strlen($part) == 2);
        return $isRemovable;
    }

    static function getBaseHostFromFqdn($fqdn, $isPrefix=FALSE)
    {
        $arr = explode('.', $fqdn);
        $prefix = '';
        if (self::_isDomainRemovable($arr[0])) {
            $prefix = $arr[0];
            unset($arr[0]);
        }
        if ($isPrefix) {
            return $prefix;
        }
        $base = implode('.', $arr);
        return $base;
    }

    static function getBaseHost($host)
    {
        $parsed = parse_url($host);
        $base = '';
        if (isset($parsed['host'])) {
            $base = self::getBaseHostFromFqdn($parsed['host']);
        }
        return $base;
    }

    static function isInternalUrl($url, $controller)
    {
        $isInternal = (strpos($url, $controller->base_url) === 0) || (isset($controller->_fake_base_url) && strpos($url, $controller->_fake_base_url) === 0);
        return $isInternal;
    }

    static function isMobileHost($host)
    {
        return (strpos($host, '.m.') !== FALSE);
    }

    static function isURL($s)
    {
        //if (strpos($s, 'http://') === 0 || strpos($s, 'https://') === 0) {
        if (preg_match('/^https?:\/\//', $s)) {
            return TRUE;
        }
        return FALSE;
    }

    static function filterLink($base_url, $val)
    {
        if ($val == $base_url) {
            return '/';
        }
        $link = $val;
        if (strpos($val, $base_url) === 0) {
            $link = substr($val, strlen($base_url));
        }
        return $link;
    }
}
