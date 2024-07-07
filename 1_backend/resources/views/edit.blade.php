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
    <title>Form Transaksi</title>
</head>

<body>
    <form action="/transaksi/edit/{{ $transaksi->id }}" method="POST" enctype="multipart/form-data"
        id="transaksi-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="data_barang" id="data-barang-input">
        <section class="create">
            <h2>EDIT TRANSAKSI</h2>
            <div>
                <p><b>Nomor Transaksi</b></p>
                <p>SO/{{ $date->format('Y') }}/{{ $date->format('m') }}/{{ $no_trx }}
                </p>
                <input type="hidden" name="nomor_transaksi"
                    value="SO/{{ $date->format('Y') }}/{{ $date->format('m') }}/{{ $no_trx }}">
            </div>
            <div>
                <p><b>Tanggal Transaksi</b></p>
                <input type="date" name="tanggal_transaksi" id="tanggal" class="form-control square-size"
                    value="{{ $transaksi->tanggal_transaksi }}">
            </div>
            <hr class="garis">
            <div>
                <p><b>Pilih Customer</b></p>
                <select name="customer" id="customer" class="form-control square-size">
                    <option value="tambah">Tambah Baru</option>
                    @foreach ($customer as $item)
                        <option value="{{ $item->id }}"
                            {{ $item->id == $transaksi->id_customer ? 'selected' : '' }}>{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div id="customer-data" style="display: none;">
                <p><b>Data Customer</b></p>
                <input type="text" name="nama" id="nama" class="form-control square-size" placeholder="Nama">
                <input type="text" name="alamat" id="alamat" class="form-control square-size"
                    placeholder="Alamat">
                <input type="text" name="phone" id="telepon" class="form-control square-size"
                    placeholder="Phone">
            </div>
            <hr class="garis">
            <div>
                <p><b>Pilih Barang</b></p>
                <select name="barang" id="barang" class="form-control square-size">
                    <option value="tambah">Tambah Baru</option>
                    @foreach ($barang as $item)
                        <option value="{{ $item['kd_barang'] }}">{{ $item['nama_barang'] }}</option>
                    @endforeach
                </select>
                <input type="number" name="qty" id="qty" class="form-control square-size" placeholder="Qty"
                    min="0">
                <input type="number" name="subtotal" id="subtotal" class="form-control square-size"
                    placeholder="Subtotal" min="0">
                <button type="button" class="btn square-size" id="tambah-barang">Tambah Barang</button>
            </div>
            <div>
                <p><b>Data Barang</b></p>
            </div>
            <section id="table-line">
                <table id="table-barang">
                    <thead>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->transaksi_d as $index => $barang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->qty }}</td>
                                <td>{{ $barang->subtotal }}</td>
                                <td><a type="button" class="delete-row delete-btn">Delete</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <div>
                <p><b>Total Transaksi : Rp <span id="total-transaksi">{{ $transaksi->total_transaksi }}</span></b></p>
                <input type="hidden" name="total_transaksi" id="total-transaksi-input"
                    value="{{ $transaksi->total_transaksi }}">
            </div>
            <div>
                <button type="submit" class="btn square-size">Simpan Transaksi</button>
            </div>
        </section>
    </form>
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var dataBarang = @json($transaksi->transaksi_d);

    document.getElementById('customer').addEventListener('change', function() {
        if (this.value == 'tambah') {
            document.getElementById('customer-data').style.display = 'block';
            document.getElementById('nama').required = true;
            document.getElementById('alamat').required = true;
            document.getElementById('telepon').required = true;
        } else {
            document.getElementById('customer-data').style.display = 'none';
        }
    });

    $(document).ready(function() {
        var rowCount = $('#table-barang tbody tr').length;

        $('#tambah-barang').click(function() {
            var barang = $('#barang').val();
            var namaBarang = $('#barang option:selected').text();
            var qty = $('#qty').val();
            var subtotal = $('#subtotal').val();

            if (barang && qty && subtotal) {
                rowCount++;

                $('#table-barang tbody').append(`
                    <tr>
                        <td>${rowCount}</td>
                        <td>${namaBarang}</td>
                        <td>${qty}</td>
                        <td>${subtotal}</td>
                        <td><a type="button" class="delete-row delete-btn">Delete</a></td>
                    </tr>
                `);

                $('#barang').val('tambah');
                $('#qty').val('');
                $('#subtotal').val('');

                updateTotal();

                dataBarang.push({
                    kd_barang: barang,
                    nama_barang: namaBarang,
                    qty: qty,
                    subtotal: subtotal
                });

                updateDataBarangInput();
            }
        });

        $(document).on('click', '.delete-row', function() {
            var row = $(this).closest('tr');
            var index = row.index();
            row.remove();
            dataBarang.splice(index, 1);
            updateRowNumbers();
            updateTotal();
            updateDataBarangInput();
        });

        function updateRowNumbers() {
            $('#table-barang tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        function updateTotal() {
            var total = 0;
            $('#table-barang tbody tr').each(function() {
                var subtotal = parseFloat($(this).find('td:eq(3)').text()) || 0;
                total += subtotal;
            });
            $('#total-transaksi').text(total);
            $('#total-transaksi-input').val(total);
        }

        function updateDataBarangInput() {
            $('#data-barang-input').val(JSON.stringify(dataBarang));
        }

        updateDataBarangInput();
    });
</script>
