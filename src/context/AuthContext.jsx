import React, { createContext, useState, useContext } from 'react';

const AuthContext = createContext();

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [userData, setUserData] = useState(null);
  const [isLoading, setIsLoading] = useState(true); // 👈 NOUVEAU : Initialisé à 'true'

  const login = (user) => {
    setIsLoggedIn(true);
    setUserData(user);
    localStorage.setItem('user', JSON.stringify(user));
  };

  const logout = () => {
    setIsLoggedIn(false);
    setUserData(null);
    localStorage.removeItem('user');
  };

  React.useEffect(() => {
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
      const user = JSON.parse(savedUser);
      setIsLoggedIn(true);
      setUserData(user);
    }
    // 👈 IMPORTANT : Mettre isLoading à false UNIQUEMENT après la vérification
    setIsLoading(false); 
  }, []);

  return (
    <AuthContext.Provider value={{ isLoggedIn, userData, login, logout, isLoading }}> 
      {/* 👆 Ajoutez isLoading à la valeur du contexte */}
      {children}
    </AuthContext.Provider>
  );
};