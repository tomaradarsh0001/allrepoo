import React, { useEffect, useState, useContext } from "react";
import SubstitutionImage from "../../public/images/introduction.jpg";
import CheckIcon from "../../public/CheckIcon.svg";
import Image from "next/image";
import Link from "next/link";
import ReadMoreBtn from "./ReadMoreBtn";
import { HOST_NAME, API_HOST } from "../constants"; //added by Nitin
import { LangContext } from "./Container"; //added by Nitin
import Translate from "@/language.json"; //added by Swati
const WhatWeDo = () => {
    const { lang } = useContext(LangContext);
    const [componentContent, setComponentContent] = useState("");
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(
                    API_HOST + "componentData/What We Do/" + lang
                );
                const result = await response.json();
                if (result.code == 200) {
                    setComponentContent(result);
                }
            } catch (err) {
                console.error("Error Fetching content!", err);
            }
        };
        fetchData();
    }, [lang]);
    return componentContent.sections == undefined ? (
        <h3 className="text-center">Loading.....</h3>
    ) : (
        <div>
            <div className="whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10">
                <div className="w-full px-4">
                    <div
                        className="title-group2 pb-2 lg:pb-5 mb-5"
                        data-aos="fade-up"
                        data-aos-duration="1000"
                    >
                        <h2 className="themeTitle text-2xl lg:text-4xl font-bold text-center">
                            {componentContent.heading}
                        </h2>
                    </div>
                </div>
            </div>

            <div className="whatwedo section-bg-0 px-4 py-4 md:px-6 md:py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10">
                <div className="block md:flex items-center w-full py-4 md:py-6 lg:py-8">
                    <div className="w-full md:w-2/4 px-4 mb-5 lg:mb-0 md:hidden">
                        <div
                            className="section_image"
                            data-aos="zoom-out-left"
                            data-aos-duration="1000"
                        >
                            <Image
                                src={
                                    HOST_NAME +
                                    componentContent.sections[0].image
                                }
                                target="_blank"
                                alt="Substitution Image"
                                className="w-full relative gradient_img"
                                width={150}
                                height={150}
                            />
                        </div>
                    </div>
                    <div className="w-full md:w-2/4 px-4">
                        <div className="section-content">
                            <div
                                className="title-group-subtitle pb-2 lg:pb-5 mb-5"
                                data-aos="fade-right"
                                data-aos-duration="1000"
                            >
                                <h2 className="themeTitle text-xl lg:text-3xl font-bold">
                                    {componentContent.sections[0].title}
                                </h2>
                            </div>
                            <p
                                className="text-sm md:text-base lg:text-lg"
                                data-aos="fade-right"
                                data-aos-duration="1000"
                            >
                                {componentContent.sections[0].content}
                            </p>
                            {/* <div data-aos="fade-right" data-aos-duration="1000"><ReadMoreBtn link={componentContent.sections[0].link ? componentContent.sections[0].link : '/services'} language={lang}/></div> */}
                            <div
                                className="more-info-link mt-4"
                                data-aos="fade-right"
                                data-aos-duration="1000"
                            >
                                <Link
                                    className="read-docs-btn text-sm md:text-lg px-5 py-2 mr-2"
                                    href={
                                        componentContent.sections[0].link
                                            ? HOST_NAME +
                                              componentContent.sections[0].link
                                            : "#"
                                    }
                                    target={
                                        componentContent.sections[0].link
                                            ? "_blank"
                                            : ""
                                    }
                                >
                                    {Translate.ReadDocs[lang]}
                                </Link>
                                <Link
                                    className="apply-btn text-sm md:text-lg px-5 py-2"
                                    href={"https://ldo.gov.in/edharti/"}
                                    target="_blank"
                                >
                                    {Translate.ForApply[lang]}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div className="w-full md:w-2/4 px-4 mb-5 lg:mb-0 hidden md:block">
                        <div
                            className="section_image"
                            data-aos="zoom-out-left"
                            data-aos-duration="1000"
                        >
                            <Image
                                src={
                                    HOST_NAME +
                                    componentContent.sections[0].image
                                }
                                alt="Substitution Image"
                                className="w-full relative gradient_img"
                                width={150}
                                height={150}
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* <div className="whatwedo section-bg-0 px-4 py-4 md:px-6 md:py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10">
                <div className="block md:flex items-center w-full py-4 md:py-6 lg:py-8">
                    <div className="w-full md:w-2/4 px-4 mb-5 lg:mb-0 md:hidden">
                        <div className='section_image' data-aos="zoom-out-left" data-aos-duration="1000">
                            <Image src={SubstitutionImage} alt='Substitution Image' className='w-full relative gradient_img' />
                        </div>
                    </div>
                    <div className="w-full md:w-2/4 px-4">
                        <div className='section-content'>
                            <div className='title-group-subtitle pb-2 lg:pb-5 mb-5' data-aos="fade-right" data-aos-duration="1000">
                                <h2 className='themeTitle text-xl lg:text-3xl font-bold'>Substitution</h2>
                            </div>
                            <p className='text-sm md:text-base lg:text-lg' data-aos="fade-right" data-aos-duration="1000">The principle of substitution in real estate states that the upper limit of a property value is set by the cost of purchasing a worthy alternative of the same designs, use, and utility, provided there are no untimely delays. The principle of substitution means that property buyers will always go for a cheaper alternative that meets the required criteria in the market.</p>
                            <div data-aos="fade-right" data-aos-duration="1000"><ReadMoreBtn link="/services" /></div>
                        </div>
                    </div>
                    <div className="w-full md:w-2/4 px-4 mb-5 lg:mb-0 hidden md:block">
                        <div className='section_image' data-aos="zoom-out-left" data-aos-duration="1000">
                            <Image src={SubstitutionImage} alt='Substitution Image' className='w-full relative gradient_img' />
                        </div>
                    </div>
                </div>
            </div> */}
            <div className="whatwedo section-bg-1 px-4 py-4 md:px-6 md:py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10">
                <div className="block md:flex items-center w-full py-4 md:py-6 lg:py-8">
                    <div className="w-full md:w-2/4 px-4 mb-5 lg:mb-0">
                        <div
                            className="section_image"
                            data-aos="zoom-out-right"
                            data-aos-duration="1000"
                        >
                            <Image
                                src={
                                    HOST_NAME +
                                    componentContent.sections[1].image
                                }
                                alt="Substitution Image"
                                className="w-full relative gradient_img"
                                width={150}
                                height={150}
                            />
                        </div>
                    </div>
                    <div className="w-full md:w-2/4 px-4">
                        <div className="section-content">
                            <div
                                className="title-group-subtitle pb-2 lg:pb-5 mb-5"
                                data-aos="fade-left"
                                data-aos-duration="1000"
                            >
                                <h2 className="themeTitle text-xl lg:text-3xl font-bold">
                                    {componentContent.sections[1].title}
                                </h2>
                            </div>
                            <p
                                className="text-sm md:text-base lg:text-lg"
                                data-aos="fade-left"
                                data-aos-duration="1000"
                            >
                                {componentContent.sections[1].content}
                            </p>
                            {/* <ul data-aos="fade-left" data-aos-duration="1000">
                                <li className='mb-3 text-sm md:text-base lg:text-lg'><Image src={CheckIcon} alt='Check' className='inline w-4 md:w-5' /> Inheriting ancestral property</li>
                                <li className='mb-3 text-sm md:text-base lg:text-lg'><Image src={CheckIcon} alt='Check' className='inline w-4 md:w-5' /> Registering purchased or gifted land</li>
                            </ul> */}
                            {/* <div data-aos="fade-left" data-aos-duration="1000"><ReadMoreBtn link="/services" language={lang}/></div> */}
                            <div
                                className="more-info-link mt-4"
                                data-aos="fade-left"
                                data-aos-duration="1000"
                            >
                                <Link
                                    className="read-docs-btn text-sm md:text-lg px-5 py-2 mr-2"
                                    href={
                                        componentContent.sections[1].link
                                            ? HOST_NAME +
                                              componentContent.sections[1].link
                                            : "#"
                                    }
                                    target={
                                        componentContent.sections[1].link
                                            ? "_blank"
                                            : ""
                                    }
                                >
                                    {Translate.ReadDocs[lang]}{" "}
                                </Link>
                                <Link
                                    className="apply-btn text-sm md:text-lg px-5 py-2"
                                    href={"https://ldo.gov.in/edharti/"}
                                    target="_blank"
                                >
                                    {Translate.ForApply[lang]}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className="whatwedo section-bg-0 px-4 py-4 md:px-6 md:py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10">
                <div className="block md:flex items-center w-full py-4 md:py-6 lg:py-8">
                    <div className="w-full md:w-2/4 px-4 mb-5 lg:mb-0 md:hidden">
                        <div
                            className="section_image"
                            data-aos="zoom-out-left"
                            data-aos-duration="1000"
                        >
                            <Image
                                src={
                                    HOST_NAME +
                                    componentContent.sections[2].image
                                }
                                alt="Substitution Image"
                                className="w-full relative gradient_img"
                                width={150}
                                height={150}
                            />
                        </div>
                    </div>
                    <div className="w-full md:w-2/4 px-4">
                        <div className="section-content">
                            <div
                                className="title-group-subtitle pb-2 lg:pb-5 mb-5"
                                data-aos="fade-right"
                                data-aos-duration="1000"
                            >
                                <h2 className="themeTitle text-xl lg:text-3xl font-bold">
                                    {componentContent.sections[2].title}
                                </h2>
                            </div>
                            <p
                                className="text-sm md:text-base lg:text-lg"
                                data-aos="fade-right"
                                data-aos-duration="1000"
                            >
                                {componentContent.sections[2].content}
                            </p>
                            {/* <div data-aos="fade-right" data-aos-duration="1000"><ReadMoreBtn link="/services" language={lang}/></div> */}
                            <div
                                className="more-info-link mt-4"
                                data-aos="fade-right"
                                data-aos-duration="1000"
                            >
                                <Link
                                    className="read-docs-btn text-sm md:text-lg px-5 py-2 mr-2"
                                    href={
                                        componentContent.sections[2].link
                                            ? HOST_NAME +
                                              componentContent.sections[2].link
                                            : "#"
                                    }
                                    target={
                                        componentContent.sections[2].link
                                            ? "_blank"
                                            : ""
                                    }
                                >
                                    {Translate.ReadDocs[lang]}
                                </Link>
                                <Link
                                    className="apply-btn text-sm md:text-lg px-5 py-2"
                                    href={"https://ldo.gov.in/edharti/"}
                                    target="_blank"
                                >
                                    {Translate.ForApply[lang]}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div className="w-full md:w-2/4 px-4 mt-10 lg:mt-0 hidden md:block">
                        <div
                            className="section_image"
                            data-aos="zoom-out-left"
                            data-aos-duration="1000"
                        >
                            <Image
                                src={
                                    HOST_NAME +
                                    componentContent.sections[2].image
                                }
                                alt="Substitution Image"
                                className="w-full relative gradient_img"
                                width={150}
                                height={150}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default WhatWeDo;
