## 🏪 **Toko Kelvin — Aplikasi Penjualan Sederhana**

**Nama:** Kelvin Dwi Pranata Sembiring
**NIM:** 22051204085
**Kelas:** RPL 2022 B

---

### 📘 **Deskripsi Proyek**

**Toko Kelvin** adalah aplikasi penjualan berbasis web yang dibuat menggunakan **PHP Native**, **MySQL**, **HTML**, **CSS**, dan **Bootstrap 5**.
Aplikasi ini berfungsi untuk mengelola data **barang**, **pembeli**, dan **transaksi** pada sebuah toko secara sederhana namun terstruktur.

Sistem ini memiliki fitur autentikasi (login) untuk keamanan akses, tampilan dashboard interaktif, serta pengelolaan data menggunakan konsep CRUD (Create, Read, Update, Delete).

Aplikasi ini dikembangkan sebagai bagian dari pembelajaran **Pemrograman Web** dengan fokus pada penerapan konsep **CRUD, relasi antar tabel database, dan tampilan antarmuka yang responsif**.

---

### ⚙️ **Teknologi yang Digunakan**

* **Frontend:** HTML5, CSS3, Bootstrap 5, dan JavaScript
* **Backend:** PHP 8 
* **Database:** MySQL
* **Library tambahan:** SweetAlert2 (untuk konfirmasi hapus data)

---

### 📊 **Struktur Menu dan Fitur**

#### 🔐 **1. Halaman Login**

* Menyediakan form login dengan validasi username dan password.
* Menggunakan session untuk menjaga keamanan akses halaman.
* Desain modern dengan tampilan *split screen* (gambar & form login).

#### 🏠 **2. Dashboard Penjualan**

* Menampilkan ringkasan performa toko:

  * **Total Transaksi**
  * **Total Pendapatan**
  * **Barang Terlaris**
* Menampilkan tabel “Transaksi Terbaru” secara dinamis.
* Desain menggunakan *card layout* agar tampilan rapi dan responsif.

#### 📦 **3. Data Barang**

* CRUD Data Barang (Tambah, Edit, Hapus).
* Kolom: ID Barang, Nama Barang, Harga, Stok.
* Harga diformat dalam bentuk mata uang (Rp 10.000).
* Stok menampilkan jumlah unit barang tersedia.
* Tombol aksi menggunakan ikon (✏️ Edit dan 🗑️ Hapus) dengan warna berbeda untuk memudahkan pengguna.

#### 👤 **4. Data Pembeli**

* CRUD Data Pembeli (Tambah, Edit, Hapus).
* Kolom: ID Pembeli, Nama Pembeli, Alamat, dan No. HP.
* Kolom No. HP menggunakan `VARCHAR` agar bisa menyimpan angka 0 di awal.
* Tersedia fitur **pencarian nama pembeli** secara dinamis.

#### 💰 **5. Data Transaksi**

* Menampilkan daftar transaksi penjualan lengkap.
* Fitur:

  * **Filter berdasarkan tanggal transaksi**
  * **Tombol Cetak laporan transaksi**
  * **Tombol tambah transaksi baru**
  * **Perhitungan total keseluruhan otomatis (Rp 26.551.000)**
* Data yang ditampilkan:

  * ID Transaksi
  * Nama Pembeli
  * Nama Barang
  * Tanggal
  * Jumlah barang
  * Total Harga
* Menggunakan *Bootstrap Table* dengan warna header biru muda dan *hover effect*.

---

### 📚 **Struktur Tabel Database Utama**

#### Tabel `barang`

| Field       | Tipe Data     | Keterangan                           |
| :---------- | :------------ | :----------------------------------- |
| id_barang   | VARCHAR(25)   | Primary Key                          |
| nama_barang | VARCHAR(100)  | Nama produk                          |
| harga       | DECIMAL(10,2) | Menyimpan nilai harga secara presisi |
| stok        | INT           | Jumlah stok barang                   |

#### Tabel `pembeli`

| Field        | Tipe Data    | Keterangan                |
| :----------- | :----------- | :------------------------ |
| id_pembeli   | VARCHAR(25)  | Primary Key               |
| nama_pembeli | VARCHAR(100) | Nama pelanggan            |
| alamat       | TEXT         | Alamat lengkap            |
| no_hp        | VARCHAR(15)  | Nomor handphone pelanggan |

#### Tabel `transaksi`

| Field        | Tipe Data     | Keterangan                   |
| :----------- | :------------ | :--------------------------- |
| id_transaksi | VARCHAR(25)   | Primary Key                  |
| id_pembeli   | VARCHAR(25)   | Foreign Key ke tabel pembeli |
| id_barang    | VARCHAR(25)   | Foreign Key ke tabel barang  |
| tanggal      | DATE          | Tanggal transaksi            |
| jumlah       | INT           | Jumlah barang dibeli         |
| total_harga  | DECIMAL(10,2) | Total harga transaksi        |

---

### 🧠 **Fitur Tambahan**

* Konfirmasi hapus data menggunakan **SweetAlert2 popup**.
* Tampilan responsif untuk semua ukuran layar.
* Menggunakan **Bootstrap Icons** untuk tombol aksi.
* Format rupiah otomatis (`number_format` PHP).
* Navigasi aktif otomatis di sidebar/topbar sesuai halaman yang sedang dibuka.

---

### 🚀 **Tujuan Pengembangan**

Proyek ini dikembangkan untuk:

1. Melatih kemampuan dalam **membangun aplikasi CRUD berbasis web**.
2. Mengimplementasikan konsep **relasional database (foreign key)** antara barang, pembeli, dan transaksi.
3. Membiasakan penggunaan **format tampilan profesional dan responsif** untuk aplikasi berbasis dashboard.

---

## 🖼️ Tampilan Aplikasi

### 🧩 Login
<img width="2874" height="1532" alt="Login" src="https://github.com/user-attachments/assets/e621140e-2f6e-4814-817a-e953b4436b0b" />


### 📊 Dashboard
<img width="2874" height="1536" alt="Dashboard" src="https://github.com/user-attachments/assets/5ac0cd8b-55ec-419b-8f06-4e9311e5e277" />


### 📦 Data Barang
<img width="2880" height="1526" alt="Barang" src="https://github.com/user-attachments/assets/33a970fa-6410-40cb-a053-4444f0f8728e" />


### 👤 Data Pembeli
<img width="2880" height="1530" alt="Pembeli" src="https://github.com/user-attachments/assets/521cc7fc-8c97-43e9-a6d7-2fc18f91e742" />


### 💰 Transaksi
<img width="2878" height="1536" alt="Transaksi" src="https://github.com/user-attachments/assets/0085d536-736f-40a3-8176-707f81df6721" />
