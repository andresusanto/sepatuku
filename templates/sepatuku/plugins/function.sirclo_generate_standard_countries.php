<?php
/**    Plugin File */
/**
* Function to generate list of countries
*
* This function get the data from other party and generate it into list of countries as select input
* 
* @param $params variable to assign the generated result
* @param $template to associate the generated countries into the selected template
*
* @return void
*/
function smarty_function_sirclo_generate_standard_countries($params, $template)
{
    if (isset($params['out'])) {
        $template->assign($params['out'], Helper_Paypal::getPaypalCountriesAssoc());
    }
}
