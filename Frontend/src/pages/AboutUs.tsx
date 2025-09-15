import React from 'react';
import { FaInstagram, FaLinkedinIn } from 'react-icons/fa';
import { FaXTwitter } from 'react-icons/fa6';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';
import 'swiper/swiper.css';

import heroBgImage from '../assets/about-us/hero.png'
const teamNabilaImage = '/path/to/your/assets/team-nabila.jpg';
const teamDaniImage = '/path/to/your/assets/team-dani.jpg';
const teamSitiImage = '/path/to/your/assets/team-siti.jpg';
const teamRianImage = '/path/to/your/assets/team-rian.jpg';
const teamDewiImage = '/path/to/your/assets/team-dewi.jpg';

const teamData = [
  {
    name: 'Nabila Raisa',
    status: 'Owner & Visionary',
    bio: 'Nabila adalah otak dan hati di balik KatanyaCafe, memastikan setiap aspek kafe mencerminkan visi dan kecintaannya pada kopi berkualitas.',
    imageUrl: teamNabilaImage,
    social: { instagram: '#', twitter: '#', linkedin: '#' },
    isTop: true,
  },
  {
    name: 'Dani Ardian',
    status: 'Web Developer',
    bio: 'Dani adalah arsitek digital kami, membangun fondasi website yang kuat dan fungsional dari nol hingga menjadi pengalaman yang mulus.',
    imageUrl: teamDaniImage,
    social: { instagram: '#', twitter: '#', linkedin: '#' },
    isTop: true,
  },
  {
    name: 'Siti Amelia',
    status: 'Frontend Developer',
    bio: 'Siti mengubah desain menjadi antarmuka yang interaktif dan responsif, memastikan website kami terlihat indah di semua perangkat.',
    imageUrl: teamSitiImage,
    social: { instagram: '#', twitter: '#', linkedin: '#' },
  },
  {
    name: 'Rian Prasetyo',
    status: 'Backend Developer',
    bio: 'Rian adalah mesin di balik layar, mengelola database, transaksi, dan semua logika server yang membuat website berjalan cepat dan aman.',
    imageUrl: teamRianImage,
    social: { instagram: '#', twitter: '#', linkedin: '#' },
  },
  {
    name: 'Dewi Lestari',
    status: 'UI/UX Designer',
    bio: 'Dewi adalah seniman visual kami, merancang setiap piksel dan alur pengguna untuk menciptakan pengalaman online yang intuitif dan memikat.',
    imageUrl: teamDewiImage,
    social: { instagram: '#', twitter: '#', linkedin: '#' },
  },
];

const AboutUsPage: React.FC = () => {
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

      <section className="py-24 bg-[#4a372d] overflow-hidden" id="team-section">
        <div className="container mx-auto">
          <div className="text-center mb-16 px-6">
            <h2 className="text-4xl md:text-5xl font-bold text-white">
              The <span className='text-[#FFA500]'>KatanyaCafe</span> Crafters
            </h2>
            <p className="mt-4 text-lg text-[#EDE4D5]">Hati dan jiwa di balik setiap cangkir.</p>
          </div>
          <Swiper
              modules={[Autoplay]}
              spaceBetween={30}
              slidesPerView={'auto'}
              loop={true}
              speed={5000}
              allowTouchMove={false}
              autoplay={{
                delay: 0,
                disableOnInteraction: false,
              }}
              className="[&_.swiper-wrapper]:!transition-timing-function-linear"
            >
              {[...teamData, ...teamData].map((member, index) => (
                <SwiperSlide key={`${member.name}-${index}`} className="!w-auto pb-10 !h-auto">
                  <div className="bg-[#EDE4D5] rounded-2xl p-8 text-center flex flex-col items-center shadow-lg w-80 h-full">
                    <div className="relative">
                      <img src={member.imageUrl} alt={member.name} className="w-32 h-32 rounded-full object-cover border-4 border-orange-400 mb-6" />
                      { member.isTop && (
                      <div className="absolute -top-2 -right-2 bg-orange-500 text-white rounded-full p-2 text-xs font-bold">TOP</div>
                      )}
                    </div>
                    <h3 className="text-2xl font-bold text-slate-800">{member.name}</h3>
                    <p className="text-orange-600 font-semibold mb-4">{member.status}</p>
                    <p className="text-gray-600 text-sm flex-grow">{member.bio}</p>
                    <div className="flex space-x-4 mt-6">
                      <a href={member.social.instagram} className="text-gray-500 hover:text-[#E4405F]"><FaInstagram size={20} /></a>
                      <a href={member.social.twitter} className="text-gray-500 hover:text-black"><FaXTwitter size={20} /></a>
                      <a href={member.social.linkedin} className="text-gray-500 hover:text-[#0077B5]"><FaLinkedinIn size={20} /></a>
                    </div>
                  </div>
                </SwiperSlide>
              ))}
          </Swiper>
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