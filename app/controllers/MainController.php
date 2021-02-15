<?php
namespace controllers;
 use models\Group;
 use models\Organization;
 use models\User;
 use services\dao\OrgaRepository;
 use services\ui\UIGroups;
 use Ubiquity\attributes\items\di\Autowired;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;
 use Ubiquity\orm\DAO;
 use Ubiquity\utils\http\USession;

 /**
  * Controller MainController
  */
class MainController extends ControllerBase{
use WithAuthTrait;

#[Autowired]
private OrgaRepository $repo;
private UIGroups $uiServices;

    public function initialize()
    {
        parent::initialize();
        $this->uiServices=new UIGroups($this);
    }


    #[Route('_default', name:'home')]
	public function index(){
        $this->uiServices = new UIGroups($this);
		$this->jquery->renderView('MainController/index.html');
	}

    #[Route(path:"test/ajax", name:"main.testAjax")]
    public function testAjax(){
        $user = DAO::getById(User::class, [1], false);
        $this->loadView('MainController/testAjax.html', ['user'=>$user]);
    }

    #[Route('user/details/{idUser}', name: 'user.details')]
    public function userDetails($idUser){
        $user=DAO::getById(User::class, [$idUser], true);
        echo "Organization : ".$user->getOrganization();
    }

    #[Route('groups/list', name: 'groups.list')]
    public function listGroups(){
        $idOrga=USession::get('idOrga');
        $groups=DAO::getAll(Group::class, 'idOrganization= ?', false, [$idOrga]);
        $this->uiServices->listGroups($groups);
        $this->jquery->renderView('MainController/listGroups.html');
    }

    public function setRepo(OrgaRepository $repo): void
    {
        $this->repo = $repo;
    }

    protected function getAuthController(): AuthController{
        return new MyAuth($this);
    }
}
