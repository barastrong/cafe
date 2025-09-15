import React, { useState } from 'react';
import { FaInstagram, FaLinkedinIn, FaFacebookF } from 'react-icons/fa';
import { FaXTwitter } from 'react-icons/fa6';

import heroBgImage from '../assets/about-us/hero.png';
const teamNabilaImage = '/path/to/your/assets/team-nabila.jpg';
import BaraImage from '../assets/about-us/Bintang Binjas.png';
const teamSitiImage = '/path/to/your/assets/team-siti.jpg';
const teamRianImage = '/path/to/your/assets/team-rian.jpg';
const teamDewiImage = '/path/to/your/assets/team-dewi.jpg';

interface TeamMember {
  name: string;
  status: string;
  bio: string;
  imageUrl: string;
  social: {
    instagram?: string;
    twitter?: string;
    linkedin?: string;
    facebook?: string;
  };
}

const teamData: TeamMember[] = [
  {
    name: 'Nabila Raisa',
    status: 'Founder & Owner',
    bio: 'Nabila adalah otak dan hati di balik KatanyaCafe, memastikan setiap aspek kafe mencerminkan visi dan kecintaannya pada kopi berkualitas.',
    imageUrl: teamNabilaImage,
    social: { instagram: '#', twitter:'#',  linkedin: '#', facebook:'#' },
  },
  {
    name: 'Bintang Bara Adyasta',
    status: 'Web Development',
    bio: 'Bara adalah pengembang web andalan kami. Dengan kreativitas dan keahliannya, ia berhasil mengubah tampilan website menjadi lebih modern, responsif, dan menarik.',
    imageUrl: BaraImage,
    social: { instagram: '#', twitter: '#', linkedin: '#', facebook: '#' },
  },
  {
    name: 'Siti Amelia',
    status: 'Interface Artisan',
    bio: 'Siti mengubah desain menjadi antarmuka yang interaktif dan responsif, memastikan website kami terlihat indah di semua perangkat.',
    imageUrl: teamSitiImage,
    social: { instagram: '#', twitter: '#', linkedin: '#', facebook: '#' },
  },
  {
    name: 'Rian Prasetyo',
    status: 'Logic Weaver',
    bio: 'Rian adalah mesin di balik layar, mengelola database dan semua logika server yang membuat website berjalan cepat dan aman.',
    imageUrl: teamRianImage,
    social: { instagram: '#', twitter: '#', linkedin: '#', facebook:'#' },
  },
  {
    name: 'Dewi Lestari',
    status: 'Visual Storyteller',
    bio: 'Dewi adalah seniman visual kami, merancang setiap piksel untuk menciptakan pengalaman online yang intuitif dan memikat.',
    imageUrl: teamDewiImage,
    social: { instagram: '#', twitter: '#', linkedin: '#', facebook:'#' },
  },
];

