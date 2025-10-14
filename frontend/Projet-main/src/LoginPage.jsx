import { useState } from 'react';
import Header from './components/layout/Header';
import Footer from './components/layout/Footer';
import AuthTabs from './components/auth/AuthTabs';
import LoginForm from '../src/components/auth/LoginForm';
import RegisterForm from './components/auth/RegisterForm';
import ForgotPasswordForm from './components/auth/ForgotPasswordForm';

export default function LoginPage() {
  const [activeTab, setActiveTab] = useState('connexion');
  const [activeForm, setActiveForm] = useState('login');
  const [isLoading, setIsLoading] = useState(false);

  // Gestion des onglets
  const handleTabChange = (tab) => {
    setActiveTab(tab);
    setActiveForm(tab === 'connexion' ? 'login' : 'register');
  };

  // Handlers pour les formulaires
  const handleLogin = async (loginData) => {
    setIsLoading(true);
    try {
      await new Promise(resolve => setTimeout(resolve, 2000));
      console.log('=== CONNEXION RÉUSSIE ===', loginData);
      alert('Connexion réussie !');
    } catch (error) {
      console.error('Erreur:', error);
      alert('Erreur de connexion');
    } finally {
      setIsLoading(false);
    }
  };

  const handleRegister = async (registerData) => {
    setIsLoading(true);
    try {
      await new Promise(resolve => setTimeout(resolve, 2000));
      console.log('=== INSCRIPTION RÉUSSIE ===', registerData);
      alert('Inscription réussie ! Vous pouvez maintenant vous connecter.');
      
      // Retour automatique au formulaire de connexion
      setActiveForm('login');
      setActiveTab('connexion');
    } catch (error) {
      console.error('Erreur:', error);
      alert('Erreur lors de l\'inscription');
    } finally {
      setIsLoading(false);
    }
  };

  const handleForgotPassword = async (email) => {
    setIsLoading(true);
    try {
      await new Promise(resolve => setTimeout(resolve, 2000));
      console.log('=== EMAIL DE RÉINITIALISATION ENVOYÉ ===', email);
      alert('Un email de réinitialisation a été envoyé à ' + email);
      setActiveForm('login');
    } catch (error) {
      console.error('Erreur:', error);
      alert('Erreur lors de l\'envoi de l\'email');
    } finally {
      setIsLoading(false);
    }
  };

  // Navigation entre formulaires
  const switchToRegister = () => {
    setActiveTab('inscription');
    setActiveForm('register');
  };

  const switchToLogin = () => {
    setActiveTab('connexion');
    setActiveForm('login');
  };

  const switchToForgotPassword = () => {
    setActiveForm('forgot');
  };

  const switchBackToLogin = () => {
    setActiveForm('login');
  };

  return (
    <div className="min-h-screen bg-gradient-to-b from-amber-50 to-stone-200">
      <Header />
      
      <main className="flex flex-col items-center justify-center mt-20 w-full">
        <div className="flex items-center justify-center px-4 py-12">
          <div className="w-full max-w-xl">
            <AuthTabs 
              activeTab={activeTab} 
              onTabChange={handleTabChange}
              hidden={activeForm === 'forgot'}
            />

            {activeForm === 'login' && (
              <LoginForm
                isLoading={isLoading}
                onLogin={handleLogin}
                onSwitchToRegister={switchToRegister}
                onSwitchToForgotPassword={switchToForgotPassword}
              />
            )}

            {activeForm === 'register' && (
              <RegisterForm
                isLoading={isLoading}
                onRegister={handleRegister}
                onSwitchToLogin={switchToLogin}
              />
            )}

            {activeForm === 'forgot' && (
              <ForgotPasswordForm
                isLoading={isLoading}
                onForgotPassword={handleForgotPassword}
                onBackToLogin={switchBackToLogin}
              />
            )}
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}