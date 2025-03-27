// Memastikan penggunaan webkitSpeechRecognition pada browser yang mendukung
if (!('webkitSpeechRecognition' in window)) {
    alert("Pengenalan suara tidak didukung di browser ini.");
} else {
    const recognition = new webkitSpeechRecognition();
    recognition.lang = 'id-ID'; // Bahasa Indonesia
    recognition.interimResults = false;  // Tidak menggunakan hasil sementara
    recognition.continuous = true; // Mendengarkan secara terus menerus
    recognition.maxAlternatives = 1; // Menggunakan alternatif minimal untuk hasil

    recognition.onstart = function() {
        console.log("Pengenalan suara dimulai...");
        beriUmpanBalik("Silakan beri perintah suara.");
    };

    recognition.onerror = function(event) {
        console.error("Error pengenalan suara:", event.error);
        beriUmpanBalik("Terjadi kesalahan saat mendengarkan perintah suara.");
        recognition.start();  // Restart pengenalan suara jika terjadi kesalahan
    };

    recognition.onend = function() {
        console.log("Pengenalan suara selesai.");
        recognition.start(); // Mulai lagi setelah pengenalan suara selesai
    };

    recognition.onresult = function(event) {
        console.log("Hasil Pengenalan Suara:", event.results); // Tambahkan log untuk memeriksa hasil pengenalan suara
        const perintah = event.results[0] ? event.results[0][0].transcript.toLowerCase() : '';

        if (!perintah) {
            console.log("Tidak ada perintah yang diterima.");
            return;
        }

        console.log("Perintah Suara Diterima:", perintah);  // Log perintah suara yang diterima

        // Menangani perintah suara berdasarkan kategori
        if (perintah.includes("tambah")) {
            const matches = perintah.match(/tambah (\d+) unit (\w+)/);
            if (matches) {
                const jumlah = parseInt(matches[1]);
                const produk = matches[2];
                tambahStok(produk, jumlah);
            } else {
                beriUmpanBalik("Perintah tidak jelas. Mohon sebutkan jumlah dan produk dengan benar.");
            }
        } else if (perintah.includes("buka inventory")) {
            bukaInventory();
        } else if (perintah.includes("buka daftar penjualan") || perintah.includes("buka daftar order")) {
            bukaPenjualan();
        } else if (perintah.includes("buka daftar pembelian")) {
            bukaPembelian();
        } else if (perintah.includes("kembali ke dashboard")) {
            kembaliKeDashboard();
        } else if (perintah.includes("ekspor laporan")) {
            eksporLaporan();
        } else if (perintah.includes("cari")) {
            const matchesInventory = perintah.match(/cari (\w+) di inventory/);
            const matchesSales = perintah.match(/cari (\w+) di penjualan/);
            const matchesPurchases = perintah.match(/cari (\w+) di pembelian/);

            if (matchesInventory) {
                const produk = matchesInventory[1];
                cariData("inventory", produk);
            } else if (matchesSales) {
                const produk = matchesSales[1];
                cariData("sales", produk);
            } else if (matchesPurchases) {
                const produk = matchesPurchases[1];
                cariData("purchases", produk);
            } else {
                beriUmpanBalik("Perintah pencarian tidak dikenali.");
            }
        } else {
            beriUmpanBalik("Perintah tidak dikenali.");
        }
    };

    // Fungsi untuk memulai pengenalan suara otomatis saat halaman dimuat
    window.onload = function() {
        console.log("Halaman dimuat, mulai mendengarkan perintah suara...");
        recognition.start();
    };

    // Fungsi untuk memberi umpan balik suara kepada pengguna
    function beriUmpanBalik(text) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID'; // Bahasa Indonesia
        speechSynthesis.speak(utterance);
    }

    // Fungsi untuk menambah stok produk
    function tambahStok(produk, jumlah) {
        fetch('/update-stock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Pastikan CSRF token ada
            },
            body: JSON.stringify({ produk: produk, jumlah: jumlah })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            beriUmpanBalik("Stok " + produk + " telah ditambah sebanyak " + jumlah + " unit.");
        })
        .catch(error => {
            console.error('Error:', error);
            beriUmpanBalik("Terjadi kesalahan saat mengupdate stok.");
        });
    }

    // Fungsi untuk membuka halaman inventory
    function bukaInventory() {
        window.location.href = '/list_item';
    }

    // Fungsi untuk membuka halaman penjualan
    function bukaPenjualan() {
        window.location.href = '/list_sale';
    }

    // Fungsi untuk membuka halaman pembelian
    function bukaPembelian() {
        window.location.href = '/list_purchase';
    }

    function kembaliKeDashboard() {
        window.location.href = '/index';
    }

    // Fungsi untuk mengekspor laporan
    function eksporLaporan() {
        window.location.href = '/export';
    }

    // Fungsi untuk mencari data berdasarkan kategori
    function cariData(kategori, produk) {
        window.location.href = '/' + kategori + '/search/' + produk;
    }
}
