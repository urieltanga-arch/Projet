import { useState } from 'react';
import { ArrowLeft, Eye, EyeOff } from 'lucide-react';


export default function LoginPage() {
  // ========================================
// ÉTATS GÉNÉRAUX
// ========================================
const [activeTab, setActiveTab] = useState('connexion');
const [activeForm, setActiveForm] = useState('login'); // NOUVEAU : 'login', 'register', 'forgot'
const [isLoading, setIsLoading] = useState(false);

// ÉTATS POUR LA CONNEXION
const [loginEmail, setLoginEmail] = useState('');
const [loginPassword, setLoginPassword] = useState('');
const [rememberMe, setRememberMe] = useState(false);
const [showLoginPassword, setShowLoginPassword] = useState(false);

// ÉTATS POUR L'INSCRIPTION (NOUVEAU)
const [registerName, setRegisterName] = useState('');
const [registerEmail, setRegisterEmail] = useState('');
const [registerPassword, setRegisterPassword] = useState('');
const [registerConfirmPassword, setRegisterConfirmPassword] = useState('');
const [showRegisterPassword, setShowRegisterPassword] = useState(false);
const [showConfirmPassword, setShowConfirmPassword] = useState(false);
const [acceptTerms, setAcceptTerms] = useState(false);
const [registerPhone, setRegisterPhone] = useState('');


// ÉTATS POUR MOT DE PASSE OUBLIÉ (NOUVEAU)
const [forgotEmail, setForgotEmail] = useState('');

 // ========================================
// FONCTION DE CONNEXION
// ========================================
const handleLogin = async () => {
  // Validation
  if (!loginEmail) {
    alert('Veuillez entrer votre email');
    return;
  }
  
  if (!loginPassword) {
    alert('Veuillez entrer votre mot de passe');
    return;
  }
  
  if (loginPassword.length < 6) {
    alert('Le mot de passe doit contenir au moins 6 caractères');
    return;
  }
  
  setIsLoading(true);
  
  try {
    // Simuler un appel API (2 secondes)
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    console.log('=== CONNEXION RÉUSSIE ===');
    console.log('Email:', loginEmail);
    console.log('Password:', loginPassword);
    console.log('Remember Me:', rememberMe);
    
    alert('Connexion réussie !');
    
  } catch (error) {
    console.error('Erreur:', error);
    alert('Erreur de connexion');
  } finally {
    setIsLoading(false);
  }
};

// ========================================
// FONCTION D'INSCRIPTION
// ========================================
const handleRegister = async () => {
  // Validation du nom
  if (!registerName) {
    alert('Veuillez entrer votre nom complet');
    return;
  }
  
  if (registerName.length < 3) {
    alert('Le nom doit contenir au moins 3 caractères');
    return;
  }
  
  // Validation de l'email
  if (!registerEmail) {
    alert('Veuillez entrer votre email');
    return;
  }
  
  // Validation basique du format email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(registerEmail)) {
    alert('Email invalide');
    return;
  }

  // Validation du numéro de telephone
  if (!registerPhone) {
  alert('Veuillez entrer votre numéro de téléphone');
  return;
} 
  // Validation du mot de passe
  if (!registerPassword) {
    alert('Veuillez entrer un mot de passe');
    return;
  }
  
  if (registerPassword.length < 6) {
    alert('Le mot de passe doit contenir au moins 6 caractères');
    return;
  }
  
  // Vérification que les mots de passe correspondent
  if (registerPassword !== registerConfirmPassword) {
    alert('Les mots de passe ne correspondent pas');
    return;
  }
  
  // Vérification des conditions d'utilisation
  if (!acceptTerms) {
    alert('Veuillez accepter les conditions d\'utilisation');
    return;
  }
  
  setIsLoading(true);
  
  try {
    // Simuler un appel API (2 secondes)
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    console.log('=== INSCRIPTION RÉUSSIE ===');
    console.log('Nom:', registerName);
    console.log('Email:', registerEmail);
    console.log('Password:', registerPassword);
    
    alert('Inscription réussie ! Vous pouvez maintenant vous connecter.');
    
    // Retour automatique au formulaire de connexion
    setActiveForm('login');
    setActiveTab('connexion');
    
    // Réinitialiser les champs
    setRegisterName('');
    setRegisterEmail('');
    setRegisterPassword('');
    setRegisterConfirmPassword('');
    setAcceptTerms(false);
    
  } catch (error) {
    console.error('Erreur:', error);
    alert('Erreur lors de l\'inscription');
  } finally {
    setIsLoading(false);
  }
};
// ========================================
// FONCTION MOT DE PASSE OUBLIÉ
// ========================================
const handleForgotPassword = async () => {
  // Validation de l'email
  if (!forgotEmail) {
    alert('Veuillez entrer votre email');
    return;
  }
  
  // Validation basique du format email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(forgotEmail)) {
    alert('Email invalide');
    return;
  }
  
  setIsLoading(true);
  
  try {
    // Simuler un appel API (2 secondes)
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    console.log('=== EMAIL DE RÉINITIALISATION ENVOYÉ ===');
    console.log('Email:', forgotEmail);
    
    alert('Un email de réinitialisation a été envoyé à ' + forgotEmail);
    
    // Retour au formulaire de connexion
    setActiveForm('login');
    setForgotEmail('');
    
  } catch (error) {
    console.error('Erreur:', error);
    alert('Erreur lors de l\'envoi de l\'email');
  } finally {
    setIsLoading(false);
  }
};
// ========================================
// GESTION DES ONGLETS
// ========================================
const handleTabChange = (tab) => {
  setActiveTab(tab);
  
  if (tab === 'connexion') {
    setActiveForm('login');
  } else if (tab === 'inscription') {
    setActiveForm('register');
  }
};

  return (
    <div className="min-h-screen bg-gradient-to-b from-amber-50 to-stone-200">
      
      {/* HEADER */}
      <header className="flex items-center justify-between px-8 py-4 bg-black shadow-md">
        {/* Logo à gauche */}
        <div className="flex items-center">
          <img
            src="/image 11.svg"
            alt="Zeduc Logo"
            className="h-10 w-auto"
          />
        </div>

        {/* Bouton Go Back à droite */}
        <button
          onClick={() => window.history.back()}
          className="flex items-center justify-center bg-amber-500 hover:bg-amber-600 transition-colors rounded p-2"
        >
          <img
            src="/Go Back.svg"
            alt="Go Back"
            className="h-6 w-6"
          />
        </button>
      </header>

      {/* CONTENU DE LA PAGE */}
      <main className="flex flex-col items-center justify-center mt-20 w-full">
        {/* CONTAINER PRINCIPAL */}
<div className="flex items-center justify-center px-4 py-12">
 <div className="w-full max-w-xl">

    {/* ONGLETS (cachés si mot de passe oublié) */}
{activeForm !== 'forgot' && (
  <div className="bg-white rounded-2xl p-2 mb-6 shadow-sm">
    <div className="flex gap-2">
      <button
        onClick={() => handleTabChange('connexion')}
        className={`flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all ${
          activeTab === 'connexion'
            ? 'bg-gradient-to-r from-yellow-500 to-amber-500 text-black shadow-md'
            : 'text-gray-600 hover:text-gray-800'
        }`}
      >
        Connexion
      </button>
      
      <button
        onClick={() => handleTabChange('inscription')}
        className={`flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all ${
          activeTab === 'inscription'
            ? 'bg-gradient-to-r from-yellow-500 to-amber-500 text-black shadow-md'
            : 'text-gray-600 hover:text-gray-800'
        }`}
      >
        Inscription
      </button>
    </div>
  </div>
)}
   {/* ==================== FORMULAIRE DE CONNEXION ==================== */}
{activeForm === 'login' && (
  <div className="bg-white shadow-lg rounded-2xl p-10 border border-amber-100">
    <div className="text-center mb-8">
      <h1 className="text-3xl font-bold text-black mb-3">
        Bon Retour!
      </h1>
      <p className="text-gray-700 text-lg">
        Connectez-vous à votre compte Zeduc-sp@ce
      </p>
    </div>

    <div className="space-y-6">
      {/* Email */}
      <div>
        <label className="block text-black font-semibold mb-2">
          Adresse Email
        </label>
        <input
          type="email"
          value={loginEmail}
          onChange={(e) => setLoginEmail(e.target.value)}
          onKeyPress={(e) => e.key === 'Enter' && !isLoading && handleLogin()}
          placeholder="Votre@email.com"
          disabled={isLoading}
          className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
        />
      </div>

      {/* Mot de passe */}
      <div>
        <label className="block text-black font-semibold mb-2">
          Mot de Passe
        </label>
        <div className="relative">
          <input
            type={showLoginPassword ? 'text' : 'password'}
            value={loginPassword}
            onChange={(e) => setLoginPassword(e.target.value)}
            onKeyPress={(e) => e.key === 'Enter' && !isLoading && handleLogin()}
            placeholder="****************"
            disabled={isLoading}
            className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
          />
          <button
            type="button"
            onClick={() => setShowLoginPassword(!showLoginPassword)}
            disabled={isLoading}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50"
          >
            {showLoginPassword ? <Eye className="w-5 h-5" /> : <EyeOff className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* Options */}
      <div className="flex items-center justify-between">
        <label className="flex items-center gap-3 cursor-pointer">
          <input
            type="checkbox"
            checked={rememberMe}
            onChange={(e) => setRememberMe(e.target.checked)}
            disabled={isLoading}
            className="w-5 h-5 rounded border-2 border-gray-400 text-amber-500 focus:ring focus:ring-amber-400/50 disabled:opacity-50"
          />
          <span className="text-black font-medium">
            Se souvenir de moi
          </span>
        </label>
        
        <button 
          onClick={() => setActiveForm('forgot')}
          className="text-amber-500 hover:text-amber-600 font-medium transition-colors disabled:opacity-50"
          disabled={isLoading}
        >
          Mot de passe oublié?
        </button>
      </div>

      {/* Bouton connexion */}
      <button
        onClick={handleLogin}
        disabled={isLoading}
        className={`w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02] ${
          isLoading ? 'opacity-50 cursor-not-allowed' : ''
        }`}
      >
        {isLoading ? (
          <span className="flex items-center justify-center gap-2">
            <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24">
              <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
              <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            Connexion en cours...
          </span>
        ) : (
          'Se connecter'
        )}
      </button>

      {/* Lien inscription */}
      <p className="text-center text-gray-700">
        Pas encore de compte ?{' '}
        <button 
          onClick={() => {
            setActiveTab('inscription');
            setActiveForm('register');
          }}
          className="text-amber-500 hover:text-amber-600 font-semibold transition-colors disabled:opacity-50"
          disabled={isLoading}
        >
          Créer un compte
        </button>
      </p>
    </div>
  </div>
)}

{/* ==================== FORMULAIRE D'INSCRIPTION ==================== */}
{activeForm === 'register' && (
  <div className="bg-white shadow-lg rounded-2xl p-10 border border-amber-100">
    <div className="text-center mb-8">
      <h1 className="text-3xl font-bold text-black mb-3">
        Rejoignez-Nous!
      </h1>
      <p className="text-gray-700 text-lg">
        Créez votre compte Zeduc-sp@ce
      </p>
    </div>

    <div className="space-y-6">
      {/* Nom complet */}
      <div>
        <label className="block text-black font-semibold mb-2">
          Nom complet
        </label>
        <input
          type="text"
          value={registerName}
          onChange={(e) => setRegisterName(e.target.value)}
          placeholder="Jean Dupont"
          disabled={isLoading}
          className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
        />
      </div>

      {/* Email */}
      <div>
        <label className="block text-black font-semibold mb-2">
          Adresse Email
        </label>
        <input
          type="email"
          value={registerEmail}
          onChange={(e) => setRegisterEmail(e.target.value)}
          placeholder="votre@email.com"
          disabled={isLoading}
          className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
        />
      </div>
      {/* Numéro de téléphone */}
<div>
  <label className="block text-black font-semibold mb-2">
    Numéro de téléphone
  </label>
  <input
    type="tel"
    value={registerPhone}
    onChange={(e) => setRegisterPhone(e.target.value)}
    placeholder="+237 6 70 46 88 05"
    disabled={isLoading}
    className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 
               focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
  />
</div>


      {/* Mot de passe */}
      <div>
        <label className="block text-black font-semibold mb-2">
          Mot de Passe
        </label>
        <div className="relative">
          <input
            type={showRegisterPassword ? 'text' : 'password'}
            value={registerPassword}
            onChange={(e) => setRegisterPassword(e.target.value)}
            placeholder="Minimum 6 caractères"
            disabled={isLoading}
            className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
          />
          <button
            type="button"
            onClick={() => setShowRegisterPassword(!showRegisterPassword)}
            disabled={isLoading}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50"
          >
            {showRegisterPassword ? <Eye className="w-5 h-5" /> : <EyeOff className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* Confirmation mot de passe */}
      <div>
        <label className="block text-black font-semibold mb-2">
          Confirmer le mot de passe
        </label>
        <div className="relative">
          <input
            type={showConfirmPassword ? 'text' : 'password'}
            value={registerConfirmPassword}
            onChange={(e) => setRegisterConfirmPassword(e.target.value)}
            placeholder="Retapez votre mot de passe"
            disabled={isLoading}
            className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
          />
          <button
            type="button"
            onClick={() => setShowConfirmPassword(!showConfirmPassword)}
            disabled={isLoading}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50"
          >
            {showConfirmPassword ? <Eye className="w-5 h-5" /> : <EyeOff className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* Accepter les conditions */}
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
            <button className="text-amber-500 hover:text-amber-600 font-medium">
              conditions d'utilisation
            </button>
          </span>
        </label>
      </div>

      {/* Accepter les cookies */}
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
            <button className="text-amber-500 hover:text-amber-600 font-medium">
              Utilisations des cookies
            </button>
            {' '}et la{' '}
            <button className="text-amber-500 hover:text-amber-600 font-medium">
              politique de confidentialité
            </button>
          </span>
        </label>
      </div>

      {/* Bouton inscription */}
      <button
        onClick={handleRegister}
        disabled={isLoading}
        className={`w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02] ${
          isLoading ? 'opacity-50 cursor-not-allowed' : ''
        }`}
      >
        {isLoading ? (
          <span className="flex items-center justify-center gap-2">
            <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24">
              <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
              <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            Inscription en cours...
          </span>
        ) : (
          'Inscrire'
        )}
      </button>

      {/* Lien connexion */}
      <p className="text-center text-gray-700">
        Déjà un compte ?{' '}
        <button 
          onClick={() => {
            setActiveTab('connexion');
            setActiveForm('login');
          }}
          className="text-amber-500 hover:text-amber-600 font-semibold transition-colors disabled:opacity-50"
          disabled={isLoading}
        >
          Se connecter
        </button>
      </p>
    </div>
  </div>
)}

{/* ==================== FORMULAIRE MOT DE PASSE OUBLIÉ ==================== */}
{activeForm === 'forgot' && (
  <>
    <div className="text-center mb-8">
      <h1 className="text-3xl font-bold text-black mb-3">
        Mot de passe oublié?
      </h1>
      <p className="text-gray-700 text-lg">
        Entrez votre email pour recevoir un lien de réinitialisation
      </p>
    </div>

    <div className="space-y-6">
      {/* Email */}
      <div>
        <label className="block text-black font-semibold mb-2">
          Adresse Email
        </label>
        <input
          type="email"
          value={forgotEmail}
          onChange={(e) => setForgotEmail(e.target.value)}
          onKeyPress={(e) => e.key === 'Enter' && !isLoading && handleForgotPassword()}
          placeholder="Votre@email.com"
          disabled={isLoading}
          className="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50 disabled:opacity-50"
        />
      </div>

      {/* Bouton envoi */}
      <button
        onClick={handleForgotPassword}
        disabled={isLoading}
        className={`w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02] ${
          isLoading ? 'opacity-50 cursor-not-allowed' : ''
        }`}
      >
        {isLoading ? (
          <span className="flex items-center justify-center gap-2">
            <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24">
              <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
              <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            Envoi en cours...
          </span>
        ) : (
          'Envoyer le lien'
        )}
      </button>

      {/* Retour connexion */}
      <p className="text-center text-gray-700">
        <button 
          onClick={() => setActiveForm('login')}
          className="text-amber-500 hover:text-amber-600 font-semibold transition-colors disabled:opacity-50"
          disabled={isLoading}
        >
          ← Retour à la connexion
        </button>
      </p>
    </div>
  </>
)}

  </div>
</div>
      </main>
      {/* FOOTER AVEC COPYRIGHT */}
<footer className="mt-auto pt-8 pb-6">
  <div className="max-w-4xl mx-auto px-4">
    {/* Ligne de séparation */}
    <div className="border-t border-gray-300 mb-6"></div>
    
    {/* Copyright et liens */}
    <div className="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
      <p>
        © {new Date().getFullYear()} Zeduc-sp@ce. Tous droits réservés.
      </p>
      
      <div className="flex gap-6">
        <button className="hover:text-amber-600 transition-colors">
          Conditions d'utilisation
        </button>
        <button className="hover:text-amber-600 transition-colors">
          Politique de confidentialité
        </button>
      </div>
    </div>
  </div>
</footer>
    </div>
  );
}
