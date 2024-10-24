import React from 'react';
import Image from 'next/image';
import emb1 from '../../public/embassy/emb1.jpg'
import emb2 from '../../public/embassy/emb2.jpg'
import emb3 from '../../public/embassy/emb3.jpg'
import emb4 from '../../public/embassy/emb4.jpg'
import emb5 from '../../public/embassy/emb5.jpg'
// import emb6 from '../../public/embassy/emb6.jpg'
// import emb7 from '../../public/embassy/emb7.jpg'
// import emb8 from '../../public/embassy/emb8.jpg'

const images = [
    { id: 1, source: emb3, alt: 'Image 1' },
    { id: 2, source: emb2, alt: 'Image 2' },
    { id: 3, source: emb1, alt: 'Image 3' },
    { id: 4, source: emb4, alt: 'Image 4' },
    { id: 5, source: emb5, alt: 'Image 5' },
    // { id: 6, source: emb6, alt: 'Image 6' },
    // { id: 7, source: emb7, alt: 'Image 7' },
    // { id: 8, source: emb8, alt: 'Image 8' },
];

const ImageGallery = () => {
  return (
    <div className="image-gallery">
      {images.map((image) => (
        <div key={image.id} className="image-item">
          <Image
            src={image.source}
            alt={image.alt}
            width={500}
            height={300}
            // layout="fill"
            objectFit="cover"
          />
        </div>
      ))}
    </div>
  );
};

export default ImageGallery;