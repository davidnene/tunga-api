<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index() {
        $profiles = Profile::all();

        return ProfileResource::collection($profiles);
    }

    public function show(string $id) {
        $profile = Profile::find($id);

        if(is_null($profile)) {
            return response()->json(['message'=>'Record not found'], 404);
        }
        return new ProfileResource($profile);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'role' => 'required',
            'age' => 'required|integer'
        ]);

        if($validator->fails()) {
            return response()->json(["error"=>$validator->errors()], 422);
        }
        Profile::create($request->all());

        return response()->json([
            'message'=>'Profile created successfully'
        ], 201);
    }

    public function update(Request $request, $id) {
        $profile = Profile::findOrFail($id);

        if(is_null($profile)) {
            return response()->json([
                'message'=>'Record not found'
            ], 404);
        }
        $profile->update($request->all());
        return new ProfileResource($profile);
    }

    public function destroy($id){
        $profile = Profile::findOrFail($id);

        if(is_null($profile)) {
            return response()->json([
                "message"=>"Record not found."
            ], 404);
        }

        $profile->delete();
        return response()->json([
            "message"=>"Profile deleted successfully"
        ], 200);

    }
}
