<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Setting Page
 *
 * @package		Fruitlab
 * @author		Guo Liang
 *
 */

class Setting extends CI_Controller {
	
	/**
	 * Setting
	 *
	 **/
	public function index() {
		
		$old_password = $this->input->post('oldpassword');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'first name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'last name', 'required|trim');
		$this->form_validation->set_rules('hp_no', 'handphone no', 'min_length[8]|max_length[20]');
		$this->form_validation->set_rules('about_you', 'about you', 'max_length[150]');
		$this->form_validation->set_rules('oldpassword', 'old password', 'callback_wrong_password[dbpassword]');
	
		$this->form_validation->set_rules('password', 'Password', 'required|matches[password2]|trim');			
		$this->form_validation->set_rules('password2', 'Confirm Password', 'required|trim');	

		
		if ($this->form_validation->run()) { 
			// Load account model and call function to update settings
			$this->load->model('account_model');
			//this will post all the data to the method update_settings
			$result = $this->account_model->update_settings($this->input->post());
		//	$result = $this->account_model->create_account($this->input->post());
			
			// settings update successfully
			if ($result) { 
				redirect('dashboard');
			}		
		} else {
			// not successful, go back to setting
		$data['main_content'] = 'account/setting';
		$data['first_name'] = $this->input->post('first_name');
		$data['last_name'] = $this->input->post('last_name');
		$data['country'] = $this->input->post('country');
		$data['date_of_birth'] = $this->input->post('date_of_birth');
		$data['hp_no'] = $this->input->post('hp_no');
		$data['about_you'] = $this->input->post('about_you');
		$data['gender'] = $this->input->post('gender');
		$data['email'] = $this->input->post('email');
		$data['password'] = $this->input->post('dbpassword');
		$this->load->view('includes/template', $data);
		}
	}
	
	public function retrieve()
	{
		$this->load->model('account_model');
		//retrieve email, then use it to query
		$email = $this->session->userdata('email');
		
		$data = $this->account_model->retrieve_settings($email);
		
		//if query has result
		if($data['result'])
		{	
			//setting it such that template know which page to redirect to
			$data['main_content'] = 'account/setting';
			//by adding variable behind, data is being pass to the file specified.
			$this->load->view('includes/template', $data);
		}
	}
	
	public function wrong_password($oldpassword,$dbpassword_field)
	{
		$dbpassword = $this->input->post($dbpassword_field);
		
		if( sha1($oldpassword) != $dbpassword)
		{
			$this->form_validation->set_message('wrong_password', 'The %s entered is incorrect.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
}


/* End of file setting.php */
/* Location: ./controller/setting.php */	
