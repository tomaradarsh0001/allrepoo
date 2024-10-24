import React, { useState, useEffect, useContext } from "react";
import Image from "next/image";
import { HOST_NAME, API_HOST } from "../constants"; //added by Nitin
import { LangContext } from "./Container"; //added by Nitin

const EmbassyContainer = () => {
    const { lang } = useContext(LangContext);
    const [componentContent, setComponentContent] = useState("");
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(
                    API_HOST + "componentData/Embassy/" + lang
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
        <div className="introduction-container bg-slate-50 px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-10 2xl:pb-24">
            <div className="w-full md:w-2/4 lg:w-3/4 m-auto px-4">
                <div className="title-group2 pb-2 lg:pb-5 mb-5">
                    <h2 className="themeTitle text-2xl md:text-3xl lg:text-4xl font-bold text-center">
                        {componentContent.heading}
                    </h2>
                </div>
            </div>
            <div className="block lg:flex items-center w-full">
                <div className="w-full lg:w-2/6 px-4">
                    <div className="relative">
                        <div className="mb-5">
                            <Image
                                src={
                                    HOST_NAME +
                                    componentContent.sections[0].image
                                }
                                alt="Embassy Collage"
                                className="w-full"
                                width={150}
                                height={150}
                            />
                        </div>
                    </div>
                </div>
                <div className="w-full lg:w-2/3 px-4 md:mt-2 md:mb-20 lg:mt-10">
                    <div className="para-border">
                        <p>{componentContent.sections[0].content}</p>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default EmbassyContainer;
