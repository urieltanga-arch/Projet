import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { authService } from '../services/api';

export default function Register() {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    location: '',
    referral_code: '',
  });
  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    // Effacer l'erreur du champ modifié
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: ''
      }));
    }
  };

  const validateForm = () => {
    const newErrors = {};
    
    if (!formData.name) newErrors.name = 'Le nom est obligatoire';
    if (!formData.email) newErrors.email = 'L\'email est obligatoire';
    else if (!/\S+@\S+\.\S+/.test(formData.email)) newErrors.email = 'Email invalide';
    if (!formData.phone) newErrors.phone = 'Le téléphone est obligatoire';
    if (!formData.password) newErrors.password = 'Le mot de passe est obligatoire';
    else if (formData.password.length < 8) newErrors.password = 'Au moins 8 caractères';
    else if (!/(?=.*[A-Z])(?=.*\d)/.test(formData.password)) {
      newErrors.password = 'Doit contenir une majuscule et un chiffre';
    }
    if (formData.password !== formData.password_confirmation) {
      newErrors.password_confirmation = 'Les mots de passe ne correspondent pas';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (!validateForm()) return;

    setLoading(true);

    try {
      const response = await authService.register(formData);
      
      if (response.success) {
        // Rediriger vers le tableau de bord
        navigate('/dashboard');
      }
    } catch (err) {
      console.error('Erreur inscription:', err);
      
      if (err.errors) {
        setErrors(err.errors);
      } else {
        setError(err.message || 'Erreur lors de l\'inscription');
      }
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
          <p className="text-gray-600 mt-2">Créer votre compte</p>
        </div>

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Nom complet *
            </label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="Jean Dupont"
            />
            {errors.name && (
              <p className="text-red-500 text-sm mt-1">{errors.name}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Email *
            </label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="jean@exemple.com"
            />
            {errors.email && (
              <p className="text-red-500 text-sm mt-1">{errors.email}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Téléphone *
            </label>
            <input
              type="tel"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="+237 6XX XXX XXX"
            />
            {errors.phone && (
              <p className="text-red-500 text-sm mt-1">{errors.phone}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Localisation (pour livraison)
            </label>
            <input
              type="text"
              name="location"
              value={formData.location}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="Résidence, Bâtiment..."
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Mot de passe *
            </label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="••••••••"
            />
            {errors.password && (
              <p className="text-red-500 text-sm mt-1">{errors.password}</p>
            )}
            <p className="text-xs text-gray-500 mt-1">
              Au moins 8 caractères, une majuscule et un chiffre
            </p>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Confirmer le mot de passe *
            </label>
            <input
              type="password"
              name="password_confirmation"
              value={formData.password_confirmation}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="••••••••"
            />
            {errors.password_confirmation && (
              <p className="text-red-500 text-sm mt-1">{errors.password_confirmation}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Code de parrainage (optionnel)
            </label>
            <input
              type="text"
              name="referral_code"
              value={formData.referral_code}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cfbd97] focus:border-transparent"
              placeholder="ABCD1234"
            />
            {errors.referral_code && (
              <p className="text-red-500 text-sm mt-1">{errors.referral_code}</p>
            )}
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full py-3 px-4 rounded-lg text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            style={{ backgroundColor: '#cfbd97' }}
          >
            {loading ? 'Inscription en cours...' : 'S\'inscrire'}
          </button>
        </form>

        <div className="mt-6 text-center">
          <p className="text-sm text-gray-600">
            Vous avez déjà un compte ?{' '}
            <button
              onClick={() => navigate('/login')}
              className="font-medium hover:underline"
              style={{ color: '#cfbd97' }}
            >
              Se connecter
            </button>
          </p>
        </div>
      </div>
    </div>
  );
}