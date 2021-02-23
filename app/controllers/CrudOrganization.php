<?php
namespace controllers;
use controllers\crud\datas\CrudOrganizationDatas;
use Ubiquity\controllers\crud\CRUDDatas;
use controllers\crud\viewers\CrudOrganizationViewer;
use Ubiquity\controllers\crud\viewers\ModelViewer;
use controllers\crud\events\CrudOrganizationEvents;
use Ubiquity\controllers\crud\CRUDEvents;
use controllers\crud\files\CrudOrganizationFiles;
use Ubiquity\controllers\crud\CRUDFiles;
use Ubiquity\attributes\items\router\Route;

#[Route(path: "/orgas",inherited: true,automated: true)]
class CrudOrganization extends \Ubiquity\controllers\crud\CRUDController{

	public function __construct(){
		parent::__construct();
		\Ubiquity\orm\DAO::start();
		$this->model='models\\Organization';
		$this->style='';
	}

	public function _getBaseRoute() {
		return '/orgas';
	}
	
	protected function getAdminData(): CRUDDatas{
		return new CrudOrganizationDatas($this);
	}

	protected function getModelViewer(): ModelViewer{
		return new CrudOrganizationViewer($this,$this->style);
	}

	protected function getEvents(): CRUDEvents{
		return new CrudOrganizationEvents($this);
	}

	protected function getFiles(): CRUDFiles{
		return new CrudOrganizationFiles();
	}


}
