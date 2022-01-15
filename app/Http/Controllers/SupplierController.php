<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $supplier = new Supplier();

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $supplier = $supplier->orderBy($request->get('sortBy'), 'desc');
            }else{
                $supplier = $supplier->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $supplier = $supplier->orderBy('id', 'desc');
        }


        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $supplier = $supplier->where('name', 'LIKE', "%$search%")
        ->orWhere('number_document', 'LIKE', "%$search%")
        ->orWhere('in_charge', 'LIKE', "%$search%")
        ->orderBy('id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'suppliers' => $supplier,
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
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->type_document = $request->type_document;
        $supplier->number_document = $request->number_document;
        $supplier->direction = $request->direction;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->in_charge = $request->in_charge;
        $supplier->phone_in_charge = $request->phone_in_charge;
        $supplier->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->name = $request->name;
        $supplier->type_document = $request->type_document;
        $supplier->number_document = $request->number_document;
        $supplier->direction = $request->direction;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->in_charge = $request->in_charge;
        $supplier->phone_in_charge = $request->phone_in_charge;
        $supplier->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    }
}
