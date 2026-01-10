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

    /* Sidebar Biru Gelap sesuai gambar */
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

    /* Teks Vertikal di Sidebar */
    /* Teks Vertikal di Sidebar */
    .sidebar-text {
        position: absolute;
        width: 400px;
        left: -170px;
        /* Koordinat khusus DomPDF agar pas di tengah */
        top: 230px;
        /* Pakai TOP lebih stabil daripada BOTTOM di PDF */
        transform: rotate(-90deg);
        text-align: center;
        color: #ffb800;
        font-weight: bold;
        font-size: 18px;
        white-space: nowrap;
        background-color: #111a36;
        /* Nutupi garis kuning */
        padding: 5px 0;
    }

    .sidebar-line {
        position: absolute;
        left: 30px;
        top: 0;
        bottom: 0;
        /* Full ke bawah */
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
        text-align: center;
        margin: 10px auto;
        padding: 8px;
        border: 2px solid #ffb800;
        width: 150px;
        height: 150px;
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
@php
    // Logic Base64 untuk Logo (Paling aman untuk PDF)
    $pathLogo = $sekolah->logo ? public_path('storage/' . $sekolah->logo) : public_path('img/logo-sekolah.png');
    $typeLogo = pathinfo($pathLogo, PATHINFO_EXTENSION);
    $dataLogo = file_get_contents($pathLogo);
    $base64Logo = 'data:image/' . $typeLogo . ';base64,' . base64_encode($dataLogo);

    // Logic Base64 untuk Foto Murid
    $pathPhoto = $data->photo ? public_path('storage/' . $data->photo) : public_path('img/user4-128x128.jpg');
    $typePhoto = pathinfo($pathPhoto, PATHINFO_EXTENSION);
    $dataPhoto = file_get_contents($pathPhoto);
    $base64Photo = 'data:image/' . $typePhoto . ';base64,' . base64_encode($dataPhoto);
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

        <div class="student-name">{{ $data->nama }}</div>

        <div class="qr-box">
            <img src="data:image/png;base64,{{ $qr }}" width="80" alt="QR Code" />
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
