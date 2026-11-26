# FlipPlay REST API — Backend Service (Laravel 12)

Proyek ini adalah implementasi **REST API** untuk sistem katalog dan manajemen koleksi media streaming **FlipPlay** (Anime & Manga), dibangun menggunakan **Laravel 12**. Proyek ini melanjutkan arsitektur Object-Oriented Programming (OOP) dari Modul 3 dan mengintegrasikannya dengan database relasional, Eloquent ORM, Form Request Validation, API Resource, dan Custom Action.

Repository: [https://github.com/viperuuu/project_laravel_web](https://github.com/viperuuu/project_laravel_web)

---

## 📋 Pemenuhan Rubrik Penilaian Demo (Modul 4)

| No | Aspek Penilaian (Rubrik Demo) | Bobot | Status & Implementasi |
|:--:|---|:---:|---|
| 1 | **CRUD API Lengkap** (index, show, store, update, destroy) dengan status code tepat (200, 201) | 20% |  **Terpenuhi** (`MediaController` menangani CRUD katalog media lengkap dengan status HTTP tepat) |
| 2 | **Eloquent Relationship** (migration, relasi antar entitas, eager loading) | 20% |  **Terpenuhi** (Relasi Many-to-Many `Media` ⇄ `User` via pivot table `media_user`, dengan Eager Loading `with()` & `load()`) |
| 3 | **Form Request Validation** & penanganan error 422 | 15% |  **Terpenuhi** (`StoreMediaRequest` & `AddMediaToCollectionRequest` memvalidasi input POST/PATCH secara ketat dengan respons 422 Unprocessable Entity) |
| 4 | **API Resource** (format response konsisten & `whenLoaded`) | 10% |  **Terpenuhi** (`MediaResource` dan `UserResource` menggunakan `whenLoaded()` dan `whenPivotLoaded()` untuk konsistensi JSON) |
| 5 | **Custom Action di luar CRUD standar** (attach / detach relasi) | 10% |  **Terpenuhi** (`UserCollectionController@store` untuk attach koleksi dengan validasi duplikasi 409 Conflict, dan `@destroy` untuk detach) |
| 6 | **Pengujian Endpoint** (Happy Path & Failure Path) | 5% |  **Terpenuhi** (17 Automated Tests lulus 100% via PHPUnit/Pest & siap diuji via Postman) |
| 7 | **Tugas Codelab** (Peminjaman Buku Perpustakaan) | 10% |  **Terpenuhi** (`Book` & `Member` relationship `borrow-book` & `return-book` lengkap) |

---

## 🚀 Cara Menjalankan Proyek

### 1. Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- SQLite / MySQL

### 2. Instalasi & Menjalankan Database
```bash
# Clone repository
git clone https://github.com/viperuuu/project_laravel_web.git
cd project_laravel_web

# Install dependencies (jika baru di-clone)
composer install

# Setup file environment
cp .env.example .env
php artisan key:generate

# Jalankan migrasi dan seeder data awal
php artisan migrate --seed

# Jalankan development server
php artisan serve
```
Server akan berjalan di `http://127.0.0.1:8000`.

### 3. Menjalankan Pengujian Otomatis
```bash
php artisan test
```
*Hasil: Seluruh 17 test cases lulus (Pass).*

---

## 📡 Daftar Endpoint REST API (FlipPlay)

### 1. Katalog Media (CRUD)

| Method | Endpoint | Deskripsi | Query / Body Param | Status Code |
|---|---|---|---|:---:|
| `GET` | `/api/media` | Menampilkan seluruh katalog media | `?title=Hutan` (pencarian), `?type=anime` atau `manga` | `200 OK` |
| `POST` | `/api/media` | Menambahkan media baru | JSON: `title`, `type`, `genre`, `creator`, `release_year`, `description` | `201 Created` |
| `GET` | `/api/media/{id}` | Detail media + daftar pengguna yang mengoleksi | - | `200 OK` |
| `PATCH` / `PUT` | `/api/media/{id}` | Memperbarui data media | JSON field yang ingin diubah | `200 OK` |
| `DELETE` | `/api/media/{id}` | Menghapus media dari katalog | - | `200 OK` |

### 2. Koleksi Media Pengguna (Custom Actions - Attach / Detach)

| Method | Endpoint | Deskripsi | Body / Parameter | Status Code |
|---|---|---|---|:---:|
| `GET` | `/api/users/{user_id}/collections` | Menampilkan daftar media yang dikoleksi user | - | `200 OK` |
| `POST` | `/api/users/{user_id}/collections` | Menambahkan media ke koleksi (**Attach**) | `{"media_id": 1, "status": "watching"}` | `201 Created` |
| `POST` | `/api/users/{user_id}/collections` | **Failure Path**: Menambah media yang sudah ada | `{"media_id": 1}` (sudah ada) | `409 Conflict` |
| `DELETE` | `/api/users/{user_id}/collections/{media_id}` | Menghapus media dari koleksi (**Detach**) | - | `200 OK` |

### 3. Codelab Perpustakaan (Opsional / Bonus)

| Method | Endpoint | Deskripsi |
|---|---|---|
| `POST` | `/api/members/{id}/borrow-book` | Pinjam buku (`{"book_id": 1}`) |
| `GET` | `/api/members/{id}` | Detail member dan buku yang sedang dipinjam |
| `PATCH` | `/api/members/{id}/return-book/{book_id}` | Kembalikan buku |

---

## 🧪 Panduan Skenario Pengujian (Postman)

### Skenario 1: Tampilkan Katalog Media & Pencarian (Happy Path)
- **URL**: `GET http://127.0.0.1:8000/api/media?title=Hutan`
- **Hasil**: Menampilkan data anime "Hutan Cahaya" dalam format JSON terstruktur.

### Skenario 2: Tambah Media Baru (Happy Path)
- **URL**: `POST http://127.0.0.1:8000/api/media`
- **Headers**: `Content-Type: application/json`, `Accept: application/json`
- **Body**:
  ```json
  {
    "title": "Sousou no Frieren Season 2",
    "type": "anime",
    "genre": "Fantasi, Drama",
    "creator": "Madhouse",
    "release_year": 2025,
    "description": "Kelanjutan perjalanan Frieren menuju Aureole."
  }
  ```
- **Response**: `201 Created`

### Skenario 3: Uji Validasi Input Kosong / Duplikat (Failure Path)
- **URL**: `POST http://127.0.0.1:8000/api/media`
- **Headers**: `Accept: application/json`
- **Body**:
  ```json
  {
    "title": "",
    "type": "game",
    "genre": ""
  }
  ```
- **Response**: `422 Unprocessable Entity` beserta pesan kesalahan validasi field dalam bahasa Indonesia.

### Skenario 4: Tambah ke Koleksi Pengguna (Custom Action - Attach)
- **URL**: `POST http://127.0.0.1:8000/api/users/1/collections`
- **Body**:
  ```json
  {
    "media_id": 3,
    "status": "watching"
  }
  ```
- **Response**: `201 Created` dengan pesan "Media berhasil ditambahkan ke koleksi pengguna."

### Skenario 5: Uji Duplikasi Koleksi Pengguna (Failure Path)
- Eksekusi request yang sama pada Skenario 4 sekali lagi.
- **Response**: `409 Conflict` dengan pesan "Media ini sudah ada di dalam daftar koleksi Anda."

### Skenario 6: Hapus dari Koleksi Pengguna (Custom Action - Detach)
- **URL**: `DELETE http://127.0.0.1:8000/api/users/1/collections/3`
- **Response**: `200 OK` dengan pesan "Media berhasil dihapus dari koleksi pengguna."