const AboutUsPage: React.FC = () => {
  const [featuredMember, setFeaturedMember] = useState<TeamMember>(teamData[0]);

  const crewMembers = teamData.filter(member => member.name !== featuredMember.name);

  const handleSelectMember = (member: TeamMember) => {
    setFeaturedMember(member);
  };

  return (
    <main className="bg-[#EDE4D5]">
      <section 
        className="relative w-full h-[80vh] bg-cover bg-center flex items-center justify-center" 
        style={{ backgroundImage: `url(${heroBgImage})` }}
      >
        <div className="absolute inset-0 bg-black/60"></div>
        <div className="relative z-10 flex flex-col items-center text-center text-white p-6">
          <h1 className="text-5xl md:text-7xl font-extrabold text-white tracking-tight">
            Our Story, Brewed with <span className="text-orange-500">Passion</span>.
          </h1>
          <p className="mt-6 text-lg text-slate-300 max-w-2xl mx-auto">
            Lebih dari sekadar kopi, kami menyajikan kehangatan dan menciptakan momen. Selamat datang di rumah kedua Anda.
          </p>
          <a href="#team-section">
          <button className="mt-8 bg-orange-500 text-white font-bold px-10 py-3 rounded-lg hover:bg-orange-600 transition-colors shadow-lg text-lg">
            Temui Tim Kami
          </button>
          </a>
        </div>
      </section>

      <section className="py-24 bg-[#4a372d]" id="team-section">
        <div className="container mx-auto px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-4xl md:text-5xl font-bold text-white">
              The <span className='text-[#FFA500]'>KatanyaCafe</span> Crafters
            </h2>
            <p className="mt-4 text-lg text-[#EDE4D5]">Hati dan jiwa di balik setiap cangkir.</p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-12 items-center mb-20">
            <div className="lg:col-span-1 flex justify-center">
              <div className="relative">
                <div className="absolute -inset-2 bg-gradient-to-br from-orange-400 to-yellow-500 rounded-full blur-xl opacity-70 transition-all duration-500"></div>
                <img src={featuredMember.imageUrl} alt={featuredMember.name} className="relative w-48 h-48 md:w-64 md:h-64 rounded-full object-cover border-4 border-white shadow-2xl transition-all duration-500" />
              </div>
            </div>
            <div className="lg:col-span-2 text-center lg:text-left transition-all duration-500">
              <h3 className="text-4xl font-bold text-white">{featuredMember.name}</h3>
              <p className="text-2xl text-orange-400 font-semibold mt-1">{featuredMember.status}</p>
              <p className="text-slate-300 mt-4 max-w-lg mx-auto lg:mx-0">{featuredMember.bio}</p>
              <div className="flex justify-center lg:justify-start space-x-4 mt-6">
                {featuredMember.social.instagram && ( 
                <a href={featuredMember.social.instagram} className="text-slate-400 hover:text-[#E4405F]"><FaInstagram size={24} /></a>
                )}
                {featuredMember.social.facebook && ( 
                <a href={featuredMember.social.facebook} className="text-slate-400 hover:text-[#1877F2]"><FaFacebookF size={24} /></a>
                )}
                {featuredMember.social.twitter && ( 
                <a href={featuredMember.social.twitter} className="text-slate-400 hover:text-black"><FaXTwitter size={24} /></a>
                )}
                {featuredMember.social.linkedin && (
                <a href={featuredMember.social.linkedin} className="text-slate-400 hover:text-[#0077B5]"><FaLinkedinIn size={24} /></a>
                )}
              </div>
            </div>
          </div>
          
          <hr className="border-t-2 border-[#EDE4D5] my-16" />

          <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-8 ">
            {crewMembers.map((member) => (
              <div 
                key={member.name} 
                className="bg-[#3D312A] p-4 rounded-xl text-center flex flex-col items-center cursor-pointer group border-2 border-transparent hover:border-orange-500 transition-all duration-300"
                onClick={() => handleSelectMember(member)}
              >
                <img src={member.imageUrl} alt={member.name} className="w-24 h-24 rounded-full object-cover border-2 border-slate-600 group-hover:border-orange-500 transition-all duration-300" />
                <h4 className="text-lg font-bold text-white mt-4">{member.name}</h4>
                <p className="text-orange-400 text-sm font-semibold">{member.status}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="py-20">
        <div className="container mx-auto px-6 text-center">
          <h2 className="text-3xl md:text-4xl font-bold text-[#4a372d]">Jadilah Bagian dari Cerita Kami.</h2>
          <p className="mt-4 text-lg text-gray-600">Kunjungi kami dan rasakan kehangatannya.</p>
          <a href="/menu">
          <button className="mt-8 bg-[#4a372d] text-white font-bold px-12 py-4 rounded-lg hover:bg-slate-800 transition-colors shadow-lg text-lg">
            Lihat Menu Kami
          </button>
          </a>
        </div>
      </section>
    </main>
  );
};

export default AboutUsPage;