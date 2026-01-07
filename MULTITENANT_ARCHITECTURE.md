# 🏗️ Multi-Tenant Architecture Documentation

## 📐 System Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                    Browser / Client                      │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              Laravel Application                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────────────────────────────────────────┐   │
│  │         Authentication & Authorization           │   │
│  │  (LoginController, IsAdmin trait)                │   │
│  └──────────────────────────────────────────────────┘   │
│                       │                                  │
│                       ▼                                  │
│  ┌──────────────────────────────────────────────────┐   │
│  │           DashboardController                     │   │
│  │  ┌─────────────────────────────────────────────┐│   │
│  │  │ superAdminDashboard()                       ││   │
│  │  │ - Monitoring semua sekolah                  ││   │
│  │  │ - View: beranda-superadmin.blade.php       ││   │
│  │  └─────────────────────────────────────────────┘│   │
│  │  ┌─────────────────────────────────────────────┐│   │
│  │  │ tenantAdminDashboard()                      ││   │
│  │  │ - Filter data by sekolah_id                 ││   │
│  │  │ - View: beranda.blade.php                   ││   │
│  │  └─────────────────────────────────────────────┘│   │
│  └──────────────────────────────────────────────────┘   │
│                       │                                  │
│                       ▼                                  │
│  ┌──────────────────────────────────────────────────┐   │
│  │           MuridController                        │   │
│  │  - index_daftar() dengan filter sekolah_id      │   │
│  │  - store() auto-set sekolah_id                  │   │
│  │  - import() dengan session untuk sekolah_id     │   │
│  └──────────────────────────────────────────────────┘   │
│                       │                                  │
│                       ▼                                  │
│  ┌──────────────────────────────────────────────────┐   │
│  │           Sidebar Partial                        │   │
│  │  - Menu dinamis @if(!auth()->user()->super_admin)   │
│  │  - Tampilkan nama sekolah                       │   │
│  └──────────────────────────────────────────────────┘   │
│                                                          │
└─────────────────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  Database Layer                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────┐                                       │
│  │  sekolahs    │                                       │
│  │  ----------- │                                       │
│  │  id          │                                       │
│  │  nama_sekolah│                                       │
│  │  npsn        │                                       │
│  │  jam_masuk   │                                       │
│  │  status_...  │                                       │
│  │  subscr...   │                                       │
│  └──────────────┘                                       │
│        │                                                │
│        ├──────────────┬──────────────┬─────────────┐   │
│        │              │              │             │    │
│        ▼              ▼              ▼             ▼    │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ │
│  │ users    │ │ murids   │ │ jenjangs │ │ kelas    │ │
│  │(many)    │ │(many)    │ │(many)    │ │          │ │
│  │sekolah_id│ │sekolah_id│ │sekolah_id│ │kelas     │ │
│  │          │ │jenjang_id│ │          │ │          │ │
│  │          │ │kelas_id  │ │          │ │          │ │
│  │          │ │tahun_id  │ │          │ │          │ │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘ │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## 🔀 Data Flow: Super Admin Login

```
┌─────────────────────────────────────────────────────┐
│  User login: superadmin / superadmin123             │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
         ┌──────────────────────────┐
         │  LoginController         │
         │  authenticate()          │
         └────────────┬─────────────┘
                      │
                      ▼
         ┌──────────────────────────┐
         │  Check super_admin = true│
         └────────────┬─────────────┘
                      │
                      ▼
         ┌──────────────────────────┐
         │  Redirect ke /beranda    │
         └────────────┬─────────────┘
                      │
                      ▼
         ┌──────────────────────────┐
         │  DashboardController     │
         │  index()                 │
         └────────────┬─────────────┘
                      │
                      ▼
    ┌─────────────────────────────────┐
    │  Deteksi super_admin = true     │
    │  Panggil:                       │
    │  superAdminDashboard()          │
    └────────────┬────────────────────┘
                 │
                 ▼
    ┌─────────────────────────────────┐
    │  Query Data Monitoring:         │
    │  - Sekolah::count()             │
    │  - Sekolah::withCount('murids') │
    │  - User::where('super_admin',0) │
    └────────────┬────────────────────┘
                 │
                 ▼
    ┌─────────────────────────────────┐
    │  Return view:                   │
    │  beranda-superadmin.blade.php   │
    │                                 │
    │  Dengan data:                   │
    │  - totalSekolah: 2              │
    │  - sekolahAktif: 2              │
    │  - totalMurid: 80               │
    │  - sekolahList: [...]           │
    └────────────┬────────────────────┘
                 │
                 ▼
    ┌─────────────────────────────────┐
    │  Render Dashboard Monitoring     │
    │  - Info boxes dengan stats       │
    │  - Tabel daftar sekolah         │
    │  - Status langganan per sekolah │
    └─────────────────────────────────┘
```

