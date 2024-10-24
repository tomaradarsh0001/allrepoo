'use client'
import PageHeader from '@/components/PageHeader'
import Table from '@/components/Table'
import React,{ useEffect, useState, useContext } from 'react'
import { HOST_NAME, API_HOST } from '../../constants'; //added by Nitin
import { LangContext } from '@/components/Container'; //added by Nitin
import Translate from '../../language.json'
const Page = () => {
  const { lang } = useContext(LangContext);
    const [officerData, setOfficerData] = useState([])
    const [heading,setHeading] = useState('');
    useEffect(() => {
      const fetchData = async () => {
          try {
              const response = await fetch(API_HOST + 'directory/' + lang)
              const result = await response.json();
              if (result.items && result.items.length > 0) {
             setOfficerData(result.items)
              }
              setHeading(result.heading)
          } catch (err) {
              console.error('Error Fetching content!', err)
          }
      }
      setTimeout(fetchData, 500);
  }, [lang]);
  const columns = [
    { Header: '#', accessor: (row, index) => index + 1 },
    { Header: Translate.officerName[lang], accessor: 'name' },
    { Header: Translate.mobile[lang], accessor: 'telephone' },
    { Header: Translate.roomNo[lang], accessor: 'room_no' },
    { Header: Translate.designation[lang], accessor: 'designation' },
  ];
  return officerData.length > 0 ?  (
    <div className='whos-who-container'>
      <PageHeader pageTitle={heading} language={lang}/>
      <div className='whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10'>
        <div className='w-full px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>{heading}</h2>
          </div>
        </div>
      </div>
      <div className='mb-10'><Table columns={columns} data={officerData} language={lang}/></div>
    </div>
  ): (
    <h3 className='text-center'>Loading.....</h3>

)
}

export default Page