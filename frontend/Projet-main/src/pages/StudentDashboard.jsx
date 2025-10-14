// src/pages/StudentDashboard.jsx
import { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';

export default function StudentDashboard() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [copied, setCopied] = useState(false);

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  const copyReferralCode = () => {
    navigator.clipboard.writeText(user?.referral_code);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white shadow">
        <div className="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center">
            <h1 className="text-2xl font-bold" style={{ color: '#cfbd97' }}>
              Mon Miam Miam
            </h1>
            <button
              onClick={handleLogout}
              className="px-4 py-2 text-sm font-medium text-white rounded-lg hover:opacity-90 transition-opacity"
              style={{ backgroundColor: '#000000' }}
            >
              Déconnexion
            </button>
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        {/* Carte de bienvenue */}
        <div className="bg-white rounded-lg shadow p-6 mb-6">
          <h2 className="text-2xl font-bold text-gray-900 mb-2">
            Bienvenue, {user?.name} ! 👋
          </h2>
          <p className="text-gray-600">
            Découvrez notre menu et passez votre commande en quelques clics
          </p>
        </div>

        {/* Statistiques */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          {/* Points de fidélité */}
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-gray-600">
                  Points de Fidélité
                </p>
                <p className="text-3xl font-bold mt-2" style={{ color: '#cfbd97' }}>
                  {user?.loyalty_points || 0}
                </p>
              </div>
              <div className="w-12 h-12 rounded-full flex items-center justify-center"
                   style={{ backgroundColor: '#cfbd97' }}>
                <span className="text-2xl">🎁</span>
              </div>
            </div>
            <p className="text-xs text-gray-500 mt-2">
              15 points = 1000 FCFA de réduction
            </p>
          </div>

          {/* Code de parrainage */}
          <div className="bg-white rounded-lg shadow p-6">
            <p className="text-sm font-medium text-gray-600 mb-2">
              Votre Code de Parrainage
            </p>
            <div className="flex items-center space-x-2">
              <code className="text-2xl font-bold px-4 py-2 bg-gray-100 rounded-lg flex-1 text-center"
                    style={{ color: '#cfbd97' }}>
                {user?.referral_code}
              </code>
              <button
                onClick={copyReferralCode}
                className="px-4 py-2 text-white rounded-lg transition-colors"
                style={{ backgroundColor: copied ? '#10b981' : '#cfbd97' }}
              >
                {copied ? '✓' : '📋'}
              </button>
            </div>
            <p className="text-xs text-gray-500 mt-2">
              Partagez et gagnez des points !
            </p>
          </div>

          {/* Commandes */}
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-gray-600">
                  Mes Commandes
                </p>
                <p className="text-3xl font-bold mt-2 text-gray-900">
                  0
                </p>
              </div>
              <div className="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                <span className="text-2xl">🍔</span>
              </div>
            </div>
            <button className="text-sm mt-2 hover:underline"
                    style={{ color: '#cfbd97' }}>
              Voir l'historique
            </button>
          </div>
        </div>

        {/* Actions rapides */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-lg font-semibold mb-4">Menu du Jour</h3>
            <p className="text-gray-600 mb-4">
              Découvrez nos plats spéciaux du jour
            </p>
            <button className="w-full py-3 px-4 rounded-lg text-white font-medium transition-opacity hover:opacity-90"
                    style={{ backgroundColor: '#cfbd97' }}>
              Voir le Menu
            </button>
          </div>

          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-lg font-semibold mb-4">Commander</h3>
            <p className="text-gray-600 mb-4">
              Passez votre commande pour livraison ou sur place
            </p>
            <button className="w-full py-3 px-4 rounded-lg text-white font-medium transition-opacity hover:opacity-90"
                    style={{ backgroundColor: '#000000' }}>
              Commander Maintenant
            </button>
          </div>
        </div>
      </main>
    </div>
  );
}