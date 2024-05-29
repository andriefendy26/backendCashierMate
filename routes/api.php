<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegistController;
use App\Http\Controllers\kategori as KategoriController;
use App\Http\Controllers\produk as ProdukController;
use App\Http\Controllers\roles as RolesController;
use App\Http\Controllers\transaksi as TransaksiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//route authentifikasi
Route::get('/usaha', [RegistController::class, 'GetUsaha'])->middleware(['auth:sanctum']);
Route::get('/users', [RegistController::class, 'GetUsers'])->middleware(['auth:sanctum']);
Route::post('/registrasi', [RegistController::class, 'RegistUsaha']);
Route::post('/login', [RegistController::class, 'LoginUser']);
Route::get('/logout', [RegistController::class, 'LogoutUser'])->middleware(['auth:sanctum']);
Route::delete('/deleteuser/{user_id}/{usaha_id}', [RegistController::class, 'deleteUser']);


Route::post('/tambahpegawai/{usaha_id}', [RegistController::class, 'tambahPegawai']);
Route::get('/ambilpegawai/{usaha_id}', [RegistController::class, 'ambilPegawai']);

Route::get('/users/{email}', [RegistController::class, 'GetUsersByEmail']);


Route::post('/roles', [RolesController::class, 'RegistRoles']);
Route::get('/roles', [RolesController::class, 'GetRoles']);


//KATEGORII
Route::get('/kategori/ambil/{id}', [KategoriController::class, 'ambilKategori']);
Route::post('/kategori/simpan/{id_usaha}', [KategoriController::class, 'simpanKategori']);
Route::delete('/kategori/hapus/{kategori_id}/{usaha_id}', [KategoriController::class, 'hapusKategori']);

//PRODUK 
Route::get('/produk/ambil/{id}', [ProdukController::class, 'ambilSemuaProduk']);
Route::get('/produk/ambil/kategori/{id_kategori}/{id_usaha}', [ProdukController::class, 'ambilKategoriProduk']);
Route::post('/produk/simpan/{id_usaha}', [ProdukController::class, 'simpanProduk']);
Route::delete('/produk/hapus/{id}/{usaha_id}', [ProdukController::class, 'hapusProduk']);
Route::put('/produk/update/{id}/{usaha_id}', [ProdukController::class, 'updateProduk']);


//TRANSAKSI
Route::get('/cart/{usaha_id}', [TransaksiController::class, 'lihatCart']);
Route::delete('/cart/{usaha_id}/{id}', [TransaksiController::class, 'hapusCart']);
Route::post('/cart', [TransaksiController::class, 'buatCart']);

Route::get('/transaksi/ambilitem/{usaha_id}/{cart_id}', [TransaksiController::class, 'ambilItem']);
Route::post('/transaksi/tambahitem/{usaha_id}', [TransaksiController::class, 'tambahItem']);
Route::delete('/transaksi/hapusitem/{item_id}/{usaha_id}/{cart_id}', [TransaksiController::class, 'hapusItem']);
Route::put('/transaksi/updateitem/{item_id}/{usaha_id}/{cart_id}', [TransaksiController::class, 'updateItem']);


Route::get('/transaksi/ambil/{usaha}', [TransaksiController::class, 'ambilSemuaTransaksi']);
Route::get('/transaksi/ambildetail/{usaha}/{trans_id}', [TransaksiController::class, 'ambilDetailTransaksi']);
Route::post('/transaksi/lanjutan/{user}/{usaha}/{cart}', [TransaksiController::class, 'transaksiLanjutan']);

Route::get('/transaksi/detailLaporan/{usaha}', [TransaksiController::class, 'detailLaporan']);




Route::put('/updateuser/{user_id}', [RegistController::class, 'UpdateUser']);