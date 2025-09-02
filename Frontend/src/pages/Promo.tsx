import React from 'react';
import '../css/Promo.css';

const Promo: React.FC = () => {
  return (
    <div className="promo-page">
      <h1>Promo Spesial Untuk Anda</h1>
      <div className="promo-grid">
        <div className="promo-card">
          <h3>Diskon 50% Akhir Tahun</h3>
          <p>Nikmati potongan harga besar untuk semua item fashion. Gunakan kode: ENDYEAR50</p>
        </div>
        <div className="promo-card">
          <h3>Gratis Ongkir Se-Indonesia</h3>
          <p>Belanja minimal Rp 150.000 dan dapatkan gratis ongkir tanpa batas.</p>
        </div>
        <div className="promo-card">
          <h3>Cashback 10% Pengguna Baru</h3>
          <p>Daftar sekarang dan dapatkan cashback instan untuk pembelian pertamamu!</p>
        </div>
      </div>
    </div>
  );
};

export default Promo;