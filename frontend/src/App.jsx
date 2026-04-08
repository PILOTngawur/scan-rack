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
    <main className="min-h-screen bg-gray-900 py-6 px-4 text-gray-900">
      <div className="mx-auto w-full max-w-md rounded-xl bg-slate-100 shadow-2xl shadow-cyan-900/30">
        <div className="rounded-t-xl bg-gradient-to-r from-cyan-300 to-cyan-200 p-4">
          <h1 className="text-center text-2xl font-bold uppercase tracking-wide">
            {view === VIEW.LOGIN && 'Login'}
            {view === VIEW.REGISTER && 'Register'}
            {view === VIEW.DASHBOARD && 'Dashboard'}
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
              onLogout={onLogout}
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
