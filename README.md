# FileCache - Fayl Asosli Kesh Tizimi

PSR-16 SimpleCache standartiga mos keladigan PHP uchun fayl asosli kesh kutubxonasi.

## Umumiy Ma'lumot

FileCache - bu PSR-16 SimpleCache interfeysini to'liq amalga oshiradigan yengil va samarali fayl asosli kesh tizimi. U PHP ilovalaringizda ma'lumotlarni fayl tizimida saqlash orqali keshlashning oddiy va qulay usulini taqdim etadi.

## Xususiyatlar

- ✅ **PSR-16 Standartiga Mos** - `Psr\SimpleCache\CacheInterface` to'liq amalga oshirilgan
- 🗂️ **Ierarxik Saqlash** - Fayllarni samarali tashkil qilish uchun MD5 asosli katalog tuzilmasi
- 🔒 **Fayl Blokirovkasi** - Xavfsiz operatsiyalar uchun to'g'ri fayl blokirovkasi mexanizmi
- ⏰ **TTL Qo'llab-Quvvatlash** - Vaqt bilan avtomatik o'chib ketadigan kesh elementlari
- 🚀 **Ommaviy Operatsiyalar** - Ko'p get/set/delete operatsiyalarini qo'llab-quvvatlash
- 💾 **Seriyalashtirish** - Kesh qiymatlarini avtomatik seriyalashtirish/deseriyalashtirish

## Talablar

- PHP 7.0 yoki undan yuqori versiya
- PSR-16 SimpleCache interfeysi

## O'rnatish

```bash
composer require stoyishi/cache
```

Keyin `autoload.php` klassini loyihangizga qo'shing:

```php
require_once 'vendor/autoload.php';

use Stoyishi\Cache\FileCache;
```

## Foydalanish

### Asosiy Foydalanish

```php
use Stoyishi\Cache\FileCache;


// Keshni ishga tushirish
$cache = new FileCache('/path/to/cache/directory');

// Kesh elementini saqlash
$cache->set('user_123', ['name' => 'John Doe', 'email' => 'john@example.com']);

// Kesh elementini olish
$user = $cache->get('user_123');

// Element mavjudligini tekshirish
if ($cache->has('user_123')) {
    echo "Kesh topildi!";
}

// Kesh elementini o'chirish
$cache->delete('user_123');

// Barcha keshni tozalash
$cache->clear();
```

### Yashash Muddati (TTL)

```php
// 1 soat davomida keshlash (3600 soniya)
$cache->set('session_data', $sessionData, 3600);

// 1 kun davomida keshlash (standart TTL 86400 soniya)
$cache->set('daily_stats', $stats);

// Doimiy saqlash (yoki qo'lda o'chirilmaguncha)
$cache->set('permanent_data', $data, PHP_INT_MAX);
```

### Ommaviy Operatsiyalar

```php
// Bir vaqtning o'zida bir nechta elementlarni saqlash
$cache->setMultiple([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3'
], 3600);

// Bir vaqtning o'zida bir nechta elementlarni olish
$values = $cache->getMultiple(['key1', 'key2', 'key3']);

// Bir nechta elementlarni o'chirish
$cache->deleteMultiple(['key1', 'key2', 'key3']);
```

### Standart Qiymatlar

```php
// Agar kalit topilmasa, standart qiymatni qaytarish
$value = $cache->get('mavjud_emas', 'standart_qiymat');

// Natija: 'standart_qiymat'
```

## API Ma'lumotnomasi

### Konstruktor

```php
public function __construct(string $path)
```

Belgilangan kesh katalogi bilan yangi FileCache nusxasini yaratadi.

**Parametrlar:**
- `$path` - Kesh fayllari saqlanadigan katalog yo'li

### get()

```php
public function get($key, $default = null)
```

Keshdan qiymatni oladi.

**Parametrlar:**
- `$key` - Elementning noyob kaliti
- `$default` - Kalit topilmasa qaytariladigan standart qiymat

**Qaytaradi:** Keshlangan qiymat yoki standart qiymat

### set()

```php
public function set($key, $value, $ttl = null)
```

Ma'lumotni keshda saqlaydi.

