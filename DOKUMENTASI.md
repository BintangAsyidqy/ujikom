# DOKUMENTASI SISTEM INVENTORY MANAGEMENT
## Aplikasi Manajemen Inventaris Berbasis Web

---

## DAFTAR ISI

1. Pendahuluan
2. Teknologi yang Digunakan
3. Struktur Database
4. Struktur Aplikasi
5. Alur Sistem (Flow)
6. Halaman-Halaman Aplikasi
   - 6.1 Halaman Welcome / Login
   - 6.2 Dashboard Admin
   - 6.3 Halaman Kategori (Admin)
   - 6.4 Halaman Items (Admin)
   - 6.5 Halaman Admin Accounts (Admin)
   - 6.6 Halaman Operator Accounts (Admin)
   - 6.7 Dashboard Operator
   - 6.8 Halaman Items (Operator)
   - 6.9 Halaman Lending (Operator)
   - 6.10 Halaman My Account (Operator)
7. Daftar Route / URL
8. Fitur Export Excel
9. Sistem Autentikasi & Role

---

## 1. PENDAHULUAN

Sistem Inventory Management adalah aplikasi web berbasis Laravel yang digunakan untuk mengelola inventaris barang/item. Sistem ini memiliki dua peran pengguna yaitu **Admin** dan **Operator**.

- **Admin** bertugas mengelola kategori, item, dan akun pengguna.
- **Operator** bertugas mencatat peminjaman (lending) item dan memantau stok.

---

## 2. TEKNOLOGI YANG DIGUNAKAN

| Komponen       | Teknologi                        |
|----------------|----------------------------------|
| Framework      | Laravel 10                       |
| Bahasa         | PHP                              |
| Database       | MySQL                            |
| Frontend       | Blade Template + Tailwind CSS    |
| Icon           | Font Awesome 6.4.0               |
| Export Excel   | Maatwebsite/Excel + PhpSpreadsheet |
| Authentication | Laravel Auth (Session-based)     |

---

## 3. STRUKTUR DATABASE

### Tabel: users

| Kolom          | Tipe         | Keterangan                        |
|----------------|--------------|-----------------------------------|
| id             | bigint (PK)  | Primary key auto increment        |
| name           | string       | Nama pengguna                     |
| email          | string       | Email (unique)                    |
| role           | string       | Role: `admin` atau `operator`     |
| password       | string       | Password terenkripsi (bcrypt)     |
| password_plain | string/null  | Password plain (untuk reset)      |
| remember_token | string/null  | Token remember me                 |
| created_at     | timestamp    | Waktu dibuat                      |
| updated_at     | timestamp    | Waktu diperbarui                  |

### Tabel: categories

| Kolom      | Tipe        | Keterangan                              |
|------------|-------------|-----------------------------------------|
| id         | bigint (PK) | Primary key auto increment              |
| name       | string      | Nama kategori (unique)                  |
| division   | string      | Division PJ: Sarpras / Tata Usaha / Tefa|
| created_at | timestamp   | Waktu dibuat                            |
| updated_at | timestamp   | Waktu diperbarui                        |

### Tabel: items

| Kolom       | Tipe        | Keterangan                              |
|-------------|-------------|-----------------------------------------|
| id          | bigint (PK) | Primary key auto increment              |
| category_id | bigint (FK) | Foreign key ke tabel categories         |
| name        | string      | Nama item                               |
| total       | integer     | Jumlah total item tersedia              |
| repair      | integer     | Jumlah item dalam perbaikan             |
| created_at  | timestamp   | Waktu dibuat                            |
| updated_at  | timestamp   | Waktu diperbarui                        |

### Tabel: lendings

| Kolom      | Tipe        | Keterangan                                        |
|------------|-------------|---------------------------------------------------|
| id         | bigint (PK) | Primary key auto increment                        |
| user_id    | bigint (FK) | Foreign key ke tabel users (operator yang input)  |
| name       | string      | Nama peminjam                                     |
| items      | json        | Array item yang dipinjam: [{item_id, name, total}]|
| ket        | string/null | Keterangan tambahan                               |
| date       | date        | Tanggal peminjaman                                |
| returned   | boolean     | Status pengembalian (default: false)              |
| created_at | timestamp   | Waktu dibuat                                      |
| updated_at | timestamp   | Waktu diperbarui (dipakai sebagai return date)    |

