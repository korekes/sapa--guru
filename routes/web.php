<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    SiswaController,
    KelasController,
    AbsensiController,
    NilaiController,
    DashboardController,
    PerwalianController,
    JurnalController,
    GuruController,
    MapelController,
    GuruMengajarController,
    JadwalController,
    NaikKelasController,
    KenaikanKelasController
};

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (SEMUA USER LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE (SEMUA ROLE LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:guest')->group(function () {
        Route::get('/kelas', [KelasController::class, 'index']);
        Route::get('/siswa', [SiswaController::class, 'index']);
        Route::get('/absensi', [AbsensiController::class, 'index']);
    });

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY (CRUD MASTER DATA)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class);

    Route::post('/siswa/import-csv', [SiswaController::class, 'importCsv'])
        ->name('siswa.importCsv');
    Route::post('/siswa/import-excel', [SiswaController::class, 'importExcel'])
        ->name('siswa.importExcel');

    Route::get('/guru/import', [GuruController::class, 'import'])
        ->name('guru.import');

    Route::post('/guru/import', [GuruController::class, 'importProcess'])
        ->name('guru.import.process');

    Route::get('/kelas/walikelas', [KelasController::class, 'editWalikelas'])
        ->name('kelas.walikelas');
    Route::post('/kelas/walikelas/update',[KelasController::class,'updateWaliKelas'])
        ->name('kelas.walikelas.update');
    Route::post('/kelas/walikelas', [KelasController::class, 'updateWalikelas'])
        ->name('kelas.walikelas.update');

    Route::resource('mapel', MapelController::class);

    Route::post('/mapel/import',[MapelController::class,'import'])
        ->name('mapel.import');
    Route::put('/mapel/{mapel}', [MapelController::class, 'update'])
        ->name('mapel.update');
    Route::get('/mapel/{mapel}/guru/{mengajar}',[MapelController::class,'showGuru'])
        ->name('mapel.guru');
    Route::get('/mapel/{mapel}/guru/{guruMengajar}',[MapelController::class, 'showGuru'])
        ->name('mapel.showGuru');

    Route::resource('jadwal',JadwalController::class);
    Route::post('/jadwal/import',[JadwalController::class,'import'])   
        ->name('jadwal.import');
    Route::post('/jadwal/drop',[JadwalController::class,'drop'])
        ->name('jadwal.drop');

    Route::prefix('naik-kelas')->group(function () {

        Route::get('/', [NaikKelasController::class,'index'])
            ->name('naik-kelas.index');

        Route::post('/preview', [NaikKelasController::class,'preview'])
            ->name('naik-kelas.preview');

        Route::post('/proses', [NaikKelasController::class,'proses'])
            ->name('naik-kelas.proses');

    });

    Route::prefix('kelas')->group(function () {
        Route::get('{kelas}/kenaikan',[KenaikanKelasController::class,'index'])
            ->name('kelas.kenaikan');
        Route::post('{kelas}/kenaikan',[KenaikanKelasController::class,'proses'])
            ->name('kelas.kenaikan.proses');
    });
});



