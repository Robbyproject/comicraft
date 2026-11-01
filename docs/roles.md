# Roles dan Hak Akses

## Guest

- Dapat membuka beranda, login, dan registrasi.
- Route katalog dan fitur akun lain meminta autentikasi.

## Buyer

- Melihat katalog, halaman fandom, dan showcase karakter.
- Mengubah profil sendiri.
- Membuat pre-order dan melihat pesanan sendiri.
- Membuat tiket bantuan dan mengaitkannya hanya dengan pre-order milik sendiri.
- Tidak memiliki akses ke route `/admin`; middleware role mengembalikan HTTP 403.

## Creator

- Dapat menggunakan halaman yang tersedia untuk user login.
- Memiliki dashboard operasional dan daftar pre-order.
- Mengelola kategori fandom dan merchandise.
- Mengubah status pesanan, memverifikasi PIN pickup, dan memasukkan nomor resi.
- Membalas tiket support.
- Membooking booth event dan melihat transaksi.

## Implementasi route

- Route buyer berada di dalam middleware `auth`.
- Route creator menggunakan middleware `auth` dan `role:creator`.
- Role didaftarkan sebagai alias `role` di `bootstrap/app.php`, dengan pemeriksaan di `app/Http/Middleware/EnsureUserRole.php`.
- Registrasi membatasi role ke `creator` dan `buyer`.

Data akun demo bersifat publik untuk keperluan tugas. Jangan memakai kredensial demo sebagai akun produksi.
