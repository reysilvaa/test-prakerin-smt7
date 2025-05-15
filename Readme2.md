# Contekan Sistem Manajemen Kepegawaian dengan Laravel Auto CRUD

## Persiapan Awal

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
mysql -u root -p -e "CREATE DATABASE db_kepegawaian"
```

## Langkah-langkah Pengembangan

### 1. Jalankan Migrasi Dasar
```bash
php artisan migrate
```

### 2. Tambahkan Kolom Role di Tabel Users
```bash
php artisan make:migration add_role_to_users_table --table=users
```

Edit file migration:
```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'pegawai'])->default('admin');
        $table->unsignedBigInteger('pegawai_id')->nullable();
    });
}
```

### 3. Buat Model dan Migrasi
```bash
# Buat model Departemen dengan migration, factory, seeder, controller
php artisan make:model Departemen -mfsc

# Buat model Pegawai
php artisan make:model Pegawai -mfsc

# Buat middleware untuk cek role
php artisan make:middleware CheckRole

# Buat controller dashboard pegawai
php artisan make:controller PegawaiDashboardController
```

### 4. Edit Migration Files

**Departemen**:
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

**Pegawai**:
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

**Tambah Foreign Key**:
```bash
php artisan make:migration add_foreign_key_to_users --table=users
```

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('set null');
    });
}
```

### 5. Edit Model

**User.php**:
```php
protected $fillable = ['name', 'email', 'password', 'role', 'pegawai_id'];

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

**Departemen.php**:
```php
protected $fillable = ['nama_departemen', 'kode_departemen', 'deskripsi'];

public function pegawais()
{
    return $this->hasMany(Pegawai::class);
}
```

**Pegawai.php**:
```php
protected $fillable = [
    'nama', 'nip', 'email', 'no_telepon', 'alamat', 
    'tanggal_lahir', 'jenis_kelamin', 'departemen_id', 
    'jabatan', 'tanggal_bergabung', 'status_kepegawaian', 'gaji'
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

### 6. Middleware CheckRole
```php
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
```

Daftarkan di Kernel.php:
```php
protected $routeMiddleware = [
    // existing middlewares...
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

### 7. Factories dan Seeders

**DepartemenFactory**:
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

**PegawaiFactory**:
```php
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

**UserSeeder**:
```php
php artisan make:seeder UserSeeder
```

```php
public function run(): void
{
    User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
}
```

**DepartemenSeeder**:
```php
public function run(): void
{
    $departments = [
        [
        'nama_departemen' => 'Human Resources',
        'kode_departemen' => 'HR001',
        'deskripsi' => 'Departemen yang mengelola sumber daya manusia'
        ],
        // tambahkan departemen lainnya
    ];

    foreach ($departments as $dept) {
        Departemen::create($dept);
    }
}
```

**PegawaiSeeder**:
```php
public function run(): void
{
    Pegawai::factory()->count(20)->create();
}
```

**PegawaiUserSeeder**:
```php
php artisan make:seeder PegawaiUserSeeder
```

```php
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
```

**DatabaseSeeder**:
```php
public function run(): void
{
    $this->call([
        UserSeeder::class,
        DepartemenSeeder::class,
        PegawaiSeeder::class,
        PegawaiUserSeeder::class,
    ]);
}
```

### 8. Controller

**PegawaiDashboardController**:
```php
public function index()
{
    $pegawai = auth()->user()->pegawai;
    return view('pegawai.dashboard', compact('pegawai'));
}
```

**AdminDashboardController**:
```php
php artisan make:controller AdminDashboardController
```

```php
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
```

### 9. Generate CRUD
```bash
# Generate CRUD untuk Departemen
php artisan auto-crud:generate -M Departemen -T web -O

# Generate CRUD untuk Pegawai
php artisan auto-crud:generate -M Pegawai -T web -O
```

### 10. Routes
```php
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
        Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('departemens', DepartemenController::class);
        Route::resource('pegawais', PegawaiController::class);
    });
    
    // Pegawai routes
    Route::middleware(['role:pegawai'])->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    });
});
```

### 11. Migrasi dan Seeding
```bash
# Jalankan migrasi
php artisan migrate:fresh

# Jalankan seeder
php artisan db:seed
```

## Troubleshooting Umum

### 1. Error Database
- **Table not found**: Gunakan `php artisan migrate:fresh` untuk migrasi ulang
- **Foreign key error**: Pastikan tabel yang direferensi sudah ada

### 2. Error Middleware
- **Too few arguments**: Pastikan parameter role selalu diberikan (`role:admin`) atau gunakan parameter opsional

### 3. Error Tampilan
- **CSS tidak load**: 
  ```bash
  npm run dev
  php artisan cache:clear
  php artisan view:clear
  ```

## Login Aplikasi

- **Admin**:
  - Email: admin@example.com
  - Password: password

- **Pegawai**:
  - Email: (email dari database)
  - Password: pegawai123

## Perintah Penting

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
```