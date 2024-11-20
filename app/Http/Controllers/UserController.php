<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Services\rabbitMQServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function send()
    {
        $user = User::all();
        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('queue_syahril', $user);

        if (Redis::get('redis_syahril') === null){
            Redis::set('redis_syahril', $user, 'EX', 7600);
            return response()->json([
                'message' => 'send redis set',
                'data' => json_decode(Redis::get('redis_syahril'), true)
            ], 200);
        }

        // dd(Redis::get('redis_syahril'));
        return response()->json([
                'message' => 'get by redis',
                'data' => json_decode(Redis::get('redis_syahril'), true)
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function userProjectTask(){
        $user = Project::with('users')->get();

        return response()->json([
            'status' => 200,
            'data' => $user
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeRole(Request $request)
    {
        $project = Project::where("name", $request->project)->first();
        
        $user = User::create([
            'project_id' => $project->id,
            'name' => $request->name,
            'password' => Hash::make('testPassword123'),
            'email' => $request->email,
            'gender' => $request->gender,
            'role' => $request->role,
        ]);

        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('queue_syahril', $user);

        $data = User::where('id', $user->id)->first();
        Redis::flushDB();

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::where("name", $request->project)->first();
        User::where('id', $id)->update([
            'project_id' => $project->id,
        ]);
        
        $data = User::where("id", $id)->first();

        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('queue_syahril', $data);

        Redis::flushDB();

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    } 
}
