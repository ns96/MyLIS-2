<?php

class Orderbook_model extends CI_Model {
    
    var $lisdb = null;
    var $o_table = ''; // the orders table
    var $i_table = ''; // the items table
    var $c_table = ''; // the chemicals table
    var $s_table = ''; // the supplies table
    var $ct_table = ''; // the category table
    var $p_table = ''; // the account wide properties table
    
    var $ordersPrivate = false; // set whether other users can see what others user ordered
    var $update_chemical = true; // update the chemical DB;
    var $update_supply = true; // update the supply DB;
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->o_table = $this->session->userdata('group')."_orders";
	$this->i_table = $this->session->userdata('group')."_order_items";
	$this->c_table = $this->session->userdata('group')."_chemicals";
	$this->s_table = $this->session->userdata('group')."_supplies";
	$this->ct_table = $this->session->userdata('group')."_categories";
	$this->p_table = $this->session->userdata('group')."_properties";
    }
    
    public function search($searchfor,$userid,$myorders){
        // search company and notes in orders, and company, product_id and description in order_item
        if($myorders == 'yes') {
          $sql1 = "SELECT * FROM $this->o_table WHERE (company LIKE '%$searchfor%' OR 
          notes LIKE '%$searchfor%' OR ponum LIKE '%$searchfor%') AND owner = '$userid'";

          $sql2 = "SELECT * FROM $this->i_table WHERE (company LIKE '%$searchfor%' OR 
          product_id LIKE '%$searchfor%' OR description LIKE '%$searchfor%')
          AND owner = '$userid'";
        }
        else {
          $sql1 = "SELECT * FROM $this->o_table WHERE (company LIKE '%$searchfor%' OR 
          notes LIKE '%$searchfor%' OR ponum LIKE '%$searchfor%')";

          $sql2 = "SELECT * FROM $this->i_table WHERE (company LIKE '%$searchfor%' OR 
          product_id LIKE '%$searchfor%' OR description LIKE '%$searchfor%')";
        }
        
        $sql_result1 = $this->lisdb->query($sql1)->result_array();
        $toReturn['ordersData'] = $this->get_orders_array($sql_result1);

        $sql_result2 = $this->lisdb->query($sql2)->result_array();
        $toReturn['items'] = $this->getItemsArray($sql_result2);
        
        return $toReturn;
    }
    
    public function add_order($data){
        $sql = "INSERT INTO $this->o_table VALUES('', '$data[company]', '$data[ponum]', '$data[conum]', '$data[priority]', 
            '$data[account]', '$data[order_date]', '$data[status]', '$data[status_date]', '0.00', '0.00', '$data[s_expense]', '0.00', '$data[notes]', '$data[owner]', '$data[user_id]', '$data[maxitems]')";
        $this->lisdb->query($sql);
        return $this->lisdb->insert_id();
    }
    
    public function add_order_item($data){
        $sql = "INSERT INTO $this->i_table VALUES('', '$data[order_id]', '$data[stock_id]', '$data[i]', '$data[type]', '$data[company]', '$data[product]', 
            '$data[description]', '$data[amount]', '$data[units]', '$data[price]', '$data[status]', '$data[status_date]', '$data[owner]', '$data[user_id]')";
        $this->lisdb->query($sql);
    }
    
    public function update_order_keep_user($data){
        $sql = "UPDATE $this->o_table SET company='$data[company]', ponum='$data[ponum]', conum='$data[conum]', priority='$data[priority]', account='$data[account]', 
            status='$data[status]', status_date='$data[status_date]', s_expense='$data[s_expense]', notes='$data[notes]' WHERE order_id='$data[order_id]'";
        $this->lisdb->query($sql);
    }
    
    public function update_order_change_user($data){
        $sql = "UPDATE $this->o_table SET company='$data[company]', ponum='$data[ponum]', conum='$data[conum]', priority='$data[priority]', account='$data[account]', 
          status='$data[status]', status_date='$data[status_date]', s_expense='$data[s_expense]', notes='$data[notes]', userid='$data[user_id]'";
        if (isset($data['maxitems'])){
            $sql .= ", maxitems = '$data[maxitems]'";
        }
        $sql .= " WHERE order_id='$data[order_id]'";
        $this->lisdb->query($sql);
    }
    
    public function update_order_item_keep_user($data){
        $sql = "UPDATE $this->i_table SET type = '$data[type]', company='$data[company]', product_id='$data[product]', description='$data[description]', amount='$data[amount]', units='$data[units]', 
            price='$data[price]', status='$data[status]', status_date='$data[status_date]', owner='$data[owner]' WHERE (order_id='$data[order_id]' AND item_num='$data[i]')";
        $this->lisdb->query($sql);
    }
    
    public function update_order_item_change_user($data){
        $sql = "UPDATE $this->i_table SET type = '$data[type]', company='$data[company]', product_id='$data[product]', description='$data[description]', amount='$data[amount]', units='$data[units]', 
            price='$data[price]', status='$data[status]', status_date='$data[status_date]', owner='$data[owner]', userid='$data[user_id]' WHERE (order_id='$data[order_id]' AND item_num='$data[i]')";
        $this->lisdb->query($sql);
    }
    
    public function update_order_totals($g_expense,$p_expense,$order_id){
        $sql = "UPDATE $this->o_table SET g_expense='$g_expense', p_expense='$p_expense' WHERE order_id='$order_id'";
        $this->lisdb->query($sql);
    }
    
    public function update_item_status($status,$status_date,$order_id,$i){
        $sql = "UPDATE $this->i_table SET status='$status', status_date='$status_date' WHERE (order_id='$order_id' AND item_num='$i')";
        $this->lisdb->query($sql);
    }
    
    public function remove_order($order_id){
        $sql = "DELETE FROM $this->o_table WHERE order_id='$order_id'";
        $this->lisdb->query($sql);
    }
    
    public function remove_order_items($order_id){
        $sql = "DELETE FROM $this->i_table WHERE order_id='$order_id'";
        $this->lisdb->query($sql);
    }
    
    public function remove_item($item_id){
        $sql = "DELETE FROM $this->i_table WHERE item_id='$item_id'";
        $this->lisdb->query($sql);
    }
    
    public function get_total_expense($order_id){
        $sql = "SELECT * FROM $this->o_table WHERE order_id='$order_id'";
        $record = $this->lisdb->query($sql)->result_array();
        return $record[0];
    }
    
    public function update_total_expense($g_expense,$p_expense,$order_id){
        $sql = "UPDATE $this->o_table SET g_expense='$g_expense', p_expense='$p_expense' WHERE order_id='$order_id'";
        $this->lisdb->query($sql);
    }
    
    public function get_order($order_id){
	$sql = "SELECT * FROM $this->o_table WHERE order_id='$order_id'";
	$records = $this->lisdb->query($sql)->result_array();
	$order = $records[0];
	
	// now retrived any items beloging to this order and store their parameters
	// as a tab delimited string with key item_1 ... item_10.
	for($i = 1; $i <= $order['maxitems']; $i++) {
	    $sql = "SELECT * FROM $this->i_table WHERE (order_id='$order_id' AND item_num='$i')";
	    $records = $this->lisdb->query($sql)->result_array();
	    $count = count($records);

	    if($count != 0) { // add new entry
		$item = $records[0];
                $info = "$item[type] \t $item[company] \t $item[product_id] \t $item[description] \t $item[amount] \t $item[units] \t $item[price] \t $item[owner] \t $item[status] \t $item[stock_id] \t $item[item_id]";
		$order['item_'.$i] = $info;
	    }
	}
	return $order;
    }
    
    public function get_item($order_id,$i){
        $sql = "SELECT * FROM $this->i_table WHERE (order_id='$order_id' AND item_num='$i')";
        $record = $this->lisdb->query($sql)->result_array();
        return $record[0];
    }
    
    public function get_companies(){
	$sql = "SELECT * FROM $this->ct_table WHERE (table_name='$this->o_table' AND type='company') ORDER BY category_id";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    public function get_accounts(){
	$sql = "SELECT * FROM $this->ct_table WHERE (table_name='$this->o_table' AND type='account') ORDER BY category_id";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    // function to return account which have need to be tallied when producing the order report
    public function get_tally_accounts() {
      $tally_accounts = array();

      $sql = "SELECT * FROM $this->ct_table WHERE (table_name='$this->o_table' AND type='tally') ORDER BY category_id";
      $records = $this->lisdb->query($sql)->result_array();
      foreach($records as $singleAccount){
        $account = $singleAccount['value'];
        $tally_accounts[$account] = $account.'*';
      }
      
      return $tally_accounts;
    }
    
    public function get_order_visibility(){
	$sql = "SELECT * FROM $this->p_table WHERE key_id='orders.private'";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    // function to return any pending orders
    public function get_pending_items($users,$my_userid,$my_role) {
      $items = array();

      $sql = "SELECT * FROM $this->o_table WHERE (status='requested' OR status='ordered') ORDER BY order_id";
      $records = $this->lisdb->query($sql)->result_array();
      $count = count($records);

      if($count > 0) {
       foreach($records as $singleOrder){
          $order_id = $singleOrder['order_id'];
          $date = $singleOrder['status_date'];
          $owner = $singleOrder['owner'];
          $user = $users[$owner];
          $name = $user->name;

          if(!$this->are_orders_private($my_role) || $owner == $my_userid) {
            $sql = "SELECT * FROM  $this->i_table WHERE (order_id='$order_id' AND status='pending')";
            $orderItems = $this->lisdb->query($sql)->result_array();

            foreach($orderItems as $singleItem){
              $info = "$order_id \t$singleItem[item_id] \t $name \t $singleItem[company] \t $singleItem[product_id] \t 
              $singleItem[description] \t $singleItem[amount] \t $singleItem[units] \t $singleItem[price] \t 
              $date \t $singleItem[owner]";

              $items[] = $info;
            } 
          }
        }
      }

      return $items;
    }
   
    // function to return the items which have been ordered
    public function get_ordered_items($users,$my_userid,$my_role) {
      $items = array();

      $sql = "SELECT * FROM $this->i_table WHERE (status='ordered' OR status='back ordered')";
      $records = $this->lisdb->query($sql)->result_array();
      $count = count($records);

      if($count > 0) {
         foreach($records as $singleItem){
          $userid = $singleItem['userid'];
          $user = $users[$userid];
          $name = $user->name;
          $owner = $singleItem['owner'];

          if(!$this->are_orders_private($my_role) || $owner == $my_userid) {
            $info = "$singleItem[order_id] \t$singleItem[item_id] \t $name \t $singleItem[company] \t 
            $singleItem[product_id] \t $singleItem[description] \t $singleItem[amount] \t $singleItem[units] \t 
            $singleItem[price] \t $singleItem[status_date] \t $singleItem[owner] \t $singleItem[type] \t $singleItem[item_num]";

            $items[] = $info;
          }
        }
      }

      return $items;
    }
    
    // function to retrive any saved orders
    public function get_saved_orders($my_userid) {
      $saved_orders = array();

      $sql = "SELECT * FROM $this->o_table WHERE (owner='$my_userid' AND status='saved') ORDER BY order_id DESC LIMIT 2";
      $records = $this->lisdb->query($sql)->result_array();
      $count = count($records);

      if($count > 0) {
        foreach($records as $singleOrder){
          $order_id = $singleOrder['order_id'];
          $date = $singleOrder['status_date'];
          $saved_orders[$order_id] =  $date;
        }
      }

      return $saved_orders;
    }
    
    // function to return a list of recent orders
    public function get_recent_orders($my_userid) {
      $recent_orders = array();

      $sql = "SELECT * FROM $this->o_table WHERE 
      (owner='$my_userid' AND (status='ordered' OR status='requested')) ORDER BY order_id DESC LIMIT 2";
      $records = $this->lisdb->query($sql)->result_array();
      $count = count($records);

      if($count > 0) {
        foreach($records as $singleRecord){
          $order_id = $singleRecord['order_id'];
          $date = $singleRecord['status_date'];
          $recent_orders[$order_id] =  $date;
        }
      }

      return $recent_orders;
    }
    
    // function to actually view the orders
    public function get_orders($ordered_by, $year, $month) {
      $orders = array();

      // based on the vales of the parameters generate the search strings
      $sql = '';
      if($ordered_by == 'all' && $year == 'all' && $month =='all') { // 1
        $sql = "SELECT * FROM $this->o_table";
      }
      else if($ordered_by == 'all' && $year == 'all' && $month !='all') { // 2
        $sql = "SELECT * FROM $this->o_table WHERE status_date LIKE '$month/%'";
      }
      else if($ordered_by == 'all' && $year != 'all' && $month =='all') { // 3
        $sql = "SELECT * FROM $this->o_table WHERE status_date LIKE '%/$year'";
      }
      else if($ordered_by == 'all' && $year != 'all' && $month !='all') { // 4
        $sql = "SELECT * FROM $this->o_table WHERE status_date LIKE '$month/%/$year'";
      }
      else if($ordered_by != 'all' && $year == 'all' && $month =='all') { // 5
        $sql = "SELECT * FROM $this->o_table WHERE owner = '$ordered_by'";
      }
      else if($ordered_by != 'all' && $year == 'all' && $month !='all') { // 6
        $sql = "SELECT * FROM $this->o_table WHERE (owner = '$ordered_by' AND status_date LIKE '$month/%')";
      }
      else if($ordered_by != 'all' && $year != 'all' && $month =='all') { // 7
        $sql = "SELECT * FROM $this->o_table WHERE (owner = '$ordered_by' AND status_date LIKE '%/$year')";
      }
      else if($ordered_by != 'all' && $year != 'all' && $month !='all') { // 8
        $sql = "SELECT * FROM $this->o_table WHERE (owner = '$ordered_by' AND status_date LIKE '$month/%/$year')";
      }

      // now search the database
      $records = $this->lisdb->query($sql)->result_array();
      
      return $this->get_orders_array($records);
    }
    
    // function to return a all the items list
    public function get_itemlists($company) {
      $itemlists = array();
      $user_id = $this->userobj->userid;

      if($company == 'all') {
        $sql = "SELECT * FROM $this->o_table WHERE status = 'itemlist'";
      }
      else { // need to also see if this is viewable by every one or just the person who created it
        $company_sql = $this->get_company_sql_string($company);
        $sql = "SELECT * FROM $this->o_table WHERE (status = 'itemlist' AND $company_sql AND (priority='High' OR owner='$user_id'))";
      }
      $records = $this->lisdb->query($sql)->result_array();
      $itemlists = $this->get_orders_array($records);

      return $itemlists;
    }
    
    public function save_company_name($company){
        $sql = "INSERT INTO $this->ct_table VALUES('', '$this->o_table', 'company', '$company', 'myadmin')";
        $this->lisdb->query($sql);
    }
    
    // function to save a company in the category database
    public function save_account($account) {
      $sql = "INSERT INTO $this->ct_table VALUES('', '$this->o_table', 'account', '$account', 'myadmin')";
      $this->lisdb->query($sql);
    }
    
    // function to return to see if orders are private or not. Private orders can only be viewed by the owner or admin, or buyer
    protected function are_orders_private($role) {
     if($role == 'admin' || $role == 'buyer') {  // user with these roles have the right to view all orders
       return false;
     } else { // return that the orders are private
       return $this->ordersPrivate;
     }
   }
   
   // function to return an array containing order information
    public function get_orders_array($sql_result) {
      $orders = array();
      $all_total = 0;
      $count = count($sql_result);

      // load the accounts for which tallies should be computed for
      $tally_accounts = $this->get_tally_accounts();
      $tally_totals = array();

      if($count > 0) {
        foreach($sql_result as $array){
          $order_id = $array['order_id'];
          $owner = $array['owner'];
          $company = $array['company'];
          $status = strtoupper($array['status']);
          $date = $array['status_date'];
          $priority = $array['priority']; // used only in orderbook_itemlist php for now 1/26/09
          $account = $array['account'];

          $sum = $array['g_expense'] + $array['p_expense'] + $array['s_expense'];
          $total = '$'.sprintf("%01.2f", $sum);
          if($status != 'SAVED') { // don't add orders that are saved to the total
            $all_total += $sum;
            if(isset($tally_accounts[$account])) {
              $tally_totals[$account] += $sum;
            }
          }
          else { // indicate that this order is just saved
            $total .= '*';
          }
          $orders[$order_id] = "$owner \t $company \t $status \t $date \t $total \t $priority \t $account";
        }

        $orders['total'] = '$'.sprintf("%01.2f", $all_total); // set the total for this order
      }
      $to_return['orders'] = $orders;
      $to_return['tally_totals'] = $tally_totals;
      return $to_return;
    }
    
    // function to add search results for ordered items to the results 2
    public function get_items_array($sql_result) {
      $items = array();
      $count = count($sql_result);

      if($count > 0) {
        foreach($sql_result as $array) {
          $item_id = $array['item_id'];  // items[0]
          $order_id = $array['order_id']; // items[1]
          $company = $array['company'];  // items[2]
          $product_id = $array['product_id']; // items[3]
          $description = $array['description']; // items[4]
          $owner = $array['owner']; // items[5]
          $status = strtoupper($array['status']); // items[6]
          $status_date = $array['status_date']; // items[7]
          $price = '$'.sprintf("%01.2f", $array['price']); // items[8]

          $items[$item_id] = "$item_id \t $order_id \t $company \t $product_id \t 
          $description \t $owner \t $status \t $status_date \t $price";
        }
      }

      return $items;
    }
   
    // function to return the maximum number of items allow for this order
    public function get_max_items_value($order_id) {
      global $conn;

      // get the maximum number of items for this order
      $sql = "SELECT maxitems FROM $this->o_table WHERE order_id='$order_id'";
      $record = $this->lisdb->query($sql)->result_array();

      return $record['maxitems'];
    }
    
    public function set_max_items_value($order_id,$new_value){
         $sql = "UPDATE $this->o_table SET maxitems='$new_value' WHERE order_id='$order_id'";
         $this->lisdb->query($sql);
    }
    
    public function check_item_existence($order_id,$i){
        $sql = "SELECT * FROM $this->i_table WHERE (order_id='$order_id' AND item_num='$i')";
        $records = $this->lisdb->query($sql)->result_array();
        $count = count($records);
        return $count;
    }
    
    // function to either add or update the database
    public function update_inventory_db($user_id, $order_id, $item_num) {

      $sql = "SELECT * FROM $this->i_table WHERE (order_id='$order_id' AND item_num='$item_num')";
      $record = $this->lisdb->query($sql)->result_array();

      $item_id = $record['item_id'];
      $type = $record['type'];
      $stock_id = $record['stock_id'];
      $status_date = getLISDate();

      if($type == 'Chemical' && $this->update_chemical) { // update chemicals database
        $cas = "000000";
        $name = $record['description'];
        $company = $record['company'];
        $product_id = $record['product_id'];
        $amount = $record['amount'];
        $units = $record['units'];
        $entry_date = getLISDate();
        $status = 'In Stock';
        $status_date =  $entry_date;
        $mfmw = 'Not Entered';
        $category = 'Organic';
        $location = 'unassigned';
        $notes = 'None';
        $owner = $record['owner'];
        $userid = $user_id;

        if(empty($stock_id)) { // add new entry
          $sql = "INSERT INTO $this->c_table VALUES('', '$cas', '$name', '$company', '$product_id', '$amount',
          '$units', '$entry_date', '$status', '$status_date', '$mfmw', '$category', '$location', '$notes', '$owner', '$userid')";
          $this->lisdb->query($sql);

          // update stock id value now
          $stock_id = mysql_insert_id();
          $sql = "UPDATE $this->i_table SET stock_id='$stock_id' WHERE item_id='$item_id'";
          $this->lisdb->query($sql);
        }
        else { // update an old entry
          $sql = "UPDATE $this->c_table SET status='$status', status_date='$status_date' WHERE chem_id='$stock_id'";
          $this->lisdb->query($sql);
        }
      }
      else if($type == 'Supply' && $this->update_supply) { // update supply database
        $model = "000000";
        $name = $record['description'];
        $company = $record['company'];
        $product_id = $record['product_id'];
        $amount = $record['amount'];
        $units = $record['units'];
        $entry_date = getLISDate();
        $status = 'In Stock';
        $status_date =  $entry_date;
        $sn = '000000';
        $category = 'Other';
        $location = 'unassigned';
        $notes = 'None';
        $owner = $record['owner'];
        $userid = $user_id;

        if(empty($stock_id)) { // add new entry
          $sql = "INSERT INTO $this->s_table VALUES('', '$model', '$name', '$company', '$product_id', '$amount',
          '$units', '$entry_date', '$status', '$status_date', '$sn', '$category', '$location', '$notes', '$owner', '$userid')";
          $this->lisdb->query($sql);

          $stock_id = $this->lisdb->insert_id();

          $sql = "UPDATE $this->i_table SET stock_id='$stock_id' WHERE item_id='$item_id'";
          $this->lisdb->query($sql);
        }
        else { // update an old entry
          $sql = "UPDATE $this->s_table SET status='$status', status_date='$status_date' WHERE item_id='$stock_id'";
         $this->lisdb->query($sql);
        }
      }
    }
    
    // function to return the order_id based on the company name for an item. If more than one
    // order exist return the fisrt one
    public function get_order_id_from_company($company,$user_id) {
      $info = array();

      $sql = "SELECT * FROM $this->o_table WHERE (status = 'itemlist' AND company LIKE '%$company%' AND owner = '$user_id')";
      $records = $$this->lisdb->query($sql)->result_array();
      $count = count($records);

      // construct the item list now
      if($count != 0) { // add new entry
        $info[0] = $records['order_id'];
        $info[1] = $records['maxitems']; // also return this sice we are going to need it
      }
      else {
        $info[0] = -1; // return -1 as order id
      }

      return $info;
    }

    // function to break apart company names seperated by / character
    // and create a sql search string
    public function get_company_sql_string($company) {
      if(strstr($company, '/')) {
        $sql = '';
        $companies = split('/', $company);

        foreach($companies as $comp) {
          $comp = trim($comp); // remove any white spaces
          $sql .= "company LIKE '%$comp%' OR ";
        }
        // remove last three characters and add closing quotes
        $sql = '('.substr($sql,'',-4).')'; // remove the last 4 characters
        return $sql;
      }
      else {
        return "company LIKE '%$company%'";                    
      }
    }
    
}