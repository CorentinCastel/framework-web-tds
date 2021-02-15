<?php
namespace services\ui;

 use models\Group;
 use Ubiquity\controllers\Controller;
 use Ubiquity\utils\http\URequest;

 /**
  * Class UIGroups
  */
class UIGroups extends \Ajax\php\ubiquity\UIService {
    public function __construct(Controller $controller) {
        parent::__construct($controller);
        if(!URequest::isAjax()) {
            $this->jquery->getHref('a[data-target]', '', ['hasLoader' => 'internal', 'historize' => false,'listenerOn'=>'body']);
        }
    }

    public function listGroups(array $groups){
        $dt=$this->semantic->dataTable('dt-groups', Group::class, $groups);
        $dt->setFields(['name', 'email']);
        $dt->FieldAsLabel('email', 'mail');
    }
}
