"use client";

import PageHeader from "@/components/PageHeader";
import CitizenCharterBG from "../../../public/citizenCharter.jpg";
import CheckIcon from "../../../public/CheckIcon.svg";
import Image from "next/image";
import TabContainer from "@/components/TabContainer";

/** imports added by Nitin */
import { useContext, useEffect, useState } from "react";
import { HOST_NAME, API_HOST } from "@/constants";
import { LangContext } from "@/components/Container";
import Link from "next/link";
import pdFIcon from "../../../public/pdf_icon.svg";
import ActTable from "@/components/ActTable";
import Translate from "../../language.json";

const Page = () => {
    const { lang } = useContext(LangContext);
    const [componentContent, setComponentContent] = useState("");
    const docRelativePath = "storage/pdf/";
    const columns = [
        { Header: "#", accessor: (row, index) => index + 1 },
        { Header: Translate.title[lang], accessor: "title" },
        { Header: Translate.read[lang], accessor: "action" },
    ];

    const tableData = [
        {
            title: Translate.citizenCharterDoc1Title[lang],
            action: (
                <Link
                    target="_blank"
                    href={
                        HOST_NAME +
                        docRelativePath +
                        "Chapter_1_PARTICULARS_OF_ORGANISATION.pdf"
                    }
                >
                    <Image src={pdFIcon} alt="PDF" />
                </Link>
            ),
        },
        {
            title: Translate.citizenCharterDoc2Title[lang],
            action: (
                <Link
                    target="_blank"
                    href={
                        HOST_NAME +
                        docRelativePath +
                        "CHAPTER_2_DUTIES_OF_VARIOUS_OFFICIALS.pdf"
                    }
                >
                    <Image src={pdFIcon} alt="PDF" />
                </Link>
            ),
        },
        {
            title: Translate.citizenCharterDoc3Title[lang],
            action: (
                <Link
                    target="_blank"
                    href={
                        HOST_NAME +
                        docRelativePath +
                        "CHAPTER_3_DELEGATION_OF_POWERS.pdf"
                    }
                >
                    <Image src={pdFIcon} alt="PDF" />
                </Link>
            ),
        },
        {
            title: Translate.citizenCharterDoc4Title[lang],
            action: (
                <Link
                    target="_blank"
                    href={
                        HOST_NAME +
                        docRelativePath +
                        "CHAPTER_4_DUTIES_OF_VARIOUS_SECTIONS.pdf"
                    }
                >
                    <Image src={pdFIcon} alt="PDF" />
                </Link>
            ),
        },
        {
            title: Translate.citizenCharterDoc5Title[lang],
            action: (
                <Link
                    target="_blank"
                    href={
                        HOST_NAME +
                        docRelativePath +
                        "CHAPTER_5_DOCUMENTS_HELD_BY_IT_OR_UNDER_ITS_CONTROL.pdf"
                    }
                >
                    <Image src={pdFIcon} alt="PDF" />
                </Link>
            ),
        },
        {
            title: Translate.citizenCharterDoc6Title[lang],
            action: (
                <Link
                    target="_blank"
                    href={
                        HOST_NAME +
                        docRelativePath +
                        "CHAPTER_6_PARTICULARS_OF_ARRANGEMENT.pdf"
                    }
                >
                    <Image src={pdFIcon} alt="PDF" />
                </Link>
            ),
        },
    ];

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(
                    API_HOST + "componentData/Citizen Charter/" + lang
                );
                const result = await response.json();
                // let formattedResults = formatListsInSections(result);
                if (result.code == 200) {
                    setComponentContent(result);
                }
            } catch (err) {
                console.error("Error Fetching content!", err);
            }
        };
        setTimeout(fetchData, 500);
    }, [lang]);

    return componentContent.sections == undefined ? (
        <h3 className="text-center">Loading.....</h3>
    ) : (
        <div className="about-us">
            <PageHeader
                pageTitle={
                    lang == "hindi" ? "नागरिक घोषणा पत्र" : "Citizen Charter"
                }
                language={lang}
            />
            <div className="introduction-container bg-slate-50 px-4 md:px-4 py-10 lg:py-10 lg:px-8 xl:px-10 2xl:px-24">
                <div className="w-full md:w-2/4 lg:w-3/4 m-auto px-4">
                    <div
                        className="title-group2 pb-2 lg:pb-5 mb-5"
                        data-aos="fade-up"
                        data-aos-duration="1000"
                    >
                        <h2 className="themeTitle text-2xl md:text-3xl lg:text-4xl font-bold text-center">
                            {componentContent.heading}
                        </h2>
                    </div>
                </div>
                <div className="block xl:flex items-center w-full relative">
                    <div className="w-full lg:w-full 2xl:w-3/4 px-4">
                        <div className="relative">
                            <div
                                className="mb-5"
                                data-aos="zoom-out-right"
                                data-aos-duration="1000"
                            >
                                <Image
                                    src={CitizenCharterBG}
                                    alt="Introduction"
                                    className="w-full round-[10px] mt-5"
                                />
                            </div>
                        </div>
                    </div>
                    <div className="w-full relative px-4 py-4 md:py-5 md:px-5 xl:w-3/6 lg:transform-none xl:-translate-y-2/4 float-content rounded-2xl xl:absolute xl:top-1/2 xl:right-0">
                        <div data-aos="fade-left" data-aos-duration="1000">
                            <div
                                className="title-group-subtitle pb-2 lg:pb-5 mb-5"
                                data-aos="fade-right"
                                data-aos-duration="1000"
                            >
                                <h2 className="themeTitle text-xl lg:text-3xl font-bold">
                                    {componentContent.sections[0].title}
                                </h2>
                            </div>
                            <p className="text-sm md:text-xl">
                                {componentContent.sections[0].content}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div className="introduction-container px-4 md:px-4 py-10 lg:py-10 lg:px-8 xl:px-10 2xl:px-24">
                <div className="w-full md:w-2/4 lg:w-3/4 m-auto px-4">
                    <div
                        className="title-group2 pb-2 lg:pb-5 mb-5"
                        data-aos="fade-up"
                        data-aos-duration="1000"
                    >
                        <h2 className="themeTitle text-2xl md:text-3xl lg:text-4xl font-bold text-center">
                            {componentContent.sections[1].title}
                        </h2>
                    </div>
                </div>
                <div className="block xl:flex items-center w-full relative">
                    <div className="w-full px-4">
                        <div className="relative lsiting-ul">
                            <div
                                className="mb-10"
                                data-aos="fade-up"
                                data-aos-duration="1000"
                                dangerouslySetInnerHTML={{
                                    __html: componentContent.sections[1]
                                        .content,
                                }}
                            />
                            {/* </div> */}
                        </div>
                    </div>
                </div>
            </div>
            {/*<div className='introduction-container bg-slate-50 px-4 md:px-4 py-10 md:pb-28 lg:py-10 lg:pb-32 lg:px-8 xl:px-10 2xl:px-24 xl:pb-24'>
        <div className='w-full md:w-2/4 lg:w-3/4 m-auto px-4'>
          <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
            <h2 className='themeTitle text-2xl md:text-3xl lg:text-4xl font-bold text-center'>Channel of Submission</h2>
          </div>
        </div>
         <div className='block xl:flex items-center w-full relative'>
          <div className='w-full px-4'>
            <div className='relative'>
              <div className='mb-5' data-aos="fade-up" data-aos-duration="1000">
                <p className='text-sm md:text-base text-justify'>With the decision to form the Capital at Delhi, the Lieutenant Governor of Punjab in his notification, ordered the Collector of Delhi District to acquire land for the New Capital of India. After the land was acquired Imperial Delhi Estate was created vide Chief Commissioner, Delhi notification.  The Land and Development work was then done by an Executive Engineer of PWD, The Land and Development work was then done by an Executive Engineer of PWD, The Land and Development work was then done by an Executive Engineer of PWD,  the Lieutenant Governor of Punjab in his notification. The Land and Development work was then done by an Executive Engineer of PWD</p>
                <ul className="list-content" data-aos="fade-up" data-aos-duration="1000">
                  <li className='mb-3 text-sm md:text-base lg:text-lg'><Image src={CheckIcon} alt='Check' className='inline w-4 md:w-5' /> The organisational structure of the office, it hierarchy and names of all its officers right down to sectional head level in all its office.</li>
                  <li className='mb-3 text-sm md:text-base lg:text-lg'><Image src={CheckIcon} alt='Check' className='inline w-4 md:w-5' /> The procedure to be followed, the forms to be filled and other requirements in respect of various transactions.</li>
                </ul>
                <p className='text-sm md:text-base text-justify' data-aos="fade-up" data-aos-duration="1000">With the decision to form the Capital at Delhi, the Lieutenant Governor of Punjab in his notification, ordered the Collector of Delhi District to acquire land for the New Capital of India. After the land was acquired Imperial Delhi Estate was created vide Chief Commissioner, Delhi notification.  The Land and Development work was then done by an Executive Engineer of PWD, The Land and Development work was then done by an Executive Engineer of PWD.</p>
              </div>
            </div>
          </div>

        </div>
      </div> */}
            <ActTable columns={columns} data={tableData} language={lang} />
        </div>
    );
};

export default Page;
