import React from 'react'
import citiesIcon from '../../public/facts/faMountainCity.svg'
import propertiesIndiaIcon from '../../public/facts/faPropertiesIndia.svg'
import propertiesDelhiIcon from '../../public/facts/faPropertiesDelhi.svg'
import residentialPropertiesIcon from '../../public/facts/faPropertiesResidential.svg'
import commercialPropertiesIcon from '../../public/facts/faPropertiesCommercial.svg'
import institutionalIcon from '../../public/facts/faInstitutional.svg'
import industrialIcon from '../../public/facts/faIndustrial.svg'
import governmentIcon from '../../public/facts/faGovernment.svg'
import foreignMissionsIcon from '../../public/facts/faForeignMissions.svg'
import visitorsCountIcon from '../../public/facts/faVisitorsCount.svg'
import Image from 'next/image'

const DashboardFacts = () => {
    const factsData = [
        { factsIcon: citiesIcon, factsTitle: 'No. of Cities', factsValue: '2,345' },
        { factsIcon: propertiesIndiaIcon, factsTitle: 'No. of Properties in India', factsValue: '34,243' },
        { factsIcon: propertiesDelhiIcon, factsTitle: 'No. of Properties in Delhi', factsValue: '2,354' },
        { factsIcon: residentialPropertiesIcon, factsTitle: 'No. of Residential Properties', factsValue: '14,424' },
        { factsIcon: commercialPropertiesIcon, factsTitle: 'Commercial Properties', factsValue: '7,643' },
        { factsIcon: institutionalIcon, factsTitle: 'Institutional', factsValue: '696' },
        { factsIcon: industrialIcon, factsTitle: 'Industrial', factsValue: '3,432' },
        { factsIcon: governmentIcon, factsTitle: 'Government', factsValue: '9,871' },
        { factsIcon: foreignMissionsIcon, factsTitle: 'Foreign Missions', factsValue: '24,424' },
        { factsIcon: visitorsCountIcon, factsTitle: 'Visitors Count', factsValue: '3,45,24,424' },
    ]
    return (
        <div className='dashboard-facts-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-24'>
            <div className='facts-row grid grid-cols-2 gap-2 2xl:grid-cols-5 2xl:gap-7 xl::grid-cols-5 xl:gap-6 lg:grid-cols-4 lg:gap-4 md:grid-cols-3 md:gap-3'>
                {
                    factsData.map((item, index) => {
                        return (
                            <div key={index} className='facts-card text-center w-full' data-aos="fade-up">
                                <div className='facts-icon text-center'>
                                    <Image src={item.factsIcon} alt={item.factsTitle} className='w-auto h-auto m-auto' />
                                </div>
                                <div className='facts-value my-2'>
                                    <h3 className='text-base 2xl:text-3xl font-bold'>{item.factsValue}</h3>
                                </div>
                                <div className='facts-title'>
                                    <p className='text-base text-white mb-0'>{item.factsTitle}</p>
                                </div>
                            </div>
                        )
                    })
                }

            </div>
        </div>
    )
}

export default DashboardFacts