<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

    .sidebar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 60px;
        background-color: #111a36;
        color: #ffb800;
        text-align: center;
    }

    .sidebar-text {
        position: absolute;
        width: 400px;
        left: -170px;
        top: 230px;
        transform: rotate(-90deg);
        text-align: center;
        color: #ffb800;
        font-weight: bold;
        font-size: 18px;
        white-space: nowrap;
        background-color: #111a36;
        padding: 5px 0;
    }

    .sidebar-line {
        position: absolute;
        left: 30px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #ffb800;
    }

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

    .qr-box {
        width: 100px;
        height: 100px;
        margin: 10px auto;
        background: white;
        padding: 5px;
        border: 1px solid #ddd;
    }

    .qr-box img {
        width: 100%;
        height: 100%;
        display: block;
    }

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

@php
    // 1. Logika Base64 untuk Logo Sekolah
    // Cek apakah file ada di storage, jika tidak pakai gambar default di public/img
    $pathLogo =
        $sekolah && $sekolah->logo && file_exists(public_path('storage/' . $sekolah->logo))
            ? public_path('storage/' . $sekolah->logo)
            : public_path('img/logo-sekolah.png');

    $typeLogo = pathinfo($pathLogo, PATHINFO_EXTENSION);
    $dataLogo = file_get_contents($pathLogo);
    $base64Logo = 'data:image/' . $typeLogo . ';base64,' . base64_encode($dataLogo);

    // 2. Logika Base64 untuk Foto Murid
    // Variabel foto murid di kartu satuan biasanya $data->photo, di massal juga $data['photo']
    $fotoPath = is_array($data) ? $data['photo'] : $data->photo;

    $pathPhoto =
        $fotoPath && file_exists(public_path('storage/' . $fotoPath))
            ? public_path('storage/' . $fotoPath)
            : public_path('img/user4-128x128.jpg');

    $typePhoto = pathinfo($pathPhoto, PATHINFO_EXTENSION);
    $dataPhoto = file_get_contents($pathPhoto);
    $base64Photo = 'data:image/' . $typePhoto . ';base64,' . base64_encode($dataPhoto);

    // 3. Logika Base64 untuk QR Code
    // Karena tidak pakai imagick, format QR Code otomatis SVG (text-based), kita base64-kan
    $base64Qr = 'data:image/svg+xml;base64,' . base64_encode($qr);
@endphp

<div class="card-container">
    <div class="sidebar">
        <div class="sidebar-line"></div>
        <div class="sidebar-text">{{ $sekolah->nama_sekolah }}</div>
    </div>

    <div class="main-content">
        <div class="header-logo">
            <table>
                <tr>
                    <td width="50"><img src="{{ $base64Logo }}" width="45"></td>
                    <td>
                        <div class="school-name">{{ $sekolah->nama_sekolah }}</div>
                        <div class="school-loc">{{ $sekolah->alamat }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="photo-profile">
            <img src="{{ $base64Photo }}">
        </div>

        <div class="student-name">
            {{ is_array($data) ? $data['nama'] : $data->nama }}
        </div>

        <div class="qr-box">
            <img src="{{ $base64Qr }}">
        </div>

        <div class="info-section">
            <table class="info-table" width="100%">
                <tr>
                    <td width="50">Kelas</td>
                    <td width="10">:</td>
                    <td>
                        {{ is_array($data) ? $data['kelas'] : $data->kelas->kelas }}
                    </td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <td>
                        {{ is_array($data) ? $data['nis'] : $data->nis }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
