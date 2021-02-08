<?php
namespace controllers;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;
use Ubiquity\attributes\items\router\Route;

#[Route(path: "/login",inherited: true,automated: true)]
class MyAuth extends \Ubiquity\controllers\auth\AuthController{

    protected function initializeAuth()
    {
        parent::initializeAuth(); // TODO: Change the autogenerated stub
        if (!URequest::isAjax()) {
            $this->loadView('@activeTheùe/main/vHeader.html');
            $this->loadView('TodosController/menu.html');

        }
    }
    protected function onConnect($connected) {
		$urlParts=$this->getOriginalURL();
		USession::set($this->_getUserSessionKey(), $connected);
		if(isset($urlParts)){
			$this->_forward(implode("/",$urlParts));
		}else{
			//TODO
			UResponse::header('location', Route('home'));
		}
	}

	protected function _connect() {
		if(URequest::isPost()){
			$email=URequest::post($this->_getLoginInputName());
			$password=URequest::post($this->_getPasswordInputName());
			return $email;
		}
		return null;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Ubiquity\controllers\auth\AuthController::isValidUser()
	 */
	public function _isValidUser($action=null) {
		return USession::exists($this->_getUserSessionKey());
	}

	public function _getBaseRoute() {
		return '/login';
	}

	private function showMessage(string $header, string $message, string $type='info', string $icon='info'){
	    this.$this->loadView('TodosController/showMessage.html',
        compact('header', 'type', 'icon', 'message', 'buttons'));
    }

	protected function getAuthcontroller(): AuthController{
	    return new MyAuth($this);
}
    public function isValid($action){
	    if($action=='index'){
	        return true;
        }
	    parent::isValid($action);
    }
	


}
