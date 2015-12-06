<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH."core/my_controller.php");

class User_login extends MY_Controller {

	function __construct() {
		$this->need_login = false;
		parent::__construct();
	}

	public function login(){
		if ($this->form_validation->run('login') == FALSE) {
			$this->show_login('缺少用户名或密码！', 'danger');
		} else {
			$name = $this->input->post('name');
			$password = $this->input->post('password');
			$this->load->model('user_model');

			$res = $this->user_model->check_login($name, $password);

			if ($res !== FALSE) {
	 			$data = array(
					'name'=>$res->name,
					'logged_in'=>TRUE,
					'id'=>$res->id,
					'custom'=>$res->custom_id,
					);
	 			$this->session->set_userdata($data);
				$this->show_main();
			} 
			else if($res !== FALSE && $res->state == 0) {
				$this->show_login('该用户已被冻结！请联系系统管理员。', 'danger');
			}
			else
			{
				$this->show_login('用户名或密码错误！', 'danger');
			}
		}
	}


	function logout() {
		$this->session->sess_destroy();
		$this->show_main();
	}

}

?>