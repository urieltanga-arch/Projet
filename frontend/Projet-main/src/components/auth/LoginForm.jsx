import { useState } from 'react';
import FormInput from '../common/FormInput';
import LoadingSpinner from '../common/LoadingSpinner';

export default function LoginForm({ 
  isLoading, 
  onLogin, 
  onSwitchToRegister, 
  onSwitchToForgotPassword 
}) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [rememberMe, setRememberMe] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    onLogin({ email, password, rememberMe });
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter' && !isLoading) {
      handleSubmit(e);
    }
  };

  return (
    <div className="bg-white shadow-lg rounded-2xl p-10 border border-amber-100">
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold text-black mb-3">Bon Retour!</h1>
        <p className="text-gray-700 text-lg">Connectez-vous à votre compte Zeduc-sp@ce</p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-6">
        <FormInput
          label="Adresse Email"
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          onKeyPress={handleKeyPress}
          placeholder="Votre@email.com"
          disabled={isLoading}
        />

        <FormInput
          label="Mot de Passe"
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          onKeyPress={handleKeyPress}
          placeholder="****************"
          disabled={isLoading}
          showPasswordToggle
          isPasswordVisible={showPassword}
          onTogglePassword={() => setShowPassword(!showPassword)}
        />

        <div className="flex items-center justify-between">
          <label className="flex items-center gap-3 cursor-pointer">
            <input
              type="checkbox"
              checked={rememberMe}
              onChange={(e) => setRememberMe(e.target.checked)}
              disabled={isLoading}
              className="w-5 h-5 rounded border-2 border-gray-400 text-amber-500 focus:ring focus:ring-amber-400/50 disabled:opacity-50"
            />
            <span className="text-black font-medium">Se souvenir de moi</span>
          </label>
          
          <button 
            type="button"
            onClick={onSwitchToForgotPassword}
            className="text-amber-500 hover:text-amber-600 font-medium transition-colors disabled:opacity-50"
            disabled={isLoading}
          >
            Mot de passe oublié?
          </button>
        </div>

        <button
          type="submit"
          disabled={isLoading}
          className={`w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02] ${
            isLoading ? 'opacity-50 cursor-not-allowed' : ''
          }`}
        >
          {isLoading ? <LoadingSpinner text="Connexion en cours..." /> : 'Se connecter'}
        </button>

        <p className="text-center text-gray-700">
          Pas encore de compte ?{' '}
          <button 
            type="button"
            onClick={onSwitchToRegister}
            className="text-amber-500 hover:text-amber-600 font-semibold transition-colors disabled:opacity-50"
            disabled={isLoading}
          >
            Créer un compte
          </button>
        </p>
      </form>
    </div>
  );
}
