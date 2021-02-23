<?php
namespace controllers\crud\datas;

use Ubiquity\controllers\crud\CRUDDatas;
 /**
  * Class CrudOrganizationDatas
  */
class CrudOrganizationDatas extends CRUDDatas{
	public function getFieldNames($model)
    {
        return ["name", "domain", "groups"];
    }
}
