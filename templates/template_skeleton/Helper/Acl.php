<?php
class Helper_Acl
{
    static function isAclOk($acl, $mfid, $op)
    {
        if (isset($acl[$mfid]) && in_array($op, $acl[$mfid])) return TRUE;
        //original fallback granted access even if not specified
        if (!isset($acl[$mfid])) {
            //second fallback to use original acl (content and account) if more details acl is not specified
            if($mfid!=='content' && $mfid!=='account') {
                return Helper_Acl::isAclOk($acl, 'content', $op);
            }else {
                return TRUE;
            }
        }
        if (!$acl) return TRUE;
        return FALSE;
    }

    static function checkAcl($requireds, $acl)
    {
        $ok = TRUE;
        foreach ($requireds as $required) {
            $exploded = explode(';', $required);
            if (isset($exploded[0]) && isset($exploded[1])) {
                $mfid = $exploded[0];
                $op = $exploded[1];
                if (!Helper_Acl::isAclOk($acl, $mfid, $op)) {
                    $ok = FALSE;
                }
            }
        }
        return $ok;
    }
}
