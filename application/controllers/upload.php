<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{       
           
            //configurations files for uploading images
            $config['upload_path'] = './uploads/';
	    $config['allowed_types'] = 'gif|jpg|png';
	    $config['max_size']	= '1000';
	    $config['max_width']  = '1024';
	    $config['max_height']  = '768';
            $this->load->library('upload', $config);
            //load the config class libraries here
            $this->load->library('form_validation');
            $this->form_validation->set_rules('title', 'title', 'required|trim');
	    $this->form_validation->set_rules('price', 'price', 'required|trim|integer');
	    $this->form_validation->set_rules('type', 'type', 'required|trim');
            $this->form_validation->set_rules('rating','rating','required|trim|integer');
            //by default is error free method
            //do the file upload method;
            $data['error'] = '';
            $data['main_content'] = 'upload_form';
            //define a false variable to prevent going into outer loop
            $result = false;
            if($this->form_validation->run())
                $result = $this->upload->do_upload();
                if(!$result){
                     //display the image upload failures options
                     $data['error'] = '';
                     $data['main_content'] = 'upload_form';
                }else{
                     $filedata = $this->upload->data();
                     $filepath = $filedata['full_path'];
                     $postdata = $this->input->post();
                     $rating = $postdata['rating'];
                     $price = $postdata['price'];
                     $title = $postdata['title'];
                     $type = $postdata['type'];
                     $db = $this->load->database();
                     $result = $this->db->query("INSERT INTO design (customer_id,image_path,rating,price,title,type)
                             VALUES(1,'". $filepath.
                             "',".$rating. "," . $price . ",'" . $title . "','".$type."')");
                     echo $this->db->insert_id();  
                     print_r($result);
                     $data = array('upload_data' => $this->upload->data());
		     $data['main_content'] = 'upload_success';
                }
                $this->load->view('includes/template', $data);
            }
        }
        function do_upload()
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('upload_form', $error);
		}
		else
		{       
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('upload_success', $data);
		}

        }

/* End of file account.php */
/* Location: ./controllers/account.php */	
