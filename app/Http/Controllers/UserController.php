<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::orderBy('id', 'asc')->get();
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
            'name' => 'required|string|max:40',
            'surname' => 'required|string|max:40',
            'phone_number' => 'required|string|max:40',
            'email' => 'required|string|max:40|email|unique:users',
            'password' => 'required|string|max:6',
            'is_admin' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            User::create($request->all());
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'surname' => 'required|string|max:40',
            'phone_number' => 'required|string|max:40',
            'email' => 'required|string|max:40|email|unique:users,email,' . $user,
            'password' => 'required|string|min:6',
            'is_admin' => 'required|boolean'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 400);
        }
    
        DB::beginTransaction();
    
        try {
            User::where('id', $user)->update($request->all());
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
    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);

        if ($user) {
            $user->delete();
            return response()->json(['status' => true], 201);
        } else {
            return response()->json(['status' => false], 500);
        }
    }
}
