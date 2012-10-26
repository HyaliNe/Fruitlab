<?php
class transaction_model extends CI_Model {
   
    public function getCustomerPaymentByCartList($user){
        $result = array();
        $result['result'] = FALSE;
        $this->db->select('*');
        $this->db->where('design.customer_id', $user);
        $this->db->from("design");
        $this->db->join('cart', 'design.customer_id = cart.customer_id');
        $this->db->join('singlecartitem','singlecartitem.design_id = design.design_id');
        $query = $this->db->get();
        $i = 0;  
	$this->load->model('account_model');
        foreach ($query->result() as $row){
            $result[$i]["single_item_id"] = $row->single_item_id;
            $result[$i]["price"] = $row->price;
            $result[$i]["quantity"] = $row->quantity;
            $result[$i]["title"] =  $row->title;
            $result[$i]["image_path"] = $row->image_path;
            $result[$i]["status"] = $row->status;
            $i++;
        }
        return $result;
    }
    /*
     * to retrieve the customer purchase transaction by cart_id,date,status,reference
     * followed by title,price,quantity 
     */
    public function getCustomerPurchaseHistory($user){
        $result = array();
        $db = 'Select cart_id,date,status,reference_number from cart where customer_id = ' . $user;
        $query = $this->db->query($db);
        $cart = 0;
        foreach ($query->result() as $row){
            $row = $query->row();
            $cart_id = $row->cart_id;
            $result[$cart]['cart_id'] = $cart_id;
            $result[$cart]['date'] = $row->date;
            $result[$cart]['status'] = $row->status;
            $result[$cart]['reference_number'] = $row->reference_number;
            $this->db->select('title,price,quantity');
            $this->db->from('singlecartitem')->join('design','singlecartitem.design_id = design.design_id');
            $this->db->where('cart_id',$cart_id)->order_by('title','asc');
            $query = $this->db->get();
            $line = 0;
            foreach($query->result() as $row){
            $result[$cart]['singlelineitem'][$line]['title'] = $row->title;
            $result[$cart]['singlelineitem'][$line]['price'] = $row->price;
            $result[$cart]['singlelineitem'][$line]['quantity'] = $row->quantity;
            $line++;
            }
        $cart++;
        }
       
        return $result;
    }
    
    public function getAllCustomer(){
        $result = array();
        $result['result'] = FALSE;
        $this->db->select('customer_id');
        $this->db->where('role_id',2);
        $query = $this->db->get('customer');
        $i = 0;
	$this->load->model('account_model');
        foreach ($query->result() as $row){
            $this->load->model('account_model');
            $result['customer'][$i] = $this->account_model->retrieve_profile($row->customer_id);
            $i++;
        }
        return $result;
    }
    public function getCustomerTransactions($user){
        $result = array();
        $result['result'] = FALSE;
        $this->db->select('transaction_id,trans_amt,timestamp,remarks');
        $this->db->where('customer_id',$user);
        $query = $this->db->get('transaction');
        $i = 0;
        foreach ($query->result() as $row){
            $result[$i]['transaction_id'] = $row->transaction_id;
            $result[$i]['trans_amt'] = $row->trans_amt;
            $result[$i]['timestamp'] = $row->timestamp;
            $result[$i]['remarks'] = $row->remarks;
            $i++;
        }
        return $result;
    }
    public function withdraw($user,$amount){
        $result['result'] = FALSE;
        $this->db->where('customer_id',$user);
        $this->db->select('balance');
        $query = $this->db->get('customer',1);
        if($query->num_rows == 1)
	{
            $row = $query->row();
            $balance = $row->balance;
        }
        if( ($balance - $amount) >= 0 ){//withdrawal success
            $newbalance = $balance - $amount;
            $userData = array(
            'balance' => $newbalance
             );
            $this->db->where('customer_id',$user);
            $this->db->update('customer',$userData);
            $value = 0 - $amount;
            $transData = array(
              'customer_id' => $user,
              'trans_amt' => $value,
              'remarks' => 'Debit'
            );
            $this->db->insert('transaction', $transData);
            $result['result'] = TRUE;
        }
        return $result;
    }
    public function deposit($user,$amount){
        $result['result'] = FALSE;
        $this->db->where('customer_id',$user);
        $this->db->select('balance');
        $query = $this->db->get('customer');
        if($query->num_rows == 1)
	{
            $row = $query->row();
            $balance = $row->balance;
        }
        if(($balance + $amount) <= 1000){
            $newbalance  = $balance + $amount;
            $userData = array(
                'balance' => $newbalance
            );
            $this->db->where('customer_id',$user);
            $this->db->update('customer',$userData);
            $transData = array(
              'customer_id' => $user,
              'trans_amt' => $amount,
              'remarks' => 'Credit'
            );
            $this->db->insert('transaction', $transData);
        }
        $result['result'] = FALSE;
    }
    
    public function close($user){
        $this->db->where('customer_id',$user);
        $this->db->select('balance');    
        $userdata = array(
            'status' => 'inactive'
        );
        $result = $this->db->update('customer',$userdata);
        if($result){
            $result['result'] = TRUE;
        }
        return $result;
    }
   
    
    public function open($user){
        $result['result'] = FALSE;
        $this->db->where('customer_id',$user);
        $userdata = array(
            'status' => 'active'
        );
        $result = $this->db->update('customer',$userdata);
        if($result){
            $result['result'] = TRUE;
        }
        return $result;
    }
}
?>
