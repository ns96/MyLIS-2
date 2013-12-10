<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
	
	$this->setCompanyNames();
	$this->setAccounts();
	$this->setConfiguration();
    }
 
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
	$status_date = getLISDate();
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
	    $order = $this->orderbook_model->getOrder($order_id);
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
	$data2['defaultAccounts'] = $this->getDefaultAccounts('defaultAccounts');
	$jsCode = $this->load->view('group/orderbook/jsCode',$data2,TRUE);
	
        $userList = $this->loadUsers();
        $pendingItems = $this->orderbook_model->getPendingItems($userList,$user_id,$role);
        $orderedItems = $this->orderbook_model->getOrderedItems($userList,$user_id,$role);
	$savedOrders = $this->orderbook_model->getSavedOrders($user_id);
        $recentOrders = $this->orderbook_model->getRecentOrders($user_id);
        $tallyAccounts = $this->orderbook_model->getTallyAccounts();
	
	
	
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
    
    public function items_pending(){
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->loadUsers();
        
        $data['page_title'] = 'Pending Items';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['items'] = $this->orderbook_model->getPendingItems($userList,$user_id,$role);
        $this->load_view('group/orderbook/pendingItems',$data);
    }
    
    public function items_ordered(){
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->loadUsers();
        
        $data['page_title'] = 'Ordered Items';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['items'] = $this->orderbook_model->getPendingItems($userList,$user_id,$role);
        $this->load_view('group/orderbook/pendingItems',$data);
    }
    
    public function orders_mine(){
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->loadUsers();
        
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
        $data['months'] = $this->getMonths();
        $orderData = $this->orderbook_model->getOrders($ordered_by, $year, $month);
        $data['orders'] = $orderData['orders'];
        $data['tally_totals'] = $orderData['tally_totals'];
        $this->load_view('group/orderbook/listOrders',$data);
    }
    
    public function orders_all(){
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->loadUsers();

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
        $data['months'] = $this->getMonths();
        $orderData = $this->orderbook_model->getOrders($ordered_by, $year, $month);
        $data['orders'] = $orderData['orders'];
        $data['tally_totals'] = $orderData['tally_totals'];
        //$this->load_view('group/orderbook/allOrders',$data);
        $this->load_view('group/orderbook/listOrders',$data);

        
    }
    
    public function order_info(){
        $order_id = $this->input->get('order_id');
        $order = $this->orderbook_model->getOrder($order_id);
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->loadUsers();

        $data['page_title'] = 'Order Information';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $data['order'] = $order;
        $data['order_id'] = $order_id;
        $this->load_view('group/orderbook/orderInfo',$data);
    }
    
    public function order_multiple_info(){
        $order_ids = $this->input->post('order_ids');                                                                                              
        $multipleOrderHTML = '';
        
        $data1['user_id'] = $this->userobj->userid;
	$data1['role'] = $this->userobj->role;
        $data1['users'] = $this->loadUsers();
        
        if(count($order_ids > 0)) {
          foreach($order_ids as $order_id) {
            if(strstr($order_id, '_')) { // check to see if item is attached to the order id
              $sa = split('_', $order_id);
              $order_id = $sa[0];
            }
            
            $order = $this->orderbook_model->getOrder($order_id);
            $data1['order'] = $order;
            $data1['order_id'] = $order_id;
            $multipleOrderHTML .= $this->load->view('group/orderbook/orderInfo',$data1,TRUE);
            $multipleOrderHTML .= '<br><br>';
          }
        }
        
        $data['page_title'] = 'Multiple order information';
        $data['multipleOrderHTML'] = $multipleOrderHTML;
        $this->load_view('group/orderbook/multipleOrderView',$data);
    }
    
    public function order_delete(){
        $order_id = $this->input->post('order_id');
        if(empty($order_id)) {
          $order_id = $this->input->get('order_id');
        }

        // remove the order
        $this->orderbook_model->removeOrder($order_id);
        // now remove any items belonging to the order
        $this->orderbook_model->removeOrderItems($order_id);

        redirect('group/orderbook');
    }
    
    public function order_process(){
        $task = $this->input->post('task2');
 
        if($task == 'save') {
          $this->saveOrder(1);
        }
        else if($task == 'remove') {
          $this->removeItem();
        }
        else if($task == 'remove_order') {
          $this->removeOrder();
        }
        else if($task == 'submit') {
          $this->saveOrder(2);
        }
        else if($task == 'item_save') {
          $this->saveOrder(4);
          $this->saveItemsToList();
        }
        else if($task == 'item_received') {
          $this->itemReceived();
        }
        else if($task == 'increment') {
          $this->saveOrder(3); // save the order first just incase a user entered any more information
          $this->incrementMaxItemsValue();
        }
        else {
          $data['error'] = 'Unknown task 2 : '.$task;
          $this->load_view('error/generic_error',$data);
        }
    }
    
    public function search(){
        $userid = $this->userobj->userid;
        $userList = $this->loadUsers();

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
    
    // function to save an order
    // $task = 1 Save only
    // $task = 2 Save and mark for odering
    // $task = 3 Don't forward to new page just save and return
    // $task = 4 Don't forward to new page and don't change user_id field
    public function saveOrder($task){
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
        $order_date = getLISDate();
        $status = $this->input->post('status');
        $status_date = getLISDate();
        $account = trim($this->input->post('account'));
        $s_expense = $this->trimDollarSign($this->input->post('sexpense'));
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
          $this->orderbook_model->saveCompanyName($company);
        }

        if($accounts == 'Enter Account') {
          $this->orderbook_model->saveAccount($account);
        }

        // add the default account for the company
        if(!empty($company) && !empty($account)) {
            $this->proputil_model->storeProperty($company, $account);
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
            $order_id = $this->orderbook_model->addOrder($data);
        } else if($task == 4) { // update but don't change userid so the person who ordered it is not changed
            $data['order_id'] = $order_id;
            $this->orderbook_model->updateOrderKeepUser($data);
        }
        else { // update an existing order
            $data['order_id'] = $order_id;
            $this->orderbook_model->updateOrderChangeUser($data);
        }

        // get the maxitems count
        $maxitems = $this->orderbook_model->getMaxItemsValue($order_id);

        // now add the items
        $this->orderbook_model->addOrderItems($order_id, $company, $maxitems, $task);

        // see if to return at this point
        if($task == 3 || $task == 4) {
          return;
        }

        redirect("group/orderbook?order_id=$order_id");
    }
    
    // Method to remove an item from an order
    public function removeItem() {
      $order_id = $this->input->post('order_id');

      // get the total expense
      $expense = $this->orderbook_model->getTotalExpense($order_id);
      $g_expense = $expense['g_expense'];
      $p_expense = $expense['p_expense'];
      $maxitems = $expense['maxitems'];

      for($i = 1; $i <= $maxitems; $i++) {
        $item = trim($this->input->post('item_'.$i));
        if(!empty($item)) {
            $item = $this->orderbook_model->getItem($order_id,$i);
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
            $this->orderbook_model->removeItem($item_id);
        }
      }

      // update the total now
      $this->orderbook_model->updateTotalExpense($g_expense,$p_expense,$order_id);

      redirect("group/orderbook?order_id=$order_id");
    }
    
    // Method to mark an item as received
    public function itemReceived() {
      $userid = $this->userobj->userid;
      $order_id = $this->input->post('order_id');
      $status = 'received';
      $status_date = getLISDate();
      $maxitems = $this->orderbook_model->getMaxItemsValue($order_id);

      for($i = 1; $i <= $maxitems; $i++) {
        $item = trim($this->input->post('item_'.$i));
        if(!empty($item)) {
            $this->orderbook_model->updateItemStatus($status,$status_date,$order_id,$i);
            // update the database now
            $this->orderbook_model->updateInventoryDB($userid,$order_id, $i);
        }
      }
      redirect('group/orderbook?order_id=$order_id');
    }
    
    // function to add the items
    public function addOrderItems($order_id, $order_company, $maxitems, $task) {
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
        $price = $this->trimDollarSign($this->input->post('price_'.$i));
        $status = $this->input->post('status_'.$i);
        $status_date = getLISDate();

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
          $count = $this->orderbook_model->checkItemExistence($order_id,$i);

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
                $this->orderbook_model->addOrderItem($data);
          }
          else if($task == 4)  {// update but dont change userid so that the person who ordered this doesn't change
                $this->orderbook_model->updateOrderItemKeepUser($data);
          }
          else { // update an existing entry
            $this->orderbook_model->updateOrderItemChangeUser($data);
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
        $this->orderbook_model->updateOrderTotals($g_expense,$p_expense,$order_id);
    }
    
    // get company names
    protected function setCompanyNames() {
	$cnames = array('Aldrich', 'Fisher', 'VWR'); // load some default names

	$companies = $this->orderbook_model->getCompanies();
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
    
    protected function setAccounts(){
	$accounts = array();
	$accountList = $this->orderbook_model->getAccounts();

	if(count($accountList) < 1) {
	    $accounts[] = 'None In List';
	} else {
	    foreach($accountList as $singleAccount) {
		$accounts[] = $singleAccount['value'];
	    }
	}
	$this->accounts = $accounts;
    }
    
    // function to set the configuration of the order book
    protected function setConfiguration() {

	$config = $this->orderbook_model->getOrderVisibility();
	if(isset($config['value']) && ($config['value'] == 'yes')) {
	    $this->ordersPrivate = true;
	}

    }
    
    // function to set the company/account associative array for popuating accounts automatically
    public function getDefaultAccounts($arrayName) {
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
	    $account = $this->proputil_model->getProperty($cname);
	    if(!empty($account)) {
		$js_code .= $arrayName.'["'.$cname.'"] = "'.$account.'";';
	    }
	}

	return $js_code;
    }
 
    // function to return the names of months. used the the orderbook module
    public function getMonths() {
      $months = array(1 => 'January', 'February', 'March', 'April', 'May',
                      'June', 'July', 'August', 'September', 'October', 
                      'November', 'December');
      return $months;
    }
    
    // function to remove leading edge dollay signs from money values
    public function trimDollarSign($money) {
      if(strstr($money, '$')) {
        $money  = substr($money, 1);
      }
      return $money;
    }
    
    // function to increment the maxium item number by 5 or set to the maximum
    public function incrementMaxItemsValue() {
      $order_id = $this->input->post('order_id');

      $maxitems = $this->orderbook_model->getMaxItemsValue($order_id);
      $maxitems_new = $maxitems + 5;
      if($maxitems_new > $this->maxItemsMax) {
        $maxitems_new = $this->maxItemsMax;
      }
      $this->orderbook_model->setMaxItemsValue($order_id,$maxitems_new);

      redirect("group/orderbook?order_id=$order_id");
    }
    
    //--------------- FROM ex-Itellist Class -----------------------//
    //
    // function to save the selected items to a list of it exist
    public function saveItemsToList() {
        $user_id = $this->user->userid;                
        $order_id = $this->input->post('order_id');
        $logHTML = '';

        $maxItems = $this->orderbook_model->getMaxItemsValue($order_id);

        for($i = 1; $i <= $maxItems; $i++) {
          $item = trim($this->input->post('item_'.$i)); // see if this item is selected
          if(!empty($item)) {
            $array = $this->orderbook_model->getItem($order_id,$i);
            $item_id = $array['item_id'];
            $company = $array['company'];
            $description = $array['description'];

            // based on the company of this item see if a item list already exist. 
            // if one exist that you own it then add this item otherwise print and error 
            // saying that the no company item list exist to add this item to it
            $info = $this->orderbook_model->getOrderIDFromCompany($company,$user_id);
            if($info[0] != -1) { // check to make sure we have a valid order id
              $logHTML .= "<i>Saving \"$description\" to $company List (ID : ".$info[0].') ...';

              $item_num = $this->orderbook_model->findNextItemNum($info);
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
                  $this->orderbook_model->addListItem($data);
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
        $this->load_view('errors/generic_error',$data0);
    }
    
    public function itemlist(){
        
        if (isset($_POST['save_itemlist_form'])){
            $this->save_itemlist();
        } else {
            $user_id = $this->userobj->userid;
            $role = $this->userobj->role;
            $order_id = $this->input->get('order_id'); // get the order id

            // set some variables
            $company = '';
            $priority = ''; // used to set the share with group variable
            $status = 'itemlist'; // specify that this order is an item list
            $status_date = getLISDate();
            $notes = 'none';
            $maxitems = 15;

            // if we have a saved order_id retrieved the order. itme list are stored as regular orders
            $order = array();
            if(!empty($order_id)) {
              $order = $this->orderbook_model->getOrder($order_id);
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

            $data['page_title'] = 'Add new itemlist';
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
    }
    
    public function itemlist_info(){
        $order_id = $this->input->get('order_id');
        $order = $this->orderbook_model->getOrder($order_id);
        
        $user_id = $this->userobj->userid;
	$role = $this->userobj->role;
        $userList = $this->loadUsers();
        
        $data['page_title'] = "Company Item List Info (Item List ID : $order_id)";
        $data['order_id'] = $order_id;
        $data['order'] = $order;
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $this->load_view('group/orderbook/itemlistInfo',$data);
    }
   
    public function itemlist_multiple_info(){
        $order_ids = $this->input->post('order_ids');
        $multiItemlistHTML = '';
        $data1['user_id'] = $this->userobj->userid;
	$data1['role'] = $this->userobj->role;
        $data1['users'] = $this->loadUsers();

        if(count($order_ids) > 0) {
          foreach($order_ids as $order_id) {
            $data1['order_id'] = $order_id;
            $data1['order'] = $this->orderbook_model->getOrder($order_id);
            $multiItemlistHTML .= $this->load->view('group/orderbook/itemlistInfo',$data1,TRUE);
            $multiItemlistHTML .= '<br>';
          }
        } else { // just display all of them
          $orders = $this->orderbook_model->getItemLists('all');
          foreach($orders as $order_id => $order) {
            if($order_id == 'total') { 
              continue;
            }
            $data1['order_id'] = $order_id;
            $data1['order'] = $order;
            $multiItemlistHTML .= $this->load->view('group/orderbook/itemlistInfo',$data1,TRUE);
            $multiItemlistHTML .= '<br>';
          }
        }
        
        $data['page_title'] = 'Multiple itemlist information';
        $data['multiItemlistHTML'] = $multiItemlistHTML;
        $this->load_view('group/orderbook/multipleItemlistView',$data);
    }
    
    public function itemlist_remove(){
    
        $order_id = $this->input->post('order_id');
        if(empty($order_id)) {
          $order_id = $this->input->get('order_id');
        }

        // remove the order
        $this->orderbook_model->removeOrder($order_id);
        // now remove any items belonging to the order
        $this->orderbook_model->removeOrderItems($order_id);

        redirect('group/orderbook/itemlist_all');
    }
    
    // this method is called to save a new itemlist or an edited itemlist
    protected function save_itemlist(){                                                                                            
        $user_id = $this->userobj->userid;

        $order_id = $this->input->post('order_id');
        $company = trim($this->input->post('company_0'));
        $ponum =  'n/a';
        $conum =  'n/a';
        $priority = $this->input->post('priority');
        $order_date = getLISDate();
        $status = 'itemlist';
        $status_date = getLISDate();
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

        // reset the maxitems variable which is used to set the maxium number of items
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
          $this->orderbook_model->saveCompanyName($company);
        }

        // connect to the database and add this now
        $data['company'] = $company;      $data['ponum'] = $ponum;
        $data['priority'] = $priority;    $data['conum'] = $conum;
        $data['account'] = $account;      $data['status'] = $status;
        $data['order_date'] = $order_date;
        $data['status_date'] = $status_date;
        $data['notes'] = $notes;          $data['owner'] = $owner;
        $data['user_id'] = $user_id;      $data['maxitems'] = $maxitems;
        if(empty($order_id)) { // add new order
            // get the id for this entry
            $order_id = $this->orderbook_model->addOrder($data);
        } else { // update an existing order
            $data['order_id'] = $order_id;
            $this->orderbook_model->updateOrderChangeUser($data);
        }

        // now add the items
        $this->orderbook_model->addOrderItems($order_id, $maxitems, $company);
        redirect("group/orderbook/itemlist?order_id=$order_id");
    }
    
    public function itemlist_all(){
        $user_id = $this->userobj->userid;
        $role = $this->userobj->role;
        $userList = $this->loadUsers();
        
        $ordersArrray = $this->orderbook_model->getItemlists('all');
        $itemlists = $ordersArrray['orders'];
        
        $data['page_title'] = 'Itemlists list';
        $data['user_id'] = $user_id;
        $data['role'] = $role;
        $data['users'] = $userList;
        $data['orders'] = $itemlists;
        $this->load_view('group/orderbook/allItemlists',$data);
    }
}