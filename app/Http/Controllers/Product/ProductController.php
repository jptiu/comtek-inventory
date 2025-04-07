<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::count();

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function create(Request $request)
    {
        $categories = Category::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);

        if ($request->has('category')) {
            $categories = Category::whereSlug($request->get('category'))->get();
        }

        if ($request->has('unit')) {
            $units = Unit::whereSlug($request->get('unit'))->get();
        }

        return view('products.create', [
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        /**
         * Handle upload image
         */
        $image = "";
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image')->store('products', 'public');
        }
        // Generate a unique code
		$lastProduct = Product::orderBy('id', 'desc')->first();
		$lastCode = $lastProduct ? intval(substr($lastProduct->code, 3)) : 0;
		$newCode = 'PC-' . str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($request, $image) {
            $product = Product::create([
                'code' => $newCode,
                'product_image'     => $image,
                'name'              => $request->name,
                'category_id'       => $request->category_id,
                'unit_id'           => $request->unit_id,
                'quantity'          => $request->quantity,
                'buying_price'      => $request->buying_price,
                'selling_price'     => $request->selling_price,
                'quantity_alert'    => $request->quantity_alert,
                'tax'               => $request->tax??0,
                'tax_type'          => $request->tax_type?? 0,
                'notes'             => $request->notes,
                "user_id" => auth()->id(),
                "slug" => Str::slug($request->name, '-'),
                "uuid" => Str::uuid()
            ]);

            // Create product codes if provided
            if ($request->has('product_codes')) {
                foreach ($request->product_codes as $codeData) {
                    $product->codes()->create([
                        'code' => $codeData['code'],
                        'type' => $codeData['type'],
                        'is_primary' => false
                    ]);
                }
            }
        });

        return to_route('products.index')->with('success', 'Product has been created!');
    }

    public function show($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($product->code, $generator::TYPE_CODE_128);

        return view('products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    public function edit($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        return view('products.edit', [
            'categories' => Category::get(),
            'units' => Unit::get(),
            'product' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, $uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        
        DB::transaction(function () use ($request, $product) {
            // Update product data
            $product->update($request->except('product_image'));

            // Handle product image
            if ($request->hasFile('product_image')) {
                // Delete old photo if exists
                if ($product->product_image && file_exists(public_path('storage/') . $product->product_image)) {
                    unlink(public_path('storage/') . $product->product_image);
                }
                
                $image = $request->file('product_image')->store('products', 'public');
                $product->product_image = $image;
            }

            // Update product codes
            if ($request->has('product_codes')) {
                // First delete all existing codes
                $product->codes()->delete();

                // Then create new ones
                foreach ($request->product_codes as $codeData) {
                    $product->codes()->create([
                        'code' => $codeData['code'],
                        'type' => $codeData['type'],
                        'is_primary' => false
                    ]);
                }
            }

            // Update other product fields
            $product->name = $request->name;
            $product->slug = Str::slug($request->name, '-');
            $product->category_id = $request->category_id;
            $product->unit_id = $request->unit_id;
            $product->quantity = $request->quantity;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->quantity_alert = $request->quantity_alert;
            $product->tax = $request->tax;
            $product->tax_type = $request->tax_type;
            $product->notes = $request->notes;
            $product->save();
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been updated!');
    }

    public function destroy($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        /**
         * Delete photo if exists.
         */
        if ($product->product_image) {
            // check if image exists in our file system
            if (file_exists(public_path('storage/') . $product->product_image)) {
                unlink(public_path('storage/') . $product->product_image);
            }
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been deleted!');
    }
}
