import React from 'react';
// Importer les images
import ndoleImage from '../assets/Ndolé.svg';
import pouletImage from '../assets/poulet.svg';

const OrdersCard = () => {
  const orders = [
    {
      id: 1,
      name: 'Ndole',
      date: '22 Mars',
      price: 1000,
      points: 10,
      image: ndoleImage  // ← Image importée
    },
    {
      id: 2,
      name: 'Poulet pané',
      date: '18 Juin',
      price: 2500,
      points: 25,
      image: pouletImage  // ← Image importée
    }
  ];

  return (
    <div className="bg-white rounded-3xl p-8 shadow-lg">
      {/* Header */}
      <h3 className="text-black text-2xl font-bold mb-6">
        Dernières Commandes
      </h3>

      {/* Orders List */}
      <div className="space-y-4">
        {orders.map((order) => (
          <div 
            key={order.id}
            className="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-md transition-shadow"
          >
            {/* Left Side - Image & Info */}
            <div className="flex items-center space-x-4">
              {/* Image réelle */}
              <div className="w-16 h-16 bg-white rounded-xl flex items-center justify-center overflow-hidden shadow-sm">
                <img 
                  src={order.image} 
                  alt={order.name}
                  className="w-full h-full object-cover"
                />
              </div>
              
              {/* Info */}
              <div>
                <h4 className="text-black font-bold text-lg">{order.name}</h4>
                <p className="text-gray-600 text-sm">{order.date}</p>
              </div>
            </div>

            {/* Right Side - Price & Points */}
            <div className="text-right">
              <p className="text-black font-bold text-xl">{order.price}F</p>
              <p className="text-green-600 text-sm font-semibold">
                +{order.points} pts
              </p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default OrdersCard;