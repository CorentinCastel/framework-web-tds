<?php
namespace controllers;

 use models\Basket;
 use models\Order;
 use models\Product;
 use models\Section;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;
 use Ubiquity\orm\DAO;
 use Ubiquity\utils\http\USession;

 /**
 * Controller MainController
 **/
class MainController extends ControllerBase{

    use WithAuthTrait;

    #[Route('_default', name:'home')]
    public function index(){
        $nbCommande = DAO::count(Order::class, 'idUser=?', [USession::get("idUser")]);
        $productsPromo = DAO::getAll(Product::class, 'promotion<0');
        $nbPaniers = DAO::count(Basket::class, 'idUser=?', [USession::get("idUser")]);
        $this->jquery->renderView('MainController/index.html', ['nbCommandes'=>$nbCommande, 'nbPaniers'=>$nbPaniers, 'products'=>$productsPromo]);
    }




    protected function getAuthController(): AuthController{
        return new MyAuth($this);
    }

    #[Route('store', name: 'store')]
    public function store(){
        $productsPromo = DAO::getAll(Product::class, 'promotion<0');
        $sections = DAO::getAll(Section::class);
        $this->jquery->renderView('mainController/store.html', ['products'=>$productsPromo, "sections"=>$sections]);
    }

    #[Route('section/{section_id}', name: 'section')]
    public function section($section_id){
        $products = DAO::getAll(Product::class, 'idSection='.$section_id);
        $sections = DAO::getAll(Section::class);
        $nomSect = DAO::getOne(Section::class, 'id='.$section_id);
        $this->jquery->renderView('mainController/section.html', ['sections'=>$sections, 'products'=>$products, 'sectionName'=>$nomSect]);
    }

    #[Route('product/{section_id}/{product_id}', name: 'product')]
    public function product($section_id, $product_id){
        $product = DAO::getOne(Product::class, 'id='.$product_id);
        $sections = DAO::getAll(Section::class);
        $nomSect = DAO::getOne(Section::class, 'id='.$section_id);
        $this->jquery->renderView('mainController/product.html', ['sections'=>$sections, 'product'=>$product, 'sectionName'=>$nomSect]);
    }

}
