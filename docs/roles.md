# Roles dan Hak Akses

## Guest

- Dapat membuka beranda, login, dan registrasi.
- Route katalog dan fitur akun lain meminta autentikasi.

## Buyer

- Melihat katalog, halaman fandom, dan showcase karakter.
- Mengubah profil sendiri.
- Membuat pre-order dan melihat pesanan sendiri.
- Membuat tiket bantuan, mengaitkannya hanya dengan pre-order milik sendiri, atau memilih creator tujuan untuk pertanyaan umum.
- Melihat tiket bantuan dan balasan hanya milik akun sendiri di halaman pesanan.
- Tidak memiliki akses ke route `/admin`; middleware role mengembalikan HTTP 403.

## Creator

- Dapat menggunakan halaman yang tersedia untuk user login.
- Memiliki dashboard operasional yang hanya menampilkan data creator tersebut.
- Mengelola merchandise miliknya sendiri; katalog publik menampilkan produk dari semua creator.
- Melihat dan mengubah status pesanan hanya untuk merchandise miliknya, termasuk verifikasi PIN pickup dan nomor resi.
- Membalas tiket yang dialamatkan kepadanya.
- Membooking booth event serta melihat booking dan transaksi miliknya sendiri.
- Menggunakan kategori fandom bersama sebagai taksonomi katalog.

## Implementasi route

- Route buyer berada di dalam middleware `auth`.
- Route creator menggunakan middleware `auth` dan `role:creator`.
- Role didaftarkan sebagai alias `role` di `bootstrap/app.php`, dengan pemeriksaan di `app/Http/Middleware/EnsureUserRole.php`.
- Registrasi membatasi role ke `creator` dan `buyer`.

Data akun demo bersifat publik untuk keperluan tugas. Jangan memakai kredensial demo sebagai akun produksi.
