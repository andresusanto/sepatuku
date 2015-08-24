<?php
 /**    Plugin File */
 /**
  * Function to generate breadcrumb of a page
  *
  * This function generate breadcrumb that leads to the hierarchy of the page
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the breadcrumb <div>...</div>
  */
function smarty_function_sirclo_render_breadcrumb($params, $template)
{
    $vw = '';
    $separator = !empty($params['separator']) ? $params['separator'] : '/';

    if (!empty($params['breadcrumb'])) {
        $breadcrumb = $params['breadcrumb'];
        $vw .= '<div class="breadcrumb">';
        $i = 0;
        foreach ($breadcrumb as $bread) {
            if ($i > 0) {
                $raquo = '<span class="bc_arrow">' . $separator . '</span>';
            } else {
                $raquo = "";
            }
            $vw .= $raquo . ' <a href="' . $bread['link'] . '">' . $bread['title'] . '</a> ';
            $i++;
        }
        $vw .= '</div>';
    }
    return $vw;
}
