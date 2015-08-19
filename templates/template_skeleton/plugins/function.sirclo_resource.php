<?php
 /**    Plugin File */
 /**
  * Function to generate the resource string that used in any template
  *
  * This function generate the resource string that used in any template
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string source to the file
  */
function smarty_function_sirclo_resource($params, $template)
{
    $s = '';
    if (isset($params['file'])) {
        $view = $template->smarty->getTemplateVars('__view');
      	$hashValue = '';
      	if (isset($view['hashValue'])) {
      		$hashValue = $view['hashValue'];
      	}
        $skipHash = false;
        if (isset($params['skipHash'])) {
        	$skipHash = $params['skipHash'];
        }
        return $view['resource_url'] . "/" . $params['file']. ((!$skipHash) && isset($hashValue) && strlen($hashValue . "")>0?'?hash='.$hashValue:"");
    }
    return $s;
}
