# Frontend Siswa - Scan Rak

Frontend ini dibuat untuk siswa agar bisa:

- Login / register
- Lihat status pengumpulan HP (belum/sudah)
- Scan QR rak
- Check-in (simpan HP) / check-out (ambil HP)

## Stack

- React + Vite
- Tailwind CSS (via CDN di `index.html`)
- Backend API Laravel (`backend`)

## Konfigurasi

Salin `.env.example` menjadi `.env`, lalu isi alamat backend API:

- `VITE_API_BASE_URL=http://127.0.0.1:8000/api`

Untuk production di Vercel (frontend) dengan backend di domain lain (mis. Rumahweb):

- Set environment variable Vercel:
	- `VITE_API_BASE_URL=https://erzzdev.xyz/api`

## Jalankan

1. Jalankan backend Laravel terlebih dahulu.
2. Jalankan frontend:
	- `npm install`
	- `npm run dev`

## Catatan Integrasi Backend

Agar frontend siswa bisa jalan stabil, backend API memakai token sederhana di field `remember_token` dan metadata device di `DeviceName`.

Endpoint yang dipakai frontend:

- `POST /api/register`
- `POST /api/login`
- `POST /api/logout`
- `GET /api/me`
- `GET /api/slot/me`
- `GET /api/rak/{qrCode}/scan`
- `POST /api/slot/checkin`
- `POST /api/slot/checkout`

## Troubleshooting

- Jika kamera tidak bisa diakses, gunakan input QR manual pada halaman scan.
- Jika request gagal, cek apakah URL `VITE_API_BASE_URL` sudah benar.
- Jika frontend di Vercel dan backend beda domain, pastikan backend mengizinkan origin Vercel di CORS.
- Pastikan data rak + slot sudah tersedia dari admin backend.
