<?php 

require_once( 'Controller/ControllerBase.php' );

require_once('/Model/UserData.php');

class UserController extends ControllerBase {

	// top
	public function indexAction(){
		$this->view->userData = $this->userData;
		$this->view->show("User/Profile");
	
	}

	public function EditLineAction(){
		$this->view->show("User/EditLine");
	}

	public function EditNotificationAction(){
		$this->view->show("User/EditNotification");
	}
}


?>