<?php
 /**    Plugin File */
 /**
  * Function to generate pagination
  *
  * This function generate pagination
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the pagination
  */
function smarty_function_sirclo_render_pagination($params, $template) {
    $vw = '';
    $first = '';
    $last = '';
    $prev = '';
    $next = '';
    $view_all = '';
    $paging = array();

    $options = array();
    if (!empty($params['first'])) $first = $params['first'];
    if (!empty($params['last'])) $last = $params['last'];
    if (!empty($params['prev'])) $options['text_prev'] = $params['prev'];
    if (!empty($params['next'])) $options['text_next'] = $params['next'];
    if (!empty($params['paging'])) $paging = $params['paging'];
    if (!empty($params['view_all'])) $view_all = $params['view_all'];

    $vw = Helper_Renderer::renderPaging($paging, 10, $options);

    return $vw;
}
