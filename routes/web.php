<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', [CursoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboardAdmin');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/cursos/otros', [CursoController::class, 'storeOtro'])->name('cursos.store.otro');
    Route::post('/cursos/obligatorios', [CursoController::class, 'storeObligatorio'])->name('cursos.store.obligatorio');
    Route::post('/cursos/validar-semarnat', [CursoController::class, 'validarSemarnat'])->name('cursos.validar.semarnat');
    Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy');


    Route::post('/admin/cursos-obligatorios', [AdminController::class, 'storeCursoObligatorio'])->name('admin.cursos.store');
    Route::delete('/admin/cursos-obligatorios/{id}', [AdminController::class, 'destroyCursoObligatorio'])->name('admin.cursos.destroy');

    Route::get('/admin/reportes/', [AdminController::class, 'exportarExcel'])->name('admin.reportes.excel');


    Route::get('/admin/descargar-zip/empleado/{id}', [AdminController::class, 'descargarZipEmpleado'])->name('admin.descargar.zip.empleado');
    Route::get('/admin/descargar-zip/curso', [AdminController::class, 'descargarZipCurso'])->name('admin.descargar.zip.curso');


    Route::put('/admin/cursos-obligatorios/{id}', [AdminController::class, 'updateCursoObligatorio'])->name('admin.cursos.update');


     Route::post('/admin/recordatorio/{id}', [AdminController::class, 'enviarRecordatorio'])->name('admin.recordatorio.empleado');


     Route::get('/cursos/evidencia/{id}', [CursoController::class, 'verEvidencia'])->name('cursos.ver.evidencia');
     Route::get('/cursos/validacion/{id}', [CursoController::class, 'verValidacion'])->name('cursos.ver.validacion');
});

require __DIR__.'/auth.php';
