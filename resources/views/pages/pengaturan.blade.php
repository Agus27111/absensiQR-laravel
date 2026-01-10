@extends('layouts/main')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#umum" data-toggle="tab">Umum</a></li>
                        <li class="nav-item"><a class="nav-link" href="#kartu" data-toggle="tab">Download Kartu</a></li>
                        <li class="nav-item"><a class="nav-link text-primary" href="#billing" data-toggle="tab"><b>Billing &
                                    Pembayaran</b></a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <div class="active tab-pane" id="umum">
                            <form method="post" action="/pengaturan" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Sekolah</label>
                                            <input type="text" name="nama_sekolah" class="form-control"
                                                value="{{ $data->nama_sekolah }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Jam Masuk</label>
                                            <input type="time" name="jam_masuk" class="form-control"
                                                value="{{ $data->jam_masuk }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <label>Logo Sekolah</label><br>
                                        <img src="{{ asset('storage/' . $data->logo) }}" width="100"
                                            class="img-thumbnail mb-2">
                                        <input type="file" name="logo" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                            </form>
                        </div>

                        <div class="tab-pane" id="kartu">
                            <h5>Download Kartu Siswa Per-Kelas</h5>
                            <p class="text-muted">Pilih kelas untuk mendownload seluruh kartu QR siswa dalam format PDF.</p>
                            <div class="list-group">
                                @if ($sekolah && $sekolah->kelas->count() > 0)
                                    @foreach ($sekolah->kelas as $k)
                                        <a href="/download-kartu-massal/{{ $k->id }}"
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            Kelas: {{ $k->kelas }}
                                            <span class="badge badge-primary badge-pill"><i class="fas fa-download"></i>
                                                PDF</span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="text-center py-3">
                                        <p class="text-muted">Belum ada data kelas. Silakan tambah kelas terlebih dahulu.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="tab-pane" id="billing">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Status Langganan</h5>
                                    <div
                                        class="alert @if ($sekolah->status_langganan == 'active') alert-success @else alert-danger @endif">
                                        Status: <strong>{{ strtoupper($sekolah->status_langganan) }}</strong><br>
                                        Berlaku hingga:
                                        <strong>{{ $sekolah->subscription_until ? \Carbon\Carbon::parse($sekolah->subscription_until)->format('d M Y') : '-' }}</strong>
                                    </div>
                                    <button id="pay-button" class="btn btn-success btn-lg shadow">
                                        <i class="fas fa-shopping-cart mr-2"></i> Perpanjang 30 Hari (Rp 100.000)
                                    </button>
                                </div>

                                <div class="col-md-6">
                                    <h5>Riwayat Transaksi Terakhir</h5>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($riwayat as $r)
                                                <tr>
                                                    <td>{{ $r->order_id }}</td>
                                                    <td><span
                                                            class="badge @if ($r->status == 'success') badge-success @else badge-warning @endif">{{ $r->status }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function() {
            fetch('{{ route('billing.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.reload();
                            },
                            onPending: function(result) {
                                window.location.reload();
                            }
                        });
                    }
                });
        };
    </script>
@endsection
