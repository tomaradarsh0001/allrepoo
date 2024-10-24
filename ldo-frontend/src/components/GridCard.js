import Image from "next/image";
import Link from "next/link";
import React from "react";

function GridCard({ item, isMedia }) {
    let itemImage = isMedia ? item.attachment : item.image;
    return (
        <div className="grid-card grid-items relative rounded-[10px] overflow-hidden">
            <Link href={item.attachment} className="block" target="_blank">
                <Image
                    src={itemImage}
                    alt={item.title}
                    className="w-full"
                    width={200}
                    height={200}
                />
                <h3 className="text-sm text-center p-2">{item.title}</h3>
            </Link>
            <span className="badge absolute text-sm top-3 right-3 rounded-md">
                {item.category}
            </span>
        </div>
    );
}

export default GridCard;
