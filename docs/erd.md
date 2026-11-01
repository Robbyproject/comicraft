# ERD ComiCraft

Diagram menggambarkan tabel domain yang dibuat oleh migration proyek. Field timestamp standar (`created_at`, `updated_at`) tidak ditampilkan agar diagram tetap ringkas.

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        enum role
        string phone_number
        string avatar
    }
    FANDOM_CATEGORIES {
        bigint id PK
        string name
        string slug UK
        string banner
    }
    MERCHANDISES {
        bigint id PK
        bigint fandom_category_id FK
        string name
        enum item_type
        uint price
        uint stock_quota
        string image
    }
    PREORDERS {
        bigint id PK
        string preorder_code UK
        string pickup_secret_pin
        bigint user_id FK
        bigint merchandise_id FK
        uint quantity
        uint total_price
        enum pickup_method
        string tracking_number
        enum payment_scheme
        uint amount_paid
        uint remaining_balance
        uint platform_fee
        uint net_creator_income
        enum payment_status
        enum status
        text notes
    }
    SUPPORT_MESSAGES {
        bigint id PK
        bigint user_id FK
        bigint preorder_id FK
        string subject
        text message
        text admin_reply
        enum status
    }
    CONVENTION_EVENTS {
        bigint id PK
        string name
        string venue
        date event_date
        enum status
    }
    BOOTH_RENTALS {
        bigint id PK
        string invoice_number UK
        bigint convention_event_id FK
        bigint creator_id FK
        string circle_name
        string pic_name
        string whatsapp_number
        string merchandise_plan
        string booth_number
        enum booth_type
        uint base_price
        uint platform_fee
        uint total_rental_cost
        string payment_method
        enum rental_status
        timestamp paid_at
        text notes
    }

    USERS ||--o{ PREORDERS : creates
    MERCHANDISES ||--o{ PREORDERS : ordered_as
    FANDOM_CATEGORIES ||--o{ MERCHANDISES : groups
    USERS ||--o{ SUPPORT_MESSAGES : opens
    PREORDERS o|--o{ SUPPORT_MESSAGES : related_to
    CONVENTION_EVENTS ||--o{ BOOTH_RENTALS : offers
    USERS o|--o{ BOOTH_RENTALS : rents
```

## Relasi

| Relasi | Makna |
| --- | --- |
| `fandom_categories` 1 — N `merchandises` | Satu kategori memiliki banyak produk; menghapus kategori menghapus produk terkait (`cascadeOnDelete`). |
| `users` 1 — N `preorders` | Buyer dapat membuat banyak pesanan; akun yang dihapus akan menghapus pesanannya (`cascadeOnDelete`). |
| `merchandises` 1 — N `preorders` | Produk dapat muncul di banyak pesanan; penghapusan produk menghapus pesanan terkait (`cascadeOnDelete`). |
| `users` 1 — N `support_messages` | Buyer dapat membuat banyak tiket; tiket terhapus jika pemilik akun dihapus. |
| `preorders` 0..1 — N `support_messages` | Tiket dapat tidak terkait pesanan; penghapusan pesanan mengosongkan `preorder_id` (`nullOnDelete`). |
| `convention_events` 1 — N `booth_rentals` | Event memiliki daftar booth; penghapusan event menghapus booth terkait. |
| `users` 0..1 — N `booth_rentals` | Booth bisa tersedia tanpa creator; penghapusan akun mengosongkan `creator_id`. |

`image`, `avatar`, dan `banner` adalah path/URL ke file storage, bukan kolom biner gambar. Tabel bawaan Laravel untuk cache, job, session, dan reset password tidak ditampilkan.
