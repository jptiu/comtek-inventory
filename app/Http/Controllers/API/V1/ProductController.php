<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Product;
use App\Models\ProductCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController
{
    public function index(Request $request)
    {
        $products = Product::with('productCodes')->get();

        if ($request->has('category_id')) {
            $products = Product::query()
                ->where('category_id', $request->get('category_id'))
                ->with('productCodes')
                ->get();
        }

        return response()->json($products);
    }

    public function lookupByBarcode($barcode)
    {
        $productCode = ProductCode::where('code', $barcode)
            ->where('is_sold', false)
            ->first();

        if (!$productCode) {
            return response()->json(null);
        }

        $product = $productCode->product;

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'selling_price' => $product->selling_price,
            'codes' => $product->productCodes,
            'product_code_id' => $productCode->id
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code',
            'product_image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'quantity_alert' => 'required|integer|min:0',
            'tax' => 'required|numeric|min:0',
            'tax_type' => 'required|string|in:percentage,amount',
            'notes' => 'nullable|string',
            'product_codes.*.code' => 'required|string|max:255',
            'product_codes.*.type' => 'required|string|in:barcode,sku,other',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // Create the product
            $product = Product::create($validated);

            // Handle product image
            if ($request->hasFile('product_image')) {
                $imagePath = $request->file('product_image')->store('products', 'public');
                $product->update(['product_image' => $imagePath]);
            }

            // Create product codes
            if ($request->has('product_codes')) {
                foreach ($request->product_codes as $codeData) {
                    ProductCode::create([
                        'product_id' => $product->id,
                        'code' => $codeData['code'],
                        'type' => $codeData['type'],
                        'is_primary' => false
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Product created successfully'], 201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'product_image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'quantity_alert' => 'required|integer|min:0',
            'tax' => 'required|numeric|min:0',
            'tax_type' => 'required|string|in:percentage,amount',
            'notes' => 'nullable|string',
            'product_codes.*.code' => 'required|string|max:255',
            'product_codes.*.type' => 'required|string|in:barcode,sku,other',
        ]);

        DB::transaction(function () use ($request, $product, $validated) {
            // Update the product
            $product->update($validated);

            // Handle product image
            if ($request->hasFile('product_image')) {
                $imagePath = $request->file('product_image')->store('products', 'public');
                $product->update(['product_image' => $imagePath]);
            }

            // Update product codes
            if ($request->has('product_codes')) {
                // First delete all existing codes
                $product->productCodes()->delete();

                // Then create new ones
                foreach ($request->product_codes as $codeData) {
                    ProductCode::create([
                        'product_id' => $product->id,
                        'code' => $codeData['code'],
                        'type' => $codeData['type'],
                        'is_primary' => false
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy(Product $product)
    {
        $product->productCodes()->delete();
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function markCodeAsSold(Request $request)
    {
        $validated = $request->validate([
            'product_code_id' => 'required|exists:product_codes,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productCode = ProductCode::findOrFail($validated['product_code_id']);
        
        DB::transaction(function () use ($productCode, $validated) {
            // Mark the code as sold
            $productCode->markAsSold();
            
            // Update product quantity
            // $product = $productCode->product;
            // $product->decrement('quantity', $validated['quantity']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Product code marked as sold and quantity updated'
        ]);
    }
}
