<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PortfolioProjectController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'welcome']);
Route::get('/methodology', [PublicController::class, 'welcome']);
Route::get('/projects', [PublicController::class, 'welcome']);
Route::get('/contact', [PublicController::class, 'welcome']);
Route::post('/contact-form', [PublicController::class, 'contactForm']);

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

Route::get('/home', [DashboardController::class, 'index'])->name('home')->middleware('auth');

Route::middleware(['auth', 'role:senior'])->group(function () {
    Route::get('/project/create', [ProjectController::class, 'create']);
    Route::get('/project/{id}/edit', [ProjectController::class, 'edit']);
    Route::put('/project/{id}/edit', [ProjectController::class, 'update']);
    Route::get('/project', [ProjectController::class, 'index']);
    Route::post('/project/create', [ProjectController::class, 'store']);
    Route::get('/project/{id}', [ProjectController::class, 'show']);

    Route::get('/portfolio', [PortfolioProjectController::class, 'index']);
    Route::get('/portfolio/create', [PortfolioProjectController::class, 'create']);
    Route::post('/portfolio/create', [PortfolioProjectController::class, 'store']);
    Route::get('/portfolio/{id}/edit', [PortfolioProjectController::class, 'edit']);
    Route::put('/portfolio/{id}/edit', [PortfolioProjectController::class, 'update']);
    Route::delete('/portfolio/{id}', [PortfolioProjectController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users/create', [UserController::class, 'store']);
    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/users/{id}/edit', [UserController::class, 'update']);
    Route::patch('/users/{id}/deactivate', [UserController::class, 'deactivate']);
    Route::patch('/users/{id}/activate', [UserController::class, 'activate']);

    Route::post('/reports/hours', [ReportController::class, 'addHours']);
    Route::post('/reports/new', [ManagerController::class, 'createReport']);
    Route::post('/reports/details', [ManagerController::class, 'detailReport']);
    Route::post('/reports/store', [ReportController::class, 'store']);
    Route::delete('/reports/{id}', [ReportController::class, 'destroy']);

    Route::get('/task/delete/{id}', [TaskController::class, 'delete']);
    Route::get('/task/{task_id}/edit', [TaskController::class, 'edit']);
    Route::post('/task/update', [TaskController::class, 'update']);
    Route::post('/tasks/add-time', [TaskController::class, 'addTime']);
    Route::post('/tasks/add-watcher', [TaskController::class, 'addWatcher']);
});

Route::middleware(['auth', 'role:senior,professional'])->group(function () {
    Route::get('/task/create', [TaskController::class, 'create']);
    Route::post('/task/create', [TaskController::class, 'store']);
    Route::get('/task/{task_id}/create-a-child', [TaskController::class, 'createAChild']);
});

Route::middleware(['auth', 'permissions'])->group(function () {
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks/add-item', [TaskController::class, 'addItem']);
    Route::get('/new-task', [TaskController::class, 'createTask']);
    Route::post('/new-task', [TaskController::class, 'storeTask']);
    Route::get('/new-task/{task_id}', [TaskController::class, 'editTask']);
    Route::post('/new-task/update', [TaskController::class, 'update']);
    Route::post('/tasks/add-effort', [TaskController::class, 'storeEffort']);
    Route::post('/tasks/complete-item', [TaskController::class, 'completeItem']);
    Route::post('/tasks/add-message', [TaskController::class, 'addMessage']);
    Route::post('/tasks/assign-to', [TaskController::class, 'assignTo']);
    Route::post('/tasks/attach-file', [TaskController::class, 'attachFile']);
    Route::post('/tasks/change-to-testing', [TaskController::class, 'changeToTesting']);
    Route::post('/tasks/change-to-feedback', [TaskController::class, 'changeToFeedback']);
    Route::post('/tasks/change-to-finished', [TaskController::class, 'changeToFinished']);
    Route::post('/tasks/charge-effort', [TaskController::class, 'chargeEffort']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checking-testing', function () {
        return view('checking');
    });
    Route::get('/wiki', function () {
        return view('wiki');
    });

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
});
