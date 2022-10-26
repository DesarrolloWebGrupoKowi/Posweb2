<?php

use Illuminate\Support\Facades\Route;
use App\Models\Articulo;
use App\Models\Usuario;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
  //  return view('welcome');
//});

//Index Home
Route::get('/', function () {
  return view('Index.index');
})->name('index');

Route::group(['middleware' => 'auth'], function () {
//+============================================================================================================================================+//
//Mostrar Catalogo Usuarios 
Route::get('CatUsuarios', 'App\Http\Controllers\UsuariosController@CatUsuarios');

Route::get('/ConfirmarContrasena', 'App\Http\Controllers\ConfirmarContrasenaController@ConfirmarContrasena')
    ->middleware('auth');//Ruta para mostrar la vista

Route::post('/ConfirmContrasena/{id}', 'App\Http\Controllers\ConfirmarContrasenaController@ConfirmContrasena')
    ->middleware('auth');//Ruta Logica para confirmar la contraseña

//CrearUsuario
Route::post('/CrearUsuario', 'App\Http\Controllers\UsuariosController@CrearUsuario');

//EliminarUsuario
Route::post('Eliminar/{id}', 'App\Http\Controllers\UsuariosController@Eliminar');

//EditarUsuario
Route::post('Editar/{id}', 'App\Http\Controllers\UsuariosController@EditarUsuario');

//Cambiar Contraseña de usuario
Route::post('CambiarContraseña/{id}', 'App\Http\Controllers\UsuariosController@CambiarContraseña');

//Activar Usuario
Route::post('ActivarUsuario/{id}', 'App\Http\Controllers\UsuariosController@ActivarUsuario');

//MiPerfil
Route::get('MiPerfil', 'App\Http\Controllers\UsuariosController@MiPerfil');

//EditarPerfil
Route::post('EditarPerfil/{id}', 'App\Http\Controllers\UsuariosController@EditarPerfil');

//Cambiar Pasword
Route::post('CambiarPassword/{id}', 'App\Http\Controllers\UsuariosController@CambiarPassword');

//+============================================================================================================================================+//
//Mostrar Estados
Route::get('CatEstados', 'App\Http\Controllers\EstadosController@CatEstados');

//CrearEstado
Route::post('/CrearEstado', 'App\Http\Controllers\EstadosController@CrearEstado');

//Editar Estado
Route::post('EditarEstado/{id}','App\Http\Controllers\EstadosController@EditarEstado');

//+============================================================================================================================================+//
//Mostrar Ciudades
Route::get('CatCiudades', 'App\Http\Controllers\CiudadesController@CatCiudades');

//CrearCiudad
Route::post('/CrearCiudad', 'App\Http\Controllers\CiudadesController@CrearCiudad');

//Editar Ciudad
Route::post('/EditarCiudad/{id}', 'App\Http\Controllers\CiudadesController@EditarCiudad');

//Ruta Select Dinamico Estado-Ciudad
Route::get('/Ciudades/{id}', 'App\Http\Controllers\CiudadesController@Ciudades');

//+============================================================================================================================================+//
//Mostrar Tiendas
Route::get('CatTiendas', 'App\Http\Controllers\TiendasController@CatTiendas');

//CrearTienda
Route::post('/CrearTienda', 'App\Http\Controllers\TiendasController@CrearTienda');

//Editar Tienda
Route::post('EditarTienda/{id}','App\Http\Controllers\TiendasController@EditarTienda');

//Eliminar Tienda
Route::post('EliminarTienda/{id}', 'App\Http\Controllers\TiendasController@EliminarTienda');

//+============================================================================================================================================+//
//Mostrar Tipo de Usuarios
Route::get('/CatTipoUsuarios','App\Http\Controllers\TipoUsuariosController@CatTipoUsuarios');

//Crear Tipo Usuario
Route::post('/CrearTipoUsuario','App\Http\Controllers\TipoUsuariosController@CrearTipoUsuario');

//EditarTipoUsuario
Route::post('/EditarTipoUsuario/{id}','App\Http\Controllers\TipoUsuariosController@EditarTipoUsuario');

//EliminarTipoUsuario
Route::post('/EliminarTipoUsuario/{id}','App\Http\Controllers\TipoUsuariosController@EliminarTipoUsuario');

//+============================================================================================================================================+//
//Mostrar Plazas
Route::get('/CatPlazas','App\Http\Controllers\PlazasController@CatPlazas');
//Crear Plaza
Route::post('/CrearPlaza','App\Http\Controllers\PlazasController@CrearPlaza');
//Editar Plaza
Route::post('/EditarPlaza/{id}','App\Http\Controllers\PlazasController@EditarPlaza');

//+============================================================================================================================================+//
//Mostrar Usuarios Tienda
Route::get('/CatUsuariosTienda','App\Http\Controllers\UsuariosTiendaController@CatUsuariosTienda');

//Crear Usuario Tienda
Route::post('/CrearUsuarioTienda','App\Http\Controllers\UsuariosTiendaController@CrearUsuarioTienda');

//Editar Usuario Tienda
Route::post('/EditarUsuarioTienda/{id}','App\Http\Controllers\UsuariosTiendaController@EditarUsuarioTienda');

//Eliminar Usuario Tienda
Route::post('EliminarUsuarioTienda/{id}','App\Http\Controllers\UsuariosTiendaController@EliminarUsuarioTienda');

//+============================================================================================================================================+//
//Mostrar Menu Posweb
Route::get('/CatMenuPosweb','App\Http\Controllers\MenuPoswebController@CatMenuPosweb');

//Crear Menu Posweb
Route::post('/CrearMenuPosweb','App\Http\Controllers\MenuPoswebController@CrearMenuPosweb');

//Editar Menu Posweb
Route::post('/EditarMenu/{id}','App\Http\Controllers\MenuPoswebController@EditarMenu');

//OrdenarMenus
Route::get('/OrdenarMenus','App\Http\Controllers\MenuPoswebController@OrdenarMenus');

//EditarPosicionMenu
Route::get('/EditarPosicionMenu','App\Http\Controllers\MenuPoswebController@EditarPosicionMenu');

//+============================================================================================================================================+//
//Mostrar Tipo de Menu
Route::get('/CatTipoMenu','App\Http\Controllers\TipoMenuController@CatTipoMenu');

//Crear Tipo de Menu
Route::post('/CrearTipoMenu','App\Http\Controllers\TipoMenuController@CrearTipoMenu');

//Editar Tipo de Menu
Route::post('/EditarTipoMenu/{id}','App\Http\Controllers\TipoMenuController@EditarTipoMenu');

//+============================================================================================================================================+//
//Mostrar Dat Menu Tipo Usuario
Route::get('/DatMenuTipoUsuario','App\Http\Controllers\MenuTipoUsuarioController@DatMenuTipoUsuario');

//Crear Menu Tipo Usuario
Route::post('/CrearMenuTipoUsuario','App\Http\Controllers\MenuTipoUsuarioController@CrearMenuTipoUsuario');

//Remover Menú
Route::get('/RemoverMenu','App\Http\Controllers\MenuTipoUsuarioController@RemoverMenu')->name('RemoverMenu');

//Agregar Menú
Route::get('/AgregarMenu', 'App\Http\Controllers\MenuTipoUsuarioController@AgregarMenu');

//+============================================================================================================================================+//
//Mostrar Articulos
Route::get('/CatArticulos','App\Http\Controllers\ArticulosController@CatArticulos');

//Crear Articulo
Route::post('/CrearArticulo', 'App\Http\Controllers\ArticulosController@CrearArticulo');

//Editar Articulo
Route::post('EditarArticulo/{id}', 'App\Http\Controllers\ArticulosController@EditarArticulo');

//EnviarArticulo
Route::get('EnviarArticulo', 'App\Http\Controllers\ArticulosController@EnviarArticulo')->name('EnviarArticulo');

//mostrarArticulo
Route::get('mostrarArticulo', 'App\Http\Controllers\ArticulosController@mostrarArticulo');

//mostrarArticulo
Route::post('AgregarArticulo/{id}', 'App\Http\Controllers\ArticulosController@AgregarArticulo');

//Articulo Item
Route::get('BuscarArticulo', 'App\Http\Controllers\ArticulosController@BuscarArticulo');

//LigarArticulo
Route::post('LigarArticulo', 'App\Http\Controllers\ArticulosController@LigarArticulo');

//ListadoCodEtiqueta
Route::get('/ListaCodEtiquetas', 'App\Http\Controllers\ListaCodEtiquetaController@ListaCodEtiquetas');

//GenerarPDF
Route::get('/GenerarPDF', 'App\Http\Controllers\ListaCodEtiquetaController@GenerarPDF');

//+============================================================================================================================================+//
//Mostrar Listas de Precio
Route::get('/CatListasPrecio','App\Http\Controllers\ListasPrecioController@CatListasPrecio');

//Crear Lista de Precio
Route::post('/CrearListaPrecio', 'App\Http\Controllers\ListasPrecioController@CrearListaPrecio');

//Editar Lista de Precio
Route::post('/EditarListaPrecio/{id}', 'App\Http\Controllers\ListasPrecioController@EditarListaPrecio');

//+============================================================================================================================================+//
//Mostrar Lista Precio Tienda
Route::get('/CatListaPrecioTienda', 'App\Http\Controllers\ListasPrecioTiendaController@CatListaPrecioTienda');

//CrearListaPrecioTienda
Route::post('/CrearListaPrecioTienda', 'App\Http\Controllers\ListasPrecioTiendaController@CrearListaPrecioTienda');

//REmover Lista
Route::get('/RemoverLista', 'App\Http\Controllers\ListasPrecioTiendaController@RemoverLista');

//Agregar Lista
Route::get('/AgregarLista', 'App\Http\Controllers\ListasPrecioTiendaController@AgregarLista');
//+============================================================================================================================================+//

//Mostrar Familias
Route::get('/CatFamilias','App\Http\Controllers\FamiliaArticulosController@CatFamilias');
//Crear Familia
Route::post('/CrearFamilia','App\Http\Controllers\FamiliaArticulosController@CrearFamilia');

//+============================================================================================================================================+//
//Mostrar Grupos
Route::get('/CatGrupos','App\Http\Controllers\GruposController@CatGrupos');
//Crear Grupo
Route::post('/CrearGrupo','App\Http\Controllers\GruposController@CrearGrupo');
//+============================================================================================================================================+//
//DatPrecios -> Precios POSWEB
Route::get('/Precios', 'App\Http\Controllers\PreciosController@Precios')->name('Precios');

//Actualizar Precios
Route::post('/ActualizarPrecios', 'App\Http\Controllers\PreciosController@ActualizarPrecios');

//+============================================================================================================================================+//
//Pedidos
Route::get('/Pedidos', 'App\Http\Controllers\PedidosController@Pedidos');

//Pedidos
Route::get('/DatPedidos', 'App\Http\Controllers\PedidosController@DatPedidos');

//Mostrar Pedidos
Route::get('/MostrarPedidos', 'App\Http\Controllers\PedidosController@MostrarPedidos');

//EliminarArticuloPedido
Route::post('/EliminarArticuloPedido/{id}', 'App\Http\Controllers\PedidosController@EliminarArticuloPedido');

//GuardarPedido
Route::get('/GuardarPedido', 'App\Http\Controllers\PedidosController@GuardarPedido');

//PedidosGuardados
Route::get('/PedidosGuardados', 'App\Http\Controllers\PedidosController@PedidosGuardados');

//CancelarPedido
Route::post('/CancelarPedido/{idPedido}', 'App\Http\Controllers\PedidosController@CancelarPedido');

//Enviar a Preventa (POS)
Route::post('/EnviarAPreventa/{idPedido}', 'App\Http\Controllers\PedidosController@EnviarAPreventa');

//+============================================================================================================================================+//
//Dashboard
Route::get('/Dashboard', 'App\Http\Controllers\DashboardController@Dashboard')->name('dashboard');

//+============================================================================================================================================+//
//CajCajas
Route::get('/CatCajas', 'App\Http\Controllers\CajasController@CatCajas');

//CrearCaja
Route::get('/CrearCaja', 'App\Http\Controllers\CajasController@CrearCaja');

//CajasTienda
Route::get('/CajasTienda', 'App\Http\Controllers\CajasController@CajasTienda');

//AgregarCajaTienda
Route::post('/AgregarCajaTienda', 'App\Http\Controllers\CajasController@AgregarCajaTienda');

//+============================================================================================================================================+//
//VentaTipoPago
Route::get('/VentaPorTipoPago', 'App\Http\Controllers\VentaPorTipoPagoController@VentaPorTipoPago');

//+============================================================================================================================================+//
//CatClientesCloud
Route::get('/CatClientesCloud', 'App\Http\Controllers\ClientesCloudController@CatClientesCloud');

//BuscarCustomer
Route::get('/BuscarCustomer', 'App\Http\Controllers\ClientesCloudController@BuscarCustomer');

//GuardarCustomerCloud
Route::get('/GuardarCustomerCloud', 'App\Http\Controllers\ClientesCloudController@GuardarCustomerCloud');

//+============================================================================================================================================+//
//SolicitudFactura
Route::get('/SolicitudFactura', 'App\Http\Controllers\SolicitudFacturaController@SolicitudFactura');

//VerSolicitudesFactura
Route::get('/VerSolicitudesFactura', 'App\Http\Controllers\SolicitudFacturaController@VerSolicitudesFactura');

//GuardarSolicitudFacturaClienteNuevo
Route::post('/GuardarSolicitudFacturaClienteNuevo', 'App\Http\Controllers\SolicitudFacturaController@GuardarSolicitudFacturaClienteNuevo');

//VerificarSolicitudFactura
Route::get('/VerificarSolicitudFactura/{idTicket}/{rfcCliente}/{bill_To}/{correo}', 'App\Http\Controllers\SolicitudFacturaController@VerificarSolicitudFactura');

//GuardarSolicitudFactura
Route::post('/GuardarSolicitudFactura', 'App\Http\Controllers\SolicitudFacturaController@GuardarSolicitudFactura');

//SubirConstanciaSolicitud
Route::post('/SubirConstanciaSolicitud/{idSolicitudFactura}', 'App\Http\Controllers\SolicitudFacturaController@SubirConstanciaSolicitud');

//+============================================================================================================================================+//
//ClientesNuevos
Route::get('/ClientesNuevos', 'App\Http\Controllers\LigarClientesController@ClientesNuevos');

//LigarCliente
Route::get('/LigarCliente', 'App\Http\Controllers\LigarClientesController@LigarCliente');

//GuardarLigueCliente
Route::post('/GuardarLigueCliente/{idSolicitudFactura}/{bill_To}', 'App\Http\Controllers\LigarClientesController@GuardarLigueCliente');

//GuardarCheckClienteEditado
Route::get('/GuardarCheckClienteEditado', 'App\Http\Controllers\LigarClientesController@GuardarCheckClienteEditado');

//VerConstanciaCliente
Route::get('/VerConstanciaCliente/{idSolicitudFactura}', 'App\Http\Controllers\LigarClientesController@VerConstanciaCliente');

//+============================================================================================================================================+//

//ClientesCloudTienda
Route::get('/ClientesCloudTienda', 'App\Http\Controllers\ClientesCloudTiendaController@ClientesCloudTienda');

//RelacionClienteCloudTienda
Route::get('/RelacionClienteCloudTienda', 'App\Http\Controllers\ClientesCloudTiendaController@RelacionClienteCloudTienda');

//GuararRelacionClienteCloud
Route::get('/GuardarRelacionClienteCloud', 'App\Http\Controllers\ClientesCloudTiendaController@GuardarRelacionClienteCloud');

//GuardarDatClienteCloud
Route::post('/GuardarDatClienteCloud', 'App\Http\Controllers\ClientesCloudTiendaController@GuardarDatClienteCloud');

//VerClientesCloudTienda
Route::get('/VerClientesCloudTienda', 'App\Http\Controllers\ClientesCloudTiendaController@VerClientesCloudTienda');

//+============================================================================================================================================+//
//RecepcionProducto
Route::get('/RecepcionProducto', 'App\Http\Controllers\RecepcionController@RecepcionProducto');

//RecepcionarProducto
Route::post('/RecepcionarProducto/{idRecepcion}', 'App\Http\Controllers\RecepcionController@RecepcionarProducto');

//CancelarRecepcion
Route::post('/CancelarRecepcion/{idRecepcion}', 'App\Http\Controllers\RecepcionController@CancelarRecepcion');

//AgregarProductoManual
Route::get('/AgregarProductoManual', 'App\Http\Controllers\RecepcionController@AgregarProductoManual');

//CapturaManualTmp
Route::get('/CapturaManualTmp', 'App\Http\Controllers\RecepcionController@CapturaManualTmp');

//EliminarProductoManual
Route::post('/EliminarProductoManual/{IdCapRecepcionManual}', 'App\Http\Controllers\RecepcionController@EliminarProductoManual');

//ReporteRecepciones
Route::get('/ReporteRecepciones', 'App\Http\Controllers\RecepcionController@ReporteRecepciones');

//RecepcionLocalSinInternet
Route::get('/RecepcionLocalSinInternet', 'App\Http\Controllers\RecepcionController@RecepcionLocalSinInternet');

//AgregarProductoLocalSinInternet
Route::get('/AgregarProductoLocalSinInternet', 'App\Http\Controllers\RecepcionController@AgregarProductoLocalSinInternet')->name('AgregarProductoLocalSinInternet');

//EliminarArticuloSinInternet
Route::post('/EliminarArticuloSinInternet/{idCapRecepcionManual}', 'App\Http\Controllers\RecepcionController@EliminarArticuloSinInternet')->name('EliminarArticuloSinInternet');

//RecepcionarProductoSinInternet
Route::post('/RecepcionarProductoSinInternet', 'App\Http\Controllers\RecepcionController@RecepcionarProductoSinInternet')->name('RecepcionarProductoSinInternet');

//+============================================================================================================================================+//
//Posweb Pantalla Principal
Route::get('/Pos', 'App\Http\Controllers\PoswebController@Pos')->name('Pos');

//EliminarPago
Route::post('/EliminarPago/{idDatTipoPago}', 'App\Http\Controllers\PoswebController@EliminarPago');

//BuscarEmpleado
Route::get('/BuscarEmpleado', 'App\Http\Controllers\PoswebController@BuscarEmpleado')->name('BuscarEmpleado');

//QuitarEmpleado
Route::get('/QuitarEmpleado', 'App\Http\Controllers\PoswebController@QuitarEmpleado')->name('QuitarEmpleado');

//CobroEmpleado
Route::get('/CobroEmpleado', 'App\Http\Controllers\PoswebController@CobroEmpleado')->name('CobroEmpleado');

//CalculosPreventa
Route::get('/CalculosPreventa', 'App\Http\Controllers\PoswebController@CalculosPreventa');

//EliminarArticuloPreventa
Route::post('/EliminarArticuloPreventa/{id}', 'App\Http\Controllers\PoswebController@EliminarArticuloPreventa');

//PaquetesPreventa
Route::get('/PaquetesPreventa', 'App\Http\Controllers\PoswebController@PaquetesPreventa');

//EliminarPreventa
Route::get('/EliminarPreventa', 'App\Http\Controllers\PoswebController@EliminarPreventa');

//iframeConsultarArticulo
Route::get('/iframeConsultarArticulo', 'App\Http\Controllers\PoswebController@iframeConsultarArticulo');

//Guardar Venta
Route::get('/GuardarVenta', 'App\Http\Controllers\PoswebController@GuardarVenta')->name('GuardarVenta');

//Corte Diario
Route::get('/CorteDiario', 'App\Http\Controllers\PoswebController@CorteDiario');

//GenerarCortePDF
Route::get('/GenerarCortePDF/{fecha}/{idTienda}/{idDatCaja}', 'App\Http\Controllers\PoswebController@GenerarCortePDF');

//Calculo Pago
Route::get('/CalculoMultiPago/{idEncabezado}/{restante}/{pago}/{idTipoPago}/{idBanco}/{numTarjeta}', 'App\Http\Controllers\PoswebController@CalculoMultiPago')->name('CalculoMultiPago');

//ImprimirTicketVenta
Route::get('/ImprimirTicketVenta/{idEncabezado}/{restante}/{pago}', 'App\Http\Controllers\PoswebController@ImprimirTicketVenta')->name('ImprimirTicketVenta');

//ReimprimirTicket
Route::get('ReimprimirTicket', 'App\Http\Controllers\PoswebController@ReimprimirTicket')->name('ReimprimirTicket');

//ImprimirTicket
Route::get('/ImprimirTicket', 'App\Http\Controllers\PoswebController@ImprimirTicket');

//VentaTicketDiario
Route::get('/VentaTicketDiario', 'App\Http\Controllers\PoswebController@VentaTicketDiario');

//ConcentradoVentas
Route::get('/ConcentradoVentas', 'App\Http\Controllers\PoswebController@ConcentradoVentas');

//VentaPorGrupo
Route::get('/VentaPorGrupo', 'App\Http\Controllers\PoswebController@VentaPorGrupo');

//PagoMonedero
Route::post('/PagoMonedero', 'App\Http\Controllers\PoswebController@PagoMonedero');

///CancelarDescuento
Route::get('/CancelarDescuento', 'App\Http\Controllers\PoswebController@CancelarDescuento');

//ReporteVentasListaPrecio
Route::get('/ReporteVentasListaPrecio', 'App\Http\Controllers\PoswebController@ReporteVentasListaPrecio')->name('ReporteVentasListasPrecio');

//+============================================================================================================================================+//
//Reporte de Stock
Route::get('/ReporteStock', 'App\Http\Controllers\StockTiendaController@ReporteStock');

//+============================================================================================================================================+//
//CatBancos
Route::get('/CatBancos', 'App\Http\Controllers\BancosController@CatBancos');

//AgregarBanco
Route::post('/AgregarBanco', 'App\Http\Controllers\BancosController@AgregarBanco');

//+============================================================================================================================================+//
//DatTipoPagoTienda
Route::get('/DatTipoPagoTienda', 'App\Http\Controllers\TipoPagoTiendaController@DatTipoPagoTienda');

//AgregarDatTipoPagoTienda
Route::get('/AgregarDatTipoPagoTienda', 'App\Http\Controllers\TipoPagoTiendaController@AgregarDatTipoPagoTienda');

//RemoverDatTipoPagoTienda
Route::get('/RemoverDatTipoPagoTienda', 'App\Http\Controllers\TipoPagoTiendaController@RemoverDatTipoPagoTienda');

//+============================================================================================================================================+//
//TipoPago
Route::get('CatTipoPago', 'App\Http\Controllers\TipoPagoController@CatTipoPago');

//Agregar TipoPago
Route::get('AgregarTipoPago', 'App\Http\Controllers\TipoPagoController@AgregarTipoPago');

//+============================================================================================================================================+//
//AdeudosEmpleado
Route::get('AdeudosEmpleado', 'App\Http\Controllers\EmpleadosController@AdeudosEmpleado');

//CreditosPagados
Route::get('CreditosPagados', 'App\Http\Controllers\EmpleadosController@CreditosPagados');

//VentaEmpleados
Route::get('VentaEmpleados', 'App\Http\Controllers\EmpleadosController@VentaEmpleados');

//VentasCredito
Route::get('/VentasCredito', 'App\Http\Controllers\EmpleadosController@VentasCredito');

//ConcentradoAdeudos
Route::get('/ConcentradoAdeudos', 'App\Http\Controllers\EmpleadosController@ConcentradoAdeudos');

//+============================================================================================================================================+//
//CatLimiteCredito
Route::get('CatLimiteCredito', 'App\Http\Controllers\LimiteCreditoController@CatLimiteCredito');

//EditarLimiteCredito
Route::get('EditarLimiteCredito/{tipoNomina}', 'App\Http\Controllers\LimiteCreditoController@EditarLimiteCredito');
//+============================================================================================================================================+//

//CatMonederoElectronico
Route::get('/CatMonederoElectronico', 'App\Http\Controllers\MonederoElectronicoController@CatMonederoElectronico');

//EditarMonederoElectronico
Route::post('/EditarMonederoElectronico/{idCatMonedero}', 'App\Http\Controllers\MonederoElectronicoController@EditarMonederoElectronico');

//ReporteMonedero
Route::get('/ReporteMonedero', 'App\Http\Controllers\MonederoElectronicoController@ReporteMonedero');

//+============================================================================================================================================+//
//CatMovimientosProducto
Route::get('/CatMovimientosProducto', 'App\Http\Controllers\MovimientosProductoController@CatMovimientosProducto');

//AgregarMovimiento
Route::post('/AgregarMovimiento', 'App\Http\Controllers\MovimientosProductoController@AgregarMovimiento');

//+============================================================================================================================================+//
//TablasUpdate
Route::get('/TablasUpdate', 'App\Http\Controllers\TablasUpdateController@TablasUpdate');

//CatTablas
Route::get('/CatTablas', 'App\Http\Controllers\TablasUpdateController@CatTablas');

//AgregarTablasActualizablesTienda
Route::get('/AgregarTablasActualizablesTienda/{idTienda}', 'App\Http\Controllers\TablasUpdateController@AgregarTablasActualizablesTienda');

//ActualizarTablas
Route::get('/ActualizarTablas/{idTienda}', 'App\Http\Controllers\TablasUpdateController@ActualizarTablas');

//AgregarTablaUpdate
Route::post('/AgregarTablaUpdate', 'App\Http\Controllers\TablasUpdateController@AgregarTablaUpdate');

//+============================================================================================================================================+//
//CatPaquetes
Route::get('/CatPaquetes', 'App\Http\Controllers\PaquetesController@CatPaquetes');

//VerPaquetes
Route::get('/VerPaquetes', 'App\Http\Controllers\PaquetesController@VerPaquetes');

///BuscarCodArticuloPaquqete
Route::get('/BuscarCodArticuloPaquqete', 'App\Http\Controllers\PaquetesController@BuscarCodArticuloPaquqete');

//GuardarPaquete
Route::post('/GuardarPaquete', 'App\Http\Controllers\PaquetesController@GuardarPaquete');

//EditarPaquete
Route::get('/EditarPaquete/{idPaquete}', 'App\Http\Controllers\PaquetesController@EditarPaquete');

//EditarPaqueteExistente
Route::post('/EditarPaqueteExistente/{idPaquete}', 'App\Http\Controllers\PaquetesController@EditarPaqueteExistente');

//EliminarPaquete
Route::post('/EliminarPaquete/{idPaquete}', 'App\Http\Controllers\PaquetesController@EliminarPaquete');

//+============================================================================================================================================+//
//TransaccionProducto
Route::get('/TransaccionProducto', 'App\Http\Controllers\TransaccionProductoController@TransaccionProducto');

//BuscarArticuloTransaccion
Route::get('/BuscarArticuloTransaccion', 'App\Http\Controllers\TransaccionProductoController@BuscarArticuloTransaccion');

//GuardarTransaccion
Route::post('/GuardarTransaccion', 'App\Http\Controllers\TransaccionProductoController@GuardarTransaccion');

//+============================================================================================================================================+//
//TransaccionesTienda
Route::get('/TransaccionesTienda', 'App\Http\Controllers\TransaccionesTiendaController@TransaccionesTienda');

//AgregarTransaccionTienda
Route::post('/AgregarTransaccionTienda/{idTienda}', 'App\Http\Controllers\TransaccionesTiendaController@AgregarTransaccionTienda');

//EliminarTransaccionTienda
Route::post('/EliminarTransaccionTienda/{idTienda}', 'App\Http\Controllers\TransaccionesTiendaController@EliminarTransaccionTienda');

//+============================================================================================================================================+//
//CancelacionTickets
Route::get('/CancelacionTickets', 'App\Http\Controllers\CancelacionTicketsController@CancelacionTickets');

//CancelarTicket
Route::post('/CancelarTicket/{idTienda}/{fechaVenta}/{numTicket}', 'App\Http\Controllers\CancelacionTicketsController@CancelarTicket');

//+============================================================================================================================================+//
//CorreosTienda
Route::get('/CorreosTienda', 'App\Http\Controllers\CorreosTiendaController@CorreosTienda');

//GuardarCorreosTienda
Route::post('/GuardarCorreosTienda/{idTienda}', 'App\Http\Controllers\CorreosTiendaController@GuardarCorreosTienda');

//EditarCorreosTienda
Route::post('/EditarCorreosTienda/{idTienda}', 'App\Http\Controllers\CorreosTiendaController@EditarCorreosTienda');

//+============================================================================================================================================+//
//TiposMerma
Route::get('/TiposMerma', 'App\Http\Controllers\TiposMermaController@TiposMerma')->name('TiposMerma');

//CrearTipoMerma
Route::post('/CrearTipoMerma', 'App\Http\Controllers\TiposMermaController@CrearTipoMerma')->name('CrearTipoMerma');

//SubTiposMerma
Route::get('/SubTiposMerma', 'App\Http\Controllers\TiposMermaController@SubTiposMerma')->name('SubTiposMerma');

//CrearSubTipoMerma
Route::post('/CrearSubTipoMerma/{idTipoMerma}', 'App\Http\Controllers\TiposMermaController@CrearSubTipoMerma')->name('CrearSubTipoMerma');

//EliminarSubTipoMerma
Route::post('/EliminarSubTipoMerma/{idSubTipoMerma}', 'App\Http\Controllers\TiposMermaController@EliminarSubTipoMerma')->name('EliminarSubTipoMerma');

//TiposMermaArticulo
Route::get('/TiposMermaArticulo', 'App\Http\Controllers\TiposMermaController@TiposMermaArticulo')->name('TiposMermaArticulo');

//AgregarArticuloMerma
Route::post('/AgregarArticuloMerma/{idTipoMerma}', 'App\Http\Controllers\TiposMermaController@AgregarArticuloMerma')->name('AgregarArticuloMerma');

//EliminarArticuloTipoMerma
Route::post('/EliminarArticuloTipoMerma/{idTipoMerma}/{codArticulo}', 'App\Http\Controllers\TiposMermaController@EliminarArticuloTipoMerma')->name('EliminarArticuloTipoMerma');

//EliminarTipoMerma
Route::post('/EliminarTipoMerma/{idTipoMerma}', 'App\Http\Controllers\TiposMermaController@EliminarTipoMerma')->name('EliminarTipoMerma');

//+============================================================================================================================================+//
//TipoArticulos
Route::get('/TipoArticulos', 'App\Http\Controllers\TipoArticulosController@TipoArticulos')->name('TipoArticulos');

//AgregarTipoArticulo
Route::post('/AgregarTipoArticulo', 'App\Http\Controllers\TipoArticulosController@AgregarTipoArticulo')->name('AgregarTipoArticulo');

//EliminarTipoArticulo
Route::post('/EliminarTipoArticulo/{idCatTipoArticulo}', 'App\Http\Controllers\TipoArticulosController@EliminarTipoArticulo')->name('EliminarTipoArticulo');

//+============================================================================================================================================+//
//CuentasMerma
Route::get('/CuentasMerma', 'App\Http\Controllers\CuentasMermaController@CuentasMerma')->name('CuentasMerma');

//AgregarCuentaMerma
Route::post('/AgregarCuentaMerma/{idTipoMerma}', 'App\Http\Controllers\CuentasMermaController@AgregarCuentaMerma')->name('AgregarCuentaMerma');

//+============================================================================================================================================+//
//CapMermas
Route::get('/CapMermas', 'App\Http\Controllers\CapMermasController@CapMermas')->name('CapMermas');

//TmpMermas
Route::post('/TmpMermas/{idTipoMerma}', 'App\Http\Controllers\CapMermasController@TmpMermas')->name('TmpMermas');

//GuardarMermas
Route::post('/GuardarMermas', 'App\Http\Controllers\CapMermasController@GuardarMermas')->name('GuardarMermas');

//EliminarMermaTmp
Route::post('/EliminarMermaTmp/{idMermaTmp}', 'App\Http\Controllers\CapMermasController@EliminarMermaTmp')->name('EliminarMermaTmp');

//ReporteMermas
Route::get('/ReporteMermas', 'App\Http\Controllers\CapMermasController@ReporteMermas')->name('ReporteMermas');

//+============================================================================================================================================+//
//InterfazMermas
Route::get('/InterfazMermas', 'App\Http\Controllers\InterfazMermasController@InterfazMermas')->name('InterfazMermas');

//InterfazarMermas
Route::post('/InterfazarMermas/{idTienda}/{fecha1}/{fecha2}', 'App\Http\Controllers\InterfazMermasController@InterfazarMermas')->name('InterfazarMermas');

//+============================================================================================================================================+//
//VerCortesTienda
Route::get('/VerCortesTienda', 'App\Http\Controllers\CortesTiendaController@VerCortesTienda')->name('VerCortesTienda');

//BuscarCajasTienda
Route::get('/BuscarCajasTienda', 'App\Http\Controllers\CortesTiendaController@BuscarCajasTienda')->name('BuscarCajasTienda');

//+============================================================================================================================================+//
  });//->Termina Middleware Auth


Route::get('/Login', 'App\Http\Controllers\Auth\LoginController@Login')->middleware('guest')->name('login');

Route::post('/authenticate', 'App\Http\Controllers\Auth\LoginController@authenticate');

Route::post('/Logout', 'App\Http\Controllers\Auth\LoginController@Logout');

Route::get('/pruebas', 'App\Http\Controllers\PruebasController@pruebas');

Route::get('/pruebas2', 'App\Http\Controllers\PruebasController@pruebas2');

Route::get('/promesas', 'App\Http\Controllers\PruebasController@promesas');

//SubirArchivo
Route::post('/SubirArchivo', 'App\Http\Controllers\PruebasController@SubirArchivo');


