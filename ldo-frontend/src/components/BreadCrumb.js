import Image from 'next/image'
import Link from 'next/link'
import React from 'react'
import { ChevronRightIcon } from '@heroicons/react/20/solid'
import Translate from '../language.json'
const BreadCrumb = (props) => {
  let language = props.language ? props.language:"english";
    return (<nav className="flex py-3 text-white" aria-label="Breadcrumb" data-aos="fade-right" data-aos-duration="1000">
            <ol className="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li className="inline-flex items-center">
                    <Link href="/" className="inline-flex items-center text-sm font-medium text-white hover:text-zinc-50 dark:hover:text-white">
                        <svg className="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        {Translate.home[language]}
                    </Link>
                </li>
                <li aria-current="page">
                    <div className="flex items-center">
                        <ChevronRightIcon width={20} />
                        <span className="ms-1 text-sm font-medium text-white md:ms-2">{props.pageTitle}</span>
                    </div>
                </li>
            </ol>
        </nav>
  )
}

export default BreadCrumb