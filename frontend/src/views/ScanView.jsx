import { useEffect, useRef, useState } from 'react'
import InputField from '../components/InputField'

function ScanView({
  loading,
  user,
  slotStatus,
  callApi,
  refreshProfile,
  onDone,
  setLoading,
  setNotice,
}) {
  const [qrCode, setQrCode] = useState('')
  const [rack, setRack] = useState(null)
  const [cameraActive, setCameraActive] = useState(false)
  const [cameraError, setCameraError] = useState('')
  const [scanAutoSupported, setScanAutoSupported] = useState(true)
  const videoRef = useRef(null)
  const streamRef = useRef(null)
  const rafRef = useRef(null)

  const stopCamera = () => {
    if (rafRef.current) {
      cancelAnimationFrame(rafRef.current)
      rafRef.current = null
    }

    if (streamRef.current) {
      streamRef.current.getTracks().forEach((track) => track.stop())
      streamRef.current = null
    }

    setCameraActive(false)
  }

  const getCameraErrorMessage = (error) => {
    if (!error || !error.name) {
      return 'Kamera tidak dapat dibuka. Silakan coba lagi.'
    }

    if (error.name === 'NotAllowedError') {
      return 'Izin kamera ditolak. Mohon izinkan akses kamera di browser lalu tekan Aktifkan Kamera.'
    }

    if (error.name === 'NotFoundError') {
      return 'Kamera tidak ditemukan pada perangkat ini.'
    }

    if (error.name === 'NotReadableError') {
      return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi lain lalu coba lagi.'
    }

    if (error.name === 'SecurityError') {
      return 'Akses kamera butuh koneksi aman (HTTPS/localhost).'
    }

    return 'Kamera tidak dapat dibuka. Silakan coba lagi.'
  }

  const startScanner = async () => {
    if (!navigator?.mediaDevices?.getUserMedia) {
      setCameraError('Browser tidak mendukung akses kamera. Gunakan input manual QR Code.')
      setNotice('Browser tidak mendukung akses kamera. Gunakan input manual QR Code.')
      return
    }

    stopCamera()
    setCameraError('')

    try {
      const stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'environment' },
        audio: false,
      })

      streamRef.current = stream

      if (videoRef.current) {
        videoRef.current.srcObject = stream
      }

      setCameraActive(true)

      if (!('BarcodeDetector' in window)) {
        setScanAutoSupported(false)
        setNotice('Kamera aktif. Browser belum mendukung scan QR otomatis, gunakan input manual jika perlu.')
        return
      }

      setScanAutoSupported(true)

      const detector = new window.BarcodeDetector({ formats: ['qr_code'] })

      const detectLoop = async () => {
        if (!videoRef.current || !streamRef.current) return

        try {
          const barcodes = await detector.detect(videoRef.current)
          if (barcodes.length > 0 && barcodes[0]?.rawValue) {
            setQrCode(extractQrCode(barcodes[0].rawValue))
            setNotice('QR berhasil dibaca dari kamera.')
            stopCamera()
            return
          }
        } catch {
          // ignore per-frame detection errors
        }

        rafRef.current = requestAnimationFrame(detectLoop)
      }

      rafRef.current = requestAnimationFrame(detectLoop)
    } catch (error) {
      const message = getCameraErrorMessage(error)
      setCameraError(message)
      setNotice(message)
      stopCamera()
    }
  }

  useEffect(() => {
    startScanner()

    return () => {
      stopCamera()
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  const findRack = async () => {
    if (!qrCode.trim()) {
      setNotice('QR code wajib diisi.')
      return
    }

    setLoading(true)
    try {
      const payload = await callApi(`/rak/${encodeURIComponent(qrCode.trim())}/scan`)
      setRack(payload.data)
      setNotice('QR berhasil dibaca.')
    } catch (error) {
      setRack(null)
      setNotice(error.message)
    } finally {
      setLoading(false)
    }
  }

  const submitSlot = async () => {
    if (!rack) {
      setNotice('Rak belum dipilih.')
      return
    }

    const existing = slotStatus?.data
    const mineSlot = rack.detail_classes?.find(
      (item) => Number(item.StudentId) === Number(user?.id),
    )
    const emptySlot = rack.detail_classes?.find((item) => item.StudentId === null)

    setLoading(true)

    try {
      if (existing || mineSlot) {
        const target = existing || mineSlot
        await callApi('/slot/checkout', {
          method: 'POST',
          body: {
            ClassId: target.ClassId,
            Slot: target.Slot,
          },
        })
        setNotice('Check-out berhasil.')
      } else {
        if (!emptySlot) {
          throw new Error('Tidak ada slot kosong pada rak ini.')
        }

        await callApi('/slot/checkin', {
          method: 'POST',
          body: {
            ClassId: rack.id,
            Slot: emptySlot.Slot,
          },
        })
        setNotice(`Check-in berhasil di slot ${emptySlot.Slot}.`)
      }

      await refreshProfile()
      onDone()
    } catch (error) {
      setNotice(error.message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <section className="space-y-4">
      <video
        ref={videoRef}
        className="w-full rounded-xl bg-black"
        autoPlay
        muted
        playsInline
      />

      {!!cameraError && (
        <p className="rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-700">
          {cameraError}
        </p>
      )}

      {!scanAutoSupported && (
        <p className="rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 text-sm text-amber-800">
          Scan otomatis belum didukung di browser ini. Tetap bisa pakai input manual.
        </p>
      )}

      <div className="grid grid-cols-2 gap-2">
        <button
          type="button"
          onClick={startScanner}
          disabled={loading}
          className="rounded-xl bg-cyan-500 py-3 text-sm font-bold text-white disabled:opacity-60"
        >
          {cameraActive ? 'Aktifkan Ulang Kamera' : 'Aktifkan Kamera'}
        </button>
        <button
          type="button"
          onClick={stopCamera}
          disabled={loading || !cameraActive}
          className="rounded-xl bg-slate-500 py-3 text-sm font-bold text-white disabled:opacity-60"
        >
          Matikan Kamera
        </button>
      </div>

      <InputField
        placeholder="Isi/manual QR Code"
        value={qrCode}
        onChange={setQrCode}
      />

      <button
        type="button"
        onClick={findRack}
        disabled={loading}
        className="w-full rounded-xl bg-cyan-500 py-3 text-lg font-bold text-white disabled:opacity-60"
      >
        {loading ? 'Memproses...' : 'Cari Rak'}
      </button>

      {rack && (
        <article className="space-y-2 rounded-xl border border-cyan-300 bg-white p-4 text-sm">
          <p>
            <b>Kelas:</b> {rack.ClassName}
          </p>
          <p>
            <b>Rak:</b> {rack.RackName}
          </p>
          <p>
            <b>Total Slot:</b> {rack.SlotTotal}
          </p>

          <button
            type="button"
            onClick={submitSlot}
            disabled={loading}
            className="w-full rounded-xl bg-indigo-500 py-3 text-base font-bold text-white disabled:opacity-60"
          >
            {loading ? 'Memproses...' : 'Simpan / Ambil via Rak Ini'}
          </button>
        </article>
      )}

      <button
        type="button"
        className="w-full rounded-xl bg-gray-200 py-3 text-sm font-semibold text-gray-800"
        onClick={onDone}
      >
        Kembali ke Dashboard
      </button>
    </section>
  )
}

function extractQrCode(rawValue) {
  const value = String(rawValue || '').trim()
  if (!value) return ''

  const match = value.match(/\/rak\/([^/\s]+)/i)
  if (match?.[1]) {
    return match[1]
  }

  return value
}

export default ScanView
