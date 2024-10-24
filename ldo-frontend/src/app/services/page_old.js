'use client'
import PageHeader from '@/components/PageHeader'
import substitution from '../../../public/services/substitution.svg'
import mutation from '../../../public/services/mutation.svg'
import conversion from '../../../public/services/conversion.svg'
import landuse from '../../../public/services/landuse.svg'
import TemporaryAllotment from '../../../public/services/TemporaryAllotment.svg'
import membership from '../../../public/services/membership.svg'
import buildingPlan from '../../../public/services/buildingPlan.svg'
import executionDeedApartment from '../../../public/services/execution-deed-apartment.svg'
import vacantland from '../../../public/services/vacantland.svg'
import multistorey from '../../../public/services/multistorey.svg'
import demarcationLand from '../../../public/services/demarcationLand.svg'
import publicEviction from '../../../public/services/publicEviction.svg'
import MortgagePermission from '../../../public/services/MortgagePermission.svg'
import issuanceCertificate from '../../../public/services/issuance-certificate.svg'
import executionLease from '../../../public/services/executionLease.svg'
import salepermission from '../../../public/services/salepermission.svg'
import noc from '../../../public/services/noc.svg'
import AllotmentofLandtocentral from '../../../public/services/AllotmentofLandtocentral.svg'
import rateOfIncrease from '../../../public/services/rate-of-increase.svg'
import inspection from '../../../public/services/inspection.svg'
import { EyeIcon } from '@heroicons/react/24/outline'
import React, { useEffect, useState, useContext } from 'react'
import Image from 'next/image'
import Modal from '@/components/Modal'
/**imports after this line added by Nitin */
import { HOST_NAME, API_HOST } from '../../constants';
import { LangContext } from '@/components/Container';
import Translate from '../../language.json'
const Page = () => {
  const [isModalOpen, setModalOpen] = useState(false);
  const [activeModel,setActiveModal] = useState({});
  const openModal = (index) => {
    setActiveModal(servicesData[index])
    setModalOpen(true)
  }
  const closeModal = () => {
    setActiveModal({})
    setModalOpen(false)
  }

  const { lang } = useContext(LangContext);
  const [servicesData, setservicesData] = useState([]);
  const [heading,setHeading] = useState('Services');
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(API_HOST + 'servicesData/' + lang)
                const result = await response.json()
                console.log(result);
                if (result.code == 200) {
                  setHeading(result.heading);
                  setservicesData(result.sections);
                }
            } catch (err) {
                console.error('Error Fetching content!', err)
            }
        }
        setTimeout(fetchData, 500);
    }, [lang]);
  return servicesData.length == 0 ? (
    <h3 className='text-center'>Loading.....</h3>

) :(
    <div className='services-offered-page'>
      <PageHeader pageTitle={heading} language={lang}/>
      <div className='whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10'>
        <div className='w-full px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>{heading}</h2>
          </div>
        </div>
      </div>
      <div className='px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-12 lg:mt-[50px]'>
        <div className='services-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mt-11 2xl:grid-cols-4 2xl:gap-7 xl::grid-cols-4 xl:gap-6 lg:grid-cols-4 lg:gap-4 md:grid-cols-3 md:gap-5 md:mt-12'>
          {
            servicesData.map((item, index) => {
              return (
                <div key={index} className='services-card text-center w-full mb-14 md:mb-20' data-aos="fade-up" data-aos-duration="1000">
                  <div className='w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 m-auto'>
                    <div className='services-icon circle-sm circle2 text-center -mt-16 p-3 w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 m-auto flex items-center justify-center rounded-full md:-mt-16 lg:-mt-[5rem] bg-white md:p-4'>
                      <Image src={HOST_NAME+item.servicesIcon} alt={item.servicesTitle} className='w-12 h-12 md:w-auto md:h-auto object-cover m-auto' width={100} height={100} />
                    </div>
                  </div>
                  <div className='services-title services-subtitle-group my-2 mt-3'>
                    <h3 className='text-base  2xl:text-xl pb-2 font-medium'>{item.servicesTitle}</h3>
                  </div>
                  <div className='services-description mt-2'>
                    <p className='text-base'>{item.description.substring(0,120) + (item.description.length > 120 ? '...':'')}</p>
                  </div>
                  <div className='services-link'>
                    <button onClick={()=>{openModal(index)}} className='text-sm sm:text-base inline-flex items-center justify-center'>{Translate.readMore[lang]} <EyeIcon className='ml-1 w-3 sm:w-4' /></button>
                  </div>
                </div>
              )
            })
          }
        </div>
      </div>
      <Modal isOpen={isModalOpen} onClose={closeModal}>
        <div className='modal-container relative'>
          <div className='flex items-center justify-start'>
            <div className='w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32'>
              <div className='services-icon circle-sm circle2 text-center p-3 w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 m-auto flex items-center justify-center rounded-full bg-white md:p-4'>
                <Image src={HOST_NAME +activeModel.servicesIcon} alt="Substitution" className='w-12 h-12 md:w-auto md:h-auto object-cover m-auto' width={200} height={200} />
              </div>
            </div>
            <div className='services-title services-subtitle-group my-2 ml-5'>
              <h3 className='text-base xl:text-2xl 2xl:text-4xl pb-2 font-medium'>{activeModel.servicesTitle}</h3>
            </div>
          </div>
        </div>
        <div className='p-2 mt-5'>
          <p>{activeModel.description}</p>
        </div>
      </Modal>
    </div>
  )
}

export default Page