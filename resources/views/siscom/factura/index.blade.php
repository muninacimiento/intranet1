<!--
/*
 *  JFuentealba @itux
 *  created at December 26, 2019 - 11:28 pm
 *  updated at 
 */
-->

@extends('layouts.app')

@section('content')

<div id="allWindow">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card border-primary shadow">

                <div class="card-header text-center text-white bg-primary">

                    @include('siscom.menu')

                </div>


                <div class="card-body">

                    <div class="row mt-5">

                        <div class="col-md-6 text-center">
                            
                            <h3>Gestión de Facturas</h3>

                            <div class="text-secondary">

                                

                            </div>

                        </div>

                        <!-- Button trigger CrearSolicitudModal -->
                        <div class="col-md-6">
                            
                            <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#createFacturaModal">

                                <button class="btn btn-success btn-block boton">

                                    <i class="fas fa-file-invoice-dollar px-2"></i>

                                    Nueva Factura

                                </button>

                            </a>
                            
                        </div>

                    </div>

                    <hr class="my-4">

                    @if (session('info'))

                        <div class="alert alert-success alert-dismissible fade show shadow mb-3" role="alert">
                              
                            <i class="fas fa-check-circle"></i>
                             
                            <strong> {{ session('info') }} </strong>
                            
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            
                                <span aria-hidden="true">&times;</span>
                              
                            </button>

                        </div>
                   
                    @endif

                    
                    <div>

                        <table class="display" id="facturasTable" style="font-size: 0.8em;" width="100%">

                            <thead>

                                <tr class="table-active">

                                    <th style="display:none;">ID</th>

                                    <th>No. Factura</th>

                                    <th>No. OC</th>

                                    <th>Estado</th>

                                    <th>Unidad Solicitante</th>

                                    <th>Tipo Documento</th>

                                    <th>Proveedor</th>

                                    <th>Total $</th>

                                    <th>Acciones</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($facturas as $factura)

                                <tr>

                                    <td style="display: none;">{{ $factura->id }}</td>

                                    <td>{{ $factura->factura_id }}</td>

                                    <td>{{ $factura->ordenCompra_id }}</td>

                                    <td>{{ $factura->Estado }}</td>

                                    <td>{{ $factura->Dependencia }}</td>

                                    <td>{{ $factura->tipoDocumento }}</td>

                                    <td>{{ $factura->RazonSocial }}</td>

                                    <td>{{ $factura->totalFactura }}</td>

                                    <td>

                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <a href="{{ route('factura.show', $factura->id) }}" class="btn btn-outline-secondary btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Ver Detalle de la Factura">
                                        
                                                <i class="fas fa-eye"></i>

                                            </a>

                                            <a href="#" class="btn btn-outline-primary btn-sm mr-1 edit" data-toggle="tooltip" data-placement="bottom" title="Modificar Proveedor">
                                        
                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <a href="#" class="btn btn-outline-danger btn-sm delete" data-toggle="tooltip" data-placement="bottom" title="Eliminar Proveedor">

                                                <i class="fas fa-trash"></i>

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="form-row">

                        <div class="col-md-12 mb-2">
                                
                            <form method="POST" action="{{ route('ordenCompra.confirmarRecepcion', $ordenCompra->id) }}">

                                @csrf
                                @method('PUT')

                                <input type="hidden" name="flag" value="FacturarTodosProductos">

                                <button type="submit" class="btn btn-success btn-block"> 

                                    <i class="fas fa-check-circle"></i>

                                    Confirmar Facturación de TODOS los Productos de la O.C.

                                </button>

                            </form>    

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Modal Create Factura -->
<div class="modal fade" id="createFacturaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header bg-success text-white">

                <p class="modal-title" id="exampleModalLabel" style="font-size: 1.2em"><i class="fas fa-file-invoice-dollar"></i> Nueva Factura</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true" class="text-white">&times;</span>

                </button>

            </div>


            <form method="POST" action="{{ action('FacturaController@store') }}" class="was-validated" id="facturaForm">

                @csrf

                <div class="modal-body">

                    <div class="form-row">

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="factura">No. Factura</label>

                            <input type="text" class="form-control" id="facturaCreate" name="factura_id" placeholder="000124578" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Número de la Factura

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="Iddoc">IDDOC</label>

                            <input type="text" class="form-control" id="iddocCreate" name="iddoc" placeholder="456123" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el IDDOC del Sistema de Correspondencia

                            </div>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="tipoDocumento">Tipo Documento</label>

                            <select name="tipoDocumento" id="tipoDocumentoCreate" class="form-control selectpicker" title="Tipo Documento ?" required>

                                <option>Factura</option>
                                <option>Boleta</option>
                                <option>Recibo</option>

                            </select>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Tipo de Documento

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">
                                                
                            <label for="proveedor_id">Proveedor</label>

                            <select name="proveedor_id" id="proveedor_id" class="form-control selectpicker" data-live-search="true" title="Seleccione el Proveedor de su Órden de Compra" required>

                                @foreach($proveedores as $proveedor)

                                    <option value="{{ $proveedor->id }}">{{ $proveedor->RazonSocial }}</option>
                                                                
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="NoOC">No. OC</label>

                            <input type="text" class="form-control" id="ordenCompra_idCreate" name="ordenCompra_id" placeholder="0123" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Número de la Órden de Compra

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="total">Total $</label>

                            <input type="text" class="form-control" id="totalCreate" name="totalFactura" placeholder="$ 123456789" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Total de la Factura

                            </div>

                        </div>

                    </div>

                    <div class="form-row">

                        <button class="btn btn-success btn-block boton" type="submit">

                            <i class="fas fa-save"></i>

                            Guardar Factura

                        </button>

                        <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal" aria-label="Close">

                            <i class="fas fa-arrow-left"></i>

                            Cancelar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
