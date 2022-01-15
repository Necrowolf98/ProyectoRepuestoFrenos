<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Permission;
use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->join('people', 'people.id', '=', 'users.people_id')
        ->select('people.name', 'people.lastname', 'people.fullname', 'people.type_document', 'people.number_document', 'people.direction', 'people.phone', 'people.email', 'users.username','users.id as user_id','users.people_id', 'roles.name as role_name', 'roles.id as role_id');

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $user = $user->orderBy($request->get('sortBy'), 'desc');
            }else{
                $user = $user->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $user = $user->orderBy('users.id', 'desc');
        }

        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $user = $user->where('people.fullname', 'LIKE', "%$search%")
        ->orWhere('people.number_document', 'LIKE', "%$search%")
        ->orWhere('people.email', 'LIKE', "%$search%")
        ->orWhere('users.username', 'LIKE', "%$search%")
        ->orWhere('roles.name', 'LIKE', "%$search%")
        ->orderBy('users.people_id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'users' => $user,
            'roles' => Role::all(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try{
            DB::beginTransaction();

            $name = explode(" ",$request->name);
            $fullname = $name[0].' '.$request->lastname;

            $person = new Person();
            $person->name = $request->name;
            $person->lastname = $request->lastname;
            $person->fullname = $fullname;
            $person->type_document = $request->type_document;
            $person->number_document = $request->number_document;
            $person->direction = $request->direction;
            $person->phone = $request->phone;
            $person->email = $request->email;
            $person->save();

            $user = new User();
            $user->people_id = $person->id;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->save();

            $user->assignRole($request->role_id);

            DB::commit();
        }catch(Exception $e){
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('people', 'roles', 'permissions')->findOrFail($id);
        $user_permissions = $user->getPermissionsViaRoles();
        $permissions = Permission::all();

        return [
            'user' => $user,
            'user_permissions' => $user_permissions,
            'permissions' => $permissions
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            DB::beginTransaction();

            $name = explode(" ",$request->name);
            $fullname = $name[0].' '.$request->lastname;

            $person = Person::findOrFail($request->people_id);

            $person->name = $request->name;
            $person->lastname = $request->lastname;
            $person->fullname = $fullname;
            $person->type_document = $request->type_document;
            $person->number_document = $request->number_document;
            $person->direction = $request->direction;
            $person->phone = $request->phone;
            $person->email = $request->email;
            $person->save();

            $user = User::findOrFail($id);

            if(empty($request->password)){
                $user->people_id = $person->id;
                $user->username = $request->username;
                $user->save();
            }else{
                $user->people_id = $person->id;
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->save();
            }

            $user->syncRoles($request->role_id);

            DB::commit();
        }catch(Exception $e){
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();

            $people = Person::findOrFail($request->people_id);
            $people->delete();

        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function updatePermissionsByUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncPermissions(collect($request->permissions)->pluck('id')->toArray());
    }
}
