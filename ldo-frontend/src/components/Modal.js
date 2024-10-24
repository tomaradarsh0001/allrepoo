// components/Modal.js
import Image from 'next/image';
import closeIcon from '../../public/close-circle.svg'
import React from 'react';

const Modal = ({ isOpen, onClose, children }) => {
  return (
    <>
      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
          <div className="services-modal bg-white p-8 max-w-sm md:max-w-md lg:max-w-5xl relative rounded-2xl" data-aos="zoom-out-down" data-aos-duration="300">
            <button className="absolute top-2 right-2 text-gray-700" onClick={onClose}>
              <Image src={closeIcon} alt='Close' className='w-6 md:w-8 lg:w-10 relative' />
            </button>
            {children}
          </div>
        </div>
      )}
    </>
  );
};

export default Modal;