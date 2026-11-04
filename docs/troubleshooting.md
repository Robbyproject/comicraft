# Troubleshooting

## Gambar merchandise menampilkan 404 setelah deployment

- Pastikan `MERCHANDISE_DISK=supabase` pada environment hosting.
- Pastikan bucket Supabase yang digunakan ada dan bersifat **Public** jika URL gambar akan dibuka tanpa autentikasi.
- Pastikan `SUPABASE_S3_ENDPOINT`, region, nama bucket, access key, dan secret berasal dari **Storage → Settings → S3 Connection**.
- Pastikan `SUPABASE_STORAGE_PUBLIC_URL` memakai format `https://<project-ref>.supabase.co/storage/v1/object/public/<bucket>`.
- Simpan variabel sebagai secrets/environment variables server dan redeploy agar konfigurasi terbaru termuat.
- File lama yang sebelumnya disimpan pada filesystem deployment Wasmer tidak otomatis berpindah ke Supabase. Upload kembali file yang hilang.

## Upload gagal pada environment lokal

- Periksa batas ukuran gambar dan aturan validasi upload di dashboard creator.
- Jika PHP melaporkan `UPLOAD_ERR_NO_TMP_DIR` (kode 6), proses PHP tidak menemukan direktori sementara upload. Pastikan `upload_tmp_dir` ada dan writable untuk proses PHP yang menjalankan web server.
- Perubahan `php.ini` tidak berlaku pada proses server yang sudah berjalan; restart server setelah konfigurasi diubah.

## Build Composer gagal karena versi PHP

- Proyek mensyaratkan PHP `^8.3`; lock file menggunakan platform Composer `8.3.29`.
- Instal dependency dengan `composer install` menggunakan `composer.lock` yang sudah dicommit.
- Jangan mengedit `composer.lock` secara manual. Jalankan update dependency dengan constraint platform yang benar, kemudian tes sebelum deploy.

## Tes gagal atau database lokal perlu dibuat ulang

```bash
php artisan test --compact
```

Untuk database development yang boleh dihapus dan dibuat ulang:

```bash
php artisan migrate:fresh --seed
```

> `migrate:fresh` menghapus semua tabel pada koneksi database yang sedang aktif. Periksa environment sebelum menjalankannya dan jangan gunakan di deployment yang berisi data penting.

## Data creator masih terlihat di akun lain

- Pastikan migrasi terbaru sudah dijalankan dengan `php artisan migrate` pada environment yang benar.
- Merchandise lama dan tiket umum tanpa PO dialihkan ke akun creator paling awal yang terdaftar. Tiket yang terkait PO dialihkan ke creator pemilik merchandise tersebut.
- Pesanan mengikuti creator pemilik merchandise. Tiket baru untuk PO otomatis diarahkan ke creator produk; tiket umum meminta buyer memilih creator.
- Jangan menjalankan `migrate:fresh` pada database deployment untuk memperbaiki ownership karena perintah itu menghapus seluruh data.

## Deployment menampilkan error Laravel

- Pastikan `APP_KEY` tersedia, `APP_DEBUG=false` pada deployment publik, dan koneksi database benar.
- Jangan commit `.env` atau kredensial storage ke repository.
- Periksa log deployment dan log Laravel untuk pesan error lengkap; jangan menampilkan secret saat meminta bantuan.
