import Link from 'next/link'
import React, {useState, useEffect, useContext} from 'react'
import govtImages from '../../public/gov-sites.png'
import { ChevronRight, ChevronRightCircle, Facebook, Twitter } from 'lucide-react'
import Image from 'next/image'
import ReadMoreBtn from './ReadMoreBtn'

/**code below this line in imports added by Nitin */
import Translate from '../language.json'
import { LangContext } from '@/components/Container'; //Added by Nitin
import { HOST_NAME, API_HOST } from '../constants'; //added by Nitin


const Footer = () => {
    const { lang } = useContext(LangContext);
    const [footerMenus, setFooterMenus] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch(API_HOST + 'footerMenu/' + lang)
        const result = await response.json()
        if (result.code == 200) {
            setFooterMenus(result.data)
        }
      } catch (err) {
        console.error('Error Fetching content!', err)
      }
    }
    fetchData();
  }, [lang]);
    return footerMenus.length == 0 ? (
        <h3 className='text-center'>Loading.....</h3>

    ) : (
        <div className='footer'>
            <div data-aos="zoom-out-up" data-aos-duration="1000" className='footer-get-in-touch px-4 md:px-4 lg:px-8 xl:px-10 2xl:px-24 w-full hidden md:block relative -top-[80px]'>
                <div className='bg-white rounded-[10px] get-in-touch py-8 px-10 flex items-center justify-between relative z-50'>
                    <h3 className='font-bold text-xl md:text-2xl lg:text-4xl'>{Translate.getInTouch[lang]}</h3>
                    <div className='flex items-center justify-between'>
                        {/* <p className='mb-0 mr-10 text-3xl'>— </p> */}
                        {/* <ReadMoreBtn link="/contact-us" /> */}
                        <Link href={'/contact-us'}><ChevronRightCircle className='contact-sticky-icon' /></Link>
                    </div>
                </div>
            </div>
            <div className='footer-container md:-mt-28 px-4 md:px-6 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10'>
                <div className='block md:flex gap-3 w-full py-4 md:py-6 lg:py-8'>
                    <div className='w-full md:w-2/4 lg:w-1/3'>
                        <div className='footer-content'>
                            <h4 className='text-white font-bold my-5 md:mb-[50px]'>{Translate.useCases[lang]}</h4>
                            <ul className='my-2 footer-list'>
                                {
                                    footerMenus[0].submenus.map((item,index)=>{
                                        return <li key={index} className='mb-2 md:mb-4 text-sm md:text-base'><Link href={item.link} target={item.new_tab == 1 ? '_blank':''}>{item.name}</Link></li>
                                    })
                                }
                            </ul>
                        </div>
                    </div>
                    <div className='w-full md:w-2/4 lg:w-1/3'>
                        <div className='footer-content'>
                            <h4 className='text-white font-bold my-5 md:mb-[50px]'>{Translate.department[lang]}</h4>
                            <ul className='my-2 footer-list'>
                            {footerMenus[1].submenus.map((item, index)=>{
                                        return <li key={index}className='mb-2 md:mb-4 text-sm md:text-base'><Link href={item.link} target={item.new_tab == 1 ? '_blank':''}>{item.name}</Link></li>
                                    })}
                              
                            </ul>
                        </div>
                    </div>
                    <div className='w-full md:w-2/4 lg:w-1/3'>
                        <div className='footer-content'>
                        <h4 className='text-white font-bold my-5 md:mb-[50px]'>{Translate.department[lang]}</h4>
                            <ul className='my-2 footer-list'>
                            {footerMenus[2].submenus.map((item,index)=>{
                                        return <li key={index} className='mb-2 md:mb-4 text-sm md:text-base'><Link href={item.link} target={item.new_tab == 1 ? '_blank':''}>{item.name}</Link></li>
                                    })}
                            </ul>
                        </div>
                    </div>
                </div>
                <div className='w-full'>
                    <Image src={govtImages} alt='Govt. Sites' className='w-80' />
                </div>
            </div>
            <div className='footer-copy w-full px-4 py-5'>
                <div className='block md:flex justify-between items-center w-full'>
                    <div className='w-full md:w-3/4'>
                        <ul className='footer-base-list text-sm text-center md:text-left'>
                        {footerMenus[3].submenus.map((item,index)=>{
                                        return <li key={index}><Link href={item.link} target={item.new_tab == 1 ? '_blank':''}>{item.name}</Link></li>
                                    })}
                          
                        </ul>
                    </div>
                    <div className='w-full mt-7 md:w-1/4 md:mt-0'>
                        <p className='text-white text-xs text-center mb-0 copyright-p'>{Translate.cpoyright[lang]} © 2024 L&DO. {Translate.allRightsReserved[lang]}</p>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default Footer