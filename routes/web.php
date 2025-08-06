<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\tables\FeedbackReportController;
use App\Http\Controllers\tables\TaskController;
use App\Http\Controllers\Technician\TaskHistoryController;
use App\Http\Controllers\authentications\LoginResident;
use App\Http\Controllers\resident\ResidentReportController;
use App\Http\Controllers\resident\DashboardResidentController;
use App\Http\Controllers\point_tracker\student_point;
use App\Http\Controllers\point_tracker\add_student_point;
use App\Http\Controllers\point_tracker\edit_student_point;
use App\Http\Controllers\point_tracker\add_log;
use App\Http\Controllers\Resident\ResidentProfileController;
use App\Http\Controllers\pages\ManageResidentController;
use App\Http\Controllers\feedback\Feedback;
use App\Http\Controllers\feedback\Dashboard_feedback;
use App\Http\Controllers\meal_menu\MealController;
use App\Http\Controllers\meal_menu\ListMenu;
use App\Http\Controllers\meal_menu\CrudMenu;
use App\Http\Controllers\resident\FeedbackMenuController;
use App\Http\Controllers\resident\MenuList;
use App\Http\Controllers\resident\StudentPointController;
use App\Http\Controllers\db_pic\PicController;
use App\Http\Controllers\Resident\FeedbackFormController;
use App\Http\Controllers\tables\ReportDashboardController;
use App\Http\Controllers\authentications\ParentAuthController;
use App\Http\Controllers\parents\DashboardParentController;
use App\Http\Controllers\pages\ManageParentController;
use App\Http\Controllers\parents\WeeklyMenuController;
use App\Http\Controllers\pages\ManageTechnicianController;
use App\Http\Controllers\pages\ManageAdminController;
use App\Http\Controllers\admin\PermissionApprovalController;
use App\Http\Controllers\resident\PermissionHistoryController;
use App\Http\Controllers\parents\PermissionController;
use App\Http\Controllers\admin\PermissionManagementController;
use App\Http\Controllers\technician\TechnicianDashboardController;


// Main Page Route
Route::get('/', function () {
  if (auth('admin')->check()) {
      return redirect()->route('admin.dashboard');
  } elseif (auth('resident')->check()) {
      return redirect()->route('resident.dashboard');
  } elseif (auth('technician')->check()) {
      return redirect()->route('technician.dashboard');
  } else {
      return redirect()->route('resident.login');
  }
});

