import React from 'react';
import { TbServerOff } from 'react-icons/tb';

interface NetworkErrorProps {
  onRetry: () => void;
}

const NetworkError: React.FC<NetworkErrorProps> = ({ onRetry }) => {
  return (
    <div className="flex flex-col items-center justify-center text-center py-20 px-4 rounded-lg ">
      <TbServerOff className="text-7xl text-white" />
      <h2 className="mt-6 text-3xl font-bold text-slate-100">
        Oops! Gagal Terhubung
      </h2>
      <p className="mt-2 max-w-md text-gray-400">
        Sepertinya ada gangguan pada server atau koneksi Anda. Silakan coba lagi beberapa saat lagi.
      </p>
      <button
        onClick={onRetry}
        className="mt-8 bg-yellow-500 text-black font-semibold px-8 py-3 rounded-lg shadow-lg shadow-yellow-500/20 hover:bg-yellow-600 transition-all duration-300 transform hover:scale-105"
      >
        Coba Lagi
      </button>
    </div>
  );
};

export default NetworkError;