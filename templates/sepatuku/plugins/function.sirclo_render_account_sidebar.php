<?php
 /**    Plugin File */
 /**
  * Function to generate navigation in account page
  *
  * This function render the navigation of account page
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the account info as unordered list <ul>...</ul>
  */
function smarty_function_sirclo_render_account_sidebar($params, $template)
{
    $html = "";
    $_hide_title = (!empty($params['hide_title'])) ? $params['hide_title'] : false;
    $_lang = (!empty($params['lang'])) ? $params['lang'] : 'en';
    $_context = (!empty($params['context']) ? $params['context'] : '');

    $_link_account = (!empty($params['link']['account']) ? $params['link']['account'] : '');
    $_link_edit_password = (!empty($params['link']['account_change_password']) ? $params['link']['account_change_password'] : '');
    $_link_order_history = (!empty($params['link']['account_orders']) ? $params['link']['account_orders'] : '');

    $_class_account = $_context == 'account' ? 'class="active"' : '';
    $_class_edit_password = $_context == 'account_edit_password' ? 'class="active"' : '';
    $_class_order_history = $_context == 'order_list' ? 'class="active last"' : 'class="last"';

    $_title = (!empty($params['title']) ? $params['title'] : ($_lang == 'id' ? 'Akun Saya' : 'My Account'));
    $_label_account = $_lang == "id" ? "Info Akun" : "Account Info";
    $_label_edit_password = $_lang == "id" ? "Ubah Password" : "Change Password";
    $_label_order_history = $_lang == "id" ? "Daftar Pesanan" : "Order History";

    if (!$_hide_title) {
        $html .= "<div class='sidebar-header'>$_title</div>";
    }

    $html .= "
    <ul>
        <li $_class_account><a href='$_link_account'>$_label_account</a></li>
        <li $_class_edit_password><a href='$_link_edit_password'>$_label_edit_password</a></li>
        <li $_class_order_history><a href='$_link_order_history'>$_label_order_history</a></li>
    </ul>";

    return $html;
}
