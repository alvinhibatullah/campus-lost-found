# 🏫 Campus Lost & Found

**Campus Lost & Found** adalah aplikasi berbasis web yang dirancang untuk membantu mahasiswa dan civitas akademika melaporkan barang hilang, mengumumkan barang temuan, dan melakukan klaim barang di lingkungan kampus.

Aplikasi ini dibangun dengan **Laravel** dan mengusung antarmuka modern **Glassmorphism (Dark Mode)** serta fitur peta interaktif.

---

##  Fitur Utama

* **🔐 Autentikasi Pengguna:** Login aman (Support Google Login & Manual).
* **📊 Dashboard Interaktif:** Statistik real-time laporan kehilangan dan temuan dengan grafik visual.
* **📦 Manajemen Barang Hilang (Lost Items):**
    * Lapor barang hilang dengan detail lengkap (Foto, Kategori, Deskripsi).
    * Pinpoint lokasi kejadian menggunakan **Peta Interaktif**.
* **🔍 Manajemen Barang Temuan (Found Items):**
    * Daftar barang yang ditemukan di kampus.
    * Status ketersediaan barang.
* **🙋‍♂️ Klaim Barang:** Proses pengajuan klaim kepemilikan barang dengan verifikasi admin.
* **📱 UI Modern:** Desain **Glassmorphism**, Responsif, dan Animasi Halus.
* **🗺️ Integrasi Peta:** Menggunakan **Leaflet.js** untuk menandai lokasi.

---

## Teknologi yang Digunakan

* **Backend:** [Laravel 10/11](https://laravel.com) (PHP)
* **Database:** MySQL
* **Frontend:** Blade Templates, [Bootstrap 5](https://getbootstrap.com)
* **Styling:** Custom CSS (Glassmorphism, Animations)
* **Maps:** [Leaflet.js](https://leafletjs.com) (OpenStreetMap)
* **Charts:** [Chart.js](https://www.chartjs.org)

---

## Panduan Instalasi (Setup Project)

Ikuti langkah ini untuk menjalankan proyek di komputer lokal:

### 1. Clone Repository
```bash
git clone [https://github.com/username-kalian/campus-lost-found.git](https://github.com/username-kalian/campus-lost-found.git)
cd campus-lost-found
