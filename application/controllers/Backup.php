<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Backup
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Backup extends CI_Controller
{
    public $logged_in_id = null;
    public $now_time = null;
    
    function __construct(){
        parent::__construct();
        $this->load->model('Crud', 'crud');
        $this->load->library('zip');
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
        $this->now_time = date('Y-m-d H:i:s');
    }
    
    function index(){
        if($this->applib->have_access_role(MODULE_BACKUP_DB_ID,"view")) {
            $login_logo_name = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'login_logo'));
            $this->zip->read_file("assets/dist/img/".$login_logo_name);
            $this->zip->read_dir("assets/uploads/logo_image/");
            $this->load->dbutil();
            $prefs = array(
                'format' => 'zip',
                'filename' => 'saas_' . date("Y-m-d-H-i-s") . '.sql'
            );
            $backup = $this->dbutil->backup($prefs);
            $this->zip->download('saas_backup_'. date("d-M-Y H:i"));
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
}
