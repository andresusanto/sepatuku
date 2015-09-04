<?php
 /**    Plugin File */
 /**
  * Function to generate list of recent blog posts
  *
  * This function render list of recent blog posts
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the recent blog posts as unordered list <ul>...</ul>
  */
function smarty_function_sirclo_render_blog_recent_posts($params, $template)
{
    $html = "";
    if (!empty($params['recent_posts'])) {
        $html .= "<ul id='blog-recent-posts'>";
        foreach ($params['recent_posts'] as $rp) {
            $html .= "<li>";
            if (!empty($rp['image'])) {
                $html .= "<img src='{sirclo_resource file=\"". $rp['image'] . "\"}'></img>";
            }
            $html .= "<a href='" . $rp['link'] . "'>" . $rp['title'] . "</a>";
            $html .= "</li>";
        }
        $html .= "</ul>";
    }

    return $html;
}
