import React from 'react'
import BreadCrumb from './BreadCrumb'
import NewsSlider from './NewsSlider';

const PageHeader = (props) => {
  let language = props.language ? props.language:"english";
    return (
        <div className='page-header'>
            <div className='dashboard-facts-container px-4 md:px-4 py-3 lg:px-8 xl:py-4 xl:px-10'>
                <div className='bg-black/20 py-5 px-10 rounded-[10px] text-white'>
                    <div className='internalTitle-group pb-3 md:pb-5' data-aos="fade-right" data-aos-duration="1000">
                        <h2 className='themeInternalTitle text-2xl lg:text-4xl font-bold text-white'>{props.pageTitle}</h2>
                    </div>
                    <BreadCrumb pageTitle={props.pageTitle} language={language}/>
                </div>
            </div>
            <NewsSlider />
        </div>
    )
}

export default PageHeader