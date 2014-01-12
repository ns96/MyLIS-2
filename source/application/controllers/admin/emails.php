<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Handles the administrative tasks that are related to e-mail lists
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Emails extends Admin_Controller {
    
    private $userobj = null;
    private $institutions = array();
    private $filename = '';
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->filename = CIPATH.'admin/files/email_list.txt';
	$this->restrict_access();
    }
    
    /**
     * Displays a form for importing an e-mail list
     */
    public function index(){
	$data['page_title'] = 'E-mail List Manager';
	$this->load_view('admin/emails/main',$data);
    }
    
    /**
     * Imports e-mails from the posted file
     */
    public function import(){
	$tmp_name = $_FILES['fileupload']['tmp_name'];
	$error = '';

	if(is_uploaded_file($tmp_name)) {
	    $lc = 1;
	    $total = 0;
	    $fp = fopen($tmp_name, "r") or die("Couldn't open $tmp_name");

	    // read in each line then add to database skipping the first
	    $line = fgets($fp, 1024);
	    while(!feof($fp)) {
		$lc++;
		$line = fgets($fp, 10000);
		if(!empty($line) && strstr($line, '@')) {
		    $sa  = explode("\t", $line);
		    $institution = trim($sa[0]);
		    $people = trim($sa[1]);
		    $email_count = substr_count($people, '@');
		    $total += $email_count;

		    $this->get_emails($institution, $people);
		}
	    }

	    fclose($fp); // close the file now
	    
	    // save the emails to the file
	    $text = $this->write_emails_to_file();
	    
	    $data['page_title'] = 'Imported E-mail List';
	    $data['total'] = $total;
	    $data['text'] = $text;
	    $data['institutions'] = $this->institutions;
	    $data['filename'] = $this->filename;
	    $data['mainHTML'] = $this->load->view('admin/emails/main',null,TRUE);
	    $this->load_view('admin/emails/summary_page',$data);
	} else {
	    $error = 'Error no file selected ...';
	}
    }
    
    /**
     * Retrieves the emails for an institution
     * 
     * @param string $institution
     * @param string $people
     */
    protected function get_emails($institution, $people) {
	$sa  = explode(";", $people);
	$emails = array();

	foreach($sa as $person) {
	    if(empty($person)) {
		continue;
	    }

	    $sa2 = explode("<", $person);
	    $name = trim($sa2[0]);
	    $em = trim($sa2[1], ' >');

	    // extract first and last name
	    $sa3 = explode(" ", $name);
	    $ll = count($sa3) - 1; // get location of last name
	    $lname =  $sa3[$ll];
	    $fname = trim(str_replace($lname, "", $name));
	    if(!stristr($fname, 'prof. ')) {
		$fname  = 'Prof. '.$fname;
	    }

	    // add the email to the email array
	    $emails[] = "$fname\t$lname\t$em\n";
	}

	$inst = new institution($institution, $emails); 
	$this->institutions[] = $inst;
    }
    
    /**
     * Writes the emails (separated by institution) in a file
     * 
     * @return string
     */
    protected function write_emails_to_file() {
	$text = '';
	$fh = fopen($this->filename, 'w') or die("can't open file");

	foreach($this->institutions as $inst) {
	    $name = $inst->name;
	    $emails = $inst->emails;
	    $text .= '<b>'.count($emails).' for '.$name.' :: </b><br>';
	    foreach($emails as $email) {
		fwrite($fh, $email);
		$text .= "$email<br>";
	    }
	}

	fclose($fh);
	return $text;
    }
    
}

    /**
     * A class that is localy used to store an email list
     */
    class institution {
	var $name = '';
	var $emails = array();

	// the main constructor
	public function __construct($name, $emails) {
	    $this->name = $name;
	    $this->emails = $emails;
	}
    }