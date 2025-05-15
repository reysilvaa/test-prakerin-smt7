# Panduan Lengkap Sistem Manajemen Kepegawaian dengan Laravel Auto CRUD

## 📋 Daftar Isi
- [Persiapan Awal](#persiapan-awal)
- [Langkah-langkah Pengembangan](#langkah-langkah-pengembangan)
- [Pengaturan Database](#pengaturan-database)
- [Pembuatan Model dan Migrasi](#pembuatan-model-dan-migrasi)
- [Middleware dan Autentikasi](#middleware-dan-autentikasi)
- [Factories dan Seeders](#factories-dan-seeders)
- [Controllers](#controllers)
- [CRUD Generator](#crud-generator)
- [Routing](#routing)
- [Migrasi dan Seeding](#migrasi-dan-seeding)
- [Troubleshooting](#troubleshooting-umum)
- [Informasi Login](#informasi-login)
- [Perintah-perintah Penting](#perintah-perintah-penting)

## 🚀 Persiapan Awal

### Persyaratan Sistem
- PHP >= 8.1
- Composer
- MySQL
- Node.js dan NPM

### Membuat Proyek Baru
```bash
composer create-project laravel/laravel kepegawaian
cd kepegawaian
```

### Install Package Yang Dibutuhkan
```bash
# Package UI untuk autentikasi
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev

# Laravel Auto CRUD Generator
composer require mrmarchone/laravel-auto-crud --dev
php artisan vendor:publish --provider="Mrmarchone\LaravelAutoCrud\LaravelAutoCrudServiceProvider" --tag="auto-crud-config"
```

### Konfigurasi Database
1. Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_kepegawaian
DB_USERNAME=root
DB_PASSWORD=
```

2. Buat database:
```bash
php artisan migrate
```
atau
```bash
mysql -u root -p -e "CREATE DATABASE db_kepegawaian"
```

## 🔄 Langkah-langkah Pengembangan

### 1. Jalankan Migrasi Dasar
```bash
php artisan migrate
```

### 2. Tambahkan Kolom Role di Tabel Users
```bash
php artisan make:migration add_role_to_users_table --table=users
```

Edit file migration yang baru dibuat:
```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'pegawai'])->default('admin');
        $table->unsignedBigInteger('pegawai_id')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
        $table->dropColumn('pegawai_id');
    });
}
```

## 📊 Pengaturan Database

### 1. Buat Model dan Migrasi
```bash
# Buat model Departemen dengan migration, factory, seeder, controller
php artisan make:model Departemen -mfsc

# Buat model Pegawai dengan migration, factory, seeder, controller
php artisan make:model Pegawai -mfsc

# Buat middleware untuk cek role
php artisan make:middleware CheckRole

# Buat controller dashboard pegawai
php artisan make:controller PegawaiDashboardController
```

### 2. Edit File Migrasi

**Migrasi Departemen**:
```php
public function up()
{
    Schema::create('departemens', function (Blueprint $table) {
        $table->id();
        $table->string('nama_departemen');
        $table->string('kode_departemen')->unique();
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
}
```

**Migrasi Pegawai**:
```php
public function up()
{
    Schema::create('pegawais', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('nip')->unique();
        $table->string('email')->unique();
        $table->string('no_telepon');
        $table->text('alamat');
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
        $table->unsignedBigInteger('departemen_id');
        $table->string('jabatan');
        $table->date('tanggal_bergabung');
        $table->enum('status_kepegawaian', ['Tetap', 'Kontrak', 'Magang']);
        $table->decimal('gaji', 12, 2);
        $table->timestamps();
        
        $table->foreign('departemen_id')->references('id')->on('departemens')->onDelete('cascade');
    });
}
```

**Tambahkan Foreign Key ke Users**:
```bash
php artisan make:migration add_foreign_key_to_users --table=users
```

Edit file migrasi yang baru dibuat:
```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['pegawai_id']);
    });
}
```

## 📝 Pembuatan Model dan Migrasi

### 1. Edit Model User.php

```php
// Tambahkan di bagian properties
protected $fillable = ['name', 'email', 'password', 'role', 'pegawai_id'];

// Tambahkan method-method berikut
public function pegawai()
{
    return $this->belongsTo(Pegawai::class);
}

public function isAdmin()
{
    return $this->role === 'admin';
}

public function isPegawai()
{
    return $this->role === 'pegawai';
}
```

### 2. Edit Model Departemen.php

```php
protected $fillable = ['nama_departemen', 'kode_departemen', 'deskripsi'];

public function pegawais()
{
    return $this->hasMany(Pegawai::class);
}
```

### 3. Edit Model Pegawai.php

```php
protected $fillable = [
    'nama', 'nip', 'email', 'no_telepon', 'alamat', 
    'tanggal_lahir', 'jenis_kelamin', 'departemen_id', 
    'jabatan', 'tanggal_bergabung', 'status_kepegawaian', 'gaji'
];

protected $casts = [
    'tanggal_lahir' => 'date',
    'tanggal_bergabung' => 'date',
    'gaji' => 'decimal:2'
];

public function departemen()
{
    return $this->belongsTo(Departemen::class);
}

public function user()
{
    return $this->hasOne(User::class, 'pegawai_id');
}
```

## 🔐 Middleware dan Autentikasi

### 1. Edit Middleware CheckRole

File: `app/Http/Middleware/CheckRole.php`:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        if (!$role) {
            return $next($request);
        }
        
        if ($request->user()->role !== $role) {
            return redirect()->route($request->user()->role === 'admin' ? 'departemens.index' : 'pegawai.dashboard');
        }

        return $next($request);
    }
}
```

### 2. Daftarkan Middleware di Kernel.php

Buka file `app/Http/Kernel.php` dan tambahkan di bagian `$routeMiddleware`:
```php
protected $routeMiddleware = [
    // middleware yang sudah ada...
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

## 🏭 Factories dan Seeders

### 1. DepartemenFactory

Edit file `database/factories/DepartemenFactory.php`:
```php
public function definition(): array
{
    return [
        'nama_departemen' => fake()->company(),
        'kode_departemen' => 'DEP' . fake()->unique()->numerify('###'),
        'deskripsi' => fake()->paragraph()
    ];
}
```

### 2. PegawaiFactory

Edit file `database/factories/PegawaiFactory.php`:
```php
use App\Models\Departemen;

public function definition(): array
{
    return [
        'nama' => fake()->name(),
        'nip' => 'NIP' . fake()->unique()->numerify('##########'),
        'email' => fake()->unique()->safeEmail(),
        'no_telepon' => fake()->phoneNumber(),
        'alamat' => fake()->address(),
        'tanggal_lahir' => fake()->date('Y-m-d', '-20 years'),
        'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
        'departemen_id' => Departemen::factory(),
        'jabatan' => fake()->randomElement(['Manager', 'Supervisor', 'Staff', 'Asisten', 'Operator']),
        'tanggal_bergabung' => fake()->date('Y-m-d', 'now'),
        'status_kepegawaian' => fake()->randomElement(['Tetap', 'Kontrak', 'Magang']),
        'gaji' => fake()->numberBetween(3000000, 15000000)
    ];
}
```

### 3. UserSeeder

Buat UserSeeder:
```bash
php artisan make:seeder UserSeeder
```

Edit file `database/seeders/UserSeeder.php`:
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
```

### 4. DepartemenSeeder

Edit file `database/seeders/DepartemenSeeder.php`:
```php
<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'nama_departemen' => 'Human Resources',
                'kode_departemen' => 'HR001',
                'deskripsi' => 'Departemen yang mengelola sumber daya manusia'
            ],
            [
                'nama_departemen' => 'Finance',
                'kode_departemen' => 'FN001',
                'deskripsi' => 'Departemen yang mengelola keuangan perusahaan'
            ],
            [
                'nama_departemen' => 'Information Technology',
                'kode_departemen' => 'IT001',
                'deskripsi' => 'Departemen yang mengelola teknologi informasi'
            ],
            [
                'nama_departemen' => 'Marketing',
                'kode_departemen' => 'MK001',
                'deskripsi' => 'Departemen yang mengelola pemasaran produk'
            ],
            [
                'nama_departemen' => 'Operations',
                'kode_departemen' => 'OP001',
                'deskripsi' => 'Departemen yang mengelola operasional perusahaan'
            ]
        ];

        foreach ($departments as $dept) {
            Departemen::create($dept);
        }
    }
}
```

### 5. PegawaiSeeder

Edit file `database/seeders/PegawaiSeeder.php`:
```php
<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        Pegawai::factory()->count(20)->create();
    }
}
```

### 6. PegawaiUserSeeder

Buat PegawaiUserSeeder:
```bash
php artisan make:seeder PegawaiUserSeeder
```

Edit file `database/seeders/PegawaiUserSeeder.php`:
```php
<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PegawaiUserSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all();
        
        foreach ($pegawais as $pegawai) {
            User::create([
                'name' => $pegawai->nama,
                'email' => $pegawai->email,
                'password' => Hash::make('pegawai123'),
                'role' => 'pegawai',
                'pegawai_id' => $pegawai->id,
            ]);
        }
    }
}
```

### 7. DatabaseSeeder

Edit file `database/seeders/DatabaseSeeder.php`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DepartemenSeeder::class,
            PegawaiSeeder::class,
            PegawaiUserSeeder::class,
        ]);
    }
}
```

## 🎮 Controllers

### 1. PegawaiDashboardController

Edit file `app/Http/Controllers/PegawaiDashboardController.php`:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        $pegawai = auth()->user()->pegawai;
        return view('pegawai.dashboard', compact('pegawai'));
    }
}
```

### 2. AdminDashboardController

Buat AdminDashboardController:
```bash
php artisan make:controller AdminDashboardController
```

Edit file `app/Http/Controllers/AdminDashboardController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::count();
        $totalDepartemen = Departemen::count();
        
        $pegawaiPerDepartemen = Departemen::select('departemens.id', 'departemens.nama_departemen', DB::raw('count(pegawais.id) as jumlah_pegawai'))
            ->leftJoin('pegawais', 'departemens.id', '=', 'pegawais.departemen_id')
            ->groupBy('departemens.id', 'departemens.nama_departemen')
            ->orderBy('jumlah_pegawai', 'desc')
            ->get();
        
        return view('admin.dashboard', compact('totalPegawai', 'totalDepartemen', 'pegawaiPerDepartemen'));
    }
}
```

## ⚙️ CRUD Generator

Jalankan auto-crud generator untuk Departemen dan Pegawai:

```bash
# Generate CRUD untuk Departemen
php artisan auto-crud:generate -M Departemen -T web -O

# Generate CRUD untuk Pegawai
php artisan auto-crud:generate -M Pegawai -T web -O
```

Perintah di atas akan membuat:
- Controller untuk CRUD
- Routes yang dibutuhkan
- Blade files (views) untuk tampilan

## 🛣️ Routing

Edit file `routes/web.php`:

```php
<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PegawaiDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Redirect default /home route
Route::get('/home', function() {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('pegawai.dashboard');
        }
    }
    return redirect('/login');
})->name('home');

// Routes dengan autentikasi
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('departemens', DepartemenController::class);
        Route::resource('pegawais', PegawaiController::class);
    });
    
    // Pegawai routes
    Route::middleware(['role:pegawai'])->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    });
});
```

## 🚀 Migrasi dan Seeding

```bash
# Jalankan migrasi
php artisan migrate:fresh

# Jalankan seeder
php artisan db:seed
```

## 🔧 Troubleshooting Umum

### 1. Error Database
- **Table not found**: Gunakan `php artisan migrate:fresh` untuk migrasi ulang
- **Foreign key error**: Pastikan tabel yang direferensi sudah ada sebelum membuat foreign key
- **Duplicate entry**: Pastikan tidak ada duplikasi data terutama pada kolom dengan constraint unique

### 2. Error Middleware
- **Too few arguments**: Pastikan parameter role selalu diberikan (`role:admin`) atau gunakan parameter opsional
- **Class not found**: Pastikan namespace sudah benar dan middleware sudah terdaftar di Kernel.php

### 3. Error Tampilan
- **CSS tidak load**: 
  ```bash
  npm run dev
  php artisan cache:clear
  php artisan view:clear
  ```
- **View not found**: Pastikan view sudah dibuat dan path-nya benar
- **Undefined variable**: Pastikan semua variabel yang digunakan di view sudah di-compact atau di-with

## 🔑 Informasi Login

- **Admin**:
  - Email: admin@example.com
  - Password: password

- **Pegawai**:
  - Email: (email dari database)
  - Password: pegawai123

## 📌 Perintah-perintah Penting

```bash
# Jalankan server Laravel
php artisan serve

# Compile asset
npm run dev

# Cache clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Migrasi ulang dan seeding
php artisan migrate:fresh --seed

# Membuat controller baru
php artisan make:controller NamaController

# Membuat model dengan migrasi, controller, seeder, dan factory
php artisan make:model NamaModel -mfsc

# Membuat middleware baru
php artisan make:middleware NamaMiddleware

# Melihat daftar route
php artisan route:list

# Optimasi aplikasi untuk production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📝 Catatan Tambahan

1. Selalu gunakan relasi yang tepat di model (hasMany, belongsTo, hasOne, dll)
2. Validasi input di Request untuk menjaga integritas data
3. Gunakan middleware untuk membatasi akses ke fitur tertentu
4. Pastikan semua rute memiliki middleware autentikasi yang tepat
5. Gunakan eager loading untuk menghindari N+1 query problem
6. Tambahkan file .env.example dengan konfigurasi default untuk memudahkan pengembangan