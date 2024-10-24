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
    const [tableData, setTableData] = useState([]);
    const [heading, setHeading] = useState([]);
    const columns = [
        { Header: "#", accessor: (row, index) => index + 1 },
        { Header: "File No.", accessor: "file_no" },
        { Header: "Case No.", accessor: "case_no" },
        { Header: "Subject", accessor: "subject" },
        {
            Header: "Status",
            accessor: (row, index) => (row.status ? row.status : "NA"),
        },
        {
            Header: "LDOH-NDOH",
            accessor: (row, index) => {
                return `${row.ldoh ? row.lodoh : ""} - ${
                    row.ndoh ? row.ndoh : ""
                }`;
            },
        },
        { Header: "Section", accessor: "section" }, ///dDepartment removed by adarsh
        { Header: "Commencement Date", accessor: "commencement_date" },
        // {
        //     Header: "Closing Date",
        //     accessor: (row, index) =>
        //         row.closing_date ? row.closing_date : "NA",
        // },
        {
            Header: "Closing Date/Judgement",
            accessor: (row, index) =>
                row.judgement_link ? (
                    <>
                        <div>{row.closing_date ? row.closing_date : "NA"}</div>
                        <Link
                            target="_blank"
                            href={HOST_NAME + row.judgement_link}
                        >
                            <Image src={pdFIcon} alt="PDF" />
                        </Link>
                    </>
                ) : (
                    "NA"
                ),
        },
        {
            Header: "Active in Court",
            accessor: (row, index) =>
                row.active_in_court ? row.active_in_court : "NA",
        },
    ];
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(API_HOST + "esocourt/" + lang);
                const result = await response.json();
                if (result.items.length > 0) {
                    setTableData(result.items);
                    setHeading(result.heading);
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
            <PageHeader pageTitle={heading} language={lang} />

            <div className="whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10">
                <div className="w-full px-4">
                    <div
                        className="title-group2 pb-2 lg:pb-5 mb-5"
                        data-aos="fade-up"
                        data-aos-duration="1000"
                    >
                        <h2 className="themeTitle text-2xl lg:text-4xl font-bold text-center">
                            {heading}
                        </h2>
                    </div>
                </div>
            </div>
            <ActTable columns={columns} data={tableData} language={lang} />
        </div>
    );
};

export default Page;
