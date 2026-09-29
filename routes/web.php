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
        if (auth()->user()->isReviewer() && ! auth()->user()->isBendahara()) {
            $spjs = Spj::query();

            return view('pages.reviewer-dashboard', [
                'totalInProgress' => (clone $spjs)->whereNotIn('status', ['completed'])->count(),
                'waitingForReviewer' => (clone $spjs)->where('current_role', auth()->user()->role)->count(),
                'revisionSpjs' => (clone $spjs)->where('status', 'like', 'revision_%')->count(),
                'recentSpjs' => (clone $spjs)->whereNotIn('status', ['completed'])->latest()->limit(8)->get(),
            ]);
        }

        if (! auth()->user()->isBendahara()) {
            $userSpjs = auth()->user()->spjs();

            return view('pages.user-dashboard', [
                'recentSpjs' => (clone $userSpjs)->latest()->limit(8)->get(),
                'pendingSpjs' => (clone $userSpjs)->whereIn('status', ['submitted', 'visitor1_review', 'visitor2_review', 'kepala_review', 'bendahara_review'])->count(),
                'approvedSpjs' => (clone $userSpjs)->whereIn('status', ['approved', 'completed'])->count(),
            ]);
        }

        $spjs = Spj::query();

        return view('welcome', [
            'totalSpjs' => (clone $spjs)->count(),
            'pendingSpjs' => (clone $spjs)->whereIn('status', ['visitor1_review', 'visitor2_review', 'kepala_review', 'bendahara_review'])->count(),
            'revisionSpjs' => (clone $spjs)->where('status', 'like', 'revision_%')->count(),
            'submittedAmount' => (clone $spjs)->sum('submitted_amount'),
            'recentSpjs' => (clone $spjs)->latest()->limit(5)->get(),
        ]);
    })->name('dashboard');

    Route::middleware('role:user')->group(function (): void {
        Route::get('/spj/baru', [SpjController::class, 'create'])->name('spj.create');
        Route::post('/spj', [SpjController::class, 'store'])->name('spj.store');
        Route::put('/spj/{spj}', [SpjController::class, 'update'])->whereNumber('spj')->name('spj.update');
        Route::post('/spj/{spj}/resubmit', [SpjController::class, 'resubmit'])->whereNumber('spj')->name('spj.resubmit');
    });

    Route::get('/antrean-review', function () {
        $user = auth()->user();
        $spjs = $user->isReviewer()
            ? Spj::query()->when($user->role !== 'bendahara', fn ($query) => $query->where('current_role', $user->role))
                ->whereIn('status', ['submitted', 'visitor1_review', 'visitor2_review', 'kepala_review', 'bendahara_review'])
                ->latest()->get()
            : $user->spjs()->latest()->get();

        return view('pages.queue', ['spjs' => $spjs]);
    })->name('review.queue');

    Route::get('/spj', function () {
        $spjs = auth()->user()->isBendahara()
            ? Spj::query()->latest()->get()
            : auth()->user()->spjs()->latest()->get();

        return view('pages.spjs', ['spjs' => $spjs]);
    })->name('spj.index');
    Route::get('/spj/{spj}/detail', [SpjController::class, 'show'])->whereNumber('spj')->name('spj.show');

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
    });

    Route::middleware('role:visitor1,visitor2,kepala_dinas,bendahara')->group(function (): void {
        Route::get('/spj/{spj}/review', [SpjReviewController::class, 'show'])->whereNumber('spj')->name('spj.review.show');
        Route::put('/spj/{spj}/review', [SpjReviewController::class, 'update'])->whereNumber('spj')->name('spj.review.update');
    });
});
