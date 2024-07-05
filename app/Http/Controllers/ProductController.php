<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::orderBy('id', 'asc')->get();
        return response()->json(compact('data'), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:350',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'shop_id' => 'required|numeric|exists:shops,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            Product::create($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['status' => true], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:350',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'shop_id' => 'required|numeric|exists:shops,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 400);
        }
    
        DB::beginTransaction();
    
        try {
            Product::where('id', $product)->update($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    
        return response()->json(['status' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
