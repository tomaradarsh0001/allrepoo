"use client"

import React, { useEffect, useState, useContext } from 'react'
import Link from 'next/link'
import pdFIcon from '../../../public/pdf_icon.svg'
import AOS from 'aos';
import 'aos/dist/aos.css';
import { HOST_NAME, API_HOST } from '@/constants'; //added by Nitin
import { LangContext } from '@/components/Container'; //added by Nitin

import Image from 'next/image'
import Translate from '@/language.json'
import ReadMoreBtn from '@/components/ReadMoreBtn';
import PageHeader from '@/components/PageHeader'
import ActTable from '@/components/ActTable'


const Page = () => {

  const [news, setNews] = useState([])
  const { lang } = useContext(LangContext);
  const fetchNewsAPI = async () => {
    try {
      const response = await fetch(API_HOST + `newsDescription/${lang}`)
      if (!response.ok) {
        throw new Error('Failed to fetch latest news')
      }
      const data = await response.json()
      let formattedNews = data.map(row=>{
        return{
            id:row.id,
            title:row.title,
            readMore:<ReadMoreBtn link={`/whats-news/${row.id}`} language={lang} />,
            action: <Link target='_blank' href={row.document_url? row.document_url:'/'}><Image src={pdFIcon} alt='PDF' /></Link>
        }
    })
      setNews(formattedNews)
    } catch (err) {
      console.error('Error fetching latest News:', err)
    }
  }
  useEffect(() => {
    AOS.init();
    fetchNewsAPI()
  }, [lang])

  const columns = [
    { Header: '#', accessor: (row, index) => index + 1 },
    { Header: Translate.headline[lang], accessor: 'title' },
    { Header: Translate.readMore[lang], accessor: 'readMore' },
    { Header: '', accessor: 'action' }
  ];

  return news.length == 0 ? (
    <h3 className='text-center'>Loading.....</h3>

) :  (
<div className="contact-us">
<PageHeader pageTitle={lang == "hindi" ? "नया क्या है":"What's New"} language={lang} />


<div className='whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10'>
<div className='w-full px-4'>
  <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
  <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>{lang == "hindi" ? "नया क्या है":"What's New"}</h2>
  </div>
</div>
</div>
<ActTable columns={columns} data={news} language={lang} />

</div>

)
}

export default Page