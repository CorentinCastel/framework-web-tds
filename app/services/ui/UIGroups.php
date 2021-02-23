<?php
namespace services\ui;

 use Ajax\semantic\html\collections\form\HtmlForm;
 use Ajax\semantic\widgets\dataform\DataForm;
 use models\Group;
 use models\Organization;
 use models\User;
 use Ubiquity\controllers\Controller;
 use Ubiquity\controllers\Router;
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

    public function orgaForm(Organization $orga){
        $frm = $this->semantic->dataForm('frmOrga', $orga);
        $frm->setFields(['id', 'name', 'domain']);
        $frm->fieldAsHidden('id');
        $frm->fieldAsLabeledInput('name', ['rules'=>['empty', 'email']]);
        $frm->setValidationParams(["on"=>"blur", "inline"=>true]);
        $frm->addSubmit('bt-submit', 'valider', 'positive', Router::path('addOrga'), '#response');
    }

    private function addFormBehavior(string $formName,HtmlForm|DataForm $frm,string $responseElement,string $postUrlName){
        $frm->setValidationParams(["on"=>"blur","inline"=>true]);
        $this->jquery->click("#$formName-div ._validate",'$("#'.$formName.'").form("submit");');
        $this->jquery->click("#$formName-div ._cancel",'$("#'.$formName.'-div").hide();');
        $frm->setSubmitParams(Router::path($postUrlName),'#'.$responseElement,['hasLoader'=>'internal']);
    }

    public function newUser($formName){
        $frm=$this->semantic->dataForm($formName,new User());
        $frm->addClass('inline');
        $frm->setFields(['firstname','lastname']);
        $frm->setCaptions(['Prénom','Nom']);
        $frm->fieldAsLabeledInput('firstname',['rules'=>'empty']);
        $frm->fieldAsLabeledInput('lastname',['rules'=>'empty']);
        $this->addFormBehavior($formName,$frm,'#new-user','new.userPost');
    }
}
