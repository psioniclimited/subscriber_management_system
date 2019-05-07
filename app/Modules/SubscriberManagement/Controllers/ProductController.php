<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use App\Modules\SubscriberManagement\Models\Product;
use App\Modules\SubscriberManagement\Models\Channel;
use Entrust;

class ProductController extends Controller {

    /**
     * [allProductsList -loads all products list view]
     * @return [view] [description]
     */
    public function allProducts() {
        return view('SubscriberManagement::product.all_products');
    }

    /**
     * [getProducts - all products are loaded with ajx in a datatable in all products view]
     * @return [type] [description]
     */
    public function getProducts(){
        $products = Product::all();
        return Datatables::of($products)
        ->addColumn('Link', function ($products) {
            $action_view = '<a href="' . url('/products') . '/' . $products->id . '/edit' . '" class="btn btn-xs btn-primary">
                            <i class="glyphicon glyphicon-edit"></i> Edit
                            </a>';
            
            return $action_view;


        })
        ->make(true);
    }

    /**
     * [addProduct -loads add new product form]
     */
    public function addProduct(){
        $allChannels = Channel::all();
        return view('SubscriberManagement::product.add_product')
        ->with('allChannels', $allChannels);
    }

    /**
     * [addChannelProcess -adds new product to database]
     * @param Request $request [description]
     */
    public function addProductProcess(Request $request){
        $product = Product::create($request->all());
        
        $product->channels()->attach($request->input('channel'));

        return redirect('allproducts');
    }

    /**
     * [editProduct -product edit form is displayed]
     * @param  [int] $id [product id]
     * @return [view]     [edit product form]
     */
    public function editProduct($id){
        $product = Product::with('channels')->findOrFail($id);
        $allChannels = Channel::all();

        return view('SubscriberManagement::product.edit_product')
        ->with('product', $product)
        ->with('allChannels', $allChannels);
    }

    /**
     * [editProductProcess -changes made to edit product form is saved in db]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function editProductProcess(Request $request, $id){
        $editProduct = Product::findOrFail($id);
        $editProduct->update($request->all());

        $editProduct->channels()->sync($request->input('channel'));

        return redirect('products/'.$id.'/edit');
    }

}
