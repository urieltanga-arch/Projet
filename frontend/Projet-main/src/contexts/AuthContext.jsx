import React, { createContext, useContext, useState, useEffect } from 'react';

// Création du contexte
const AuthContext = createContext();

// Hook personnalisé pour utiliser le contexte
export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}

// Provider component
export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  // Vérifier si l'utilisateur est connecté au chargement
  useEffect(() => {
    checkAuthStatus();
  }, []);

  const checkAuthStatus = async () => {
    try {
      // Récupérer le token du localStorage
      const token = localStorage.getItem('auth_token');
      const userData = localStorage.getItem('user_data');

      if (token && userData) {
        setUser(JSON.parse(userData));
      }
    } catch (error) {
      console.error('Erreur lors de la vérification de l\'authentification:', error);
    } finally {
      setIsLoading(false);
    }
  };

  // Fonction de connexion
  const login = async (email, password) => {
    try {
      setIsLoading(true);
      
      // Simulation d'appel API - Remplacez par votre vraie API
      const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });

      if (!response.ok) {
        throw new Error('Identifiants incorrects');
      }

      const data = await response.json();

      // Stocker les données dans le localStorage
      localStorage.setItem('auth_token', data.access_token);
      localStorage.setItem('user_data', JSON.stringify(data.user));
      
      setUser(data.user);
      
      return { success: true, data };
    } catch (error) {
      console.error('Erreur de connexion:', error);
      return { success: false, error: error.message };
    } finally {
      setIsLoading(false);
    }
  };

  // Fonction d'inscription
  const register = async (userData) => {
    try {
      setIsLoading(true);
      
      const response = await fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Erreur lors de l\'inscription');
      }

      const data = await response.json();
      
      return { success: true, data };
    } catch (error) {
      console.error('Erreur d\'inscription:', error);
      return { success: false, error: error.message };
    } finally {
      setIsLoading(false);
    }
  };

  // Fonction de déconnexion
  const logout = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_data');
    setUser(null);
  };

  // Fonction mot de passe oublié
  const forgotPassword = async (email) => {
    try {
      setIsLoading(true);
      
      const response = await fetch('http://localhost:8000/api/forgot-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email }),
      });

      if (!response.ok) {
        throw new Error('Erreur lors de l\'envoi de l\'email');
      }

      return { success: true };
    } catch (error) {
      console.error('Erreur:', error);
      return { success: false, error: error.message };
    } finally {
      setIsLoading(false);
    }
  };

  const value = {
    user,
    isLoading,
    login,
    register,
    logout,
    forgotPassword,
    isAuthenticated: !!user,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
}