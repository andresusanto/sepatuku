<?php
class Helper_Image
{
    static function copyImage($controller, $ori, $name, $sizes, $dir, $watermark, $default_resize_mode, $oriName=NULL)
    {
        if ($oriName) {
            $tmpName = $controller->tmp_dir . '/' . Helper_File::getFileUploadPrefix($controller->now) . $oriName;
            copy($ori, $tmpName);
            $ori = $tmpName;
        }
        foreach ($sizes as $size) {
            $resize_mode = $default_resize_mode;
            if (!empty($size['resize_mode'])) {
                $resize_mode = $size['resize_mode'];
            }
            $convMatrix = self::getConvolutionMatrix($controller);
            $controller->images->copyImage($ori, Helper_File::addSuffix(Helper_File::getFileName($name), $size['suffix']), $size['width'], $size['height'], $dir, $watermark, $resize_mode, $convMatrix);
        }
    }

    public static $convSharpen = array(
        array( 0.0, -1.0,  0.0),
        array(-1.0, 12.0, -1.0),
        array( 0.0, -1.0,  0.0),
    );

    static function getConvolutionMatrix($controller)
    {
        $matrix = NULL;
        if (method_exists($controller, 'getDepConfigValue') && $controller->getDepConfigValue('jpg_auto_sharpen')) {
            $matrix = self::$convSharpen;
        }
        return $matrix;
    }

    static function deleteOldImage($controller, $name, $sizes, $dir)
    {
        if (isset($controller->files)) {
            $root = $controller->files->file_root;
            foreach ($sizes as $size) {
                $controller->files->deleteFile("$root/$dir/" . Helper_File::addSuffix(Helper_File::getFileName($name), $size['suffix']));
            }
        }
    }

    static function expandImageSizes($sizes)
    {
        $expanded = array_map(function ($size) {
            $newSize = array(
                'suffix' => $size[0],
                'width' => $size[1],
                'height' => $size[2],
            );
            if (!empty($size[3])) {
                $newSize['resize_mode'] = $size[3];
            }
            return $newSize;
        }, $sizes);
        return $expanded;
    }
}
