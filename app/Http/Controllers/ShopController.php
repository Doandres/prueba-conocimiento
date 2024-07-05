<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Shop::orderBy('id', 'asc')->get();
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
            'name' => 'required|string|max:50',
            'user_id' => 'required|numeric|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            Shop::create($request->all());
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
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $shop)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 400);
        }
    
        DB::beginTransaction();
    
        try {
            Shop::where('id', $shop)->update($request->all());
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
    public function destroy(Shop $shop)
    {
        $shop = Shop::findOrFail($shop->id);

        if ($shop) {
            $shop->delete();
            return response()->json(['status' => true], 201);
        } else {
            return response()->json(['status' => false], 500);
        }
    }
}
