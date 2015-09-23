<?php
 /**    Plugin File */
 /**
  * Function to render Google analytics script
  *
  * This function is used to generate Google Analytic script
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string Google Analytic script <script>...</script>
  */
function smarty_function_sirclo_render_ga_ecommerce_script($params, $template)
{
    $html = '';
    if (isset($params['order'])) {
        $html = Helper_Analytics::renderEcommerceScript($params['order']);
    }
    return $html;
}
