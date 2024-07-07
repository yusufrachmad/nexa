<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Penjualan</title>
</head>

<body>
    <section>
        <h2>TRANSAKSI PENJUALAN</h2>
        <h4 style="margin-top: 3rem">Filter Tanggal Transaksi</h4>
        <form>
            <div id="filter-line">
                <div class="datepicker">
                    <input type="date" name="tanggal1" id="tanggal" class="form-control square-size" required>
                    <p class="square-size">sd</p>
                    <input type="date" name="tanggal2" id="tanggal" class="form-control square-size" required>
                    <div class="button-div">
                        <button type="submit" class="btn square-size">Filter</button>
                    </div>
                </div>
                <div class="transaksi-btn">
                    <a href="/transaksi/create" class="btn square-size">Tambah Transaksi</a>
                </div>
            </div>
        </form>
        <div id="search-line">
            <input type="text" name="search" id="search" class="form-control square-size" onkeyup="filterTable()"
                autocomplete="off" placeholder="Search...">
            <a href="/transaksi/export" class="btn square-size">Export Excel</a>
        </div>
        <div id="table-line">
            <table id="tx-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Transaksi</th>
                        <th>Customer</th>
                        <th>Total Transaksi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $value['nomor_transaksi'] }}</td>
                            <td>{{ $value->customer['nama'] }}</td>
                            <td style="text-align: right">{{ $value['total_transaksi'] }}</td>
                            <td>
                                <a href="/transaksi/edit/{{ $value['id'] }}" class="edit-btn">Edit</a>
                                |
                                <form action="/transaksi/{{ $value['id'] }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Apakah anda akan menghapus data ini?')"
                                        class="delete-btn">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td style="text-align: right">{{ $totalTransaksi }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</body>

</html>

<script>
    function filterTable() {
        var input, filter, table, tr, td, i, j, txtValue, footer;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("tx-table");
        tr = table.getElementsByTagName("tr");
        tfoot = table.getElementsByTagName("tfoot");
        footer = table.getElementsByTagName("tfoot")[0];

        if (filter === "") {
            footer.style.display = "";
        } else {
            footer.style.display = "none";
        }

        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < 4; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    }
                }
            }
        }
    }
</script>
