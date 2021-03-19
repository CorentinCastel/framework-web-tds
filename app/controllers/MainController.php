<?php
namespace controllers;

 use models\Order;
 use models\Product;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;
 use Ubiquity\orm\DAO;
 use Ubiquity\utils\http\USession;

 /**
 * Controller MainController
 **/
class MainController extends ControllerBase{

    use WithAuthTrait;

    #[Route('_default', name:'home')]
    public function index(){
        $nbCommande = DAO::count(Order::class, 'idUser=?', [USession::get("idUser")]);

        $this->jquery->renderView('MainController/index.html', ['nbCommandes'=>$nbCommande]);
    }




    protected function getAuthController(): AuthController{
        return new MyAuth($this);
    }



}
