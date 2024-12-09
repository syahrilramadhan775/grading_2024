<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Models\Project;
use App\Services\rabbitMQServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;

class ProjectController extends Controller
{

    public function listener()
    {
        return view('projects');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $project = Project::orderBy('id', 'asc')->get(['id','name']);

        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('projects_collection', $project);

        return $project;
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

        Redis::flushDB();

        $projects = Project::orderBy('id', 'asc')->get(['id', 'name']);
        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('projects_collection', $projects);

        return response()->json([
            'id' => $project->id,
            'name' => $project->name
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
        if(Project::find($id) === null)
        { return response()->json([ 'status' => 404, 'message' => "Projects Not Found" ], 404); }
        else {
            Project::where('id', '=', $id)->update([ 'name' => $request->name ]);
            $project = Project::where('id','=', $id)->first();

            Redis::flushDB();

            $projects = Project::orderBy('id', 'asc')->get(['id', 'name']);
            $rabbitMQServices = new rabbitMQServices();
            $rabbitMQServices->sendMessages('projects_collection', $projects);

            return response()->json([
                'id' => $project->id,
                'name' => $project->name
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Project::find($id) === null)
        { return response()->json([ 'status' => 404, 'message' => "Projects Not Found" ], 404); }
        else {
            Project::where('id', '=', $id)->delete();

            Redis::flushDB();

            $projects = Project::orderBy('id', 'asc')->get(['id', 'name']);
            $rabbitMQServices = new rabbitMQServices();
            $rabbitMQServices->sendMessages('projects_collection', $projects);

            return $projects;
        }
    }
}
