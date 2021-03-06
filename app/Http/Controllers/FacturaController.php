<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/* Invocamos la clase Carbon para trabajar con fechas */
use Carbon\Carbon;

use App\OrdenCompra;

use App\MoveOC;

use App\MoveFactura;

/* Invocamos el modelo de la Entidad DetalleSolicitud*/
use App\DetailSolicitud;

use App\Solicitud;

/* Invocamos el modelo de la Entidad Movimiento de la Solicitud*/
use App\MoveSolicitud;

use DB;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dateCarbon = Carbon::now()->locale('es')->isoFormat('dddd D, MMMM YYYY');

        $proveedores = DB::table('proveedores')
                        ->select(DB::raw('CONCAT(proveedores.id, " ) ", proveedores.razonSocial) as RazonSocial'), 'proveedores.id')
                        ->get();

        $ocs = DB::table('orden_compras')
                    ->join('status_o_c_s', 'orden_compras.estado_id', 'status_o_c_s.id')
                    ->select(DB::raw('CONCAT(orden_compras.id, " ) ", orden_compras.ordenCompra_id, " / ", status_o_c_s.estado) as OC'), 'orden_compras.id')
                    ->get();

        $facturas = DB::table('facturas')
                    ->join('status_facturas', 'facturas.estado_id', '=', 'status_facturas.id')
                    ->join('proveedores', 'facturas.proveedor_id', '=', 'proveedores.id')
                    ->join('orden_compras', 'facturas.ordenCompra_id', '=', 'orden_compras.id')
                    ->select('facturas.*', 'proveedores.razonSocial as RazonSocial', 'status_facturas.estado as Estado', 'orden_compras.ordenCompra_id as NoOC')
                    ->get();

        $moveFacturas = DB::table('move_facturas') 
                ->join('facturas', 'move_facturas.factura_id', 'facturas.id')
                ->select('move_facturas.*')
                ->get();

        //dd($facturas);

        return view('siscom.factura.index', compact('facturas', 'proveedores', 'dateCarbon', 'ocs', 'moveFacturas'));
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
    public function store(Request $request)
    {

        try {

                DB::beginTransaction();

                    $factura = new Factura;
                    $factura->factura_id        = $request->factura_id;
                    $factura->iddoc             = $request->iddoc;
                    $factura->tipoDocumento     = $request->tipoDocumento;
                    $factura->proveedor_id      = $request->proveedor_id;
                    $factura->ordenCompra_id    = $request->ordenCompra_id;
                    $factura->totalFactura      = $request->totalFactura;
                    $factura->fechaOficinaParte = $request->fechaOficinaParte;
                    $factura->user_id           = Auth::user()->id;
                    $factura->estado_id         = 1;

                    $factura->save();

                    //Guardamos los datos de Movimientos de la Factura
                    $move = new MoveFactura;
                    $move->factura_id                    = $factura->id;
                    $move->estadoFactura_id             = 1;
                    $move->fecha                        = $factura->created_at;
                    $move->user_id                      = Auth::user()->id;

                    $move->save();

                DB::commit();
                
            } catch (Exception $e) {

                DB::rollback();
                
            }

        

        return back()->with('info', 'Factura Creada con ??xito!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dateCarbon = Carbon::now()->locale('es')->isoFormat('dddd D, MMMM YYYY');

        $proveedores = DB::table('proveedores')
                        ->select(DB::raw('CONCAT(proveedores.id, " ) ", proveedores.razonSocial) as RazonSocial'), 'proveedores.id')
                        ->get();

        $factura = DB::table('facturas')
                    ->join('proveedores', 'facturas.proveedor_id', '=', 'proveedores.id')
                    ->join('orden_compras', 'facturas.ordenCompra_id', '=', 'orden_compras.id')
                    ->join('detail_solicituds', 'orden_compras.id', '=', 'detail_solicituds.ordenCompra_id')
                    ->join('solicituds', 'detail_solicituds.solicitud_id', '=', 'solicituds.id')
                    ->join('users', 'facturas.user_id', '=', 'users.id')
                    ->join('status_facturas', 'facturas.estado_id', '=', 'status_facturas.id')
                    ->join('dependencies', 'users.dependency_id', '=', 'dependencies.id')
                    ->select('facturas.*', 'proveedores.razonSocial as RazonSocial', 'dependencies.name as Dependencia', 'users.name as userName', 'status_facturas.estado as Estado')
                    ->where('facturas.id', '=', $id)
                    ->first();

        $move = DB::table('move_facturas') 
                ->join('status_facturas', 'move_facturas.estadoFactura_id', 'status_facturas.id')               
                ->join('users', 'move_facturas.user_id', 'users.id')
                ->select('move_facturas.*', 'status_facturas.estado as status', 'users.name as name', 'move_facturas.created_at as date')
                ->where('move_facturas.factura_id', '=', $id)
                ->get();

        $detalleSolicituds = DB::table('detail_solicituds')
                    ->join('products', 'detail_solicituds.product_id', 'products.id')
                    ->join('solicituds', 'detail_solicituds.solicitud_id', '=', 'solicituds.id')
                    ->leftjoin('orden_compras', 'detail_solicituds.ordenCompra_id', '=', 'orden_compras.id')
                    ->leftjoin('status_o_c_s', 'orden_compras.estado_id', '=', 'status_o_c_s.id')
                    ->leftjoin('licitacions', 'detail_solicituds.licitacion_id', '=', 'licitacions.id')
                    ->leftjoin('status_licitacions', 'licitacions.estado_id', '=', 'status_licitacions.id')
                    ->select('detail_solicituds.*', 'products.name as Producto', 'orden_compras.ordenCompra_id as NoOC', 'status_o_c_s.estado as EstadoOC', 'licitacions.licitacion_id as NoLicitacion', 'status_licitacions.estado as EstadoLicitacion')
                     ->where('detail_solicituds.ordenCompra_id', $factura->ordenCompra_id)
                    ->get();

        return view('siscom.factura.show', compact('factura', 'proveedores', 'dateCarbon', 'detalleSolicituds', 'move'));

    }

    public function validar($id)
    {
        $dateCarbon = Carbon::now()->locale('es')->isoFormat('dddd D, MMMM YYYY');

        $proveedores = DB::table('proveedores')
                        ->select(DB::raw('CONCAT(proveedores.id, " ) ", proveedores.razonSocial) as RazonSocial'), 'proveedores.id')
                        ->get();

        $factura = DB::table('facturas')
                    ->join('proveedores', 'facturas.proveedor_id', '=', 'proveedores.id')
                    ->join('orden_compras', 'facturas.ordenCompra_id', '=', 'orden_compras.id')
                    ->join('detail_solicituds', 'orden_compras.id', '=', 'detail_solicituds.ordenCompra_id')
                    ->join('solicituds', 'detail_solicituds.solicitud_id', '=', 'solicituds.id')
                    ->join('users', 'facturas.user_id', '=', 'users.id')
                    ->join('dependencies', 'users.dependency_id', '=', 'dependencies.id')
                     ->join('status_facturas', 'facturas.estado_id', '=', 'status_facturas.id')
                    ->select('facturas.*', 'proveedores.razonSocial as RazonSocial', 'dependencies.name as Dependencia', 'users.name as userName', 'status_facturas.estado as Estado')
                    ->where('facturas.id', '=', $id)
                    ->first();

        $move = DB::table('move_facturas') 
                ->join('status_facturas', 'move_facturas.estadoFactura_id', 'status_facturas.id')               
                ->join('users', 'move_facturas.user_id', 'users.id')
                ->select('move_facturas.*', 'status_facturas.estado as status', 'users.name as name', 'move_facturas.created_at as date')
                ->where('move_facturas.factura_id', '=', $id)
                ->get();

        $detalleSolicituds = DB::table('detail_solicituds')
                    ->join('products', 'detail_solicituds.product_id', 'products.id')
                    ->join('solicituds', 'detail_solicituds.solicitud_id', '=', 'solicituds.id')
                    ->leftjoin('orden_compras', 'detail_solicituds.ordenCompra_id', '=', 'orden_compras.id')
                    ->leftjoin('status_o_c_s', 'orden_compras.estado_id', '=', 'status_o_c_s.id')
                    ->leftjoin('licitacions', 'detail_solicituds.licitacion_id', '=', 'licitacions.id')
                    ->leftjoin('status_licitacions', 'licitacions.estado_id', '=', 'status_licitacions.id')
                    ->select('detail_solicituds.*', 'products.name as Producto', 'orden_compras.ordenCompra_id as NoOC', 'status_o_c_s.estado as EstadoOC', 'licitacions.licitacion_id as NoLicitacion', 'status_licitacions.estado as EstadoLicitacion')
                     ->where('detail_solicituds.ordenCompra_id', $factura->ordenCompra_id)
                    ->get();

        return view('siscom.factura.validar', compact('factura', 'proveedores', 'dateCarbon', 'detalleSolicituds', 'move'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if ($request->flag == 'Actualizar') {

            try {

                    DB::beginTransaction();

                        $factura = Factura::findOrFail($id);;
                        $factura->factura_id        = $request->factura_id;
                        $factura->iddoc             = $request->iddoc;
                        $factura->tipoDocumento     = $request->tipoDocumento;
                        $factura->proveedor_id      = $request->proveedor_id;
                        $factura->ordenCompra_id    = $request->ordenCompra_id;
                        $factura->totalFactura      = $request->totalFactura;
                        $factura->fechaOficinaParte = $request->fechaOficinaParte;
                        $factura->user_id           = Auth::user()->id;

                        $factura->save();

                    DB::commit();
                    
            } catch (Exception $e) {

                DB::rollback();
                    
            }

            return back();

        }

        else if ($request->flag == 'FacturarProducto') {

            try {

                DB::beginTransaction();

                    $facturar = DetailSolicitud::findOrFail($id);
                    $facturar->factura_id       = $request->factura_id;
                    $facturar->save();

                    $oc = OrdenCompra::findOrFail($facturar->ordenCompra_id);
                    $oc->estado_id                                  = 21;
                    $oc->save();

                    $move = new MoveOC;
                    $move->ordenCompra_id                           = $facturar->ordenCompra_id;
                    $move->estadoOrdenCompra_id                     = 21;
                    $move->fecha                                    = $facturar->updated_at;
                    $move->user_id                                  = Auth::user()->id;
                    $move->save();

                    $solicitud = Solicitud::findOrFail($facturar->solicitud_id);
                    $solicitud->estado_id                           =10;
                    $solicitud->save();

                    $moveSolicitud = new MoveSolicitud;
                    $moveSolicitud->solicitud_id                     = $facturar->solicitud_id;
                    $moveSolicitud->estadoSolicitud_id               = 10;
                    $moveSolicitud->fecha                            = $facturar->updated_at;
                    $moveSolicitud->user_id                          = Auth::user()->id;
                    $moveSolicitud->save();

                    

                DB::commit();
                
            } catch (Exception $e) {

                db::rollback();
                
            }
            
            return back()->with('info', 'Producto ??rden de Compra Facturado Correctamente!');

        }else if ($request->flag == 'NoFacturarProducto') {

            $nofacturar = DetailSolicitud::findOrFail($id);

            $nofacturar->factura_id       = NULL;
            $nofacturar->save();

            return back()->with('info', 'Producto ??rden de Compra NO Facturado Correctamente!');

        }

        // Facturar TODOS los Productos de la ??rden de Compra 
        else if ($request->flag == 'FacturarTodosProductos') {

            try {

                DB::beginTransaction();

                    $dateCarbon = Carbon::now();

                    //Traemos todos los productos de la OC
                    $fullFactura = DB::table('detail_solicituds')
                                    ->where('detail_solicituds.solicitud_id', '=', $id)
                                    ->count();

                    $parcialFactura = DB::table('detail_solicituds')
                                        ->where('detail_solicituds.solicitud_id', '=', $id)
                                        ->where('detail_solicituds.factura_id', '=', null)
                                        ->count();


                    if ($fullFactura == $parcialFactura) {
                        
                        $dSolicitud = DetailSolicitud::where('ordenCompra_id', $id);    
                        $dSolicitud->update(['factura_id'=> $request->factura_id]);

                        //Buscamos la Solicitud relacionada con la OC a recepcionar
                        $s = DB::table('solicituds')
                                    ->join('detail_solicituds', 'solicituds.id', '=', 'detail_solicituds.solicitud_id')
                                    ->join('orden_compras', 'detail_solicituds.ordenCompra_id', '=', 'orden_compras.id')
                                    ->where('detail_solicituds.ordenCompra_id', '=', $id)
                                    ->first();

                        //dd($s->id);
                        //Actualizmos el estado de la Solicitud
                        $solicitud = Solicitud::findOrFail($s->solicitud_id);             
                        $solicitud->estado_id                   = 10;
                        $solicitud->update();

                        //Actualizamos el estado de la OC
                        $oc = OrdenCompra::findOrFail($s->id);
                        $oc->estado_id                          = 20;
                        $oc->save();


                        //Guardamos el Movimientos de la Solicitud
                        $move = new MoveSolicitud;
                        $move->solicitud_id                     = $solicitud->id;
                        $move->estadoSolicitud_id               = 10;
                        $move->fecha                            = $solicitud->updated_at;
                        $move->user_id                          = Auth::user()->id;
                        $move->save();

                        //Guardamos el Movimientos de la OC
                        $move = new MoveOC;
                        $move->ordenCompra_id                   = $oc->id;
                        $move->estadoOrdenCompra_id             = 20;
                        $move->fecha                            = $oc->updated_at;
                        $move->user_id                          = Auth::user()->id;
                        $move->save();      

                    }

                    

                DB::commit();
                
            } catch (Exception $e) {

                db::rollback();
                
            }

            return redirect('/siscom/factura')->with('info', 'Productos de ??rden de Compra Facturados con ??xito !');
        }  

        // Enviada a VB
        else if ($request->flag == 'EnviadaVB') {

            try {

                DB::beginTransaction();

                    $factura = Factura::findOrFail($id);
                    $factura->estado_id                  = 2;
                    $factura->save();

                    //Guardamos los datos de Movimientos de la Factura
                    $move = new MoveFactura;
                    $move->factura_id                    = $factura->id;
                    $move->estadoFactura_id              = 2;
                    $move->fecha                         = $factura->updated_at;
                    $move->user_id                       = Auth::user()->id;

                    $move->save();


                DB::commit();
                
            } catch (Exception $e) {

                db::rollback();
                
            }

            return back();
        } 

        // Recepcionada con VB
        else if ($request->flag == 'RecepcionarVB') {

            try {

                DB::beginTransaction();

                    $factura = Factura::findOrFail($id);
                    $factura->estado_id                  = 3;
                    $factura->save();

                    //Guardamos los datos de Movimientos de la Factura
                    $move = new MoveFactura;
                    $move->factura_id                    = $factura->id;
                    $move->estadoFactura_id              = 3;
                    $move->fecha                         = $factura->updated_at;
                    $move->user_id                       = Auth::user()->id;

                    $move->save();


                DB::commit();
                
            } catch (Exception $e) {

                db::rollback();
                
            }

            return back();
        } 

        // Enviada a VB
        else if ($request->flag == 'EnviarPago') {

            try {

                DB::beginTransaction();

                    $factura = Factura::findOrFail($id);
                    $factura->estado_id                  = 4;
                    $factura->save();

                    //Guardamos los datos de Movimientos de la Factura
                    $move = new MoveFactura;
                    $move->factura_id                    = $factura->id;
                    $move->estadoFactura_id              = 4;
                    $move->fecha                         = $factura->updated_at;
                    $move->user_id                       = Auth::user()->id;

                    $move->save();


                DB::commit();
                
            } catch (Exception $e) {

                db::rollback();
                
            }

            return back();
        } 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        $factura->delete();

        return back()->with('info', 'Factura Eliminada correctamente !');
    }
}
