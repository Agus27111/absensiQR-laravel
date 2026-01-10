<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    @page {
        margin: 1cm;
    }

    body {
        margin: 0;
        font-family: 'Helvetica', 'Arial', sans-serif;
        background-color: #ffffff;
    }

    /* Wrapper untuk kartu agar bisa berjejer */
    .card-wrapper {
        display: inline-block;
        margin: 10px;
        /* Jarak antar kartu */
        vertical-align: top;
    }

    .card-container {
        width: 250px;
        /* Ukuran diperkecil sedikit agar muat berjejer di A4 */
        height: 420px;
        position: relative;
        overflow: hidden;
        border: 1px solid #ddd;
        background: white;
    }

    /* Sidebar Gelap */
    .sidebar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 45px;
        background-color: #111a36;
        color: #ffb800;
        text-align: center;
    }

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

    .sidebar-line {
        position: absolute;
        left: 22px;
        top: 0;
        bottom: 180px;
        width: 1.5px;
        background-color: #ffb800;
    }

    /* Konten Utama */
    .main-content {
        margin-left: 45px;
        padding: 15px;
    }

    .header-logo table {
        width: 100%;
        border-collapse: collapse;
    }

    .school-name {
        font-size: 11px;
        font-weight: bold;
        color: #111a36;
        line-height: 1.1;
    }

    .school-loc {
        font-size: 9px;
        color: #333;
    }

    .photo-profile {
        text-align: center;
        margin: 10px 0;
    }

    .photo-profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 2px solid #ffb800;
        object-fit: cover;
    }

    .student-name {
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        color: #111a36;
        margin: 5px 0;
        height: 35px;
        /* Menjaga tinggi agar tetap sejajar */
        text-transform: uppercase;
    }

    .qr-box {
        text-align: center;
        margin: 10px auto;
        padding: 5px;
        border: 1.5px solid #ffb800;
        width: 110px;
        height: 110px;
    }

    .qr-box img {
        width: 100%;
    }

    .info-section {
        margin-top: 10px;
        font-size: 11px;
        color: #111a36;
    }

    .info-table td {
        padding: 2px 0;
        font-weight: bold;
    }

    /* Supaya tidak terpotong di tengah kartu saat ganti halaman PDF */
    .card-wrapper {
        display: inline-block;
        margin: 5px;
        page-break-inside: avoid;
    }
</style>

@foreach ($data as $item)
    <div class="card-wrapper">
        <div class="card-container">
            <div class="sidebar">
                <div class="sidebar-line"></div>
                <div class="sidebar-text">{{ $sekolah->nama_sekolah }}</div>
            </div>

            <div class="main-content">
                <div class="header-logo">
                    <table>
                        <tr>
                            <td width="50">
                                @if ($sekolah->logo)
                                    <img src="{{ public_path('storage/' . $sekolah->logo) }}" width="45">
                                @else
                                    <img src="{{ public_path('img/logo-sekolah.png') }}" width="45">
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="photo-profile">
                    @if ($item['photo'])
                        <img src="{{ public_path('storage/' . $item['photo']) }}">
                    @else
                        <img src="{{ public_path('/img/user4-128x128.jpg') }}">
                    @endif
                </div>

                <div class="student-name">{{ $item['nama'] }}</div>

                <div class="qr-box">
                    <img src="data:image/png;base64, {{ base64_encode($item['qr']) }} ">
                </div>

                <div class="info-section">
                    <table class="info-table" width="100%">
                        <tr>
                            <td width="40">Kelas</td>
                            <td>: {{ $item['kelas'] }}</td>
                        </tr>
                        <tr>
                            <td>NIS</td>
                            <td>: {{ $item['nis'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach
