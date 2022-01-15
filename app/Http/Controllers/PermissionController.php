<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permission = new Permission();

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $permission = $permission->orderBy($request->get('sortBy'), 'desc');
            }else{
                $permission = $permission->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $permission = $permission->orderBy('id', 'desc');
        }


        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $permission = $permission->where('name', 'LIKE', "%$search%")
        ->orWhere('description', 'LIKE', "%$search%")
        ->orderBy('id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'permissions' => $permission,
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
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->description = $request->description;
        $permission->guard_name = $request->guard_name;
        $permission->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->description = $request->description;
        $permission->guard_name = $request->guard_name;
        $permission->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
    }
}
