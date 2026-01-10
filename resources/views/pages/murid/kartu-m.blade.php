<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    @page {
        margin: 0.5cm;
        size: A4 portrait;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Helvetica', 'Arial', sans-serif;
        background-color: #ffffff;
    }

    .grid-container {
        width: 100%;
    }

    /* Ukuran Standar ID Card (ISO 7810 ID-1) adalah 8.56cm x 5.4cm */
    /* Kita gunakan 5.5cm x 8.5cm untuk mode Portrait */
    .card-wrapper {
        display: inline-block;
        width: 5.5cm;
        height: 8.5cm;
        margin: 0.15cm;
        vertical-align: top;
        page-break-inside: avoid;
    }

    .card-container {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
        border: 0.5pt solid #111a36;
        background-color: white;
    }

    /* Sidebar Biru Gelap Vertikal */
    .sidebar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 0.8cm;
        background-color: #111a36;
    }

    .sidebar-text {
        position: absolute;
        width: 7cm;
        left: -3.1cm;
        top: 3.5cm;
        transform: rotate(-90deg);
        text-align: center;
        color: #ffb800;
        font-weight: bold;
        font-size: 9pt;
        white-space: nowrap;
        background-color: #111a36;
    }

    .sidebar-line {
        position: absolute;
        left: 0.4cm;
        top: 0;
        bottom: 0;
        width: 1.5px;
        background-color: #ffb800;
    }

    /* Konten Utama */
    .main-content {
        margin-left: 0.8cm;
        padding: 0.2cm;
    }

    .header-logo {
        margin-bottom: 0.3cm;
    }

    .school-name {
        font-size: 7.5pt;
        font-weight: bold;
        color: #111a36;
        line-height: 1.1;
    }

    .photo-profile {
        text-align: center;
        margin: 0.3cm 0;
    }

    .photo-profile img {
        width: 2.2cm;
        height: 2.2cm;
        border-radius: 50%;
        border: 2px solid #ffb800;
        object-fit: cover;
    }

    .student-name {
        text-align: center;
        font-size: 9pt;
        font-weight: 900;
        color: #111a36;
        text-transform: uppercase;
        height: 0.8cm;
        overflow: hidden;
    }

    .qr-box {
        width: 1.8cm;
        height: 1.8cm;
        margin: 0.2cm auto;
        padding: 2px;
        border: 1px solid #ddd;
    }

    .qr-box img {
        width: 100%;
        height: 100%;
    }

    .info-section {
        margin-top: 0.2cm;
        font-size: 7.5pt;
        color: #111a36;
    }

    .info-table td {
        padding: 1px 0;
        font-weight: bold;
    }
</style>

<div class="grid-container">
    @foreach ($data as $item)
        @php
            $pathLogo =
                $sekolah && $sekolah->logo && file_exists(public_path('storage/' . $sekolah->logo))
                    ? public_path('storage/' . $sekolah->logo)
                    : public_path('img/logo-sekolah.png');
            $base64Logo =
                'data:image/' .
                pathinfo($pathLogo, PATHINFO_EXTENSION) .
                ';base64,' .
                base64_encode(file_get_contents($pathLogo));

            $pathPhoto =
                $item['photo'] && file_exists(public_path('storage/' . $item['photo']))
                    ? public_path('storage/' . $item['photo'])
                    : public_path('img/user4-128x128.jpg');
            $base64Photo =
                'data:image/' .
                pathinfo($pathPhoto, PATHINFO_EXTENSION) .
                ';base64,' .
                base64_encode(file_get_contents($pathPhoto));

            $base64Qr = 'data:image/svg+xml;base64,' . base64_encode($item['qr']);
        @endphp

        <div class="card-wrapper">
            <div class="card-container">
                <div class="sidebar">
                    <div class="sidebar-line"></div>
                    <div class="sidebar-text">{{ $sekolah->nama_sekolah }}</div>
                </div>

                <div class="main-content">
                    <div class="header-logo">
                        <table width="100%">
                            <tr>
                                <td width="30"><img src="{{ $base64Logo }}" width="25"></td>
                                <td class="school-name">{{ $sekolah->nama_sekolah }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="photo-profile">
                        <img src="{{ $base64Photo }}">
                    </div>

                    <div class="student-name">{{ $item['nama'] }}</div>

                    <div class="qr-box">
                        <img src="{{ $base64Qr }}">
                    </div>

                    <div class="info-section">
                        <table class="info-table" width="100%">
                            <tr>
                                <td width="35">Kelas</td>
                                <td width="8">:</td>
                                <td>{{ $item['kelas'] }}</td>
                            </tr>
                            <tr>
                                <td>NIS</td>
                                <td>:</td>
                                <td>{{ $item['nis'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
