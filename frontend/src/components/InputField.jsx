function InputField({ placeholder, value, onChange, type = 'text' }) {
  return (
    <input
      className="w-full rounded-xl border border-gray-400 bg-white px-4 py-3 text-base shadow-sm focus:border-cyan-500 focus:outline-none"
      type={type}
      placeholder={placeholder}
      value={value}
      onChange={(event) => onChange(event.target.value)}
      required
    />
  )
}

export default InputField
