<?php

namespace App\Exports;

use App\Models\Transaksi_h;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Transaksi_h::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Transaksi',
            'Customer',
            'Total Transaksi'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        $totalTx = Transaksi_h::sum('total_transaksi');

        $data = [];
        $rowData = [
            $no,
            $row->nomor_transaksi,
            $row->customer->nama,
            $row->total_transaksi
        ];
        array_push($data, $rowData);

        if ($no === count(Transaksi_h::all())) {
            $totalTxData = [
                '',
                '',
                'Total Transaksi',
                $totalTx
            ];
            array_push($data, $totalTxData);
        }

        return $data;
    }
}
