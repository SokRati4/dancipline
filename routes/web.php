<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TrainingSystemController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\TimezoneController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DanceLegendController;
use App\Http\Controllers\Admin\TutorialController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\CompetitionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('training_systems', TrainingSystemController::class);
    Route::post('training_sessions/{id}/start', [TrainingSessionController::class, 'startSession'])->name('training_sessions.start');
    Route::post('training_sessions/{id}/finish', [TrainingSessionController::class, 'finishSession'])->name('training_sessions.finish');
    Route::get('training_systems/{trainingSystem}/schedule', [TrainingSystemController::class, 'schedule'])->name('training_systems.schedule');
    Route::get('training_sessions/edit/{id}/{system_id}', [TrainingSessionController::class, 'edit'])->name('training_sessions.edit');
    Route::resource('training_sessions', TrainingSessionController::class)->except(['edit','destroy']);
    Route::get('training_sessions/create_with_date/{date}/{system_id}', [TrainingSessionController::class, 'createWithDate'])->name('training_sessions.create_with_date');
    Route::delete('training_sessions/{id}', [TrainingSessionController::class, 'destroy'])->name('training_sessions.destroy');
    Route::post('/set-timezone', [TimezoneController::class, 'setTimezone']);
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/archived/systems', [StatisticsController::class, 'indexArchivedSystemStats'])->name('archived.systems');
    Route::get('/archived/weekly-stats', [StatisticsController::class, 'indexArchivedWeeklyStats'])->name('archived.weekly_stats');
    Route::get('/education', [EducationController::class, 'index'])->name('education.index');
    Route::get('/education/tutorials/{id}', [EducationController::class, 'showTutorial'])->name('educationTutorial.show');
    Route::get('/education/dance_legends/{id}', [EducationController::class, 'showLegend'])->name('educationLegend.show');
    Route::resource('notes',NotesController::class);
    Route::delete('/attachments/{id}', [NotesController::class, 'destroyAttachment'])->name('attachments.destroy');
    Route::get('competitions/schedule', [CompetitionController::class, 'schedule'])->name('competitions.schedule');
    Route::get('competitions/edit/{id}', [CompetitionController::class, 'edit'])->name('competitions.edit');
    Route::resource('competitions', CompetitionController::class)->except(['index','create','edit','destroy']);
    Route::get('competitions/create_with_date/{date}', [CompetitionController::class, 'createWithDate'])->name('competitions.create_with_date');
    Route::delete('competitions/{id}', [CompetitionController::class, 'destroy'])->name('competitions.destroy');
    

});
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class)->except(['show', 'create', 'store']);
    Route::resource('dance_legends', DanceLegendController::class);
    Route::resource('tutorials', TutorialController::class);
    Route::resource('quotes', QuoteController::class);
});