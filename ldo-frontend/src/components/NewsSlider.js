"use client";
import React, { useEffect, useState, useContext } from "react";
import leftIcon from "../../public/faCircleArrowLeft.svg";
import pauseIcon from "../../public/Pause-icon.svg";
import playIcon from "../../public/Play-icon.svg";
import rightIcon from "../../public/faCircleArrowRight.svg";
import Link from "next/link";
import Image from "next/image";
import ReadMoreBtn from "./ReadMoreBtn";
import { HOST_NAME, API_HOST } from "../constants"; //added by Nitin
import { LangContext } from "@/components/Container"; //added by
import Translate from "../language.json";

const NewsSlider = () => {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [isPlaying, setIsPlaying] = useState(true);
    const [newsCount, setNewsCount] = useState(7);
    const { lang } = useContext(LangContext);

    const nextSlide = () => {
        if (newsCount > 0) {
            setCurrentIndex((prevIndex) => (prevIndex + 1) % newsCount);
        }
    };

    const prevSlide = () => {
        if (newsCount > 0) {
            setCurrentIndex(
                (prevIndex) => (prevIndex - 1 + newsCount) % newsCount
            );
        }
    };

    const togglePlayPause = () => {
        setIsPlaying((prevIsPlaying) => !prevIsPlaying);
    };

    const [news, setNews] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(API_HOST + "headerNews/" + lang);
                const result = await response.json();
                setNews(result);
                setNewsCount(result.length);
            } catch (err) {
                console.error("Error Fetching News Data!", err);
            }
        };
        fetchData();
    }, [lang]);

    useEffect(() => {
        let interval;
        if (isPlaying) {
            interval = setTimeout(nextSlide, 2000);
        }
        return () => clearInterval(interval);
    }, [currentIndex, isPlaying]);
    return (
        <div
            className="news-container md:flex md:items-center md:justify-between p-2 lg:px-8"
            data-aos="zoom-out-up"
            data-aos-duration="1000"
        >
            <div className="keyUpdate w-full md:w-[108px] border-b-2 md:border-b-0 md:border-r-2">
                <p className="font-semibold text-center md:text-left text-base mb-0">
                    {Translate.keyUpdate[lang]}
                </p>
            </div>
            <div className="news-data mt-2 md:mt-0 md:w-[calc(100%-108px)]">
                <div className="news-cont flex items-center overflow-hidden w-[calc(100%-209px)] sm:w-[calc(100%-252px)] md:w-[calc(100%-300px)] lg:w-[calc(100%-262px)]">
                    <div
                        style={{
                            display: `flex`,
                            width: `calc(${newsCount * 50}% + ${
                                newsCount * 30
                            }px) `,
                        }}
                    >
                        {news.map((text, index) => (
                            <Link
                                key={index}
                                href={`/whats-news/${text.id}`}
                                className="news_datalist"
                                style={{
                                    transform: `translateX(-${
                                        currentIndex * 50
                                    }%)`,
                                }}
                            >
                                {text.title}
                            </Link>
                        ))}
                    </div>
                </div>
                <div className="news-controller flex items-center justify-end sm:w-[252px] md:w-[300px] lg:w-[262px] border-l-2">
                    <div className="controller-group flex md:gap-2 md:mr-[10px]">
                        <button onClick={prevSlide}>
                            <Image
                                src={leftIcon}
                                alt="Left Icon"
                                className="w-5 h-5 md:w-6 md:h-6"
                            />
                        </button>
                        <button onClick={togglePlayPause}>
                            {isPlaying ? (
                                <Image
                                    src={pauseIcon}
                                    alt="Pause Icon"
                                    className="w-5 h-5 md:w-6 md:h-6"
                                />
                            ) : (
                                <Image
                                    src={playIcon}
                                    alt="Play Icon"
                                    className="w-5 h-5 md:w-6 md:h-6"
                                />
                            )}{" "}
                        </button>
                        <button onClick={nextSlide}>
                            <Image
                                src={rightIcon}
                                alt="Right Icon"
                                className="w-5 h-5 md:w-6 md:h-6"
                            />
                        </button>
                    </div>
                    <ReadMoreBtn link="/whats-news" language={lang} />
                </div>
            </div>
        </div>
    );
};

export default NewsSlider;
