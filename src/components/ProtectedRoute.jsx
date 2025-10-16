// components/ProtectedRoute.jsx (Modifiez-le)

import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const ProtectedRoute = ({ children }) => {
  const { isLoggedIn, isLoading } = useAuth(); // 👈 Récupérez isLoading

  // ÉTAPE 1 : Si c'est en cours de chargement, affichez un écran d'attente
  if (isLoading) {
    return <div style={{ 
      minHeight: '100vh', 
      display: 'flex', 
      alignItems: 'center', 
      justifyContent: 'center',
      backgroundColor: '#f8f8f8'
    }}>Vérification de l'authentification...</div>;
  }

  // ÉTAPE 2 : Si le chargement est terminé et que l'utilisateur n'est PAS connecté, rediriger
  if (!isLoggedIn) {
    return <Navigate to="/login" replace />;
  }

  // ÉTAPE 3 : Chargement terminé et utilisateur connecté, afficher le contenu
  return children;
};

export default ProtectedRoute;