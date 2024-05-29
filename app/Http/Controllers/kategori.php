<?php

namespace App\Http\Controllers;

use Dotenv\Exception\ValidationException;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

use App\Models\kategori as kategoriModel;
use App\Models\produk as produkModel;
use App\Models\cart as cartModel;
use App\Models\itemhastransaksi;

class kategori extends BaseController
{
    //
    public function ambilKategori($usaha_id)
    {
        $kategori = KategoriModel::where('usaha_id', $usaha_id)->get();
        if ($kategori->isEmpty()) {
            return response()->json([]);
        }
        return response()->json($kategori);
    }
    public function simpanKategori(Request $request, $usaha_id)
    {
        try {
            $validate = $request->validate([
                'nama' => 'required',
                // 'usaha_id' => 'required',
            ]);

            $validate['usaha_id'] = $usaha_id;

            $kategori = kategoriModel::create($validate);
            return response()->json(['status' => 200, "massage" => 'Berhasil Menambahkan Kategori', 'data' => $kategori]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 500, 'message' => 'Gagal Menambahkan Kategori', 'error' => $e]);
        }
    }
    public function hapusKategori($id, $usaha_id)
    {
        //cek apatkah kategori yang di ambil berdasarkan id_kat dan usaha_id
        $kategori = KategoriModel::where('id', $id)
            ->where('usaha_id', $usaha_id)
            ->first();
        //validasi kategori
        if (!$kategori) {
            //kembalikan respon
            return response()->json(['message' => 'Kategori tidak di temukan']);
        }

        //cek apakah memiliki item di dalamnya
        $produk = ProdukModel::where('usaha_id', $usaha_id)->where('kategori_id', $kategori->id)->first();
        if ($produk) {
            $items = itemhastransaksi::where('usaha_id', $usaha_id)
                ->where('produk_id', $produk->id)
                ->first();
            if ($items) {
                //kita looping peritem berdasarkan produk_id lalu kita hapus
                foreach ($items->where('produk_id', $produk->id)->get() as $item) {
                    $item->delete();
                }
            }
            //looping produk berdasarkan kategori id yang ingin kita hapus
            foreach ($produk->where('kategori_id', $kategori->id)->get() as $item) {
                $item->delete();
            }
            //hapus kategori
            $kategori->delete();
            return response()->json(['message' => 'Berhasil Menghapus Kategori dan Produk']);
        }

        $kategori->delete();
        // return response()->json(['produk' => $produk]);


        return response()->json([
            'status' => 200,
            'message' => 'Berhasil Menghapus Kategori',
        ]);
    }
}
