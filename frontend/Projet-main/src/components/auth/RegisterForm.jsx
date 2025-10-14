import { useState } from 'react';
import { HiPhone } from 'react-icons/hi';
import FormInput from '../common/FormInput';
import LoadingSpinner from '../common/LoadingSpinner';

export default function RegisterForm({ 
  isLoading, 
  onRegister, 
  onSwitchToLogin 
}) {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    password: '',
    confirmPassword: '',
    countryCode: '+237'
  });
  
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [acceptTerms, setAcceptTerms] = useState(false);
  const [acceptCookies, setAcceptCookies] = useState(false);

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onRegister({ ...formData, acceptTerms, acceptCookies });
  };

  return (
    <div className="bg-white shadow-lg rounded-2xl p-10 border border-amber-100">
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold text-black mb-3">Rejoignez-Nous!</h1>
        <p className="text-gray-700 text-lg">Créez votre compte Zeduc-sp@ce</p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-6">
        <FormInput
          label="Nom complet"
          type="text"
          value={formData.name}
          onChange={(e) => handleChange('name', e.target.value)}
          placeholder="Jean Dupont"
          disabled={isLoading}
        />

        <FormInput
          label="Adresse Email"
          type="email"
          value={formData.email}
          onChange={(e) => handleChange('email', e.target.value)}
          placeholder="votre@email.com"
          disabled={isLoading}
        />

        {/* Champ téléphone */}
        <div>
          <label className="block text-black font-semibold mb-2">
            Numéro de téléphone
          </label>
          <div className="flex gap-2">
            <select
              value={formData.countryCode}
              onChange={(e) => handleChange('countryCode', e.target.value)}
              disabled={isLoading}
              className="w-32 px-3 py-4 rounded-xl bg-amber-50 border-none text-gray-700 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
            >
              <option value="+237">🇨🇲 +237</option>
              <option value="+33">🇫🇷 +33</option>
              <option value="+1">🇺🇸 +1</option>
              <option value="+44">🇬🇧 +44</option>
              <option value="+225">🇨🇮 +225</option>
              <option value="+221">🇸🇳 +221</option>
              <option value="+243">🇨🇩 +243</option>
            </select>
            
            <div className="flex-1 relative">
              <div className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <HiPhone className="w-5 h-5" />
              </div>
              <input
                type="tel"
                value={formData.phone}
                onChange={(e) => handleChange('phone', e.target.value.replace(/[^\d]/g, ''))}
                placeholder="6 XX XX XX XX"
                disabled={isLoading}
                maxLength={15}
                className="w-full pl-12 pr-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
              />
            </div>
          </div>
          <p className="mt-1 text-xs text-gray-500">
            Sélectionnez votre code pays et entrez votre numéro
          </p>
        </div>

        <FormInput
          label="Mot de Passe"
          type="password"
          value={formData.password}
          onChange={(e) => handleChange('password', e.target.value)}
          placeholder="Minimum 6 caractères"
          disabled={isLoading}
          showPasswordToggle
          isPasswordVisible={showPassword}
          onTogglePassword={() => setShowPassword(!showPassword)}
        />

        <FormInput
          label="Confirmer le mot de passe"
          type="password"
          value={formData.confirmPassword}
          onChange={(e) => handleChange('confirmPassword', e.target.value)}
          placeholder="Retapez votre mot de passe"
          disabled={isLoading}
          showPasswordToggle
          isPasswordVisible={showConfirmPassword}
          onTogglePassword={() => setShowConfirmPassword(!showConfirmPassword)}
        />

        <div>
          <label className="flex items-start gap-3 cursor-pointer">
            <input
              type="checkbox"
              checked={acceptTerms}
              onChange={(e) => setAcceptTerms(e.target.checked)}
              disabled={isLoading}
              className="w-5 h-5 mt-0.5 rounded border-2 border-gray-400 text-amber-500 focus:ring focus:ring-amber-400/50 disabled:opacity-50"
            />
            <span className="text-sm text-gray-700">
              J'accepte les{' '}
              <button type="button" className="text-amber-500 hover:text-amber-600 font-medium">
                conditions d'utilisation
              </button>
            </span>
          </label>
        </div>

        <div>
          <label className="flex items-start gap-3 cursor-pointer">
            <input
              type="checkbox"
              checked={acceptCookies}
              onChange={(e) => setAcceptCookies(e.target.checked)}
              disabled={isLoading}
              className="w-5 h-5 mt-0.5 rounded border-2 border-gray-400 text-amber-500 focus:ring focus:ring-amber-400/50 disabled:opacity-50"
            />
            <span className="text-sm text-gray-700">
              J'accepte les{' '}
              <button type="button" className="text-amber-500 hover:text-amber-600 font-medium">
                Utilisations des cookies
              </button>
              {' '}et la{' '}
              <button type="button" className="text-amber-500 hover:text-amber-600 font-medium">
                politique de confidentialité
              </button>
            </span>
          </label>
        </div>

        <button
          type="submit"
          disabled={isLoading}
          className={`w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02] ${
            isLoading ? 'opacity-50 cursor-not-allowed' : ''
          }`}
        >
          {isLoading ? <LoadingSpinner text="Inscription en cours..." /> : 'Inscrire'}
        </button>

        <p className="text-center text-gray-700">
          Déjà un compte ?{' '}
          <button 
            type="button"
            onClick={onSwitchToLogin}
            className="text-amber-500 hover:text-amber-600 font-semibold transition-colors disabled:opacity-50"
            disabled={isLoading}
          >
            Se connecter
          </button>
        </p>
      </form>
    </div>
  );
}