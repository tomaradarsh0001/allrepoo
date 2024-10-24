import React from 'react'
import Link from 'next/link'
import { ChevronRightIcon } from '@heroicons/react/24/outline'
import Translate from '../language.json'

const ReadMoreBtn = (props) => {
  let language = props.language ? props.language:"english";
  return (
    <Link href={props.link} className="readmore-btn animation-btn text-sm md:text-lg px-4">{Translate.readMore[language]}
    <ChevronRightIcon className='ml-2 w-4 md:w-5' /></Link>
  )
}

export default ReadMoreBtn