import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';

// Pages
import LoginPage from './components/LoginPage';
import Dashboard from './components/Dashboard';
import Menu from './pages/Menu';
import Fidelite from './pages/Fidelite';
import Parrainage from './pages/parrainage';
import Historique from './pages/Historique';
import Jeux from './pages/Jeux';
import Top10 from './pages/Top10';

function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          {/* Route publique */}
          <Route path="/login" element={<LoginPage />} />

          {/* Routes protégées */}
          <Route
            path="/dashboard"
            element={
              <ProtectedRoute>
                <Dashboard />
              </ProtectedRoute>
            }
          />
          <Route
            path="/menu"
            element={
              <ProtectedRoute>
                <Menu />
              </ProtectedRoute>
            }
          />
          <Route
            path="/fidelite"
            element={
              <ProtectedRoute>
                <Fidelite />
              </ProtectedRoute>
            }
          />
          <Route
            path="/parrainage"
            element={
              <ProtectedRoute>
                <Parrainage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/historique"
            element={
              <ProtectedRoute>
                <Historique />
              </ProtectedRoute>
            }
          />
          <Route
            path="/jeux"
            element={
              <ProtectedRoute>
                <Jeux />
              </ProtectedRoute>
            }
          />
          <Route
            path="/top10"
            element={
              <ProtectedRoute>
                <Top10 />
              </ProtectedRoute>
            }
          />

          {/* Redirection par défaut */}
          <Route path="/" element={<Navigate to="/loginpage" replace />} />
          <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;