<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
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
        $list[$index]=null;
	}


	#[Post(path: "todos/edit/{index}", name: 'todos.edit')]
	public function editElement($index){
		
	}


	#[Get(path: "todos/loadList/{uniqid}", name: 'todos.loadList')]
	public function loadList($uniqid){
		
	}


	#[Post(path: "todos/loadList/", name: 'todos.loadListPost')]
	public function loadListFromForm(){
		
	}


	#[Get(path: "todos/newlist/{force}", name: 'todos.new')]
	public function newlist($force = false){
        USession::set(self::LIST_SESSION_KEY,[]);
        $this->displayList([]);

	}


	#[Get(path: "todos/saveList/", name: 'todos.save')]
	public function saveList(){
        $list=USession::get(self::LIST_SESSION_KEY);
        $id = self::LIST_SESSION_KEY;
        //CacheManager::$cache->store(self::CACHE_KEY . $id, $list);
        $this->showMessage("Sauvegarde", "La liste a été sauvegardée sous l'id $id", "info", "check square");
	}

    private function menu(){
        $this->loadView('TodosController/menu.html');
    }

	
	private function displayList($list){
		$this->jquery->change('#multiple', '$(".form").toggle');
		$this->jquery->renderView('TodosController/displayList.html',['list'=>$list]);

	}

	private function showMessage(string $header, string $message,string $type ='info', string $icon='info circle', array $buttons=[]){
        $this->loadView('TodosController/showMessage.html',
        compact('header', 'message', 'type', 'icon', 'buttons'));
    }
}
