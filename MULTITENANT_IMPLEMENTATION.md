# 📋 Multi-Tenant Implementation Guide

## ✅ Fitur yang Sudah Diimplementasi

### 1. **Role-Based Access Control**

-   **Super Admin**: Hanya bisa melihat monitoring semua sekolah
-   **Tenant Admin**: Bisa mengelola siswa dan data mereka sendiri saja

### 2. **Dashboard Berbeda untuk Setiap Role**

#### Super Admin Dashboard (`/beranda`)

-   Monitoring status semua sekolah
-   Statistik jumlah sekolah aktif, kadaluarsa, tertunda
-   Daftar semua siswa di semua sekolah
-   Daftar admin setiap sekolah
-   Status langganan setiap sekolah

**Akses**: superadmin / superadmin123

#### Tenant Admin Dashboard (`/beranda`)

-   Statistik siswa hanya dari sekolah mereka
-   Data absensi siswa mereka sendiri
-   Tidak ada akses ke data sekolah lain

**Akses**:

-   admin_sma1 / admin123 (SMA Negeri 1 - 50 siswa)
-   admin_smkn2 / admin456 (SMKN 2 Bandung - 30 siswa)

### 3. **Sidebar Menu Dinamis**

#### Super Admin Menu:

-   ✓ Beranda (Dashboard Monitoring)
-   ✗ Scan QR (tidak perlu)
-   ✓ Murid > Daftar Murid (lihat semua)

#### Tenant Admin Menu:

-   ✓ Beranda (Dashboard Sekolah)
-   ✓ Scan QR
-   ✓ Murid > Daftar Murid (hanya sekolah mereka)
-   ✓ Murid > Input Murid
-   ✓ Kelas > Daftar Kelas
-   ✓ Tahun > Daftar Tahun
-   ✓ Pengaturan

### 4. **Data Filtering Multi-Tenant**

#### MuridController

```php
// index_daftar() - Filter siswa berdasarkan sekolah
$murid = Murid::with(['kelas','tahun'])
    ->when(!$user->super_admin, function($query) use ($user) {
        return $query->where('sekolah_id', $user->sekolah_id);
    })
    ->get();

// store() - Set sekolah_id otomatis dari user
$validasi['sekolah_id'] = auth()->user()->sekolah_id;
```

#### DashboardController

```php
// Super Admin: Lihat monitoring semua sekolah
// Tenant Admin: Lihat data hanya sekolah mereka (filter by sekolah_id)
```

#### Absensi & Data Lainnya

-   Filter otomatis berdasarkan sekolah user
-   Super admin bisa lihat semua
-   Tenant admin hanya sekolah mereka

### 5. **File Management (Import)**

-   Saat import Excel, `sekolah_id` otomatis diset dari user yang login
-   Setiap tenant hanya bisa import siswa mereka sendiri

## 📝 Testing Checklist

### Scenario 1: Login sebagai Superadmin

```
Username: superadmin
Password: superadmin123
```

✅ Lihat Dashboard Monitoring dengan 2 sekolah
✅ Lihat 80 siswa total (50 + 30)
✅ Lihat daftar semua siswa
✅ Sidebar tanpa Scan QR dan Input Murid
✅ Hanya bisa lihat, tidak bisa edit/input

### Scenario 2: Login sebagai Tenant Admin SMA Negeri 1

```
Username: admin_sma1
Password: admin123
```

✅ Lihat Dashboard dengan 50 siswa (SMA 1 saja)
✅ Daftar Murid menampilkan 25 siswa (dari test seeder) + 25 factory
✅ Bisa Input Murid (otomatis ke SMA 1)
✅ Bisa Upload/Import (otomatis ke SMA 1)
✅ Menu Scan QR tersedia
✅ Menu Kelas, Tahun, Pengaturan tersedia

### Scenario 3: Login sebagai Tenant Admin SMKN 2 Bandung

```
Username: admin_smkn2
Password: admin456
```

✅ Lihat Dashboard dengan 30 siswa (SMKN 2 saja)
✅ Daftar Murid menampilkan 30 siswa saja
✅ Tidak bisa lihat siswa SMA 1
✅ Bisa Input Murid (otomatis ke SMKN 2)

### Scenario 4: Data Isolation

```
admin_sma1 input siswa NIS 99999 "Budi Santoso"
→ Siswa muncul di dashboard admin_sma1
→ Siswa TIDAK muncul di dashboard admin_smkn2
→ Superadmin bisa lihat di monitoring (temasuk di jumlah total)
```

## 🔧 File yang Diubah

1. **MuridController.php**

    - `index_daftar()` - Filter by sekolah_id
    - `store()` - Auto-set sekolah_id
    - `import()` - Gunakan session untuk sekolah_id

2. **DashboardController.php**

    - Pisah logic super admin vs tenant admin
    - `superAdminDashboard()` - Monitoring semua
    - `tenantAdminDashboard()` - Data sekolah saja

3. **MuridImport.php**

    - Tambah `sekolah_id` saat import

4. **sidebar.blade.php**

    - Conditional menu berdasarkan `super_admin` flag
    - Tampilkan nama sekolah untuk tenant admin

5. **beranda-superadmin.blade.php** (BARU)
    - Dashboard khusus super admin
    - Monitoring status semua sekolah

## 🚀 Next Steps (Optional)

1. **Middleware untuk Authorization**

    ```php
    // Proteksi route Murid hanya untuk admin
    Route::middleware(['auth', 'admin'])->group(...);
    ```

2. **Policy untuk Model Murid**

    ```php
    // Tenant hanya bisa edit murid sekolah mereka
    if (!$user->super_admin && $murid->sekolah_id !== $user->sekolah_id) {
        abort(403);
    }
    ```

3. **Audit Log**

    - Track siapa yang input/edit data
    - Untuk compliance dan audit trail

4. **Payment Integration**
    - Complete billing module
    - Automatic subscription management

## 📞 Support

Untuk pertanyaan atau issue, hubungi tim development!
