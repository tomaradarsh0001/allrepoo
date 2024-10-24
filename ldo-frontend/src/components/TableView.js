import React from 'react';
import ResouceTable from './ResourceTable';
import Image from 'next/image';
import pdFIcon from '../../public/pdf_icon.svg'
import Link from 'next/link';

function TableView({columns, data, language}) {
  return (
    <div>
      <ResouceTable columns={columns} data={data} language={language}/>
    </div>
  );
}

export default TableView;