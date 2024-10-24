/* import PageHeader from '@/components/PageHeader'
import Table from '@/components/Table'
import Link from 'next/link';
import React from 'react'
import pdFIcon from '../../../public/pdf_icon.svg'
import Image from 'next/image'

const page = () => {
  const columns = [
    { Header: 'ID', accessor: 'id' },
    { Header: 'Title', accessor: 'title' },
    { Header: 'Issued Date', accessor: 'IssuedDate' },
    { Header: 'Last Date', accessor: 'LastDate' },
    { Header: 'Tender/RFP Link', accessor: 'tenderRFPLink' }
  ];
  const data = [
    { id: 1, title: 'Response to Prebid queries & Corrigendum 1 - Request for Empanelment (RFE) to Empanel Event Management, Printing & Video Creation cum editing Agencies', IssuedDate: '3/02/2023', LastDate: "17.02.2023 by 06:00 PM Opening of Bid: 20.02.2023 by 12:00 PM", tenderRFPLink: <Link target='_blank' href={'https://static.mygov.in/static/s3fs-public/mygov_167542622551307401.pdf'}><Image src={pdFIcon} alt='Response to Prebid' /></Link> },
  ];
  return (
    <div>
      <PageHeader pageTitle="Tenders" />
      <div className='whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10'>
        <div className='w-full px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>Tenders</h2>
          </div>
        </div>
      </div>
      <Table columns={columns} data={data} />
    </div>
  )
}

export default page */

"use client";

import PageHeader from "@/components/PageHeader";

import React, { useEffect, useState, useContext } from "react";
import pdFIcon from "../../../public/pdf_icon.svg";
import Image from "next/image";
import ActTable from "@/components/ActTable";
import Link from "next/link";
import Translate from "../../language.json";
import { HOST_NAME, API_HOST } from "../../constants"; //added by Nitin
import { LangContext } from "@/components/Container"; //added by Nitin

const Page = () => {
    const { lang } = useContext(LangContext);
    const [tableData, setTableData] = useState([
        {
            id: "",
            docName: Translate.noDocFound[lang],
            uploadDate: "",
            action: "",
        },
    ]);
    const columns = [
        { Header: "#", accessor: (row, index) => index + 1 },
        { Header: Translate.documantName[lang], accessor: "docName" },
        { Header: Translate.uploadDate[lang], accessor: "uploadedDate" },
        { Header: Translate.action[lang], accessor: "action" },
    ];
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(API_HOST + "officeDocs/5/" + lang);
                const result = await response.json();
                if (result.code == 200) {
                    let data = result.data;
                    let formattedData = data.map((row) => {
                        return {
                            id: row.id,
                            docName: row.docName,
                            uploadedDate: row.uploadedDate,
                            action: (
                                <Link
                                    target="_blank"
                                    href={row.attachment ? row.attachment : "/"}
                                >
                                    <Image src={pdFIcon} alt="PDF" />
                                </Link>
                            ),
                        };
                    });
                    setTableData(formattedData);
                }
            } catch (err) {
                console.error("Error Fetching content!", err);
            }
        };
        fetchData();
    }, [lang]);
    return tableData.length == 0 ? (
        <h3 className="text-center">Loading.....</h3>
    ) : (
        <div className="contact-us">
            <PageHeader
                pageTitle={lang == "hindi" ? "निविदाएं" : "Tenders"}
                language={lang}
            />

            <div className="whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10">
                <div className="w-full px-4">
                    <div
                        className="title-group2 pb-2 lg:pb-5 mb-5"
                        data-aos="fade-up"
                        data-aos-duration="1000"
                    >
                        <h2 className="themeTitle text-2xl lg:text-4xl font-bold text-center">
                            {lang == "hindi" ? "निविदाएं" : "Tenders"}
                        </h2>
                    </div>
                </div>
            </div>
            <ActTable columns={columns} data={tableData} language={lang} />
        </div>
    );
};

export default Page;