### Relasi Antar Tabel

```
users       ──< lendings   (one-to-many: satu user bisa punya banyak lending)
categories  ──< items      (one-to-many: satu kategori bisa punya banyak item)
```

---

## 4. STRUKTUR APLIKASI

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php       ← Login, logout, dashboard
│   │   ├── CategoryController.php   ← CRUD kategori
│   │   ├── ItemController.php       ← CRUD item + export
│   │   ├── LendingController.php    ← CRUD lending + export
│   │   └── UserController.php       ← CRUD user + export + reset password
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Item.php
│   └── Lending.php
resources/
└── views/
    ├── welcome.blade.php            ← Halaman utama + modal login
    ├── admin/
    │   ├── dashboard.blade.php
    │   ├── categories.blade.php
    │   ├── items.blade.php
    │   ├── users-admin.blade.php
    │   └── users-operator.blade.php
    ├── operator/
    │   ├── dashboard.blade.php
    │   ├── items.blade.php
    │   ├── lending.blade.php
    │   └── users.blade.php
    └── components/
        ├── sidebar.blade.php        ← Sidebar admin
        └── operator-sidebar.blade.php ← Sidebar operator
```

---

## 5. ALUR SISTEM (FLOW)

### Alur Login
```
1. User membuka halaman utama (/)
2. Klik tombol "Login" → modal login muncul
3. Input email dan password
4. Sistem memvalidasi kredensial
5. Jika role = admin  → redirect ke /admin/dashboard
6. Jika role = operator → redirect ke /operator/dashboard
7. Jika gagal → kembali ke halaman login dengan pesan error
```

### Alur Peminjaman (Lending)
```
1. Operator membuka halaman /operator/lending
2. Klik tombol "Add" → modal form muncul
3. Isi: Nama peminjam, pilih item, jumlah, keterangan, tanggal
4. Submit → sistem cek stok item
5. Jika stok cukup → data lending disimpan, stok item berkurang
6. Jika stok tidak cukup → error "Total item more than available!"
7. Untuk pengembalian: klik tombol "Returned" → stok item bertambah kembali
```

### Alur Pengelolaan Item
```
1. Admin membuka /admin/items
2. Tambah item: isi kategori, nama, total, repair → Save
3. Edit item: klik Edit → ubah data → Update
   (field "New Broke Item" menambah nilai repair yang sudah ada)
