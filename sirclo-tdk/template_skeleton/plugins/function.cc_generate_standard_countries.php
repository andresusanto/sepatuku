<?php
function smarty_function_cc_generate_standard_countries($params, $template)
{
    if (isset($params['out'])) {
        $template->assign($params['out'], Helper_Paypal::getPaypalCountriesAssoc());
    }
}
