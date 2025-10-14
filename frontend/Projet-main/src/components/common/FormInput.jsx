import { Eye, EyeOff } from 'lucide-react';

export default function FormInput({
  label,
  type = 'text',
  value,
  onChange,
  placeholder,
  disabled = false,
  showPasswordToggle = false,
  isPasswordVisible = false,
  onTogglePassword = () => {},
  ...props
}) {
  return (
    <div>
      {label && (
        <label className="block text-black font-semibold mb-2">
          {label}
        </label>
      )}
      <div className="relative">
        <input
          type={showPasswordToggle && isPasswordVisible ? 'text' : type}
          value={value}
          onChange={onChange}
          placeholder={placeholder}
          disabled={disabled}
          className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
          {...props}
        />
        {showPasswordToggle && (
          <button
            type="button"
            onClick={onTogglePassword}
            disabled={disabled}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50"
          >
            {isPasswordVisible ? <Eye className="w-5 h-5" /> : <EyeOff className="w-5 h-5" />}
          </button>
        )}
      </div>
    </div>
  );
}