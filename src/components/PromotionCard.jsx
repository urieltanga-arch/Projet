import React from 'react';

const PromotionCard = () => {
  return (
    <div className="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-3xl p-8 shadow-lg">
      {/* Header */}
      <h3 className="text-black text-2xl font-bold mb-6">
        Promotion Activé
      </h3>

      {/* Happy Hour Promo */}
      <div className="bg-white rounded-2xl p-6 mb-4 relative overflow-hidden">
        {/* Discount Badge */}
        <div className="absolute top-4 right-4 bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-bold">
          -30%
        </div>

        <h4 className="text-black font-bold text-xl mb-1">Happy Hour</h4>
        <p className="text-gray-600 text-sm">17H-19H tous les jours</p>
      </div>

      {/* Student Menu */}
      <div className="bg-black rounded-2xl p-6">
        <h4 className="text-white font-bold text-xl mb-2">Ménu Etudiant</h4>
        <p className="text-gray-300 text-base">
          Plat + Boisson à <span className="text-yellow-400 font-bold">3500F</span>
        </p>
      </div>
    </div>
  );
};

export default PromotionCard;