<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\produk as produkModel;
use Dotenv\Exception\ValidationException;

use Illuminate\Support\Facades\Storage;

class produk extends BaseController
{
    //
    public function ambilSemuaProduk($usaha_id)
    {
        $produk = produkModel::where('usaha_id', $usaha_id)->with('kategori')->get();
        if ($produk->isEmpty()) {
            return response()->json([]);
        }
        return response()->json($produk);
    }
    public function ambilKategoriProduk($usaha_id, $kategori_id)
    {
        $produk = produkModel::where('usaha_id', $usaha_id)->where('kategori_id', $kategori_id)->get();
        if ($produk->isEmpty()) {

            return response()->json(['message' => 'id_usaha/id_kategori tidak di temukan']);
        }
        return response()->json($produk);
    }
    public function simpanProduk(Request $request, $usaha_id)
    {
        try {
            $validate = $request->validate([
                'nama' => 'required|max:12',
                'harga' => 'required|',
                'qty' => 'required|',
                'kategori_id' => 'required|',
            ]);

            $usaha_id = (int) $usaha_id;
            // $validate['gambar'] = null;
            $image = null;
            // $request->gambar = null;
            if ($request->gambar) {
                $gambar = $this->random_string();
                $extension = $request->gambar->extension();
                $imageName = $gambar . '.' . $extension;
                Storage::putFileAs('public/image/' . $usaha_id, $request->gambar, $imageName);

                // Generate the URL to the stored image
                $image = Storage::url('public/image/' . $usaha_id . '/' . $imageName);
            }

            $validate['gambar'] = $image;
            $validate['usaha_id'] = $usaha_id;

            $produk = produkModel::create($validate);


            return response()->json(['status' => 200, "massage" => 'Berhasil Menambahkan Produk', 'data' => $produk->with(['kategori'])->where('usaha_id', $usaha_id)->get()]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Gagal Menambahkan Kategori', 'error' => $e]);
        }
    }

    public function hapusProduk($id, $usaha_id)
    {
        $produk = produkModel::where('id', $id)
            ->where('usaha_id', $usaha_id)
            ->firstOrFail();

        // $produk->delete();

        // Set the qty field to zero
        $produk->qty = 0;

        // Save the changes to the database
        $produk->save();

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil Menghapus Produk Terkait',
            'data' => [
                'produk' => $produk,
            ]
        ]);
    }

    public function updateProduk(Request $request, $id, $usaha_id)
    {
        try {
            // Temukan produk yang ingin diperbarui
            $produk = ProdukModel::where('id', $id)
                ->where('usaha_id', $usaha_id)
                ->firstOrFail();

            $validatedData = $request->validate([
                'nama' => 'required|max:255',
                'harga' => 'required|numeric|min:0',
                // 'gambar' => 'required|image', // Misalnya: hanya menerima file gambar
                'qty' => 'required|integer', // Misalnya: hanya menerima bilangan bulat
                'kategori_id' => 'required',
                // Tambahkan aturan validasi lainnya sesuai kebutuhan Anda
            ]);

            // Perbarui atribut produk berdasarkan data yang diterima
            $produk->nama = $validatedData['nama'];
            $produk->harga = $validatedData['harga'];
            // $produk->gambar = $validatedData['gambar'];
            $produk->qty = $validatedData['qty'];
            $produk->kategori_id = $validatedData['kategori_id'];

            // Simpan perubahan pada produk
            $produk->save();

            return response()->json([
                'status' => 200,
                'message' => 'Produk berhasil diperbarui',
                'data' => $produk
            ]);
        } catch (\Throwable $th) {
            // Tangani jika terjadi kesalahan
            return response()->json([
                'status' => 500,
                'message' => 'Gagal memperbarui produk',
                'error' => $th->getMessage()
            ]);
        }
    }

    function random_string($length = 20)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
