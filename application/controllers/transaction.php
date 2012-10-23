<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction extends CI_Controller {
        public function index() {
            $this->load->model('transaction_model');
            $this->load->library('table');
            $data['customers'] = $this->transaction_model->getAllCustomer();
            $data['main_content'] = 'transaction/display_customers';
            $this->load->view('includes/template', $data);
        }    
        public function financialsummary() {
		$data['main_content'] = 'transaction/financialsummary';
		$this->load->view('includes/template', $data);
	}
        public function paymenthistory($customer_id = 0) {
                $this->load->model("transaction_model");
                $data['sales'] = $this->transaction_model->getCustomerPaymentByCartList($customer_id);
                $data['main_content'] = 'transaction/paymenthistory';
		$this->load->view('includes/template', $data);
	}
        public function viewearnings() {
		$data['main_content'] = 'transaction/viewearnings';
		$this->load->view('includes/template', $data);
	}
    public function withdrawbalance() {
            $customer_id = 1;    
            $this->load->model("transaction_model");
            $this->load->library('form_validation');
            $data['error'] = '';
            $this->form_validation->set_rules('money', 'money', 'required|numeric');
            if($this->form_validation->run()){
                if(isset($_POST['withdraw'])){
                    $result = $this->transaction_model->withdraw($customer_id,$_POST['money']);
                    if($result['result']){
                        $data['error'] = 'You have successfully withdrawn ' . $_POST['money'];
                    }else{
                        $data['error'] = 'You have exceeded the limit';   
                    }
                }else if(isset($_POST['deposit'])){
                    $result = $this->transaction_model->deposit($customer_id,$_POST['money']);
                    if($result['result']){
                        $data['error'] = 'You have successfully deposited' . $_POST['money'];
                    }else{
                        $data['error'] = 'You have exceeded deposit limit';   
                    }
                }
            }
            $data['transactions'] = $this->transaction_model->getCustomerTransactions($customer_id);
            $data['main_content'] = 'transaction/withdrawbalance';
            $data['customer'] = $this->transaction_model->retrieve_profile($customer_id);
            $this->load->view('includes/template', $data);
    }
    
    public function process(){
        $this->load->library('form_validation');
            $this->form_validation->set_rules('money', 'money', 'required|numeric');
            if($this->form_validation->run()){
            }
    }
	
	public function purchaseshirt()
	{
		$this->load->model("transaction_model");
		//this call should return all the necessary information to populate the purchaseshirt view
		$transaction = $this->transaction_model->purchaseshirt($this->input->post());
		
		$data['purchaseshirt'] = $transaction;
		$data['main_content'] = 'transaction/purchaseshirt';
		$this->load->view('includes/template');
	}
}



/* End of file login.php */
/* Location: ./controllers/login.php */	
