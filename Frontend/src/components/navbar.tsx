import React, { useState } from "react";
import { NavLink } from "react-router-dom";
import { FaBars, FaTimes } from "react-icons/fa";
import Logo from "../assets/Logo.png";

const Navbar: React.FC = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const toggleMenu = () => setIsMenuOpen(!isMenuOpen);
  const closeMobileMenu = () => setIsMenuOpen(false);

  const linkStyle = "text-gray-600 font-medium hover:text-orange-500 transition-colors duration-200 px-3 py-2 whitespace-nowrap";
  const activeLinkStyle = "bg-orange-500 text-white font-semibold rounded-md px-3 py-2 shadow-sm whitespace-nowrap";

  const navigationItems = [
    { label: "Beranda", path: "/" },
    { label: "Daftar Menu", path: "/menu" },
    { label: "Promo", path: "/promo" },
    { label: "Event", path: "/event" },
    { label: "Tentang Kami", path: "/about-us" },
  ];

  return (
    <header className="bg-white w-full shadow-sm sticky top-0 z-50">
      <div className="w-full h-1 bg-gray-800"></div>

      <nav className="w-full px-6 lg:px-10 h-24 flex items-center">
        <div className="flex-1 flex justify-start">
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

        <div className="hidden lg:flex flex-1 justify-end items-center space-x-3">
          <NavLink
            to="/signin"
            className="bg-[#FDF6E3] text-gray-800 px-5 py-2 rounded-md border border-[#E3D8C1] hover:bg-yellow-100 transition-colors font-medium"
          >
            Sign in
          </NavLink>
          <NavLink
            to="/register"
            className="bg-slate-800 text-white px-5 py-2 rounded-md hover:bg-slate-700 transition-colors font-medium"
          >
            Sign Up
          </NavLink>
        </div>

        <div className="lg:hidden ml-auto">
          <button onClick={toggleMenu} className="text-2xl text-gray-700">
            {isMenuOpen ? <FaTimes /> : <FaBars />}
          </button>
        </div>
      </nav>

      {isMenuOpen && (
        <div className="lg:hidden bg-white w-full absolute left-0 flex flex-col items-center space-y-3 py-6 shadow-lg border-t border-gray-100">
          {navigationItems.map((item) => (
            <NavLink
              key={item.path}
              to={item.path}
              className={({ isActive }) => (isActive ? activeLinkStyle : linkStyle)}
              onClick={closeMobileMenu}
            >
              {item.label}
            </NavLink>
          ))}
          <div className="flex items-center space-x-3 pt-4 border-t w-4/5 justify-center mt-4">
            <NavLink
              to="/signin"
              className="bg-[#FDF6E3] text-gray-800 px-5 py-2 rounded-md border border-[#E3D8C1]"
              onClick={closeMobileMenu}
            >
              Sign in
            </NavLink>
            <NavLink
              to="/register"
              className="bg-slate-800 text-white px-5 py-2 rounded-md"
              onClick={closeMobileMenu}
            >
              Sign Up
            </NavLink>
          </div>
        </div>
      )}
    </header>
  );
};

export default Navbar;