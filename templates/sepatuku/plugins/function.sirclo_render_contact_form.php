<?php
 /**    Plugin File */
 /**
  * Function to generate contact form
  *
  * This function generate contact form
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the contact form <form>...</form>
  */
 function smarty_function_sirclo_render_contact_form($params, $template) {
    $_html = Helper_Renderer::sircloRenderContactForm($params);
    return $_html;
}
