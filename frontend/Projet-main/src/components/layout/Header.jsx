export default function Header() {
  return (
    <header className="flex items-center justify-between px-8 py-4 bg-black shadow-md">
      {/* Logo à gauche */}
      <div className="flex items-center">
        <img
          src="/image 11.svg"
          alt="Zeduc Logo"
          className="h-10 w-auto"
        />
      </div>

      {/* Bouton Go Back à droite */}
      <button
        onClick={() => window.history.back()}
        className="flex items-center justify-center bg-amber-500 hover:bg-amber-600 transition-colors rounded p-2"
      >
        <img
          src="/Go Back.svg"
          alt="Go Back"
          className="h-6 w-6"
        />
      </button>
    </header>
  );
}