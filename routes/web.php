<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HijoController;
use App\Http\Controllers\MensajeriaController;
use App\Http\Controllers\NutriologoController;

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS Y AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
// index.blade.php está en la raíz de views
Route::get('/', function () { return view('index'); })->name('inicio');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/procesar-login', [LoginController::class, 'procesarLogin']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/guardar-usuario', [LoginController::class, 'procesarRegistro'])->name('guardar.admin');

/*
|--------------------------------------------------------------------------
| 2. RUTAS DE PERFIL Y EDICIÓN (GENERALES)
|--------------------------------------------------------------------------
*/
Route::get('/perfil', [ViewController::class, 'mostrarPerfilSegunRol'])->name('perfil');

// inicio.blade.php ahora está dentro de la carpeta /users
Route::get('/inicio', function () { return view('users.inicio'); })->name('inicioo');

// agregar_hijo.blade.php ahora está dentro de /users
Route::get('/agregar_hijo', function () {
    return view('users.agregar_hijo');
})->name('agregar_hijo');

Route::get('/hijos-registrados', [ViewController::class, 'verHijosRegistrados'])->name('hijos.registrados');
Route::get('/ver-plan-hijo/{id}', [ViewController::class, 'descargarPlanHijo'])->name('plan.hijo');
Route::post('/agregar_hijo', [HijoController::class, 'store'])->name('guardar_hijo');

// editar_perfil.blade.php está en la raíz de views según tu imagen
Route::get('/editar-perfil/{id}', [ViewController::class, 'editarDocPerfil'])->name('perfil.editar');

Route::patch('/actualizar-firebase/{coleccion}/{id}', [ViewController::class, 'actualizarDoc'])->name('actualizar.doc');

// panel_usuario.blade.php ahora está en /users
Route::get('/panel-usuario', function () { return view('users.panel_usuario'); })->name('panel.usuario');

/* |--------------------------------------------------------------------------
| RUTAS NUTRIÓLOGO (Carpeta /nutri)
|--------------------------------------------------------------------------
*/
Route::get('/panel-nutriologo', [NutriologoController::class, 'index'])->name('panel.nutriologo');
Route::get('/perfil_nutri', [NutriologoController::class, 'perfil'])->name('perfil.nutri');

Route::get('/nutri/pacientes', [NutriologoController::class, 'verPacientes'])->name('nutri.pacientes');
Route::get('/nutri/plan-alimenticio', function () { return view('nutri.plan_alimenticio'); })->name('nutri.plan');
Route::get('/nutri/progreso', function () { return view('nutri.progreso'); })->name('nutri.progreso');
Route::get('/nutri/mensajes', function () { return view('nutri.mensajes'); })->name('nutri.mensajes');

Route::get('/asignar-plan-nino/{id}', [ViewController::class, 'pantallaAsignarPlan'])->name('nino.asignar_plan');
Route::post('/guardar-plan-nino/{id}', [ViewController::class, 'guardarPlanNino'])->name('nino.guardar_plan');

// ver_ninos.blade.php está en la raíz de views
Route::get('/ver-ninos', [ViewController::class, 'verTodosLosNinos'])->name('ver.ninos');

Route::get('/nutri/directorio', [NutriologoController::class, 'directorioNinos'])->name('nutri.pacientes_dir');
Route::get('/nutri/mis-pacientes', [NutriologoController::class, 'misPacientes'])->name('nutri.mis_pacientes');
Route::get('/nutri/elegir/{id}', [NutriologoController::class, 'elegirPaciente'])->name('nutri.elegir');

/*
|--------------------------------------------------------------------------
| 3. RUTAS PROTEGIDAS (SOLO ADMINISTRADORES - Carpeta /admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['checkAdmin'])->group(function () {
    // home.blade.php está en /admins
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::get('/registro-nutriologo', function () { return view('auth.register_nutriologo'); })->name('admin.register_nutri');
    Route::post('/guardar-nutriologo', [LoginController::class, 'guardarNutriologo'])->name('guardar.nutriologo');
    Route::get('/registro-admin', function () { return view('auth.register_admin'); })->name('admin.register');
    Route::get('/crear-usuario', function () { return view('auth.register'); })->name('usuario.crear');
    
    // ver_usuarios y ver_contactos están en /admins
    Route::get('/ver-usuarios', [ViewController::class, 'verUsuarios'])->name('ver.usuarios');
    Route::get('/ver-contactos', [ViewController::class, 'verContactos'])->name('ver.contactos');
    
    Route::get('/ver-servicios', [ViewController::class, 'verServicios'])->name('ver.servicios');
    Route::get('/estado-contacto/{id}/{estado}', [ViewController::class, 'estadoContacto'])->name('estado.contacto');

    Route::delete('/eliminar-firebase/{coleccion}/{id}', [ViewController::class, 'eliminarDoc']);
    Route::get('/editar-firebase/{coleccion}/{id}', [ViewController::class, 'editarDoc']);
    
    // responder_consulta.blade.php está en /admins
    Route::get('/admin/responder-mensaje/{id}', [MensajeriaController::class, 'mostrarFormularioRespuesta'])->name('mensaje.responder');
    Route::post('/admin/guardar-respuesta/{id}', [MensajeriaController::class, 'procesarRespuesta'])->name('mensaje.guardar');
    
    Route::get('/ninos', [ViewController::class, 'verTodosLosNinos'])->name('ver.ninos');
});

/*
|--------------------------------------------------------------------------
| 4. OTRAS RUTAS (Carpeta /users para la mayoría)
|--------------------------------------------------------------------------
*/
// crear_contacto.blade.php está en /users
Route::get('/crear_contacto', function () { return view('users.crear_contacto'); })->name('crear_contacto');
Route::post('/guardar-contacto', [FirebaseController::class, 'storeContacto'])->name('guardar.contacto');

// plan.blade.php y actividades.blade.php están en /users
Route::get('/plan/{edad}', function ($edad) { return view('users.plan', compact('edad')); });
Route::get('/actividades', function () { return view('users.actividades'); })->name('actividades');

// mis_consultas.blade.php está en /users
Route::get('/mis-consultas', [ViewController::class, 'misConsultas'])->name('mis_consultas');
Route::post('/buscar-alimento', [ViewController::class, 'buscarAlimento'])->name('alimento.buscar');
