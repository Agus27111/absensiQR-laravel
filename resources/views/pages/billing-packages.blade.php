@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a></li>
    <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Pembayaran Langganan</a></li>
    <li class="breadcrumb-item active">Pilih Paket</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mb-4">Pilih Paket Langganan</h2>
                </div>
            </div>

            <div class="row">
                <!-- Paket Basic -->
                <div class="col-md-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header bg-primary">
                            <h5 class="card-title text-white text-center">Paket Basic</h5>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-primary mb-3">Rp 50.000</h3>
                            <p class="text-muted mb-4">Per Bulan</p>

                            <ul class="list-unstyled mb-4 text-left">
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Unlimited Siswa</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Fitur Absensi Dasar</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Laporan Absensi</li>
                                <li class="mb-2"><i class="fas fa-times text-danger"></i> QR Code Dinamis</li>
                                <li class="mb-2"><i class="fas fa-times text-danger"></i> Support Priority</li>
                            </ul>

                            <form action="{{ route('billing.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="package" value="basic">
                                <input type="hidden" name="price" value="50000">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Pilih Paket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Paket Professional -->
                <div class="col-md-4">
                    <div class="card card-outline card-success">
                        <div class="card-header bg-success">
                            <h5 class="card-title text-white text-center">Paket Professional</h5>
                            <span class="badge badge-warning position-absolute"
                                style="top: 10px; right: 10px;">POPULAR</span>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-success mb-3">Rp 100.000</h3>
                            <p class="text-muted mb-4">Per Bulan</p>

                            <ul class="list-unstyled mb-4 text-left">
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Unlimited Siswa</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Fitur Absensi Lengkap</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Laporan Lanjutan</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> QR Code Dinamis</li>
                                <li class="mb-2"><i class="fas fa-times text-danger"></i> Support Priority</li>
                            </ul>

                            <form action="{{ route('billing.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="package" value="professional">
                                <input type="hidden" name="price" value="100000">
                                <button type="submit" class="btn btn-success btn-block">
                                    Pilih Paket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Paket Enterprise -->
                <div class="col-md-4">
                    <div class="card card-outline card-danger">
                        <div class="card-header bg-danger">
                            <h5 class="card-title text-white text-center">Paket Enterprise</h5>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-danger mb-3">Rp 200.000</h3>
                            <p class="text-muted mb-4">Per Bulan</p>

                            <ul class="list-unstyled mb-4 text-left">
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Unlimited Siswa</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Fitur Absensi Lengkap</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Laporan Lanjutan</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> QR Code Dinamis</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Support Priority 24/7</li>
                            </ul>

                            <form action="{{ route('billing.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="package" value="enterprise">
                                <input type="hidden" name="price" value="200000">
                                <button type="submit" class="btn btn-danger btn-block">
                                    Pilih Paket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h5 class="card-title text-white">Perbandingan Fitur</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Fitur</th>
                                            <th class="text-center">Basic</th>
                                            <th class="text-center">Professional</th>
                                            <th class="text-center">Enterprise</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Unlimited Siswa</td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Fitur Absensi</td>
                                            <td class="text-center">Dasar</td>
                                            <td class="text-center">Lengkap</td>
                                            <td class="text-center">Lengkap</td>
                                        </tr>
                                        <tr>
                                            <td>Laporan</td>
                                            <td class="text-center">Standar</td>
                                            <td class="text-center">Lanjutan</td>
                                            <td class="text-center">Lanjutan</td>
                                        </tr>
                                        <tr>
                                            <td>QR Code Dinamis</td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Support</td>
                                            <td class="text-center">Email</td>
                                            <td class="text-center">Email + Chat</td>
                                            <td class="text-center">24/7 Priority</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <a href="{{ route('billing.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
