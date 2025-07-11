@extends('layouts/main')

<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Murid / Input Murid</li>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-lg-6">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            Data Murid Berhasil di Tambahkan.
                        </div>
                    @endif
                    @error('nis')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('nama')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Halaman untuk menambahkan data murid</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="/input-murid-proses" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group col-md-12">
                                    <label for="nis">NIS</label>
                                    <input type="text" name="nis"
                                        class="form-control @error('nis') is-invalid @enderror" id="nis"
                                        placeholder="Masukkan NIS" value="{{ old('nis') }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="nama">Nama Lengkap</label>
                                    <input type="text" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror" id="nama"
                                        placeholder="Masukkan Nama" value="{{ old('nama') }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Kelas</label>
                                    @if ($kelas->count() > 0)
                                        <select class="form-control" id="kelas" name="kelas">
                                            @foreach ($kelas as $k)
                                                @if (old('kelas') == $k->kelas)
                                                    <option value="{{ $k->kelas }}" selected>{{ $k->kelas }}
                                                    </option>
                                                @else
                                                    <option value="{{ $k->kelas }}">{{ $k->kelas }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control"
                                            placeholder="Harap masukkan data Kelas terlebih dahulu!" disabled>
                                        <span><a href="/kelas">Klik disini untuk menambah Kelas</a></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Tahun</label>
                                    @if ($tahun->count() > 0)
                                        <select class="form-control" id="tahun" name="tahun">
                                            @foreach ($tahun as $t)
                                                @if (old('tahun') == $t->tahun)
                                                    <option value="{{ $t->tahun }}" selected>{{ $t->tahun }}
                                                    </option>
                                                @else
                                                    <option value="{{ $t->tahun }}">{{ $t->tahun }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control"
                                            placeholder="Harap masukkan data Tahun terlebih dahulu!" disabled>
                                        <span><a href="/tahun">Klik disini untuk menambah Tahun</a></span>
                                    @endif
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="verifikasi" required>
                                    <label class="form-check-label" for="verifikasi">Data di atas sudah benar</label>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                @if ($tahun->count() > 0 && $kelas->count() > 0)
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                @else
                                    <button type="" class="btn btn-primary" disabled>Submit</button>
                                @endif
                            </div>
                        </form>

                        <!-- Tombol Download Template Import Murid -->
<a href="/download-template-murid" class="btn btn-info mb-2" target="_blank">
    <i class="fas fa-download"></i> Download Template Import Murid
</a>

                        <!-- Form Import Excel -->
                        <div class="card card-info mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Import Data Murid dari Excel</h3>
                            </div>
                            <form action="{{ route('murid.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">Import Excel</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- Configure a few settings and attach camera -->

@endsection
