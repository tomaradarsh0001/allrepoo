'use client'
import PageHeader from '@/components/PageHeader'
import React, { useEffect, useState, useContext } from 'react'
import pdFIcon from '../../../public/pdf_icon.svg'
import Image from 'next/image'
import ActTable from '@/components/ActTable'
import Link from 'next/link'
import Translate from '@/language.json'
import { HOST_NAME, API_HOST } from '@/constants'; //added by Nitin
import { LangContext } from '@/components/Container'; //added by Nitin

const Page = () => {
    const { lang } = useContext(LangContext);
    const [tableData, setTableData] = useState([])
    const columns = [
        { Header: '#', accessor: (row, index) => index + 1 },
        { Header: Translate.documantName[lang], accessor: 'docName' },
        { Header: Translate.uploadDate[lang], accessor: 'uploadedDate' },
        { Header: Translate.action[lang], accessor: 'action' },
      ]
      useEffect(() => {
          const fetchData = async () => {
              try {
                  const response = await fetch(API_HOST + 'officeDocs/1/' + lang)
                  const result = await response.json();
                  if (result.code == 200) {
                    let data  = result.data
                    let formattedData = data.map(row=>{
                        return{
                            id:row.id,
                            docName:row.docName,
                            uploadedDate:row.uploadedDate,
                            action: <Link target='_blank' href={row.attachment? row.attachment:'/'}><Image src={pdFIcon} alt='PDF' /></Link>
                        }
                    })
                    setTableData(formattedData)
                  }
              } catch (err) {
                  console.error('Error Fetching content!', err)
              }
          }
          fetchData();
      }, [lang]);
      return tableData.length == 0 ? (
          <h3 className='text-center'>Loading.....</h3>
  
      ) : (
        <div className='acts-and-rules'>
            <PageHeader pageTitle={lang == "hindi" ? "अधिनियम एवं नियम":"Acts And Rules"} language={lang}/>
            <div className='whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10'>
                <div className='w-full px-4'>
                    <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
                        <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>{lang == "hindi" ? "अधिनियम एवं नियम":"Acts And Rules"}</h2>
                    </div>
                </div>
            </div>
            <ActTable columns={columns} data={tableData} language={lang} />
        </div>
    )
}

export default Page