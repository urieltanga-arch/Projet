import React from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';

const Top10 = () => {
  return (
    <div className="min-h-screen bg-gradient-to-br from-amber-50 to-orange-50">
      <Header />
      <main className="container mx-auto px-4 py-12">
        <h1 className="text-4xl font-bold text-black mb-8">Top 10 Utilisateurs</h1>
        <p className="text-gray-700">Classement en construction...</p>
      </main>
      <Footer />
    </div>
  );
};

export default Top10;