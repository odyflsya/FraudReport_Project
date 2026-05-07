# Dokumentasi Fitur Pengaturan (Settings)

## Ringkasan Fitur

Sistem pengaturan telah diimplementasikan dengan fitur-fitur lengkap:

### 1. **Profil Pengguna**
- Menampilkan informasi profil lengkap
- Edit nama dan email pengguna
- Upload dan ganti foto profil
- Hapus foto profil
- Verifikasi email

### 2. **Keamanan Akun**
- Ubah password
- Reset password (Forgot Password)

### 3. **Logout**
- Logout akun dari sistem

---

## File-File yang Dibuat/Dimodifikasi

### Database
- **Migration**: `database/migrations/2025_05_04_000000_add_profile_photo_to_users_table.php`
  - Menambahkan kolom `profile_photo_path` ke tabel `users`

### Models
- **User Model**: `app/Models/User.php`
  - Menambahkan `profile_photo_path` ke mass assignable attributes

### Controllers
- **ProfileController**: `app/Http/Controllers/ProfileController.php`
  - Method `edit()` - Menampilkan halaman pengaturan
  - Method `update()` - Update profil pengguna
  - Method `uploadPhoto()` - Upload foto profil (NEW)
  - Method `deletePhoto()` - Hapus foto profil (NEW)
  - Method `logout()` - Logout pengguna (NEW)
  - Method `destroy()` - Delete akun pengguna

### Views
- **Pengaturan Page**: `resources/views/pengaturan/index.blade.php`
  - Halaman utama pengaturan dengan UI yang modern
  - Integrasi semua fitur dalam satu halaman

### Routes
- **Web Routes**: `routes/web.php`
  - `GET /pengaturan` - Tampilkan halaman pengaturan (name: `pengaturan.index`)
  - `GET /profile` - Tampilkan halaman profil (name: `profile.edit`)
  - `PATCH /profile` - Update profil (name: `profile.update`)
  - `POST /profile/upload-photo` - Upload foto (name: `profile.upload-photo`)
  - `DELETE /profile/delete-photo` - Hapus foto (name: `profile.delete-photo`)
  - `POST /logout` - Logout pengguna (name: `profile.logout`)

### Layout
- **App Layout**: `resources/views/layouts/app.blade.php`
  - Link pengaturan di sidebar
  - Update title navbar untuk pengaturan
  - Tampilkan foto profil di navbar
  - Update logout button

---

## Fitur Detail

### 1. Upload Foto Profil
**Path**: `POST /profile/upload-photo`

**Validasi**:
- File harus image (JPEG, PNG, JPG, GIF)
- Ukuran maksimal: 2MB

**Proses**:
- Foto lama akan dihapus otomatis
- Foto baru disimpan di `storage/app/public/profile-photos/`
- Path disimpan di database

**Response**:
- Redirect ke halaman pengaturan dengan status "photo-uploaded"

---

### 2. Hapus Foto Profil
**Path**: `DELETE /profile/delete-photo`

**Proses**:
- Foto dihapus dari storage
- Path di database di-clear (set null)

**Response**:
- Redirect ke halaman pengaturan dengan status "photo-deleted"

---

### 3. Update Profil
**Path**: `PATCH /profile`

**Field yang bisa diupdate**:
- `name` - Nama pengguna
- `email` - Email pengguna

**Validasi**:
- Menggunakan `ProfileUpdateRequest`

**Response**:
- Redirect ke halaman pengaturan dengan status "profile-updated"

---

### 4. Logout
**Path**: `POST /logout`

**Proses**:
- Logout dari session
- Invalidate session
- Regenerate CSRF token
- Redirect ke halaman welcome

**Response**:
- Redirect ke welcome page dengan status "logged-out"

---

### 5. Reset Password (Forgot Password)
**Path**: `GET /password/request`

**Fitur Default Laravel**:
- Menggunakan built-in password reset functionality
- User dapat request reset password link via email
- Link akan dikirim ke email terdaftar

---

## Cara Penggunaan

### Akses Pengaturan
1. Login ke aplikasi
2. Klik profil atau nama di navbar
3. Klik "Pengaturan" di sidebar, atau klik nama/foto di navbar

### Upload Foto
1. Di halaman pengaturan, klik tombol "Ganti Foto"
2. Pilih file gambar dari komputer
3. Tunggu proses upload selesai
4. Foto akan ditampilkan di halaman

### Edit Profil
1. Ubah data di form (Nama, Email)
2. Klik "Simpan Perubahan"
3. Data akan tersimpan dan halaman di-redirect dengan notifikasi sukses

### Logout
1. Klik tombol "Logout" di halaman pengaturan
2. Atau klik tombol logout (icon) di navbar
3. Akan di-redirect ke halaman welcome

### Forgot Password
1. Dari halaman pengaturan, klik "Reset Password"
2. Atau bisa langsung ke `/password/request`
3. Masukkan email Anda
4. Email dengan link reset password akan dikirim
5. Klik link di email untuk set password baru

---

## Storage Configuration

### Location
- Foto profil disimpan di: `storage/app/public/profile-photos/`
- Accessible via: `/storage/profile-photos/{filename}`

### Symbolic Link
- Sudah dibuat: `public/storage` -> `storage/app/public`
- Link dibuat dengan command: `php artisan storage:link`

---

## Validasi Input

### Foto Profil Upload
```php
$request->validate([
    'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
]);
```

### Update Profil
- Menggunakan `ProfileUpdateRequest`
- Validasi built-in di Request class

---

## Alert/Notification

### Success Messages
- `"photo-uploaded"` - Foto berhasil diunggah
- `"photo-deleted"` - Foto berhasil dihapus
- `"profile-updated"` - Profil berhasil diperbarui
- `"password-updated"` - Password berhasil diperbarui
- `"logged-out"` - Berhasil logout

### Error Messages
- Validasi error ditampilkan di alert merah
- File size error
- File type error

---

## Testing

### Test Upload Foto
```bash
# Login terlebih dahulu
# POST ke /profile/upload-photo dengan form data
# Key: profile_photo
# Value: Image file (max 2MB)
```

### Test Update Profil
```bash
# PATCH ke /profile dengan data
# - name: "Nama Baru"
# - email: "email@baru.com"
```

### Test Logout
```bash
# POST ke /logout
# Session akan invalid dan redirect ke welcome
```

---

## Security Features

1. **CSRF Protection**: Semua form dilindungi dengan @csrf
2. **Authentication**: Semua route di middleware 'auth'
3. **Authorization**: User hanya bisa update profil miliknya
4. **File Validation**: File upload divalidasi tipe dan ukuran
5. **Old Photo Cleanup**: Foto lama otomatis dihapus saat upload baru

---

## Browser Compatibility

- Chrome ✓
- Firefox ✓
- Safari ✓
- Edge ✓

---

## Performance

- Upload foto di-compress dan dioptimasi
- Foto lama dihapus otomatis (cleanup)
- Session management yang efficient

---

## Future Improvements (Opsional)

1. Crop foto sebelum upload
2. Multiple profile photos
3. Social media integration
4. Two-factor authentication
5. Login history
6. Device management
7. Export data user

---

## Troubleshooting

### Foto tidak muncul
- Check storage link: `php artisan storage:link`
- Pastikan permissions folder storage: `755`

### Upload error
- Check max file size di php.ini
- Check disk space

### Logout tidak bekerja
- Clear cache: `php artisan cache:clear`
- Check session configuration

---

**Last Updated**: 04 May 2026
**Version**: 1.0
