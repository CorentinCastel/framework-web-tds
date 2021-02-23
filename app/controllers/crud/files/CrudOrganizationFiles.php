<?php
namespace controllers\crud\files;

use Ubiquity\controllers\crud\CRUDFiles;
 /**
  * Class CrudOrganizationFiles
  */
class CrudOrganizationFiles extends CRUDFiles{
	public function getViewIndex(){
		return "CrudOrganization/index.html";
	}

	public function getViewForm(){
		return "CrudOrganization/form.html";
	}

	public function getViewDisplay(){
		return "CrudOrganization/display.html";
	}


}
