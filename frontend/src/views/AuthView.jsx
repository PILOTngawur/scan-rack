import { useEffect, useState } from 'react'
import InputField from '../components/InputField'

function LoginView({ loading, onLogin, goRegister }) {
  const [form, setForm] = useState({ email: '', password: '' })

  const submit = (event) => {
    event.preventDefault()
    onLogin(form)
  }

  return (
    <form className="space-y-4" onSubmit={submit}>
      <InputField
        placeholder="Email"
        type="email"
        value={form.email}
        onChange={(value) => setForm((old) => ({ ...old, email: value }))}
      />
      <InputField
        placeholder="Password"
        type="password"
        value={form.password}
        onChange={(value) => setForm((old) => ({ ...old, password: value }))}
      />

      <p className="text-center text-sm text-gray-700">
        Belum punya akun?{' '}
        <button
          type="button"
          className="font-semibold text-indigo-700 underline"
          onClick={goRegister}
        >
          REGISTER
        </button>
      </p>

      <button
        type="submit"
        disabled={loading}
        className="w-full rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 py-3 text-lg font-semibold text-white disabled:opacity-60"
      >
        {loading ? 'Memproses...' : 'MASUK'}
      </button>
    </form>
  )
}

function RegisterView({ loading, onRegister, goLogin, classes = [], onLookupNis }) {
  const [form, setForm] = useState({
    FullName: '',
    ClassId: '',
    NISNUPTK: '',
    email: '',
    password: '',
    password_confirmation: '',
  })
  const [nisMessage, setNisMessage] = useState('')
  const [nisChecking, setNisChecking] = useState(false)

  useEffect(() => {
    const nis = String(form.NISNUPTK).trim()

    if (!nis) {
      setNisMessage('')
      setNisChecking(false)
      setForm((old) => ({ ...old, FullName: '' }))
      return
    }

    if (!onLookupNis) {
      return
    }

    let cancelled = false

    const timeoutId = setTimeout(async () => {
      setNisChecking(true)
      try {
        const data = await onLookupNis(nis)
        if (cancelled) return

        setForm((old) => ({ ...old, FullName: data?.FullName || '' }))
        setNisMessage(data?.FullName ? '' : 'NIS tidak ada.')
      } catch (error) {
        if (cancelled) return

        setForm((old) => ({ ...old, FullName: '' }))
        setNisMessage(error.message || 'NIS tidak ada.')
      } finally {
        if (!cancelled) {
          setNisChecking(false)
        }
      }
    }, 1000)

    return () => {
      cancelled = true
      clearTimeout(timeoutId)
    }
  }, [form.NISNUPTK, onLookupNis])

  const submit = (event) => {
    event.preventDefault()
    onRegister({
      ...form,
      ClassId: Number(form.ClassId),
      NISNUPTK: Number(form.NISNUPTK),
    })
  }

  return (
    <form className="space-y-3" onSubmit={submit}>
      <button
        type="button"
        className="text-sm font-medium text-indigo-700 underline"
        onClick={goLogin}
      >
        &lt; Login
      </button>

      <p className="rounded-lg border border-cyan-300 bg-cyan-50 px-3 py-2 text-xs text-cyan-800">
        Nama siswa akan otomatis diambil dari data master berdasarkan NIS.
      </p>

      <InputField
        placeholder="NIS"
        type="number"
        value={form.NISNUPTK}
        onChange={(value) => setForm((old) => ({ ...old, NISNUPTK: value }))}
      />

      {String(form.NISNUPTK).trim() !== '' && (
        <>
          <input
            className="w-full cursor-not-allowed rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 text-base text-gray-500 shadow-sm"
            value={
              nisChecking
                ? 'Mengecek NIS...'
                : form.FullName || 'Nama akan terisi otomatis saat data NIS valid'
            }
            disabled
            readOnly
          />

          {nisMessage && (
            <p className="text-sm font-medium text-red-600">{nisMessage}</p>
          )}
        </>
      )}

      <select
        className="w-full rounded-xl border border-gray-400 bg-white px-4 py-3 text-base shadow-sm focus:border-cyan-500 focus:outline-none"
        value={form.ClassId}
        onChange={(event) =>
          setForm((old) => ({ ...old, ClassId: event.target.value }))
        }
        required
      >
        <option value="" disabled>
          Pilih Kelas
        </option>
        {classes.map((item) => (
          <option key={item.id} value={item.id}>
            {item.ClassName}
          </option>
        ))}
      </select>
      <InputField
        placeholder="Email"
        type="email"
        value={form.email}
        onChange={(value) => setForm((old) => ({ ...old, email: value }))}
      />
      <InputField
        placeholder="Password"
        type="password"
        value={form.password}
        onChange={(value) => setForm((old) => ({ ...old, password: value }))}
      />
      <InputField
        placeholder="Konfirmasi Password"
        type="password"
        value={form.password_confirmation}
        onChange={(value) =>
          setForm((old) => ({ ...old, password_confirmation: value }))
        }
      />

      <button
        type="submit"
        disabled={loading || !form.FullName}
        className="w-full rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 py-3 text-lg font-semibold text-white disabled:opacity-60"
      >
        {loading ? 'Memproses...' : 'DAFTAR'}
      </button>
    </form>
  )
}

function AuthView({
  view,
  loading,
  onLogin,
  onRegister,
  setNotice,
  setView,
  VIEW,
  classes,
  onLookupNis,
}) {
  return (
    <>
      {view === VIEW.LOGIN && (
        <LoginView
          loading={loading}
          onLogin={onLogin}
          goRegister={() => {
            setNotice('')
            setView(VIEW.REGISTER)
          }}
        />
      )}

      {view === VIEW.REGISTER && (
        <RegisterView
          loading={loading}
          onRegister={onRegister}
          classes={classes}
          onLookupNis={onLookupNis}
          goLogin={() => {
            setNotice('')
            setView(VIEW.LOGIN)
          }}
        />
      )}
    </>
  )
}

export default AuthView
