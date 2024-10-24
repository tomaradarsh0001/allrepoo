import React, { useEffect, useState, useContext } from "react";
import eDharti from "../../public/eportal/eDharti.svg";
import eHearingAppointment from "../../public/eportal/e-HearingAppointment.svg";
import eDhartiGeoPortal from "../../public/eportal/eDhartiGeoPortal.svg";
import LandAllotment from "../../public/eportal/LandAllotment.svg";
import MediaGallery from "../../public/eportal/MediaGallery.svg";
import Feedback from "../../public/eportal/Feedback.svg";
import PublicGrievances from "../../public/eportal/PublicGrievances.svg";
import OfficeVisitAppointment from "../../public/eportal/OfficeVisitAppointment.svg";
import Image from "next/image";
import Link from "next/link";
import { HOST_NAME, API_HOST } from "../constants"; //added by Nitin
import { LangContext } from "./Container"; //added by Nitin

const PortalGroup = () => {
    const { lang } = useContext(LangContext);
    const [componentContent, setComponentContent] = useState("");
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(
                    API_HOST + "componentData/E-Portal/" + lang
                );
                const result = await response.json();
                console.log(result);
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
        <div className="portal-group-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10">
            <div className="portal-container grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-14">
                {componentContent.sections.map((item, ind) => {
                    let itemLink = item.link ?? "#";
                    let linkType = item.link_type;
                    if (linkType == 3) {
                        itemLink = HOST_NAME + itemLink;
                    }
                    return (
                        <div key={ind} className="grid-item mb-5 sm:mb-0">
                            <Link
                                href={itemLink}
                                className="portal-link"
                                target={linkType > 1 ? "_blank" : ""}
                            >
                                <div className="icon_group circle relative">
                                    <div className="icons_circle circle-sm circle2 relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center">
                                        <Image
                                            src={HOST_NAME + item.image}
                                            alt="eDharti"
                                            className="w-50"
                                            width={50}
                                            height={50}
                                        />
                                    </div>
                                    <span className="circle__back-1"></span>
                                    <span className="circle__back-2"></span>
                                </div>
                                <h4 className="font-medium text-lg text-center mt-2 md:mt-2">
                                    {item.title}
                                </h4>
                            </Link>
                        </div>
                    );
                })}
                {/*  <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="1000">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle circle-sm circle2 relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={eDharti} alt='eDharti' className='w-50' />
                            </div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>eDharti</h4>
                    </Link>
                </div>
                <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="1300">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={eHearingAppointment} alt='e-Hearing Appointment' className='w-50' /></div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>e-Hearing Appointment</h4>
                    </Link>
                </div>
                <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="1600">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={eDhartiGeoPortal} alt='eDharti Geo Portal' className='w-50' /></div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>eDharti Geo Portal</h4>
                    </Link>
                </div>
                <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="1900">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={LandAllotment} alt='Land Allotment' className='w-50' /></div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>Land Allotment</h4>
                    </Link>
                </div>
                <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="2100">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={MediaGallery} alt='eDharti' className='w-50' /></div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>Media Gallery</h4>
                    </Link>
                </div>
                <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="2400">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={Feedback} alt='Feedback' className='w-50' /></div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>Feedback</h4>
                    </Link>
                </div>
                <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="2700">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={PublicGrievances} alt='Public Grievances' className='w-50' /></div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>Public Grievances</h4>
                    </Link>
                </div>
                <div className='grid-item mb-5 sm:mb-0' data-aos="fade-up" data-aos-duration="3000">
                    <Link href={'/'} className='portal-link'>
                        <div className='icon_group circle relative'>
                            <div className='icons_circle relative w-20 h-20 m-auto rounded-full bg-white flex items-center justify-center'><Image src={OfficeVisitAppointment} alt='Office Visit Appointment' className='w-50' /></div>
                            <span className="circle__back-1"></span>
                            <span className="circle__back-2"></span>
                        </div>
                        <h4 className='font-medium text-sm text-center mt-2 md:mt-2'>Office Visit Appointment</h4>
                    </Link>
                </div> */}
            </div>
        </div>
    );
};

export default PortalGroup;
