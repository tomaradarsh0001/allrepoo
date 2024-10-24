import React, { useState } from 'react'
import Tab from './Tab'

const TabContainer = ({tabs}) => {
    const [activeTab, setActiveTab] = useState(0)
    const handleTabClick = (index) => {
        setActiveTab(index);
    }
  return (
    <div className='tabs-child-container'>
        {tabs.map((tab, index)=> {
            <Tab 
            key={index}
            label={tab.label}
            isActive={index === activeTab}
            onClick={()=> handleTabClick(index)} />
        })}
        <div className='tab-content'>
            {tabs[activeTab].content}
        </div>
    </div>
  )
}

export default TabContainer