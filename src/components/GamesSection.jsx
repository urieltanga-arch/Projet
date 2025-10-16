import React from 'react';
// Importer vos images depuis le dossier assets
import Dice from '../assets/Dice.svg';
import Puzzle from '../assets/Puzzle.svg';
import trophe from '../assets/trophe.svg';
import game from '../assets/game.svg';

const GamesSection = () => {
  const games = [
    {
      id: 1,
      name: 'Roue de Fortune',
      points: '+ 50-200 pts',
      image: Dice, // ← Image au lieu de icon
      bgColor: 'from-yellow-500 to-yellow-600',
      textColor: 'text-white',
      buttonBg: 'bg-black',
      buttonText: 'text-white'
    },
    {
      id: 2,
      name: 'Quiz Cusine',
      points: '+ 100 pts',
      image: Puzzle, // ← Image au lieu de icon
      bgColor: 'from-black to-gray-900',
      textColor: 'text-white',
      buttonBg: 'bg-yellow-500',
      buttonText: 'text-black'
    },
    {
      id: 3,
      name: 'Défi Quotidien',
      points: '+ 150 pts',
      image: trophe, // ← Image au lieu de icon
      bgColor: 'from-gray-100 to-gray-200',
      textColor: 'text-black',
      buttonBg: 'bg-black',
      buttonText: 'text-white'
    },
    {
      id: 4,
      name: 'Jeux de mémoire',
      points: '+ 75 pts',
      image: game, // ← Image au lieu de icon
      bgColor: 'from-black to-gray-900',
      textColor: 'text-white',
      buttonBg: 'bg-yellow-500',
      buttonText: 'text-black'
    }
  ];

  return (
    <div className="mt-16">
      {/* Section Title */}
      <h2 className="text-black text-3xl font-bold mb-8">
        Mini Jeux Disponibles
      </h2>

      {/* Games Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {games.map((game) => {
          return (
            <div 
              key={game.id}
              className={`bg-gradient-to-br ${game.bgColor} rounded-3xl p-8 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1`}
            >
              {/* Image remplaçant l'icône */}
              <div className="mb-6 flex justify-center">
                <div className="w-24 h-24 rounded-full overflow-hidden bg-white/10 backdrop-blur-sm shadow-lg">
                  <img 
                    src={game.image} 
                    alt={game.name}
                    className="w-full h-full object-cover"
                  />
                </div>
              </div>

              {/* Game Name */}
              <h3 className={`${game.textColor} text-xl font-bold text-center mb-2`}>
                {game.name}
              </h3>

              {/* Play Button */}
              <button className={`w-full ${game.buttonBg} ${game.buttonText} py-3 rounded-full font-semibold hover:opacity-90 transition-opacity`}>
                {game.points}
              </button>
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default GamesSection;

