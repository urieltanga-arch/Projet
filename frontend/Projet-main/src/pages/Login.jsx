// src/pages/Login.jsx
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

export default function Login() {
  const navigate = useNavigate();
  const { login } = useAuth();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    setError('');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (!formData.email || !formData.password) {
      setError('Veuillez remplir tous les champs');
      return;
    }

    setLoading(true);

    try {
      const response = await login(formData);
      
      if (response.success) {
        const user = response.data.user;
        
        // Redirection selon le rôle
        switch (user.role) {
          case 'admin':
            navigate('/admin/dashboard');
            break;
          case 'manager':
            navigate('/manager/dashboard');
            break;
          case 'employee':
            navigate('/employee/dashboard');
            break;
          default:
            navigate('/dashboard');
        }
      }
    } catch (err) {
      console.error('Erreur connexion:', err);
      setError(err.message || 'Email ou mot de passe incorrect');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
      <div className="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold" style={{ color: '#cfbd97' }}>
            Mon Miam Miam
          </h2>
          <p className="text-gray-600 mt-2">Connectez-vous à votre compte</p>
        </div>

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            {error}
          </div>
        )}

        <div className="space-y-6">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Email
            </label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="jean@exemple.com"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Mot de passe
            </label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="••••••••"
              required
            />
          </div>

          <div className="flex items-center justify-between">
            <label className="flex items-center">
              <input
                type="checkbox"
                className="w-4 h-4 rounded border-gray-300"
                style={{ accentColor: '#cfbd97' }}
              />
              <span className="ml-2 text-sm text-gray-600">
                Se souvenir de moi
              </span>
            </label>

            <button
              type="button"
              className="text-sm hover:underline"
              style={{ color: '#cfbd97' }}
            >
              Mot de passe oublié ?
            </button>
          </div>

          <button
            type="button"
            onClick={handleSubmit}
            disabled={loading}
            className="w-full py-3 px-4 rounded-lg text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            style={{ backgroundColor: '#cfbd97' }}
          >
            {loading ? 'Connexion en cours...' : 'Se connecter'}
          </button>
        </div>

        <div className="mt-6 text-center">
          <p className="text-sm text-gray-600">
            Vous n'avez pas de compte ?{' '}
            <button
              onClick={() => navigate('/register')}
              className="font-medium hover:underline"
              style={{ color: '#cfbd97' }}
            >
              S'inscrire
            </button>
          </p>
        </div>
      </div>
    </div>
  );
}