/*
|--------------------------------------------------------------------------
| KELAS (ADMIN + GURU - SHARED ACCESS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // LIST & DETAIL KELAS (SEMUA ROLE BOLEH)
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/{id}', [SiswaController::class, 'show'])->name('siswa.show');
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/jurusan/{jurusan}', [KelasController::class, 'jurusan'])->name('kelas.jurusan');
    Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
});

/*
|--------------------------------------------------------------------------
| FITUR GURU (ABSENSI / NILAI / JURNAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->group(function () {

    // ABSENSI
    Route::get('/kelas/{kelas}/absensi', [AbsensiController::class, 'index'])
        ->name('absensi.kelas');
    Route::get('/kelas/{kelas}/absensi/create', [AbsensiController::class, 'create'])
        ->name('absensi.create');
    Route::post('/absensi', [AbsensiController::class, 'store'])
        ->name('absensi.store');
    Route::post('/absensi/update-status', [AbsensiController::class, 'updateStatus'])
        ->name('absensi.updateStatus');
    Route::get('/absensi/{absensi}/rekap', [AbsensiController::class, 'rekap'])
        ->name('absensi.rekap');
    Route::get('/absensi/{id}/edit', [AbsensiController::class, 'edit'])
        ->name('absensi.edit');
    Route::put('/absensi/{id}', [AbsensiController::class, 'update'])
        ->name('absensi.update');
    Route::delete('/absensi/{absensi}', [AbsensiController::class, 'destroy'])
        ->name('absensi.destroy');

    // NILAI
    Route::get('/kelas/{kelas}/nilai', [NilaiController::class, 'index'])
    ->name('nilai.kelas');
        // 3 jenis nilai
        Route::get('/kelas/{kelas}/nilai/absensi', [NilaiController::class, 'absensi'])
            ->name('nilai.absensi');

        Route::get('/kelas/{kelas}/nilai/sikap', [NilaiController::class, 'sikap'])
            ->name('nilai.sikap');
        Route::post('/nilai/sikap', [NilaiController::class, 'storeSikap'])
            ->name('nilai.sikap.store');
        Route::get('/kelas/{kelas}/nilai/keaktifan', [NilaiController::class, 'keaktifan'])
            ->name('nilai.sikap.keaktifan');
        Route::post('/nilai/keaktifan/store', [NilaiController::class, 'storeKeaktifan'])
            ->name('nilai.sikap.storeKeaktifan');
        Route::post('/nilai/sikap/save', [NilaiController::class, 'saveAjax'])
            ->name('nilai.sikap.save');

        Route::get('/kelas/{kelas}/nilai/perilaku', [NilaiController::class, 'perilaku'])
            ->name('nilai.sikap.perilaku');

        Route::get('/kelas/{kelas}/nilai/akademik', [NilaiController::class, 'akademik'])
            ->name('nilai.akademik');
        Route::get('/kelas/{kelas}/nilai/formatif/create',[NilaiController::class, 'createFormatif'])
            ->name('nilai.formatif.create');
        Route::get('/kelas/{kelas}/nilai/formatif/{bab}/show', [NilaiController::class, 'formatifShow'])
            ->name('nilai.formatif.show');
        Route::get('/kelas/{kelas}/nilai/formatif/{bab}/input', [NilaiController::class, 'inputFormatif'])
            ->name('nilai.formatif.input');
        Route::post('/nilai/formatif/ajax',[NilaiController::class, 'saveFormatifAjax'])
            ->name('nilai.formatif.ajax');
        Route::get('/kelas/{kelas}/nilai/formatif', [NilaiController::class, 'formatifList'])
            ->name('nilai.formatif.list');
        Route::get('/kelas/{kelas}/nilai/formatif/{bab}', [NilaiController::class, 'formatif'])
            ->name('nilai.formatif');
        Route::get('/kelas/{kelas}/nilai/formatif/{bab}/show', [NilaiController::class, 'formatifShow'])
            ->name('nilai.formatif.show');
        Route::post('/nilai/formatif/ajax', [NilaiController::class, 'saveFormatifAjax'])
            ->name('nilai.formatif.ajax');

        // UTS
        Route::get('/kelas/{kelas}/nilai/uts', [NilaiController::class, 'uts'])
            ->name('nilai.uts.show');
        Route::post('/nilai/uts', [NilaiController::class, 'storeUTS'])->name('nilai.uts.store');
        Route::post('/nilai/uts/ajax', [NilaiController::class, 'saveUtsAjax'])->name('nilai.uts.ajax');

        // UAS
        Route::get('/kelas/{kelas}/nilai/uas', [NilaiController::class, 'uas'])
            ->name('nilai.uas.show');
        Route::post('/nilai/uas', [NilaiController::class, 'storeUAS'])->name('nilai.uas.store');
        Route::post('/nilai/uas/ajax', [NilaiController::class, 'saveUasAjax'])->name('nilai.uas.ajax');


    // NILAI SIKAP DAN AKIF
    Route::post('/nilai/keaktifan/ajax', [NilaiController::class, 'saveKeaktifanAjax'])
        ->name('nilai.keaktifan.ajax'); 
    Route::post('/nilai/perilaku/ajax', [NilaiController::class, 'savePerilakuAjax'])
        ->name('nilai.perilaku.ajax');

    // JURNAL
    Route::get('/kelas/{kelas}/jurnal', [JurnalController::class, 'index'])
        ->name('jurnal.index');
    Route::get('/kelas/{kelas}/jurnal/create', [JurnalController::class, 'create'])
        ->name('jurnal.create');
    Route::post('/jurnal', [JurnalController::class, 'store'])
        ->name('jurnal.store');
    Route::get('/jurnal/{jurnal}/edit', [JurnalController::class, 'edit'])
        ->name('jurnal.edit');
    Route::put('/jurnal/{jurnal}', [JurnalController::class, 'update'])
        ->name('jurnal.update');
    Route::delete('/jurnal/{jurnal}', [JurnalController::class, 'destroy'])
        ->name('jurnal.destroy');
    Route::get('/jurnal/{jurnal}', [JurnalController::class, 'show'])
        ->name('jurnal.show');

    // PERWALIAN
    Route::get('/perwalian', [PerwalianController::class, 'index'])->name('perwalian.index');

    Route::get('/Activity/latest', [NilaiController::class, 'latestActivity']);

});

/*
|--------------------------------------------------------------------------
| ABOUT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/about/developer', [DashboardController::class, 'developer'])
    ->name('about.developer');

Route::get('/about', [DashboardController::class, 'about'])->name('about');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';