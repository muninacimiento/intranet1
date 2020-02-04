<!--
/*
 *  JFuentealba @itux
 *  created at December 23, 2019 - 3:45 pm
 *  updated at December 23, 2019 - 3:47 pm
 */
-->

@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col">

            <div class="card border-danger shadow">

                <div class="card-header text-center text-white bg-danger">

                    @include('farmacia.menu')

                </div>


                <div class="card-body">

                    <div class="row py-3">

                        <div class="col-md-6 text-center">
                            
                            <h3>Gestión de Categorias de Medicamentos</h3>

                            <div class="text-secondary">

                                {{ $dateCarbon }}

                            </div>

                        </div>

                        <!-- Button trigger Crear Usuario -->
                        <div class="col-md-6">
                            
                            <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#createCategoriaModal">

                                <button class="btn btn-warning btn-block boton">

                                    <i class="fas fa-cogs px-2"></i>

                                    Nueva Categoria

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

                        <table class="display" id="categoriasTable" style="font-size: 0.8em;">

                            <thead>

                                <tr class="table-active">

                                    <th>ID</th>

                                    <th>Categoria</th>

                                    <th>Acciones</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($categorias as $categoria)

                                <tr>

                                    <td>{{ $categoria->id }}</td>

                                    <td>{{ $categoria->name }}</td>

                                    <td>

                                        <div class="btn-group" role="group">

                                            <a href="#" class="btn btn-warning btn-sm mr-1 actualizar" data-toggle="tooltip" data-placement="bottom" title="Actualizar Categoria">
                                                    
                                                <i class="fas fa-pencil-alt"></i>

                                            </a>

                                            {!! Form::open(['route'=> ['categoria.destroy', $categoria->id], 'method' => 'DELETE']) !!}

                                                <button class="btn btn-danger btn-sm mr-1">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            {!! Form::close() !!}

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                            <tfoot>

                                <tr class="table-active">

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Creación Nuevo Medicamento -->
<div class="modal fade" id="createCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        
        <div class="modal-content">
            
            <div class="modal-header bg-warning">
                
                <p class="modal-title" id="tituloLabel" style="font-size: 1.2em"><i class="fas fa-cogs px-2"></i> Nueva Categoria</p>
                    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        
                    <span aria-hidden="true">&times;</span>
                
                </button>

            </div>
        
            <form method="POST" action="{{ action('CategoriaMedicamentoController@store') }}" class="was-validated" id="categoriaForm">

                @csrf
            
            <div class="modal-body">
                
                <div class="form-row">

                    <div class="col-md-12 mb-3">
                                                                              
                        <label for="name">Categoría de Medicamento</label>

                        <input type="text" class="form-control" id="name" name="name" placeholder="Probiótico" required>

                        <div class="invalid-feedback">

                            Por favor Ingrese la Categoría del Medicamento

                        </div>

                    </div>

                </div>

                <div class="form-row">

                    <button type="submit" class="btn btn-success btn-block">

                        <i class="fas fa-check-circle"></i>

                        Guardar Categoría

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
<!-- Crear Nueva Categoria -->

<!-- Actualizaa Categoria -->
<div class="modal fade" id="updateCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        
        <div class="modal-content">
            
            <div class="modal-header bg-warning">
                
                <p class="modal-title" id="tituloLabel" style="font-size: 1.2em"><i class="fas fa-user-edit px-2"></i> Actualizar Categoria</p>
                    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        
                    <span aria-hidden="true">&times;</span>
                
                </button>

            </div>
        
            <form method="POST" action="#" class="was-validated" id="updateCategoriaForm">

                @csrf

                @method('PUT')
            
            <div class="modal-body">
                
                <div class="form-row">

                    <div class="col-md-12 mb-3">
                                                                              
                        <label for="name">Categoría de Medicamento</label>

                        <input type="text" class="form-control" id="nameUpdate" name="name" placeholder="Probiótico" required>

                        <div class="invalid-feedback">

                            Por favor Ingrese la Categoría del Medicamento

                        </div>

                    </div>

                </div>

                <div class="form-row">

                    <button type="submit" class="btn btn-success btn-block">

                        <i class="fas fa-check-circle"></i>

                        Guardar Categoría

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
<!-- Actualizar Usuario -->

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

<script>

$(document).ready(function () {

    // Start Configuration DataTable
    var table = $('#categoriasTable').DataTable({

        "paginate"  : true,

        "order"     : ([0, 'desc']),

        "language"  : {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "No existen Categorías registradas aún...",
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
    table.on('click', '.actualizar', function () {

        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')) {

            $tr = $tr.prev('.parent');

        }

        var data = table.row($tr).data();

        console.log(data);

        $('#nameUpdate').val(data[1]);

        $('#updateCategoriaForm').attr('action', '/farmacia/categoria/' + data[0]);
        $('#updateCategoriaModal').modal('show');

    });
    //End Edit Record
});

</script>

@endpush