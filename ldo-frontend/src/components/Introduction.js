"use client"
import React, { useEffect, useState, useContext } from 'react'
import IntroductionImg from '../../public/images/introduction.jpg'
import Image from 'next/image'
import Link from 'next/link'
import { ChevronRightIcon } from '@heroicons/react/24/outline'
import ReadMoreBtn from './ReadMoreBtn'
import { HOST_NAME, API_HOST } from '../constants'; //added by Nitin
import { LangContext } from './Container'; //added by Nitin
const Introduction = () => {
    const { lang } = useContext(LangContext);
    const [componentContent, setComponentContent] = useState('')
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(API_HOST + 'componentData/Introduction/' + lang)
                const result = await response.json()
                if (result.code == 200) {
                    setComponentContent(result)
                }
            } catch (err) {
                console.error('Error Fetching content!', err)
            }
        }
        setTimeout(fetchData, 500);
    }, [lang]);

    return componentContent.sections == undefined ? (
        <h3 className='text-center'>Loading.....</h3>

    ) : (

        <div className='introduction-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10'>
            <div className='block lg:flex items-center w-full'>

                <div className='w-full lg:w-2/4 px-4'>
                    <div className='relative'>
                        <div className='title-group pb-5'>
                            <h2 className='themeTitle text-2xl lg:text-4xl font-bold' data-aos="fade-right" data-aos-duration="1000">{componentContent.heading}</h2>
                        </div>
                        <div className='section_image mb-5' data-aos="zoom-out-right" data-aos-duration="1000">
                            <Image src={(componentContent.sections != undefined) ? HOST_NAME + componentContent.sections[0].image : IntroductionImg} alt='Introduction' className='w-full round-[10px] mt-5' width={500} height={500}/>
                        </div>
                        <div className='orange-content-bg p-5 mb-5 lg:mb-0 text-white rounded-[10px] -mt-10 lg:mt-5 w-full lg:w-1/2 relative lg:absolute right-0 -bottom-0' data-aos="flip-left" data-aos-duration="1000">
                            <div className='subtitle-group pb-2'>
                                <h4 className='md:text-2xl font-bold'>{componentContent.sections ? componentContent.sections[0].title : ''}</h4>
                            </div>
                            <div className=''>
                                <p className='text-white mt-5 text-sm md:text-base'>{(componentContent.sections) ? componentContent.sections[0].content : ''}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div className='w-full lg:w-2/4 px-4 mt-10 lg:mt-0'>
                    <div className='para-border' data-aos="fade-left" data-aos-duration="1000">
                        <p className='md:text-2xl'>{(componentContent.sections) ? componentContent.sections[1].content : ''}</p>
                    </div>
                    {/* <div className='blue-content-bg p-5 text-white rounded-[10px] mt-5' data-aos="flip-right" data-aos-duration="1000">
                        <div className='subtitle-group pb-2'>
                            <h4 className='md:text-4xl font-bold'>{(componentContent.sections) ? componentContent.sections[2].title : ''}</h4>
                        </div>
                        <div>
                            <p className='text-white mt-5 text-xs md:text-base'>{(componentContent.sections) ? componentContent.sections[2].content : ''}</p>
                        </div>
                    </div> */}
                                            <div data-aos="fade-left" data-aos-duration="1000"><ReadMoreBtn link="/history-and-mandate" language={lang} className="mt-5" /></div>

                </div>
            </div>
        </div>
    )
}

export default Introduction