import React from 'react';
import { Hand } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import Header from './Header';
import FidelityCard from './FidelityCard';
import ReferralCard from './ReferralCard';
import OrdersCard from './OrdersCard';
import PromotionCard from './PromotionCard';
import GamesSection from './GamesSection';
import Footer from './Footer';

 const Dashboard = () =>{
  const { userData } = useAuth();

  return (
    <div className="min-h-screen bg-gradient-to-br from-amber-50 to-orange-50">
      <Header />

      <main className="container mx-auto px-4 py-12">
        {/* Welcome Section */}
        <div className="mb-12">
          <div className="flex items-center space-x-4 mb-2">
            <h1 className="text-5xl font-bold text-black">
              Bonjour {userData?.name || 'Utilisateur'}
            </h1>
            <Hand className="w-12 h-12 text-black" fill="black" />
          </div>
          <p className="text-gray-700 text-lg">
            Bienvenue sur votre espace personnel ZEDUC-SP@CE
          </p>
        </div>

        {/* Cards Grid */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <FidelityCard points={userData?.points || 2450} />
          <ReferralCard referralCode="MARIEADA2025" />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <OrdersCard />
          <PromotionCard />
        </div>

        {/* Games Section */}
        <GamesSection />
      </main>

      <Footer />
    </div>
  );
};

export default Dashboard;