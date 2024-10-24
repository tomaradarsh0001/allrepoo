import React, { useContext } from "react";
import { LangContext } from "./Container";
import Translate from "@/language.json";

const Hero = () => {
    const { lang } = useContext(LangContext);

    return (
        <div className="hero" data-aos="zoom-out-down" data-aos-duration="1000">
            <div className="item item-1">
                <div className="content">
                    <h3 className="text-base md:text-2xl lg:text-3xl">
                        <span> {Translate.SliderTextHeading1[lang]}</span>{" "}
                        {/* {Translate.LdoSliderTitle2[lang]} */}
                    </h3>
                </div>
            </div>
            <div className="item item-1">
                <div className="content">
                    <h3 className="text-base md:text-2xl lg:text-3xl">
                        <span>{Translate.SliderTextHeading2[lang]}</span>{" "}
                        {/* {Translate.LdoSliderTitle2[lang]} */}
                    </h3>
                </div>
            </div>
            <div className="item item-1">
                <div className="content">
                    <h3 className="text-base md:text-2xl lg:text-3xl">
                        <span>{Translate.SliderTextHeading3[lang]}</span>{" "}
                        {/* {Translate.LdoSliderTitle2[lang]} */}
                    </h3>
                </div>
            </div>
            <div className="item item-1">
                <div className="content">
                    <h3 className="text-base md:text-2xl lg:text-3xl">
                        <span>{Translate.SliderTextHeading4[lang]}</span>{" "}
                        {/* {Translate.LdoSliderTitle2[lang]} */}
                    </h3>
                </div>
            </div>
            <div className="item item-1">
                <div className="content">
                    <h3 className="text-base md:text-2xl lg:text-3xl">
                        <span>{Translate.SliderTextHeading5[lang]}</span>{" "}
                        {/* {Translate.LdoSliderTitle2[lang]} */}
                    </h3>
                </div>
            </div>
        </div>
    );
};

export default Hero;
