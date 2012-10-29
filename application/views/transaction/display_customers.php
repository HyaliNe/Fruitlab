<html>
<head>
<title></title>
</head>
<body>
    
    <h2>View Customer's Detail</h2>
    <table border ="1">
        <tr><th>Customer_ID</th><th>Email</th><th>Date_of_Birth</th><th>First_Name</th><th/>Last_Name</th><th>Gender</th><th>Balance</th><th>Country</th><th>Status</th><th>Action</th></tr>
        <?php 
        $this->load->helper('url');
        for($i = 0;$i < sizeof($customers['customer']);$i++){
            $customer = $customers['customer'][$i];
            $checked =  ($customer['status'] == 'active') ? 'checked=checked' : '';
            echo "<tr>";
		echo "<td>".$customer['customer_id']."</td><td>". $customer['email'] ."</td>";
		echo "<td>" . date('Y-m-d',strtotime($customer['date_of_birth'])) . "</td><td>" .$customer['first_name'] ."</td><td>".$customer['last_name'] ."</td>";
		echo "<td>".$customer['gender']."</td><td>". $customer['balance'] ."</td>";
		echo "<td>".$customer['country'] ."</td>";
		echo "<td><input type='checkbox' disabled='disabled' $checked/></td>";
                echo "<td>
                    <a href=".site_url("transaction/viewearnings/" . $customer['customer_id']).">[View Earnings]</a>
                    <a href=".site_url("transaction/purchasestatement/" . $customer['customer_id']).">[Purchase Statement]</a><br/></td>";
                echo "</tr>";
        }
        ?>
    </table>
</body>
</html>