import React, { useEffect, useState, useContext } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/effect-coverflow";
import "swiper/css/pagination";
import "swiper/css/navigation";

import {
    Autoplay,
    EffectCoverflow,
    Pagination,
    Navigation,
} from "swiper/modules";

import { HOST_NAME, API_HOST } from "../constants"; //added by Nitin
import { LangContext } from "./Container"; //added by Nitin
import Image from "next/image";
import { ArrowLeftCircle, ArrowRightCircle } from "lucide-react";
const SlickSlider = () => {
    const { lang } = useContext(LangContext);
    const [componentContent, setComponentContent] = useState("");

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(
                    API_HOST + "componentData/Our Prominent Lessees/" + lang
                );
                const result = await response.json();
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
        <div>
            <div className="whatwedo px-4 md:px-6 pt-16 pb-16 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10">
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
                <div className="">
                    <Swiper
                        effect={"coverflow"}
                        grabCursor={true}
                        centeredSlides={true}
                        loop={true}
                        slidesPerView={"3"}
                        autoplay={{
                            delay: 1500,
                            disableOnInteraction: false,
                        }}
                        coverflowEffect={{
                            rotate: 10,
                            stretch: 5,
                            depth: 10,
                            modifier: 2.5,
                        }}
                        pagination={{
                            el: ".swiper-pagination",
                            clickable: true,
                        }}
                        breakpoints={{
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 40,
                            },
                            1024: {
                                slidesPerView: 4,
                                spaceBetween: 50,
                            },
                        }}
                        navigation={{
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                            clickable: true,
                        }}
                        modules={[
                            Autoplay,
                            EffectCoverflow,
                            Pagination,
                            Navigation,
                        ]}
                        className="swiper_container"
                    >
                        {componentContent.sections.map((section, index) => (
                            <SwiperSlide key={index}>
                                <Image
                                    src={HOST_NAME + section.image}
                                    alt="Prominent Lessee"
                                    width={80}
                                    height={50}
                                />
                                <div className="text_visible_swiper">
                                    <h4>{section.title}</h4>
                                </div>
                            </SwiperSlide>
                        ))}
                        <div className="slider-controler">
                            <div className="swiper-button-prev slider-arrow">
                                <ArrowLeftCircle />
                            </div>
                            <div className="swiper-button-next slider-arrow">
                                <ArrowRightCircle />
                            </div>
                            <div className="swiper-pagination"></div>
                        </div>
                    </Swiper>
                </div>
            </div>
        </div>
    );
};

export default SlickSlider;
