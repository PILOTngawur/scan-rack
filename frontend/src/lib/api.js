const fallbackHost = typeof window !== 'undefined' ? window.location.hostname : '127.0.0.1'
const fallbackOrigin = typeof window !== 'undefined' ? window.location.origin : 'http://127.0.0.1'

function resolveApiBaseUrl() {
  if (import.meta.env.DEV) {
    return '/api'
  }

  const envBaseUrl = import.meta.env.VITE_API_BASE_URL?.trim()

  if (!envBaseUrl) {
    return `${fallbackOrigin.replace(/\/$/, '')}/api`
  }

  try {
    const parsed = new URL(envBaseUrl)
    const envHost = parsed.hostname
    const isEnvLocalHost = envHost === 'localhost' || envHost === '127.0.0.1'
    const isCurrentLocalHost = fallbackHost === 'localhost' || fallbackHost === '127.0.0.1'

    if (isEnvLocalHost && !isCurrentLocalHost) {
      parsed.hostname = fallbackHost
      return parsed.toString().replace(/\/$/, '')
    }

    return envBaseUrl
  } catch {
    return `${fallbackOrigin.replace(/\/$/, '')}/api`
  }
}

export const API_BASE_URL = resolveApiBaseUrl()

export async function callApi(path, { token, method = 'GET', body, headers = {} } = {}) {
  const finalHeaders = {
    'Content-Type': 'application/json',
    ...headers,
  }

  if (token) {
    finalHeaders.Authorization = `Bearer ${token}`
  }

  let response

  try {
    response = await fetch(`${API_BASE_URL}${path}`, {
      method,
      headers: finalHeaders,
      body: body ? JSON.stringify(body) : undefined,
    })
  } catch {
    throw new Error('Tidak dapat terhubung ke server.')
  }

  const payload = await response.json().catch(() => ({}))

  if (!response.ok) {
    const validationErrors = payload?.errors
      ? Object.values(payload.errors).flat().filter(Boolean)
      : []

    throw new Error(
      payload.message || validationErrors[0] || 'Terjadi kesalahan pada server.',
    )
  }

  return payload
}

export function getDeviceName() {
  const platform = navigator.platform || 'unknown-platform'
  const ua = navigator.userAgent || 'unknown-ua'

  return `${platform} | ${ua}`.slice(0, 255)
}
