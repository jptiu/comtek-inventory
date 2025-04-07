<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use Str;

class InvoiceController extends Controller
{
    public function create(StoreInvoiceRequest $request, Customer $customer)
	{
		// If customer_id is a non-numeric string, create a new customer
		if(is_string($request->customer_id) && !is_numeric($request->customer_id)) {
			$customer = Customer::create([
				'user_id' => auth()->id(),
				'uuid' => Str::uuid(),
				'name' => $request->customer_id
			]);
		} 
		// Otherwise use the existing customer (either from parameter or request)
		else {
			$customer = $request->has('customer_id') 
				? Customer::findOrFail($request->customer_id)
				: $customer;
		}

		$carts = Cart::content();

		return view('invoices.create', [
			'customer' => $customer,
			'carts' => $carts
		]);
	}
}
