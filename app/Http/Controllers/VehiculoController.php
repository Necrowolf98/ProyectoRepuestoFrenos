<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehiculo\StoreRequest;
use App\Http\Requests\Vehiculo\UpdateRequest;
use App\Models\Marca;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use App\Models\RepuestoFreno;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vehiculo = Vehiculo::join('repuesto_frenos', 'repuesto_frenos.id', '=', 'vehiculos.repuestofreno_id')
        ->join('descripcion_repuestos', 'descripcion_repuestos.repuestofreno_id', '=', 'repuesto_frenos.id')
        ->join('marcas', 'marcas.id', '=', 'vehiculos.casa_marca_id')
        ->select('vehiculos.id', 'vehiculos.modelo', 'vehiculos.anio_vehiculo', 'vehiculos.repuestofreno_id', 'vehiculos.casa_marca_id', 'marcas.casa_marca', 'repuesto_frenos.id as id_repuestofreno', 'repuesto_frenos.codigo', 'repuesto_frenos.descripcion', 'descripcion_repuestos.id as id_descripcionrepuesto', 'descripcion_repuestos.repuestofreno_id','descripcion_repuestos.clase', 'descripcion_repuestos.medidas', 'descripcion_repuestos.posicion');

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $vehiculo = $vehiculo->orderBy($request->get('sortBy'), 'desc');
            }else{
                $vehiculo = $vehiculo->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $vehiculo = $vehiculo->orderBy('vehiculos.id', 'desc');
        }

        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');
        $tipo_clase = $request->get('tipo_clase');

        $vehiculo = $vehiculo->where([
            ['descripcion_repuestos.clase', $tipo_clase],
            ['marcas.casa_marca', 'LIKE', "%$search%"]
        ])->orWhere([
            ['descripcion_repuestos.clase', $tipo_clase],
            ['vehiculos.modelo', 'LIKE', "%$search%"]
        ])->orWhere([
            ['descripcion_repuestos.clase', $tipo_clase],
            ['vehiculos.anio_vehiculo', 'LIKE', "%$search%"]
        ])->orWhere([
            ['descripcion_repuestos.clase', $tipo_clase],
            ['repuesto_frenos.codigo', 'LIKE', "%$search%"]
        ])->orWhere([
            ['descripcion_repuestos.clase', $tipo_clase],
            ['repuesto_frenos.descripcion', 'LIKE', "%$search%"]
        ])->orWhere([
            ['descripcion_repuestos.clase', $tipo_clase],
            ['descripcion_repuestos.medidas', 'LIKE', "%$search%"]
        ])->orWhere([
            ['descripcion_repuestos.clase', $tipo_clase],
            ['descripcion_repuestos.posicion', 'LIKE', "%$search%"]
        ])->orderBy('repuesto_frenos.id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'vehiculo' => $vehiculo,
            'marca' => Marca::all(),
            'repuesto' => RepuestoFreno::all()
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
        $vehiculo = new Vehiculo();

        $vehiculo->repuestofreno_id = $request->repuestofreno_id;
        $vehiculo->casa_marca_id = $request->casa_marca_id;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->anio_vehiculo = $request->anio_vehiculo;
        $vehiculo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(Vehiculo $vehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehiculo $vehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->repuestofreno_id = $request->repuestofreno_id;
        $vehiculo->casa_marca_id = $request->casa_marca_id;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->anio_vehiculo = $request->anio_vehiculo;
        $vehiculo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();
    }
}
