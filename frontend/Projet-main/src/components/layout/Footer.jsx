export default function Footer() {
  return (
    <footer className="mt-auto pt-8 pb-6">
      <div className="max-w-4xl mx-auto px-4">
        {/* Ligne de séparation */}
        <div className="border-t border-gray-300 mb-6"></div>
        
        {/* Copyright et liens */}
        <div className="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
          <p>
            © {new Date().getFullYear()} Zeduc-sp@ce. Tous droits réservés.
          </p>
          
          <div className="flex gap-6">
            <button className="hover:text-amber-600 transition-colors">
              Conditions d'utilisation
            </button>
            <button className="hover:text-amber-600 transition-colors">
              Politique de confidentialité
            </button>
          </div>
        </div>
      </div>
    </footer>
  );
}