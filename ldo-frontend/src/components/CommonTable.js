import React, { useMemo } from 'react';
import { useTable, useSortBy, useGlobalFilter, usePagination } from 'react-table';
import searchIcon from '../../public/searchIcon.svg'
import Image from 'next/image';
import { ChevronLeft, ChevronRight } from 'lucide-react';

const CommonTable = ({columns, data}) => {
    const {
        getTableProps,
        getTableBodyProps,
        headerGroups,
        rows,
        prepareRow,
        state,
        setGlobalFilter,
        page,
        nextPage,
        previousPage,
        canNextPage,
        canPreviousPage,
    } = useTable(
        {
            columns,
            data,
            initialState: { pageIndex: 0 },
        },
        useGlobalFilter,
        useSortBy,
        usePagination
    );

    const { globalFilter, pageIndex } = state;

    const memoizedColumns = useMemo(() => columns, [columns]);
    return (
        <div>
            <div className='filter-container flex items-center' data-aos="fade-up" data-aos-duration="1000">
                <h3 className='mr-3 text-lg lg:text-xl'>Search By</h3>
                <div className='relative w-1/3 rounded-full bg-white border-zinc-500 dropdown-filter'>
                    <input
                        type="text"
                        value={globalFilter || ''}
                        onChange={(e) => setGlobalFilter(e.target.value)}
                        placeholder={"Search By Name, ID"}
                        className='bg-transparent w-full outline-none pl-16 text-lg py-1 lg:py-2'
                    />
                    <Image src={searchIcon} alt='Search' className='w-4 h-4 lg:w-6 lg:h-6 absolute top-1/2 -translate-y-1/2 left-4' />
                </div>
            </div>
            <table {...getTableProps()} style={{ width: '100%' }} className='table-container' data-aos="fade-up" data-aos-duration="1000">
                <thead>
                    {headerGroups.map((headerGroup, index) => (
                        <tr key={index} {...headerGroup.getHeaderGroupProps()}>
                            {headerGroup.headers.map((column, index) => (
                                <th key={index} className='text-base font-semibold' {...column.getHeaderProps(column.getSortByToggleProps())}>
                                    {column.render('Header')}
                                    <span>
                                        {column.isSorted ? (column.isSortedDesc ? ' 🔽' : ' 🔼') : ''}
                                    </span>
                                </th>
                            ))}
                        </tr>
                    ))}
                </thead>
                <tbody {...getTableBodyProps()}>
                    {page.map((row, index) => {
                        prepareRow(row);
                        return (
                            <tr key={index} {...row.getRowProps()}>
                                {row.cells.map((cell) => (
                                    <td key={index} className='text-base' {...cell.getCellProps()}>{cell.render('Cell')}</td>
                                ))}
                            </tr>
                        );
                    })}
                </tbody>
            </table>
            <div className='flex items-center gap-4 mt-5 justify-end'>
                <button className='bg-zinc-300 w-9 h-9 lg:w-10 lg:h-10 flex items-center justify-center rounded-md text-white pagination-btn' onClick={() => previousPage()} disabled={!canPreviousPage}>
                    <ChevronLeft />
                </button>{' '}
                <span className='text-base lg:text-lg'>
                    Page{' '}
                    <strong>
                        {pageIndex + 1} of {page.length}
                    </strong>{' '}
                </span>
                <button onClick={() => nextPage()} disabled={!canNextPage} className='bg-zinc-300 w-9 h-9 lg:w-10 lg:h-10 flex items-center justify-center rounded-md text-white pagination-btn'>
                    <ChevronRight />
                </button>{' '}
            </div>
        </div>
    )
}

export default CommonTable