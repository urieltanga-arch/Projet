// src/pages/Unauthorized.jsx
import { useNavigate } from 'react-router-dom';

export default function Unauthorized() {
  const navigate = useNavigate();

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center px-4">
      <div className="max-w-md w-full text-center">
        <div className="mb-8">
          <span className="text-6xl">🚫</span>
        </div>
        <h1 className="text-4xl font-bold text-gray-900 mb-4">
          Accès Refusé
        </h1>
        <p className="text-gray-600 mb-8">
          Vous n'avez pas les permissions nécessaires pour accéder à cette page.
        </p>
        <div className="space-y-3">
          <button
            onClick={() => navigate(-1)}
            className="w-full py-3 px-4 rounded-lg text-white font-medium transition-opacity hover:opacity-90"
            style={{ backgroundColor: '#cfbd97' }}
          >
            Retour
          </button>
          <button
            onClick={() => navigate('/login')}
            className="w-full py-3 px-4 border-2 rounded-lg font-medium transition-colors hover:bg-gray-50"
            style={{ borderColor: '#cfbd97', color: '#cfbd97' }}
          >
            Se reconnecter
          </button>
        </div>
      </div>
    </div>
  );
}