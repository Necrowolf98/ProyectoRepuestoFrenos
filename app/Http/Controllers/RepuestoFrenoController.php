<?php

namespace App\Http\Controllers;

use App\Http\Requests\Repuesto\StoreRequest;
use App\Http\Requests\Repuesto\UpdateRequest;
use App\Models\FrenoRepuesto;
use Illuminate\Http\Request;
use App\Models\RepuestoFreno;
use Illuminate\Support\Facades\DB;
use Exception;

class RepuestoFrenoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repuesto = RepuestoFreno::join('descripcion_repuestos', 'descripcion_repuestos.repuestofreno_id', '=', 'repuesto_frenos.id')
        ->select('repuesto_frenos.id', 'repuesto_frenos.codigo', 'repuesto_frenos.descripcion', 'repuesto_frenos.compatibilidad', 'descripcion_repuestos.id as descripcion_id', 'descripcion_repuestos.repuestofreno_id', 'descripcion_repuestos.clase', 'descripcion_repuestos.medidas', 'descripcion_repuestos.posicion');

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $repuesto = $repuesto->orderBy($request->get('sortBy'), 'desc');
            }else{
                $repuesto = $repuesto->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $repuesto = $repuesto->orderBy('repuesto_frenos.id', 'desc');
        }

        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $repuesto = $repuesto->where('repuesto_frenos.codigo', 'LIKE', "%$search%")
        ->orWhere('repuesto_frenos.compatibilidad', 'LIKE', "%$search%")
        ->orWhere('repuesto_frenos.descripcion', 'LIKE', "%$search%")
        ->orWhere('descripcion_repuestos.clase', 'LIKE', "%$search%")
        ->orWhere('descripcion_repuestos.medidas', 'LIKE', "%$search%")
        ->orWhere('descripcion_repuestos.posicion', 'LIKE', "%$search%")
        ->orderBy('repuesto_frenos.id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'repuesto' => $repuesto
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
            $repuesto = new RepuestoFreno();
            $repuesto->codigo = $request->codigo;
            $repuesto->descripcion = $request->descripcion;
            $repuesto->compatibilidad = $request->compatibilidad;
            $repuesto->save();

            $freno_repuesto = new FrenoRepuesto();
            $freno_repuesto->repuestofreno_id = $repuesto->id;
            $freno_repuesto->clase = $request->clase;
            $freno_repuesto->medidas = $request->medidas;
            $freno_repuesto->posicion = $request->posicion;

            $freno_repuesto->save();


            DB::commit();
        }catch(Exception $e){
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {

            $repuesto = RepuestoFreno::findOrFail($id);
            $repuesto->codigo = $request->codigo;
            $repuesto->descripcion = $request->descripcion;
            $repuesto->compatibilidad = $request->compatibilidad;
            $repuesto->save();

            $freno_repuesto = FrenoRepuesto::findOrFail($request->descripcion_id);
            $freno_repuesto->repuestofreno_id = $repuesto->id;
            $freno_repuesto->clase = $request->clase;
            $freno_repuesto->medidas = $request->medidas;
            $freno_repuesto->posicion = $request->posicion;

            $freno_repuesto->save();

 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            $repuesto = RepuestoFreno::findOrFail($id);
            $repuesto->delete();

            $freno_repuesto = FrenoRepuesto::findOrFail($request->descripcion_id);
            $freno_repuesto->delete();

        }catch(Exception $e){
            DB::rollback();
        }
    }
}
