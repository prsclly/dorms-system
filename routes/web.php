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
use App\Http\Controllers\admin\AdminPermissionController;
use App\Http\Controllers\admin\PermissionApprovalController;
use App\Http\Controllers\resident\PermissionHistoryController;
use App\Http\Controllers\parents\ParentPermissionController;



// Main Page Route
Route::get('/', function () {
  if (auth('admin')->check()) {
      return redirect()->route('admin.dashboard');
  } elseif (auth('resident')->check()) {
      return redirect()->route('resident.dashboard');
  } elseif (auth('technician')->check()) {
      return redirect()->route('admin.dashboard'); // sesuaikan kalau teknisi punya dashboard khusus
  } else {
      return redirect()->route('resident.login');
  }
});

//admin login
Route::get('/admin/login', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::post('/admin/login', [LoginBasic::class, 'login'])->name('auth-login-basic-post');
// Logout
Route::post('/logout', [LoginBasic::class, 'logout'])->name('admin.logout');

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

    // Manage Parents (Hanya Admin)
    Route::get('admin/manage-parents', [ManageParentController::class, 'index'])->name('admin.manage.parents');
    Route::post('admin/manage-parents', [ManageParentController::class, 'store'])->name('admin.manage.parents.store');
    Route::put('admin/manage-parents/{parent}', [ManageParentController::class, 'update'])->name('admin.manage.parents.update');
    Route::delete('admin/manage-parents/{parent}', [ManageParentController::class, 'destroy'])->name('admin.manage.parents.destroy');

     Route::get('admin/manage-admins', [ManageAdminController::class, 'index'])->name('admin.manage.admins');
    Route::post('admin/manage-admins', [ManageAdminController::class, 'store'])->name('admin.manage.admins.store');
    Route::put('admin/manage-admins/{admin}', [ManageAdminController::class, 'update'])->name('admin.manage.admins.update');
    Route::delete('admin/manage-admins/{admin}', [ManageAdminController::class, 'destroy'])->name('admin.manage.admins.destroy');


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

     Route::get('admin/permissions', [PermissionApprovalController::class, 'index'])->name('admin.permissions.index');
    Route::get('admin/permissions/{permission}', [PermissionApprovalController::class, 'show'])->name('admin.permissions.show');
    Route::post('admin/permissions/{permission}/approve', [PermissionApprovalController::class, 'approve'])->name('admin.permissions.approve');
    Route::post('admin/permissions/{permission}/reject', [PermissionApprovalController::class, 'reject'])->name('admin.permissions.reject');
    Route::get('admin/permissions/{permission}/download', [PermissionApprovalController::class, 'downloadPDF'])->name('admin.permissions.download');

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
Route::middleware(['auth:technician'])->group(function () {
    // Technician Dashboard
    Route::get('/admin/dashboard', [Analytics::class, 'index'])->name('admin.dashboard');

    // Technician tasks
    Route::get('/technician/tasks', [TaskController::class, 'index'])->name('technician.tasks');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('technician.tasks.updateStatus');

    Route::get('/task-history', [TaskHistoryController::class, 'index'])->name('technician.task-history');

    Route::get('/manage-technicians', [ManageTechnicianController::class, 'index'])->name('admin.manage.technicians');
    Route::post('/manage-technicians', [ManageTechnicianController::class, 'store'])->name('admin.manage.technicians.store');
    Route::put('/manage-technicians/{technician}', [ManageTechnicianController::class, 'update'])->name('admin.manage.technicians.update');
    Route::delete('/manage-technicians/{technician}', [ManageTechnicianController::class, 'destroy'])->name('admin.manage.technicians.destroy');

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
    Route::get('/permissions/create', [ParentPermissionController::class, 'create'])->name('parent.permissions.create');
    Route::post('/permissions', [ParentPermissionController::class, 'store'])->name('parent.permissions.store');
    Route::get('/permissions/history', [ParentPermissionController::class, 'history'])->name('parent.permissions.history');

});
