<?php
 /**    Plugin File */
 /**
  * Function to generate login form
  *
  * This function generate login form
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the login form <form>...</form>
  */
function smarty_function_sirclo_render_login_form($params, $template) {
    $_btn_class = !empty($params['btn_class']) ? $params['btn_class'] : 'btn-flat';

    $_lang = !empty($params['lang']) ? $params['lang'] : "en";
    $_label_email = $_lang == "id" ? "E-mail" : "E-mail";
    $_label_password = $_lang == "id" ? "Password" : "Password";

    $params['fields'] = array(
        array('name' => 'username', 'type' => 'emailLogin', 'value' => '', 'label' => 'E-mail', 'attribute' => 'required'),
        array('name' => 'password', 'type' => 'passwordLogin', 'value' => '', 'label' => 'Password', 'attribute' => 'required'),
        array('name' => '', 'type' => 'submit', 'value' => 'LOGIN', 'label' => '', 'attribute' => 'class="'.$_btn_class.'"'));

    $_html = Helper_Renderer::renderForm($params);
    return $_html;
}
