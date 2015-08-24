<?php
 /**    Plugin File */
 /**
  * Function to generate blog archives
  *
  * This function render blog archives
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the blog archives as unordered list <ul>...</ul>
  */

function smarty_function_sirclo_render_blog_archives($params, $template)
{
    $html = "";
    if (!empty($params['archives'])) {
        $html .= "<ul id='blog-archives'>";
        foreach ($params['archives'] as $ar) {
            $html .= "<li>";
            $html .= "<a href='" . $ar['link'] . "'>" . $ar['name'] . " (" . $ar['n_articles'] . ")" . "</a>";
            $html .= "</li>";
        }
        $html .= "</ul>";
    }
    return $html;
}
