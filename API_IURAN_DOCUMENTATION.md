# API Iuran Transactions

**Base URL:** `https://booking-nicc.mydscm.id/api`

**Header:** `X-API-Key: iuran-secret-key-2026`

---

## 1. Simpan Data

**POST** `/api/iuran`

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<void> simpanIuran() async {
  var request = http.MultipartRequest(
    'POST',
    Uri.parse('https://booking-nicc.mydscm.id/api/iuran'),
  );

  request.headers['X-API-Key'] = 'iuran-secret-key-2026';

  request.fields['tanggal'] = '2026-01-06';
  request.fields['jam'] = '14:30';
  request.fields['dari'] = 'John Doe';
  request.fields['keterangan'] = 'Iuran bulanan';
  request.fields['nominal'] = '150000';

  // Jika ada foto
  request.files.add(await http.MultipartFile.fromPath('bukti_foto', '/path/to/image.jpg'));

  var response = await request.send();
  var responseBody = await response.stream.bytesToString();
  print(responseBody);
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Data berhasil disimpan",
  "data": {
    "id": 1,
    "tanggal": "2026-01-06",
    "jam": "14:30",
    "dari": "John Doe",
    "keterangan": "Iuran bulanan",
    "nominal": 150000,
    "bukti_foto": "/storage/iuran/abc123.jpg"
  }
}
```

---

## 2. List Data

**GET** `/api/iuran`

```dart
Future<void> getIuran() async {
  var response = await http.get(
    Uri.parse('https://booking-nicc.mydscm.id/api/iuran?tanggal_dari=2026-01-01&tanggal_sampai=2026-01-31&dari=John'),
    headers: {'X-API-Key': 'iuran-secret-key-2026'},
  );
  print(response.body);
}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "tanggal": "2026-01-06",
      "jam": "14:30:00",
      "dari": "John Doe",
      "keterangan": "Iuran bulanan",
      "nominal": "150000.00",
      "bukti_foto": "/storage/iuran/abc123.jpg"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 1,
    "last_page": 1
  }
}
```

**Filter yang tersedia:**
- `tanggal_dari` - YYYY-MM-DD
- `tanggal_sampai` - YYYY-MM-DD
- `dari` - string (partial match)
- `per_page` - jumlah per halaman

---

## 3. Detail Data

**GET** `/api/iuran/{id}`

```dart
Future<void> getDetail() async {
  var response = await http.get(
    Uri.parse('https://booking-nicc.mydscm.id/api/iuran/1'),
    headers: {'X-API-Key': 'iuran-secret-key-2026'},
  );
  print(response.body);
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "tanggal": "2026-01-06",
    "jam": "14:30:00",
    "dari": "John Doe",
    "keterangan": "Iuran bulanan",
    "nominal": "150000.00",
    "bukti_foto": "/storage/iuran/abc123.jpg"
  }
}
```

---

## 4. Hapus Data

**DELETE** `/api/iuran/{id}`

```dart
Future<void> hapusIuran() async {
  var response = await http.delete(
    Uri.parse('https://booking-nicc.mydscm.id/api/iuran/1'),
    headers: {'X-API-Key': 'iuran-secret-key-2026'},
  );
  print(response.body);
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Data berhasil dihapus"
}
```

---

## Error Response

**API Key tidak valid (401):**
```json
{
  "success": false,
  "message": "API key tidak valid"
}
```

**Validasi gagal (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "tanggal": ["Field tanggal wajib diisi"]
  }
}
```
