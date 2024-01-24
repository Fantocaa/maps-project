<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        // The user is logged in, redirect them to the desired page
        return redirect('/login');
    } else {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            // 'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
});

// Route::get('/register', function () {
// })->middleware(['auth', 'verified', 'role:superadmin']);

// Route::get('/registeruser', [RegisterUserController::class, 'create'])->name('registeruser')->middleware(['auth', 'verified', 'role:superadmin']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:superadmin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/components/buttons', function () {
    return Inertia::render('Components/Buttons');
})->middleware(['auth', 'verified'])->name('components.buttons');

Route::get('/manage/user', function () {
    return Inertia::render('Components/Register');
})->middleware(['auth', 'verified'])->name('manage.user');

//Maps Route

Route::get('/maps', function () {
})->middleware('auth.redirect');

Route::get('/maps/user', function () {
    return Inertia::render('Maps/MapsUser');
})->middleware(['auth', 'verified', 'role:user|superuser|admin|superadmin'])->name('mapsUser');

Route::get('/maps/superuser', function () {
    return Inertia::render('Maps/MapsSuperUser');
})->middleware(['auth', 'verified', 'role:superuser|admin|superadmin'])->name('mapsSuperUser');

Route::get('/maps/admin', function () {
    return Inertia::render('Maps/MapsAdmin');
})->middleware(['auth', 'verified', 'role:admin|superadmin'])->name('mapsAdmin');


require __DIR__ . '/auth.php';
