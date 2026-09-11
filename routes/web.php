<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpjController;
use App\Http\Controllers\SpjReviewController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserSettingsController;
use App\Models\Spj;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/', function () {
        if (! auth()->user()->isBendahara()) {
            return view('pages.user-dashboard');
        }

        $spjs = Spj::query();

        return view('welcome', [
            'totalSpjs' => (clone $spjs)->count(),
            'pendingSpjs' => (clone $spjs)->where('status', 'submitted')->count(),
            'revisionSpjs' => (clone $spjs)->where('status', 'revision')->count(),
            'submittedAmount' => (clone $spjs)->sum('submitted_amount'),
            'recentSpjs' => (clone $spjs)->latest()->limit(5)->get(),
        ]);
    })->name('dashboard');

    Route::middleware('role:user')->group(function (): void {
        Route::get('/spj/baru', [SpjController::class, 'create'])->name('spj.create');
        Route::post('/spj', [SpjController::class, 'store'])->name('spj.store');
    });

    Route::get('/antrean-review', function () {
        $spjs = auth()->user()->isBendahara()
            ? Spj::query()->where('status', 'submitted')->latest()->get()
            : auth()->user()->spjs()->latest()->get();

        return view('pages.queue', ['spjs' => $spjs]);
    })->name('review.queue');

    Route::get('/spj', function () {
        $spjs = auth()->user()->isBendahara()
            ? Spj::query()->latest()->get()
            : auth()->user()->spjs()->latest()->get();

        return view('pages.spjs', ['spjs' => $spjs]);
    })->name('spj.index');

    Route::get('/pengaturan', [UserSettingsController::class, 'edit'])->name('settings.index');
    Route::put('/pengaturan', [UserSettingsController::class, 'update'])->name('settings.update');

    Route::middleware('role:bendahara')->group(function (): void {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::get('/laporan', function () {
            return view('pages.reports', [
                'approvedAmount' => Spj::query()->where('status', 'approved')->sum('submitted_amount'),
                'totalAmount' => Spj::query()->sum('submitted_amount'),
                'approvedCount' => Spj::query()->where('status', 'approved')->count(),
                'revisionCount' => Spj::query()->where('status', 'revision')->count(),
            ]);
        })->name('reports.index');
        Route::get('/spj/{spj}/review', [SpjReviewController::class, 'show'])->whereNumber('spj')->name('spj.review.show');
        Route::put('/spj/{spj}/review', [SpjReviewController::class, 'update'])->whereNumber('spj')->name('spj.review.update');
    });
});
