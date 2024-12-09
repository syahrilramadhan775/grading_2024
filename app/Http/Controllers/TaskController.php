<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\rabbitMQServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TaskController extends Controller
{
    private $data;

    public function listener()
    {
        return view('tasks');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $tasks = Task::where('parent_id', '=', null)->with(['users','projects'])->get()->map(function ($q) {
            $this->data['id'] = $q->id;
            $this->data['name'] = $q->name;
            $this->data['status'] = $q->status;
            $this->data['startTime'] = $q->start_time;
            $this->data['endTime'] = $q->end_time;
            $this->data['usersId'] = $q->users_id;
            $this->data['usersName'] = $q->usersName;
            $this->data['project_id'] = $q->project_id;
            $this->data['projectName'] = $q->projectName;
            $this->data['child'] = $this->mapping($q->child);

            return $this->data;
        });

        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('tasks_collection', $tasks);

        return $tasks;
    }

    private function mapping($array){
        return $array->map(function ($data) {
            return [
                'id'            =>  $data->id,
                'name'          =>  $data->name,
                'parentId'      =>  $data->parent_id,
                'status'        =>  $data->status,
                'startTime'     =>  $data->start_time,
                'endTime'       =>  $data->end_time,
                'usersId'       =>  $data->users_id,
                'usersName'     =>  $data->usersName,
                'projectName'   =>  $data->projectName,
                'child'         =>  $this->mapping($data->child)
            ];
        });

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users = User::where('username', '=', $request->username)->first('id')->id;
        $projects = Project::where('name', '=', $request->project)->first('id')->id;
        $task = Task::create([
            'name'          =>  $request->name,
            'status'        =>  $request->status,
            'start_time'    =>  $request->start_time,
            'end_time'      =>  $request->end_time,
            'users_id'      =>  $users,
            'project_id'    =>  $projects,
        ]);

        return [
            'id'            =>  $task->id,
            'parentId'      =>  $task->parent_id,
            'name'          =>  $task->name,
            'status'        =>  $task->status,
            'startTime'     =>  $task->start_time,
            'endTime'       =>  $task->end_time,
            'usersName'     =>  $task->usersName,
            'projectName'   =>  $task->projectName,
        ];
    }

    public function storeChild(Request $request)
    {
        $users = User::where('username', '=', $request->username)->first('id')->id;
        $projects = Project::where('name', '=', $request->project)->first('id')->id;
        $task = Task::where('name', '=', $request->task)->first();

        $data = Task::create([
            'name'          =>  $request->name,
            'status'        =>  $request->status,
            'start_time'    =>  $request->start_time,
            'end_time'      =>  $request->end_time,
            'users_id'      =>  $users,
            'project_id'    =>  $projects,
            'parent_id'     =>  $task->id,
        ]);

        return [
            'id'            =>  $data->id,
            'parentId'      =>  $data->parent_id,
            'parentName'    =>  $task->name,
            'name'          =>  $data->name,
            'status'        =>  $data->status,
            'startTime'     =>  $data->start_time,
            'endTime'       =>  $data->end_time,
            'usersName'     =>  $data->usersName,
            'projectName'   =>  $data->projectName,
        ];
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
        if(Task::find($id) === null)
        { return response()->json([ 'status' => 404, 'message' => "Tasks Not Found" ], 404); }
        else {
            Task::where('id', '=', $id)->update([
                'status' => $request->status
            ]);

            Redis::flushDB();

            $tasks = Task::where('parent_id', '=', null)->with(['users','projects'])->get()->map(function ($q) {
                $this->data['id'] = $q->id;
                $this->data['name'] = $q->name;
                $this->data['status'] = $q->status;
                $this->data['startTime'] = $q->start_time;
                $this->data['endTime'] = $q->end_time;
                $this->data['usersId'] = $q->users_id;
                $this->data['usersName'] = $q->usersName;
                $this->data['project_id'] = $q->project_id;
                $this->data['projectName'] = $q->projectName;
                $this->data['child'] = $this->mapping($q->child);

                return $this->data;
            });
            $rabbitMQServices = new rabbitMQServices();
            $rabbitMQServices->sendMessages('tasks_collection', $tasks);

            return $tasks;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
