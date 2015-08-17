<?php
class Helper_Fake_Db
{
    static function createPDO($host, $dbname, $user, $passwd)
    {
        $pdo = array(
            'host' => $host,
            'dbname' => $dbname,
            'user' => $user,
            'passwd' => $passwd,
        );
        return $pdo;
    }
}
