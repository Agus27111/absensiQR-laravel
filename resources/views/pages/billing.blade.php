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
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Status Langganan Sekolah</h3>
                        </div>
                        <div class="card-body">
                            @if ($sekolah)
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Nama Sekolah:</strong></label>
                                            <p>{{ $sekolah->nama_sekolah }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>NPSN:</strong></label>
                                            <p>{{ $sekolah->npsn ?? 'Belum diisi' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Status Langganan:</strong>
                                            @if ($sekolah->status_langganan === 'active')
                                                <span class="badge badge-success ml-2">Aktif</span>
                                                <p class="mt-2">Langganan Anda akan berakhir pada:
                                                    <strong>{{ $sekolah->subscription_until?->format('d-m-Y') ?? 'Tidak ditentukan' }}</strong>
                                                </p>
                                            @elseif ($sekolah->status_langganan === 'expired')
                                                <span class="badge badge-danger ml-2">Kadaluarsa</span>
                                                <p class="mt-2">Langganan Anda telah berakhir. Silakan perpanjang untuk
                                                    melanjutkan menggunakan layanan.</p>
                                            @else
                                                <span class="badge badge-warning ml-2">Tertunda</span>
                                                <p class="mt-2">Status langganan Anda sedang dalam proses. Hubungi tim
                                                    support untuk informasi lebih lanjut.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($sekolah->status_langganan !== 'active')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('billing.packages') }}" class="btn btn-primary">
                                                <i class="fas fa-shopping-cart"></i> Pilih Paket Langganan
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('billing.packages') }}" class="btn btn-info">
                                                <i class="fas fa-sync-alt"></i> Perpanjang Langganan
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    <strong>Peringatan:</strong> Data sekolah tidak ditemukan. Silakan hubungi
                                    administrator.
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
                            <h3 class="card-title text-white">Riwayat Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Fitur riwayat pembayaran akan segera tersedia.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
