import React from 'react';
import Zoom from 'react-medium-image-zoom';
import 'react-medium-image-zoom/dist/styles.css';
import BharatRaj1 from '../../public/images/Bharat-Rajpatra/Notification-on-Article-299-pages 1.jpg'
import BharatRaj2 from '../../public/images/Bharat-Rajpatra/Notification-on-Article-299-pages-2.jpg'
import BharatRaj3 from '../../public/images/Bharat-Rajpatra/Notification-on-Article-299-pages-3.jpg'
import BharatRaj75 from '../../public/images/Bharat-Rajpatra/Notification-on-Article-299-pages-75.jpg'
import Image from 'next/image';

const images = [
    BharatRaj1,
    BharatRaj2,
    BharatRaj3,
    BharatRaj75,
];

const ImageZoomGallery = () => {
  return (
    <div style={{ display: 'flex', flexWrap: 'wrap', gap: '10px' }}>
      {images.map((src, index) => (
        <Zoom key={index}>
          <Image
            src={src}
            alt={`Image ${index + 1}`}
            style={{ width: '250px', height: 'auto', cursor: 'pointer' }}
          />
        </Zoom>
      ))}
    </div>
  );
};

export default ImageZoomGallery;