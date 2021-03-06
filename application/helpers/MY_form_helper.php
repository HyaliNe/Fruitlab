<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Country Dropdown Menu
 *
 * Create a dropdown menu that is populated with country loaded from $config['country_list']
 *
 * @access	public
 * 
 * @param 	string	Name of the select form. DEFAULT is country.
 *
 * @return 	string 	Complete build of Country Dropdown Menu
 */

function country_dropdown($name = 'country', $selected_country = '', $span = '') {
	//Get an instance of CodeIgniter before loading country_list
	$CI =& get_instance();
	$CI->config->load('country_list');
	$countries = config_item('country_list');
	$span_display = (!empty($span)) ? "class = 'span$span" : "";
	
	$html = "<select name = '{$name}' id = '{$name}' {$span_display} ";
	$html .= "<option value = ''>Please select a country</option>";
	
	foreach ($countries as $key => $value) {
		$selected = (strtolower($value) === strtolower($selected_country)) ? "SELECTED": "" ;
		$html .= "<option value = '{$key}' {$selected}>{$value}</option> ";
	}
	
	$html .= "</select>";
	return $html;
}

//function to return the value of country given a key
function value_of_country($input)
{
	//Get an instance of CodeIgniter before loading country_list
	$CI =& get_instance();
	$CI->config->load('country_list');
	$countries = config_item('country_list');
	
	foreach ($countries as $key => $value)
	{
		if($input == $key)
		{
			return $value;
		}
	}
}	

/* End of file MY_form_helper.php */
/* Location: ./helpers/MY_form_helper.php */