import Link from 'next/link'
import React from 'react'
import Image from 'next/image'
import NewsAPI from './NewsAPI'

const NewsContainer = () => {
  return (
    <div className='news-container md:flex md:items-center md:justify-between p-2 lg:px-8' data-aos="zoom-out-up" data-aos-duration="1000">
        <div className='keyUpdate w-full md:w-[108px] border-b-2 md:border-b-0 md:border-r-2'>
            <p className='font-semibold text-center md:text-left text-base mb-0'>Key Update</p>
        </div>
        <NewsAPI />
    </div>
  )
}

export default NewsContainer