<?php
namespace controllers;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;

 use models\Organization;
 use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;

/**
 * Controller OrgaController
 **/
class OrgaController extends ControllerBase{
    private ViewRepository $repo;
    public function initialize()
    {
        parent::initialize();
        $this->repo=new ViewRepository($this, Organization::class);
    }

    #[Route(path:'orga', name: 'home')]
	public function index(){
        $this->repo->all("", false);
        $this->loadView("OrgaController/index.html");
    }


	#[Route(path: "orga/{idOrga}",name: "orga.getOne")]
	public function getOne($idOrga){
		$this->repo->byId($idOrga, ['users.groupes','groupes.users']);
		$this->loadDefaultView();
	}

    #[Post(path: "orga/add",name: "orga.add")]
    public function create(){
        $this->loadView("OrgaController/create.html");
    }

}
