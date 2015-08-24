<?php
// include ('Archive/Zip.php');
function getRelativePath($destination,$source) {
    return substr($destination, strlen($source)+1,strlen($destination));
}

function Zip($source, $destination)
{
    $test_dir="/images/test";

    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));
    
    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..','.DS_Store','.git')) )
                continue;
            if (strpos($file,$test_dir) > -1) {
                // echo ($file).' is_not_copied<br/>';
                continue;
            }
            $file = realpath($file);
            $fileToPut = getRelativePath($file,$source);

            if (is_file($file) === true && (!strpos($file,'.git')||strpos($file,'.git')==-1))
            {
                // echo ($file).' is_file <br/>';
                $zip->addFromString($fileToPut, file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}
$archive = $tempDir.'/'.$template.'.zip';
Zip($rootDir.'/'.$template, $archive);
header('Content-type: application/zip');
// It will be called test.zip
header('Content-Disposition: attachment; filename="'.$template.'.zip"');
header('Content-Length: '.filesize($archive));
readfile($archive);
unlink($archive);
?>