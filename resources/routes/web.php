<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\FoldController;
use App\Http\Controllers\DashboardController;
use App\Models\Cell;
use App\Http\Controllers\AttendanceReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('members', MemberController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('folds', FoldController::class);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Service routes
    Route::get('/attendance/services/{service}', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.mark');
    
    // Attendance CRUD routes
    Route::get('attendance/{service}', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/{service}/create', [App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance/{service}', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('attendance/{service}/show/{attendance}', [App\Http\Controllers\AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('attendance/{service}/edit/{attendance}', [App\Http\Controllers\AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('attendance/{service}/update/{attendance}', [App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance/{service}/destroy/{attendance}', [App\Http\Controllers\AttendanceController::class, 'destroy'])->name('attendance.destroy');
    
    // Attendance marking routes
    Route::post('attendance/{service}/mark/{member}', [AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::post('attendance/{service}/mark-first-timer/{firstTimer}', [AttendanceController::class, 'markFirstTimer'])->name('attendance.markFirstTimer');
    Route::post('attendance/{service}/finalize', [AttendanceController::class, 'finalize'])->name('attendance.finalize');
    
    // Bulk operations and utilities
    Route::post('attendance/{service}/bulk-update', [AttendanceController::class, 'bulkUpdate'])->name('attendance.bulk-update');
    Route::get('attendance/{service}/statistics', [App\Http\Controllers\AttendanceController::class, 'statistics'])->name('attendance.statistics');
    Route::get('attendance/{service}/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');

    Route::get('/attendance/service/{service_id}', [AttendanceController::class, 'showServiceAttendance'])->name('attendance.service');

    // First Timers management
    Route::get('first-timers', [\App\Http\Controllers\FirstTimerController::class, 'index'])->name('first_timers.index');
    Route::get('first-timers/create', [\App\Http\Controllers\FirstTimerController::class, 'create'])->name('first_timers.create');
    Route::post('first-timers', [\App\Http\Controllers\FirstTimerController::class, 'store'])->name('first_timers.store');
    Route::post('first-timers/{id}/promote', [\App\Http\Controllers\FirstTimerController::class, 'promote'])->name('first_timers.promote');
    Route::delete('first-timers/{id}', [\App\Http\Controllers\FirstTimerController::class, 'destroy'])->name('first_timers.destroy');

    // API routes for first timer functionality
    Route::get('/api/members/search', [\App\Http\Controllers\FirstTimerController::class, 'searchMembers'])->name('api.members.search');

    // Dashboard drilldown routes
    Route::get('dashboard/cells', [\App\Http\Controllers\DashboardController::class, 'cells'])->name('dashboard.cells');
    Route::get('dashboard/folds', [\App\Http\Controllers\DashboardController::class, 'folds'])->name('dashboard.folds');
    Route::get('dashboard/members', [\App\Http\Controllers\DashboardController::class, 'members'])->name('dashboard.members');
    Route::get('dashboard/cells/{id}', [\App\Http\Controllers\DashboardController::class, 'cellDetail'])->name('dashboard.cells.detail');
    Route::get('dashboard/folds/{id}', [\App\Http\Controllers\DashboardController::class, 'foldDetail'])->name('dashboard.folds.detail');
    Route::get('dashboard/members/{id}', [\App\Http\Controllers\DashboardController::class, 'memberDetail'])->name('dashboard.members.detail');
    Route::get('dashboard/analytics', [\App\Http\Controllers\DashboardController::class, 'analytics'])->name('dashboard.analytics');

    // Attendance Report routes
    Route::get('/attendance-report', [AttendanceReportController::class, 'index'])->name('attendance.report');
    Route::get('/attendance-report/show', [AttendanceReportController::class, 'show'])->name('attendance.report.show');
});

Route::middleware('auth')->get('/api/cells/{cell}/folds', function (Cell $cell) {
    return $cell->folds()->orderBy('name')->get();
});

require __DIR__.'/auth.php';
