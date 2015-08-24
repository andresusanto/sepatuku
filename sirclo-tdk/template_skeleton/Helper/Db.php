<?php
class Helper_Db
{
    static function createPDO($host, $dbname, $user, $pswd)
    {
        if (!$host) {
            return NULL;
        }
        if ($dbname == 'fake') {
            $pdo = 'fake';
            return $pdo;
        }
        //try {
            $pdo = new PDO("mysql:dbname=$dbname;host=$host", $user, $pswd, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        //} catch (Exception $e) {
        //    $pdo = NULL;
        //}
        return $pdo;
    }
}
