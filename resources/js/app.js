// Ambil form
const form = document.querySelector("form");

// Event submit
form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Ambil data input
    const nama = document.querySelector('input[type="text"]').value;
    const tanggal = document.querySelector('input[type="date"]').value;
    const jam = document.querySelector('input[type="time"]').value;
    const dokter = document.querySelector("select").value;
    const keluhan = document.querySelector("textarea").value;

    // Validasi sederhana
    if (!nama || !tanggal || !jam || !dokter) {
        alert("Semua data wajib diisi!");
        return;
    }

    // Simpan ke localStorage (sementara)
    const reservasi = {
        nama,
        tanggal,
        jam,
        dokter,
        keluhan
    };

    let data = JSON.parse(localStorage.getItem("reservasi")) || [];
    data.push(reservasi);
    localStorage.setItem("reservasi", JSON.stringify(data));

    // Notifikasi
    alert("Reservasi berhasil disimpan!");

    // Reset form
    form.reset();
});