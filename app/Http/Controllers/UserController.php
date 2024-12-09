<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\rabbitMQServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Models\Gender;
use App\Models\Roles;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $data;

    public function listener()
    { return view('users'); }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->with(['rolesType', 'genderType'])->get()->map(function ($q) {
            $this->data['id'] = $q->id;
            $this->data['username'] = $q->username;
            $this->data['name'] = $q->name;
            $this->data['gender'] = $q->genderType->name;
            $this->data['role'] = $q->rolesType->name;

            return $this->data;
        });

        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('users_collection', $users);

        return $users;
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $role = Roles::where('name', '=', Str::title($request->role))->first()->id;
        $gender = Gender::where('name', '=', Str::title($request->gender))->first()->id;

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->username.'123'),
            'name' => $request->name,
            'roles_id' => $role,
            'gender_id' => $gender
        ]);

        $users = User::with(['rolesType', 'genderType'])->get()->map(function ($q) {
            $this->data['id'] = $q->id;
            $this->data['username'] = $q->username;
            $this->data['name'] = $q->name;
            $this->data['gender'] = $q->genderType->name;
            $this->data['role'] = $q->rolesType->name;

            return $this->data;
        });

        Redis::flushDB();

        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->sendMessages('users_collection', $users);

        return $user;
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
        if(User::find($id) === null)
        { return response()->json([ 'status' => 404, 'message' => "Users Not Found" ], 404); }
        else {
            $role = Roles::where('name', '=', Str::title($request->role))->first()->id;

            User::where('id', '=', $id)->update(['roles_id' => $role]);
            $user = User::where('id','=', $id)->first();

            $users = User::with(['rolesType', 'genderType'])->get()->map(function ($q) {
                $this->data['id'] = $q->id;
                $this->data['username'] = $q->username;
                $this->data['name'] = $q->name;
                $this->data['gender'] = $q->genderType->name;
                $this->data['role'] = $q->rolesType->name;
                return $this->data;
            });

            Redis::flushDB();

            $rabbitMQServices = new rabbitMQServices();
            $rabbitMQServices->sendMessages('users_collection', $users);

            return [
                    'id' =>  $user->id,
                    'username' =>  $user->username,
                    'name' =>  $user->name,
                    'gender' =>  $user->genderType->name,
                    'role' =>  $user->rolesType->name ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(User::find($id) === null)
        { return response()->json([ 'status' => 404, 'message' => "Users Not Found" ], 404); }
        else {
            User::where('id', '=', $id)->delete();

            Redis::flushDB();

            $users = User::with(['rolesType', 'genderType'])->get()->map(function ($q) {
                $this->data['id'] = $q->id;
                $this->data['username'] = $q->username;
                $this->data['name'] = $q->name;
                $this->data['gender'] = $q->genderType->name;
                $this->data['role'] = $q->rolesType->name;
                return $this->data;
            });

            $rabbitMQServices = new rabbitMQServices();
            $rabbitMQServices->sendMessages('users_collection', $users);

            return $users;
        }
    }
}
