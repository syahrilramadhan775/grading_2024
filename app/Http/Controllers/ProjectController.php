<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\rabbitMQServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::with("users")->get();
        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('queue_syahril', $project);

        if (Redis::get('redis_syahril') === null){
            Redis::set('redis_syahril', $project, 'EX', 7600);
            return response()->json([
                'status' => 200,
                'message' => 'send redis set',
                'data' => json_decode(Redis::get('redis_syahril'), true)
            ], 200);
        }

        return response()->json([
                'status' => 200,
                'message' => 'get by redis',
                'data' => json_decode(Redis::get('redis_syahril'), true)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'date_start' => $request->start,
            'date_end' => $request->end
        ]);

        $data = Project::where("id", $project->id)->first();
        return response()->json([
            'id' => $data->id,
            'name' => $data->name
        ], 201);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
