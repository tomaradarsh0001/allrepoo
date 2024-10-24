'use client'

import PageHeader from "@/components/PageHeader"
import ContactImage from '../../../public/contact-meet.svg'
import CheckIcon from '../../../public/CheckIcon.svg'
import PhoneIcon from '../../../public/phoneIcon.svg'
import ArrowNarrowRightIcon from '../../../public/arrow-narrow-right.svg'
import AddressLocationIco from '../../../public/contacts/address_location.svg'
import FacebookIco from '../../../public/contacts/facebook.svg'
import PhoneIco from '../../../public/contacts/phone.svg'
import EmailIcon from '../../../public/email_icon.svg'

import TwitterIco from '../../../public/contacts/twitter.svg'
import Image from "next/image"
import Link from "next/link"


import { HOST_NAME, API_HOST } from '@/constants'; //added by Nitin
import { LangContext } from '@/components/Container'; //added by Nitin
import Translate from '../../language.json';
import { useContext, useEffect, useState, } from "react"
import BtnLink from "@/components/BtnLink"

const Page = () => {
  
  const { lang } = useContext(LangContext);
  const [content, setContent] = useState('');

  const eHearingLink = "https://ldo.gov.in/eDharti/SSL/NewUserRegistration/ApplicantRegistration/OnlineAppointment.aspx?id=a41e0eea6e85ed7490ac9d09fdfc94fb";
  const officeVisitLink = "https://ldo.gov.in/eDharti/SSL/NewUserRegistration/ApplicantRegistration/OfflineAppointment.aspx?id=76fc83e5c4f93adc2047d37380806c57";

  const setContactcontent = ()=>{
    let text1 = lang == "hindi" ? `
    भूमि एवं प्रशासन द्वारा प्रशासित संपत्तियों के पट्टेदारों/मालिकों को सुविधा प्रदान करने के लिए विकास कार्यालय बातचीत के विकल्पों को बढ़ाने का एक और उपाय रहा है लिया गया। यह निर्णय लिया गया है कि भौतिक सार्वजनिक बैठकें पूर्व में आयोजित की जाएंगी प्रत्येक बुधवार, गुरुवार और शुक्रवार को 1400-1500 बजे तक अपॉइंटमेंट। `: `In a bid to facilitate the lessees/owners of the properties administered by the Land & Development Office another measure of augmenting the interaction options has been taken. It has been decided that the physical public meetings shall be held on prior appointments on every Wednesday, Thursday and Friday from 1400-1500 hrs.`
    let text2 = lang == "hindi" ? `जनता के पास प्रत्येक बुधवार, गुरुवार और शुक्रवार को 1500-1530 बजे तक पूर्व अपॉइंटमेंट के साथ वर्चुअल प्लेटफॉर्म के माध्यम से बातचीत का अतिरिक्त विकल्प होगा।`:`Public will have additional option of interaction through a virtual platform with a prior appointment on every Wednesday, Thursday and Friday from 1500-1530 hrs.`

    setContent({heading:Translate.toBookAppointment[lang], text1:text1, text2:text2});
  }

  useEffect(setContactcontent,[lang]  )
  return (
    <div className="contact-us">
      <PageHeader pageTitle={Translate.contactUs[lang]} language={lang}/>
      <div className='introduction-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10'>
        <div className='block lg:flex items-center w-full'>
          <div className='w-full lg:w-2/4 px-4'>
            <div className='relative' data-aos="zoom-out-right" data-aos-duration="1000">
              <Image src={ContactImage} alt="Contact Meeting" className="w-full" />
            </div>
          </div>
          <div className='w-full lg:w-2/4 px-4 mt-10 lg:mt-0'>
            <div className='section-content'>
              <div className='title-group-subtitle pb-2 lg:pb-5 mb-5' data-aos="fade-right" data-aos-duration="1000">
                <h2 className='themeTitle text-xl lg:text-3xl font-bold'>{content.heading}</h2>
              </div>
              {/* <p className='text-sm md:text-base lg:text-lg' data-aos="fade-right" data-aos-duration="1000">Registrar through web-based video-conferencing system on the VIDYO platform hosted on the servers of National Data Centre of National Informatics. Centre, Govt.</p>
              <ul data-aos="fade-left" data-aos-duration="1000">
                <li className='mb-3 text-sm md:text-base lg:text-lg'><Image src={CheckIcon} alt='Check' className='inline w-4 md:w-5' /> The Party-in-Person shall specify as to whether he/she would link to the Registrar through own Desktop/Laptop Computer or would prefer to avail the facility for video-conferencing in the Supreme Court premises.</li>
                <li className='mb-3 text-sm md:text-base lg:text-lg'><Image src={CheckIcon} alt='Check' className='inline w-4 md:w-5' /> Hence, it is expected that the request for such links be forwarded on aforementioned email-id at the earliest, latest by 2 PM on the day preceding the hearing of the matter.</li>
              </ul> */}
              <p>{content.text1}</p>
              <p>
                <BtnLink href={officeVisitLink}target="_blank" text={Translate.officeVisitAppointment[lang]}/>
                </p>
              <p>{content.text2}</p>
              <p  >
                <BtnLink href={eHearingLink}target="_blank" text={Translate.eHearingAppointment[lang]} />
              </p>
            </div>
          </div>
        </div>
      </div>
      <div className="tollfree-container relative px-4 py-10 md:px-4 lg:px-8 lg:py-28 xl:px-16 2xl:px-24 2xl:py-20">
        <div className="contact-tollfree z-10 relative bg-black/30 py-5 px-10 rounded-[10px] text-white md:flex items-center justify-center gap-10 backdrop-blur-md">
          <div className="flex items-center justify-center" data-aos="zoom-out-right" data-aos-duration="1000">
          <Link href={'tel:1800111705'}>
            <div className="iconBG p-4 sm:w-16 sm:h-16 md:w-32 md:h-32 rounded-full flex items-center justify-center">
              <Image src={PhoneIcon} alt="Tollfree" className="w-8 md:w-14 lg:w-20" />
            </div>
          </Link>
          <h3 className="text-3xl md:text-4xl lg:text-5xl font-semibold ml-3" data-aos="zoom-out-right" data-aos-duration="1000">{Translate.tollFree[lang]}</h3>
          </div>
          <div className="flex items-center justify-center" data-aos="zoom-out-left" data-aos-duration="1000">
          <Image src={ArrowNarrowRightIcon} alt="Arrow" className="w-auto mr-3" />
          <Link href={'tel:1800111705'} className="tollfree-number text-4xl md:text-6xl lg:text-8xl">1800111705</Link>
          </div>
        </div>
      </div>
      <div className='introduction-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10 2xl:pb-36 contact-us-info'>
        <div className='block lg:flex items-center w-full'>
          <div className='w-full lg:w-3/5 px-4'>
            <div className="block md:flex justify-between items-center">
              <div className='w-full py-4 md:w-2/3 px-4 md:py-0'>
                <div className="flex items-center" data-aos="fade-up" data-aos-duration="1000">
                  <div className="contact-Icons w-11 h-11 sm:w-16 sm:h-16 md:w-[85px] md:h-[85px] rounded-lg p-2 md:p-4 mr-3">
                    <Image src={AddressLocationIco} alt="Address" className="w-full" />
                  </div>
                  {/* <h4 className="text-sm">Land and Development Office (MoHUA), <br /> Gate #4, A Wing, 6th floor, Nirman Bhawan</h4> */}
                  <h4 className="text-sm">
                    {lang === "hindi" ? 
                      "भूमि और विकास कार्यालय (एम ओ एच यू ए), गेट #4, ए विंग, 6ठी मंजिल, निर्माण भवन" : 
                      "Land and Development Office (MoHUA), Gate #4, A Wing, 6th floor, Nirman Bhawan"}
                  </h4>
                </div>
              </div>
              <div className='w-full md:w-2/6 px-4'>
                <div className="flex items-center" data-aos="fade-up" data-aos-duration="1500">
                  <div className="contact-Icons w-11 h-11 sm:w-16 sm:h-16 md:w-[85px] md:h-[85px] rounded-lg p-2 md:p-4 mr-3">
                    <Image src={PhoneIco} alt="Contact Meeting" className="w-full" />
                  </div>
                  <h4>011-23062871,<br /> 011-23022174</h4>
                </div>
              </div>
            </div>
            <div className="block my-4 md:my-0 md:flex justify-between items-center">

              <div className='w-full py-4 md:py-4 md:w-2/3 px-4'>
                <div className="flex items-center" data-aos="fade-up" data-aos-duration="2000">
                  <div className="contact-Icons w-11 h-11 sm:w-16 sm:h-16 md:w-[85px] md:h-[85px] rounded-lg p-2 md:p-4 mr-3">
                    <Image src={EmailIcon} alt="Contact Meeting" className="w-full" />
                  </div>
                  <h4>ldo@nic.in</h4>
                </div>

              </div>
              {/* <div className='w-full md:w-2/6 px-4'>
                <div className="flex items-center" data-aos="fade-up" data-aos-duration="2500">
                  <div className="contact-Icons w-11 h-11 sm:w-16 sm:h-16 md:w-[85px] md:h-[85px] rounded-lg p-2 md:p-4 mr-3">
                    <Image src={TwitterIco} alt="Contact Meeting" className="w-full" />
                  </div>
                  <h4>Twitter</h4>
                </div>
              </div> */}
            </div>
          </div>
          <div className='w-full lg:w-2/5 px-4 mt-10 lg:mt-0'>
            <div className="contact-map" data-aos="zoom-out-left" data-aos-duration="1000">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.651975694626!2d77.21483207500755!3d28.610215685094598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce2b875b93a87%3A0xf3db6f0b6ba1a0f5!2sLand%20and%20Development%20Office!5e0!3m2!1sen!2sin!4v1709901097163!5m2!1sen!2sin" width="100%" height="320px" className="border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Page