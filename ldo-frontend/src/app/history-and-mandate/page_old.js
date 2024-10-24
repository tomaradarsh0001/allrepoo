'use client'

import PageHeader from "@/components/PageHeader"
import { useContext, useState, useEffect } from "react"
import IntroductionImg from '../../../public/images/introduction.jpg'
import HistoryandMandateImg from '../../../public/history-mandate.png'
import Image from "next/image"
import { HOST_NAME, API_HOST } from '../../constants'; //added by Nitin
import { LangContext } from '../../components/Container'; //added by Nitin

const Page = () => {
  const { lang } = useContext(LangContext);
  const [componentContent, setComponentContent] = useState('')
  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch(API_HOST + 'componentData/History and Mandate/' + lang)
        const result = await response.json();
        if (result.code == 200) {
          setComponentContent(result)
        }
      } catch (err) {
        console.error('Error Fetching content!', err)
      }
    }
    setTimeout(fetchData,500);
  }, [lang]);

  return componentContent.sections == undefined ? (
    <h3 className='text-center'>Loading.....</h3>

  ) : (
    <div className="about-us">
      <PageHeader pageTitle={componentContent.heading} language={lang}/>
      <div className='introduction-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10'>
        <div className='w-full m-auto px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>{componentContent.heading} </h2>
            {/* <span className='themeHighlightColor inline'>(MoHUA)</span></h2> */}
          </div>
        </div>
        <div className='block lg:flex items-center w-full'>
          <div className='w-full lg:w-2/4 px-4'>
            <div className='relative'>
              <div className='section_image mb-5' data-aos="zoom-out-right" data-aos-duration="1000">
                <Image src={IntroductionImg} alt='Introduction' className='w-full round-[10px] mt-5' />
              </div>
              {/* <div className='orange-content-bg p-2 mb-5 lg:mb-0 text-white rounded-[10px] -mt-10 lg:mt-5 w-full relative lg:absolute right-0 bottom-0' data-aos="flip-left" data-aos-duration="1000">
                <div className='subtitle-group pb-2'>
                  <h4 className='md:text-2xl font-bold'>{componentContent.sections[0].subtitle}</h4>
                </div>
                <div data-aos="fade-up" data-aos-duration="1000">
                  <p className='text-white mt-5 text-xs sm:text-sm md:text-base mb-0'>{componentContent.sections[0].content}</p>
                </div>
              </div> */}

            </div>
          </div>
          <div className='w-full lg:w-2/4 px-4 mt-10 lg:mt-0'>
            <div className='para-border' data-aos="fade-left" data-aos-duration="1000">
            <h4 className='md:text-2xl font-bold'>{componentContent.sections[1].title}</h4>
              <p className='md:text-xl'>{componentContent.sections[1].content}</p>
            </div>

          </div>
        </div>
        <div className="block" data-aos="fade-up" data-aos-duration="1000">
        <h4 className='md:text-2xl font-bold'>{componentContent.sections[2].title}</h4>
          <p className='md:text-xl'> {componentContent.sections[2].content}</p>
        </div>
      </div>
      <div className='introduction-container bg-slate-50 px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10 2xl:pb-24'>
        <div className='w-full md:w-2/4 lg:w-3/4 m-auto px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-down" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl md:text-3xl lg:text-4xl font-bold text-center'>{componentContent.sections[3].title}</h2>
            {/* <span className='themeHighlightColor inline'>(LDO)</span> */}
          </div>
        </div>
        <div className='block lg:flex items-center w-full'>
          <div className='w-full lg:w-3/5 px-4'>
            <div className='relative'>
              <div className='mb-5' data-aos="zoom-out-right" data-aos-duration="1000">
                <Image src={HistoryandMandateImg} alt='Introduction' className='w-full round-[10px] mt-5' />
              </div>
            </div>
          </div>
          <div className='w-full lg:w-2/5 px-4 md:mt-2 md:mb-20 lg:mt-10'>
            <div data-aos="fade-left" data-aos-duration="1000">
              <p className='md:text-xl text-justify'>{componentContent.sections[3].content}</p>
            </div>

          </div>
        </div>
      </div>


      <div className='introduction-container bg-slate-50 px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10 2xl:pb-24'>
        <div className='w-full md:w-2/4 lg:w-3/4 m-auto px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-down" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl md:text-3xl lg:text-4xl font-bold text-center'>{componentContent.sections[4].title}</h2>
            {/* <span className='themeHighlightColor inline'>(LDO)</span> */}
          </div>
        </div>
        <div className='block lg:flex items-center w-full'>
          {/* <div className='w-full lg:w-3/5 px-4'>
            <div className='relative'>
              <div className='mb-5' data-aos="zoom-out-right" data-aos-duration="1000">
                <Image src={HistoryandMandateImg} alt='Introduction' className='w-full round-[10px] mt-5' />
              </div>
            </div>
          </div> */}
          <div className='w-full md:mt-2 md:mb-20 lg:mt-10'>
            <div data-aos="fade-left" data-aos-duration="1000">
              <p className='md:text-xl text-justify'>{componentContent.sections[4].content}</p>
            </div>

          </div>
        </div>
      </div>

    </div>
  )
}

export default Page