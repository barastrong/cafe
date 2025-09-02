import React, { useState, useEffect } from 'react';
import { NavLink } from 'react-router-dom';
import { FaBars, FaTimes, FaHome, FaTag, FaShoppingBag } from 'react-icons/fa';
import '../css/Navbar.css';

const Navbar: React.FC = () => {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [isScrolled, setIsScrolled] = useState(false);

    const toggleMenu = () => setIsMenuOpen(!isMenuOpen);
    const closeMobileMenu = () => setIsMenuOpen(false);

    const handleScroll = () => {
        setIsScrolled(window.scrollY > 50);
    };

    useEffect(() => {
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <header className={isScrolled ? 'header scrolled' : 'header'}>
            <div className="navbar-container">
                <NavLink to="/" className="navbar-logo" onClick={closeMobileMenu}>
                    MyStore
                </NavLink>

                <div className="menu-icon" onClick={toggleMenu}>
                    {isMenuOpen ? <FaTimes /> : <FaBars />}
                </div>

                <nav className={isMenuOpen ? 'nav-menu active' : 'nav-menu'}>
                    <ul className="nav-menu-list">
                        <li className="nav-item">
                            <NavLink to="/" className="nav-links" onClick={closeMobileMenu}>
                                <FaHome className="nav-icon" />
                                <span>Home</span>
                            </NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink to="/promo" className="nav-links" onClick={closeMobileMenu}>
                                <FaTag className="nav-icon" />
                                <span>Promo</span>
                            </NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink to="/shop" className="nav-links nav-links-btn" onClick={closeMobileMenu}>
                                <FaShoppingBag className="nav-icon" />
                                <span>Shop Now</span>
                            </NavLink>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
    );
};

export default Navbar;