---

## 🔀 Data Flow: Tenant Admin Login

```
┌──────────────────────────────────────────────┐
│  User login: admin_sma1 / admin123           │
└────────────────────┬────────────────────────┘
                     │
                     ▼
         ┌──────────────────────────┐
         │  LoginController         │
         │  authenticate()          │
         └────────────┬─────────────┘
                      │
                      ▼
         ┌──────────────────────────┐
         │  Check super_admin = false│
         └────────────┬─────────────┘
                      │
                      ▼
         ┌──────────────────────────┐
         │  Redirect ke /beranda    │
         └────────────┬─────────────┘
                      │
                      ▼
         ┌──────────────────────────┐
         │  DashboardController     │
         │  index()                 │
         └────────────┬─────────────┘
                      │
                      ▼
    ┌──────────────────────────────────┐
    │  Deteksi super_admin = false     │
    │  Panggil:                        │
    │  tenantAdminDashboard()          │
    └────────────┬─────────────────────┘
                 │
                 ▼
    ┌──────────────────────────────────┐
    │  Get sekolah_id dari user:       │
    │  sekolahId = auth()->user()->    │
    │             sekolah_id (= 1)     │
    └────────────┬─────────────────────┘
                 │
                 ▼
    ┌──────────────────────────────────┐
    │  Filter ALL queries by sekolah_id:
    │  - Murid::where('sekolah_id', 1) │
    │  - Absensi::where(               │
    │     sekolah_id=1)                │
    └────────────┬─────────────────────┘
                 │
                 ▼
    ┌──────────────────────────────────┐
    │  Return view: beranda.blade.php  │
    │                                  │
    │  Dengan data hanya sekolah 1:    │
    │  - totalMurid: 50               │
    │  - absenMasuk: XX               │
    │  - muridAll: [...]  (SMA 1 only) │
    └────────────┬─────────────────────┘
                 │
                 ▼
    ┌──────────────────────────────────┐
    │  Render Dashboard Sekolah        │
    │  - Statistik absensi SMA 1       │
    │  - Daftar siswa SMA 1 saja       │
    │  - Data terisolasi               │
    └──────────────────────────────────┘
```

---

## 📊 Database Schema Relationships

```
sekolahs (1)
  ├─── (1:N) ──→ users
  │               (sekolah_id)
  │
  ├─── (1:N) ──→ murids
  │               (sekolah_id)
  │
  ├─── (1:N) ──→ jenjangs
  │               (sekolah_id)
  │
  └─── (1:N) ──→ absensi (melalui murids)
                  (murid_id → murids.id → sekolah_id)

users (1)
  ├─── (N:1) ──→ sekolahs
  │               (sekolah_id)
  │
  └─── (1:N) ──→ personal_access_tokens

murids (1)
  ├─── (N:1) ──→ sekolahs
  │               (sekolah_id)
  │
  ├─── (N:1) ──→ jenjangs
  │               (jenjang_id)
  │
  ├─── (N:1) ──→ kelas
  │               (kelas_id)
  │
  ├─── (N:1) ──→ tahun
  │               (tahun_id)
  │
  └─── (1:N) ──→ absensi
                  (murid_id)

jenjangs (1)
  ├─── (N:1) ──→ sekolahs
  │               (sekolah_id)
  │
  └─── (1:N) ──→ murids
                  (jenjang_id)

kelas (1)
  └─── (1:N) ──→ murids
                  (kelas_id)

tahun (1)
  └─── (1:N) ──→ murids
                  (tahun_id)

absensi (1)
  ├─── (N:1) ──→ murids
  │               (murid_id)
  │
  └─── (N:1) ──→ kelas
                  (kelas_id)
```

---

## 🔐 Security & Authorization

### Endpoint Protection

#### Super Admin Only

