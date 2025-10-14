export default function AuthTabs({ activeTab, onTabChange, hidden = false }) {
  if (hidden) return null;

  return (
    <div className="bg-white rounded-2xl p-2 mb-6 shadow-sm">
      <div className="flex gap-2">
        <button
          onClick={() => onTabChange('connexion')}
          className={`flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all ${
            activeTab === 'connexion'
              ? 'bg-gradient-to-r from-yellow-500 to-amber-500 text-black shadow-md'
              : 'text-gray-600 hover:text-gray-800'
          }`}
        >
          Connexion
        </button>
        
        <button
          onClick={() => onTabChange('inscription')}
          className={`flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all ${
            activeTab === 'inscription'
              ? 'bg-gradient-to-r from-yellow-500 to-amber-500 text-black shadow-md'
              : 'text-gray-600 hover:text-gray-800'
          }`}
        >
          Inscription
        </button>
      </div>
    </div>
  );
}