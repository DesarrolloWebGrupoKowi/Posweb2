<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\TipoUsuariosController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\CiudadesController;
use App\Http\Controllers\PlazasController;
use App\Http\Controllers\FamiliaArticulosController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\AutoservicioFacturacionController;
use App\Http\Controllers\ListasPrecioController;
use App\Http\Controllers\MenuPoswebController;
use App\Http\Controllers\TipoMenuController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\ClientesCloudController;
use App\Http\Controllers\CajasController;
use App\Http\Controllers\LimiteCreditoController;
use App\Http\Controllers\BancosController;
use App\Http\Controllers\ClientesAutoservicioController;
use App\Http\Controllers\MovimientosProductoController;
use App\Http\Controllers\TablasUpdateController;
use App\Http\Controllers\TipoArticulosController;
use App\Http\Controllers\CuentasMermaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\TiposMermaController;
use App\Http\Controllers\LimiteCreditoEspecialController;
use App\Http\Controllers\TicketFacturacionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Index Home
Route::get('/', function () {
    return redirect('Dashboard');
})->name('index');

Route::middleware('auth')->group(function () {

    //+==========================================================================================================+
    // CATÁLOGOS (MODERNIZADOS)
    //+==========================================================================================================+

    // Tiendas
    Route::get('CatTiendas', [TiendasController::class, 'CatTiendas'])->name('tiendas.index');
    Route::post('CrearTienda', [TiendasController::class, 'CrearTienda'])->name('tiendas.store');
    Route::post('EditarTienda/{id}', [TiendasController::class, 'EditarTienda'])->name('tiendas.update');
    Route::post('EliminarTienda/{id}', [TiendasController::class, 'EliminarTienda'])->name('tiendas.destroy');

    // Usuarios
    Route::get('CatUsuarios', [UsuariosController::class, 'CatUsuarios'])->name('usuarios.index');
    Route::post('CrearUsuario', [UsuariosController::class, 'CrearUsuario'])->name('usuarios.store');
    Route::post('Editar/{id}', [UsuariosController::class, 'EditarUsuario'])->name('usuarios.update');
    Route::post('Eliminar/{id}', [UsuariosController::class, 'Eliminar'])->name('usuarios.destroy');
    Route::post('ActivarUsuario/{id}', [UsuariosController::class, 'ActivarUsuario'])->name('usuarios.activate');
    Route::post('CambiarContraseña/{id}', [UsuariosController::class, 'CambiarContraseña'])->name('usuarios.password');

    Route::get('/api/buscar-empleado/{nomina}', [UsuariosController::class, 'BuscarEmpleado']);
    Route::get('/api/verificar-usuario', [UsuariosController::class, 'VerificarUsuario']);

    // Tipo de Usuarios
    Route::get('CatTipoUsuarios', [TipoUsuariosController::class, 'CatTipoUsuarios'])->name('tipos-usuario.index');
    Route::post('CrearTipoUsuario', [TipoUsuariosController::class, 'CrearTipoUsuario'])->name('tipos-usuario.store');
    Route::post('EditarTipoUsuario/{id}', [TipoUsuariosController::class, 'EditarTipoUsuario'])->name('tipos-usuario.update');
    Route::post('EliminarTipoUsuario/{id}', [TipoUsuariosController::class, 'EliminarTipoUsuario'])->name('tipos-usuario.destroy');

    // Estados
    Route::get('CatEstados', [EstadosController::class, 'CatEstados'])->name('estados.index');
    Route::post('CrearEstado', [EstadosController::class, 'CrearEstado'])->name('estados.store');
    Route::post('EditarEstado/{id}', [EstadosController::class, 'EditarEstado'])->name('estados.update');

    // Ciudades
    Route::get('CatCiudades', [CiudadesController::class, 'CatCiudades'])->name('ciudades.index');
    Route::post('CrearCiudad', [CiudadesController::class, 'CrearCiudad'])->name('ciudades.store');
    Route::post('EditarCiudad/{id}', [CiudadesController::class, 'EditarCiudad'])->name('ciudades.update');

    // Plazas
    Route::get('CatPlazas', [PlazasController::class, 'CatPlazas'])->name('plazas.index');
    Route::post('CrearPlaza', [PlazasController::class, 'CrearPlaza'])->name('plazas.store');
    Route::post('EditarPlaza/{id}', [PlazasController::class, 'EditarPlaza'])->name('plazas.update');

    // Familias
    Route::get('CatFamilias', [FamiliaArticulosController::class, 'CatFamilias'])->name('familias.index');
    Route::post('CrearFamilia', [FamiliaArticulosController::class, 'CrearFamilia'])->name('familias.store');

    // Grupos
    Route::get('CatGrupos', [GruposController::class, 'CatGrupos'])->name('grupos.index');
    Route::post('CrearGrupo', [GruposController::class, 'CrearGrupo'])->name('grupos.store');

    // Artículos
    Route::get('CatArticulos', [ArticulosController::class, 'CatArticulos'])->name('articulos.index');
    Route::post('CrearArticulo', [ArticulosController::class, 'CrearArticulo'])->name('articulos.store');
    Route::post('EditarArticulo/{id}', [ArticulosController::class, 'EditarArticulo'])->name('articulos.update');
    Route::get('ExportExcelCatArticulos', [ArticulosController::class, 'ExportExcel'])->name('articulos.export');
    Route::get('BuscarArticulo', [ArticulosController::class, 'BuscarArticulo'])->name('articulos.buscar');
    Route::post('LigarArticulo', [ArticulosController::class, 'LigarArticulo'])->name('articulos.ligar');

    // Listas de Precio
    Route::get('CatListasPrecio', [ListasPrecioController::class, 'CatListasPrecio'])->name('listas-precio.index');
    Route::post('CrearListaPrecio', [ListasPrecioController::class, 'CrearListaPrecio'])->name('listas-precio.store');
    Route::post('EditarListaPrecio/{id}', [ListasPrecioController::class, 'EditarListaPrecio'])->name('listas-precio.update');

    // Menú Posweb
    Route::get('CatMenuPosweb', [MenuPoswebController::class, 'CatMenuPosweb'])->name('menus.index');
    Route::post('CrearMenuPosweb', [MenuPoswebController::class, 'CrearMenuPosweb'])->name('menus.store');
    Route::post('EditarMenu/{id}', [MenuPoswebController::class, 'EditarMenu'])->name('menus.update');

    // Tipo de Menú
    Route::get('CatTipoMenu', [TipoMenuController::class, 'CatTipoMenu'])->name('tipos-menu.index');
    Route::post('CrearTipoMenu', [TipoMenuController::class, 'CrearTipoMenu'])->name('tipos-menu.store');
    Route::post('EditarTipoMenu/{id}', [TipoMenuController::class, 'EditarTipoMenu'])->name('tipos-menu.update');

    // Tipo de Pago
    Route::get('CatTipoPago', [TipoPagoController::class, 'CatTipoPago'])->name('tipos-pago.index');
    Route::get('AgregarTipoPago', [TipoPagoController::class, 'AgregarTipoPago'])->name('tipos-pago.store');

    // Clientes Cloud
    Route::get('CatClientesCloud', [ClientesCloudController::class, 'CatClientesCloud'])->name('clientes-cloud.index');
    Route::get('BuscarCustomer', [ClientesCloudController::class, 'BuscarCustomer'])->name('clientes-cloud.buscar');
    Route::get('GuardarCustomerCloud', [ClientesCloudController::class, 'GuardarCustomerCloud'])->name('clientes-cloud.store');

    // Cajas
    Route::get('CatCajas', [CajasController::class, 'CatCajas'])->name('cajas.index');
    Route::get('CrearCaja', [CajasController::class, 'CrearCaja'])->name('cajas.store');

    // Límite Crédito
    Route::get('CatLimiteCredito', [LimiteCreditoController::class, 'CatLimiteCredito'])->name('limites-credito.index');
    Route::post('EditarLimiteCredito/{tipoNomina}', [LimiteCreditoController::class, 'EditarLimiteCredito'])->name('limites-credito.update');

    // Bancos
    Route::get('CatBancos', [BancosController::class, 'CatBancos'])->name('bancos.index');
    Route::post('AgregarBanco', [BancosController::class, 'AgregarBanco'])->name('bancos.store');

    // Movimientos de Producto
    Route::get('CatMovimientosProducto', [MovimientosProductoController::class, 'CatMovimientosProducto'])->name('movimientos.index');
    Route::post('AgregarMovimiento', [MovimientosProductoController::class, 'AgregarMovimiento'])->name('movimientos.store');

    // Tablas
    Route::get('CatTablas', [TablasUpdateController::class, 'CatTablas'])->name('tablas.index');
    Route::post('AgregarTablas', [TablasUpdateController::class, 'AgregarTablas'])->name('tablas.store');

    // Tipo de Artículos
    Route::get('TipoArticulos', [TipoArticulosController::class, 'TipoArticulos'])->name('tipos-articulo.index');
    Route::post('AgregarTipoArticulo', [TipoArticulosController::class, 'AgregarTipoArticulo'])->name('tipos-articulo.store');
    Route::post('EliminarTipoArticulo/{idCatTipoArticulo}', [TipoArticulosController::class, 'EliminarTipoArticulo'])->name('tipos-articulo.destroy');

    // Cuentas Merma
    Route::get('CuentasMerma', [CuentasMermaController::class, 'CuentasMerma'])->name('cuentas-merma.index');
    Route::post('AgregarCuentaMerma', [CuentasMermaController::class, 'AgregarCuentaMerma'])->name('cuentas-merma.store');

    // Tipos de Merma
    Route::get('TiposMerma', [TiposMermaController::class, 'TiposMerma'])->name('tipos-merma.index');
    Route::post('CrearTipoMerma', [TiposMermaController::class, 'CrearTipoMerma'])->name('tipos-merma.store');
    Route::post('EliminarTipoMerma/{idTipoMerma}', [TiposMermaController::class, 'EliminarTipoMerma'])->name('tipos-merma.destroy');

    // Sub Tipos de Merma
    Route::get('SubTiposMerma', [TiposMermaController::class, 'SubTiposMerma'])->name('subtipos-merma.index');
    Route::post('CrearSubTipoMerma/{idTipoMerma}', [TiposMermaController::class, 'CrearSubTipoMerma'])->name('subtipos-merma.store');
    Route::post('EliminarSubTipoMerma/{idSubTipoMerma}', [TiposMermaController::class, 'EliminarSubTipoMerma'])->name('subtipos-merma.destroy');

    // Límite Crédito Especial
    Route::get('CatLimiteCreditoEspecial', [LimiteCreditoEspecialController::class, 'index'])->name('limites-credito-especial.index');
    Route::post('CatLimiteCreditoEspecial', [LimiteCreditoEspecialController::class, 'create'])->name('limites-credito-especial.store');
    Route::put('CatLimiteCreditoEspecial/{Id}', [LimiteCreditoEspecialController::class, 'update'])->name('limites-credito-especial.update');
    Route::delete('CatLimiteCreditoEspecial/{Id}', [LimiteCreditoEspecialController::class, 'delete'])->name('limites-credito-especial.destroy');

    //+==========================================================================================================+
    // RESTO DE RUTAS (SE MANTIENEN IGUAL)
    //+==========================================================================================================+

    // Confirmar Contraseña
    Route::get('/ConfirmarContrasena', 'App\Http\Controllers\ConfirmarContrasenaController@ConfirmarContrasena');
    Route::post('/ConfirmContrasena/{id}', 'App\Http\Controllers\ConfirmarContrasenaController@ConfirmContrasena');

    // Mi Perfil
    Route::get('MiPerfil', 'App\Http\Controllers\UsuariosController@MiPerfil')->name('miperfil');
    Route::post('EditarPerfil/{id}', 'App\Http\Controllers\UsuariosController@EditarPerfil');
    Route::post('CambiarPassword/{id}', 'App\Http\Controllers\UsuariosController@CambiarPassword');

    // Select Dinámico Estado-Ciudad
    Route::get('/Ciudades/{id}', 'App\Http\Controllers\CiudadesController@Ciudades');

    // Procesar Cortes - Tiendas
    Route::get('CatTiendasProcesar', 'App\Http\Controllers\TiendasController@CatTiendasProcesar');
    Route::post('CatTiendas/procesarcorte/{id}', 'App\Http\Controllers\TiendasController@actualizarProcesarCorte');
    Route::get('CatTiendas/historial/{id}', 'App\Http\Controllers\TiendasController@historialCatTiendas');

    // Procesar Cortes - Rutas
    Route::get('CatRutasProcesar', 'App\Http\Controllers\TiendasController@CatRutasProcesar');
    Route::post('CatRutas/procesarcorte/{id}', 'App\Http\Controllers\TiendasController@actualizarProcesarCorteRutas');
    Route::get('CatRutas/historial/{id}', 'App\Http\Controllers\TiendasController@historialRutas');

    // Procesar Cortes - Ecommerce
    Route::get('CatCentrosVentaProcesar', 'App\Http\Controllers\TiendasController@CatCentrosVentaProcesar');
    Route::post('CatCentrosVenta/procesarcorte/{id}', 'App\Http\Controllers\TiendasController@actualizarProcesarCorteCentrosVenta');
    Route::get('CatCentrosVenta/historial/{id}', 'App\Http\Controllers\TiendasController@historialCentrosVenta');

    // Usuarios Tienda
    Route::get('/CatUsuariosTienda', 'App\Http\Controllers\UsuariosTiendaController@CatUsuariosTienda');
    Route::post('/CrearUsuarioTienda', 'App\Http\Controllers\UsuariosTiendaController@CrearUsuarioTienda');
    Route::post('/EditarUsuarioTienda/{id}', 'App\Http\Controllers\UsuariosTiendaController@EditarUsuarioTienda');
    Route::post('EliminarUsuarioTienda/{id}', 'App\Http\Controllers\UsuariosTiendaController@EliminarUsuarioTienda');

    // Menú Posweb (adicionales)
    Route::get('/OrdenarMenus', 'App\Http\Controllers\MenuPoswebController@OrdenarMenus');
    Route::post('/EditarPosicionMenu', 'App\Http\Controllers\MenuPoswebController@EditarPosicionMenu');

    // Menu Tipo Usuario
    Route::get('/DatMenuTipoUsuario', 'App\Http\Controllers\MenuTipoUsuarioController@DatMenuTipoUsuario');
    Route::post('/CrearMenuTipoUsuario', 'App\Http\Controllers\MenuTipoUsuarioController@CrearMenuTipoUsuario');
    Route::post('/RemoverMenu', 'App\Http\Controllers\MenuTipoUsuarioController@RemoverMenu')->name('RemoverMenu');
    Route::post('/AgregarMenu', 'App\Http\Controllers\MenuTipoUsuarioController@AgregarMenu');

    // Artículos (adicionales)
    Route::get('EnviarArticulo', 'App\Http\Controllers\ArticulosController@EnviarArticulo')->name('EnviarArticulo');
    Route::get('mostrarArticulo', 'App\Http\Controllers\ArticulosController@mostrarArticulo');
    Route::post('AgregarArticulo/{id}', 'App\Http\Controllers\ArticulosController@AgregarArticulo');
    Route::get('/ListaCodEtiquetas', 'App\Http\Controllers\ListaCodEtiquetaController@ListaCodEtiquetas');
    Route::get('/GenerarPDF', 'App\Http\Controllers\ListaCodEtiquetaController@GenerarPDF');

    // Interfaz Créditos
    Route::get('/InterfazCreditos', 'App\Http\Controllers\InterfazCreditosController@InterfazCreditos');
    Route::get('/InterfazCreditosExcel', 'App\Http\Controllers\InterfazCreditosController@InterfazCreditosExcel');
    Route::post('/InterfazarCreditos/{fecha1}/{fecha2}/{idTipoNomina}/{numNomina}', 'App\Http\Controllers\InterfazCreditosController@InterfazarCreditos');
    Route::get('/PrepagoCreditos/{fecha1}/{fecha2}/{numNomina}/{idTipoNomina}', 'App\Http\Controllers\InterfazCreditosController@PrepagoCreditos');
    Route::get('/CreditosPagosAbonos', 'App\Http\Controllers\InterfazCreditosController@CreditosPagosAbonos');
    Route::post('/AjusteDeuda/{idEncabezado}/{importeDeuda}', 'App\Http\Controllers\InterfazCreditosController@AjusteDeuda');
    Route::post('/EliminarAjuste/{idEncabezado}', 'App\Http\Controllers\InterfazCreditosController@EliminarAjuste');

    // Lista Precio Tienda
    Route::get('/CatListaPrecioTienda', 'App\Http\Controllers\ListasPrecioTiendaController@CatListaPrecioTienda');
    Route::post('/CrearListaPrecioTienda', 'App\Http\Controllers\ListasPrecioTiendaController@CrearListaPrecioTienda');
    Route::post('/RemoverLista', 'App\Http\Controllers\ListasPrecioTiendaController@RemoverLista');
    Route::post('/AgregarLista', 'App\Http\Controllers\ListasPrecioTiendaController@AgregarLista');

    // Precios
    Route::get('/Precios', 'App\Http\Controllers\PreciosController@Precios')->name('Precios');
    Route::post('/ActualizarPrecios', 'App\Http\Controllers\PreciosController@ActualizarPrecios');
    Route::get('/DetallePrecios', 'App\Http\Controllers\PreciosController@DetallePrecios');
    Route::get('/ExportExcelDetallePrecios', 'App\Http\Controllers\PreciosController@ExportExcel');
    Route::get('/DetallePromociones', 'App\Http\Controllers\PreciosController@DetallePromociones');
    Route::post('/DetallePromociones/update', 'App\Http\Controllers\PreciosController@DetallePromocionesUpdate');

    // Pedidos
    Route::get('/Pedidos', 'App\Http\Controllers\PedidosController@Pedidos');
    Route::get('/DatPedidos', 'App\Http\Controllers\PedidosController@DatPedidos');
    Route::get('/MostrarPedidos', 'App\Http\Controllers\PedidosController@MostrarPedidos');
    Route::post('/EliminarArticuloPedido/{id}', 'App\Http\Controllers\PedidosController@EliminarArticuloPedido');
    Route::get('/GuardarPedido', 'App\Http\Controllers\PedidosController@GuardarPedido');
    Route::get('/PedidosGuardados', 'App\Http\Controllers\PedidosController@PedidosGuardados');
    Route::post('/CancelarPedido/{idPedido}', 'App\Http\Controllers\PedidosController@CancelarPedido');
    Route::post('/EnviarAPreventa/{idPedido}', 'App\Http\Controllers\PedidosController@EnviarAPreventa');
    Route::get('/HistorialGuardados', 'App\Http\Controllers\PedidosController@HistorialGuardados');

    // Dashboard
    Route::get('/Dashboard', 'App\Http\Controllers\DashboardController@Dashboard')->name('dashboard');

    // Cajas (adicionales)
    Route::get('/CajasTienda', 'App\Http\Controllers\CajasController@CajasTienda');
    Route::post('/AgregarCajaTienda', 'App\Http\Controllers\CajasController@AgregarCajaTienda');

    // Venta por Tipo Pago
    Route::get('/VentaPorTipoPago', 'App\Http\Controllers\VentaPorTipoPagoController@VentaPorTipoPago');

    // Clientes
    Route::get('/CatClientes', 'App\Http\Controllers\ClientesController@CatClientes');
    Route::post('/CatClientes/Actualizar', 'App\Http\Controllers\ClientesController@CatClientesActualizar');

    // Solicitudes Factura (Admin)
    Route::get('/SolicitudesFactura', 'App\Http\Controllers\SolicitudesFacturaController@VerSolicitudes');
    Route::get('/SolicitudesFactura/{id}', 'App\Http\Controllers\SolicitudesFacturaController@VerSolicitud');
    Route::get('/SolicitudesFactura/Relacionar/{id}/{billTo}', 'App\Http\Controllers\SolicitudesFacturaController@Relacionar');
    Route::get('/SolicitudesFactura/Finalizar/{id}', 'App\Http\Controllers\SolicitudesFacturaController@Finalizar');
    Route::post('/SolicitudesFactura/Cancelar/{id}', 'App\Http\Controllers\SolicitudesFacturaController@Cancelar');

    // Solicitud Factura (Cajero)
    Route::get('/SolicitudFactura', 'App\Http\Controllers\SolicitudFacturaController@SolicitudFactura');
    Route::get('/VerSolicitudesFactura', 'App\Http\Controllers\SolicitudFacturaController@VerSolicitudesFactura');
    Route::post('/GuardarSolicitudFacturaClienteNuevo', 'App\Http\Controllers\SolicitudFacturaController@GuardarSolicitudFacturaClienteNuevo');
    Route::get('/VerificarSolicitudFactura/{idTicket}/{rfcCliente}/{bill_To}/{correo}', 'App\Http\Controllers\SolicitudFacturaController@VerificarSolicitudFactura');
    Route::post('/GuardarSolicitudFactura', 'App\Http\Controllers\SolicitudFacturaController@GuardarSolicitudFactura');
    Route::post('/SubirConstanciaSolicitud/{idSolicitudFactura}', 'App\Http\Controllers\SolicitudFacturaController@SubirConstanciaSolicitud');
    Route::post('/SolicitudesFactura/Subir', 'App\Http\Controllers\SolicitudFacturaController@SolicitudFacturaSubir');

    // Ligar Clientes
    Route::get('/ClientesNuevos', 'App\Http\Controllers\LigarClientesController@ClientesNuevos');
    Route::get('/LigarCliente', 'App\Http\Controllers\LigarClientesController@LigarCliente');
    Route::post('/ClientesNuevos/Cancelar/{id}', 'App\Http\Controllers\LigarClientesController@Cancelar');
    Route::get('/ClientesNuevos/Finalizar/{id}', 'App\Http\Controllers\LigarClientesController@Finalizar');
    Route::post('/GuardarLigueCliente/{idSolicitudFactura}/{bill_To}', 'App\Http\Controllers\LigarClientesController@GuardarLigueCliente');
    Route::get('/GuardarCheckClienteEditado', 'App\Http\Controllers\LigarClientesController@GuardarCheckClienteEditado');
    Route::get('/VerConstanciaCliente/{idSolicitudFactura}', 'App\Http\Controllers\LigarClientesController@VerConstanciaCliente');

    // Clientes Cloud Tienda
    Route::get('/ClientesCloudTienda', 'App\Http\Controllers\ClientesCloudTiendaController@ClientesCloudTienda');
    Route::get('/RelacionClienteCloudTienda', 'App\Http\Controllers\ClientesCloudTiendaController@RelacionClienteCloudTienda');
    Route::get('/GuardarRelacionClienteCloud', 'App\Http\Controllers\ClientesCloudTiendaController@GuardarRelacionClienteCloud');
    Route::post('/GuardarDatClienteCloud', 'App\Http\Controllers\ClientesCloudTiendaController@GuardarDatClienteCloud');
    Route::get('/VerClientesCloudTienda', 'App\Http\Controllers\ClientesCloudTiendaController@VerClientesCloudTienda');

    // Recepción
    Route::get('/RecepcionProducto', 'App\Http\Controllers\RecepcionController@RecepcionProducto');
    Route::post('/importExcel', 'App\Http\Controllers\RecepcionController@importExcel');
    Route::get('/ReadExcel', 'App\Http\Controllers\RecepcionController@vistaDemo');
    Route::post('/RecepcionarProducto/{idRecepcion}', 'App\Http\Controllers\RecepcionController@RecepcionarProducto');
    Route::post('/CancelarRecepcion/{idRecepcion}', 'App\Http\Controllers\RecepcionController@CancelarRecepcion');
    Route::get('/AgregarProductoManual', 'App\Http\Controllers\RecepcionController@AgregarProductoManual');
    Route::get('/CapturaManualTmp', 'App\Http\Controllers\RecepcionController@CapturaManualTmp');
    Route::post('/EliminarProductoManual/{IdCapRecepcionManual}', 'App\Http\Controllers\RecepcionController@EliminarProductoManual');
    Route::get('/ReporteRecepciones', 'App\Http\Controllers\RecepcionController@ReporteRecepciones');
    Route::get('/RecepcionLocalSinInternet', 'App\Http\Controllers\RecepcionController@RecepcionLocalSinInternet');
    Route::get('/AgregarProductoLocalSinInternet', 'App\Http\Controllers\RecepcionController@AgregarProductoLocalSinInternet')->name('AgregarProductoLocalSinInternet');
    Route::post('/EliminarArticuloSinInternet/{idCapRecepcionManual}', 'App\Http\Controllers\RecepcionController@EliminarArticuloSinInternet')->name('EliminarArticuloSinInternet');
    Route::post('/RecepcionarProductoSinInternet', 'App\Http\Controllers\RecepcionController@RecepcionarProductoSinInternet')->name('RecepcionarProductoSinInternet');

    // POS
    Route::get('/Pos', 'App\Http\Controllers\PoswebController@Pos')->name('Pos');
    Route::get('/tickets/pendientes', 'App\Http\Controllers\PoswebController@TicketsPendientes');
    Route::post('/EliminarPago/{idDatTipoPago}', 'App\Http\Controllers\PoswebController@EliminarPago');
    Route::get('/BuscarEmpleado', 'App\Http\Controllers\PoswebController@BuscarEmpleado')->name('BuscarEmpleado');
    Route::get('/QuitarEmpleado', 'App\Http\Controllers\PoswebController@QuitarEmpleado')->name('QuitarEmpleado');
    Route::get('/CobroEmpleado', 'App\Http\Controllers\PoswebController@CobroEmpleado')->name('CobroEmpleado');
    Route::post('/CobroFrecuenteSocio/{folioFrecuenteSocio}', 'App\Http\Controllers\PoswebController@CobroFrecuenteSocio')->name('CobroFrecuenteSocio');
    Route::get('/CalculosPreventa', 'App\Http\Controllers\PoswebController@CalculosPreventa');
    Route::post('/EliminarArticuloPreventa/{id}', 'App\Http\Controllers\PoswebController@EliminarArticuloPreventa');
    Route::get('/PaquetesPreventa', 'App\Http\Controllers\PoswebController@PaquetesPreventa');
    Route::get('/EliminarPreventa', 'App\Http\Controllers\PoswebController@EliminarPreventa');
    Route::get('/iframeConsultarArticulo', 'App\Http\Controllers\PoswebController@iframeConsultarArticulo');
    Route::get('/GuardarVenta', 'App\Http\Controllers\PoswebController@GuardarVenta')->name('GuardarVenta');
    Route::get('/CorteDiario', 'App\Http\Controllers\PoswebController@CorteDiario');
    Route::get('/GenerarCortePDF/{fecha}/{idTienda}/{idDatCaja}', 'App\Http\Controllers\PoswebController@GenerarCortePDF');
    Route::get('/CalculoMultiPago/{idEncabezado}/{restante}/{pago}/{idTipoPago}/{idBanco}/{numTarjeta}', 'App\Http\Controllers\PoswebController@CalculoMultiPago')->name('CalculoMultiPago');
    Route::get('/ImprimirTicketVenta/{idEncabezado}/{restante}/{pago}', 'App\Http\Controllers\PoswebController@ImprimirTicketVenta')->name('ImprimirTicketVenta');
    Route::get('ReimprimirTicket', 'App\Http\Controllers\PoswebController@ReimprimirTicket')->name('ReimprimirTicket');
    Route::get('/ImprimirTicket', 'App\Http\Controllers\PoswebController@ImprimirTicket');
    Route::get('/MandarPulso', 'App\Http\Controllers\PoswebController@MandarPulso');
    Route::get('/VentaTicketDiario', 'App\Http\Controllers\PoswebController@VentaTicketDiario');
    Route::post('/VentaTicketDiario/Subir', 'App\Http\Controllers\PoswebController@VentaTicketDiarioSubir');
    Route::get('/ConcentradoVentas', 'App\Http\Controllers\PoswebController@ConcentradoVentas');
    Route::get('/VentaPorGrupo', 'App\Http\Controllers\PoswebController@VentaPorGrupo');
    Route::post('/PagoMonedero', 'App\Http\Controllers\PoswebController@PagoMonedero');
    Route::get('/CancelarDescuento', 'App\Http\Controllers\PoswebController@CancelarDescuento');
    Route::get('/ReporteVentasListaPrecio', 'App\Http\Controllers\PoswebController@ReporteVentasListaPrecio')->name('ReporteVentasListasPrecio');

    // Socio Frecuente
    Route::get('/LigarSocioFrecuente', 'App\Http\Controllers\SocioFrecuenteController@LigarSocioFrecuente')->name('LigarSocioFrecuente');
    Route::post('/GuardarSocioFrecuente/{folioViejo}', 'App\Http\Controllers\SocioFrecuenteController@GuardarSocioFrecuente')->name('GuardarSocioFrecuente');
    Route::post('/DescargarSociosFrecuentes/{folioViejo}', 'App\Http\Controllers\SocioFrecuenteController@DescargarSociosFrecuentes')->name('DescargarSociosFrecuentes');

    // Stock
    Route::get('/ReporteStock', 'App\Http\Controllers\StockTiendaController@ReporteStock');
    Route::get('/ReporteStockAdmin', 'App\Http\Controllers\StockTiendaController@ReporteStockAdmin');
    Route::get('/UpdateStockViewAdmin', 'App\Http\Controllers\StockTiendaController@UpdateStockViewAdmin');
    Route::get('/DescargarPlantillaStock/{idTienda}', 'App\Http\Controllers\StockTiendaController@descargarPlantillaStock');
    Route::post('/UpdateStockAdmin/{id}', 'App\Http\Controllers\StockTiendaController@UpdateStockAdmin');

    // Tipo Pago Tienda
    Route::get('/DatTipoPagoTienda', 'App\Http\Controllers\TipoPagoTiendaController@DatTipoPagoTienda');
    Route::post('/AgregarDatTipoPagoTienda', 'App\Http\Controllers\TipoPagoTiendaController@AgregarDatTipoPagoTienda');
    Route::post('/RemoverDatTipoPagoTienda', 'App\Http\Controllers\TipoPagoTiendaController@RemoverDatTipoPagoTienda');

    // Empleados
    Route::get('AdeudosEmpleado', 'App\Http\Controllers\EmpleadosController@AdeudosEmpleado');
    Route::get('CreditosPagados', 'App\Http\Controllers\EmpleadosController@CreditosPagados');
    Route::get('VentaEmpleados', 'App\Http\Controllers\EmpleadosController@VentaEmpleados');
    Route::get('VentaEmpleadosExcel', 'App\Http\Controllers\EmpleadosController@VentaEmpleadosExcel');
    Route::get('/VentasCredito', 'App\Http\Controllers\EmpleadosController@VentasCredito');
    Route::get('/ConcentradoAdeudos', 'App\Http\Controllers\EmpleadosController@ConcentradoAdeudos');

    // Monedero Electrónico
    Route::get('/CatMonederoElectronico', 'App\Http\Controllers\MonederoElectronicoController@CatMonederoElectronico');
    Route::post('/EditarMonederoElectronico/{idCatMonedero}', 'App\Http\Controllers\MonederoElectronicoController@EditarMonederoElectronico');
    Route::get('/ReporteMonedero', 'App\Http\Controllers\MonederoElectronicoController@ReporteMonedero');

    // Tablas Update (adicionales)
    Route::get('/TablasUpdate', 'App\Http\Controllers\TablasUpdateController@TablasUpdate');
    Route::get('/AgregarTablasActualizablesTienda/{idTienda}', 'App\Http\Controllers\TablasUpdateController@AgregarTablasActualizablesTienda');
    Route::post('/ActualizarTablas/{idTienda}', 'App\Http\Controllers\TablasUpdateController@ActualizarTablas');
    Route::post('/AgregarTablaUpdate/{idTienda}', 'App\Http\Controllers\TablasUpdateController@AgregarTablaUpdate');

    // Paquetes
    Route::get('/CatPaquetes', 'App\Http\Controllers\PaquetesController@CatPaquetes');
    Route::get('/VerPaquetes', 'App\Http\Controllers\PaquetesController@VerPaquetes')->name('VerPaquetes');
    Route::get('/BuscarCodArticuloPaquqete', 'App\Http\Controllers\PaquetesController@BuscarCodArticuloPaquqete');
    Route::post('/GuardarPaquete', 'App\Http\Controllers\PaquetesController@GuardarPaquete');
    Route::get('/EditarPaquete/{idPaquete}', 'App\Http\Controllers\PaquetesController@EditarPaquete');
    Route::post('/EditarPaqueteExistente/{idPaquete}', 'App\Http\Controllers\PaquetesController@EditarPaqueteExistente');
    Route::post('/EliminarPaquete/{idPaquete}', 'App\Http\Controllers\PaquetesController@EliminarPaquete');
    Route::get('/Paquetes', 'App\Http\Controllers\PaquetesController@PaquetesLocal');
    Route::get('/ActivarPaquetes/{idPaquete}', 'App\Http\Controllers\PaquetesController@ActivarPaquetesLocal');
    Route::get('/DesactivarPaquetes/{idPaquete}', 'App\Http\Controllers\PaquetesController@DesactivarPaquetesLocal');
    Route::post('/Paquetes/{idPreparado}', 'App\Http\Controllers\PaquetesController@ActualizarCantidadRecepcion');

    // Transacciones
    Route::get('/TransaccionProducto', 'App\Http\Controllers\TransaccionProductoController@TransaccionProducto');
    Route::get('/BuscarArticuloTransaccion', 'App\Http\Controllers\TransaccionProductoController@BuscarArticuloTransaccion');
    Route::post('/GuardarTransaccion', 'App\Http\Controllers\TransaccionProductoController@GuardarTransaccion');
    Route::get('/HistorialTransaccion', 'App\Http\Controllers\TransaccionProductoController@HistorialTransaccion');
    Route::get('/HistorialTransaccionExcel', 'App\Http\Controllers\TransaccionProductoController@HistorialTransaccion');
    Route::get('/TransaccionesTienda', 'App\Http\Controllers\TransaccionesTiendaController@TransaccionesTienda');
    Route::post('/AgregarTransaccionTienda/{idTienda}', 'App\Http\Controllers\TransaccionesTiendaController@AgregarTransaccionTienda');
    Route::post('/EliminarTransaccionTienda/{idTienda}', 'App\Http\Controllers\TransaccionesTiendaController@EliminarTransaccionTienda');

    // Cancelación Tickets
    Route::get('/SolicitudCancelacionTicket', 'App\Http\Controllers\CancelacionTicketsController@SolicitudCancelacionTicket');
    Route::post('/SolicitudCancelacionTicket/Subir', 'App\Http\Controllers\CancelacionTicketsController@SolicitudCancelacionTicketSubir');
    Route::post('/SolicitarCancelacion/{idEncabezado}', 'App\Http\Controllers\CancelacionTicketsController@SolicitarCancelacion');
    Route::get('/CancelacionTickets', 'App\Http\Controllers\CancelacionTicketsController@CancelacionTickets');
    Route::post('/CancelarTicket/{idEncabezado}', 'App\Http\Controllers\CancelacionTicketsController@CancelarTicket');
    Route::post('/CancelarTicket/Cancelar/{idEncabezado}', 'App\Http\Controllers\CancelacionTicketsController@CancelarCancelarTicket');
    Route::get('/HistorialCancelacionTickets', 'App\Http\Controllers\CancelacionTicketsController@HistorialCancelacionTickets');

    // Reporte Solicitud Cancelación
    Route::get('/ReporteSolicitudCancelacion', 'App\Http\Controllers\ReporteCancelacionTicketsController@SolicitudesCancelacion');

    // Correos Tienda
    Route::get('/CorreosTienda', 'App\Http\Controllers\CorreosTiendaController@CorreosTienda');
    Route::post('/GuardarCorreosTienda/{idTienda}', 'App\Http\Controllers\CorreosTiendaController@GuardarCorreosTienda');
    Route::post('/EditarCorreosTienda/{idTienda}', 'App\Http\Controllers\CorreosTiendaController@EditarCorreosTienda');

    // Mermas (adicionales)
    Route::get('/TiposMermaArticulo', 'App\Http\Controllers\TiposMermaController@TiposMermaArticulo')->name('TiposMermaArticulo');
    Route::post('/AgregarArticuloMerma/{idTipoMerma}', 'App\Http\Controllers\TiposMermaController@AgregarArticuloMerma')->name('AgregarArticuloMerma');
    Route::post('/EliminarArticuloTipoMerma/{idTipoMerma}/{codArticulo}', 'App\Http\Controllers\TiposMermaController@EliminarArticuloTipoMerma')->name('EliminarArticuloTipoMerma');

    // Cap Mermas
    Route::get('/CapMermas', 'App\Http\Controllers\CapMermasController@CapMermas')->name('CapMermas');
    Route::post('/TmpMermas/{idTipoMerma}', 'App\Http\Controllers\CapMermasController@TmpMermas')->name('TmpMermas');
    Route::post('/GuardarMermas', 'App\Http\Controllers\CapMermasController@GuardarMermas')->name('GuardarMermas');
    Route::post('/EliminarMermaTmp/{idMermaTmp}', 'App\Http\Controllers\CapMermasController@EliminarMermaTmp')->name('EliminarMermaTmp');
    Route::get('/ReporteMermas', 'App\Http\Controllers\CapMermasController@ReporteMermas')->name('ReporteMermas');

    // Interfaz Mermas
    Route::get('/InterfazMermas', 'App\Http\Controllers\InterfazMermasController@InterfazMermas')->name('InterfazMermas');
    Route::post('/InterfazarMermas/{idTienda}/{fecha1}/{fecha2}', 'App\Http\Controllers\InterfazMermasController@InterfazarMermas')->name('InterfazarMermas');
    Route::get('/InterfazMermasExcel', 'App\Http\Controllers\InterfazMermasController@InterfazMermasExcel');

    // Dashboards
    Route::get('DashTiendas', 'App\Http\Controllers\DashTiendasController@Tiendas')->name('DashTiendas');
    Route::get('DashTiendas/graficas', 'App\Http\Controllers\DashTiendasController@Grafica')->name('DashTiendas.grafica');
    Route::get('DashTienda', 'App\Http\Controllers\DashTiendaController@Index')->name('DashTienda');
    Route::get('DashTienda/grafica', 'App\Http\Controllers\DashTiendaController@Grafica')->name('DashTienda.grafica');
    Route::post('DashTienda/enviar-pedido/{orden}', 'App\Http\Controllers\DashTiendaController@enviarPedidoOracle')->name('DashTienda.enviar-pedido');
    Route::post('DashTienda/enviar-correo', 'App\Http\Controllers\DashTiendaController@enviarCorreoOracle')->name('DashTienda.enviar-correo-cliente');
    Route::get('DashCorte', 'App\Http\Controllers\DashCorteController@Index')->name('DashCorte');
    Route::get('DashTiendaAdmin', 'App\Http\Controllers\DashTiendaAdminController@Index')->name('DashTiendaAdmin');
    Route::get('DashVentaPorTicket', 'App\Http\Controllers\DashVentaPorTicketController@index')->name('DashVentaPorTicket');
    Route::get('DashVentaPorTicket/exports', 'App\Http\Controllers\DashVentaPorTicketController@exports')->name('DashVentaPorTicket.exports');
    Route::get('DashTicketsCancelados', 'App\Http\Controllers\DashTicketsCanceladosController@index')->name('DashTicketsCancelados');
    Route::get('DashTicketsCancelados/exports', 'App\Http\Controllers\DashTicketsCanceladosController@exports')->name('DashTicketsCancelados.exports');

    // Cortes Tienda
    Route::get('/VerCortesTienda', 'App\Http\Controllers\CortesTiendaController@VerCortesTienda')->name('VerCortesTienda');
    Route::get('/BuscarCajasTienda', 'App\Http\Controllers\CortesTiendaController@BuscarCajasTienda')->name('BuscarCajasTienda');
    Route::get('/GenerarCorteOraclePDF/{fecha}/{idTienda}/{idDatCaja}', 'App\Http\Controllers\CortesTiendaController@GenerarCorteOraclePDF')->name('GenerarCorteOraclePDF');
    Route::get('/procesarclientescontado/{fecha}/{idTienda}/{idDatCaja}', 'App\Http\Controllers\CortesTiendaController@ProcesarClientesContado')->name('ProcesarClientesContado');
    Route::get('/procesarclientesfacturas/{fecha}/{idTienda}/{idDatCaja}', 'App\Http\Controllers\CortesTiendaController@ProcesarClientesFacturas')->name('ProcesarClientesFacturas');
    Route::get('/InformacionVentas', 'App\Http\Controllers\ReportesController@ReporteInformacionVentas')->name('InformacionVentas');

    // Reportes
    Route::get('/ReporteMermasAdmin', 'App\Http\Controllers\ReportesController@ReporteMermasAdmin')->name('ReporteMermasAdmin');
    Route::get('/ReporteMermasAdminExcel', 'App\Http\Controllers\ReportesController@ReporteMermasAdminExcel')->name('ReporteMermasAdminExcel');
    Route::get('/ReporteRosticeroAdmin', 'App\Http\Controllers\ReportesController@ReporteRosticeroAdmin')->name('ReporteRosticeroAdmin');
    Route::get('/ReporteConcentradoDeArticulos', 'App\Http\Controllers\ReportesController@ReporteConcentradoDeArticulos')->name('ReporteConcentradoDeArticulos');
    Route::get('/ExportReporteConcentradoDeArticulos', 'App\Http\Controllers\ReportesController@ExportReporteConcentradoDeArticulos')->name('ExportReporteConcentradoDeArticulos');
    Route::get('/ReporteDescuentos', 'App\Http\Controllers\ReportesController@reporteDescuentos')->name('ReporteDescuentos');
    Route::get('/ExportsReporteDescuentos', 'App\Http\Controllers\ReportesController@exportsDescuentos')->name('ExportsReporteDescuentos');
    Route::get('/ReportePaquetes', 'App\Http\Controllers\ReportesController@reportePaquetes')->name('ReportePaquetes');
    Route::get('/ExportsReportePaquetes', 'App\Http\Controllers\ReportesController@exportsPaquetes')->name('ExportsReportePaquetes');
    Route::get('/ReporteConcentradoDeTickets', 'App\Http\Controllers\ReportesController@ReporteConcentradoDeTickets')->name('ReporteConcentradoDeTickets');
    Route::get('/ExportReporteConcentradoDeTickets', 'App\Http\Controllers\ReportesController@ExportReporteConcentradoDeTickets')->name('ExportReporteConcentradoDeTickets');
    Route::get('/ReportePorTipoDePrecio', 'App\Http\Controllers\ReportesController@ReportePorTipoDePrecio')->name('ReportePorTipoDePrecio');
    Route::get('/ExportReportePorTipoDePrecio', 'App\Http\Controllers\ReportesController@ExportReportePorTipoDePrecio')->name('ExportReportePorTipoDePrecio');
    Route::get('/ReporteConcentradoPorCiudadYFamilia', 'App\Http\Controllers\ReportesController@ReporteConcentradoPorCiudadYFamilia')->name('ReporteConcentradoPorCiudadYFamilia');
    Route::get('/ExportReporteConcentradoPorCiudadYFamilia', 'App\Http\Controllers\ReportesController@ExportReporteConcentradoPorCiudadYFamilia')->name('ExportReporteConcentradoPorCiudadYFamilia');
    Route::get('/ReporteConcentradoPorTiendaYFamilia', 'App\Http\Controllers\ReportesController@ReporteConcentradoPorTiendaYFamilia')->name('ReporteConcentradoPorTiendaYFamilia');
    Route::get('/ReporteGrupoYTipoPrecio', 'App\Http\Controllers\ReportesController@ReporteGrupoYTipoPrecio')->name('ReporteGrupoYTipoPrecio');
    Route::get('/ExportReporteGrupoYTipoPrecio', 'App\Http\Controllers\ReportesController@ExportReporteGrupoYTipoPrecio')->name('ExportReporteGrupoYTipoPrecio');
    Route::get('/ReporteDineroElectronido', 'App\Http\Controllers\ReportesController@ReporteDineroElectronido')->name('ReporteDineroElectronido');
    Route::get('/ExportReporteDineroElectronido', 'App\Http\Controllers\ReportesController@ExportReporteDineroElectronido')->name('ExportReporteDineroElectronido');
    Route::get('/ReportePedidosOracle', 'App\Http\Controllers\ReportesController@ReportePedidosOracle')->name('ReportePedidosOracle');

    // Bloqueo Empleados
    Route::get('/BloqueoEmpleados', 'App\Http\Controllers\BloqueoEmpleadosController@BloqueoEmpleados');
    Route::post('/AgregarBloqueoEmpleado', 'App\Http\Controllers\BloqueoEmpleadosController@AgregarBloqueoEmpleado');
    Route::post('/DesbloquearEmpleado/{numNomina}', 'App\Http\Controllers\BloqueoEmpleadosController@DesbloquearEmpleado');
    Route::get('/BuscarEmpleadoParaBloqueo/{numNomina}', 'App\Http\Controllers\BloqueoEmpleadosController@BuscarEmpleadoParaBloqueo');

    // Resumen Ventas
    Route::get('/ResumenVentas', 'App\Http\Controllers\ResumenVentasController@ResumenVentas');

    // Preparados
    Route::get('/Preparados', 'App\Http\Controllers\PreparadosController@Preparados')->name('Preparados.index');
    Route::post('/Preparados', 'App\Http\Controllers\PreparadosController@AgregarPreparados');
    Route::post('/EditarPreparados/{id}', 'App\Http\Controllers\PreparadosController@EditarPreparados');
    Route::post('/EditarListaPreciosPreparados/{id}', 'App\Http\Controllers\PreparadosController@EditarListaPreciosPreparados');
    Route::post('/EnviarPreparados/{id}', 'App\Http\Controllers\PreparadosController@EnviarPreparados');
    Route::post('/EliminarPreparados/{id}', 'App\Http\Controllers\PreparadosController@EliminarPreparados');
    Route::post('/AgregarArticuloDePreparados/{idPreparado}', 'App\Http\Controllers\PreparadosController@AgregarArticulo');
    Route::post('/EliminarArticuloDePreparados/{id}', 'App\Http\Controllers\PreparadosController@EliminarArticulo');

    // Asignar Preparados
    Route::get('/AsignarPreparados', 'App\Http\Controllers\AsignarPreparadosController@Preparados')->name('AsignarPreparados.index');
    Route::get('/AsignarPreparados/{id}', 'App\Http\Controllers\AsignarPreparadosController@VerPreparado')->name('AsignarPreparados.id');
    Route::post('/RegresarPreparado/{id}', 'App\Http\Controllers\AsignarPreparadosController@RegresarPreparado');
    Route::post('/FinalizarPreparado/{id}', 'App\Http\Controllers\AsignarPreparadosController@FinalizarPreparado');
    Route::post('/AsignarTienda/{id}', 'App\Http\Controllers\AsignarPreparadosController@AsignarTienda');
    Route::post('/EliminarTiendaAsignada/{id}', 'App\Http\Controllers\AsignarPreparadosController@EliminarTiendaAsignada');

    // Detalle Asignados
    Route::get('/DetalleAsignados', 'App\Http\Controllers\AsignacionPreparadosController@Asignados')->name('Asignados.index');

    // Actualización Precios
    Route::get('/ActualizacionPrecios', 'App\Http\Controllers\ActualizacionPreciosController@index');

    // Update Sistema
    Route::get('/Update', 'App\Http\Controllers\ConfigSystemController@Index')->name('Update.index');

    // Descuentos
    Route::get('/CatDescuentos', 'App\Http\Controllers\DescuentosController@CatDescuentos');
    Route::get('/VerDescuentos', 'App\Http\Controllers\DescuentosController@VerDescuentos')->name('VerDescuentos');
    Route::get('/VerDescuentosDetallado', 'App\Http\Controllers\DescuentosController@VerDescuentosDetallado')->name('VerDescuentosDetallado');
    Route::post('/GuardarDescuento', 'App\Http\Controllers\DescuentosController@GuardarDescuento');
    Route::get('/EditarDescuento/{IdEncDescuento}', 'App\Http\Controllers\DescuentosController@EditarDescuento');
    Route::post('/EditarDescuentoExistente/{idDescuento}', 'App\Http\Controllers\DescuentosController@EditarDescuentoExistente');
    Route::post('/EliminarDescuento/{IdEncDescuento}', 'App\Http\Controllers\DescuentosController@EliminarDescuento');
    Route::post('/DesactivarArticuloPromocion', 'App\Http\Controllers\DescuentosController@DesactivarArticuloPromocion');

    // Cat Prod Diez
    Route::get('/CatProdDiez', 'App\Http\Controllers\CatProdDiezController@index')->name('CatProdDiez.index');
    Route::post('/CrearCatProdDiez', 'App\Http\Controllers\CatProdDiezController@store');
    Route::delete('/EliminarCatProdDiez/{id}', 'App\Http\Controllers\CatProdDiezController@destroy');

    // Interfaz/Envio de pedidos a Oracle
    Route::get('/InterfazPedidos', 'App\Http\Controllers\InterfazController@index')->name('interfaz.index');
    Route::get('/InterfazPedidos/Detallado', 'App\Http\Controllers\InterfazController@detallado')->name('interfaz.detallado');

    // Ordenes Oracle
    Route::get('/OrdenesOracle', 'App\Http\Controllers\OrdenesOracleController@index');

    // Ordenes Oracle
    Route::get('/EstatusFacturas', 'App\Http\Controllers\EstatusFacturasController@index')->name('facturasdiarias.index');

    // Devoluciones
    Route::get('/Devoluciones', 'App\Http\Controllers\DevolucionController@index');
    Route::get('/devoluciones/{folio}/refresh', 'App\Http\Controllers\DevolucionController@refresh')->name('devoluciones.refresh');
    Route::get('/api/devoluciones/{folio}', 'App\Http\Controllers\DevolucionController@show');
    Route::post('/api/devoluciones', 'App\Http\Controllers\DevolucionController@store');

    // Habilitar tickets viejos para facturar
    Route::get('/TicketFacturacion', [TicketFacturacionController::class, 'index']);
    Route::post('/TicketFacturacion/Habilitar', [TicketFacturacionController::class, 'habilitar']);
    Route::post('/TicketFacturacion/Desactivar/{id}', [TicketFacturacionController::class, 'desactivar']);
}); // Termina Middleware Auth