<!-- End Modal Create Proveedor -->

<!-- Update Modal Proveedor -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h3 class="modal-title" id="exampleModalLabel"> Actualizar Proveedor <i class="fas fa-edit"></i></h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true" class="text-white">&times;</span>

                </button>

            </div>


            <form method="POST" action="{{ url('/siscom/proveedores') }}" class="was-validated" id="updateForm">

                @csrf
                @method('PUT')

                <input type="hidden" name="flag" value="Actualizar">

                <div class="modal-body">

                    <div class="form-row">

                        <div class="col-md-12 mb-3">
                                                                              
                            <label for="Rut">Rut</label>

                            <input type="text" class="form-control" id="rutUpdate" name="rut" placeholder="Ingrese el Rut del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Rut del Proveedor

                            </div>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="col-md-12 mb-3">
                                                                              
                            <label for="RazonSocial">Razón Social</label>

                            <input type="text" class="form-control" id="razonSocialUpdate" name="razonSocial" placeholder="Ingrese el Razón Social del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Razón Social del Proveedor

                            </div>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="Alias">Alias</label>

                            <input type="text" class="form-control" id="aliasUpdate" name="alias" placeholder="Ingrese el Alias del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Alias del Proveedor

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="Giro">Giro</label>

                            <input type="text" class="form-control" id="giroUpdate" name="giro" placeholder="Ingrese el Giro del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Giro del Proveedor

                            </div>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="Dirección">Dirección</label>

                            <input type="text" class="form-control" id="direccionUpdate" name="direccion" placeholder="Ingrese el Dirección del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Dirección del Proveedor

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="Ciudad">Ciudad</label>

                            <input type="text" class="form-control" id="ciudadUpdate" name="ciudad" placeholder="Ingrese el Ciudad del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Ciudad del Proveedor

                            </div>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="Teléfono">Teléfono</label>

                            <input type="text" class="form-control" id="telefonoUpdate" name="telefono" placeholder="Ingrese el Teléfono del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Teléfono del Proveedor

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">
                                                                              
                            <label for="Correo">Correo</label>

                            <input type="email" class="form-control" id="correoUpdate" name="correo" placeholder="Ingrese el Correo del Proveedor" required>

                            <div class="invalid-feedback">
                                                                                            
                                Por favor ingrese el Correo del Proveedor

                            </div>

                        </div>

                    </div>

                    <div class="mb-3 form-row">

                        <button class="btn btn-success btn-block" type="submit">

                            <i class="fas fa-save"></i>

                            Guardar Proveedor

                        </button>

                        <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal" aria-label="Close">

                            <i class="fas fa-arrow-left"></i>

                            Cancelar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
