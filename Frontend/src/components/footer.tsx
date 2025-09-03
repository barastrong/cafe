import React from 'react';
import { Link } from 'react-router-dom';
import { FaInstagram, FaFacebookF, FaYoutube } from 'react-icons/fa';
import Logo from '../assets/Logo.png';

const Footer: React.FC = () => {
  return (
    <footer className="w-full  pt-10">
      <div className="max-w-screen-xl mx-auto bg-[#EAE1D4] rounded-t-3xl px-8 py-10 lg:px-12">
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-10">
          
          <div className="space-y-4 pr-4">
            <img src={Logo} alt="Coffee Logo" className="h-35" />
            <p className="text-gray-600 text-sm leading-relaxed">
              Tempat terbaik buat santai, kerja, atau nongkrong bareng teman. Nikmati racikan kopi terbaik dan camilan yang bikin betah berlama-lama di café kami.
            </p>
          </div>

          <div>
            <h3 className="text-xl font-bold text-[#38251e] mb-5">Categories</h3>
            <ul className="space-y-3 text-gray-700">
              <li><Link to="/menu" className="hover:text-orange-500 transition-colors">Menu</Link></li>
              <li><Link to="/promo" className="hover:text-orange-500 transition-colors">Promo</Link></li>
              <li><Link to="/event" className="hover:text-orange-500 transition-colors">Event</Link></li>
              <li><Link to="/tentang-kami" className="hover:text-orange-500 transition-colors">Tentang Kami</Link></li>
            </ul>
          </div>

          <div>
            <h3 className="text-xl font-bold text-[#38251e] mb-5">Follow Us</h3>
            <ul className="space-y-4 text-gray-700">
              <li className="flex items-center space-x-3">
                <FaInstagram size={20} />
                <a href="mailto:coffeshop@gmail.com" className="hover:text-orange-500 transition-colors">
                  Coffeshop@gmail.com
                </a>
              </li>
              <li className="flex items-center space-x-3">
                <FaFacebookF size={20} />
                <a href="#" className="hover:text-orange-500 transition-colors">
                  coffeshoptelkom
                </a>
              </li>
              <li className="flex items-center space-x-3">
                <FaYoutube size={20} />
                <a href="#" className="hover:text-orange-500 transition-colors">
                  @coffeshoptelkom
                </a>
              </li>
            </ul>
          </div>
          
          <div className="w-full h-48 rounded-lg overflow-hidden">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.0883015497497!2d112.43309631527964!3d-7.565863976722216!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e787265c01b9575%3A0x838531535497f1f0!2sSMP%20Negeri%202%20Prambon!5e0!3m2!1sen!2sid!4v1678889999999!5m2!1sen!2sid"
              width="100%"
              height="100%"
              style={{ border: 0 }}
              allowFullScreen={false}
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>

        </div>
      </div>
    </footer>
  );
};

export default Footer;