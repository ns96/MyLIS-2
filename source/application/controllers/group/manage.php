<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Manages various group-level administrative tasks
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Manage extends Group_Controller {
    
    private $userobj = null;
    private $u_table = null;
    private $main_error = '';
    private $home_dir = '';
    private $gtypes = null;
    private $disciplines = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
        $this->u_table = $this->properties['lis.account'].'_users';
        $this->home_dir = CIPATH."/accounts/mylis_".$this->properties['lis.account']."/";
	$this->gtypes = array('Select One', 'Academia', 'Government', 'Research Instititue', 'Company');
	$this->disciplines = array('Select One', 'Astronomy', 'Atmospheric Science', 
	'Biochemistry', 'Biological Sciences', 'Biotechnology', 'Chemistry', 
	'Computer Science', 'Electrical Engineering', 'Material Science', 'Mathematics', 
	'Mechanical Engineering', 'Nanotechology', 'Pharmacology', 'Physics');
	
	$this->restrict_access();
    }
   
    /**
     * Defines as main management page the user management page
     */
    public function index(){
        redirect('group/manage/users_main');
    }
    
    /**
     * Loads the main page of 'User management'
     */
    public function users_main(){
        $data1['page'] = 'user';
        $menuHTML = $this->load->view('group/manage/menu_bar',$data1,TRUE);
        
        $data2['home_dir'] = $this->home_dir;
        $data2['im_message'] = $this->session->userdata('im_message');
        $this->session->unset_userdata('im_message');
        $importForm = $this->load->view('group/manage/users_import_form',$data2,TRUE);
        
        $data['page_title'] = "Group user management";
        $data['menuHTML'] = $menuHTML;
        $data['importForm'] = $importForm;
        $data['users'] = $this->load_users();
        $data['um_error'] = $this->session->userdata('um_error');
        $this->load_view('group/manage/users_main',$data);
    }
    
    /**
     * Imports a list users from a file
     */
    public function users_import(){
        if (isset($_POST['users_import_form'])){
           
            $tmp_name = $_FILES['fileupload']['tmp_name'];
            $error = '';

            if(is_uploaded_file($tmp_name)) {
              $lc = 2; // keep tracks of the current line
              $ec = 0; // keep tracks of the entries add to the database

              $fp = fopen($tmp_name, "r") or die("Couldn't open $tmp_name");

              // read in each line then add to database skipping the first
              $line = fgets($fp, 1024); //skip the first line
              while(!feof($fp)) {
                $line = fgets($fp, 1024);
                $sa  = split("\t", $line);

                if(!empty($line)) {
                  $userid = trim($sa[0]);
                  $password = trim($sa[1]); // should really check password, but not doing so for migration issues
                  $role = trim($sa[2]); // has to be user, buyer, guest, or admin
                  $name = addslashes($sa[3]);
                  $email = trim($sa[4]);
                  $status = trim($sa[5]); // hasto be present or past
                  $info = addslashes($sa[6]);

                  // check that user id is correct if not return error message
                  if(strlen($userid) < 4) {
                    $error .= 'Warning, The "'.$userid.'" User ID is less than 4 characters<br>';
                  }

                  // if role is not correct fix it
                  if($role != 'user' && $role != 'buyer' && $role != 'guest' && $role != 'admin') {
                    $role = 'user'; // set it to be a regular user
                  }

                  if($status != 'present' && $status != 'past') {
                    $status = 'present'; // make this user current
                  }

                  // check to make sure this user id is not already in the system if it is then just delete it
                  $this->load->model('user_model');
                  $this->user_model->delete_user($userid);

                  // add this entry to the database now
                  $data['userid'] = $userid;         $data['password'] = $password;
                  $data['role'] = $role;             $data['name'] = $name;
                  $data['email'] = $email;           $data['status'] = $status;
                  $data['info'] = $info;
                  $this->user_model->add_user($data);

                  $ec++; // increment the entry count
                }
                else { // line is empty so do nothing
                  //$error .= 'Error, unable to add entry on line '.$lc.'<br>';
                }
                $lc++;
              }

              fclose($fp); // close the file now
            }
            else {
              $ec = 'Error no file selected. 0';
            }

            // set message informing user that the data has been updated
            $message = $ec.' entries added to user database ...<br>'.$error;
            $this->session->set_userdata('im_message',$message);

            redirect('group/manage/users_main');
        } else {
            redirect('group/manage/users_main');
        }
    }
    
    /**
     * 
     * @return typeAdds a new user to the group
     */
    public function users_add(){
        if (isset($_POST['user_add_form'])){
            if($this->check_form_input()) {
                $data['userid'] = $this->input->post('userid'); 
                $data['password'] = $this->input->post('password');
                $data['name'] = $this->input->post('name');
                $data['email'] = $this->input->post('email');
                $data['role'] = $this->input->post('role');
                $data['info'] = $this->input->post('info');
                $data['status'] = 'present';

                $this->load->model('user_model');
                $this->user_model->add_user($data);
            } else {
                $data['error_message'] = $this->error_message;
                $this->load_view('error/error_and_back',$data);
                return;
            }
        }
        redirect('group/manage/users_main');
    }
    
    /**
     * Updates the information about the group's users
     */
    public function userlist_modify(){
        if (isset($_POST['userlist_modify'])){
            
            $modify_task = $this->input->post('modify_task');
            $userids = $this->input->post('userids');

            $this->session->set_userdata('um_error',''); // clear any errors

            if(!empty($userids)) {
                $this->load->model('user_model');
                foreach($userids as $userid) {
                  if($modify_task == 'update') {
                      $userid2 = cleanUserID($userid); // repalces any @ or . with underscores

                      if($this->check_form_input2($userid2)) {
                          $data['password'] = $this->input->post('password_'.$userid2);
                          $data['name'] = $this->input->post('name_'.$userid2);
                          $data['email'] = $this->input->post('email_'.$userid2);
                          $data['role'] = $this->input->post('role_'.$userid2);
                          $data['info'] = $this->input->post('info_'.$userid2);
                          $data['userid'] = $userid2;

                          $this->user_model->update_user($data);
                      } else {
                          $main_error = 'Errors found modifying '.$userid.' ( '.$this->error_message.')<br>';
			  $data['page_title'] = 'Error!';
                          $data['error_message'] = $this->error_message;
                          $this->load_view('errors/error_and_back',$data);
                          return;
                      }
                  } else { // remove user
                    $this->user_model->complete_user_removal($userid);
                  }
                }
            }

            $this->session->set_userdata('um_error',$this->main_error);
        }
        redirect('group/manage/users_main');
    }
    
    /**
     * Loads the main page of 'Location Management'
     */
    public function locations_main(){

        $data1['page'] = 'location';
        $menuHTML = $this->load->view('group/manage/menu_bar',$data1,TRUE);
        
        $data['page_title'] = 'Group location manager';
        $data['menuHTML'] = $menuHTML;
        $data['users'] = $this->load_users();
        $data['currentUsers'] = $this->get_current_users();
        $this->load->model('chemicals_model');
        $data['locationList'] = $this->chemicals_model->simple_location_list();
        $data['lm_error'] = $this->session->userdata('lm_error');
        $this->session->unset_userdata('lm_error');
        $this->load_view('group/manage/locations_main',$data);
    }
    
    /**
     * Adds a new location to the group
     */
    public function locations_add(){
        $userid = $this->userobj->userid;

        $same_room = $this->input->post('same_room');
        $same_description = $this->input->post('same_description');
        $same_owner = $this->input->post('same_owner');

        for($i = 0; $i < 5; $i++) {
          $location_id = $this->input->post('locationid_'.$i); 

          if($same_room == 'yes') {
            $room = $this->input->post('room_0');
          }
          else {
            $room = $this->input->post('room_'.$i);
          }

          if($same_description == 'yes') {
            $description = $this->input->post('description_0');
          }
          else {
            $description = $this->input->post('description_'.$i);
          }

          if($same_owner == 'yes') {
            $owner = $this->input->post('owner_0');
            $otherowner0 = $this->input->post('otherowner_0');
            if(!empty($otherowner0)) 
                $owner = $otherowner0;
          } else {
            $owner = $this->input->post('owner_'.$i);
            $otherowner_i = $this->input->post('otherowner_'.$i);
            if(!empty($otherowner_i)) 
                $owner = $otherowner_i;
          }
         
          if(!empty($location_id) && !empty($room)) {
                // add to the database
                $locationInfo[0] = $location_id;
                $locationInfo[1] = $room;
                $locationInfo[2] = $description;
                $locationInfo[3] = $owner;
                
                $this->load->model('chemicals_model');
                $this->chemicals_model->add_location($locationInfo,$this->userobj);
          }
        }

        redirect('group/manage/locations_main');
    }
    
    /**
     * Updates information about group's locations
     */
    public function locations_update(){
        
        $modify_task = $this->input->post('modify_task');
        $locationids = $this->input->post('locationids');
        $userid = $this->userobj->userid;
        
        $error = '';
        if(!empty($locationids)) {
            $this->load->model('chemicals_model');
            foreach($locationids as $location_id) {
            if($modify_task == 'update') {
                $room = $this->input->post('room_'.$location_id);
                $description = $this->input->post('description_'.$location_id);
                $owner = $this->input->post('owner_'.$location_id);
                $otherowner = $this->input->post('otherowner_'.$location_id);
                if(!empty($otherowner)) {
                  $owner = $otherowner;
                }
                if(!empty($room)) {
                    $data['room'] = $room;
                    $data['description'] = $description;
                    $data['owner'] = $owner;
                    $data['userid'] = $userid;
                    $data['location_id'] = $location_id;
                    $this->chemicals_model->update_location($data);
                } else {
                  $error .= 'Errors found modifying '.$location_id.'<br>';
                }
            } else { // remove the location
              $this->chemicals_model->delete_location($location_id);
            }
          }
        }
        $this->session->set_userdata('lm_error',$error);

        redirect('group/manage/locations_main');
    }
    
    /**
     * Loads the main page of 'Inventory Management'
     */
    public function inventory_main(){
        $data1['page'] = 'inventory';
        $menuHTML = $this->load->view('group/manage/menu_bar',$data1,TRUE);
        
        $data2['home_dir'] = $this->home_dir;
        $data2['im_message1'] = $this->session->userdata('im_message1');
        $data2['im_message2'] = $this->session->userdata('im_message2');
        $this->session->unset_userdata('im_message1');
        $this->session->unset_userdata('im_message2');

        $this->load->model('chemicals_model');
        
        $data2['type'] = 'Chemical';
        $data2['categories'] = $this->chemicals_model->get_categories_by_type('Chemical');
        $chemicalsImportForm = $this->load->view('group/manage/inventory_import_form',$data2,TRUE);
        $addChemicalCategories = $this->load->view('group/manage/inventory_add_form',$data2,TRUE);
        $editChemicalCategories = $this->load->view('group/manage/inventory_edit_form',$data2,TRUE);
        
        $data2['type'] = 'Supply';
        $data2['categories'] = $this->chemicals_model->get_categories_by_type('Supply');
        $suppliesImportForm = $this->load->view('group/manage/inventory_import_form',$data2,TRUE);
        $addSupplyCategories = $this->load->view('group/manage/inventory_add_form',$data2,TRUE);
        $editSupplyCategories = $this->load->view('group/manage/inventory_edit_form',$data2,TRUE);
        
        $data['page_title'] = 'Group inventory manager';
        $data['menuHTML'] = $menuHTML;
        $data['chemicalsImportForm'] = $chemicalsImportForm;
        $data['suppliesImportForm'] = $suppliesImportForm;
        $data['addChemicalCategories'] = $addChemicalCategories;
        $data['addSupplyCategories'] = $addSupplyCategories;
        $data['editChemicalCategories'] = $editChemicalCategories;
        $data['editSupplyCategories'] = $editSupplyCategories;
        $this->load_view('group/manage/inventory_main',$data);
    }
   
    /**
     * Imports items to the chemicals inventory from a file
     */
    public function inventory_import_chemicals(){  
        $tmp_name = $_FILES['fileupload']['tmp_name'];
        $error = '';
        $this->load->model('chemicals_model');
        
        if(is_uploaded_file($tmp_name)) {
          $date = getLISDate(); // get todays date
          $categories = $this->chemicals_model->get_categories_by_type('Chemical');
          $lc = 2; // keep tracks of the current line
          $ec = 0; // keep tracks of the entries add to the database

          // see if to reset the database table
          if($_POST['action'] == 'overwrite') {
            $this->chemicals_model->reset_chemicals_table();
          }

          $fp = fopen($tmp_name, "r") or die("Couldn't open $tmp_name");

          // read in each line then add to database skipping the first
          $line = fgets($fp, 1024);
          while(!feof($fp)) {
            $line = fgets($fp, 1024);
            $sa  = split("\t", $line);

            if(!empty($line)) {
              $cas = trim($sa[0]);
              $name = trim($sa[1], ' "');
              $name = addslashes($name);
              $company = ucfirst($sa[2]);
              $product_id = ''; // blank on purpose
              $amount = '1';
              $units = trim($sa[3]);
              $entry_date = $date;
              $status = 'In Stock';
              $status_date = $date;
              $mfmw = ''; // blank on purpose
              $category = trim($sa[4]);
              $location_id = trim($sa[5]);
              $owner = trim($sa[6]);
              $notes = trim($sa[7], ' "');
              $notes = addslashes($notes);
              $userid = 'myadmin';

              // set some default if the values where not enetered
              if(empty($company)) {
                $company = 'Unknown';
              }
              if(empty($category)) {
                $category = 'Organic';
              }
              if(empty($location_id)) {
                $location_id = 'unassigned';
              }
              if(empty($owner)) {
                $owner = 'myadmin';
              }

              // add to the database if all the required values have been entered
              if(!empty($name) || !empty($units)) {
                    $data['cas'] = $cas;                    
                    $data['name'] = $name;
                    $data['company'] = $company;
                    $data['product_id'] = $product_id;
                    $data['amount'] = $amount;
                    $data['units'] = $units;
                    $data['entry_date'] = $entry_date;
                    $data['status'] = $status;
                    $data['status_date'] = $status_date;
                    $data['mfmw'] = $mfmw;
                    $data['category'] = $category;
                    $data['location'] = $location_id;
                    $data['notes'] = $notes;
                    $data['owner'] = $owner;
                    $data['userid'] = $userid;
                    $this->chemicals_model->add_chemical($data);

                    if(!in_array($category, $categories)) {
                      $this->chemicals_model->add_category($category,$this->userobj->userid);
                    }
                    $ec++; // increment the entry count
                } else {
                    $error .= 'Error, unable to add entry on line '.$lc.'<br>';
                }
                $lc++;
            }
          }
          fclose($fp); // close the file now
        }
        else {
          $ec = 'Error no file selected. 0';
        }

        // set message informing user that the data has been updated
        $message = $ec.' entries added to chemical database ...<br>'.$error;
        $this->session->set_userdata('im_message1',$message);

        redirect('group/manage/inventory_main');
    }
    
    /**
     * Imports items to the supplies inventory from a file
     */
    public function inventory_import_supplies(){
        $tmp_name = $_FILES['fileupload']['tmp_name'];
        $error = '';
        $this->load->model('supplies_model');
        
        if(is_uploaded_file($tmp_name)) {
          $date = getLISDate(); // get todays date
          $categories = $this->chemicals_model->get_categories_by_type('Supply');
          $lc = 2; // keep tracks of the current line
          $ec = 0; // keep tracks of the entries add to the database

          // see if to reset the database table
          if($_POST['action'] == 'overwrite') {
            $this->supplies_model->reset_supplies_table();
          }

          $fp = fopen($tmp_name, "r") or die("Couldn't open $tmp_name");

          // read in each line then add to database skipping the first
          $line = fgets($fp, 1024);
          while(!feof($fp)) {
            $line = fgets($fp, 1024);
            $sa  = split("\t", $line);

            if(!empty($line)) {
                $model = trim($sa[0]);
                $name = trim($sa[1], ' "');
                $name = addslashes($name);
                $company = ucfirst($sa[2]);
                $product_id = ''; // blank on purpose
                $amount = '1';
                $units = trim($sa[3]);
                $entry_date = $date;
                $status = 'In Stock';
                $status_date = $date;
                $sn = ''; // blank on purpose
                $category = trim($sa[4]);
                $location_id = trim($sa[5]);
                $owner = trim($sa[6]);
                $notes = trim($sa[7], ' "');
                $notes = addslashes($notes);
                $userid = 'myadmin';

                // set some default if the values where not enetered
                if(empty($company)) {
                  $company = 'Unknown';
                }
                if(empty($category)) {
                  $category = 'General';
                }
                if(empty($location_id)) {
                  $location_id = 'unassigned';
                }
                if(empty($owner)) {
                  $owner = 'myadmin';
                }

              // add to the database if all the required values have been entered
              if(!empty($name) || !empty($units)) {
                    $data['model'] = $model;
                    $data['name'] = $name;
                    $data['company'] = $company;
                    $data['product_id'] = $product_id;
                    $data['amount'] = $amount;
                    $data['units'] = $units;
                    $data['entry_date'] = $entry_date;
                    $data['status'] = $status;
                    $data['status_date'] = $status_date;
                    $data['sn'] = $sn;
                    $data['category'] = $category;
                    $data['location'] = $location_id;
                    $data['notes'] = $notes;
                    $data['owner'] = $owner;
                    $data['userid'] = $userid;
                    $this->supplies_model->add_supply($data);

                    if(!in_array($category, $categories)) {
                      $this->supplies_model->add_category($category,$this->userobj->userid);
                    }
                    $ec++; // increment the entry count
                } else {
                    $error .= 'Error, unable to add entry on line '.$lc.'<br>';
                }
                $lc++;
            }
          }
          fclose($fp); // close the file now
        }
        else {
          $ec = 'Error no file selected. 0';
        }

        // set message informing user that the data has been updated
        $message = $ec.' entries added to supply database ...<br>'.$error;
        $this->session->set_userdata('im_message2',$message);

        redirect('group/manage/inventory_main#supply');
    }
    
    /**
     * Adds new categories for chemicals
     */
    public function inventory_add_chemical_categories(){
        $userid = $this->user->userid;
        $table_name = '';
        $this->load->model('chemicals_model');
        
        // get all the categories that may have been entered
        for($i = 0; $i < 7; $i++) {
            $category = $this->input->post('cat_'.$i);
            if(!empty($category)) 
                $this->chemicals_model->add_category($category,$userid);
        }

        redirect('group/manage/inventory_main#chemical');
    }
    
    /**
     * Adds new categories for supplies
     */
    public function inventory_add_supply_categories(){
        $userid = $this->user->userid;
        $table_name = '';
        $this->load->model('supplies_model');
        
        // get all the categories that may have been entered
        for($i = 0; $i < 7; $i++) {
            $category = $this->input->post('cat_'.$i);
            if(!empty($category)) 
                $this->supplies_model->add_category($category,$userid);
        }

        redirect('group/manage/inventory_main#supply');
    }
    
    /**
     * Updates information about the chemical categories
     */
    public function inventory_edit_chemical_categories(){
        $userid = $this->user->userid;
        $modify_task = $this->input->post('modify_task');
        $catids = $this->input->post('catids');

        $this->load->model('chemicals_model');

        if(!empty($catids)) {
          foreach($catids as $category_id) {
            $value = $this->input->post('cat_'.$category_id);

            if($modify_task == 'update' && !empty($value)) {
              $this->chemicals_model->update_category($value,$category_id);
            } else { // remove category
              $this->chemicals_model->delete_category($category_id);
            }
          }
        }

        redirect('group/manage/inventory_main#chemical');
    }
    
    /**
     * Updates information about the supply categories
     */
    public function inventory_edit_supply_categories(){
        $userid = $this->user->userid;
        $modify_task = $this->input->post('modify_task');
        $catids = $this->input->post('catids');

        $this->load->model('chemicals_model');

        if(!empty($catids)) {
          foreach($catids as $category_id) {
            $value = $this->input->post('cat_'.$category_id);

            if($modify_task == 'update' && !empty($value)) {
              $this->chemicals_model->update_category($value,$category_id);
            } else { // remove category
              $this->chemicals_model->delete_category($category_id);
            }
          }
        }

        redirect('group/manage/inventory_main#supply');
    }
    
    /**
     * Loads the modules' settings page
     */
    public function modules_main(){
        $data1['page'] = 'module';
        $menuHTML = $this->load->view('group/manage/menu_bar',$data1,TRUE);
        
        $this->load->model('proputil_model');
        // Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
        $this->proputil_model->initialize($params);
                
        $data['proputil'] = $this->proputil_model->get_properties();
        
        $data['page_title'] = "Group user management";
        $data['menuHTML'] = $menuHTML;
        $data['properties'] = $this->properties;
        $this->load_view('group/manage/modules_main',$data);
    }
    
    /**
     * Reconfigures which modules will be visible 
     */
    public function modules_update(){
        
        // Setup paramaters for initializing models below
        $params['user'] = $this->userobj;
        $params['account'] = $this->session->userdata('group');
        $params['properties'] = $this->properties;
        
        $this->load->model('proputil_model');
        $this->proputil_model->initialize($params);
        
	$this->load->model('filemanager');
	$this->filemanager->initialize($params);
        
        if($this->input->post('chemical') == 'yes') {
          $this->properties['show.chemical'] = 'yes';
        } else {
          $this->properties['show.chemical'] = 'no';
        }

        if($this->input->post('chemical2') == 'yes') {
          $this->properties['show.chemical2'] = 'yes';
          $this->proputil_model->store_property('chemical2.link', checkURL($this->input->post('url')));
          $this->proputil_model->store_property('chemical2.sitename', $this->input->post('name')); 
        } else {
          $this->properties['show.chemical2'] = 'no';
        }

        if($this->input->post('labsupply') == 'yes') {
          $this->properties['show.labsupply'] = 'yes';
        } else {
          $this->properties['show.labsupply'] = 'no';
        }

        if($this->input->post('groupmeeting') == 'yes') {
          $this->properties['show.groupmeeting'] = 'yes';
        } else {
          $this->properties['show.groupmeeting'] = 'no';
        }

        if($this->input->post('orderbook') == 'yes') {
          $this->properties['show.orderbook'] = 'yes';
        } else {
          $this->properties['show.orderbook'] = 'no';
        }

        if($this->input->post('publication') == 'yes') {
          $this->properties['show.publication'] = 'yes';
        } else {
          $this->properties['show.publication'] = 'no';
        }

        if($this->input->post('instrulog') == 'yes') {
          $this->properties['show.instrulog'] = 'yes';
        } else {
          $this->properties['show.instrulog'] = 'no';
        }

        if($this->input->post('grouptask') == 'yes') {
          $this->properties['show.grouptask'] = 'yes';
        } else {
          $this->properties['show.grouptask'] = 'no';
        }

        if($this->input->post('libraryDB') == 'yes') {
          $this->properties['show.libraryDB'] = 'yes';
        } else {
          $this->properties['show.libraryDB'] = 'no';
        }

        if($this->input->post('folder') == 'yes') {
          $this->properties['show.folder'] = 'yes';
        } else {
          $this->properties['show.folder'] = 'no';
        }

        if($this->input->post('weblinks') == 'yes') {
          $this->properties['show.weblinks'] = 'yes';
        } else {
          $this->properties['show.weblinks'] = 'no';
        }

        if($this->input->post('webapps') == 'yes') {
          $this->properties['show.webapps'] = 'yes';
        } else {
          $this->properties['show.webapps'] = 'no';
        }
        
        // save the properties file now
        $this->filemanager->write_initiation_file($this->properties);

        redirect('group/manage/modules_main');
    }
    
    /**
     * Updates settings related each module's functionality
     */
    public function modules_configure(){
        
        // Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
        
        $this->load->model('proputil_model');
        $this->proputil_model->initialize($params);
    
        if($this->input->post('orders_private') == 'yes') {
          $this->proputil_model->store_property('orders.private', 'yes');
        } else {
          $this->proputil_model->store_property('orders.private', 'no');
        }

        if($this->input->post('orders_notifybuyer') == 'yes') {
          $this->proputil_model->store_property('orders.notifybuyer', 'yes');
        } else {
          $this->proputil_model->store_property('orders.notifybuyer', 'no');
        }

        if($this->input->post('orders_notifyuser') == 'yes') {
          $this->proputil_model->store_property('orders.notifyuser', 'yes');
        } else {
          $this->proputil_model->store_property('orders.notifyuser', 'no');
        }

        redirect('group/manage/modules_main');
    }
    
    /**
     * Loads the group information management page
     */
    public function groupinfo_main(){
        if (isset($_POST['groupinfo_update_form'])){
            if($this->check_form_input()) {
                $account_id = $this->account_id;
                $fname = trim($this->input->post('fname')); // PI first name
                $mi = trim($this->input->post('mi')); // PI middle name
                $lname = trim($this->input->post('lname')); // PI last name
                $group_name = ucfirst(trim($this->input->post('group_name'))); // group name
                $group_type = $this->input->post('group_type'); // group type or location
                $discipline = $this->input->post('discipline'); // discipline
                $institution = trim($this->input->post('institution_name')); // name of institution
                $address = trim($this->input->post('address')); // institution adress
                $phone = trim($this->input->post('phone'));
                $fax = trim($this->input->post('fax'));
                $email = trim($this->input->post('email'));
                $group_pi = "$fname $lname";
                if(!empty($mi)) {
                  $group_pi = "$fname $mi. $lname";
                }

                $data['account_id'] = $account_id;
                $data['fname'] = $fname;
                $data['mi'] = $mi;
                $data['lname'] = $lname;
                $data['group_name'] = $group_name;
                $data['group_type'] = $group_type;
                $data['discipline'] = $discipline;
                $data['institution'] = $institution;
                $data['address'] = $address;
                $data['phone'] = $phone;
                $data['fax'] = $fax;
                $data['email'] = $email;
                $data['group_pi'] = $group_pi;
                $this->load->model('account_model');
                $this->account_model->update_account_info($data);

                // now update the profile files
                $userList = $this->load_users();
                $site_manager = $userList[$this->input->post('site_manager')];
                $site_manager_email = $site_manager->email;

                $props = array(
                    'group.name' => $group_name,
                    'site.manager' => $site_manager->userid,
                    'site.manager.email' => $site_manager_email
                );

                // Setup paramaters for initializing models below
                $params['user'] = $this->userobj;
                $params['account'] = $this->session->userdata('group');
                $params['properties'] = $this->properties;
                // Load a FileManager model
                $this->load->model('filemanager');
                $this->filemanager->initialize($params);
                
                $this->filemanager->modify_initiation_file($props);

                redirect('group/manage/groupinfo_main');
            }
        } else {
            $data1['page'] = 'group';
            $menuHTML = $this->load->view('group/manage/menu_bar',$data1,TRUE);

            $this->load->model('account_model');
            $account_id = $this->properties['lis.account'];

            $data['page_title'] = "Group Information Management";
            $data['menuHTML'] = $menuHTML;
            $data['users'] = $this->get_current_users();
            $data['info'] = $this->account_model->get_account_info($account_id);
            $data['account_id'] = $account_id;
	    $data['disciplines'] = $this->disciplines;
	    $data['gtypes'] = $this->gtypes;
            $this->load_view('group/manage/groupinfo_main',$data);
        }
    }
    
    /**
     * Validates the input of the user add form
     * 
     * @return boolean
     */
    protected function check_form_input() {
      $error = '';

      $userid = $this->input->post('userid'); 
      $password = $this->input->post('password');
      $name = $this->input->post('name');
      $email = $this->input->post('email');

      if(!$this->valid_userid($userid)) {
        $error .= '<li>Please Enter Valid User ID</li>';
      }

      if(empty($email) || !valid_email($email)) {
        $error .= '<li>Please Enter Valid Email Address </li>';
      }

      if(!$this->valid_password($password)) {
        $error .= '<li>Please Enter Valid Password (6 or more characters) </li>';
      }

      if(empty($name)) {
        $error .= '<li>Please Enter Valid Name</li>';
      }

      if(!empty($error)) {
        $this->error_message = '<h3><span style="background-color: rgb(255, 100, 100);">
        Error, the following value(s) were not entered, or the formating is 
        incorrect. Please use the back button and correct the values.</span></h3>
       <ul style="list-style-type: square;"> '.$error.'</ul>';
        return false;
      }
      else {
        return true;
      }
    }
    
    /**
     * Validates the form input when modifying user account information
     * 
     * @param string $userid
     * @return boolean
     */
    protected function check_form_input2($userid) {
      $password = $this->input->post("password_$userid");
      $name = $this->input->post("name_$userid");
      $email = $this->input->post("email_$userid");

      //echo "UserID $userid password $password name $name email $email";
      if(empty($email) || !valid_email($email)) {
            $this->error_message = 'Please Enter Valid E-mail ';
            return false;
      }

      if(!$this->valid_password($password)) {
            $this->error_message = 'Please Enter Valid Password ';
            return false;
      }

      if(empty($name)) {
        $this->error_message = 'Please Enter Valid Name ';
        return false;
      }

      return true;
    }
    
    /**
     * Validates a userid
     *
     * @param string $userid
     * @return boolean
     */
    protected function valid_userid($userid) {

      $users = $this->load_users();
      if(strlen($userid) < 3 || isset($users[$userid])) { // make sure userid is not empty or already in list
        return false;
      }
      else {
        return true;
      }
    }

    /**
     * Validates user's password
     * 
     * @param type $password
     * @return boolean
     */
    protected function valid_password($password) {
      $valid = true;
      if(strlen($password) < 4) {
        return false;
      }

      return $valid;
  }
}