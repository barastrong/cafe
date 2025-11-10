import React from 'react';
import { NavLink } from 'react-router-dom';

import Logo from '../assets/logo.png';

const Navbar: React.FC = () => {
  return (
    <header className="bg-white w-full shadow-lg sticky top-0 z-50 border-b-4 border-orange-400">
      <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <NavLink to="/" className="flex items-center gap-3">
          <div className="w-30 h-auto rounded-full overflow-hidden transition-transform duration-300">
            <img src={Logo} alt="Coffee Logo" className="w-full h-full object-cover" />
          </div>
        </NavLink>
      </nav>
    </header>
  );
};

export default Navbar;
