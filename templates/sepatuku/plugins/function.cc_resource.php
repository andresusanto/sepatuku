<?php
function smarty_function_cc_resource($params, $template)
{
    $s = '';
    if (isset($params['file'])) {
        $view = $template->smarty->getTemplateVars('__view');
        return $view['resource_url'] . "/" . $params['file'];
    }
    return $s;
}
