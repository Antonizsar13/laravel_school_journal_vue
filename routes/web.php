<?php

use App\Http\Controllers\AcademicDisciplineController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LearningClassController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Models\LearningClass;
use App\Models\Schedule;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['middleware' => ['role:Super Admin|Admin|Student']], function () {
        Route::get('/point/my_points', [PointController::class, 'myPoints'])->name('point.my_points');

        Route::get('/schedule/show_discipline', [ScheduleController::class, 'showDiscipline'])->name('schedule.show_discipline');
    });
    
    Route::group(['middleware' => ['role:Super Admin|Admin|Teacher']], function () {
        Route::get('academic_discipline/my_discipline', [AcademicDisciplineController::class, 'myDiscipline'])->name('academic_discipline.teacher.my_discipline');
        Route::get('academic_discipline/my_discipline/{academic_discipline}', [AcademicDisciplineController::class, 'myDisciplineClasses'])->name('academic_discipline.teacher.my_discipline_classes');
        Route::get('academic_discipline/my_discipline/{academic_discipline}/{learning_class}', [AcademicDisciplineController::class, 'myDisciplineClassStudents'])->name('academic_discipline.teacher.my_discipline_class_students');

        Route::get('/point/create_point_user/{academic_discipline}/{user}', [PointController::class, 'createPointUser'])->name('point.create_point_user');
        
        Route::get('/schedule/classes', [ScheduleController::class, 'classes'])->name('schedule.classes');
        Route::get('/schedule/show_class/{learning_class}', [ScheduleController::class, 'showClass'])->name('schedule.show_class');
    });

    Route::group(['middleware' => ['role:Super Admin|Admin']], function () {
        
        Route::get('/point/classes', [PointController::class, 'classes'])->name('point.classes');
        Route::get('/point/discipline_list/{learning_class}', [PointController::class, 'disciplineList'])->name('point.discipline_list');
        Route::get('/point/discipline_list/{learning_class}/{academic_discipline}', [PointController::class, 'disciplinePointsList'])->name('point.discipline_points_list');
        Route::get('/point/edit_point_user/{academic_discipline}/{user}', [PointController::class, 'editPointUser'])->name('point.edit_point_user');

        Route::resource('academic_discipline', AcademicDisciplineController::class);
        Route::resource('learning_class', LearningClassController::class);


        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/{user}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::patch('/permissions/{user}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{user}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    });

    Route::resource('point', PointController::class);

    Route::resource('homework', HomeworkController::class);

    Route::resource('schedule', ScheduleController::class);
    
});



require __DIR__ . '/auth.php';
