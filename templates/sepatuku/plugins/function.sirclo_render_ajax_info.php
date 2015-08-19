<?php
 /**    Plugin File */
 /**
  * Function to generate ajax info
  *
  * This function render the ajax info of the page. It reads whether there is a flag with name isFakeView. 
  *	Fake view sometimes needed to do test.
  * 
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string script code to indicate a fake view
  */
function smarty_function_sirclo_render_ajax_info($params, $template)
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
