# Struktur Proyek

```text
app/
  Http/
    Controllers/
      Admin/                 Dashboard dan proses creator
      Buyer/                 Katalog, pre-order, bantuan
      AuthController.php     Login, registrasi, logout
      ProfileController.php  Pengelolaan profil
    Middleware/              Pemeriksaan role pengguna
  Models/                     Model Eloquent dan relasi domain
bootstrap/                     Inisialisasi aplikasi dan middleware
config/
  database.php                Koneksi database
  filesystems.php             Disk lokal, S3, dan Supabase
database/
  factories/                  Factory untuk data tes
  migrations/                 Skema database
  seeders/                    Data demo
public/                        File publik statis dan entry point
resources/
  css/                         Gaya aplikasi
  js/                          JavaScript frontend
  views/
    admin/                     Halaman creator
    auth/                      Login dan registrasi
    buyer/                     Halaman buyer
routes/
  web.php                      Route web dan middleware grup
tests/
  Feature/                     Tes alur HTTP dan fitur
docs/                          Dokumentasi teknis
```

## Domain model utama

- `User`: akun, role, profil, dan avatar.
- `FandomCategory`: kategori fandom serta banner.
- `Merchandise`: item katalog, harga, kuota, dan path gambar.
- `Preorder`: transaksi pemesanan, pembayaran simulasi, status produksi, dan pengambilan.
- `SupportMessage`: tiket bantuan buyer dan balasan creator.
- `ConventionEvent`: data event konvensi.
- `BoothRental`: inventaris booth dan booking creator.

## Konfigurasi storage

- Disk `public` adalah penyimpanan lokal default untuk development, termasuk avatar.
- `supabase` memakai adapter S3-compatible.
- `MERCHANDISE_DISK` memilih disk untuk file gambar merchandise dan default-nya `public`.
- Bucket Supabase publik hanya untuk file katalog yang memang perlu ditampilkan tanpa login; kredensial S3 hanya berada di backend.
