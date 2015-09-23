<?php
class Helper_String
{
    static function htmlSummary($text)
    {
        $abstract = array();
        preg_match('/^([^.!?\s]*[\.!?\s]+){0,30}/', strip_tags($text), $abstract);
        $sum = $abstract[0];
        return $sum;
    }

    static function dollarFormat($value, $dec=0, $symbol='$')
    {
        $pref = '';
        if ($symbol) {
            $pref = "$symbol ";
        }
        return $pref . number_format((double)$value, $dec);
    }

    static function toCamelCase($underscored)
    {
        $spaced = str_replace('_', ' ', $underscored);
        $spaced = str_replace('.', '', $spaced);
        $ucworded = ucwords($spaced);
        $temp = str_replace(' ', '', $ucworded);

        $spaced = str_replace('/', ' ', $temp);
        $ucworded = ucwords($spaced);
        $temp = str_replace(' ', '_', $ucworded);
        return $temp;
    }

    static function toUnderscored($cameled)
    {
        return strtolower(preg_replace('/(?<=[a-z0-9])([A-Z])/', '_$1', $cameled));
    }

    static function getLanguageFromCode($code)
    {
        $langs = array(
            'en' => 'English',
            'id' => 'Bahasa Indonesia',
            'zh' => '中文',
            'cn' => '中文',
            'ja' => '日本語',
        );
        if (!isset($langs[$code])) {
            return NULL;
        }
        return $langs[$code];
    }

    static function generateRandom($salt, $ts, $len=8)
    {
        return substr(md5($salt . $ts), 0, $len);
    }

    static function sumChars($s)
    {
        return array_sum(array_map(function ($c) {return ord($c);}, str_split($s)));
    }

    static function extractYoutubeId($link)
    {
        $components = parse_url($link);
        $parsed = array();
        $id = NULL;
        if (isset($components['query'])) {
            parse_str($components['query'], $parsed);
            if (isset($parsed['v'])) {
                $id = $parsed['v'];
            }
        }
        return $id;
    }

    static function getSymbolOfCurrency($cur)
    {
        $syms = array(
            'SGD' => '$',
            'USD' => '$',
            'AUD' => '$',
            'MYR' => 'RM',
            'IDR' => 'Rp',
        );
        if (isset($syms[$cur])) {
            return $syms[$cur];
        }
        return '';
    }

    static function getCountryNameOfCode($code)
    {
        $countries = array_flip(Helper_Paypal::getPaypalCountries());
        $name = $code;
        if (isset($countries[$code])) {
            $name = $countries[$code];
        }
        return $name;
    }

    static function addExtension($str, $ext)
    {
        $exploded = explode('?', $str);
        $path = $exploded[0];
        $qstr = '';
        if (isset($exploded[1])) {
            $qstr = '?' . $exploded[1];
        }
        if (substr($path, strlen($path)-1, 1) != '/') {
            $path .= $ext;
        }
        return $path . $qstr;
    }

    static function removeSuffix($str, $suff)
    {
        $suffLength = strlen($suff);
        $strLength = strlen($str);
        $removed = $str;
        if (substr($str, $strLength-$suffLength, $suffLength) === $suff) {
            $removed = substr($str, 0, $strLength-$suffLength);
        }
        return $removed;
    }

    static function commaStrToArr($val)
    {
        $val = str_replace('\,', '\comma', $val);
        $exploded = explode(',', $val);
        $ret = array_filter(array_map(function ($x) {return trim(str_replace('\comma', ',', $x)); }, $exploded), function ($x) {return (bool) $x;});
        return $ret;
    }

    static function formatCommaArray($arr)
    {
        if (!is_array($arr)) {
            return '';
        }
        $strArr = $arr;
        foreach ($strArr as &$val) {
            if (is_array($val)) {
                $val = '[' . self::formatCommaArray($val) . ']';
            } else {
                $val = str_replace(',', '\,', $val);
            }
        }
        $s = implode(', ', $strArr);
        return $s;
    }

    static function explodeEOL($str)
    {
        return array_map(function ($x) { return trim($x); }, explode("\n", $str));
    }

    static function implodeIfNotEmpty($sep, $arr)
    {
        return implode($sep, array_filter($arr, function ($x) {return $x;}));
    }

    static function htmlToList($str)
    {
        $list = array_filter(array_map(function ($x) {return trim($x);}, explode(',', strip_tags($str))), function ($x) {return (bool)$x;});
        return $list;
    }

    static function getImgSrc($str)
    {
        preg_match('/img.*?src="(.*?)"/', $str, $matches);
        $src = NULL;
        if (isset($matches[1])) {
            $src = $matches[1];
        }
        return $src;
    }

    static function htmlToDictionary($html)
    {
        $dict = array();
        $lines = explode("\n", strip_tags($html));
        foreach ($lines as $line) {
            $exploded = explode(':', $line);
            if (isset($exploded[1])) {
                $key = trim($exploded[0]);
                $val = trim($exploded[1]);
                if ($key) {
                    $dict[$key] = $val;
                }
            }
        }
        return $dict;
    }

    static function getFriendlyItemTitle($title)
    {
        $friendly = $title;
        /*
        preg_match('/(.*?) \((.*)\)/', $title, $matches);
        $name = $matches[1];
        $options = array();
        if (isset($matches[2])) {
            $options = explode(', ', $matches[2]);
        }
        */
        $options = array();
        $last = Helper_String::getLastParenth($title);
        $name = $last['lead'];
        if ($last['last']) {
            $options = explode(', ', $last['last']);
        }
        if (count($options) > 2) {
            $optionStr = implode(', <br />', $options);
            $friendly = "$name(<br />" . $optionStr . "<br />)";
        }
        return $friendly;
    }

    static function getLastParenth($str)
    {
        $c = 0;
        for ($i = strlen($str)-1; $i >= 0; $i--) {
            if ($str[$i] == ')') {
                if (!isset($end)) {
                    $end = $i;
                }
                $c += 1;
            }
            if ($str[$i] == '(') {
                $c -= 1;
                if ($c == 0) {
                    $start = $i;
                    break;
                }
            }
        }
        $last = NULL;
        $lead = NULL;
        if (isset($start)) {
            $last = substr($str, $start+1, $end-$start-1);
            $lead = substr($str, 0, $start);
        }
        return array(
            'lead' => $lead,
            'last' => $last,
        );
    }

    static function sanitizeNumber($s)
    {
        if (is_numeric($s)) {
            return $s;
        }
        preg_match("/([0-9]+[\.,]?)+/", $s, $matches);
        if (isset($matches[0])) {
            return $matches[0];
        }
        return '0';
    }

    static function endsWith($str, $test)
    {
        return substr_compare($str, $test, strlen($str)-strlen($test), strlen($test)) === 0;
    }

    static function truncateString($str, $max)
    {
        if (strlen($str) > $max) {
            return substr($str, 0, $max) . ' ...';
        }
        return $str;
    }
}
