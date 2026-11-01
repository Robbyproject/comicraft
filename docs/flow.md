# Flow Aplikasi

## Autentikasi

1. Pengunjung membuka halaman login atau registrasi.
2. Setelah login, role `creator` diarahkan ke dashboard creator.
3. Role `buyer` diarahkan ke katalog.
4. Route katalog, profil, pesanan, pre-order, dan bantuan membutuhkan login.
5. Route dengan prefix `/admin` juga memerlukan middleware `role:creator`.

## Pre-order buyer

1. Buyer membuka katalog, halaman fandom, atau showcase.
2. Buyer memilih merchandise lalu mengisi jumlah, metode pengambilan (`booth_pickup` atau `shipping`), skema bayar (`full_payment` atau `down_payment`), dan catatan opsional.
3. Aplikasi memeriksa kuota yang tersisa berdasarkan pesanan yang belum dibatalkan.
4. Aplikasi menghitung total, jumlah pembayaran, sisa pembayaran, fee platform 1.5%, dan pendapatan bersih creator.
5. Pesanan dibuat dengan kode PO dan PIN pickup enam digit.
6. Buyer melihat daftar pesanan dan dapat membuat tiket bantuan yang terkait dengan PO miliknya.
7. Creator mengubah status produksi melalui dashboard.
8. Untuk menyelesaikan pickup booth, creator memverifikasi PIN. Untuk shipping, nomor resi harus tersedia.
9. Status pembayaran simulasi berubah menjadi `released_to_creator` setelah pesanan diselesaikan.

### Status pre-order

`pending` → `producing` → `ready_at_booth` → `completed`

Pesanan dapat menjadi `cancelled`; status ini tidak lagi menggunakan kuota produksi.

> Pembayaran dan escrow belum terhubung ke payment gateway. Nilai status dan saldo adalah simulasi aplikasi.

## Bantuan pelanggan

1. Buyer mengirim tiket dengan subjek, pesan, dan pilihan PO opsional.
2. Server memvalidasi bahwa PO yang dilampirkan memang milik buyer yang login.
3. Tiket dibuat dengan status `waiting_reply`.
4. Creator membalas dari halaman support; status menjadi `resolved`.

## Booking booth

1. Creator memilih booth dengan status `available`.
2. Creator mengisi nama circle, PIC, nomor WhatsApp, rencana merchandise, dan metode pembayaran.
3. Aplikasi membuat nomor invoice dan mencatat booking sebagai `paid_confirmed`.
4. Riwayat ditampilkan pada halaman transaksi creator.

> Pembayaran booth juga merupakan simulasi, bukan transaksi gateway nyata.
