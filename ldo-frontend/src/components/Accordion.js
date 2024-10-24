'use client'
import Image from 'next/image';
import searchIcon from '../../public/searchIcon.svg'
import React, { useState, useMemo } from 'react';
import AccordionItem from './AccordionItem';
import NotFound from './NotFound';

const Accordion = ({ data }) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [accordionData, setAccordionData] = useState(data);

    const filteredData = useMemo(() => {
        return accordionData.filter(item =>
            item.title.toLowerCase().includes(searchTerm.toLowerCase())
        );
    }, [accordionData, searchTerm]);

    const handleSearchChange = (e) => {
        setSearchTerm(e.target.value);
    };

    return (
        <div className=''>
            <div className='relative m-auto w-1/2 rounded-full bg-white search-input'>
                <input
                    type="text"
                    placeholder="Search for Topic"
                    value={searchTerm}
                    onChange={handleSearchChange}
                    className='bg-transparent w-full outline-none pl-20 text-lg py-5'
                />
                <Image src={searchIcon} alt='Search' className='w-10 h-10 absolute top-1/2 -translate-y-1/2 left-4' />
            </div>
            <div className='w-full m-auto px-4 md:px-4 py-10 md:pb-28 lg:py-10 lg:pb-32 lg:px-8 xl:px-10 2xl:px-24 xl:pb-24'>

                {filteredData.length === 0 ? (
                    <NotFound />
                ) : (
                    filteredData.map((item, index) => (
                        <AccordionItem
                            key={index}
                            title={item.title}
                            content={item.content}
                        />
                    ))
                )}
            </div>
        </div>
    );
};

export default Accordion;