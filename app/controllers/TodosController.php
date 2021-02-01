<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\cache\CacheManager;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
  * Controller TodosController
  * @property \Ajax\php\ubiquity\JsUtils $jquery
  */
class TodosController extends ControllerBase{

    const CACHE_KEY = 'datas/lists/';
    const EMPTY_LIST_ID='not saved';
    const LIST_SESSION_KEY='list';
    const ACTIVE_LIST_SESSION_KEY='active-list';


    public function initialize(){
		parent::initialize();
		$this->menu();
    }

	public function index(){
        if(USession::exists(self::LIST_SESSION_KEY)){
            $list=USession::get(self::LIST_SESSION_KEY);
            return $this->displayList($list);
        }
        $this->showMessage("Bienvenue", "TodoLists permet de gérer des listes...", "info", "info circle",
        [['url'=>Router::path('todos.new'), 'caption'=>"Créer une nouvelle liste","style"=>'basic inverted']]);
	}

	#[Post(path: "todos/add", name: 'todos.add')]
	public function addElement(){
        $list=USession::get(self::LIST_SESSION_KEY);
        if (URequest::has('elements')){
            $elements=explode("\n",URequest::post('elements'));
            foreach ($elements as $elm){
                $list[]=$elm;
            }
        }
        else{
            $list[]=URequest::post('element');
        }
        USession::set(self::LIST_SESSION_KEY, $list);
        $this->displayList($list);
	}


	#[Get(path: "todos/delete/{index}", name: 'todos.delete')]
	public function deleteElement($index){
        $list=USession::get(self::LIST_SESSION_KEY);
        $temp = [];
        $i = 0;
        $j =0;
        foreach ($list as $elm){
            if($j != $index){
                $temp[$i]=$elm;
                $i++;
            }
            $j++;
        }
        USession::set(self::LIST_SESSION_KEY, $temp);
        $list = USession::get(self::LIST_SESSION_KEY);
        $this->displayList($list);
	}


	#[Post(path: "todos/edit/{index}", name: 'todos.edit')]
	public function editElement($index){
        $list=USession::get(self::LIST_SESSION_KEY);
        $list[$index]=URequest::post('element');
        USession::set(self::LIST_SESSION_KEY, $list);
        $this->displayList($list);
	}


	#[Get(path: "todos/loadList/{uniqid}", name: 'todos.loadList')]
	public function loadList($uniqid){
        if (CacheManager::$cache->exists(self::CACHE_KEY . $uniqid)) {
            $list = CacheManager::$cache->fetch(self::CACHE_KEY . $uniqid);
            $this->displayList($list);
        }

	}


	#[Post(path: "todos/loadList/", name: 'todos.loadListPost')]
	public function loadListFromForm(){
		$uniqid = URequest::post('id');
        if (CacheManager::$cache->exists(self::CACHE_KEY . $uniqid)) {
            $list = CacheManager::$cache->fetch(self::CACHE_KEY . $uniqid);
            $this->displayList($list);
        }
        else{
            $this->showMessage("Chargement", "La liste d'id $uniqid n'existe pas", "info", "frown outline icon");
        }
	}


	#[Get(path: "todos/newlist/{force}", name: 'todos.new')]
	public function newlist($force = false){
        $list = USession::get(self::LIST_SESSION_KEY);
        if($list == null || $list == [] || $force){
            $this->showMessage("Nouvelle Liste", "Liste correctement créée.", "icon", "check square green");
            USession::set(self::LIST_SESSION_KEY,[]);
            $this->displayList([]);
        }
        else{
            $this->showMessage("Nouvelle Liste", "Une liste a déjà été créée. Souhaitez-vous la vider ?", "info", "info circle",
                [
                    ['url'=>Router::path('home', [],false), 'caption'=>"Annuler","style"=>'ui inverted button'],
                    ['url'=>Router::path("todos.new", [1]), 'caption'=>"Confirmer la création","style"=>'ui inverted green button']
                ]);
        }


	}


	#[Get(path: "todos/saveList/", name: 'todos.save')]
	public function saveList(){
        $list=USession::get(self::LIST_SESSION_KEY);
        $id = uniqid('', true);
        CacheManager::$cache->store(self::CACHE_KEY . $id, $list);
        $this->showMessage("Sauvegarde", "La liste a été sauvegardée sous l'id $id."."Elle sera disponible a l'adresse : http://127.0.0.1:8090/todos/loadList/$id", "info", "check square");
	}

    private function menu(){
        $this->loadView('TodosController/menu.html');
    }

	
	private function displayList($list){
        /*if(count($list)>0){
            $this->jquery->show('._saveList', '', '', false);
        }*/
		$this->jquery->change('#multiple', '$(".form").toggle');
		$this->jquery->renderView('TodosController/displayList.html',['list'=>$list]);

	}

	private function showMessage(string $header, string $message,string $type ='info', string $icon='info circle', array $buttons=[]){
        $this->loadView('TodosController/showMessage.html',
        compact('header', 'message', 'type', 'icon', 'buttons'));
    }
}
