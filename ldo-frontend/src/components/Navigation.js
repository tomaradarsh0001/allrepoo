"use client";
import { Fragment, useState, useEffect, useContext } from "react";
import { Dialog, Disclosure, Popover, Transition } from "@headlessui/react";
import { Bars3Icon, XMarkIcon } from "@heroicons/react/24/outline";
import {
    ChevronDownIcon,
    PhoneIcon,
    PlayCircleIcon,
} from "@heroicons/react/20/solid";
import Link from "next/link";
import LogoIcon from "../../public/Logo.png";
import HomeIcon from "../../public/faHome.svg";
import UserLoginIcon from "../../public/User Login.svg";
import Image from "next/image";

import { LangContext } from "./Container"; // Added by Nitin
import { HOST_NAME, API_HOST } from "../constants"; // Added by Nitin
import HindiLogoIcon from "../../public/logoldo_hin.png";

const about = [
    { name: "History & Mandate", href: "/history-and-mandate" },
    { name: "Citizen Charter", href: "/citizen-charter" },
    { name: "Who's Who", href: "/whos-who" },
    { name: "Resource Library", href: "/resource-library" },
];
const actsRules = [
    { name: "Acts and Rules", href: "/acts-and-rules" },
    { name: "Policy Orders & Guidelines", href: "#" },
    { name: "Circulars", href: "#" },
    { name: "List of Eligible Offices", href: "#" },
];
const eformsPublic = [
    { name: "Conversion", href: "#" },
    { name: "Substitution", href: "#" },
    { name: "Mutation", href: "#" },
    { name: "Sale Permission", href: "#" },
    { name: "Mortgage Permission", href: "#" },
    { name: "Gift Permission", href: "#" },
];
const eformsOffice = [
    { name: "Administration", href: "#" },
    { name: "Stores", href: "#" },
];
const eservices = [
    { name: "Payment", href: "#" },
    { name: "Application", href: "#" },
    { name: "Applicant Registration", href: "#" },
    { name: "Update NEFT/RTGS Status", href: "#" },
    { name: "Payment Help", href: "#" },
];
const noc = [
    { name: "NOCs from June 2023", href: "#" },
    { name: "NOCs Prior to June 2023", href: "#" },
    { name: "NOCs till Dec 2022", href: "#" },
    { name: "NOC Public Notice", href: "#" },
];
const authList = [
    { name: "Log In", href: "#" },
    { name: "Sign Up", href: "#" },
];
const callsToAction = [
    { name: "FAQ", href: "#", icon: PlayCircleIcon },
    { name: "Please Call Us @", href: "#", icon: PhoneIcon },
];

function classNames(...classes) {
    return classes.filter(Boolean).join(" ");
}

export default function Example() {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const { lang } = useContext(LangContext);
    const [headerMenus, setHeaderMenus] = useState([]);
    const [openMenuIndex, setOpenMenuIndex] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(API_HOST + "headerMenu/" + lang);
                const result = await response.json();
                if (result.code == 200) {
                    setHeaderMenus(result.data);
                }
            } catch (err) {
                console.error("Error Fetching content!", err);
            }
        };
        fetchData();
    }, [lang]);

    return (
        <header className="bg-white">
            <nav
                className="mx-auto flex max-w-full items-center justify-between p-4 lg:px-8"
                aria-label="Global"
            >
                <div className="flex lg:flex-1">
                    <Link href={"/"} className="-m-1.5 p-1.5">
                        <span className="sr-only">
                            Land and Development Office
                        </span>
                        <Image
                            className="w-[320px] 2xl:w-[380px]"
                            width={300}
                            src={lang == "hindi" ? HindiLogoIcon : LogoIcon}
                            alt="Land and Development Office"
                        />
                    </Link>
                </div>

                <div className="flex items-center gap-6">
                    <div className="flex lg:hidden">
                        <button
                            type="button"
                            className="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                            onClick={() => setMobileMenuOpen(true)}
                        >
                            <span className="sr-only">Open main menu</span>
                            <Bars3Icon className="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                    <Popover.Group className="hidden lg:flex lg:gap-x-4 xl:gap-x-6">
                        {headerMenus.map((menu, index) => {
                            let menuDisplay =
                                menu.sort_order == 0 ? (
                                    <Image
                                        src={HomeIcon}
                                        alt="Home"
                                        className="w-[22px] xl:w-[23px] homeIcon"
                                    />
                                ) : (
                                    menu.name
                                );
                            if (menu.submenus) {
                                return (
                                    <Popover key={index} className="relative">
                                        {({ open }) => (
                                            <div
                                                onMouseEnter={() =>
                                                    setOpenMenuIndex(index)
                                                }
                                                onMouseLeave={() =>
                                                    setOpenMenuIndex(null)
                                                }
                                            >
                                                <Popover.Button className="flex items-center gap-x-1 text-sm md:text-base lg:text-lg font-semibold leading-6 text-gray-900 outline-0">
                                                    {menuDisplay}
                                                    <ChevronDownIcon
                                                        className="h-5 w-5 flex-none text-gray-400"
                                                        aria-hidden="true"
                                                    />
                                                </Popover.Button>

                                                <Transition
                                                    as={Fragment}
                                                    show={
                                                        openMenuIndex === index
                                                    }
                                                    enter="transition ease-out duration-200"
                                                    enterFrom="opacity-0 translate-y-1"
                                                    enterTo="opacity-100 translate-y-0"
                                                    leave="transition ease-in duration-150"
                                                    leaveFrom="opacity-100 translate-y-0"
                                                    leaveTo="opacity-0 translate-y-1"
                                                >
                                                    <Popover.Panel className="absolute -left-8 top-full z-10 mt-3 w-max max-w-md overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-900/5">
                                                        <div className="p-4">
                                                            {menu.submenus.map(
                                                                (item) => (
                                                                    <div
                                                                        key={
                                                                            item.name
                                                                        }
                                                                        className="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm leading-6 hover:bg-gray-50"
                                                                    >
                                                                        <div className="flex-auto">
                                                                            <Link
                                                                                href={
                                                                                    item.link !=
                                                                                    undefined
                                                                                        ? "/" +
                                                                                          item.link
                                                                                        : "#"
                                                                                }
                                                                                className="block font-semibold text-gray-900"
                                                                                target={
                                                                                    item.new_tab ==
                                                                                    "1"
                                                                                        ? "_blank"
                                                                                        : ""
                                                                                }
                                                                            >
                                                                                {
                                                                                    item.name
                                                                                }
                                                                                <span className="absolute inset-0" />
                                                                            </Link>
                                                                        </div>
                                                                    </div>
                                                                )
                                                            )}
                                                        </div>
                                                    </Popover.Panel>
                                                </Transition>
                                            </div>
                                        )}
                                    </Popover>
                                );
                            } else {
                                return (
                                    <Link
                                        key={index}
                                        href={
                                            menu.link.length > 0
                                                ? menu.link
                                                : "#"
                                        }
                                        className="text-sm md:text-base lg:text-lg font-semibold leading-6 text-gray-900"
                                        target={
                                            menu.new_tab == "1" ? "_blank" : ""
                                        }
                                    >
                                        {menuDisplay}
                                    </Link>
                                );
                            }
                        })}
                    </Popover.Group>
                </div>
            </nav>
            <Dialog
                as="div"
                className="lg:hidden"
                open={mobileMenuOpen}
                onClose={setMobileMenuOpen}
            >
                <div className="fixed inset-0 z-10" />
                <Dialog.Panel className="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div className="flex items-center justify-between">
                        <Link href={"/"} className="-m-1.5 p-1.5">
                            <span className="sr-only">Your Company</span>
                            <Image
                                className="h-8 w-auto"
                                src={LogoIcon}
                                alt=""
                            />
                        </Link>
                        <button
                            type="button"
                            className="-m-2.5 rounded-md p-2.5 text-gray-700"
                            onClick={() => setMobileMenuOpen(false)}
                        >
                            <span className="sr-only">Close menu</span>
                            <XMarkIcon className="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                    <div className="mt-6 flow-root">
                        <div className="-my-6 divide-y divide-gray-500/10">
                            <div className="space-y-2 py-6">
                                {headerMenus.map((menu, index) => {
                                    let menuDisplay =
                                        menu.sort_order == 0 ? (
                                            <Image
                                                src={HomeIcon}
                                                alt="Home"
                                                className="w-[22px] xl:w-[23px] homeIcon"
                                            />
                                        ) : (
                                            menu.name
                                        );
                                    if (menu.submenus) {
                                        return (
                                            <Disclosure
                                                as="div"
                                                key={index}
                                                className="-mx-3"
                                            >
                                                {({ open }) => (
                                                    <>
                                                        <Disclosure.Button className="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 hover:bg-gray-400/10">
                                                            {menuDisplay}
                                                            <ChevronDownIcon
                                                                className={classNames(
                                                                    open
                                                                        ? "rotate-180"
                                                                        : "",
                                                                    "h-5 w-5 flex-none"
                                                                )}
                                                                aria-hidden="true"
                                                            />
                                                        </Disclosure.Button>
                                                        <Disclosure.Panel className="mt-2 space-y-2">
                                                            {menu.submenus.map(
                                                                (item) => (
                                                                    <Disclosure.Button
                                                                        key={
                                                                            item.name
                                                                        }
                                                                        as="a"
                                                                        href={
                                                                            item.link !=
                                                                            undefined
                                                                                ? "/" +
                                                                                  item.link
                                                                                : "#"
                                                                        }
                                                                        className="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 hover:bg-gray-400/10"
                                                                        target={
                                                                            item.new_tab ==
                                                                            "1"
                                                                                ? "_blank"
                                                                                : ""
                                                                        }
                                                                    >
                                                                        {
                                                                            item.name
                                                                        }
                                                                    </Disclosure.Button>
                                                                )
                                                            )}
                                                        </Disclosure.Panel>
                                                    </>
                                                )}
                                            </Disclosure>
                                        );
                                    } else {
                                        return (
                                            <Link
                                                key={index}
                                                href={
                                                    menu.link.length > 0
                                                        ? menu.link
                                                        : "#"
                                                }
                                                className="text-base font-semibold leading-7 text-gray-900"
                                                target={
                                                    menu.new_tab == "1"
                                                        ? "_blank"
                                                        : ""
                                                }
                                            >
                                                {menuDisplay}
                                            </Link>
                                        );
                                    }
                                })}
                            </div>
                            <div className="py-6">
                                <Link
                                    href="#"
                                    className="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-6 text-gray-900 hover:bg-gray-400/10"
                                >
                                    Log in
                                </Link>
                            </div>
                        </div>
                    </div>
                </Dialog.Panel>
            </Dialog>
        </header>
    );
}
