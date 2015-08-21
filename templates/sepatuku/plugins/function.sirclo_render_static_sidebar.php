<?php
 /**    Plugin File */
 /**
  * Function to generate static sidebar
  *
  * This function generate static sidebar
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the static sidebar <ul>...</ul>
  */
function smarty_function_sirclo_render_static_sidebar($params, $template)
{
    $html = "";
    $_nav = !empty($params['nav']) ? $params['nav'] : array();
    $_nav_title = !empty($params['nav_title']) ? $params['nav_title'] : '';

    $_hide_title = !empty($params['hide_title']) ? true : false;
    $_hide_subnav = !empty($params['hide_subnav']) ? true : false;

    $_html_list = "";
    foreach ($_nav as $_n) {
        $_is_active = !empty($_n['is_active']) ? 'class="active"' : '';

        if (!empty($_n['sub_nav']) && !$_hide_subnav) {
            $_html_list .= "<li ".$_is_active."><a href='".$_n['link']."'>".$_n['title']."</a><ul>";
            foreach ($_n['sub_nav'] as $_sn) {
                $_sn_is_active = !empty($_sn['is_active']) ? 'class="active"' : '';
                $_html_list .= "<li ".$_sn_is_active."><big><a href='".$_sn['link']."'>".$_sn['title']."</a></big></li>";
            }
            $_html_list .= "</ul></li>";
        }
        $_html_list .= "<li ".$_is_active."><a href='".$_n['link']."'>".$_n['title']."</a></li>";
    }

    if (!$_hide_title) {
        $html .= "<div class='sidebar-header'>$_nav_title</div>";
    }

    $html .= "
    <ul>
        $_html_list
    </ul>";

    return $html;
}