**Parametrlar:**
- `$key` - Elementning noyob kaliti
- `$value` - Keshlanadigan qiymat (seriyalashtiriladi)
- `$ttl` - Yashash muddati soniyalarda (null bo'lsa standart 86400)

**Qaytaradi:** Muvaffaqiyatli bo'lsa `true`

### delete()

```php
public function delete($key)
```

Elementni keshdan o'chiradi.

**Parametrlar:**
- `$key` - Elementning noyob kaliti

**Qaytaradi:** Muvaffaqiyatli bo'lsa `true`

### clear()

```php
public function clear()
```

Barcha kesh kalitlarini to'liq tozalaydi.

**Qaytaradi:** Muvaffaqiyatli bo'lsa `true`

### has()

```php
public function has($key)
```

Elementning keshda mavjudligini aniqlaydi.

**Parametrlar:**
- `$key` - Elementning noyob kaliti

**Qaytaradi:** Element mavjud bo'lsa `true`, aks holda `false`

### getMultiple()

```php
public function getMultiple($keys, $default = null)
```

Noyob kalitlar bo'yicha bir nechta kesh elementlarini oladi.

**Parametrlar:**
- `$keys` - Kalitlar massivi
- `$default` - Topilmagan kalitlar uchun standart qiymat

**Qaytaradi:** Kalit-qiymat juftliklari massivi

### setMultiple()

```php
public function setMultiple($values, $ttl = null)
```

Keshda kalit-qiymat juftliklari to'plamini saqlaydi.

**Parametrlar:**
- `$values` - Kalit-qiymat juftliklari massivi
- `$ttl` - Yashash muddati soniyalarda

**Qaytaradi:** Muvaffaqiyatli bo'lsa `true`

### deleteMultiple()

```php
public function deleteMultiple($keys)
```

Bir nechta kesh elementlarini o'chiradi.

**Parametrlar:**
- `$keys` - O'chiriladigan kalitlar massivi

**Qaytaradi:** Muvaffaqiyatli bo'lsa `true`

## Katalog Tuzilmasi

FileCache MD5 xeshlashga asoslangan ierarxik katalog tuzilmasidan foydalanadi:

```
cache/
├── ab/
│   ├── cd/
│   │   ├── abcd1234567890.cache
│   │   └── abcdef9876543210.cache
│   └── ef/
└── 12/
    └── 34/
```

Har bir kesh kaliti MD5 bilan xeshlanadi va birinchi 4 ta belgi pastki katalog tuzilmasini (2 daraja chuqurlik) belgilaydi.

## Xavfsizlik

FileCache operatsiyalarning xavfsizligini ta'minlash uchun PHP ning `flock()` funksiyasidan foydalanadi:
- **Umumiy blokirovka (LOCK_SH)** - o'qish operatsiyalari uchun
- **Eksklyuziv blokirovka (LOCK_EX)** - yozish operatsiyalari uchun

## Ishlash Samaradorligi

1. **Katalog Tuzilmasi**: Ikki darajali katalog ierarxiyasi bitta katalogda juda ko'p fayllar bo'lishining oldini oladi
2. **Fayl Blokirovkasi**: Ma'lumotlar yaxlitligini ta'minlaydi, lekin yuqori yuklamada ishlashga ta'sir qilishi mumkin
3. **Seriyalashtirish**: Maksimal muvofiqlik uchun PHP ning `serialize()` funksiyasidan foydalanadi
4. **Avtomatik Tozalash**: Muddati o'tgan elementlar ularga murojaat qilinganda o'chiriladi

## Eng Yaxshi Amaliyotlar

```php
// 1. Ma'noli kesh kalitlaridan foydalaning
$cache->set('user_profile_' . $userId, $profile);

// 2. Ma'lumotlar o'zgaruvchanligi asosida mos TTL o'rnating
$cache->set('real_time_stock', $price, 60);      // 1 daqiqa
$cache->set('daily_report', $report, 86400);     // 1 kun
$cache->set('static_config', $config, 604800);   // 1 hafta

// 3. Kesh topilmasa, to'g'ri ishlov bering
$data = $cache->get('expensive_query');
if ($data === null) {
    $data = performExpensiveQuery();
    $cache->set('expensive_query', $data, 3600);
}

// 4. Imkon qadar ommaviy operatsiyalardan foydalaning
$cache->setMultiple($bulkData);
```

## Xatolarni Qayta Ishlash

Kesh tizimi umumiy holatlarni avtomatik boshqaradi:
- Kesh katalogi mavjud bo'lmasa, uni yaratadi
- Topilmagan/muddati o'tgan elementlar uchun standart qiymatlarni qaytaradi
- Fayl blokirovkasi xatolarini xavfsiz boshqaradi

## Misol

```php
<?php

use Stoyishi\Cache\FileCache;

// Keshni yaratish
$cache = new FileCache(__DIR__ . '/cache');

// Foydalanuvchi ma'lumotlarini keshlash
$userId = 123;
$userKey = 'user_' . $userId;

// Ma'lumotni keshda tekshirish
$user = $cache->get($userKey);

if ($user === null) {
    // Keshda yo'q, ma'lumotlar bazasidan olish
    $user = [
        'id' => $userId,
        'name' => 'Aziz Rahimov',
        'email' => 'aziz@example.com',
        'phone' => '+998901234567'
    ];
    
    // 1 soat davomida keshlash
    $cache->set($userKey, $user, 3600);
    echo "Ma'lumot bazasidan olindi va keshlandi\n";
} else {
    echo "Keshdan olindi\n";
}

print_r($user);

// Bir nechta foydalanuvchilarni keshlash
$users = [
    'user_1' => ['name' => 'Ali', 'age' => 25],
    'user_2' => ['name' => 'Vali', 'age' => 30],
    'user_3' => ['name' => 'Sardor', 'age' => 28]
];

$cache->setMultiple($users, 7200); // 2 soat

// Barcha foydalanuvchilarni olish
$cachedUsers = $cache->getMultiple(['user_1', 'user_2', 'user_3']);
print_r($cachedUsers);

// Ma'lum bir foydalanuvchini o'chirish
$cache->delete('user_1');

// Barcha keshni tozalash
// $cache->clear();
?>
```

## Litsenziya

Ushbu implementatsiya Stoyishi namespace qismini tashkil qiladi. Foydalanish shartlari uchun loyihangizning litsenziyasiga murojaat qiling.

## Hissa Qo'shish

Hissa qo'shish xush kelibsiz! Quyidagilarga e'tibor bering:
- PSR-16 muvofiqligini saqlang
- Kod PSR-12 kodlash standartlariga amal qilishi kerak
- Barcha operatsiyalar xavfsiz bo'lishi kerak
- Yangi xususiyatlar uchun testlar qo'shing

## Qo'llab-Quvvatlash

Muammolar, savollar yoki hissa qo'shish uchun loyiha ta'minlovchilariga murojaat qiling yoki loyiha omborida muammoni yuborib qo'ying.

---

**Eslatma:** Ushbu kesh tizimi kichik va o'rta darajadagi loyihalar uchun juda mos keladi. Katta yuklama va yuqori trafik uchun Redis yoki Memcached kabi maxsus kesh tizimlaridan foydalanish tavsiya etiladi.
