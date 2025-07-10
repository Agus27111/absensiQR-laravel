const codeReader = new ZXing.BrowserMultiFormatReader();
let selectedDeviceId = null;
const sourceSelect = $('#sourceSelect');
const videoElement = document.getElementById('video');

function initScanner() {
  codeReader.listVideoInputDevices()
    .then((videoInputDevices) => {
      if (videoInputDevices.length === 0) {
        alert("Tidak ada kamera terdeteksi.");
        return;
      }

      selectedDeviceId = selectedDeviceId ?? videoInputDevices[0].deviceId;

      sourceSelect.html('');
      videoInputDevices.forEach((device) => {
        const option = document.createElement('option');
        option.text = device.label || `Kamera ${device.deviceId}`;
        option.value = device.deviceId;
        if (device.deviceId === selectedDeviceId) {
          option.selected = true;
        }
        sourceSelect.append(option);
      });

      decodeQRCode();
    })
    .catch((err) => {
      console.error("Error mendapatkan kamera:", err);
      alert("Gagal mendeteksi kamera: " + err);
    });
}

function decodeQRCode() {
  codeReader.decodeFromVideoDevice(selectedDeviceId, videoElement, (result, err) => {
    if (result) {
      console.log("Hasil QR Code:", result.text);
      alert("Hasil QR Code: " + result.text);
      codeReader.reset(); // stop scan setelah berhasil
    }
    if (err && !(err instanceof ZXing.NotFoundException)) {
      console.error("Scan error:", err);
    }
  });
}

sourceSelect.on('change', function () {
  selectedDeviceId = $(this).val();
  codeReader.reset();
  decodeQRCode();
});

$(document).ready(function () {
  initScanner();
});