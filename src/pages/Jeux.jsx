import React from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import GamesSection from '../components/GamesSection';

const Jeux = () => {
  return (
    <div className="min-h-screen bg-gradient-to-br from-amber-50 to-orange-50">
      <Header />
      <main className="container mx-auto px-4 py-12">
        <h1 className="text-4xl font-bold text-black mb-8">Jeux & Challenges</h1>
        <GamesSection />
      </main>
      <Footer />
    </div>
  );
};

export default Jeux;