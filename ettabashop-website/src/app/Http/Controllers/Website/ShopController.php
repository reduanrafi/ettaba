<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller {
    private $globalObject;
    private $moduleName           = "Shop";
    private $singularVariableName = 'shop';
    private $pluralVariableName   = 'shops';

    private $retrievedDataList;
    private $singleData;

    public function __construct() {
        $this->globalObject = new Shop();
    }

    public function Index(Request $request) {

        $this->retrievedDataList = $this->globalObject->all();

        return view('website.shops', [
            $this->pluralVariableName => $this->retrievedDataList,
        ]);
    }

    public function detail(Request $request) {
        $id               = $request->id;
        $this->singleData = $this->globalObject->GetShop($id);
        $products         = $this->globalObject->GetShopProducts($id);

        return view('website.shop_detail', [
            $this->singularVariableName => $this->singleData,
            'products'                  => $products,
        ]);

    }

    public function search(Request $request) {
        $this->retrievedDataList = $this->globalObject->SearchShop($request->keywords);

        return view('website.shops', [
            $this->pluralVariableName => $this->retrievedDataList,
        ]);
    }
}
