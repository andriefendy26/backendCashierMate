<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\itemhastransaksi as itemhastransaksi;
use App\Models\transaksi as transaksiModel;
use App\Models\produk as produkModel;
use App\Models\usaha as usahaModel;
use App\Models\cart as cart;
use App\Models\users as userModel;
use Carbon\Carbon;

class transaksi extends BaseController
{

    public function buatCart(Request $request)
    {
        $req = $request->validate([
            'usaha_id' => 'required',
        ]);
        $usaha = usahaModel::find($req['usaha_id']);
        if (!$usaha) {
            return response()->json(['status' => 'Usaha tidak di temukan'], 422);
        }
        $cart = cart::create($req);
        return response()->json(['status' => 200, 'data' => $cart]);
    }



    public function lihatCart($usaha_id)
    {
        $cart = cart::where('usaha_id', $usaha_id)->get();

        if ($cart->isEmpty()) {
            return response()->json(['message' => 'tidak ditemukan']);
        }
        return response()->json(['status' => 200, 'data' => $cart]);
    }
    public function hapusCart($usaha_id, $id)
    {
        $cart = cart::where('id', $id)->where('usaha_id', $usaha_id)->first();
        if (!$cart) {
            return response()->json(['status' => 'tidak ditemukan']);
        }

        // Temukan semua item yang terkait dengan cart yang akan dihapus
        $items = itemhastransaksi::where('usaha_id', $usaha_id)
            ->where('cart_id', $cart->id)
            ->get();

        // Hapus semua item yang ditemukan
        foreach ($items as $item) {
            $item->delete();
        }

        $cart->delete();
        return response()->json(['status' => 200, 'message' => 'berhasil di hapus']);
    }

    public function ambilItem($usaha_id, $cart_id)
    {
        $cart = cart::where('id', $cart_id)->where('usaha_id', $usaha_id)->first();
        if (!$cart) {
            return response()->json(['message' => 'cart tidak di temukan']);
        }
        $item = itemhastransaksi::where('usaha_id', $usaha_id)->where('cart_id', $cart_id)->get()->load(['produk']);
        if ($item->isEmpty()) {
            return response()->json(['message' => 'item di dalam cart masih kosong']);
        }

        return response()->json(['status' => 'success', 'data' => [$item]]);

    }

    public function tambahItem(Request $request, $usaha_id)
    {
        $req = $request->validate([
            'qty' => 'required',
            'produk_id' => 'required',
            'cart_id' => 'required',
        ]);

        // Temukan cart berdasarkan cart_id dan usaha_id
        $cart = Cart::where('id', $req['cart_id'])->where('usaha_id', $usaha_id)->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart tidak ditemukan'], 404);
        }

        // Temukan atau buat produk berdasarkan produk_id dan usaha_id
        $produk = ProdukModel::where('id', $req['produk_id'])->where('usaha_id', $usaha_id)->first();

        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        // Hitung total harga
        $totalHarga = $produk->harga * $req['qty'];

        // Buat data untuk disimpan
        $dataToStore = [
            'qty' => $req['qty'],
            'total' => $totalHarga,
            'produk_id' => $produk->id,
            'usaha_id' => $cart->usaha_id,
            'cart_id' => $cart->id,
        ];

        // Simpan data ke dalam tabel itemhastransaksi
        $item = ItemHasTransaksi::create($dataToStore);

