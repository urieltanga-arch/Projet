import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { ShoppingCart, LogOut } from 'lucide-react';
import { useAuth } from '../context/AuthContext';

const Header = () => {
  const { userData, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <header className="bg-black text-white sticky top-0 z-50">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
        <Link to="/dashboard" className="flex items-center">
                        <div className="w-16 h-16 bg-black rounded-full flex items-center justify-center">
                            <img src="/image 11.svg" alt="logo" className="w-full h-full object-contain p-1" />
                        </div>
                    </Link>
                    

          {/* Navigation */}
          <nav className="hidden md:flex items-center space-x-8">
            <Link 
              to="/dashboard" 
              className="text-white hover:text-yellow-500 transition-colors font-medium"
            >
              Dashboard
            </Link>
            <Link 
              to="/menu" 
              className="text-white hover:text-yellow-500 transition-colors font-medium"
            >
              Menu
            </Link>
            <Link 
              to="/fidelite" 
              className="text-white hover:text-yellow-500 transition-colors font-medium"
            >
              Fidélité
            </Link>
            <Link 
              to="/parrainage" 
              className="text-white hover:text-yellow-500 transition-colors font-medium"
            >
              Parrainage
            </Link>
            <Link 
              to="/historique" 
              className="text-white hover:text-yellow-500 transition-colors font-medium"
            >
              Historique
            </Link>
            <Link 
              to="/jeux" 
              className="text-white hover:text-yellow-500 transition-colors font-medium"
            >
              Jeux
            </Link>
            <Link 
              to="/top10" 
              className="text-white hover:text-yellow-500 transition-colors font-medium"
            >
              Top 10
            </Link>
          </nav>

          {/* Actions */}
          <div className="flex items-center space-x-4">
            
            {/* Cart Icon */}
            <button className="relative">
              <ShoppingCart className="w-8 h-8 text-white hover:text-yellow-500 transition-colors" />
              <span className="absolute -top-2 -right-2 bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                0
              </span>
            </button>

            {/* Logout Button */}
            <button
              onClick={handleLogout}
              className="flex items-center space-x-2 bg-orange-600 hover:bg-orange-700 px-4 py-2 rounded-lg transition-colors"
            >
              <LogOut className="w-4 h-4" />
              <span className="hidden md:block">Déconnexion</span>
            </button>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;