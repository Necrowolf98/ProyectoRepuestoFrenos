<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Person;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $income = Income::join('suppliers', 'suppliers.id', '=', 'incomes.supplier_id')
        ->join('people', 'people.id', '=', 'incomes.people_id')
        ->select('people.id as people_id', 'people.fullname', 'suppliers.id as supplier_id', 'suppliers.name', 'incomes.*');

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $income = $income->orderBy($request->get('sortBy'), 'desc');
            }else{
                $income = $income->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $income = $income->orderBy('incomes.id', 'desc');
        }

        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $income = $income->where('people.fullname', 'LIKE', "%$search%")
        ->orWhere('suppliers.name', 'LIKE', "%$search%")
        ->orWhere('people.email', 'LIKE', "%$search%")
        ->orWhere('incomes.type_voucher', 'LIKE', "%$search%")
        ->orWhere('incomes.serie_voucher', 'LIKE', "%$search%")
        ->orWhere('incomes.number_voucher', 'LIKE', "%$search%")
        ->orderBy('incomes.id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'income' => $income,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = Person::all();
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }
}