4. Export: klik "Export Excel" → download file .xlsx
```

---

## 6. HALAMAN-HALAMAN APLIKASI

---

### 6.1 Halaman Welcome / Login

**URL:** `/`  
**Akses:** Publik (semua pengguna)

**Deskripsi:**  
Halaman utama aplikasi yang menampilkan landing page dengan informasi singkat tentang sistem Inventory Management. Terdapat tombol "Login" dan "Get Started" yang membuka modal login.

**Komponen Halaman:**
- Header dengan logo "IM" (Inventory Management)
- Judul: "Inventory Management of Personal Items"
- Deskripsi: "Management of incoming and outgoing items"
- Ilustrasi SVG inventory
- Footer dengan link Tentang, Kontak, Login

**Modal Login:**
- Field Email (required, validasi format email)
- Field Password (required)
- Checkbox "Ingat saya" (remember me)
- Tombol "Masuk"
- Validasi client-side (JavaScript) dan server-side (Laravel)
- Pesan error ditampilkan jika login gagal

**Validasi Login:**
- Email wajib diisi
- Password wajib diisi
- Jika email/password salah: "Email atau password salah."

---

### 6.2 Dashboard Admin

**URL:** `/admin/dashboard`  
**Akses:** Admin only  
**Middleware:** `auth`, `role:admin`

**Deskripsi:**  
Halaman dashboard utama untuk admin. Menampilkan pesan selamat datang dengan nama admin yang sedang login dan ringkasan sistem.

**Komponen Halaman:**
- Sidebar navigasi admin (kiri)
- Judul: "Dashboard"
- Pesan: "Selamat datang di panel admin, [nama admin]."
- Subjudul: "Ringkasan Sistem"

**Navigasi Sidebar Admin:**
- Dashboard
- Categories
- Items
- Admin Accounts
- Operator Accounts
- Logout

---

### 6.3 Halaman Kategori (Admin)

**URL:** `/admin/categories`  
**Akses:** Admin only  
**Middleware:** `auth`, `role:admin`

**Deskripsi:**  
Halaman untuk mengelola kategori item inventory. Admin dapat menambah, mengedit, dan menghapus kategori.

**Tabel Kategori:**

| Kolom       | Keterangan                    |
|-------------|-------------------------------|
| No          | Nomor urut                    |
| Name        | Nama kategori                 |
| Division PJ | Divisi penanggung jawab       |
| Total Items | Jumlah item dalam kategori    |
| Action      | Tombol Edit dan Delete        |

**Form Tambah Kategori (Modal):**
- Name: input teks (wajib, unique)
- Division PJ: dropdown pilihan (Sarpras / Tata Usaha / Tefa)
- Tombol Save dan Cancel

**Form Edit Kategori (Modal):**
- Name: input teks (pre-filled dari data existing)
- Division PJ: dropdown (pre-selected)
- Tombol Update dan Cancel
- Data diambil via AJAX GET ke `/admin/categories/{id}/edit`

**Konfirmasi Hapus (Modal):**
- Pesan: "Kategori ini akan dihapus permanen."
- Tombol Delete (merah) dan Cancel

**Validasi:**
- Name: wajib diisi, maksimal 255 karakter, harus unik
- Division: wajib dipilih

---

### 6.4 Halaman Items (Admin)

**URL:** `/admin/items`  
**Akses:** Admin only  
**Middleware:** `auth`, `role:admin`

**Deskripsi:**  
Halaman untuk mengelola data item inventory. Admin dapat menambah, mengedit item, dan mengekspor data ke Excel.

**Tabel Items:**

| Kolom    | Keterangan                              |
|----------|-----------------------------------------|
| #        | Nomor urut                              |
| Category | Nama kategori item                      |
| Name     | Nama item                               |
| Total    | Jumlah stok tersedia                    |
| Repair   | Jumlah item dalam perbaikan             |
| Lending  | Jumlah item sedang dipinjam             |
| Action   | Tombol Edit                             |

**Tombol Aksi:**
- Export Excel (ungu): download data items ke file .xlsx
- Add (hijau): buka modal tambah item

**Form Tambah Item (Modal):**
- Category: dropdown pilih kategori (wajib)
- Name: input teks, contoh: "Piring" (wajib)
- Total: input angka, min 0 (wajib)
- Repair: input angka, min 0 (wajib)
- Tombol Save dan Cancel

**Form Edit Item (Modal):**
- Category: dropdown (pre-selected)
- Name: input teks (pre-filled)
- Total: input angka (pre-filled)
- New Broke Item: input angka (nilai ini akan **ditambahkan** ke repair yang sudah ada, bukan mengganti)
  - Label menampilkan nilai repair saat ini, contoh: "(currently: 3)"
- Tombol Update dan Cancel
- Data diambil via AJAX GET ke `/admin/items/{id}/edit`

**Validasi:**
- Category: wajib dipilih, harus ada di database
- Name: wajib diisi, maksimal 255 karakter
- Total: wajib, angka, min 0
- New Broke Item: opsional, angka, min 0

---

### 6.5 Halaman Admin Accounts (Admin)

**URL:** `/admin/users/admin`  
**Akses:** Admin only  
**Middleware:** `auth`, `role:admin`

**Deskripsi:**  
Halaman untuk mengelola akun pengguna dengan role admin. Admin dapat menambah, mengedit, menghapus akun admin, dan mengekspor data.

**Tabel Admin Accounts:**

| Kolom  | Keterangan              |
|--------|-------------------------|
| #      | Nomor urut              |
| Name   | Nama admin              |
| Email  | Email admin             |
| Action | Tombol Edit dan Delete  |

**Informasi Password:**  
Password default dibuat otomatis dengan format: **4 karakter pertama email + angka dari email**  
Contoh: email `budi123@gmail.com` → password: `budi123`

**Form Tambah Admin (Modal):**
- Role: dropdown (Admin / Operator)
- Name: input teks (wajib)
- Email: input email (wajib, unique)
- Password dibuat otomatis oleh sistem
- Tombol Save dan Cancel

**Form Edit Admin (Modal):**
- Name: input teks (pre-filled)
- Email: input email (pre-filled)
- New Password: input password (opsional)
- Tombol Update dan Cancel

**Konfirmasi Hapus:**  
Konfirmasi via `confirm()` browser sebelum menghapus.

**Notifikasi:**
- Setelah tambah/reset: muncul banner kuning dengan password yang digenerate
- Setelah operasi berhasil: muncul banner hijau

---

### 6.6 Halaman Operator Accounts (Admin)

**URL:** `/admin/users/operator`  
**Akses:** Admin only  
**Middleware:** `auth`, `role:admin`

**Deskripsi:**  
Halaman untuk mengelola akun pengguna dengan role operator. Admin dapat menambah, mereset password, menghapus akun operator, dan mengekspor data.

**Tabel Operator Accounts:**

| Kolom  | Keterangan                              |
|--------|-----------------------------------------|
| No     | Nomor urut                              |
| Name   | Nama operator                           |
| Email  | Email operator                          |
| Action | Tombol Reset Password dan Delete        |

**Form Tambah Operator (Modal):**
- Name: input teks (wajib)
- Email: input email (wajib, unique)
- Role otomatis: operator
- Password dibuat otomatis oleh sistem
- Tombol Save dan Cancel

**Modal Reset Password:**
- Konfirmasi reset password untuk operator tertentu
- Password baru dibuat otomatis (format sama: 4 karakter email + angka)
- Tombol Reset (kuning) dan Cancel

**Modal Hapus User:**
- Konfirmasi hapus akun operator
- Pesan: "Data tidak bisa dipulihkan."
- Tombol Delete (merah) dan Cancel

**Perbedaan dengan Admin Accounts:**
- Tidak ada tombol Edit (operator tidak bisa diedit nama/emailnya oleh admin)
- Ada tombol Reset Password (khusus operator)

---

### 6.7 Dashboard Operator

**URL:** `/operator/dashboard`  
**Akses:** Operator only  
**Middleware:** `auth`, `role:operator`

**Deskripsi:**  
Halaman dashboard utama untuk operator. Menampilkan pesan selamat datang dan ringkasan sistem.

**Komponen Halaman:**
- Sidebar navigasi operator (kiri)
- Judul: "Dashboard Operator"
- Pesan: "Selamat datang di panel operator, [nama operator]."
- Ringkasan sistem

**Navigasi Sidebar Operator:**
- Dashboard
- Items
- Lending
- My Account
- Logout

---

### 6.8 Halaman Items (Operator)

**URL:** `/operator/items`  
**Akses:** Operator only  
**Middleware:** `auth`, `role:operator`

**Deskripsi:**  
Halaman untuk melihat data item inventory. Operator hanya bisa melihat (read-only), tidak bisa menambah atau mengedit item.

**Tabel Items:**

| Kolom         | Keterangan                                    |
|---------------|-----------------------------------------------|
| #             | Nomor urut                                    |
| Category      | Nama kategori item                            |
| Name          | Nama item                                     |
| Total         | Jumlah total stok                             |
| Available     | Stok tersedia (Total - Repair)                |
| Lending Total | Jumlah item yang sedang dipinjam saat ini     |

**Catatan:** Kolom "Available" dihitung dengan rumus `max(0, total - repair)` untuk memastikan tidak negatif.

---

### 6.9 Halaman Lending (Operator)

**URL:** `/operator/lending`  
**Akses:** Operator only  
**Middleware:** `auth`, `role:operator`

**Deskripsi:**  
Halaman utama untuk mengelola data peminjaman item. Operator dapat menambah data lending, menandai item sebagai dikembalikan, menghapus data, dan mengekspor ke Excel.

**Tabel Lending:**

| Kolom      | Keterangan                                          |
|------------|-----------------------------------------------------|
| No         | Nomor urut                                          |
| Item       | Nama item yang dipinjam (bisa lebih dari satu)      |
| Total      | Jumlah masing-masing item yang dipinjam             |
| Name       | Nama peminjam                                       |
| Ket.       | Keterangan tambahan                                 |
| Date       | Tanggal peminjaman (format: dd Month, YYYY)         |
| Returned   | Status: tanggal kembali (hijau) / "not returned" (kuning) |
| Edited By  | Nama operator yang menginput data                   |
| Action     | Tombol Returned dan Delete                          |

**Tombol Aksi:**
- Export Excel (ungu): download data lending ke file .xlsx
- Add (hijau): buka modal tambah lending

**Form Tambah Lending (Modal):**

Sesuai tampilan pada gambar:

- **Name**: input teks nama peminjam (wajib)
- **Items**: dropdown pilih item (wajib, minimal 1)
- **Total**: input angka jumlah item (wajib, min 1)
- **More** (tombol biru): menambah baris item baru (bisa meminjam beberapa item sekaligus)
  - Setiap baris tambahan memiliki tombol × untuk menghapus baris
- **Ket.**: textarea keterangan (opsional)
- **Date**: input tanggal peminjaman (wajib)
- Tombol Submit (ungu) dan Cancel

**Validasi Lending:**
- Name: wajib diisi
- Item: wajib dipilih, minimal 1 item
- Total: wajib, angka, min 1
- Date: wajib, format tanggal valid
- Stok: jika total yang diminta melebihi stok → error "Total item more than available!"

**Proses Returned:**
- Klik tombol "Returned" pada baris lending yang belum dikembalikan
- Sistem otomatis menambah kembali stok item sesuai jumlah yang dipinjam
- Status lending berubah menjadi returned dengan tanggal pengembalian

**Proses Delete:**
- Klik tombol "Delete" → modal konfirmasi muncul
- Jika lending belum returned → stok item dikembalikan otomatis sebelum data dihapus
- Jika lending sudah returned → data langsung dihapus

---

### 6.10 Halaman My Account (Operator)

**URL:** `/operator/users`  
**Akses:** Operator only  
**Middleware:** `auth`, `role:operator`

**Deskripsi:**  
Halaman untuk operator melihat dan mengedit data akun mereka sendiri.

**Tabel Account:**

| Kolom  | Keterangan          |
|--------|---------------------|
| #      | Nomor (selalu 1)    |
| Name   | Nama operator       |
| Email  | Email operator      |
| Action | Tombol Edit         |

**Form Edit Account (Modal):**
- Name: input teks (pre-filled, wajib)
- Email: input email (pre-filled, wajib, unique)
- New Password: input password (opsional, min 4 karakter)
  - Jika diisi → password diperbarui, `password_plain` dihapus (null)
  - Jika kosong → password tidak berubah
- Tombol Submit (ungu) dan Cancel

---

## 7. DAFTAR ROUTE / URL

### Route Publik

| Method | URL      | Keterangan                    |
|--------|----------|-------------------------------|
| GET    | /        | Halaman welcome               |
| GET    | /login   | Tampilkan form login          |
| POST   | /login   | Proses login                  |

### Route Admin (Prefix: /admin, Middleware: auth + role:admin)

| Method | URL                              | Keterangan                        |
|--------|----------------------------------|-----------------------------------|
| GET    | /admin/dashboard                 | Dashboard admin                   |
| GET    | /admin/categories                | Daftar kategori                   |
| POST   | /admin/categories                | Tambah kategori                   |
| GET    | /admin/categories/{id}/edit      | Get data kategori (JSON/AJAX)     |
| PUT    | /admin/categories/{id}           | Update kategori                   |
| DELETE | /admin/categories/{id}           | Hapus kategori                    |
| GET    | /admin/items                     | Daftar items                      |
| POST   | /admin/items                     | Tambah item                       |
| GET    | /admin/items/{id}/edit           | Get data item (JSON/AJAX)         |
| PUT    | /admin/items/{id}                | Update item                       |
| DELETE | /admin/items/{id}                | Hapus item                        |
| GET    | /admin/items/export              | Export items ke Excel             |
| GET    | /admin/users/admin               | Daftar akun admin                 |
| GET    | /admin/users/admin/export        | Export akun admin ke Excel        |
| GET    | /admin/users/operator            | Daftar akun operator              |
| GET    | /admin/users/operator/export     | Export akun operator ke Excel     |
| POST   | /admin/users                     | Tambah user baru                  |
| GET    | /admin/users/{id}/edit           | Get data user (JSON/AJAX)         |
| PUT    | /admin/users/{id}                | Update user                       |
| POST   | /admin/users/{id}/reset-password | Reset password user               |
| DELETE | /admin/users/{id}                | Hapus user                        |

### Route Operator (Prefix: /operator, Middleware: auth + role:operator)

| Method | URL                                  | Keterangan                        |
|--------|--------------------------------------|-----------------------------------|
| GET    | /operator/dashboard                  | Dashboard operator                |
| GET    | /operator/items                      | Lihat daftar items                |
| GET    | /operator/lending                    | Daftar data lending               |
| POST   | /operator/lending                    | Tambah data lending               |
| POST   | /operator/lending/{id}/returned      | Tandai lending sebagai returned   |
| DELETE | /operator/lending/{id}               | Hapus data lending                |
| GET    | /operator/lending/export             | Export lending ke Excel           |
| GET    | /operator/users                      | Lihat akun sendiri                |
| PUT    | /operator/users/{id}                 | Update akun sendiri               |

### Route Lainnya (Middleware: auth)

| Method | URL      | Keterangan          |
|--------|----------|---------------------|
| GET    | /error   | Halaman error image |
| POST   | /logout  | Proses logout       |

---

## 8. FITUR EXPORT EXCEL

Sistem menyediakan fitur export data ke format Excel (.xlsx) menggunakan library **PhpSpreadsheet**.

### Export Items
- **URL:** `/admin/items/export`
- **Nama file:** `items_YYYYMMDD_HHMMSS.xlsx`
- **Kolom:** (sesuai data item di database)

### Export Lending
- **URL:** `/operator/lending/export`
- **Nama file:** `lendings_YYYYMMDD_HHMMSS.xlsx`
- **Kolom:** Item, Total, Name, Ket., Date, Return Date, Edited By

### Export Admin Accounts
- **URL:** `/admin/users/admin/export`
- **Nama file:** `admin-accounts_YYYYMMDD_HHMMSS.xlsx`
- **Kolom:** Name, Email, Password

### Export Operator Accounts
- **URL:** `/admin/users/operator/export`
- **Nama file:** `operator-accounts_YYYYMMDD_HHMMSS.xlsx`
- **Kolom:** Name, Email, Password

> **Catatan:** Kolom Password pada export akun berisi password plain text. Jika operator sudah mengubah password sendiri, kolom ini akan berisi teks: "This account already edited the password"

---

## 9. SISTEM AUTENTIKASI & ROLE

### Mekanisme Login
- Menggunakan `Auth::attempt()` Laravel bawaan
- Session-based authentication
- Setelah login berhasil, session di-regenerate untuk keamanan

### Role-Based Access Control
Sistem menggunakan middleware custom `role` untuk membatasi akses:

```
Middleware: role:admin   → hanya user dengan role 'admin'
Middleware: role:operator → hanya user dengan role 'operator'
```

### Sistem Password Default
Saat admin membuat akun baru, password dibuat otomatis dengan format:
```
password = [4 karakter pertama email] + [semua angka dalam email]
```

**Contoh:**
- Email: `budi123@gmail.com` → Password: `budi123`
- Email: `admin001@sekolah.sch.id` → Password: `admi001`

### Reset Password (Khusus Operator)
Admin dapat mereset password operator ke password default (format di atas).  
Setelah reset, password plain tersimpan di kolom `password_plain` untuk keperluan export.

### Ubah Password Sendiri (Operator)
Operator dapat mengubah password mereka sendiri melalui halaman My Account.  
Jika password diubah sendiri, kolom `password_plain` akan dikosongkan (null).

### Logout
- Method: POST ke `/logout`
- Session di-invalidate dan token di-regenerate
- Redirect ke halaman `/`

---

*Dokumentasi ini dibuat untuk sistem Inventory Management berbasis Laravel.*  
*© 2026 Inventory Management. All rights reserved.*
