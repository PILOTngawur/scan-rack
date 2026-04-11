import { useState } from 'react'

function DashboardView({
  loading,
  name,
  className,
  deviceName,
  onSaveDeviceName,
  isCheckedIn,
  onScan,
  onCheckout,
}) {
  const [editing, setEditing] = useState(false)
  const [draftDeviceName, setDraftDeviceName] = useState(deviceName || '')

  const submitDeviceName = async (event) => {
    event.preventDefault()
    const value = draftDeviceName.trim()
    if (!value) return

    const ok = await onSaveDeviceName(value)
    if (ok) {
      setEditing(false)
    }
  }

  return (
    <section className="space-y-4">
      <article className="rounded-xl bg-white p-4 text-center shadow">
        <div className="mx-auto mb-2 flex h-14 w-14 items-center justify-center rounded-full bg-gray-200 text-2xl">
          👤
        </div>
        <h2 className="text-2xl font-bold">{name}</h2>
        <p className="text-sm font-semibold text-gray-600">{className}</p>
        {!editing && (
          <div className="mt-2 flex items-center justify-center gap-2 text-sm font-medium text-gray-700">
            <span>📱 {deviceName || 'Belum diisi'}</span>
            <button
              type="button"
              onClick={() => {
                setDraftDeviceName(deviceName || '')
                setEditing(true)
              }}
              className="rounded px-2 py-1 text-xs font-semibold text-indigo-700 underline"
            >
              Edit
            </button>
          </div>
        )}

        {editing && (
          <form className="mt-3 flex gap-2" onSubmit={submitDeviceName}>
            <input
              className="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm"
              placeholder="Contoh: iPhone 15"
              value={draftDeviceName}
              onChange={(event) => setDraftDeviceName(event.target.value)}
              required
            />
            <button
              type="submit"
              disabled={loading}
              className="rounded-lg bg-indigo-500 px-3 py-2 text-sm font-semibold text-white disabled:opacity-60"
            >
              Simpan
            </button>
          </form>
        )}
      </article>

      <div
        className={`rounded-xl px-4 py-3 text-center text-2xl font-bold text-white ${
          isCheckedIn ? 'bg-green-500' : 'bg-red-500'
        }`}
      >
        {isCheckedIn ? 'DI Kumpulkan' : 'Belum Mengumpulkan'}
      </div>

      <button
        type="button"
        onClick={onScan}
        className="w-full rounded-xl border-2 border-cyan-400 bg-white py-4 text-2xl font-bold"
      >
        <span className="flex items-center justify-center gap-3">
          <svg
            width="34"
            height="34"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            className="text-gray-800"
            aria-hidden="true"
          >
            <path
              d="M3 8.5C3 7.12 4.12 6 5.5 6H8L9.2 4.4C9.58 3.9 10.17 3.6 10.8 3.6H13.2C13.83 3.6 14.42 3.9 14.8 4.4L16 6H18.5C19.88 6 21 7.12 21 8.5V17.5C21 18.88 19.88 20 18.5 20H5.5C4.12 20 3 18.88 3 17.5V8.5Z"
              stroke="currentColor"
              strokeWidth="1.8"
            />
            <circle cx="12" cy="13" r="4" stroke="currentColor" strokeWidth="1.8" />
          </svg>
          <span>Scan QR</span>
        </span>
      </button>

      {isCheckedIn && (
        <button
          type="button"
          onClick={onCheckout}
          disabled={loading}
          className="w-full rounded-xl bg-yellow-500 py-3 text-lg font-bold text-gray-900 disabled:opacity-60"
        >
          {loading ? 'Memproses...' : 'Ambil Handphone'}
        </button>
      )}
    </section>
  )
}

export default DashboardView
