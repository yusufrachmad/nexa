<?php

namespace App\Http\Controllers;

use App\Models\Transaksi_h;
use App\Models\Ms_customer;
use App\Models\Transaksi_d;
use App\Models\Counter;
use App\Exports\TransaksiExport;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;

class TransaksiController extends Controller
{
    public function index(): View
    {
        $transaksi = Transaksi_h::all();
        $totalTransaksi = Transaksi_h::sum('total_transaksi');

        if (request('tanggal1') && request('tanggal2')) {
            $transaksi = Transaksi_h::dateFilter(request('tanggal1'), request('tanggal2'))->get();
            $totalTransaksi = Transaksi_h::dateFilter(request('tanggal1'), request('tanggal2'))->sum('total_transaksi');
        }

        return view('transaksi', ['transaksi' => $transaksi, 'totalTransaksi' => $totalTransaksi]);
    }

    public function create(): View
    {
        $barang = $this->getBarang()['response'];
        $customer = Ms_customer::all();
        $counter = new Counter();
        $tahun = date('Y');
        $bulan = date('m');
        $no_trx = $counter->getNoTrxPerMonth(date('m'), date('Y')) + 1;
        $formatted_no = str_pad($no_trx, 4, '0', STR_PAD_LEFT);

        return view('create', [
            'customer' => $customer, 'no_trx' => $formatted_no,
            'tahun' => $tahun, 'bulan' => $bulan, 'barang' => $barang
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // dd(json_decode($request->data_barang));
        $transaksi_h = new Transaksi_h();
        $transaksi_d = new Transaksi_d();
        $counter = new Counter();
        $no_trx = $counter->getNoTrxPerMonth(date('m'), date('Y')) + 1;

        if ($request->customer == "tambah") {
            $customer = new Ms_customer();
            $customer->nama = $request->nama;
            $customer->alamat = $request->alamat;
            $customer->phone = $request->phone;
            $customer->save();
            $transaksi_h->id_customer = $customer->id;
        } else {
            $transaksi_h->id_customer = $request->customer;
        }

        $transaksi_h->nomor_transaksi = $request->nomor_transaksi;
        $transaksi_h->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi_h->total_transaksi = $request->total_transaksi;
        $transaksi_h->save();

        $counter->updateOrCreate(
            ['bulan' => date('m'), 'tahun' => date('Y')],
            ['counter' => $no_trx]
        );

        foreach (json_decode($request->data_barang) as $value) {
            $transaksi_d = new Transaksi_d();
            $transaksi_d->id_transaksi_hs = $transaksi_h->id;
            $transaksi_d->kd_barang = $value->kd_barang;
            $transaksi_d->nama_barang = $value->nama_barang;
            $transaksi_d->qty = $value->qty;
            $transaksi_d->subtotal = $value->subtotal;
            $transaksi_d->save();
        }

        return redirect('/transaksi');
    }

    public function edit($id): View
    {
        $transaksi = Transaksi_h::with('customer', 'transaksi_d')->findOrFail($id);
        $counter = new Counter();
        $no_trx = $counter->getNoTrxPerMonth(date('m'), date('Y'));
        $no_trx = str_pad($no_trx, 4, '0', STR_PAD_LEFT);
        $date = new DateTime($transaksi->tanggal_transaksi);

        $barang = $this->getBarang()['response'];
        $customer = Ms_customer::all();

        return view('edit', [
            'transaksi' => $transaksi, 'customer' => $customer,
            'barang' => $barang, 'no_trx' => $no_trx, 'date' => $date
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $transaksi = Transaksi_h::find($id);
        $transaksi->id_customer = $request->customer;
        $transaksi->nomor_transaksi = $request->nomor_transaksi;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->total_transaksi = $request->total_transaksi;
        $transaksi->save();

        return redirect('/transaksi');
    }


    public function delete($id): RedirectResponse
    {
        $transaksi = Transaksi_h::findOrFail($id);
        $transaksi->delete();

        return redirect('/transaksi');
    }

    public function export()
    {
        return Excel::download(new TransaksiExport, 'transaksi.xlsx');
    }

    public function getBarang()
    {
        $client = new Client();
        $url = 'http://gmedia.bz/DemoCase/main/list_barang';
        $headers = [
            'Client-Service' => 'gmedia-recruitment',
            'Auth-Key' => 'demo-admin',
            'User-Id' => '1',
            'Token' => '8godoajVqNNOFz21npycK6iofUgFXl1kluEJt/WYFts9C8IZqUOf7rOXCe0m4f9B',
        ];

        $body = [
            'start' => 0,
            'count' => 10,
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'form_params' => $body,
            ]);
            $responseBody = $response->getBody()->getContents();
            $data = json_decode($responseBody, true);

            return $data;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
