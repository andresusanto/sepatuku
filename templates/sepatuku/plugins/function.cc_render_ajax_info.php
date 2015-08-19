<?php
function smarty_function_cc_render_ajax_info($params, $template)
{
    $view = $template->smarty->getTemplateVars('__view');
    $s = '';
    if (isset($view['isFakeView']) && $view['isFakeView']) {
        $s = <<<EOD
<script type="text/javascript">
window.isFakeView = true;
</script>
EOD;
    }
    return $s;
}
