<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Role::with('permissions');

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $role = $role->orderBy($request->get('sortBy'), 'desc');
            }else{
                $role = $role->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $role = $role->orderBy('id', 'desc');
        }

        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $role = $role->where('name', 'LIKE', "%$search%")
        ->orWhere('description', 'LIKE', "%$search%")
        ->orderBy('id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'roles' => $role,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Permission::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $permissions = $request->permissions;

        if(!$permissions == []){
            $role = new Role();
            $role->name = $request->name;
            $role->description = $request->description;
            $role->save();
            $role->givePermissionTo(collect($request->permissions)->pluck('id')->toArray());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return [
            'role' => $role,
            'permissions' => Permission::all(),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $permissions = $request->permissions;

        if(!$permissions == []){
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->description = $request->description;
            $role->save();
            $role->syncPermissions(collect($request->permissions)->pluck('id')->toArray());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
    }
}
