import Accordion from '@/components/Accordion';
import PageHeader from '@/components/PageHeader';
import React from 'react'


const page = () => {
    const accordionData = [
        { title: 'How to apply for Substitution?', content: 'Substitution is the process of mutation of the names of legal heirs on the death of lessee. Application for this purpose shall be made on a plain paper signed by all or one of the legal heirs accompanied by the following documents:- ( i) Attested copy of the death certificate of the lessee issued by the Local Body.' },
        { title: 'What are the document required for Substitution?', content: 'Substitution is the process of mutation of the names of legal heirs on the death of lessee. Application for this purpose shall be made on a plain paper signed by all or one of the legal heirs accompanied by the following documents:- ( i) Attested copy of the death certificate of the lessee issued by the Local Body.' },
        { title: 'What is the function of LDO?', content: 'Substitution is the process of mutation of the names of legal heirs on the death of lessee. Application for this purpose shall be made on a plain paper signed by all or one of the legal heirs accompanied by the following documents:- ( i) Attested copy of the death certificate of the lessee issued by the Local Body.' },
        { title: 'What are the Inspection of Properties?', content: 'Substitution is the process of mutation of the names of legal heirs on the death of lessee. Application for this purpose shall be made on a plain paper signed by all or one of the legal heirs accompanied by the following documents:- ( i) Attested copy of the death certificate of the lessee issued by the Local Body.' },
        // Add more items as needed
    ];
    return (
        <div className='faq-container'>
            <PageHeader pageTitle="FAQ" />
            <div className='introduction-container px-4 md:px-4 py-10 md:pb-28 lg:py-10 lg:pb-32 lg:px-8 xl:px-10 2xl:px-24 xl:pb-24'>
                <div className='w-full md:w-2/4 lg:w-3/4 m-auto px-4'>
                    <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
                        <h2 className='themeTitle text-2xl md:text-3xl lg:text-4xl font-bold text-center'>Frequently Asked Questions</h2>
                    </div>
                    <p className='text-center'>Find advice and answers from our support team fast or get in touch</p>
                </div>
                <div className='mt-5'>
                    <Accordion data={accordionData} />
                </div>
            </div>
        </div>
    )
}

export default page