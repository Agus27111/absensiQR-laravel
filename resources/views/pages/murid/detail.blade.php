@extends('layouts/main')

<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Murid / <a href="/daftar-murid">Daftar Murid</a> / Detail</li>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    @if (session()->has('fail'))
                        Gagal!
                    @endif
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ $murid->photo ? asset('storage/' . $murid->photo) : '/img/user4-128x128.jpg' }}"
                                    alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ $murid->nama }}</h3>

                            <p class="text-center">{{ $murid->kelas->kelas }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>NIS</b> <a class="float-right">{{ $murid->nis }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Tahun</b> <a class="float-right">{{ $murid->tahun->tahun }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Kehadiran</b> <a class="float-right">98%</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Di Buat</b> <a class="float-right">{{ date('d-m-Y', strtotime($murid->created_at)) }}
                                        | {{ date('H:m:s', strtotime($murid->created_at)) }} WIB</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Di Perbarui</b> <a
                                        class="float-right">{{ date('d-m-Y', strtotime($murid->updated_at)) }} |
                                        {{ date('H:m:s', strtotime($murid->updated_at)) }} WIB</a>
                                </li>
                            </ul>
                            <a href="#" class="btn btn-warning btn-block" data-toggle="modal"
                                data-target="#modalQr"><b>Lihat Kartu</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Profile</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                            <p class="text-muted">{{ $murid->alamat ?? 'Alamat belum diisi' }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity"
                                        data-toggle="tab">Absensi</a></li>
                                <li class="nav-item"><a class="nav-link" href="#profile" data-toggle="tab">Profil</a></li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Manage</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="startDate">Tanggal Awal</label>
                                                    <input type="date" name="startDate" id="startDate"
                                                        class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="endDate">Tanggal Akhir</label>
                                                    <input type="date" name="endDate" id="endDate" class="form-control"
                                                        required>
                                                </div>
                                                <button id="searchButton" class="btn btn-primary mb-4">Cari</button>
                                            </div>
                                            <div class="col-md-8">
                                                <div id="searchResult"></div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">30 absensi terakhir untuk murid : <b>
                                                        {{ $murid->nama }}</b></h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <table id="example2" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Hari</th>
                                                            <th>Tanggal</th>
                                                            <th>Bulan</th>
                                                            <th>Jam Absen</th>
                                                            <th class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($absensi as $a)
                                                            <tr>
                                                                @if ($a->status == '0')
                                                                    <!-- Tidak Masuk -->
                                                                    <td class="table-danger">{{ $a->hari }}</td>
                                                                    <td class="table-danger">{{ $a->tanggal }}</td>
                                                                    <td class="table-danger">{{ $a->bulan }}</td>
                                                                    <td class="table-danger">{{ $a->jam_absen }} WIB</td>
                                                                    <td class="text-center table-danger"><img
                                                                            src="/img/fail.png" width="20px"
                                                                            height="20px"></td>
                                                                @elseif($a->status == '1')
                                                                    <!-- Masuk -->
                                                                    <td class="table-success">{{ $a->hari }}</td>
                                                                    <td class="table-success">{{ $a->tanggal }}</td>
                                                                    <td class="table-success">{{ $a->bulan }}</td>
                                                                    <td class="table-success">{{ $a->jam_absen }} WIB</td>
                                                                    <td class="text-center table-success"><img
                                                                            src="/img/success.png" width="20px"
                                                                            height="20px"></td>
                                                                @elseif($a->status == '2')
                                                                    <!-- Terlambat -->
                                                                    <td class="table-warning">{{ $a->hari }}</td>
                                                                    <td class="table-warning">{{ $a->tanggal }}</td>
                                                                    <td class="table-warning">{{ $a->bulan }}</td>
                                                                    <td class="table-warning">{{ $a->jam_absen }} WIB</td>
                                                                    <td class="text-center table-warning">Terlambat</td>
                                                                @elseif($a->status == '3')
                                                                    <!-- Izin -->
                                                                    <td class="table-secondary">{{ $a->hari }}</td>
                                                                    <td class="table-secondary">{{ $a->tanggal }}</td>
                                                                    <td class="table-secondary">{{ $a->bulan }}</td>
                                                                    <td class="table-secondary">{{ $a->jam_absen }} WIB
                                                                    </td>
                                                                    <td class="text-center table-secondary">Izin</td>
                                                                @elseif($a->status == '4')
                                                                    <!-- Izin -->
                                                                    <td class="table-light">{{ $a->hari }}</td>
                                                                    <td class="table-light">{{ $a->tanggal }}</td>
                                                                    <td class="table-light">{{ $a->bulan }}</td>
                                                                    <td class="table-light">{{ $a->jam_absen }} WIB</td>
                                                                    <td class="text-center table-light">Hari Libur</td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Hari</th>
                                                            <th>Tanggal</th>
                                                            <th>Bulan</th>
                                                            <th>Jam Absen</th>
                                                            <th class="text-center">Status</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="profile">
                                    {{-- <form class="form-horizontal" action="/detail-murid/update/{{ $murid->id }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <div class="form-group row">
                                            <label for="inputNis" class="col-sm-2 col-form-label">NIS</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $murid->nis }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="nama"
                                                    value="{{ $murid->nama }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputAlamat" class="col-sm-2 col-form-label">Alamat</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="alamat" placeholder="Alamat Lengkap">{{ $murid->alamat }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputKelas" class="col-sm-2 col-form-label">Kelas</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="kelas_id">
                                                    @foreach ($kelas_all as $k)
                                                        <option value="{{ $k->id }}"
                                                            {{ $k->id == $murid->kelas_id ? 'selected' : '' }}>
                                                            {{ $k->kelas }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputPhoto" class="col-sm-2 col-form-label">Foto Murid</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="photo"
                                                    accept="image/*">
                                                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form> --}}
                                    <div class="tab-pane" id="profile">
                                        <form class="form-horizontal" action="/detail-murid/update/{{ $murid->id }}"
                                            method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="nama"
                                                        value="{{ $murid->nama }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Alamat</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" name="alamat" rows="3">{{ $murid->alamat }}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Kelas</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" name="kelas_id" required>
                                                        @foreach ($kelas_all as $k)
                                                            <option value="{{ $k->id }}"
                                                                {{ $k->id == $murid->kelas_id ? 'selected' : '' }}>
                                                                {{ $k->kelas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Foto Murid</label>
                                                <div class="col-sm-10">
                                                    <input type="file" class="form-control" name="photo"
                                                        accept="image/*">
                                                    @if ($murid->photo)
                                                        <small class="text-info">Sudah ada foto. Upload lagi untuk
                                                            mengganti.</small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-warning">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">
                                    <div class="form-group row">
                                        <label for="inputNis" class="col-sm-2 col-form-label">Hapus Murid ?</label>
                                        <div class="col-sm-10">
                                            <button class="btn btn-danger" data-toggle="modal"
                                                data-target="#modalLoginForm">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Pop Up Form Input -->
    <div class="modal fade" id="modalQr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content col-md-9">
                <style>
                    @page {
                        margin: 0;
                    }

                    body {
                        margin: 0;
                        font-family: 'Helvetica', 'Arial', sans-serif;
                        background-color: #ffffff;
                    }

                    .card-container {
                        width: 320px;
                        height: 530px;
                        position: relative;
                        overflow: hidden;
                        border: 1px solid #eee;
                    }

                    /* Sidebar Biru Gelap sesuai gambar */
                    .qr-sidebar {
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 60px;
                        background-color: #111a36;
                        color: #ffb800;
                        text-align: center;
                    }


                    /* Teks Vertikal di Sidebar */
                    /* Teks Vertikal di Sidebar */
                    .sidebar-text {
                        position: absolute;
                        width: 400px;
                        height: 60px;
                        left: -170px;
                        /* Geser ke kiri untuk menyeimbangkan rotasi */
                        bottom: 180px;

                        /* Rotasi standar DomPDF */
                        transform: rotate(-90deg);

                        text-align: left;
                        color: #ffb800;
                        font-weight: bold;
                        font-size: 18px;
                        white-space: nowrap;
                    }


                    /* Garis Kuning di Sidebar */
                    .sidebar-line {
                        position: absolute;
                        left: 30px;
                        top: 0;
                        bottom: 230px;
                        width: 2px;
                        background-color: #ffb800;
                    }

                    /* Konten Utama */
                    .main-content {
                        margin-left: 60px;
                        padding: 20px;
                    }

                    .header-logo {
                        width: 100%;
                        margin-bottom: 20px;
                    }

                    .header-logo table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    .school-name {
                        font-size: 14px;
                        font-weight: bold;
                        color: #111a36;
                        line-height: 1.2;
                    }

                    .school-loc {
                        font-size: 11px;
                        color: #333;
                    }

                    /* Lingkaran Foto Murid */
                    .photo-profile {
                        text-align: center;
                        margin: 10px 0;
                    }

                    .photo-profile img {
                        width: 110px;
                        height: 110px;
                        border-radius: 50%;
                        border: 3px solid #ffb800;
                        object-fit: cover;
                    }

                    .student-name {
                        text-align: center;
                        font-size: 16px;
                        font-weight: 900;
                        color: #111a36;
                        margin: 10px 0;
                        text-transform: uppercase;
                        min-height: 40px;
                    }

                    /* Kotak QR Code dengan Border Kuning */
                    .qr-box {
                        width: 80px;
                        /* Atur lebar kotak QR sesuai keinginan */
                        height: 80px;
                        /* Atur tinggi kotak QR sesuai keinginan */
                        margin: 10px auto;
                        background: white;
                        padding: 5px;
                        /* Memberi sedikit jarak putih di pinggir QR */
                        border: 1px solid #ddd;
                    }

                    .qr-box svg,
                    .qr-box img {
                        width: 100% !important;
                        /* Memaksa QR Code mengikuti lebar qr-box */
                        height: 100% !important;
                        /* Memaksa QR Code mengikuti tinggi qr-box */
                        display: block;
                    }

                    .qr-box img {
                        width: 100%;
                        height: 100%;
                    }

                    /* Bagian Info Bawah */
                    .info-section {
                        margin-top: 15px;
                        font-size: 13px;
                        color: #111a36;
                    }

                    .info-table td {
                        padding: 3px 0;
                        font-weight: bold;
                        vertical-align: top;
                    }
                </style>

                <div class="modal-content" style="border-radius: 20px; border: none;">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Kartu Pelajar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-container">
                        <div class="qr-sidebar">
                            <div class="sidebar-line"></div>
                            <div class="sidebar-text">{{ $sekolah->nama_sekolah }}</div>
                        </div>

                        <div class="main-content">
                            <div class="header-logo">
                                <table>
                                    <tr>
                                        <td width="50">
                                            @if ($sekolah->logo)
                                                <img src="{{ asset('storage/' . $sekolah->logo) }}" width="45">
                                            @else
                                                <img src="{{ asset('img/logo-sekolah.png') }}" width="45">
                                            @endif
                                        </td>
                                        <td>
                                            <div class="school-name">{{ $sekolah->nama_sekolah }}</div>
                                            <div class="school-loc">{{ $sekolah->alamat }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="photo-profile">
                                @if ($murid->photo)
                                    <img src="{{ asset('storage/' . $murid->photo) }}">
                                @else
                                    <img src="{{ asset('img/user4-128x128.jpg') }}">
                                @endif
                            </div>

                            <div class="student-name">
                                {{ $data->nama }}
                            </div>

                            <div class="qr-box">
                                {!! $qr !!}
                            </div>

                            <div class="info-section">
                                <table class="info-table" width="100%">
                                    <tr>
                                        <td width="50">Kelas</td>
                                        <td width="10">:</td>
                                        <td>{{ $data->kelas->kelas }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIS</td>
                                        <td>:</td>
                                        <td>{{ $data->nis }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <a href="{{ url('/download-kartu-satuan/' . $murid->id) }}"
                            class="btn btn-success btn-lg shadow">
                            <i class="fas fa-download mr-2"></i> Download Kartu (PDF)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Menampilkan Hasil dari Range Tanggal -->

    <!-- Pop Up Form Hapus Murid -->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-danger">Hapus Murid</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/detail-murid/hapus/{{ $murid->id }}" method="post">
                    @csrf
                    <div class="modal-body mx-3">
                        <div class="md-form mb-0">
                            <p class="text-danger">Hapus : <b>{{ $murid->nama }}</b></p>
                            <p class="text-danger"><i>Perhatian! Menghapus data siswa tidak dapat di undur! Apabila kamu
                                    sudah mengisi Absensi dengan data siswa ini sebelumnya, maka data absensi untuk siwa ini
                                    akan hilang permanen! Apabila kamu sudah mengerti tentang resiko ini, maka silahkan isi
                                    Captcha di bawah dan klik Submit.</i></p>
                            <hr>
                            {!! captcha_img() !!}
                            <span><input type="text" name="captcha" placeholder="Masukkan captcha" required></span>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">Saya Yakin!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
