@extends('plantillaBase.masterblade')
@section('title','Menu Tipo Usuario')
@section('contenido')
@if (empty($TipoUsuarioFind))
    <h2 class="titulo my-2">Asignación de Menús</h2>
@else
    <h2 class="titulo my-2">Asignación de Menús Para {{$TipoUsuarioFind->NomTipoUsuario}}</h2>
@endif
    <div class="container-fluid my-3">
        <div>
            @include('Alertas.Alertas')
        </div>
        <form id="formChange" action="/DatMenuTipoUsuario">
            <div class="row">
                <div class="col-2">
                    <select class="form-select ms-4" name="IdTipoUsuario" id="IdTipoUsuario" onchange="MenuTipoUsuario()">
                        <option value="0">Seleccione</option>
                                @foreach ($tipoUsuarios as $tipoUsuario)    
                                <option {!! $filtroIdTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' !!} value="{{$tipoUsuario->IdTipoUsuario}}">{{$tipoUsuario->NomTipoUsuario}}</option>
                                @endforeach
                    </select>
                </div>
            </div>
        
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-11">
                        <!--<button class="btn btn-sm btn-outline-danger alinearDerecha" onclick="remove()">Remover Menú</button>-->
                        <buton class="btn btn-sm btnRemove alinearDerecha" onclick="remove()"><i class="fa fa-close"></i> Remover</buton>
                    </div>
                </div>
                <div class="container cuchi">
                    <div>
                        <h5 style="text-align: center">Menús Agregados</h5>
                    </div>
                    <div class="table table-responsive table-sm">
                        <table class="table table-responsive table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Menús ({{count($menuTipoUsuarios)}})</th>
                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($menuTipoUsuarios) == 0)
                                    @if($filtroIdTipoUsuario == 0)
                                    <tr>
                                        <td colspan="2">Seleccione Tipo de Usuario</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="2">No Hay Menús para este Tipo de Usuario</td>
                                    </tr>
                                    @endif
                                @else
                                @foreach($menuTipoUsuarios as $menuTipoUsuario)
                                <tr>
                                    <td>{{$menuTipoUsuario->cmpNomMenu}}</td>
                                    <td><input class="form-check-input" type="checkbox" name="chkRemoverMenu[]" id="chkRemoverMenu[]" value="{{$menuTipoUsuario->cmpIdMenu}}"></td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-4">
                        
                    </div>
                    <div class="col-11">
                        <!--<button class="btn btn-sm btn-outline-success alinearDerecha me-4" onclick="agregar()">Agregar Menú</button>-->
                        <button class="btn btn-sm btnAgregar alinearDerecha" onclick="agregar()"><i class="fa fa-plus"></i> Agregar</button>
                    </div>
                </div>

                <div class="container cuchi">
                    <div>
                        <h5 style="text-align: center">Agregar Menús</h5>
                    </div>
                    <div class="table-sm table-responsive">
                        <table class="table table-sm table-responsive table-striped">
                            <thead>
                                <tr>
                                    <th>Menús ({{count($menus)}})</th>
                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($menus == "" || $filtroIdTipoUsuario == 0)
                                <tr>
                                    <td colspan="2">Seleccione Tipo de Usuario</td>
                                </tr>
                                @else
                                @if(count($menus) == 0)
                                    <tr>
                                        <td colspan="2">No Hay Menús por Agregar</td>
                                    </tr>
                                @endif
                                @foreach ($menus as $menu)
                                <tr>
                                    <td>{{$menu->NomMenu}}</td>
                                    <td><input class="form-check-input" type="checkbox" name="chkAgregarMenu[]" id="chkAgregarMenu[]" value="{{$menu->IdMenu}}"></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection