"use client";
import Link from "next/link";
import React, { Fragment, useState, useEffect, useContext } from "react";
import { Popover, Transition } from "@headlessui/react";
import Image from "next/image";
import LanguageIcon from "../../public/faLanguage.svg";
import AppearanceIcon from "../../public/faUniversalAccess.svg";
import SocialIcon from "../../public/social-media.svg";
import Navigation from "./Navigation";
import AOS from "aos";
import "aos/dist/aos.css";
import { LangContext } from "./Container";
import Translate from "@/language.json";

const Navbar = () => {
    // Initiate Animation
    useEffect(() => {
        AOS.init();
    }, []);
    const { lang, setLang } = useContext(LangContext);
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);
    return (
        <div className="header">
            <div className="topbar">
                <ul className="topbar_list text-xs md:text-sm lg:text-base">
                    <li>
                        <Link href={"#"}>{Translate.Substitution[lang]}</Link>
                    </li>
                    <li>
                        <Link href={"#"}>{Translate.Mutation[lang]}</Link>
                    </li>
                    <li>
                        <Link href={"https://ldo.gov.in/edharti/"}>
                            {Translate.NOCs[lang]}
                        </Link>
                    </li>
                    {/* <li><Link href={'/'}><Image src={AppearanceIcon} alt='Appearance' /></Link></li>
          <li><Link href={'/'}><Image src={SocialIcon} alt='Social Media' /></Link></li> */}
                    <li>
                        <Link href={"/"}>
                            {Translate.skipToMainContent[lang]}
                        </Link>
                    </li>
                </ul>
                {/* <li><Link href={'/'}><Image src={LanguageIcon} alt='Land and Development Office' className='mt-1 mr-1' /> English</Link></li> */}
                {/* <Popover className="relative">
                        <Popover.Button
                            className=""
                            style={{ display: "flex", padding: "5px" }}
                        >
                            <Image
                                src={LanguageIcon}
                                alt="Land and Development Office"
                                className="mt-1 mr-1"
                            />{" "}
                            {lang == "hindi" ? "हिंदी" : "English"}
                        </Popover.Button>

                        <Transition
                            as={Fragment}
                            enter="transition ease-out duration-200"
                            enterFrom="opacity-0 translate-y-1"
                            enterTo="opacity-100 translate-y-0"
                            leave="transition ease-in duration-150"
                            leaveFrom="opacity-100 translate-y-0"
                            leaveTo="opacity-0 translate-y-1"
                        >
                            <Popover.Panel className="absolute -left-8 top-full z-10 mt-3 w-max max-w-md overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                                <div className="p-4">
                                    <div className="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm leading-6 hover:bg-gray-50">
                                        <div className="flex-auto">
                                            <Link
                                                href="#"
                                                className="block font-semibold text-gray-900"
                                                onClick={() => {
                                                    setLang("english");
                                                }}
                                            >
                                                English
                                            </Link>
                                        </div>
                                    </div>
                                    <div className="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm leading-6 hover:bg-gray-50">
                                        <div className="flex-auto">
                                            <Link
                                                href="#"
                                                className="block font-semibold text-gray-900"
                                                onClick={() => {
                                                    setLang("hindi");
                                                }}
                                            >
                                                हिंदी
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </Popover.Panel>
                        </Transition>
                    </Popover> */}
                {/* Diwakar Sinha */}
                <div
                    className="relative"
                    onMouseEnter={() => setIsDropdownOpen(true)}
                    onMouseLeave={() => setIsDropdownOpen(false)}
                >
                    <div
                        className=""
                        style={{ display: "flex", padding: "5px" }}
                    >
                        <Image
                            src={LanguageIcon}
                            alt="Land and Development Office"
                            className="mt-1 mr-1"
                        />{" "}
                        {lang == "hindi" ? "हिंदी" : "English"}
                    </div>

                    {isDropdownOpen && (
                        <div className="absolute -left-8 top-full z-10 w-max max-w-md overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                            <div className="p-4">
                                <div className="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm leading-6 hover:bg-gray-50">
                                    <div className="flex-auto">
                                        <Link
                                            href="#"
                                            className="block font-semibold text-gray-900"
                                            onClick={() => {
                                                setLang("english");
                                                setIsDropdownOpen(false);
                                            }}
                                        >
                                            English
                                        </Link>
                                    </div>
                                </div>
                                <div className="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm leading-6 hover:bg-gray-50">
                                    <div className="flex-auto">
                                        <Link
                                            href="#"
                                            className="block font-semibold text-gray-900"
                                            onClick={() => {
                                                setLang("hindi");
                                                setIsDropdownOpen(false);
                                            }}
                                        >
                                            हिंदी
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
            <div className="navbar-menu">
                <Navigation />
            </div>
            {/* <ul className='fixed z-20 flex flex-col gap-[6.8rem] -right-[3.2rem] top-56'>
        <li className='uppercase text-center p-2 px-5 text-m commonBtns'><Link href={'/faq'}>{Translate.faq[lang]}</Link></li>
        <li className='uppercase text-center p-2 px-5 text-m commonBtns'><Link href={'tel:1800111705'}>{Translate.tollfree[lang]}</Link></li>
      </ul> */}

            {/* Swati 21-05-2024 to make entire button clickable */}

            <ul className="fixed z-20 flex flex-col gap-[6.8rem] -right-[3.2rem] top-80">
                <li className="commonBtns">
                    <Link
                        href="/faq"
                        className="uppercase text-center p-2 px-5 text-m block w-full h-full"
                    >
                        {Translate.faq[lang]}
                    </Link>
                </li>
                <li className="commonBtns">
                    <Link
                        href="tel:1800111705"
                        className="uppercase text-center p-2 px-5 text-m block w-full h-full"
                    >
                        {Translate.tollfree[lang]}
                    </Link>
                </li>
            </ul>
        </div>
    );
};

export default Navbar;