//admin login
Route::get('/admin/login', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::post('/admin/login', [LoginBasic::class, 'login'])->name('auth-login-basic-post');
// Logout
Route::post('/logout', [LoginBasic::class, 'logout'])->name('admin.logout');
// Technician logout
Route::post('/technician/logout', [LoginBasic::class, 'logoutTechnician'])->name('technician.logout');

// Resident login routes
Route::get('/resident/login', [LoginResident::class, 'index'])->name('resident.login');
Route::post('/resident/login', [LoginResident::class, 'login'])->name('resident.login.post');
Route::post('/resident/logout', [LoginResident::class, 'logout'])->name('resident.logout');

// Admin Routes
// ==========================
Route::middleware(['auth:admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [Analytics::class, 'index'])->name('admin.dashboard');

    // All Reports
    Route::get('admin/reports', [TablesBasic::class, 'index'])->name('tables-basic');
    Route::post('/assign-task/{report}', [TablesBasic::class, 'assignTechnician'])->name('assign.technician');
    Route::get('/report/dashboard', [ReportDashboardController::class, 'index'])->name('report.dashboard');
    Route::post('/report/{report}/reject', [TablesBasic::class, 'rejectReport'])->name('reject.report');

    // Feedback (admin)
    Route::get('/admin/feedback-reports', [FeedbackReportController::class, 'index'])->name('tables-feedback-reports');

    // studentpoint
    Route::get('admin/point_tracker/student_point', [student_point::class, 'index'])->name('student_point');
    Route::get('admin/point_tracker/add_student_point', [add_student_point::class, 'index'])->name('add_student_point');
    Route::get('admin/point_tracker/edit_student_point/{id}', [edit_student_point::class, 'edit'])->name('edit_student_point');
    Route::get('admin/student-point', [student_point::class, 'index'])->name('student_point');
    Route::get('admin/student-point/{id}/add-log', [add_log::class, 'create'])->name('add_log');
    Route::post('admin/student-points/{id}/store-log', [add_log::class, 'store'])->name('store_log');

    // manage resident
    Route::get('admin/manage-residents', [ManageResidentController::class, 'index'])->name('admin.manage.residents');
    Route::post('admin/manage-residents', [ManageResidentController::class, 'store'])->name('admin.manage.residents.store');
    Route::post('admin/manage-residents/invite/{resident}', [ManageResidentController::class, 'sendInvite'])->name('admin.manage.residents.invite');
    Route::delete('admin/manage-residents/{resident}', [ManageResidentController::class, 'destroy'])->name('admin.manage.residents.destroy');
    Route::put('/admin/manage-resident/update/{id}', [ManageResidentController::class, 'update'])->name('admin.manage.residents.update');

    // Manage Parents (Hanya Admin)
    Route::get('admin/manage-parents', [ManageParentController::class, 'index'])->name('admin.manage.parents');
    Route::post('admin/manage-parents', [ManageParentController::class, 'store'])->name('admin.manage.parents.store');
    Route::put('admin/manage-parents/{parent}', [ManageParentController::class, 'update'])->name('admin.manage.parents.update');
    Route::delete('admin/manage-parents/{parent}', [ManageParentController::class, 'destroy'])->name('admin.manage.parents.destroy');

     Route::get('admin/manage-admins', [ManageAdminController::class, 'index'])->name('admin.manage.admins');
    Route::post('admin/manage-admins', [ManageAdminController::class, 'store'])->name('admin.manage.admins.store');
    Route::put('admin/manage-admins/{admin}', [ManageAdminController::class, 'update'])->name('admin.manage.admins.update');
    Route::delete('admin/manage-admins/{admin}', [ManageAdminController::class, 'destroy'])->name('admin.manage.admins.destroy');

    Route::get('admin/manage-technicians', [ManageTechnicianController::class, 'index'])->name('admin.manage.technicians');
    Route::post('admin/manage-technicians', [ManageTechnicianController::class, 'store'])->name('admin.manage.technicians.store');
    Route::put('admin/manage-technicians/{technician}', [ManageTechnicianController::class, 'update'])->name('admin.manage.technicians.update');
    Route::delete('admin/manage-technicians/{technician}', [ManageTechnicianController::class, 'destroy'])->name('admin.manage.technicians.destroy');

    //feedback menu
    Route::get('admin/catering/issue_log', [feedback::class, 'index'])->name('feedback-list');
    Route::get('admin/catering/dashboard', [Dashboard_feedback::class, 'index'])->name('feedback-dashboard');

    // menu list
    Route::get('admin/catering/menu', [ListMenu::class, 'index'])->name('menu_list');
    Route::get('/pic-for-meal', [MealController::class, 'picForMeal'])->name('pic.for.meal');

    // manage pic database
    Route::get('admin/database/pic', [PicController::class, 'manage'])->name('pic.manage');
    Route::post('admin/database/pic/store', [PicController::class, 'store'])->name('pic.store');
    Route::post('admin/database/pic/update/{id}', [PicController::class, 'update'])->name('pic.update');
    Route::delete('admin/database/pic/delete/{id}', [PicController::class, 'destroy'])->name('pic.destroy');

    Route::prefix('catering-daily-menu')->group(function () {
        Route::get('/{id}/edit', [CrudMenu::class, 'edit'])->name('catering-daily-menu.edit');
        Route::put('/{id}', [CrudMenu::class, 'update'])->name('catering-daily-menu.update');
        Route::delete('/{id}', [CrudMenu::class, 'destroy'])->name('catering-daily-menu.destroy');
        Route::post('/bulk', [CrudMenu::class, 'storeBulk'])->name('catering-daily-menu.storeBulk');
        Route::post('/store-single', [CrudMenu::class, 'storeSingle'])->name('catering-daily-menu.storeSingle');
    });



    Route::get('/admin/catering/feedback', [Feedback::class, 'index'])->name('feedback-list');




});



// ==========================
// Technician Routes
// ==========================
Route::middleware(['auth:technician'])->prefix('technician')->name('technician.')->group(function () {
    
    // Technician Dashboard
    Route::get('/dashboard', [TechnicianDashboardController::class, 'index'])->name('dashboard');

    // Technician tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    Route::get('/task-history', [TaskHistoryController::class, 'index'])->name('technician.task-history');


});

// Resident Route
Route::middleware(['auth:resident'])->group(function () {
    // Halaman Dashboard Resident
    Route::get('/resident/dashboard', [DashboardResidentController::class, 'index'])->name('resident.dashboard');

    // Halaman Riwayat Report
    Route::get('/resident/report-history', [ResidentReportController::class, 'index'])->name('resident.report.history');

    // Ambil detail report
    Route::get('/resident/report-history/detail/{id}', [ResidentReportController::class, 'detail'])->name('resident.report.detail');

    // Hapus report
    Route::delete('/resident/report-history/delete/{id}', [ResidentReportController::class, 'destroy'])->name('resident.report.destroy');

    // Update report
    Route::post('/resident/report-history/update/{id}', [ResidentReportController::class, 'update'])->name('resident.report.update');

    // Tambah report baru (via modal)
    Route::post('/resident/report-history/store', [ResidentReportController::class, 'store'])->name('resident.report.store');

    Route::post('/resident/report-history/feedback', [ResidentReportController::class, 'submitFeedback'])->name('resident.feedback.submit');

    Route::get('/profile', [ResidentProfileController::class, 'index'])->name('resident.profile');
    Route::post('/profile/update', [ResidentProfileController::class, 'update'])->name('resident.profile.update');

    Route::get('/resident/StudentPoint', [StudentPointController::class, 'index'])->name('student-point');

    Route::get('/feedback/{id}/edit', [FeedbackMenuController::class, 'edit'])->name('feedbackmenu.edit');
    Route::put('/feedback/{id}', [FeedbackMenuController::class, 'update'])->name('feedbackmenu.update');
    Route::delete('/feedback/{id}', [FeedbackMenuController::class, 'destroy'])->name('feedbackmenu.destroy');

    Route::get('/leave-history', [PermissionHistoryController::class, 'index'])->name('resident.permissions.history');
});

Route::prefix('resident')->middleware(['auth:resident'])->group(function () {
  // Route untuk Feedback Menu
  Route::get('/feedback_menu', [FeedbackMenuController::class, 'index'])->name('catering-feedback-history');
  Route::get('/daily_menu', [MenuList::class, 'index'])->name('catering-daily-menu');
  Route::get('/feedback_form', [FeedbackFormController::class, 'index'])->name('catering-feedback-form');
  Route::post('/feedback_form', [FeedbackFormController::class, 'store'])->name('feedbackmenu.store');
});

Route::get('/login', function () {
    return redirect()->back()->with('error', 'You are not authorized to access that page.');
})->name('login');

// Route login (tanpa middleware)
Route::get('/login/parent', [ParentAuthController::class, 'index'])->name('parent.login');
Route::post('/login/parent', [ParentAuthController::class, 'login'])->name('parent.login.post');

// Route logout (opsional)
Route::post('/logout/parent', [ParentAuthController::class, 'logout'])->name('parent.logout');
// Route untuk parent dashboard (dengan guard auth:parent)
Route::middleware('auth:parent')->group(function () {
  Route::get('/parent/dashboard', [DashboardParentController::class, 'index'])->name('parent.dashboard');
  Route::get('/parent/point-logs', [DashboardParentController::class, 'pointLogs'])->name('parent.point.logs');
  Route::get('/parent/weekly-menu', [WeeklyMenuController::class, 'index'])->name('parent.weekly-menu');

});

Route::middleware(['auth:parent'])->prefix('parent')->group(function () {
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('parent.permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('parent.permissions.store');
    Route::get('/permissions/history', [PermissionController::class, 'history'])->name('parent.permissions.history');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('parent.permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('parent.permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('parent.permissions.destroy');
});


Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    // ✅ Ganti ke ManagementController
    Route::get('/permissions', [PermissionManagementController::class, 'index'])->name('admin.permissions.index');

    // ✅ Detail, Approve, Reject, Download tetap pakai ApprovalController
    Route::get('/permissions/{permission}', [PermissionApprovalController::class, 'show'])->name('admin.permissions.show');
    Route::post('/permissions/{permission}/process', [PermissionApprovalController::class, 'process'])->name('admin.permissions.process');
    Route::post('/permissions/{permission}/approve', [PermissionApprovalController::class, 'approve'])->name('admin.permissions.approve');
    Route::post('/permissions/{permission}/reject', [PermissionApprovalController::class, 'reject'])->name('admin.permissions.reject');
    Route::get('/permissions/{permission}/download', [PermissionApprovalController::class, 'downloadPDF'])->name('admin.permissions.download');

});

Route::middleware(['auth:resident'])->prefix('resident')->group(function () {
    Route::get('/leave-history', [PermissionHistoryController::class, 'index'])->name('resident.permissions.history');
});

// Edit & Delete Point Log
Route::get('admin/student-point/{student_id}/log/{log_id}/edit', [add_log::class, 'edit'])->name('edit_log');
Route::put('admin/student-point/{student_id}/log/{log_id}', [add_log::class, 'update'])->name('update_log');
Route::delete('admin/student-point/{student_id}/log/{log_id}', [add_log::class, 'destroy'])->name('delete_log');
