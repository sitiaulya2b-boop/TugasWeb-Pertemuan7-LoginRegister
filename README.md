# Sistem Login/Register - PHP Native

Tugas Rutin 7 — Pemrograman Web (Pertemuan 7: Fundamental Server-Side PHP)

Sistem autentikasi sederhana (register, login, dashboard terproteksi, logout) dibangun dengan **PHP Native** (tanpa framework) dan file JSON sebagai penyimpanan data - tanpa database.

## Fitur

1. Form registrasi dengan validasi (nama, email, password)
2. Validasi email dengan `filter_var()`
3. Password di-hash dengan `password_hash()` (bcrypt) — tidak pernah disimpan plain text
4. Data user disimpan di `data/users.json`
5. Cek duplikasi email saat registrasi
6. Sistem login dengan `$_SESSION`
7. Dashboard yang diproteksi — redirect otomatis ke `login.php` kalau belum login
8. Logout functionality (`session_destroy()`)
9. Sanitasi input dengan `htmlspecialchars()` di semua output
10. Pesan error & sukses yang jelas di setiap form
11. **Bonus:** "Remember Me" pakai cookie ber-`httpOnly`, halaman edit profil, dan tampilan terminal yang rapi

## Struktur File

```
login-register-app/
├── includes/
│   ├── functions.php   # baca/tulis users.json, cari user
│   └── auth.php        # proteksi halaman + auto-login via cookie remember me
├── data/
│   └── users.json      # "database" — 2 akun contoh sudah disediakan
├── index.php            # redirect otomatis ke dashboard/login
├── register.php
├── login.php
├── dashboard.php        # halaman terproteksi
├── edit-profile.php     # bonus — ubah nama/password
├── logout.php
├── style.css
└── README.md
```

## Cara Menjalankan

Butuh PHP terpasang di komputer (disarankan lewat **Laragon** atau **XAMPP**, sudah termasuk Apache + PHP).

**Opsi 1 - PHP built-in server (paling cepat):**

```bash
cd login-register-app
php -S localhost:8000
```

Buka `http://localhost:8000` di browser.

**Opsi 2 - Laragon/XAMPP:**
Taruh folder ini di `www/` (Laragon) atau `htdocs/` (XAMPP), lalu buka `http://localhost/login-register-app`.

> Pastikan folder `data/` bisa ditulis (writable) oleh PHP, karena di situlah `users.json` diperbarui setiap ada registrasi/edit profil.

## Akun Contoh (di `data/users.json`)

| Email                 | Password        |
| --------------------- | --------------- |
| `adid@unimed.ac.id` | `password123` |
| `budi@example.com`  | `password123` |

Password di file JSON sudah dalam bentuk hash (bcrypt) - bukan `"password123"` mentah. Dua akun ini bisa langsung dipakai untuk tes login tanpa perlu daftar dulu.

## Alur Aplikasi

```
register.php → simpan ke users.json → login.php → dashboard.php (terproteksi) → logout.php
```

- Belum login lalu coba buka `dashboard.php` atau `edit-profile.php` → otomatis di-redirect ke `login.php`.
- Login dengan centang "Ingat saya" → cookie `remember_token` (httpOnly, 30 hari) dibuat, jadi walau session browser habis, kamu tetap otomatis login lewat `includes/auth.php`.
- Logout → session dihapus, cookie remember me dihapus, dan token di `users.json` di-null-kan supaya cookie lama tidak bisa dipakai lagi.

## Push ke GitHub

```bash
git init
git add .
git commit -m "Tugas Rutin 7: Sistem Login/Register PHP Native"
git branch -M main
git remote add origin https://github.com/<username>/TugasWeb-Pertemuan7-LoginRegister.git
git push -u origin main
```

Pastikan repository dibuat **Public** dengan nama `TugasWeb-Pertemuan7-LoginRegister`, dan `data/users.json` ikut ter-push (ini beda dari tugas Weather App - di sini file datanya justru **wajib** disertakan sebagai contoh, bukan disembunyikan). Setelah itu kumpulkan link repo-nya lewat LMS UNIMED.
