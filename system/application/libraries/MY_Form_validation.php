<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function My_Form_validation()
	{
		parent::CI_Form_validation();
		parent::set_error_delimiters('<li>', '</li>');
	}

	/**
	 * Unique
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	function unique($str, $field)
	{
		$CI =& get_instance();
		list ($table, $column) = explode('.', $field, 2);

		$CI->form_validation->set_message('unique', 'The %s that you requested is already in use');

		$query = $CI->db->query("SELECT COUNT(*) AS dupe FROM $table WHERE $column = '$str'");
		$row = $query->row();
		return ($row->dupe > 0) ? FALSE : TRUE;
	}

	function uniquevoucherno($str, $type)
	{
		$CI =& get_instance();

		$CI->form_validation->set_message('uniquevoucherno', 'The %s that you requested is already in use');

		$query = $CI->db->query("SELECT COUNT(*) AS dupe FROM vouchers WHERE number = ? AND type = ?", array((int)$str, (int)$type));
		$row = $query->row();
		return ($row->dupe > 0) ? FALSE : TRUE;
	}

	function uniquevouchernowithid($str, $field)
	{
		$CI =& get_instance();

		list ($type, $id) = explode('.', $field, 2);
		$CI->form_validation->set_message('uniquevouchernowithid', 'The %s that you requested is already in use');

		$query = $CI->db->query("SELECT COUNT(*) AS dupe FROM vouchers WHERE number = ? AND type = ? AND id != ?", array((int)$str, (int)$type, $id));
		$row = $query->row();
		return ($row->dupe > 0) ? FALSE : TRUE;
	}

	function uniquewithid($str, $field)
	{
		$CI =& get_instance();
		list($table, $column, $id) = explode('.', $field, 3);

		$CI->form_validation->set_message('uniquewithid', 'The %s that you requested is already in use');

		$query = $CI->db->query("SELECT COUNT(*) AS dupe FROM $table WHERE $column = '$str' AND id != ?", array($id));
		$row = $query->row();
		return ($row->dupe > 0) ? FALSE : TRUE;
	}

	function is_dc($str)
	{
		$CI =& get_instance();

		$CI->form_validation->set_message('is_dc', '%s can only be "Dr" or "Cr"');
		return ($str == "D" || $str == "C") ? TRUE : FALSE;
	}

	function currency($str)
	{
		$CI =& get_instance();
		if (preg_match('/^[\-]/', $str))
		{
			$CI->form_validation->set_message('currency', '%s cannot be negative');
			return FALSE;
		}

		if (preg_match('/^[0-9]*\.?[0-9]{0,2}$/', $str))
		{
			return TRUE;
		} else {
			$CI->form_validation->set_message('currency', '%s must be a valid amount. Maximum 2 decimal places is allowed');
			return FALSE;
		}
	}

	function is_date($str)
	{
		$CI =& get_instance();

		$CI->form_validation->set_message('is_date', 'The %s is a invalid date');

		$current_date_format = $CI->config->item('account_date_format');
		list($d, $m, $y) = array(0, 0, 0);
		switch ($current_date_format)
		{
		case 'dd/mm/yyyy':
			list($d, $m, $y) = explode('/', $str);
			break;
		case 'mm/dd/yyyy':
			list($m, $d, $y) = explode('/', $str);
			break;
		case 'yyyy/mm/dd':
			list($y, $m, $d) = explode('/', $str);
			break;
		default:
			$CI->messages->add('Invalid date format. Please check your account settings', 'error');
			return "";
		}
		return checkdate($m, $d, $y) ? TRUE : FALSE;
	}
	
	function is_date_within_range($str)
	{
		$CI =& get_instance();
		$cur_date = date_php_to_mysql($str);
		$start_date = $CI->config->item('account_fy_start');
		$end_date = $CI->config->item('account_fy_end');

		if ($cur_date < $start_date)
		{
			$CI->form_validation->set_message('is_date_within_range', 'The %s is less than start of current financial year');
			return FALSE;
		} else if ($cur_date > $end_date)
		{
			$CI->form_validation->set_message('is_date_within_range', 'The %s is more than end of current financial year');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function is_hex($str)
	{
		$CI =& get_instance();

		$CI->form_validation->set_message('is_hex', 'The %s is a invalid value');

		if (preg_match('/^[0-9A-Fa-f]*$/', $str))
			return TRUE;
		else
			return FALSE;
	}
}
?>
