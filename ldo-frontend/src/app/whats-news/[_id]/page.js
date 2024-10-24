'use client'
import PageHeader from '@/components/PageHeader'
import React, { useEffect, useState, useContext } from 'react'
import AOS from 'aos';
import 'aos/dist/aos.css';
import Link from 'next/link';
import Translate from '@/language.json'

/** imports below added by Nitin */
import { HOST_NAME, API_HOST } from '@/constants'; //added by Nitin
import { LangContext } from '@/components/Container'; //added by Nitin
import { useParams } from 'next/navigation'
const Page = () => {
  const [news, setNews] = useState([]);
  const [topNews, setTopNews] = useState([]);
  const { lang } = useContext(LangContext);
  
    const params = useParams();
  const fetchNewsAPI = async () => {
    try {
      const response = await fetch(API_HOST + `newsDescriptionById/${params._id}/${lang}`)
      if (!response.ok) {
        throw new Error('Failed to fetch latest news')
      }
      const data = await response.json()
      setNews(data)
    } catch (err) {
      console.error('Error fetching latest News:', err)
    }
  }
  const fetchTopNewsAPI = async () => {
    try {
      const response = await fetch(API_HOST + `newsDescription/${lang}`)
      if (!response.ok) {
        throw new Error('Failed to fetch latest news')
      }
      const data = await response.json()
      setTopNews(data.slice(0,9));
    } catch (err) {
      console.error('Error fetching latest News:', err)
    }
  }
  useEffect(() => {
    AOS.init();
    fetchNewsAPI()
    fetchTopNewsAPI()
  }, [lang])
  return (
    <div className='whats-news-page'>
      <PageHeader pageTitle={lang=='hindi' ? 'क्या समाचार है': "What's New"} language={lang}/>
      <div className='introduction-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10 2xl:pb-36'>
        <div className='w-full m-auto px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>{lang=='hindi' ? 'क्या समाचार है': "What's New"}</h2>
          </div>
        </div>
        <div className='block lg:flex w-full'>
          <div className='w-full lg:w-3/4 px-4'>
            <h4 className='font-medium text-2xl'>{news.title}</h4>
            <p>
              {news.description}
            </p>
          </div>
          <div className='w-full lg:w-1/4 px-4'>
            <div className='bg-zinc-100 p-5 rounded-xl'>
              <h4 className='news-list-title text-lg md:text-xl font-semibold mb-4'>{Translate.top[lang]} 10 {Translate.keyUpdate[lang]}</h4>
              <ul className='pl-4'>
                {topNews.map(item => (
                  <li key={item.id} className='mb-2 text-zinc-900 font-normal list-disc'><Link href={`/whats-news/${item.id}`} className='hover:underline capitalize'>{item.title}</Link></li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Page