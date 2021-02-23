<?php
namespace controllers\crud\datas;

use Ubiquity\controllers\crud\CRUDDatas;
 /**
  * Class CrudUsersDatas
  */
class CrudUsersDatas extends CRUDDatas
{
    public function getFieldNames($model)
    {
        return ['firstname', 'lastname', 'email', 'suspended', 'groups'];
    }

    public function getFormFieldNames($model, $instance)
    {
        return ["firstname", "lastname", "email", "groups", "suspended"];
    }

    /*public function _getInstancesFilter($model)
    {
        return "";
    }

    public function getManyToManyDatas($fkClass, $instance, $member)
    {
        return "";
    }*/
}