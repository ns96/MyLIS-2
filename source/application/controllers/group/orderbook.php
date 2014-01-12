<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Manages the ordering of chemicals or supplies
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Orderbook extends Group_Controller {
    
    var $userobj = null;
    var $cnames = null; // company names
    var $js_cnames = ''; // javascript company names
    var $accounts = null; // the names of accounts used for othering
    var $ordersPrivate = false; // set whether other users can see what others user ordered
    var $maxItemsMax = 200; // only allow up to 150 items. This should be more than enough
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->load->model('orderbook_model');
	
	$this->restrict_access();
	
	$this->set_company_names();
	$this->set_accounts();
	$this->set_configuration();
    }
 
    /**
     * Loads the main page
     */
    public function index(){
	$user_id = $this->userobj->userid;
	$role = $this->userobj->role;
	
	$order_id = $this->input->get('order_id');

	// some variables
	$company = '';
	$ponum = 'n/a';
	$conum = 'n/a';
	$priority = '';
	$status = 'saved';
	$status_date = $this->get_lis_date();
	$account = '';
	$g_expense = '$'.sprintf("%01.2f", 0.00);
	$p_expense = '$'.sprintf("%01.2f", 0.00);
	$s_expense = '$'.sprintf("%01.2f", 0.00);
	$total = '$'.sprintf("%01.2f", 0.00);
	$notes = 'none';
	$maxitems = '10';

	// if we have a saved order_id retrieved the order
	$order = array();
	if(!empty($order_id)) {
	    $order = $this->orderbook_model->get_order($order_id);
	    $company = $order['company'];
	    $ponum = $order['ponum'];
	    $conum = $order['conum'];
	    $priority = $order['priority'];
	    $status = $order['status'];
	    $status_date = $order['status_date'];
	    $account = $order['account'];
	    $g_expense = '$'.sprintf("%01.2f", $order['g_expense']);
	    $p_expense = '$'.sprintf("%01.2f", $order['p_expense']);
	    $s_expense = '$'.sprintf("%01.2f", $order['s_expense']);
	    $sum = $order['g_expense'] + $order['p_expense'] + $order['s_expense'];
	    $total = '$'.sprintf("%01.2f", $sum);
	    $notes = $order['notes'];
	    $maxitems = $order['maxitems'];
	}
	
	$data2['order_id'] = $order_id;
	$data2['js_cnames'] = $this->js_cnames;
	$data2['maxitems'] = $maxitems;
	$data2['defaultAccounts'] = $this->get_default_accounts('defaultAccounts');
	$jsCode = $this->load->view('group/orderbook/jsCode',$data2,TRUE);
	
        $userList = $this->load_users();
        $pendingItems = $this->orderbook_model->get_pending_items($userList,$user_id,$role);
        $orderedItems = $this->orderbook_model->get_ordered_items($userList,$user_id,$role);
	$savedOrders = $this->orderbook_model->get_saved_orders($user_id);
        $recentOrders = $this->orderbook_model->get_recent_orders($user_id);
        $tallyAccounts = $this->orderbook_model->get_tally_accounts();
	
	$data['page_title'] = "Group Orders";
	$data['order_id'] = $order_id;
        $data['role'] = $this->userobj->role;
	$data['jsCode'] = $jsCode;
        $data['pendingItems'] = $pendingItems;
        $data['orderedItems'] = $orderedItems;
        $data['savedOrders'] = $savedOrders;
        $data['recentOrders'] = $recentOrders;
        $data['cnames'] = $this->cnames;
        $data['tallyAccounts'] = $tallyAccounts;
        $data['accounts'] = $this->accounts;
        $data['order'] = $order;
        $data['status'] = $status;
        $data['ponum'] = $ponum;
        $data['conum'] = $conum;
	$data['priority'] = $priority;
        $data['g_expense'] = $g_expense;
        $data['p_expense'] = $p_expense;
        $data['s_expense'] = $s_expense;
        $data['total'] = $total;
        $data['notes'] = $notes;
        $data['company'] = $company;
        $data['status_date'] = $status_date;
        $data['account'] = $account;
        $data['user_id'] = $user_id;
	$this->load_view('group/orderbook/main',$data);
    }
    
    /**
     * Displays all the pending items 
     */
    public function items_pending(){
        if (isset($_POST['pending_items_form'])){
	    $user_id = $this->userobj->userid;
	    $role = $this->userobj->role;
	    $userList = $this->load_users();
    
	    // get the pending orders
	    $items = $this->orderbook_model->get_pending_items($userList,$user_id,$role);
	    $items_status = array();
	    $items_price = array();
	    $p_expenses = array(); // store the personal expense
	    $g_expenses = array(); // store the group expense
	    $orders = array();

	    // set the status array
	    foreach($items as $item) {
		$info = preg_split("/\t/", $item);
		$order_id = trim($info[0]);
		$item_id = trim($info[1]);
		$item_amount = trim($info[6]);
		$item_owner = trim($info[10]);
		$id = $order_id.'_'.$item_id;

		$status = $this->input->post('status_'.$id);
		$price = $this->trim_dollar_sign($this->input->post('price_'.$id));

		if(!empty($status)) {
		    $items_status[$item_id] = $status;
		    $items_price[$item_id] = $price;
		    $orders[$order_id] = $order_id; // store the order id

		    if($item_owner != 'admin') { // increment the personal expense
			$p_expenses[$order_id] += $price*$item_amount;
		    }
		    else { // increment the group expense
			$g_expenses[$order_id] += $price*$item_amount;
		    }
		}
	    }

	    // update the status of the items and the order
	    $status_date = $this->get_lis_date();

	    // update the status of orders
	    $this->orderbook_model->update_orders_status($orders,$p_expenses,$g_expenses,$user_id,$status_date);

	    // update the status of the items
	    $this->orderbook_model->update_items_status($items_status,$items_price,$status_date,$user_id);

	    // indicate to the user the the update was succesfull
	    $back_link = "$script?task=orderbook_main";
	    $count = count($items_status);
	    $message = '<div style="text-align: Center;">
	    The status of '.$count.' item(s) have been successfully updated</div>';
	    
	    $this->session->set_flashdata('message', $message);
	    redirect('group/orderbook/items_pending');
	} else {
	    $user_id = $this->userobj->userid;
	    $role = $this->userobj->role;
	    $userList = $this->load_users();

	    $data['page_title'] = 'Pending Items';
	    $data['user_id'] = $user_id;
	    $data['role'] = $role;
	    $data['items'] = $this->orderbook_model->get_pending_items($userList,$user_id,$role);
	    $data['message'] = $this->session->flashdata('message');
	    $this->load_view('group/orderbook/pending_items',$data);
	}
    }
    
    /**
     * Displays all the items that have been ordered
     */
    public function items_ordered(){
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->load_users();
        
        $data['page_title'] = 'Ordered Items';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['items'] = $this->orderbook_model->get_pending_items($userList,$user_id,$role);
        $this->load_view('group/orderbook/ordered_items',$data);
    }
    
    /**
     * Displays the orders that have been placed by the logged in user
     */
    public function orders_mine(){
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->load_users();
        
        $page = $this->input->get('page');
        if(empty($page)) {
          $page = $this->input->post('page');
        }

        // set the three search parameters
        $ordered_by = $this->input->post('ordered_by');

        if(empty($ordered_by) && $page == 'admin') {
          $ordered_by = 'all';
        }
        else if(empty($ordered_by)) {
          $ordered_by = $user_id;
        }

        $year = $this->input->post('year');
        if(empty($year)) {
          $year = date('Y'); // get for current year
        }

        $month = $this->input->post('month');
        if(empty($month)) {
          $month = 'all'; // get for all months
        }
        
        $data['page_title'] = 'My orders';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $data['page'] = $page;
        $data['ordered_by'] = $ordered_by;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['months'] = $this->get_months();
        $orderData = $this->orderbook_model->get_orders($ordered_by, $year, $month);
        $data['orders'] = $orderData['orders'];
	$data['order_page'] = 'mine';
        $data['tally_totals'] = $orderData['tally_totals'];
        $this->load_view('group/orderbook/list_orders',$data);
    }
    
    /**
     * Displays alist of all the group's orders
     */
    public function orders_all(){
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->load_users();

        $page = $this->input->get('page');
        if(empty($page)) {
          $page = $this->input->post('page');
        }

        // set the three search parameters
        $ordered_by = $this->input->post('ordered_by');

        if(empty($ordered_by)) {
          $ordered_by = 'all';
        }
        else if(empty($ordered_by)) {
          $ordered_by = $user_id;
        }

        $year = $this->input->post('year');
        if(empty($year)) {
          $year = date('Y'); // get for current year
        }

        $month = $this->input->post('month');
        if(empty($month)) {
          $month = 'all'; // get for all months
        }

        $data['page_title'] = 'Orders list';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $data['page'] = $page;
        $data['ordered_by'] = $ordered_by;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['months'] = $this->get_months();
        $orderData = $this->orderbook_model->get_orders($ordered_by, $year, $month);
        $data['orders'] = $orderData['orders'];
	$data['order_page'] = 'all';
        $data['tally_totals'] = $orderData['tally_totals'];
        $this->load_view('group/orderbook/list_orders',$data);

        
    }
    
    /**
     * Displays information about a specific order and its items
     */
    public function order_info(){
        $order_id = $this->input->get('order_id');
        $order = $this->orderbook_model->get_order($order_id);
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->load_users();

        $data['page_title'] = 'Order Information';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $data['order'] = $order;
        $data['order_id'] = $order_id;
        $this->load_view('group/orderbook/order_info',$data);
    }
    
    /**
     * Displays information about multiple orders
     */
    public function order_multiple_info(){
        $order_ids = $this->input->post('order_ids');                                                                                              
        $multipleOrderHTML = '';
        
        $data1['user_id'] = $this->userobj->userid;
	$data1['role'] = $this->userobj->role;
        $data1['users'] = $this->load_users();
        
        if(count($order_ids > 0)) {
          foreach($order_ids as $order_id) {
            if(strstr($order_id, '_')) { // check to see if item is attached to the order id
              $sa = split('_', $order_id);
              $order_id = $sa[0];
            }
            
            $order = $this->orderbook_model->get_order($order_id);
            $data1['order'] = $order;
            $data1['order_id'] = $order_id;
            $multipleOrderHTML .= $this->load->view('group/orderbook/order_info',$data1,TRUE);
            $multipleOrderHTML .= '<br><br>';
          }
        }
        
        $data['page_title'] = 'Multiple order information';
        $data['multipleOrderHTML'] = $multipleOrderHTML;
        $this->load_view('group/orderbook/multiple_order_view',$data);
    }
    
    /**
     * Deletes an order
     */
    public function order_delete(){
        $order_id = $this->input->post('order_id');
        if(empty($order_id)) {
          $order_id = $this->input->get('order_id');
        }

        // remove the order
        $this->orderbook_model->remove_order($order_id);
        // now remove any items belonging to the order
        $this->orderbook_model->remove_order_items($order_id);

        redirect('group/orderbook');
    }
    
    /**
     * Handles a bunch of actions related to an order
     */
    public function order_process(){
        $task = $this->input->post('task2');
 
        if($task == 'save') {
          $this->save_order(1);
        }
        else if($task == 'remove') {
          $this->remove_item();
        }
        else if($task == 'remove_order') {
          $this->removeOrder();
        }
        else if($task == 'submit') {
          $this->save_order(2);
        }
        else if($task == 'item_save') {
          $this->save_order(4);
          $this->save_items_to_list();
        }
        else if($task == 'item_received') {
          $this->item_received();
        }
        else if($task == 'increment') {
          $this->save_order(3); // save the order first just incase a user entered any more information
          $this->increment_max_items_value();
        }
        else {
          $data['error'] = 'Unknown task 2 : '.$task;
          $this->load_view('error/generic_error',$data);
        }
    }
    
    /**
     * Searches for orders meeting certain criteria
     */
    public function search(){
        $userid = $this->userobj->userid;
        $userList = $this->load_users();

        $order_id = $this->input->post('order_id'); // get the order_id for adding found items to current order /*1/28/09 note used for now */
        $searchfor = $this->input->post('searchfor');
        $myorders = $this->input->post('myorders');

        // see whether the search string was past in using the GET
        if(empty($searchfor)) {
          $searchfor = $this->input->get('searchfor');
          $myorders = $this->input->get('myorders');
        }

        $orders = array(); // stores the results from searching the orders table
        $items = array(); // stores the results from searching the order_iten table
        $sql1 = '';
        $sql2 = '';

        $search_results = $this->orderbook_model->search($searchfor,$userid,$myorders);
        
        // now display the results
        $data['page_title'] = 'Search results';
        $data['user_id'] = $userid;
        $data['users'] = $userList;
        $data['order_id'] = $order_id;
        $data['orders'] = $search_results['ordersData']['orders'];
        $data['tally_totals'] = $search_results['ordersData']['tally_totals'];
        $data['items'] = $search_results['items'];;
        $data['searchfor'] = $searchfor;
        $this->load_view('group/orderbook/search_results',$data);
    }
    
    /**
     * Saves an order
     * 
     * $task = 1 Save only
     * $task = 2 Save and mark for odering
     * $task = 3 Don't forward to new page just save and return
     * $task = 4 Don't forward to new page and don't change user_id field
     * 
     * @param int $task
     */
    public function save_order($task){
        // Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;

	// Load a Proputil model
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);
        
        $user_id = $this->userobj->userid;

        $order_id = $this->input->post('order_id');
        $company = trim($this->input->post('company_0'));
        $ponum =  trim($this->input->post('ponum'));
        $conum =  trim($this->input->post('conum'));
        $priority = $this->input->post('priority');
        $order_date = $this->get_lis_date();
        $status = $this->input->post('status');
        $status_date = $this->get_lis_date();
        $account = trim($this->input->post('account'));
        $s_expense = $this->trim_dollar_sign($this->input->post('sexpense'));
        $notes = $this->input->post('notes');
        $owner = $user_id;
        $maxitems = 10; // default number of items in an order

        // check to make sure that they required input are there
        if(empty($company) || empty($account)) {
          echo 'Error >> Missing Company or Account Information ...';
          return;
        }

        // used to see if to add a new company and account number
        $companies = $this->input->post('companies');
        $accounts = $this->input->post('accounts');

        if($companies == 'Enter Company') {
          $this->orderbook_model->save_company_name($company);
        }

        if($accounts == 'Enter Account') {
          $this->orderbook_model->save_account($account);
        }

        // add the default account for the company
        if(!empty($company) && !empty($account)) {
            $this->proputil_model->store_property($company, $account);
        }

        // change the status of the order depending on task number
        if($task == 2 && $status == 'saved') {
          $status = 'requested';
        }

        // connect to the database and add this now
        $data['company'] = $company;
        $data['ponum'] = $ponum;
        $data['conum'] = $conum;
        $data['priority'] = $priority;
        $data['account'] = $account;
        $data['status'] = $status;
        $data['status_date'] = $status_date;
        $data['s_expense'] = $s_expense;
        $data['notes'] = $notes;
        $data['user_id'] = $user_id;
        if (empty($order_id)) { // add new order
            $data['order_date'] = $order_date;
            $data['owner'] = $owner;
            $data['maxitems']= $maxitems;
            $order_id = $this->orderbook_model->add_order($data);
        } else if($task == 4) { // update but don't change userid so the person who ordered it is not changed
            $data['order_id'] = $order_id;
            $this->orderbook_model->update_order_keep_user($data);
        }
        else { // update an existing order
            $data['order_id'] = $order_id;
            $this->orderbook_model->update_order_change_user($data);
        }

        // get the maxitems count
        $maxitems = $this->orderbook_model->get_max_items_value($order_id);

        // now add the items
        $this->add_order_items($order_id, $company, $maxitems, $task);

        // see if to return at this point
        if($task == 3 || $task == 4) {
          return;
        }

        redirect("group/orderbook?order_id=$order_id");
    }
    
    /**
     * Removes an item from an order
     */
    public function remove_item() {
      $order_id = $this->input->post('order_id');

      // get the total expense
      $expense = $this->orderbook_model->get_total_expense($order_id);
      $g_expense = $expense['g_expense'];
      $p_expense = $expense['p_expense'];
      $maxitems = $expense['maxitems'];

      for($i = 1; $i <= $maxitems; $i++) {
        $item = trim($this->input->post('item_'.$i));
        if(!empty($item)) {
            $item = $this->orderbook_model->get_item($order_id,$i);
            $item_id = $item['item_id'];
            $amount = $item['amount'];
            $price = $item['price'];
            $owner = $item['owner'];
            
            // remove the cost of this item from the total
            if($owner == 'admin') {
              $g_expense -= $amount*$price;
            }
            else {
              $p_expense -= $amount*$price;
            }
            // remove the item now
            $this->orderbook_model->remove_item($item_id);
        }
      }

      // update the total now
      $this->orderbook_model->update_total_expense($g_expense,$p_expense,$order_id);

      redirect("group/orderbook?order_id=$order_id");
    }
    
    /**
     * Marks an item as received
     */
    public function item_received() {
      $userid = $this->userobj->userid;
      $order_id = $this->input->post('order_id');
      $status = 'received';
      $status_date = $this->get_lis_date();
      $maxitems = $this->orderbook_model->get_max_items_value($order_id);
      for($i = 1; $i <= $maxitems; $i++) {
        $item = trim($this->input->post('item_'.$i));
        if(!empty($item)) {
            $this->orderbook_model->update_item_status($status,$status_date,$order_id,$i);
            // update the database now
	    $lisdate = $this->get_lis_date();
            $this->orderbook_model->update_inventory_db($userid,$order_id, $i, $lisdate);
        }
      }
      redirect("group/orderbook?order_id=$order_id");
    }
    
    /**
     * Adds items to an order
     * 
     * @param type $order_id
     * @param type $order_company
     * @param type $maxitems
     * @param type $task
     */
    public function add_order_items($order_id, $order_company, $maxitems, $task) {
      $user_id = $this->userobj->userid;
      $owner = '';
      $g_expense = 0.0;
      $p_expense = 0.0;

      for($i = 1; $i <= $maxitems; $i++) {
        $item = trim($this->input->post('item_'.$i)); // not used here
        $type = trim($this->input->post('type_'.$i));
        $company = trim($this->input->post('company_'.$i));
        $product = trim($this->input->post('product_'.$i));
        $description = trim($this->input->post('description_'.$i));
        $amount = $this->input->post('amount_'.$i);
        $units = trim($this->input->post('units_'.$i));
        $price = $this->trim_dollar_sign($this->input->post('price_'.$i));
        $status = $this->input->post('status_'.$i);
        $status_date = $this->get_lis_date();

        $myitem = $this->input->post('myitem_'.$i);
        if(empty($myitem)) {
          $owner = 'myadmin';
        }
        else {
          $owner = $user_id;
        }

        // if company wasn't entered the set the company to that of the order
        if(empty($company)) {
          $company = $order_company;
        }

        // add to the database now
        if(!empty($product) &&  !empty($description) && !empty($units) && !empty($price)) {
          // now check the database to see if this item is already in there
          $count = $this->orderbook_model->check_item_existence($order_id,$i);

          $data['type'] = $type;
          $data['company'] = $company;
          $data['product'] = $product;
          $data['description'] = $description;
          $data['amount'] = $amount;
          $data['units'] = $units;
          $data['price'] = $price;
          $data['status'] = $status;
          $data['status_date'] = $status_date;
          $data['owner'] = $owner;
          $data['order_id'] = $order_id;
          $data['i'] = $i;
          if($count == 0) { // add new entry
                $data['stock_id'] = $stock_id;
                $data['user_id'] = $user_id;
                $this->orderbook_model->add_order_item($data);
          }
          else if($task == 4)  {// update but dont change userid so that the person who ordered this doesn't change
                $this->orderbook_model->update_order_item_keep_user($data);
          }
          else { // update an existing entry
            $this->orderbook_model->update_order_item_change_user($data);
          }

          if($owner == 'admin') {
            $g_expense += ($amount*$price);
          }
          else {
            $p_expense += ($amount*$price);
          }
        }
      }

        // update the totals for this order now
        $this->orderbook_model->update_order_totals($g_expense,$p_expense,$order_id);
    }
    
    /**
     * Loads the company names
     */
    protected function set_company_names() {
	$cnames = array('Aldrich', 'Fisher', 'VWR'); // load some default names

	$companies = $this->orderbook_model->get_companies();
	foreach($companies as $company) {
	    $cnames[] = $company['value'];
	}

	// create the string for the used by the javascript function
	$js_cnames = '';
	foreach($cnames as $cname) {
	    $js_cnames  .= '"'.$cname.'",';
	}
	// remove the last character "," from the js_cnames so that every thing works fine
	$js_cnames = substr($js_cnames,0,-1);

	$this->cnames = $cnames;
	$this->js_cnames = $js_cnames;
    }
    
    /**
     * Loads the order accounts
     */
    protected function set_accounts(){
	$accounts = array();
	$accountList = $this->orderbook_model->get_accounts();

	if(count($accountList) < 1) {
	    $accounts[] = 'None In List';
	} else {
	    foreach($accountList as $singleAccount) {
		$accounts[] = $singleAccount['value'];
	    }
	}
	$this->accounts = $accounts;
    }
    
    /**
     * Reads the privacy setting of the order book
     */
    protected function set_configuration() {

	$config = $this->orderbook_model->get_order_visibility();
	if(isset($config['value']) && ($config['value'] == 'yes')) {
	    $this->ordersPrivate = true;
	}

    }
    
    /**
     * Sets the company/account associative array for popuating accounts automatically
     * 
     * @param type $arrayName
     * @return string
     */
    public function get_default_accounts($arrayName) {
	$js_code = ''; // The javascrip code with the associative array
	//
	// Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
	// Load a Proputil model
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);

	foreach($this->cnames as $cname) {
	    $account = $this->proputil_model->get_property($cname);
	    if(!empty($account)) {
		$js_code .= $arrayName.'["'.$cname.'"] = "'.$account.'";';
	    }
	}

	return $js_code;
    }
 
    /**
     * Returns the names of months. 
     * 
     * Used by the orderbook module
     * 
     * @return array
     */
    public function get_months() {
      $months = array(1 => 'January', 'February', 'March', 'April', 'May',
                      'June', 'July', 'August', 'September', 'October', 
                      'November', 'December');
      return $months;
    }
    
    /**
     * Removes leading edge dollar signs from money values
     * 
     * @param type $money
     * @return type
     */
    public function trim_dollar_sign($money) {
      if(strstr($money, '$')) {
        $money  = substr($money, 1);
      }
      return $money;
    }
    
    /**
     * Increments the maximum item number by 5 or set to the maximum
     */
    public function increment_max_items_value() {
      $order_id = $this->input->post('order_id');

      $maxitems = $this->orderbook_model->get_max_items_value($order_id);
      $maxitems_new = $maxitems + 5;
      if($maxitems_new > $this->maxItemsMax) {
        $maxitems_new = $this->maxItemsMax;
      }
      $this->orderbook_model->set_max_items_value($order_id,$maxitems_new);

      redirect("group/orderbook?order_id=$order_id");
    }
    
    //--------------- FROM ex-Itellist Class -----------------------//
        
    /**
     * Saves the selected items to a list of it exist
     */
    public function save_items_to_list() {
        $user_id = $this->user->userid;                
        $order_id = $this->input->post('order_id');
        $logHTML = '';

        $maxItems = $this->orderbook_model->get_max_items_value($order_id);

        for($i = 1; $i <= $maxItems; $i++) {
          $item = trim($this->input->post('item_'.$i)); // see if this item is selected
          if(!empty($item)) {
            $array = $this->orderbook_model->get_item($order_id,$i);
            $item_id = $array['item_id'];
            $company = $array['company'];
            $description = $array['description'];

            // based on the company of this item see if a item list already exist. 
            // if one exist that you own it then add this item otherwise print and error 
            // saying that the no company item list exist to add this item to it
            $info = $this->orderbook_model->get_order_id_from_company($company,$user_id);
            if($info[0] != -1) { // check to make sure we have a valid order id
              $logHTML .= "<i>Saving \"$description\" to $company List (ID : ".$info[0].') ...';

              $item_num = $this->orderbook_model->find_next_item_num($info);
              if($item_num != -1) {
                  $data = array();
                  $data['order_id'] = $info[0];
                  $data['item_num'] = $item_num;
                  $data['type'] = $array['type'];
                  $data['company'] = $array['company'];
                  $data['product_id'] = $array['product_id'];
                  $data['description'] = $array['description'];
                  $data['units'] = $array['units'];
                  $data['price'] = $array['price'];
                  $data['user_id'] = $user_id;
                  $this->orderbook_model->add_list_item($data);
                $logHTML .= $item_num.' <span style="color: rgb(0, 153, 0);">done</span></i><br>';
              }
              else {
                $logHTML .=' <span style="color: rgb(240, 0, 0);">error : can\'t add any more items to this list</span><br>';
              }
            }
            else {
              $logHTML .= ' <span style="color: rgb(240, 0, 0);">error: "'.$company.'" list not found to store "'.$description.'"... please create one</span><br>';
            }
          }
        }
        $data0['logHTML'] = $logHTML;
        $this->load_view('group/orderbook/saveItemToListLog',$data0);
    }
    
    public function itemlist_process(){
	
	$task = $this->input->post('task2');
 
        if($task == 'save') {
          $this->save_itemlist();
        }
        else if($task == 'remove') {
          $this->remove_item();
        }
        else if($task == 'remove_order') {
          $this->itemlist_remove();
        }
        else {
          $data['error'] = 'Unknown task 2 : '.$task;
          $this->load_view('error/generic_error',$data);
        }
    }
    
    /**
     * Displays a form for adding an itemlist or saves this itemlist in case the
     * form has been submitted.
     */
    public function itemlist(){
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
	$order_id = $this->input->get('order_id'); // get the order id

	// set some variables
	$company = '';
	$priority = ''; // used to set the share with group variable
	$status = 'itemlist'; // specify that this order is an item list
	$status_date = $this->get_lis_date();
	$notes = 'none';
	$maxitems = 15;

	// if we have a saved order_id retrieved the order. item list are stored as regular orders
	$order = array();
	if(!empty($order_id)) {
	    $order = $this->orderbook_model->get_order($order_id);
	    $company = $order['company'];
	    $priority = $order['priority'];
	    $status = $order['status'];
	    $status_date = $order['status_date'];
	    $notes = $order['notes'];
	    $owner = $order['owner'];
	    $maxitems = $order['maxitems'];
	}

	$title = "Company Item List";
	if(!empty($company)) {
	    $title .= " ($company)";
	}

	if(!empty($order_id)) {
	    $data['page_title'] = 'Edit itemlist';
	} else {
	    $data['page_title'] = 'Add new itemlist';
	}
	$data['role'] = $role;
	$data['user_id'] = $user_id;
	$data['cnames'] = $this->cnames;
	$data['js_cnames'] = $this->js_cnames;
	$data['maxItemsMax'] = $this->maxItemsMax;

	$data['title'] = $title;
	$data['order_id'] = $order_id;
	$data['company'] = $company;
	$data['maxitems'] = $maxitems;
	$data['priority'] = $priority;
	$data['status_date'] = $status_date;
	$data['notes'] = $notes;
	$data['order'] = $order;

	$this->load_view('group/orderbook/add_itemlist',$data);
    }
    
    /**
     * Displays information about an itemlist.
     */
    public function itemlist_info(){
        $order_id = $this->input->get('order_id');
        $order = $this->orderbook_model->get_order($order_id);
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->load_users();
        
        $data['page_title'] = "Company Item List Info (Item List ID : $order_id)";
        $data['order_id'] = $order_id;
        $data['order'] = $order;
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $this->load_view('group/orderbook/itemlist_info',$data);
    }
   
    /**
     * Displays information about multiple itemlists.
     */
    public function itemlist_multiple_info(){
        $order_ids = $this->input->post('order_ids');
        $multiItemlistHTML = '';
        $data1['user_id'] = $this->userobj->userid;
	$data1['role'] = $this->userobj->role;
        $data1['users'] = $this->load_users();

        if(count($order_ids) > 0) {
          foreach($order_ids as $order_id) {
            $data1['order_id'] = $order_id;
            $data1['order'] = $this->orderbook_model->get_order($order_id);
            $multiItemlistHTML .= $this->load->view('group/orderbook/itemlist_info',$data1,TRUE);
            $multiItemlistHTML .= '<br>';
          }
        } else { // just display all of them
          $orders = $this->orderbook_model->get_item_lists('all');
          foreach($orders as $order_id => $order) {
            if($order_id == 'total') { 
              continue;
            }
            $data1['order_id'] = $order_id;
            $data1['order'] = $order;
            $multiItemlistHTML .= $this->load->view('group/orderbook/itemlist_info',$data1,TRUE);
            $multiItemlistHTML .= '<br>';
          }
        }
        
        $data['page_title'] = 'Multiple itemlist information';
        $data['multiItemlistHTML'] = $multiItemlistHTML;
        $this->load_view('group/orderbook/multiple_itemlist_view',$data);
    }
    
    /**
     * Removes an itemlist
     */
    public function itemlist_remove(){
    
        $order_id = $this->input->post('order_id');
        if(empty($order_id)) {
          $order_id = $this->input->get('order_id');
        }

        // remove the order
        $this->orderbook_model->remove_order($order_id);
        // now remove any items belonging to the order
        $this->orderbook_model->remove_order_items($order_id);

        redirect('group/orderbook/itemlist_all');
    }
    
    /**
     * Saves a new itemlist or updates an edited itemlist
     * 
     * @return type
     */
    protected function save_itemlist(){                                                                                            
        $user_id = $this->userobj->userid;

        $order_id = $this->input->post('order_id');
        $company = trim($this->input->post('company_0'));
        $ponum =  'n/a';
        $conum =  'n/a';
        $priority = $this->input->post('priority');
        $order_date = $this->get_lis_date();
        $status = 'itemlist';
        $status_date = $this->get_lis_date();
        $account = 'n/a';
        $notes = $this->input->post('notes');
        $owner = $user_id;
        $maxitems = trim($this->input->post('maxitems'));

        // check to make sure that they required input are there
        if(empty($company)) {
          $data['error'] = 'Error >> Missing Company ...';
          $data['page_title'] = 'Error';
          $this->load_view('errors/generic_error');
          return;
        }

        // reset the maxitems variable which is used to set the maximum number of items
        // also check to see if it a number. if it is not then set to 15
        if(is_numeric($maxitems)) {
          if($maxitems > $this->maxItemsMax) {
            $maxitems = $this->maxItemsMax;
          }
        } else {
          $maxitems = 15;
        }

        // used to see if to add a new company and account number
        $companies = $this->input->post('companies');

        if($companies == 'Enter Company') {
          $this->orderbook_model->save_company_name($company);
        }

        // connect to the database and add this now
        $data['company'] = $company;      $data['ponum'] = $ponum;
        $data['priority'] = $priority;    $data['conum'] = $conum;
        $data['account'] = $account;      $data['status'] = $status;
        $data['order_date'] = $order_date;
        $data['status_date'] = $status_date;
        $data['notes'] = $notes;          $data['owner'] = $owner;
        $data['user_id'] = $user_id;      $data['maxitems'] = $maxitems;
	$data['s_expense'] = 0;
        if(empty($order_id)) { // add new order
            // get the id for this entry
            $order_id = $this->orderbook_model->add_order($data);
        } else { // update an existing order
            $data['order_id'] = $order_id;
            $this->orderbook_model->update_order_change_user($data);
        }

        // now add the items
        $this->orderbook_model->add_order_items($order_id, $maxitems, $company);
        redirect("group/orderbook/itemlist?order_id=$order_id");
    }
    
    /**
     * Displays a list of all itemlists
     */
    public function itemlist_all(){
        $user_id = $this->userobj->userid;
        $role = $this->userobj->role;
        $userList = $this->load_users();
        
        $ordersArrray = $this->orderbook_model->get_itemlists('all');
        $itemlists = $ordersArrray['orders'];
        
        $data['page_title'] = 'Itemlists list';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $data['orders'] = $itemlists;
        $this->load_view('group/orderbook/all_itemlists',$data);
    }
}