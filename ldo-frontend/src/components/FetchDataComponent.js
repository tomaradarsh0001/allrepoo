'use client'
import React, { useEffect, useState } from 'react'
import searchIcon from '../../public/searchIcon.svg'
import Image from 'next/image'

const FetchDataComponent = () => {
    const [data, setData] = useState([]);
    const [filteredData, setFilteredData] = useState([]);
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('');

    useEffect(() => {
        // Fetch data from your API or endpoint
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const response = await fetch('https://jsonplaceholder.typicode.com/posts'); // Replace with your API endpoint
            const result = await response.json();
            setData(result);
            setFilteredData(result);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };

    const handleSearch = () => {
        const filtered = data.filter(item =>
            item.name.toLowerCase().includes(searchTerm.toLowerCase())
        );
        setFilteredData(filtered);
    };

    const handleCategoryChange = (category) => {
        setSelectedCategory(category);
        const filtered = data.filter(item => item.category === category);
        setFilteredData(filtered);
    };

    return (
        <div className='introduction-container px-4 md:px-4 py-10 md:pb-28 lg:py-10 lg:pb-32 lg:px-8 xl:px-10 2xl:px-24 xl:pb-24'>
            <div className='w-full md:w-2/4 lg:w-3/4 m-auto px-4'>
                <div className='filter-container flex items-center justify-between'>
                <div className='relative w-1/2 rounded-full bg-white border-zinc-500 dropdown-filter'>
                    <input
                        type="text"
                        placeholder="Search..."
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className='bg-transparent w-full outline-none pl-20 text-xl py-5'
                    />
                    <Image src={searchIcon} alt='Search' className='w-10 h-10 absolute top-1/2 -translate-y-1/2 left-4' />
                </div>

                <select
                    value={selectedCategory}
                    onChange={(e) => handleCategoryChange(e.target.value)}
                    className='rounded-full px-4 py-5 outline-none dropdown-filter w-1/4 text-xl'
                    >
                    <option value="">All Categories</option>
                </select>
                </div>

                <table className='table-container'>
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        {filteredData.map((item) => (
                            <tr key={item.id}>
                                <td>{item.id}</td>
                                <td>{item.title}</td>
                                <td>{item.completed}</td>
                                <td>{item.category}</td>
                                {/* Add more cells based on your data */}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    )
}

export default FetchDataComponent