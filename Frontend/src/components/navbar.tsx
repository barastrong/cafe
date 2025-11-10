import React, { useState } from 'react';
import { NavLink } from 'react-router-dom';
import { FaBars, FaTimes, FaShoppingCart } from 'react-icons/fa';
import Logo from '../assets/Logo.png';

const Navbar: React.FC = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [cartItemCount] = useState(3); 

  const toggleMenu = () => setIsMenuOpen(!isMenuOpen);
  const closeMobileMenu = () => setIsMenuOpen(false);

  const linkStyle = "text-gray-600 font-medium hover:text-orange-500 transition-colors duration-200 px-3 py-2 whitespace-nowrap";
  const activeLinkStyle = "bg-orange-500 text-white font-semibold rounded-md px-3 py-2 shadow-sm whitespace-nowrap";
  
  const mobileLinkClass = ({ isActive }: { isActive: boolean }) =>
    `w-full text-center py-4 text-gray-600 text-xl rounded-lg transition-colors duration-300 ${isActive ? 'bg-orange-500 text-white font-bold' : 'text-gray-300 hover:bg-slate-700 hover:text-white'}`;

  const navigationItems = [
    { label: "Beranda", path: "/" },
    { label: "Daftar Menu", path: "/menu" },
    { label: "Promo", path: "/promo" },
    { label: "Tentang Kami", path: "/about-us" },
  ];

  return (
    <>
      <header className="bg-white w-full shadow-sm sticky top-0 z-40">
        <div className="w-full h-1 bg-gray-800"></div>
        <nav className="w-full px-6 lg:px-10 h-24 flex items-center">
          <div className={`flex-1 flex justify-start transition-opacity duration-300 ${isMenuOpen ? 'opacity-0 lg:opacity-100' : 'opacity-100'}`}>
            <NavLink to="/" onClick={closeMobileMenu}>
              <img src={Logo} alt="Coffee Logo" className="h-35" />
            </NavLink>
          </div>

          <div className="hidden lg:flex justify-center">
            <ul className="flex items-center space-x-6">
              {navigationItems.map((item) => (
                <li key={item.path}>
                  <NavLink
                    to={item.path}
                    className={({ isActive }) => (isActive ? activeLinkStyle : linkStyle)}
                  >
                    {item.label}
                  </NavLink>
                </li>
              ))}
            </ul>
          </div>

          <div className="hidden lg:flex flex-1 justify-end items-center">
            <NavLink to="/order" className="relative text-slate-700 p-2 rounded-full hover:bg-gray-100 transition-colors">
              <FaShoppingCart size={24} />
              {cartItemCount > 0 && (
                <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">
                  {cartItemCount}
                </span>
              )}
            </NavLink>
          </div>

          <div className="lg:hidden ml-auto flex items-center gap-4">
             <NavLink to="/order" className="relative text-slate-700 p-2">
                <FaShoppingCart size={24} />
                {cartItemCount > 0 && (
                  <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">
                    {cartItemCount}
                  </span>
                )}
              </NavLink>
            <button onClick={toggleMenu} className="text-2xl text-gray-700 z-50 relative">
              <FaBars />
            </button>
          </div>
        </nav>
      </header>

      <div 
        onClick={closeMobileMenu}
        className={`fixed inset-0 bg-black/50 z-30 transition-opacity duration-300 lg:hidden ${
          isMenuOpen ? 'opacity-100 visible' : 'opacity-0 invisible'
        }`}
      />
      
      <div 
        className={`fixed top-0 right-0 h-full w-4/5 max-w-sm bg-white shadow-lg z-40 transform transition-transform duration-300 ease-in-out lg:hidden ${
          isMenuOpen ? 'translate-x-0' : 'translate-x-full'
        }`}
      >
          <div className="flex flex-col h-full p-6">
            <div className="flex justify-between items-center">
                <NavLink to="/" onClick={closeMobileMenu}>
                    <img src={Logo} alt="Coffee Logo White" className="h-30" />
                </NavLink>
                <button onClick={closeMobileMenu} className="text-orange-600 text-3xl">
                    <FaTimes />
                </button>
            </div>
            <hr className="my-3 border-t-2 border-gray-300" />
            <nav className="flex flex-col items-center space-y-4 mt-6">
              {navigationItems.map((item) => (
                <NavLink
                  key={item.path}
                  to={item.path}
                  className={mobileLinkClass}
                  onClick={closeMobileMenu}
                >
                  {item.label}
                </NavLink>
              ))}
            </nav>
          </div>
      </div>
    </>
  );
};

export default Navbar;