        return response()->json(['status' => 200, 'data' => $item]);
    }



    public function hapusItem($id, $usaha_id, $cart_id)
    {

        $item = itemhastransaksi::where('id', $id)
            ->where('usaha_id', $usaha_id)
            ->where('cart_id', $cart_id)
            ->firstOrFail();

        $item->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil Menghapus Item Terkait',
            'data' => [
                'produk' => $item,
            ]
        ]);
    }
    public function updateItem(Request $request, $id, $usaha_id, $cart_id)
    {
        // Temukan item transaksi yang akan diupdate
        $item = itemhastransaksi::where('id', $id)
            ->where('usaha_id', $usaha_id)
            ->where('cart_id', $cart_id)
            ->firstOrFail();

        // Validasi input
        $validatedData = $request->validate([
            'qty' => 'required|integer', // Kuantitas harus bilangan bulat
        ]);

        // Temukan atau buat usaha berdasarkan usaha_id
        $usaha = usahaModel::find($usaha_id);

        // Pastikan usaha ditemukan
        if (!$usaha) {
            return response()->json(['error' => 'Usaha tidak ditemukan'], 404);
        }

        // Temukan atau buat produk berdasarkan produk_id dari item transaksi
        $produk = produkModel::find($item->produk_id);
        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        // Hitung total harga baru berdasarkan harga produk dan kuantitas yang diupdate
        $totalHarga = $produk->harga * $validatedData['qty'];

        // Perbarui kuantitas dan total harga pada item transaksi
        $item->qty = $validatedData['qty'];
        $item->total = $totalHarga;
        $item->save();

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil mengupdate item transaksi',
            'data' => $item,
        ]);
    }

    public function transaksiLanjutan(Request $request, $id_users, $usaha_id, $cart_id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'metode' => 'required',
            'bayar' => 'required',
        ]);

        //cek userid
        $user = userModel::where('usaha_id', $usaha_id)->where('id', $id_users)->first();
        if (!$user) {
            return response()->json(['message' => "user blm terdaftar di tokoh ini"]);
        }
        //cek keranjang
        $cart = cart::where('usaha_id', $usaha_id)->where('id', $cart_id)->first();
        if (!$cart) {
            return response()->json(['message' => "cart tidak ada"]);
        }

        $items = itemhastransaksi::where('cart_id', $cart_id)->where('usaha_id', $usaha_id)->with('produk')->get();
        //menjumlahkan total barang
        $total = $items->sum('total');
        //validasi jika uang  bayar kurang dari total belanja
        if ($validatedData['bayar'] < $total) {
            return response()->json(['message' => 'Uang di terima kurang']);
        }

        $kembalian = $validatedData['bayar'] - $total;
        // // Menambahkan tanggal saat ini
        $tanggal = Carbon::now()->toDateTimeString();

        // $jam = Carbon::now()->format('H:i:s');

        // $tanggalWithTime = $tanggal . ' ' . $jam;

        $transaksi = transaksiModel::create([
            'metode' => $validatedData['metode'],
            'bayar' => $validatedData['bayar'],
            'total' => $total,
            'kembalian' => $kembalian,
            'tanggal' => $tanggal,
            'users_id' => $user->id,
            'usaha_id' => $user->usaha_id,
            'cart_id' => $cart->id
        ]);

        return response()->json([
            'message' => "Transaksi berhasil disimpan",
            'data' => [
                'items' => $items,
                'transaksi' => $transaksi,
                'kasir' => $user
            ]
        ], 200);
    }

    public function ambilSemuaTransaksi($usaha_id)
    {
        $transaksi = transaksiModel::where('usaha_id', $usaha_id)->with(['user'])->get()->all();

        // $item = itemhastransaksi::where('cart_id', $transaksi["cart_id"])->with(['produk'])->get();

        return response()->json(['data' => $transaksi]);
    }


    public function ambilDetailTransaksi($usaha_id, $trans_id)
    {
        $detail = transaksiModel::where('id', $trans_id)->where('usaha_id', $usaha_id)->first();

        //ambil user
        $user = userModel::where('id', $detail->users_id)->value('nama');

        //ambil item berdasarkan cart_id
        $item = itemhastransaksi::where('cart_id', $detail->cart_id)->with(['produk'])->get();

        $sum = $item->sum('qty');
        return response()->json([
            'data' => [
                'detail' => $detail, 
                'item' => $item, 
                'kasir' => $user,
                'total' => $sum
                ]
            ]);
    }

    public function detailLaporan($usaha_id)
    {
        $pendapatan = transaksiModel::where('usaha_id', $usaha_id)->get();
        $produk = produkModel::where('usaha_id', $usaha_id)->get();
        $item = itemhastransaksi::where('usaha_id', $usaha_id)->get();

        $total = $pendapatan->sum('total');
        $stock = $produk->sum('qty');
        $produkTerjual = $item->sum('qty');
        $jumlhtrans = $pendapatan->count();

        return response()->json([
            'status' => 200,
            'data' => [
                'totalpendapatan' => $total,
                'jumlahtransaksi' => $jumlhtrans,
                'stock' => $stock,
                'produkterjual' => $produkTerjual
            ]
        ]);
    }

}