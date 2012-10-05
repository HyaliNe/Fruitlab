<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Friends extends CI_Controller {
        function __construct()
	{
            	parent::__construct();
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{  
            $data['resultset'] = '';
            $data["error"] = '';
            $data["main_content"]  = 'friend_display';
            $this->load->view('includes/template', $data);
        }
        
        function add(){
            $customer = $this->session->userdata('customer_id');
            //$customer_id = 1;
            $data['resultset'] = '';
            $getdata = $this->input->get();
            $data['error'] = '';
            $customer_id2 = $getdata["id"];
            if($customer_id2){
                $result = $this->db->insert('is_friends_with',
                array('customer_id'=> $customer_id
                ,'customer_id2' => $customer_id2
                ));
                if($result){
                    $data['error'] == 'Successfully added the friend';
                }else{
                    $data['error'] == 'Does not exist in the database';
                }
            }
            $data['main_content'] = 'friend_display';
            $this->load->view('includes/template', $data);
        }
        function remove(){
            $customer = $this->session->userdata('customer_id');

            $data['error'] = '';
            $data['resultset'] = '';
            $getdata = $this->input->get();
            $customer_id2 = $getdata["id"];
            if($customer_id2){
                $result = $this->db->delete('is_friends_with',array('customer_id'=>$customer_id,'customer_id2'=>$customer_id2));
                if($result){
                    $data['error'] == 'Successfully deleted friends from the database';
                }else{
                    $data['error'] == 'Does not exist in the database';
                }
            }
            $data['main_content'] = 'friend_display';
            $this->load->view('includes/template', $data);
        }
        function search(){
            $data["error"] = '';
            $data["main_content"]  = 'friend_display';
            $postdata = $this->input->post();
            if(isset($postdata['fname']) && $_POST['fname'] != ''){
                $name = $postdata['fname'];
                $this->db->like('first_name',$name);
            }
            if(isset($postdata['email']) && $_POST['email'] != ''){
                $email = $postdata['email'];
                $this->db->like('email',$email);
            }
            if((isset($postdata['gender']) && $_POST['gender']) != ''){
                 $gender = $postdata['gender'];
                 $this->db->where(array('gender' => $gender));
            }
            if((isset($postdata['fromage']) && $postdata['fromage'] != '' && $postdata['toage'] && $postdata['toage'] !='')){
                    $fromage = $postdata['fromage'];
                    $toage = $postdata['toage'];
                    $this->db->where("date_of_birth BETWEEN '$fromage' AND '$toage'");
            }
            $query = $this->db->get("customer");
            $data['resultset'] = $query;
            $data['main_content'] = 'friend_display';
            $this->load->view('includes/template', $data);
        }
        
}
/* End of file account.php */
/* Location: ./controllers/account.php */	
