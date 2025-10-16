import React, { useState } from 'react';
import { GitBranch, Copy, Check } from 'lucide-react';

const ReferralCard = ({ referralCode = 'MARIEADA2025' }) => {
  const [copied, setCopied] = useState(false);

  const handleCopy = () => {
    navigator.clipboard.writeText(referralCode);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="bg-white rounded-3xl p-8 shadow-lg">
      {/* Header */}
      <div className="flex items-center space-x-4 mb-6">
        <div className="bg-yellow-500 p-4 rounded-2xl">
          <GitBranch className="w-8 h-8 text-black" strokeWidth={2.5} />
        </div>
        <div>
          <h3 className="text-black text-xl font-bold">Code Parrainage</h3>
          <p className="text-gray-600 text-sm">Invitez vos amis</p>
        </div>
      </div>

      {/* Code Display */}
      <div className="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-6 mb-4">
        <p className="text-center text-3xl font-bold text-black tracking-wider mb-2">
          {referralCode}
        </p>
        <p className="text-center text-gray-600 text-sm">Votre code personnel</p>
      </div>

      {/* Copy Button */}
      <button 
        onClick={handleCopy}
        className="w-full bg-black text-white py-4 rounded-full font-semibold text-lg hover:bg-gray-900 transition-all flex items-center justify-center space-x-2 shadow-lg"
      >
        {copied ? (
          <>
            <Check className="w-5 h-5" />
            <span>Code copié !</span>
          </>
        ) : (
          <>
            <Copy className="w-5 h-5" />
            <span>copier le code</span>
          </>
        )}
      </button>
    </div>
  );
};

export default ReferralCard;