<?php
 /**    Plugin File */
 /**
  * Function to generate rss atom 
  *
  * This function render the rss atom of blog posts
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string RSS atom
  */
function smarty_function_sirclo_render_atom($params, $template)
{
    $html = '';
    if (isset($params['channel']) && isset($params['articles'])) {
        $atom = new Formatter_Atom();
        $html = $atom->formatArticles($params['channel'], $params['articles']);
    }
    return $html;
}
