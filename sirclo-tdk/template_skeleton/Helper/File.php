<?php
class Helper_File
{
    static function formatFileSize($size)
    {
        $s = '';
        $suff = array(
            'bytes',
            'KB',
            'MB',
            'GB',
        );
        for ($i = count($suff)-1; $i >= 0; $i-- ) {
            $a = $size / pow(2, $i*10);
            if ($a >= 1) {
                $s = number_format($a) . ' ' . $suff[$i];
                break;
            }
        }
        return $s;
    }

    static function addSuffix($fullname, $suf)
    {
        $pi = pathinfo($fullname);
        $extension = '';
        if (isset($pi['extension'])) {
            $extension = '.' . $pi['extension'];
        }
        $dir_pref = '';
        if (isset($pi['dirname']) && ($pi['dirname'] != '.')) {
            $dir_pref = $pi['dirname'] . '/';
        }
        return $dir_pref . $pi['filename'] . $suf . $extension;
    }

    static function removeSuffix($fullname, $suf)
    {
        $pi = pathinfo($fullname);
        $extension = '';
        if (isset($pi['extension'])) {
            $extension = '.' . $pi['extension'];
        }
        $dir_pref = '';
        if (isset($pi['dirname']) && ($pi['dirname'] != '.')) {
            $dir_pref = $pi['dirname'] . '/';
        }
        return $dir_pref . preg_replace("/$suf$/", '', $pi['filename']) . $extension;
    }

    static function getFileName($url)
    {
        $pi = pathinfo($url);
        $extension = '';
        if (isset($pi['extension'])) {
            $extension = '.' . $pi['extension'];
        }
        return $pi['filename'] . $extension;
    }

    static function getFileNameWithoutExtension($url)
    {
        $pi = pathinfo($url);
        return $pi['filename'];
    }

    static function getFileExtension($url)
    {
        $pi = pathinfo($url);
        return $pi['extension'];
    }

    static function getFileUploadPrefix($timestamp)
    {
        $prefix = date('ymdHis', $timestamp) . '_';
        return $prefix;
    }

    static function ensureDirExists($filename)
    {
        if (!file_exists($filename)) {
            try {
                mkdir($filename);
            } catch (Exception $e) {
                echo '[ERROR]'.PHP_EOL;
                echo "Failed to create directory: $filename".PHP_EOL;
                echo "Reason: ".$e->getMessage().PHP_EOL;
                echo PHP_EOL;
                throw new $e;
            }
        }
    }

    static function scanDirIfExists($dir)
    {
        $allfiles = array();
        if (file_exists($dir)) {
            $tempFiles = scandir($dir);
            foreach ($tempFiles as $file) {
                if (($file != '..') && ($file != '.')) {
                    $allfiles[] = $file;
                }
            }
        }
        return $allfiles;
    }

    static function isImage($name)
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $exts = array(
            'jpg',
            'jpeg',
            'png',
            'gif',
            'ico',
        );
        if (in_array($extension, $exts)) {
            return TRUE;
        }
        return FALSE;
    }

    static function calculateViewVersion()
    {
        $values = array_merge(self::getSha1OfDir('resources/css'), self::getSha1OfDir('resources/js'), self::getSha1OfDir('pages'), self::getSha1OfDir('i18n'));
        sort($values);
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($values));
        $result = array();
        foreach($it as $v) {
            $result[] = $v;
        }
        $version = sha1(implode('', $result));
        return substr($version, 0, 8);
    }

    static function getSha1OfDir($path)
    {
        $result = array();
        $files = array();
        if (is_dir($path)) {
            $files = scandir($path);            
        }
        foreach ($files as $key => $fileName) {
            $fullPathFileName = $path . DIRECTORY_SEPARATOR . $fileName;
            if (!is_dir($fullPathFileName))  {
                $pathinfo = pathinfo($fullPathFileName);
                if ($pathinfo['extension'] == 'css' || $pathinfo['extension'] == 'js' || $pathinfo['extension'] == 'ini' || $pathinfo['extension'] == 'tpl' || $pathinfo['extension'] == 'php') {
                    $hasil = sha1(str_replace("\r", "", file_get_contents($fullPathFileName)));
                    $result[] = sha1(str_replace("\r", "", file_get_contents($fullPathFileName)));
                }
            } else if($fileName != "." && $fileName != "..") {
                $result[] = self::getSha1OfDir($fullPathFileName);
            }
        }
        return $result;
    }

}
