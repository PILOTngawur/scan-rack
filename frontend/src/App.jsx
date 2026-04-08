import { useEffect, useMemo, useState } from 'react'
import DashboardView from './views/DashboardView'
import ScanView from './views/ScanView'
import AuthView from './views/AuthView'
import { callApi } from './lib/api'

const VIEW = {
  LOGIN: 'login',
  REGISTER: 'register',
  DASHBOARD: 'dashboard',
  SCAN: 'scan',
}

function App() {
  const [view, setView] = useState(VIEW.LOGIN)
  const [token, setToken] = useState(localStorage.getItem('scanrack_token') || '')
  const [user, setUser] = useState(null)
  const [slotStatus, setSlotStatus] = useState(null)
  const [classes, setClasses] = useState([])
  const [notice, setNotice] = useState('')
  const [loading, setLoading] = useState(false)

  const isCheckedIn = slotStatus?.status === 'checked_in'

  const name = useMemo(() => user?.FullName || '-', [user])
  const className = useMemo(() => user?.class?.ClassName || '-', [user])
  const deviceName = useMemo(() => user?.DeviceName || '', [user])

  const api = (path, options = {}) => callApi(path, { token, ...options })
  const publicApi = (path, options = {}) => callApi(path, options)

  const lookupMasterStudentByNis = async (nis) => {
    const payload = await publicApi(`/master-students/by-nis/${encodeURIComponent(nis)}`)
    return payload?.data || null
  }

  const fetchClasses = async () => {
    try {
      const payload = await callApi('/classes')
      setClasses(payload.data || [])
    } catch {
      setClasses([])
    }
  }

  useEffect(() => {
    fetchClasses()
  }, [])

  const refreshProfile = async () => {
    if (!token) {
      setUser(null)
      setSlotStatus(null)
      setView(VIEW.LOGIN)
      return
    }

    try {
      const [mePayload, slotPayload] = await Promise.all([
        api('/me'),
        api('/slot/me'),
      ])

      setUser(mePayload.user || null)
      setSlotStatus(slotPayload)
      setView(VIEW.DASHBOARD)
    } catch {
      localStorage.removeItem('scanrack_token')
      setToken('')
      setUser(null)
      setSlotStatus(null)
      setView(VIEW.LOGIN)
    }
  }

  useEffect(() => {
    refreshProfile()
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [token])

  const saveToken = (nextToken) => {
    localStorage.setItem('scanrack_token', nextToken)
    setToken(nextToken)
  }

  const onLogin = async (form) => {
    setLoading(true)
    try {
      const payload = await api('/login', {
        method: 'POST',
        body: form,
      })
      saveToken(payload.token)
      setNotice('Login berhasil.')
    } catch (error) {
      setNotice(error.message)
    } finally {
      setLoading(false)
    }
  }

  const onRegister = async (form) => {
    setLoading(true)
    try {
      const payload = await api('/register', {
        method: 'POST',
        body: form,
      })
      saveToken(payload.token)
      setNotice('Registrasi berhasil.')
    } catch (error) {
      setNotice(error.message)
    } finally {
      setLoading(false)
    }
  }

  const onLogout = async () => {
    try {
      await api('/logout', { method: 'POST' })
    } catch {
      // ignore network/server error on logout
    }

    localStorage.removeItem('scanrack_token')
    setToken('')
    setUser(null)
    setSlotStatus(null)
    setView(VIEW.LOGIN)
    setNotice('Anda sudah logout.')
  }

  const onCheckout = async () => {
    const slot = slotStatus?.data
    if (!slot) return

    setLoading(true)
    try {
      await api('/slot/checkout', {
        method: 'POST',
        body: {
          ClassId: slot.ClassId,
          Slot: slot.Slot,
        },
      })
      await refreshProfile()
      setNotice('Check-out berhasil.')
    } catch (error) {
      setNotice(error.message)
    } finally {
      setLoading(false)
    }
  }

  const onSaveDeviceName = async (nextDeviceName) => {
    setLoading(true)
    try {
      const payload = await api('/me/device-name', {
        method: 'PATCH',
        body: {
          device_name: nextDeviceName,
        },
      })

      setUser(payload.user || null)
      setNotice('Tipe handphone berhasil disimpan.')
      return true
    } catch (error) {
      setNotice(error.message)
      return false
    } finally {
      setLoading(false)
    }
  }

  return (
  <main className="relative min-h-screen bg-gradient-to-b from-cyan-300 to-slate-600 py-6 px-4 text-gray-900">
      {(view === VIEW.DASHBOARD || view === VIEW.SCAN) && (
        <button
          type="button"
          onClick={onLogout}
          className="absolute right-4 top-4 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white shadow-lg shadow-black/30 transition hover:bg-slate-800"
          title="Logout"
          aria-label="Logout"
        >
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
          >
            <path
              d="M9 6V5C9 3.9 9.9 3 11 3H18C19.1 3 20 3.9 20 5V19C20 20.1 19.1 21 18 21H11C9.9 21 9 20.1 9 19V18"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
            <path
              d="M14 12H4"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
            <path
              d="M7 9L4 12L7 15"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </button>
      )}

      <div className="mx-auto w-full max-w-md rounded-xl bg-slate-100 shadow-2xl shadow-cyan-900/30">
        <div className="rounded-t-xl bg-gradient-to-r from-cyan-300 to-cyan-200 p-4">
          <h1 className="text-center text-2xl font-bold uppercase tracking-wide">
            {view === VIEW.LOGIN && 'Login'}
            {view === VIEW.REGISTER && 'Register'}
            {view === VIEW.SCAN && 'Scan QR'}
          </h1>
        </div>

        <div className="space-y-4 p-5">
          {notice && (
            <p className="rounded-lg border border-cyan-300 bg-cyan-50 px-3 py-2 text-center text-sm text-cyan-800">
              {notice}
            </p>
          )}

          {(view === VIEW.LOGIN || view === VIEW.REGISTER) && (
            <AuthView
              view={view}
              VIEW={VIEW}
              classes={classes}
              onLookupNis={lookupMasterStudentByNis}
              loading={loading}
              onLogin={onLogin}
              onRegister={onRegister}
              setNotice={setNotice}
              setView={setView}
            />
          )}

          {view === VIEW.DASHBOARD && (
            <DashboardView
              loading={loading}
              name={name}
              className={className}
              deviceName={deviceName}
              onSaveDeviceName={onSaveDeviceName}
              isCheckedIn={isCheckedIn}
              onScan={() => {
                setNotice('')
                setView(VIEW.SCAN)
              }}
              onCheckout={onCheckout}
            />
          )}

          {view === VIEW.SCAN && (
            <ScanView
              loading={loading}
              user={user}
              slotStatus={slotStatus}
              callApi={api}
              refreshProfile={refreshProfile}
              onDone={() => setView(VIEW.DASHBOARD)}
              setLoading={setLoading}
              setNotice={setNotice}
            />
          )}
        </div>
      </div>
    </main>
  )
}

export default App
