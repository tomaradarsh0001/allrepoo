'use client'
import React, { useState } from 'react';


const AccordionItem = ({ title, content }) => {
    const [isExpanded, setIsExpanded] = useState(false);
    const toggleCollapse = () => {
        setIsExpanded(!isExpanded);
    };

    return (
        <div className='accordion-items p-6 rounded-md my-2' data-aos="fade-up" data-aos-duration="1000">
            <div className={isExpanded ? 'Collapse custom-transition' : 'Expand custom-transition'} onClick={toggleCollapse} style={{ cursor: 'pointer', fontWeight: 'bold' }}
            >
                <h3 className='text-2xl font-medium'>{title}</h3>
            </div>
            {isExpanded && (
                <div className='mt-4'>
                    <p className='mb-0'>{content}</p>
                </div>
            )}
        </div>
    );
};

export default AccordionItem;