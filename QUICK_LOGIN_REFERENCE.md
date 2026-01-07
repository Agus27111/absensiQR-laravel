# 🔐 Multi-Tenant Quick Login Reference

## 📋 Login Credentials

### 👑 Super Admin (Monitoring)

```
URL: http://localhost:8000/
Username: superadmin
Password: superadmin123
Role: Dashboard Monitoring Semua Sekolah
```

### 🏫 Tenant Admin 1 (SMA Negeri 1)

```
Username: admin_sma1
Password: admin123
Sekolah: SMA Negeri 1 (NPSN: 20212345)
Siswa: 50 orang
Status: Aktif
```

### 🏭 Tenant Admin 2 (SMKN 2 Bandung)

```
Username: admin_smkn2
Password: admin456
Sekolah: SMKN 2 Bandung (NPSN: 20219876)
Siswa: 30 orang
Status: Aktif
```

### 🎓 Demo User

```
Username: demo
Password: demo123
Sekolah: SMA Negeri 1 (sama dengan admin_sma1)
Status: Aktif
```

---

## 🎯 Expected Dashboard Behavior

### Super Admin Dashboard

```
┌─────────────────────────────────────────┐
│  Dashboard Monitoring                    │
├─────────────────────────────────────────┤
│ Total Sekolah: 2                        │
│ Sekolah Aktif: 2                        │
│ Kadaluarsa: 0                           │
│ Tertunda: 0                             │
├─────────────────────────────────────────┤
│ Total Siswa: 80                         │
│ Admin Sekolah: 3                        │
├─────────────────────────────────────────┤
│ Tabel:                                  │
│ - SMA Negeri 1: 50 siswa | Aktif       │
│ - SMKN 2 Bandung: 30 siswa | Aktif    │
└─────────────────────────────────────────┘
```

### Tenant Admin Dashboard

```
┌─────────────────────────────────────────┐
│  Dashboard Beranda (Admin SMA 1)         │
├─────────────────────────────────────────┤
│ Absensi Masuk: XX siswa                 │
│ Absensi Terlambat: XX siswa             │
│ Absensi Alpa: XX siswa                  │
├─────────────────────────────────────────┤
│ Total Siswa: 50                         │
│ Siswa Hari Ini: XX                      │
└─────────────────────────────────────────┘
```

---

## 📱 Sidebar Menu Per Role

### Super Admin Menu

```
✓ Beranda (Dashboard Monitoring)
✓ Murid (Daftar Murid - semua sekolah)
✗ Scan QR (disabled)
✗ Input Murid (disabled)
✗ Kelas (disabled)
✗ Tahun (disabled)
✗ Pengaturan (disabled)
```

### Tenant Admin Menu

```
✓ Beranda (Dashboard Sekolah)
✓ Scan QR
✓ Murid
  ├─ Daftar Murid
  └─ Input Murid
✓ Kelas
  └─ Daftar Kelas
✓ Tahun
  └─ Daftar Tahun
✓ Pengaturan
```

---

## 🧪 Quick Test Steps

### Test 1: Login Super Admin

1. Buka http://localhost:8000/
2. Masukkan: superadmin / superadmin123
3. Verifikasi:
    - ✓ Lihat 2 sekolah di dashboard
    - ✓ Lihat 80 siswa total
    - ✓ Menu terbatas (hanya monitoring)

### Test 2: Login Admin SMA 1

1. Logout dari super admin
2. Masukkan: admin_sma1 / admin123
3. Verifikasi:
    - ✓ Dashboard menampilkan 50 siswa
    - ✓ Daftar murid hanya SMA 1
    - ✓ Menu lengkap tersedia
    - ✓ Bisa input/edit siswa

### Test 3: Login Admin SMKN 2

1. Logout dari admin_sma1
2. Masukkan: admin_smkn2 / admin456
3. Verifikasi:
    - ✓ Dashboard menampilkan 30 siswa
    - ✓ Daftar murid hanya SMKN 2
    - ✓ Tidak bisa lihat siswa SMA 1

### Test 4: Data Isolation

1. Login admin_sma1, input siswa baru: NIS 88888
2. Logout dan login admin_smkn2
3. Verifikasi: Siswa NIS 88888 TIDAK ada di SMKN 2
4. Login superadmin, verifikasi siswa ada di monitoring

---

## 🔍 Database Check

### Check via Artisan Command

```bash
php artisan verify:multitenant
```

Output expected:

```
✓ SMA Negeri 1: 50 siswa
✓ SMKN 2 Bandung: 30 siswa
✓ Total User: 5 (3 untuk SMA 1, 2 untuk SMKN 2)
```

### Check via Tinker

```bash
php artisan tinker

# Count sekolah
>>> \App\Models\Sekolah::count()
# Should return: 2

# Count siswa per sekolah
>>> \App\Models\Murid::where('sekolah_id', 1)->count()
# Should return: 50

>>> \App\Models\Murid::where('sekolah_id', 2)->count()
# Should return: 30

# Check user
>>> \App\Models\User::where('super_admin', true)->count()
# Should return: 1 (superadmin)
```

---

## 🚨 Troubleshooting

### Issue: "Call to undefined method Sekolah::murids()"

**Fix**: Pastikan model Sekolah memiliki method `murids()`

```php
public function murids()
{
    return $this->hasMany(Murid::class, 'sekolah_id');
}
```

### Issue: Dashboard tidak menampilkan data

**Fix**: Check apakah user memiliki `sekolah_id` di database

```bash
php artisan tinker
>>> \App\Models\User::find(3)->sekolah_id
```

### Issue: Siswa terlihat di semua sekolah

**Fix**: Check apakah sekolah_id ter-set saat input/import

```php
$validasi['sekolah_id'] = auth()->user()->sekolah_id;
```

---

## 📞 Support

Untuk pertanyaan atau issue:

1. Check file `MULTITENANT_TESTING_GUIDE.md` untuk detail lengkap
2. Jalankan `php artisan verify:multitenant` untuk verifikasi data
3. Check database langsung dengan Tinker jika diperlukan

**Status**: ✅ PRODUCTION READY
