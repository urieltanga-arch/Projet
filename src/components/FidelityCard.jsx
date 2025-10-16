import React from 'react';
import { Coins } from 'lucide-react';

const FidelityCard = ({ points = 2450 }) => {
  return (
    <div className="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-3xl p-8 shadow-lg">
      {/* Header */}
      <div className="mb-6">
        <h3 className="text-white text-xl font-semibold mb-1">
          Points de Fidélité
        </h3>
        <p className="text-white/80 text-sm">Votre solde actuel</p>
      </div>

      {/* Points Display */}
      <div className="flex items-center justify-between mb-8">
        <div>
          <div className="flex items-baseline">
            <span className="text-white text-6xl font-bold">
              {points.toLocaleString('fr-FR')}
            </span>
            <span className="text-white text-2xl font-semibold ml-2">pts</span>
          </div>
        </div>
        
        {/* Coins Icon */}
        <div className="relative">
          <Coins className="w-24 h-24 text-black/30" strokeWidth={1.5} />
          <Coins className="w-20 h-20 text-black/20 absolute top-2 -left-6" strokeWidth={1.5} />
        </div>
      </div>

      {/* Action Button */}
      <button className="w-full bg-black text-white py-4 rounded-full font-semibold text-lg hover:bg-gray-900 transition-colors shadow-lg">
        Utiliser mes points
      </button>
    </div>
  );
};

export default FidelityCard;