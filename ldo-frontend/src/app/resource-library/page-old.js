'use client'

import PageHeader from "@/components/PageHeader"
import TabsComponent from "@/components/TabsComponent"


const page = () => {

  return (
    <div className="resource-library">
      <PageHeader pageTitle="Resource Library" />
      <div className='introduction-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10 2xl:pb-5'>
      <div className='w-full m-auto px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>Resource <span className='themeHighlightColor inline'>Library</span></h2>
          </div>
        </div>
      </div>
      <div className="introduction-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-7 2xl:pb-36">
        <TabsComponent />
      </div>
    </div>
  )
}

export default page