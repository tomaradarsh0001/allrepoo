import React from 'react'
import Link from 'next/link'
import { ChevronRightIcon } from '@heroicons/react/24/outline'

const BtnLink = (props) => {
  return (
    <Link href={props.href} className="readmore-btn animation-btn text-sm md:text-lg px-4" target={props.target}>{props.text}
    <ChevronRightIcon className='ml-2 w-4 md:w-5' /></Link>
  )
}

export default BtnLink;