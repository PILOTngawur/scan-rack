import { useEffect, useState } from 'react'
import InputField from '../components/InputField'

function PasswordField({ placeholder, value, onChange }) {
  const [showPassword, setShowPassword] = useState(false)

  return (
    <div className="relative">
      <input
        className="w-full rounded-xl border border-gray-400 bg-white px-4 py-3 pr-12 text-base shadow-sm focus:border-cyan-500 focus:outline-none"
        type={showPassword ? 'text' : 'password'}
        placeholder={placeholder}
        value={value}
        onChange={(event) => onChange(event.target.value)}
        required
      />

      <button
        type="button"
        onClick={() => setShowPassword((old) => !old)}
        className="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
        aria-label={showPassword ? 'Sembunyikan password' : 'Tampilkan password'}
      >
        {showPassword ? (
          <svg
            className="h-5 w-5"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            viewBox="0 0 24 24"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.15-2.399A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.966 9.966 0 01-4.293 5.225M15 12a3 3 0 11-6 0 3 3 0 016 0z"
            />
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M3 3l18 18"
            />
          </svg>
        ) : (
          <svg
            className="h-5 w-5"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
            />
          </svg>
        )}
      </button>
    </div>
  )
}

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
      <PasswordField
        placeholder="Password"
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
      <PasswordField
        placeholder="Password"
        value={form.password}
        onChange={(value) => setForm((old) => ({ ...old, password: value }))}
      />
      <PasswordField
        placeholder="Konfirmasi Password"
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
