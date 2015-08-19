<?php
 /**    Plugin File */
 /**
  * Function to generate rss
  *
  * This function generate rss
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the rss
  */
function smarty_function_sirclo_render_rss($params, $template)
{
    $html = '';
    if (isset($params['channel']) && isset($params['articles'])) {
        $atom = new Formatter_Rss();
        $html = $atom->formatArticles($params['channel'], $params['articles']);
    }
    return $html;
}
