import { useState } from 'react';
import FormInput from '../common/FormInput';
import LoadingSpinner from '../common/LoadingSpinner';

export default function ForgotPasswordForm({ 
  isLoading, 
  onForgotPassword, 
  onBackToLogin 
}) {
  const [email, setEmail] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    onForgotPassword(email);
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter' && !isLoading) {
      handleSubmit(e);
    }
  };

  return (
    <>
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold text-black mb-3">Mot de passe oublié?</h1>
        <p className="text-gray-700 text-lg">
          Entrez votre email pour recevoir un lien de réinitialisation
        </p>
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

        <button
          type="submit"
          disabled={isLoading}
          className={`w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02] ${
            isLoading ? 'opacity-50 cursor-not-allowed' : ''
          }`}
        >
          {isLoading ? <LoadingSpinner text="Envoi en cours..." /> : 'Envoyer le lien'}
        </button>

        <p className="text-center text-gray-700">
          <button 
            type="button"
            onClick={onBackToLogin}
            className="text-amber-500 hover:text-amber-600 font-semibold transition-colors disabled:opacity-50"
            disabled={isLoading}
          >
            ← Retour à la connexion
          </button>
        </p>
      </form>
    </>
  );
}