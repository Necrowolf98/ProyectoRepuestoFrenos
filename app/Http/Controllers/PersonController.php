<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\StoreRequest;
use App\Http\Requests\Person\UpdateRequest;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $person = new Person();

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $person = $person->orderBy($request->get('sortBy'), 'desc');
            }else{
                $person = $person->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $person = $person->orderBy('id', 'desc');
        }


        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $person = $person->where('fullname', 'LIKE', "%$search%")
        ->orWhere('number_document', 'LIKE', "%$search%")
        ->orWhere('email', 'LIKE', "%$search%")
        ->orderBy('id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'persons' => $person,
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $name = explode(" ",$request->name);
        $fullname = $name[0].' '.$request->lastname;

        $person = Person::findOrFail($id);
        $person->name = $request->name;
        $person->lastname = $request->lastname;
        $person->fullname = $fullname;
        $person->type_document = $request->type_document;
        $person->number_document = $request->number_document;
        $person->direction = $request->direction;
        $person->phone = $request->phone;
        $person->email = $request->email;
        $person->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();
    }
}