```
GET  /beranda          → DashboardController@index
                          (if super_admin redirect beranda-superadmin)

GET  /daftar-murid     → MuridController@index_daftar
                          (shows all murids)
```

#### Tenant Admin Only

```
GET  /beranda          → DashboardController@index
                          (if !super_admin use tenant dashboard)

GET  /input-murid      → MuridController@index_input
                          (only tenant admin can access)

POST /input-murid-proses → MuridController@store
                          (auto sekolah_id from auth user)

POST /murid/import     → MuridController@import
                          (auto sekolah_id from session)

GET  /scan-qr          → AbsensiController@index
                          (only tenant admin can scan)
```

#### Both Roles

```
GET  /daftar-murid     → MuridController@index_daftar
                          (super admin: all murids)
                          (tenant admin: only own sekolah)

POST /keluar           → LoginController@logout
                          (all roles)
```

### Data Filtering Strategy

#### In Controller

```php
// Get current user
$user = auth()->user();

// Filter by sekolah_id if not super admin
$murid = Murid::with(['kelas','tahun'])
    ->when(!$user->super_admin, function($query) use ($user) {
        return $query->where('sekolah_id', $user->sekolah_id);
    })
    ->get();
```

#### In Import

```php
// Store sekolah_id in session
session(['import_sekolah_id' => auth()->user()->sekolah_id]);

// Used in MuridImport model
'sekolah_id' => session('import_sekolah_id') ?? auth()->user()->sekolah_id
```

---

## 🎯 Key Design Patterns Used

### 1. Repository Pattern (Controller as Filter)

Controllers act as repositories, filtering data before returning to views.

### 2. Polymorphic Behavior

Dashboard controller returns different views based on `super_admin` flag.

### 3. Session-Based Context

Import uses session to preserve tenant context across requests.

### 4. Eager Loading

Relations are loaded with `with()` to prevent N+1 queries.

### 5. Query Scopes (Implicit)

Filtering happens in controller before DB query for flexibility.

---

## 📈 Performance Considerations

### Current Implementation

-   Sekolah: 2
-   Murids: 80
-   Absensi: 0 (can be millions)

### Query Optimization

```php
// Good: Eager load relationships
Sekolah::with(['users', 'murids'])->get()

// Avoid: N+1 query problem
foreach($sekolahs as $sekolah) {
    $sekolah->murids;  // Query inside loop!
}

// Use withCount for statistics
Sekolah::withCount('murids')->get()
```

### Index Strategy

```sql
-- Recommended indexes:
CREATE INDEX idx_murids_sekolah_id ON murids(sekolah_id);
CREATE INDEX idx_users_sekolah_id ON users(sekolah_id);
CREATE INDEX idx_absensi_murid_id ON absensi(murid_id);
CREATE INDEX idx_murids_kelas_id ON murids(kelas_id);
```

---

## 🚀 Scaling Strategy

### Phase 1: Current (Production Ready)

-   2-5 sekolah
-   50-500 siswa per sekolah
-   Real-time filtering

### Phase 2: Growth (100+ sekolah)

-   Implement caching layer (Redis)
-   Partition large tables
-   Implement queue for import

### Phase 3: Enterprise (1000+ sekolah)

-   Separate tenant databases
-   Read replicas
-   API-first architecture

---

## 📚 Files Reference

| File                           | Purpose                 | Role    |
| ------------------------------ | ----------------------- | ------- |
| `DashboardController.php`      | Route logic             | Core    |
| `MuridController.php`          | Data management         | Core    |
| `Sekolah.php`                  | Relationships           | Model   |
| `User.php`                     | Auth + sekolah relation | Model   |
| `Murid.php`                    | Student data            | Model   |
| `sidebar.blade.php`            | Dynamic menu            | View    |
| `beranda.blade.php`            | Tenant dashboard        | View    |
| `beranda-superadmin.blade.php` | Admin dashboard         | View    |
| `MultiTenantTestSeeder.php`    | Test data               | Seeder  |
| `VerifyMultiTenant.php`        | Verification            | Command |

---

## ✅ Status

**Architecture**: ✅ COMPLETE  
**Implementation**: ✅ COMPLETE  
**Testing**: ✅ PASSED  
**Documentation**: ✅ COMPLETE  
**Ready for Production**: ✅ YES
