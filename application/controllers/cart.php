<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cart Controller
 *
 * Handles all page that involves the shopping cart
 *
 * @package		Fruitlab
 * @author		Chen Deshun
 */


 class Cart extends CI_Controller {
	 
	 public function customise($id) {
		 $this->load->model('cart_model');
		 
		 $options = $this->cart_model->fetchOptions();
		 $data['collar'] = $options['collar'];
		 $data['material'] = $options['material'];
		 $data['colour'] = $options['colour'];
		 
 		 $this->load->model('design_model');
		
 		 $design = $this->design_model->fetchSingleDesign($id);
		
 	 	 if ($design['exist']) {
			 $data['design_id']		= $design['design_id'];
			 $data['image_path']		= $design['image_path'];
		 }
		 
		 
		 $data['main_content'] = 'cart/custom_shirt';
 		 $this->load->view('includes/template', $data);
		 
	 }
	 
	 
	 public function addToCart() {
		 $color		= $this->input->post('colorID');
		 $collar	= $this->input->post('collardID');
		 $design	= $this->input->post('designID');
		 $material	= $this->input->post('materialID');
		 //$qty		= $this->input->post('qty');
		 $qty		= 1;
		 //$size		= $this->input->post('size');
		 $price		= 0; //fetch price of design, collar and color from database and compute
		 
		$this->load->model('cart_model');
		 if($this->cart_model->validateCombination($design, $collar, $color, $material)){		 
			 $data = array(
		                'id'      => $design,
		                'qty'     => $qty,
		                'price'   => $price,
		                'name'    => 'Custom T-Shirt with design by ',
		                'options' => array(
							//'Size' => $size, 
							'Color' => $color, 
							'Collar' => $collar,
							'Design' => $design,
							'material' => $material)
		             );

		 	$this->cart->insert($data); 
		 	
			//successfully added to cart
			//return message to user

			$data['message_title'] = "added to cart";
			$data['message'] = "added to cart";
				
		 } else {
			 //display error message

 			$data['message_title'] = "FAILED to add to cart";
 			$data['message'] = "fail to add to cart";
			 }
				
 			$data['main_content'] = "message";
			$this->load->view('includes/template', $data);
			 //redirect('cart/viewCart');
		 
		 
			 //todo stupid items is not inserted into session. unable to store cart info.
	 }
	 
	 public function viewCart() {
 		$data['main_content'] = 'cart/cart';
 		$this->load->view('includes/template', $data);
	 }
	 
	 public function checkOut() {
		 //take current cart display out details of the cart
		 //ask the user to confirm
	 }
	 
	 public function payment() {
		 //redirect to paypal for payment
	 }

 }

 ?>