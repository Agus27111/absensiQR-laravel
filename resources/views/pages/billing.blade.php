@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a></li>
    <li class="breadcrumb-item active">Pembayaran Langganan</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Status Langganan Sekolah</h3>
                        </div>
                        <div class="card-body">
                            @if ($sekolah)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Nama Sekolah:</strong> {{ $sekolah->nama_sekolah }}</p>
                                        <p><strong>Status:</strong>
                                            @if ($sekolah->subscription_until && now()->lt($sekolah->subscription_until))
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Expired / Non-Aktif</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p><strong>Berlaku Sampai:</strong> <br>
                                            <span
                                                class="text-primary h5">{{ $sekolah->subscription_until ? \Carbon\Carbon::parse($sekolah->subscription_until)->format('d M Y') : '-' }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="alert alert-light border">
                                    <h5>Isi Saldo / Perpanjang Masa Aktif</h5>
                                    <p>Perpanjang masa aktif sekolah selama <strong>30 Hari</strong> seharga <strong>Rp
                                            100.000</strong>.</p>
                                    <button id="pay-button" class="btn btn-primary shadow">
                                        <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title text-white">Riwayat Transaksi</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($riwayat as $item)
                                        <tr>
                                            <td>{{ $item->order_id }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                            <td>Rp {{ number_format($item->gross_amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($item->status == 'success')
                                                    <span class="badge badge-success">Berhasil</span>
                                                @elseif($item->status == 'pending')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">Belum ada riwayat
                                                transaksi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        const payButton = document.getElementById('pay-button');
        payButton.onclick = function() {
            // Animasi Loading sederhana
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghubungkan...';
            payButton.disabled = true;

            fetch('{{ route('billing.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    payButton.innerHTML = '<i class="fas fa-credit-card mr-2"></i> Bayar Sekarang';
                    payButton.disabled = false;

                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.reload();
                            },
                            onPending: function(result) {
                                window.location.reload();
                            },
                            onError: function(result) {
                                alert("Gagal!");
                            }
                        });
                    } else {
                        alert('Gagal mengambil token pembayaran');
                    }
                })
                .catch(err => {
                    alert('Terjadi kesalahan sistem');
                    payButton.disabled = false;
                });
        };
    </script>
@endsection
