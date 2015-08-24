<?php
 /**    Plugin File */
 /**
  * Function to generate yahoo messenger online status
  *
  * This function generate yahoo messenger online status
  * 
  *
  * @param $params contains option of the generated result
  * @param $template to associate generated result
  *
  * @return string HTML code of the yahoo messenger online status
  */
function smarty_function_sirclo_render_ym_image($params, $template)
{
    $html = "";
    if (!empty($params['ym_id']) && !empty($params['ym_img_online']) && !empty($params['ym_img_offline'])) {
        $_ym_id = $params['ym_id'];
        $_ym_img_online = $params['ym_img_online'];
        $_ym_img_offline = $params['ym_img_offline'];

        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, "http://opi.yahoo.com/online?u=$_ym_id&m=s");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        $strPage = curl_exec($cURL);
        curl_close($cURL);

        $_offline_message = "$_ym_id is NOT ONLINE";

        if ($strPage == $_offline_message) {
            $html .= "<a href='ymsgr:SendIM?$_ym_id'><img src='$_ym_img_offline'/></a>";
        }
        else {
            $html .= "<a href='ymsgr:SendIM?$_ym_id'><img src='$_ym_img_online'/></a>";
        }
    }
    return $html;
}
