@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Monitoring</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-school"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Sekolah</span>
                            <span class="info-box-number">{{ $totalSekolah }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sekolah Aktif</span>
                            <span class="info-box-number">{{ $sekolahAktif }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kadaluarsa</span>
                            <span class="info-box-number">{{ $sekolahKadaluarsa }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tertunda</span>
                            <span class="info-box-number">{{ $sekolahTertunda }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Siswa</span>
                            <span class="info-box-number">{{ $totalMurid }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-user-tie"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Admin Sekolah</span>
                            <span class="info-box-number">{{ $totalUser }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Sekolah Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Daftar Sekolah & Status Langganan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>No</th>
                                            <th>Nama Sekolah</th>
                                            <th>NPSN</th>
                                            <th>Admin</th>
                                            <th>Jumlah Siswa</th>
                                            <th>Status Langganan</th>
                                            <th>Berlaku Hingga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sekolahList as $key => $sekolah)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <strong>{{ $sekolah->nama_sekolah }}</strong>
                                                </td>
                                                <td>{{ $sekolah->npsn ?? '-' }}</td>
                                                <td>
                                                    @if ($sekolah->users->count() > 0)
                                                        @foreach ($sekolah->users as $u)
                                                            <span class="badge badge-info">{{ $u->username }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $sekolah->murids_count }}</span>
                                                </td>
                                                <td>
                                                    @if ($sekolah->status_langganan === 'active')
                                                        <span class="badge badge-success">Aktif</span>
                                                    @elseif($sekolah->status_langganan === 'expired')
                                                        <span class="badge badge-danger">Kadaluarsa</span>
                                                    @else
                                                        <span class="badge badge-warning">Tertunda</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($sekolah->subscription_until)
                                                        <small>{{ $sekolah->subscription_until->format('d-m-Y') }}</small>
                                                        @if ($sekolah->subscription_until->isBefore(now()))
                                                            <br><span
                                                                class="text-danger text-sm">{{ $sekolah->subscription_until->diffForHumans() }}</span>
                                                        @else
                                                            <br><span
                                                                class="text-success text-sm">{{ $sekolah->subscription_until->diffForHumans() }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <em>Belum ada data sekolah</em>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