<!-- END Update Modal Proveedor -->

<!-- DELETE Modal Proveedor -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h3 class="modal-title" id="exampleModalLabel"> Eliminar Proveedor <i class="fas fa-times-circle"></i></h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true" class="text-white">&times;</span>

                </button>

            </div>


            <form method="POST" action="{{ url('/siscom/proveedores') }}" class="was-validated" id="deleteForm">

                @csrf
                @method('PUT')

                <input type="hidden" name="flag" value="Eliminar">

                <div class="modal-body">

                    <div class="form-row">                        

                            <label class="col-sm-2 col-form-label text-muted">Rut</label><br>
                                                                        
                            <label class="col-sm-10 col-form-label" id="rutDelete">Rut Proveedor</label>
                                                                     
                    </div>

                    <div class="form-row">

                        <label class="col-sm-2 col-form-label text-muted">Razón Social</label><br>
                                                                        
                        <label class="col-sm-10 col-form-label" id="razonSocialDelete">Razón Social</label>

                    </div>

                    <div class="mb-3 form-row">

                        <button class="btn btn-danger btn-block" type="submit">

                            <i class="fas fa-times-circle"></i> Eliminar Proveedor

                        </button>

                        <a href="{{ url('/siscom/solicitud') }}" class="btn btn-secondary btn-block" type="reset">

                            <i class="fas fa-arrow-left"></i> Atrás

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
<!-- End Delete Modal Proveedor -->

@endsection

@push('scripts')

    <!-- JQuery DataTable -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js" ></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" defer></script>

<!-- JQuery DatePicker -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Boostrap Select -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-*.min.js"></script>

<script type="text/javascript">
        
        $(document).ready(function () {

            // Start Configuration DataTable
            var table = $('#facturasTable').DataTable({

                "paginate"  : true,

                "order"     : ([0, 'desc']),

                "language"  : {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "No existen Facturas recepcionadas por su unidad, aún...",
                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     ">>",
                                "sPrevious": "<<"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            },
                            "buttons": {
                                "copy": "Copiar",
                                "colvis": "Visibilidad"
                            }
                        }

            });
            //End Configuration DataTable

            //Start Edit Record
            table.on('click', '.edit', function () {

                $tr = $(this).closest('tr');

                if ($($tr).hasClass('child')) {

                    $tr = $tr.prev('.parent');

                }

                var data = table.row($tr).data();

                console.log(data);

                $('#rutUpdate').val(data[1]);
                $('#razonSocialUpdate').val(data[2]);
                $('#aliasUpdate').val(data[3]);
                $('#giroUpdate').val(data[4]);
                $('#direccionUpdate').val(data[5]);
                $('#ciudadUpdate').val(data[6]);
                $('#telefonoUpdate').val(data[7]);
                $('#correoUpdate').val(data[8]);

                $('#updateForm').attr('action', '/siscom/proveedores/' + data[0]);
                $('#updateModal').modal('show');

            });
            //End Edit Record

            //Start Delete Record
            table.on('click', '.delete', function () {

                $tr = $(this).closest('tr');

                if ($($tr).hasClass('child')) {

                    $tr = $tr.prev('.parent');

                }

                var data = table.row($tr).data();

                console.log(data);

                document.getElementById('rutDelete').innerHTML = data[1];
                document.getElementById('razonSocialDelete').innerHTML = data[2];
                
                $('#deleteForm').attr('action', '/siscom/proveedores/' + data[0]);
                $('#deleteModal').modal('show');

            });
            //End Delete Record

    });    

</script>

@endpush