// ORDENEDES DE VENTAS AUTOSERVICIOS
Route::middleware('auth')->group(function () {
    // Clientes Autoservicio
    Route::get('/ClientesAutoservicio', [ClientesAutoservicioController::class, 'index'])->name('autoservicio.index');
    Route::get('/api/autoservicio/buscar-clientes', [ClientesAutoservicioController::class, 'buscarClientes']);
    Route::get('/api/autoservicio/buscar-shipto', [ClientesAutoservicioController::class, 'buscarShipTo']);
    Route::get('/api/autoservicio/buscar-billto', [ClientesAutoservicioController::class, 'buscarBillTo']);
    Route::get('/api/autoservicio/buscar-precios', [ClientesAutoservicioController::class, 'buscarPrecios']);
    Route::get('/api/autoservicio/buscar-tipo-orden', [ClientesAutoservicioController::class, 'buscarTipoOrden']);
    Route::post('/ClientesAutoservicio/guardar', [ClientesAutoservicioController::class, 'guardar'])->name('autoservicio.guardar');

    // Interfaz de Autoservicio
    Route::get('/AutoservicioFacturacion', [AutoservicioFacturacionController::class, 'index'])->name('autoservicio.facturacion');
    Route::post('/AutoservicioFacturacion/enviar', [AutoservicioFacturacionController::class, 'enviar'])->name('autoservicio.enviar');

    // Reporte de Interfaz Autoservicio
    Route::get('/AutoservicioReporte', [AutoservicioFacturacionController::class, 'reporte'])->name('autoservicio.reporte');
    Route::get('/api/autoservicio/detalle/{folio}', [AutoservicioFacturacionController::class, 'detalleLineas']);
});

