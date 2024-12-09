<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\rabbitMQServices;
use Illuminate\Http\Request;

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
    // Before Recursive
    // public function index(){
    //     $users = Task::where([['parent_id', '=', null], ['sub_parent_id', '=', null]])->with(['users','projects'])->get()->map(function ($q) {
    //         $this->data['id'] = $q->id;
    //         $this->data['name'] = $q->name;
    //         $this->data['status'] = $q->status;
    //         $this->data['startTime'] = $q->start_time;
    //         $this->data['endTime'] = $q->end_time;
    //         $this->data['usersName'] = $q->usersName;
    //         $this->data['projectName'] = $q->projectName;
    //         $this->data['subTask'] = $q->parentTask->where('parent_id', '=', $q->id)->where('sub_parent_id', '=', null)->map(function ($subTask) {
    //             return [
    //                 'id' => $subTask->id,
    //                 'name' => $subTask->name,
    //                 'parent_id' => $subTask->parent_id,
    //                 'status' => $subTask->status,
    //                 'startTime' => $subTask->start_time,
    //                 'endTime' => $subTask->end_time,
    //                 'usersName' => $subTask->usersName,
    //                 'childTask' => $subTask->subChildTask->where('sub_parent_id', '=', $subTask->id)->map(function ($childTask) {
    //                     return [
    //                         'id' => $childTask->id,
    //                         'name' => $childTask->name,
    //                         'parent_id' => $childTask->parent_id,
    //                         'sub_parent_id' => $childTask->sub_parent_id,
    //                         'status' => $childTask->status,
    //                         'startTime' => $childTask->start_time,
    //                         'endTime' => $childTask->end_time,
    //                         'usersName' => $childTask->usersName,
    //                     ];
    //                 })
    //             ];
    //         });
    //         return $this->data;
    //     });

    //     $rabbitMQServices = new rabbitMQServices();
    //     $rabbitMQServices->sendMessages('tasks_collection', $users);

    //     return $users;
    // }

    // After Recursive
    public function index(){
        $users = Task::where('parent_id', '=', null)->with(['users','projects'])->get()->map(function ($q) {
            $this->data['id'] = $q->id;
            $this->data['name'] = $q->name;
            $this->data['status'] = $q->status;
            $this->data['start_time'] = $q->start_time;
            $this->data['end_time'] = $q->end_time;
            $this->data['users_id'] = $q->users_id;
            $this->data['project_id'] = $q->project_id;
            $this->data['subTask'] = $q->child->map(function ($q1) {
                return [
                    'id'            =>  $q1->id,
                    'name'          =>  $q1->name,
                    'status'        =>  $q1->status,
                    'startTime'     =>  $q1->start_time,
                    'endTime'       =>  $q1->end_time,
                    'usersName'     =>  $q1->usersName,
                    'projectName'   =>  $q1->projectName,
                    'childTask'     =>  $q1->child
                ];
            });
            return $this->data;
        });

        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('tasks_collection', $users);

        return $users;
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
            'parent_id'     =>  $task->parent_id,
            'name'          =>  $task->name,
            'status'        =>  $task->status,
            'startTime'     =>  $task->start_time,
            'endTime'       =>  $task->end_time,
            'usersName'     =>  $task->usersName,
            'projectName'   =>  $task->projectName,
        ];
    }

    public function storeSubParent(Request $request)
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
            'parent_id'     =>  $data->parent_id,
            'parentTask'    =>  $task->name,
            'name'          =>  $data->name,
            'status'        =>  $data->status,
            'startTime'     =>  $data->start_time,
            'endTime'       =>  $data->end_time,
            'usersName'     =>  $data->usersName,
            'projectName'   =>  $data->projectName,
        ];
    }

    // public function storeChild(Request $request)
    // {
    //     $users = User::where('username', '=', $request->username)->first('id')->id;
    //     $projects = Project::where('name', '=', $request->project)->first('id')->id;
    //     $parentTask = Task::where([['name', '=', $request->task], ['parent_id', '=', null], ['sub_parent_id', '=', null]])->first();
    //     $subTask = Task::where([['name', '=', $request->subTask], ['parent_id', '!=', null], ['sub_parent_id', '=', null]])->first();

    //     $task = Task::create([
    //         'name'          =>  $request->name,
    //         'status'        =>  $request->status,
    //         'start_time'    =>  $request->start_time,
    //         'end_time'      =>  $request->end_time,
    //         'users_id'      =>  $users,
    //         'project_id'    =>  $projects,
    //         'parent_id'     =>  $parentTask->id,
    //         'sub_parent_id' =>  $subTask->id,
    //     ]);

    //     return [
    //         'id'                =>  $task->id,
    //         'parent_id'         =>  $parentTask->id,
    //         'parentTask'        =>  $parentTask->name,
    //         'sub_parent_id'     =>  $subTask->id,
    //         'subParentTask'     =>  $subTask->name,
    //         'name'              =>  $task->name,
    //         'status'            =>  $task->status,
    //         'startTime'         =>  $task->start_time,
    //         'endTime'           =>  $task->end_time,
    //         'usersName'         =>  $task->usersName,
    //         'projectName'       =>  $task->projectName,
    //     ];
    // }

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

            $users = Task::orderBy('id', 'asc')->where([['parent_id', '=', null], ['sub_parent_id', '=', null]])->with(['users','projects'])->get()->map(function ($q) {
                $this->data['id'] = $q->id;
                $this->data['name'] = $q->name;
                $this->data['status'] = $q->status;
                $this->data['startTime'] = $q->start_time;
                $this->data['endTime'] = $q->end_time;
                $this->data['usersName'] = $q->usersName;
                $this->data['projectName'] = $q->projectName;
                $this->data['subTask'] = $q->parentTask->where('parent_id', '=', $q->id)->where('sub_parent_id', '=', null)->map(function ($subTask) {
                    return [
                        'id' => $subTask->id,
                        'name' => $subTask->name,
                        'parent_id' => $subTask->parent_id,
                        'status' => $subTask->status,
                        'startTime' => $subTask->start_time,
                        'endTime' => $subTask->end_time,
                        'usersName' => $subTask->usersName,
                        'childTask' => $subTask->subChildTask->where('sub_parent_id', '=', $subTask->id)->map(function ($childTask) {
                            return [
                                'id' => $childTask->id,
                                'name' => $childTask->name,
                                'parent_id' => $childTask->parent_id,
                                'sub_parent_id' => $childTask->sub_parent_id,
                                'status' => $childTask->status,
                                'startTime' => $childTask->start_time,
                                'endTime' => $childTask->end_time,
                                'usersName' => $childTask->usersName,
                            ];
                        })
                    ];
                });
                return $this->data;
            });

            $rabbitMQServices = new rabbitMQServices();
            $rabbitMQServices->sendMessages('tasks_collection', $users);

            return response()->json([
                'status' => 200,
                'message' => 'Update Status Success'
            ], 200);
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
