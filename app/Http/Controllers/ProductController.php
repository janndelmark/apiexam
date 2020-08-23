<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductModel;
use Validator;

class ProductController extends Controller
{
    public function __construct(){
		$this->middleware('auth:api');
	}
	
	public function order(Request $request){
		
		$validator = Validator::make($request->all(), [
            'product_id' => 'required|digits_between:1,4',
            'quantity' => 'required|digits_between:1,4',
        ]);
		
		 if ($validator->fails()) {
			return response()->json($validator->errors(), 422);
		 }else{
			$inputId = $request->get('product_id');
			$inputQuantity =  $request->get('quantity');
			$fetchProduct = ProductModel::find($inputId);
			
			if($inputQuantity <= $fetchProduct->stock){
				$newCount = $fetchProduct->stock - $inputQuantity;
				ProductModel::where('id',$inputId)->update(['stock' => $newCount]);
				return response()->json(['message' => 'You have successfully ordered this product'], 201);
			}else{
				return response()->json(['message' => 'Failed to order this product due to unavailability of the stock'], 400);
			}
		 }
		 
	}
}