// GRUPO ROSTICERO
Route::middleware('auth')->group(function () {
    // Interfaz Rosticero (Admin)
    Route::get('/InterfazarRosticero', 'App\Http\Controllers\InterfazRosticeroController@index');
    Route::post('/InterfazarRosticeroBaja/{idTienda}/{fecha1}/{fecha2}', 'App\Http\Controllers\InterfazRosticeroController@InterfazarBaja');
    Route::post('/InterfazarRosticeroAlta/{idTienda}/{fecha1}/{fecha2}', 'App\Http\Controllers\InterfazRosticeroController@InterfazarAlta');

    // Rosticero (Cajero)
    Route::get('/VerRosticero', 'App\Http\Controllers\RosticeroController@VerRosticero');
    Route::post('/CrearRosticero', 'App\Http\Controllers\RosticeroController@CrearRosticero');
    Route::post('/EditarRosticero/{id}', 'App\Http\Controllers\RosticeroController@EditarRosticero');
    Route::post('/AgregarDetalleRosticero/{id}', 'App\Http\Controllers\RosticeroController@AgregarDetalleRosticero');
    Route::post('/Api/AgregarDetalleRosticero/{id}', 'App\Http\Controllers\RosticeroController@ApiAgregarDetalleRosticero');
    Route::post('/RecalentadoRosticero/{id}', 'App\Http\Controllers\RosticeroController@RecalentadoRosticero');
    Route::delete('/EliminarRosticero/{id}', 'App\Http\Controllers\RosticeroController@EliminarRosticero');
    Route::put('/CambiarDetalleRosticero/{id}', 'App\Http\Controllers\RosticeroController@CambiarDetalleRosticero');
    Route::delete('/EliminarDetalleRosticero/{id}', 'App\Http\Controllers\RosticeroController@EliminarDetalleRosticero');
    Route::post('/FinalizarRosticero/{id}', 'App\Http\Controllers\RosticeroController@FinalizarRosticero');
    Route::post('/RecalentarRosticero', 'App\Http\Controllers\RosticeroController@RecalentarRosticero');
    Route::post('/MermarRosticero', 'App\Http\Controllers\RosticeroController@MermarRosticero');
    Route::get('/HistorialRosticero', 'App\Http\Controllers\RosticeroController@HistorialRosticero');

    // Reporte Movimientos Inventario
    Route::get('/ReporteMovimientosInventario', 'App\Http\Controllers\ReporteMovimientosProductosController@index');
    Route::get('/ReporteMovimientosInventario/exports', 'App\Http\Controllers\ReporteMovimientosProductosController@exports');
});

// Autenticación
Route::get('/Login', 'App\Http\Controllers\Auth\LoginController@Login')->middleware('guest')->name('login');
Route::post('/authenticate', 'App\Http\Controllers\Auth\LoginController@authenticate');
Route::post('/Logout', 'App\Http\Controllers\Auth\LoginController@Logout');

// Pruebas
Route::get('/pruebas', 'App\Http\Controllers\PruebasController@pruebas');
Route::get('/pruebas2', 'App\Http\Controllers\PruebasController@pruebas2');
Route::get('/pruebasjob', 'App\Http\Controllers\PruebasController@pruebasjob');
Route::get('/promesas', 'App\Http\Controllers\PruebasController@promesas');
Route::post('/SubirArchivo', 'App\Http\Controllers\PruebasController@SubirArchivo');
Route::post('/runnertest', 'App\Http\Controllers\PruebasController@SubirArchivo');

//Precios test
Route::get('/CalculoPrecios', 'App\Http\Controllers\DashboardController@CalculoPrecios')->name('CalculoPrecios');

// pagina de error 404
Route::fallback(function () {
    return view('Errores.Error404');
});
