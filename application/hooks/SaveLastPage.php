<?php
class SaveLastVisitedPage {
    public function save_last_visited_page()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('session');
        if (!$this->ci->input->is_ajax_request() && $this->ci->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            $tmp_login_user_id = $this->ci->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
            $tmp_where = array("user_id" => $tmp_login_user_id);
            $last_visited_page = uri_string();
            $this->ci->crud->update('user',array("last_visited_page" => $last_visited_page),$tmp_where);
        }
        return true;
    }
}