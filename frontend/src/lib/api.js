export const API_BASE_URL =
  import.meta.env.VITE_API_BASE_URL?.trim() || 'http://127.0.0.1:8000/api'

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
