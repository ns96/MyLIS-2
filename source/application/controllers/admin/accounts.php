<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends Admin_Controller {
    
    private $userobj = null;
    private $gtypes = null;
    private $disciplines = null;
    private $remove_code = 'XFG37'; // must be entered for account to be deleted
    private $version = '1.0';
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	
	$this->gtypes = array('Select One', 'Academia', 'Government', 'Research Instititue', 'Company');

	$this->disciplines = array('Select One', 'Astronomy', 'Atmospheric Science', 
	'Biochemistry', 'Biological Sciences', 'Biotechnology', 'Chemistry', 
	'Computer Science', 'Electrical Engineering', 'Material Science', 'Mathematics', 
	'Mechanical Engineering', 'Nanotechology', 'Pharmacology', 'Physics');
	
	$params['properties'] = $this->properties;
	$params['user'] = $this->userobj;
	$this->load->model('admin_filemanager');
	$this->admin_filemanager->initialize($params);
	
	$this->load->model('account_model');
	$this->restrict_access();
    }
    
    public function index(){
	
	$accountList = $this->account_model->getAllAccounts();
	
	$data['page_title'] = 'MyLIS Accounts';
	$data['accountList'] = $accountList;
	$data['gtypes'] = $this->gtypes;
	$data['disciplines'] = $this->disciplines;
	$data['no_acct'] = $this->input->get('no_acct');
	$data['mtype'] = $this->input->get('mtype');
	$data['message'] = $this->input->get('message');
	$this->load_view('admin/accounts/main',$data);
    }
    
    public function view($account_id){

	if(!empty($account_id) && $this->account_model->accountExists($account_id)) {
	    $data['page_title'] = "MyLIS Account ($account_id)";
	    $data['account_id'] = $account_id;
	    $accountInfo = $this->account_model->getAccountInfo($account_id);
	    
	    $manager_id = $accountInfo['manager_id'];
	    $userList = $this->loadUsers();
	    $managerInfo = $userList[$manager_id];
	    
	    $data['accountInfo'] = $accountInfo;
	    $data['accountUsers'] = $this->account_model->getAccountUsers($account_id);
	    $data['managerInfo'] = $managerInfo;
	    $data['accountProfile'] = $this->account_model->getAccountProfile($account_id);
	    $this->load_view('admin/accounts/account_info',$data);
	} else {
	    redirect('admin/accounts');
	}
    }
    
    public function create($template=null){
	
	if ($template == 'test'){
	    $this->create_test_account();
	    return;
	} elseif ($template == 'sandbox'){
	    $this->create_sandbox_account();
	    return;
	}
	
	if (isset($_POST['add_account_form'])){
	    if($this->checkFormInput('add')) {
		$fname = addslashes(trim($this->input->post('fname'))); // PI first name
		$mi = trim($this->input->post('mi')); // PI middle name
		$lname = addslashes(trim($this->input->post('lname'))); // PI last name
		$group_name = addslashes(ucfirst(trim($this->input->post('group_name')))); // group name
		//$group_name .= ' Group'; // add group to the group name
		$group_type = $this->input->post('group_type'); // group type or location
		$discipline = $this->input->post('discipline'); // discipline
		$institution = trim($this->input->post('institution_name')); // name of institution
		$address = trim($this->input->post('address')); // institution adress
		$phone = trim($this->input->post('phone'));
		$fax = trim($this->input->post('fax'));
		$email = trim($this->input->post('email'));
		$password1 = trim($this->input->post('password1')); // this password should be the same
		$password2 = trim($this->input->post('password2'));
		$keywords = trim($this->input->post('keywords')); // research keywords
		$description = addslashes(trim($this->input->post('description'))); // research keywords
		$piurl = trim($this->input->post('piurl')); // the group web page of the PI
		$piurl = $this->checkURL($piurl);

		$term = $this->input->post('term');
		$cost = $this->input->post('cost');
		$activate_date = $this->input->post('activate_date');
		$status = $this->input->post('status');
		$expire_date = getExpireDate($activate_date, $status, $term); // automatically set
		$storage = $this->input->post('storage');
		$max_users = $this->input->post('max_users');
		$time_zone = $this->input->post('time_zone');
		$notes = addslashes($this->input->post('notes'));
		$group_pi = "$fname $lname";
		
		if(!empty($mi)) {
		    $group_pi = "$fname $mi. $lname";
		}
		$name = $this->user->name;
		$department_id = ''; // blank on purpose
		$network_ids = ''; // blank on purpose
		$manager_id = $this->user->userid;
		$activate_code = $this->getActivateCode();

		if(!empty($notes)) {
		    $notes = $name.' ('.getLISDateTime().") >>Account Created\n$notes\n";
		}
		else {
		    $notes = $name.' ('.getLISDateTime().") >>Account Created\n";
		}

		$account_id = $this->getAccountID($lname); // get the account id

		$data['account_id'] = $account_id;
		$data['fname'] = $fname;		    $data['term'] = $term;
		$data['mi'] = $mi;			    $data['cost'] = $cost;
		$data['lname'] = $lname;		    $data['activate_date'] = $activate_date;
		$data['group_name'] = $group_name;	    $data['expire_date'] = $expire_date;
		$data['group_type'] = $group_type;	    $data['status'] = $status;
		$data['discipline'] = $discipline;	    $data['storage'] = $storage;
		$data['institution'] = $institution;	    $data['max_users'] = $max_users;
		$data['address'] = $address;		    $data['time_zone'] = $time_zone;
		$data['phone'] = $phone;		    $data['activate_code'] = $activate_code;
		$data['fax'] = $fax;			    $data['notes'] = $notes;
		$data['email'] = $email;		    $data['department_id'] = $department_id;
		$data['network_ids'] = $network_ids;	    $data['manager_id'] = $manager_id;
		$data['password1'] = $password1;	    $data['group_pi'] = $group_pi;
		$data['keywords'] = $keywords;		    $data['description'] = $description;
		$data['piurl'] = $piurl;
		
		$this->account_model->add_account($data);
		
		// create the tables associated wthis account
		$this->load->model('managedb_model');
		$this->managedb_model->createMyLISTables($account_id);

		// now add the username and password for login purposes
		$this->account_model->add_account_admin($data);

		// add default and location into account database categories
		$this->account_model->setDefaultDatabaseEntries($account_id);

		// set the version number
		$this->account_model->setVersionNumber($account_id,$this->version);

		// now create a directory for this account
		$props = array(
		    'lis.account' => $account_id,
		    'lis.expire' => $expire_date,
		    'lis.status' => $status,
		    'timezone' => $time_zone,
		    'storage.quota' => $storage,
		    'max.users' => $max_users,
		    'group.name' => $group_name,
		    'site.manager' => $group_pi,
		    'site.manager.email' => $email
		);

		$this->admin_filemanager->createMyLISDirectory($account_id, $props);

		// email the user that the account has been created
		$login_url = getBaseURL().'accounts/mylis_'.$account_id.'/index.php';
		$account_info = array($account_id, $group_pi, $email, $password1, 
		$activate_code, $login_url);
		$this->sendEmail($account_info);

		// print the ok page account page
		$data['page_title'] = 'System Message!';
		$data['account_id'] = $account_id;
		$this->load_view('admin/accounts/account_created',$data);
	    }
	} else {
	    $data['page_title'] = "Add MyLIS Account";
	    $data['gtypes'] = $this->gtypes;
	    $data['disciplines'] = $this->disciplines;
	    $data['properties'] = $this->properties;
	    $data['lis_tz'] = $this->lis_tz;
	    $this->load_view('admin/accounts/add',$data);
	}
    }
    
    protected function create_test_account() {
	$fname = 'Bob'; // PI first name
	$mi = 'J'; // PI middle name
	$lname = 'Hane'; // PI last name
	$group_name = 'Hane'; // group name
	$group_type = 'Academia'; // group type or location
	$discipline = 'Chemistry'; // discipline
	$institution = 'Nano'; // name of institution
	$address = "Nano University\nDepartment of Chemistry\n1000 Nano Drive, NY, New York, 10031"; // institution adress
	$phone = '1111-555-5555';
	$fax = '1111-555-5555';
	$email = 'hane@nano.edu';
	$password1 = 'sandbox'; // this password should be the same
	$password2 = 'sandbox';
	$keywords = 'unix,materials, photons, laser, organic dyes'; // research keywords
	$description = "Some Cool\nEnvironmentally Friendly Research"; // research keywords
	$piurl = 'www.cnn.com'; // the group web page of the PI
	$piurl = checkURL($piurl);

	$term = 4;
	$cost = 0;
	$activate_date = getLISDate();
	$status = 'trial';
	$expire_date = getExpireDate($activate_date, $status, $term); // automatically set
	$storage = 50;
	$max_users = '25';
	$time_zone = 'UTC';
	$notes = 'Test User Account';
	$group_pi = "$fname $mi. $lname";
	$name = $this->userobj->name;
	$department_id = ''; // blank on purpose
	$manager_id = $this->userobj->userid;
	$activate_code = $this->getActivateCode();

	if(!empty($notes)) {
	    $notes = $name.' ('.getLISDateTime().") >>Account Created\n$notes\n";
	}
	else {
	    $notes = $name.' ('.getLISDateTime().") >>Account Created\n";
	}

	$this->load->model('managedb_model');

	// if this account already exist remove it
	$account_id = strtolower($lname).'1';
	if($this->account_model->accountExists($account_id)) {
	    $dir = $this->admin_filemanager->moveToTrash($account_id);
	    // dump and then remove tables from LISDB
	    $this->managedb_model->removeMyLISTables($account_id);
	    $this->managedb_model->removeMyLISUsers($account_id);
	}

	$data['account_id'] = $account_id;
	$data['fname'] = $fname;		    $data['term'] = $term;
	$data['mi'] = $mi;			    $data['cost'] = $cost;
	$data['lname'] = $lname;		    $data['activate_date'] = $activate_date;
	$data['group_name'] = $group_name;	    $data['expire_date'] = $expire_date;
	$data['group_type'] = $group_type;	    $data['status'] = $status;
	$data['discipline'] = $discipline;	    $data['storage'] = $storage;
	$data['institution'] = $institution;	    $data['max_users'] = $max_users;
	$data['address'] = $address;		    $data['time_zone'] = $time_zone;
	$data['phone'] = $phone;		    $data['activate_code'] = $activate_code;
	$data['fax'] = $fax;			    $data['notes'] = $notes;
	$data['email'] = $email;		    $data['department_id'] = $department_id;
	$data['network_ids'] = $network_ids;	    $data['manager_id'] = $manager_id;
	$data['password1'] = $password1;	    $data['group_pi'] = $group_pi;
	$data['keywords'] = $keywords;		    $data['description'] = $description;
	$data['piurl'] = $piurl;

	$this->account_model->add_account($data);

	// create the tables associated wthis account
	$this->managedb_model->createMyLISTables($account_id);

	// now add the username and password for login purposes
	$this->account_model->add_account_admin($data);

	// add default and location into account database categories
	$this->account_model->setDefaultDatabaseEntries($account_id);

	// set the version number
	$this->account_model->setVersionNumber($account_id,$this->version);

	// now create a directory for this account
	$props = array(
	    'lis.account' => $account_id,
	    'lis.expire' => $expire_date,
	    'lis.status' => $status,
	    'timezone' => $time_zone,
	    'storage.quota' => $storage,
	    'max.users' => $max_users,
	    'group.name' => $group_name,
	    'site.manager' => $group_pi,
	    'site.manager.email' => $email
	);

	$this->admin_filemanager->createMyLISDirectory($account_id, $props);
	
	$title = 'New Account Created!';
	$message = 'Test account has been created successfully!';
	$destination = base_url().'admin/accounts';
	showModal($title,$message,$destination);
    }
    
    protected function create_sandbox_account(){
	$fname = 'John'; // PI first name
	$mi = 'H'; // PI middle name
	$lname = 'Smith'; // PI last name
	$group_name = 'Smith'; // group name
	$group_type = 'Academia'; // group type or location
	$discipline = 'Chemistry'; // discipline
	$institution = 'Nano University'; // name of institution
	$address = "Nano University\nDepartment of Chemistry\n1000 Nano Drive, NY, New York, 10031"; // institution adress
	$phone = '1111-555-5555';
	$fax = '1111-555-5555';
	$email = 'jhsmith@nano.edu';
	$password1 = 'sandbox'; // this password should be the same
	$password2 = 'sandbox';
	$keywords = 'software,materials, photons, laser, bio materials, sensors'; // research keywords
	$description = "Some Cool\nEnvironmentally Friendly Research"; // research keywords
	$piurl = 'www.nano.edu/jhsmith'; // the group web page of the PI
	$piurl = checkURL($piurl);

	$term = 4;
	$cost = 0;
	$activate_date = getLISDate();
	$status = 'active';
	$expire_date = getExpireDate($activate_date, $status, $term); // automatically set
	$storage = 50;
	$max_users = '25';
	$time_zone = 'UTC';
	$notes = 'Sandbox User Account';
	$group_pi = "$fname $mi. $lname";
	$name = $this->user->name;
	$department_id = ''; // blank on purpose
	$manager_id = $this->user->userid;
	$activate_code = $this->getActivateCode();
	$login_count = 0;

	if(!empty($notes)) {
	    $notes = $name.' ('.getLISDateTime().") >>Account Created\n$notes\n";
	}
	else {
	    $notes = $name.' ('.getLISDateTime().") >>Account Created\n";
	}

	$this->load->model('managedb_model');

	// if this account already exist remove it
	$account_id = strtolower($lname).'1';
	if($this->account_model->accountExists($account_id)) {
	    $dir = $this->admin_filemanager->moveToTrash($account_id);
	    $login_count = $this->managedb_model->getMyLISProperty($account_id, 'login.count');
	    // dump and then remove tables from LISDB
	    $this->managedb_model->removeMyLISTables($account_id);
	    $this->managedb_model->removeMyLISUsers($account_id);
	} else {
	    $login_count = 0;
	}

	$data['account_id'] = $account_id;
	$data['fname'] = $fname;		    $data['term'] = $term;
	$data['mi'] = $mi;			    $data['cost'] = $cost;
	$data['lname'] = $lname;		    $data['activate_date'] = $activate_date;
	$data['group_name'] = $group_name;	    $data['expire_date'] = $expire_date;
	$data['group_type'] = $group_type;	    $data['status'] = $status;
	$data['discipline'] = $discipline;	    $data['storage'] = $storage;
	$data['institution'] = $institution;	    $data['max_users'] = $max_users;
	$data['address'] = $address;		    $data['time_zone'] = $time_zone;
	$data['phone'] = $phone;		    $data['activate_code'] = $activate_code;
	$data['fax'] = $fax;			    $data['notes'] = $notes;
	$data['email'] = $email;		    $data['department_id'] = $department_id;
	$data['network_ids'] = $network_ids;	    $data['manager_id'] = $manager_id;
	$data['password1'] = $password1;	    $data['group_pi'] = $group_pi;
	$data['keywords'] = $keywords;		    $data['description'] = $description;
	$data['piurl'] = $piurl;

	$this->account_model->add_account($data);

	// create the tables associated wthis account
	$this->managedb_model->createMyLISTables($account_id);

	// now add the username and password for login purposes
	$this->account_model->add_account_admin($data);

	// add default and location into account database categories
	$this->account_model->setDefaultDatabaseEntries($account_id);

	// set the version number
	$this->account_model->setVersionNumber($account_id,$this->version);

	// set the login count now
	$this->account_model->set_login_count($account_id,$login_count);

	// add the dummy information to this account
	$this->load->model('datasource_model');
	$this->datasource_model->addSandboxData($account_id);

	// now create a directory for this account
	$props = array(
	    'lis.account' => $account_id,
	    'lis.expire' => $expire_date,
	    'lis.status' => 'demo',
	    'timezone' => $time_zone,
	    'storage.quota' => $storage,
	    'max.users' => $max_users,
	    'group.name' => $group_name,
	    'site.manager' => $group_pi,
	    'site.manager.email' => $email
	);

	$this->admin_filemanager->createMyLISDirectory($account_id, $props);

	$title = 'New Account Created!';
	$message = 'Sandbox account has been created successfully!';
	$destination = base_url().'admin/accounts';
	showModal($title,$message,$destination);
    }
    
    public function edit($account_id){
	
	if(!empty($account_id) && $this->account_model->accountExists($account_id)) {
	    if (isset($_POST['account_edit_form'])){
		// check that the user as the rights to edit this account
		$userid = $this->userobj->userid;
		$role = $this->userobj->role;
		$name = $this->userobj->name;
		if($role != 'admin') {
		    $data['error'] = '<br><br><h1 style="text-align: center;">Sorry, '.$name.'
		    <br>You Don\'t Have The Right to Modify This Account.</h1>';
		    $data['page_title'] = 'Edit Error';
		    $this->load_view('errors/generic_error',$data);
		    return;
		}

		// now make the 
		if($this->checkFormInput('edit')) {
		    $data['account_id'] = $this->input->post('account_id');
		    $data['fname'] = addslashes(trim($this->input->post('fname'))); // PI first name
		    $data['mi'] = trim($this->input->post('mi')); // PI middle name
		    $data['lname'] = addslashes(trim($this->input->post('lname'))); // PI last name
		    $data['group_name'] = addslashes(ucfirst(trim($this->input->post('group_name')))); // group name
		    $data['group_type'] = $this->input->post('group_type'); // group type or location
		    $data['discipline'] = $this->input->post('discipline'); // discipline
		    $data['institution'] = trim($this->input->post('institution_name')); // name of institution
		    $data['address'] = trim($this->input->post('address')); // institution adress
		    $data['phone'] = trim($this->input->post('phone'));
		    $data['fax'] = trim($this->input->post('fax'));
		    $data['email'] = trim($this->input->post('email'));
		    $data['public'] = trim($this->input->post('public'));
		    $data['keywords'] = trim($this->input->post('keywords')); // research keywords
		    $data['description'] = addslashes(trim($this->input->post('description'))); // research keywords
		    $piurl = trim($this->input->post('piurl')); // the group web page of the PI
		    $data['collaborator_ids'] = $this->input->post('collaborators'); // list of collaborators
		    $data['instruments'] = addslashes($this->input->post('instruments'));
		    $data['piurl'] = checkURL($piurl);

		    $data['cost'] = $this->input->post('cost');
		    $data['status'] = $this->input->post('status');
		    $data['expire_date'] = $this->input->post('expire_date'); // must check the format of this in checkform 
		    $data['storage'] = $this->input->post('storage');
		    $data['max_users'] = $this->input->post('max_users');
		    $data['time_zone'] = $this->input->post('time_zone');
		    $notes = addslashes($this->input->post('notes'));
		    $new_note = $this->input->post('new_note');
		    $pi_name = "$data[fname] $data[lname]";
		    if(!empty($data['mi'])) {
			$data['pi_name'] = "$data[fname] $data[mi]. $data[lname]";
		    } else {
			$data['pi_name'] = $pi_name;
		    }
		    $data['manager_id'] = $this->input->post('manager_id');
		    $data['activate_code'] = $this->input->post('activate_code');

		    if(!empty($new_note)) {
			$data['notes'] .= $name.' ('.getLISDateTime().") >>\n$new_note\n";
		    } else {
			$data['notes'] = $notes;
		    }

		    $this->account_model->update_account_info_by_admin($data);

		    // update the account initiation file
		    $props = array(
			'lis.expire' => $data['expire_date'],
			'lis.status' => $data['status'],
			'lis.timezone' => $data['time_zone'],
			'lis.storage' => $data['storage'],
			'max.users' => $data['max_users'],
		    );
		    
		    $this->admin_filemanager->modifyInitiationFile($data['account_id'], $props);

		    redirect('admin/accounts/edit/'.$data['account_id']);
		} else {
		    $data['error_message'] = $this->formError;
		    $data['page_title'] = 'Account Edit Error';
		    $this->load_view('errors/error_and_back',$data);
		}
	    } else {
		$accountInfo = $this->account_model->getAccountInfo($account_id);

		$manager_id = $accountInfo['manager_id'];
		$userList = $this->loadUsers();
		$managerInfo = $userList[$manager_id];
		
		$data['page_title'] = "Edit MyLIS Account  ($account_id)";
		$data['accountInfo'] = $accountInfo;
		$data['account_id'] = $account_id;
		$data['managerInfo'] = $managerInfo;
		$data['userList'] = $userList;
		$data['gtypes'] = $this->gtypes;
		$data['disciplines'] = $this->disciplines;
		$data['properties'] = $this->properties;
		$data['lis_tz'] = $this->lis_tz;
		$data['accountProfile'] = $this->account_model->getAccountProfile($account_id);
		$this->load_view('admin/accounts/edit',$data);
	    }
	} else {
	    redirect('admin/accounts');
	}
    }
    
    public function remove($account_id){
	
	if (isset($_POST['account_remove_form'])){
	    $code = $this->input->post('code');

	    if($code == $this->remove_code) {
		// move the account directory to the trash directory
		$dir = $this->admin_filemanager->moveToTrash($account_id);

		// remove tables from LISDB
		$this->account_model->removeMyLISTables($account_id);
		$this->account_model->removeMyLISUsers($account_id);

		redirect('admin/accounts?mtype=remove&message='.$account_id);
	    } else {
		$data['error'] = '<br><h1 style="text-align: center;">Account Not Removed<br>
		Incorrect Removal Code Entered</h1>';
		$data['page_title'] = 'Account Removal Error';
		$this->load_view('errors/generic_error',$data);
		return;
	    }
	} else {
	    $data['page_title'] = 'Account Removal Confirmation';
	    $data['account_id'] = $account_id;
	    $this->load_view('admin/accounts/remove',$data);
	}
	
    }
    
    public function renew(){
	$account_id = $this->input->post('account_id');
	$term = $this->input->post('term');
	$userid = $this->userobj->userid;
	$role = $this->userobj->role;
	$name = $this->userobj->name;

	if($role != 'admin') {
	    $data['error'] = '<br><h1 style="text-align: center;">Sorry, '.$name.'
	    <br>You Don\'t Have The Right to Modify This Account.</h1>';
	    $data['page_title'] = 'Account Renew Error';
	    $this->load_view('errors/generic_error',$data);
	    return;
	}
	
	// set the new expire date based on the old one
	$accountInfo = $this->account_model->getAccountInfo($account_id);
	$expire_date = $accountInfo['expire_date'];
	$expire_date = getExpireDate($expire_date, '', $term);

	// update the database now
	$this->account_model->renew_account($expire_date,$term,$account_id);

	// update the initiation file now
	$props = array(
	    'lis.expire' => $expire_date,
	);
	$this->admin_filemanager->modifyInitiationFile($account_id, $props);

	redirect('admin/accounts/edit'.$account_id);
    }
    
    public function update(){
	
	$current_version = '1.2';
	$new_version = '1.3';
	$account_ids = $this->getAccountIDs();

	$this->load->model('updater_model');
	$update_logs = '';
	foreach($account_ids as $account_id) {
	    $update_logs .= $this->updater_model->updateAccount($account_id);
	}
	
	$data['page_title'] = 'MyLIS Account Updater';
	$data['new_version'] = $new_version;
	$data['update_logs'] = $update_logs;
	$this->load_view('admin/accounts/update',$data);
    }
    
    public function search(){
	if(isset($_POST['account_id'])) {
	    $account_id = $_POST['account_id'];
	} else {
	    $account_id = '';
	}
	if(!empty($account_id) && $this->account_model->accountExists($account_id)) {
	    $accountInfo = $this->account_model->getAccountInfo($account_id);
	    $accountProfile = $this->account_model->getAccountProfile($account_id);
	    $userList = $this->loadUsers();
	    $managerInfo = $userList[$accountInfo['manager_id']];
	    
	    // get the userid and password from DB
	    $userids_passwords = '';
	    $accountUsers = $this->account_model->getAccountUsers($account_id);
	    foreach($accountUsers as $user) {
		$userids_passwords .=$user['email'].' : '.$user['password'].' ;';
	    }
	    
	    $data['page_title'] = 'Search Results';
	    $data['account_id'] = $account_id;
	    $data['accountInfo'] = $accountInfo;
	    $data['accountProfile'] = $accountProfile;
	    $data['managerInfo'] = $managerInfo;
	    $data['userids_passwords'] = $userids_passwords;
	    $this->load_view('admin/accounts/search_results',$data);
	} else {
	    // redirect to the accounts view page
	    redirect('admin/accounts/view/'.$account_id);
	}
    }
    
    // function to check the input form data from add account and edit account page
    function checkFormInput($page) {
	$error = '';

	$fname = $this->input->post('fname'); // PI first name
	$mi = $this->input->post('mi'); // PI middle name
	$lname = $this->input->post('lname'); // PI last name
	$group_name = $this->input->post('group_name'); // group name
	$group_type = $this->input->post('group_type'); // group type or location
	$discipline = $this->input->post('discipline'); // discipline
	$institution_name = $this->input->post('institution_name'); // name of institution
	$address = trim($this->input->post('address')); // institution adress
	$phone = $this->input->post('phone');
	$fax = $this->input->post('fax');
	$email = $this->input->post('email');
	$password1 = $this->input->post('password1'); // this password should be the same
	$password2 = $this->input->post('password2');
	$keywords = $this->input->post('keywords'); // research keywords
	$description = trim($this->input->post('description')); // research keywords
	$piurl = $this->input->post('piurl'); // the group web page of the PI
	$expire_date = $this->input->post('expire_date');

	if(empty($fname)) {
	    $error .= '<li>Please Enter PI\'s First Name</li>';
	}
	if(empty($lname)) {
	    $error .= '<li>Please Enter PI\'s Last Name</li>';
	}
	if(empty($group_name)) {
	    $error .= '<li>Please Enter Group Name</li>';
	}
	if($group_type == 'Select One') {
	    $error .= '<li>Please Enter Group Type</li>';
	}
	if($discipline == 'Select One') {
	    $error .= '<li>Please Enter Discipline</li>';
	}
	if(empty($institution_name)) {
	    $error .= '<li>Please Enter Institution Name</li>';
	}
	if(empty($address)) {
	    $error .= '<li>Please Enter Institution Address</li>';
	}
	if(empty($phone) || strlen($phone) < 10) {
	    $error .= '<li>Please Enter Phone Number </li>';
	}
	if(empty($email) || !valid_email($email)) {
	    $error .= '<li>Please Enter Valid Email Address </li>';
	}
	if(!valid_password($password1) && $page == 'add') {
	    $error .= '<li>Password Not Valid </li>';
	}
	if(!valid_password($password1) && $password1 != $password2 && $page == 'add') {
	    $error .= '<li>Two Passwords Do Not Match </li>';
	}
	if(!checkLISDate($expire_date) && $page == 'edit') {
	    $error .= '<li>Please Enter A Valid Expire Date</li>';
	}
	if(empty($keywords)) {
	    $error .= '<li>Please Enter One or More Keywords Seperated By Commas </li>';
	}
	if(empty($piurl)) {
	    $error .= '<li>Please Enter Group\'s or PI Webpage URL</li>';
	}

	if(!empty($error)) {
	    $this->formError = '<h3><span style="background-color: rgb(255, 0, 0);">
	    Error, the following value(s) were not entered, or the formating is 
	    incorrect. Please use the back button and correct the values.</span></h3>
	    <ul style="list-style-type: square;"> '.$error.'</ul>';
	    return false;
	}
	else {
	    return true;
	}
    }

    // function to return the group name 
    function getAccountID($lname) {
	$account_id = $this->input->post('account_id');
	if(empty($account_id) || strlen($account_id) < 3) {
	    $i = 1;
	    $account_id = strtolower($lname).$i;
	    while($this->account_model->accountExists($account_id)) {
		$i++;
		$account_id = $lname.$i;
	    }
	    return $account_id;
	} else {
	    return $account_id;
	}
    }
 
    // function to get an activate code
    function getActivateCode() {
	$code = '';
	for($i = 0; $i < 6; $i++) {
	$code .= chr(mt_rand(65, 90));
	}
	return $code;
    }
    
    // function to send an email informing that the account has been created
    function sendEmail($account_info) {
	$to  = $account_info[2];
	$subject  = 'MyLIS Account Created';

	$body = "This is an automated response to inform you the MyLIS account for $account_info[1] as been created.\n\n";
	$body .= "Group ID : $account_info[0]\n";
	$body .= "Initial login userid : $account_info[2]\n";
	$body .= "Password : $account_info[3]\n";
	$body .= "Activation code : $account_info[4]\n";
	$body .= "Account url : $account_info[5]\n";
	$body .= "Bookmark this URL for site access or login at http://www.mylis.net\n";
	$body .= "Please input the activation code once logged in within 30 days to keep account from expiring";

	mail($to, $subject, $body);
    }
 
    
    
}