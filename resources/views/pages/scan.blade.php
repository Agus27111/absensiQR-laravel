@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Scan QR</li>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">

                    <div id="messageContainer"></div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Menu Scan QR</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Pilih kamera belakang pada kolom di bawah, kemudian arahkan ke kode QR pada
                                kartu siswa.</p>

                            <div class="card mb-3" style="max-width: 100%;">
                                <div class="row no-gutters">
                                    <div class="col-lg-12">
                                        <div class="card-body text-center">
                                            <video id="previewKamera" playsinline muted
                                                style="width: 100%; max-width: 500px; border: 2px solid #ddd; border-radius: 8px;"></video>
                                            <br>
                                            <select id="pilihKamera" class="form-control mt-2"
                                                style="max-width: 400px; margin: auto;">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-warning">Perlu bantuan ?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        let selectedDeviceId = null;
        const codeReader = new ZXing.BrowserMultiFormatReader();
        const sourceSelect = $("#pilihKamera");
        let isScanning = false; // Untuk mencegah double scan saat proses AJAX

        // 1. Inisialisasi Scanner saat halaman siap
        $(document).ready(function() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                initScanner();
            } else {
                alert('Akses kamera tidak didukung atau belum menggunakan HTTPS.');
            }
        });

        // 2. Fungsi untuk list kamera yang tersedia
        function initScanner() {
            codeReader.listVideoInputDevices()
                .then(videoInputDevices => {
                    if (videoInputDevices.length > 0) {
                        sourceSelect.html(''); // Kosongkan select

                        // Gunakan kamera belakang secara otomatis jika tersedia
                        const backCamera = videoInputDevices.find(device =>
                            device.label.toLowerCase().includes('back') ||
                            device.label.toLowerCase().includes('rear')
                        );

                        selectedDeviceId = backCamera ? backCamera.deviceId : videoInputDevices[0].deviceId;

                        videoInputDevices.forEach((element) => {
                            const sourceOption = document.createElement('option');
                            sourceOption.text = element.label;
                            sourceOption.value = element.deviceId;
                            if (element.deviceId == selectedDeviceId) {
                                sourceOption.selected = 'selected';
                            }
                            sourceSelect.append(sourceOption);
                        });

                        decodeQRCode();
                    } else {
                        alert("Kamera tidak ditemukan!");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Gagal mengakses kamera: " + err);
                });
        }

        // 3. Fungsi utama pemindaian
        function decodeQRCode() {
            codeReader.decodeFromVideoDevice(selectedDeviceId, 'previewKamera', (result, err) => {
                if (result && !isScanning) {
                    isScanning = true; // Mengunci agar tidak mengirim banyak request sekaligus

                    var snd = new Audio("{{ asset('audio/beep.mp3') }}");

                    // Coba putar suara
                    snd.play().catch(e => console.warn(
                        "Suara diblokir browser, silakan klik di mana saja pada halaman dulu."));

                    // Tampilkan hasil di input (jika ada)
                    $("#hasilscan").val(result.text);

                    // --- PROSES AJAX ---
                    $.ajax({
                        url: '/scan-qr/' + encodeURIComponent(result.text),
                        method: 'get',
                        success: function(response) {
                            // Membuat pesan sukses seperti kode lama Anda
                            var successMessage = document.createElement("div");
                            successMessage.className = "alert alert-success";
                            successMessage.textContent = "Absensi berhasil.";
                            document.getElementById("messageContainer").appendChild(successMessage);

                            setTimeout(function() {
                                successMessage.remove();
                            }, 5000);
                        },
                        error: function(xhr, status, error) {
                            // Membuat pesan error seperti kode lama Anda
                            var errorMessage = document.createElement("div");
                            errorMessage.className =
                                "alert alert-danger"; // Tambahkan class danger agar merah
                            errorMessage.textContent = xhr.responseJSON ? xhr.responseJSON.message :
                                "Terjadi kesalahan.";
                            document.getElementById("messageContainer").appendChild(errorMessage);

                            setTimeout(function() {
                                errorMessage.remove();
                            }, 5000);
                        },
                        complete: function() {
                            // Beri jeda 3 detik sebelum kamera bisa menscan QR berikutnya
                            setTimeout(() => {
                                isScanning = false;
                            }, 3000);
                        }
                    });
                }

                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            });
        }

        // 4. Fungsi AJAX untuk kirim ke Laravel
        function sendDataToServer(qrCode) {
            $.ajax({
                url: '/scan-qr/' + encodeURIComponent(qrCode),
                method: 'GET',
                success: function(response) {
                    showMessage("Absensi berhasil: " + qrCode, "success");
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : "Terjadi kesalahan server";
                    showMessage("Gagal: " + msg, "danger");
                },
                complete: function() {
                    // Beri jeda 2 detik sebelum bisa scan lagi agar tidak duplikat
                    setTimeout(() => {
                        isScanning = false;
                    }, 2000);
                }
            });
        }

        // 5. Helper: Menampilkan pesan di messageContainer
        function showMessage(text, type) {
            const messageHtml = `<div class="alert alert-${type} alert-dismissible fade show">
                                ${text}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                             </div>`;

            $("#messageContainer").html(messageHtml);

            // Hapus pesan otomatis setelah 4 detik
            setTimeout(() => {
                $(".alert").alert('close');
            }, 4000);
        }

        // 6. Helper: Suara beep sukses
        function playBeep() {
            // Suara Beep Standar (Base64 WAV)
            const beepSound = new Audio(
                "data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU9vT18=");

            // Memberikan instruksi ke browser agar mengizinkan pemutaran
            let playPromise = beepSound.play();

            if (playPromise !== undefined) {
                playPromise.then(_ => {
                    // Suara berhasil diputar
                }).catch(error => {
                    // Biasanya kena blokir browser jika user belum melakukan klik apapun di halaman
                    console.warn("Autoplay diblokir. User harus berinteraksi dengan halaman dulu.");
                });
            }
        }

        // 7. Event saat ganti kamera di dropdown
        $(document).on('change', '#pilihKamera', function() {
            selectedDeviceId = $(this).val();
            codeReader.reset();
            decodeQRCode();
        });
    </script>
@endsection
