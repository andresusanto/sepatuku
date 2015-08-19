<?php
 /**    Plugin File */
 /**
  * Function to get text
  *
  * This function get text based on the parameter and return the result
  * 
  *
  * @param $params variable that contains the thing that want to be generated the text
  * @param $template to associate text
  *
  * @return string the text
  */
function smarty_function_sirclo_get_text($params, $template)
{
    $view = $template->smarty->getTemplateVars('__view');
    $text = '';
    if (isset($params['text'])) {
        $text = $params['text'];
    }
    $messages = array();
    if (isset($view['messages'])) {
        $messages = $view['messages'];
    }
    return $text;
    // return Helper_Krco::getText($messages, $text);
}
