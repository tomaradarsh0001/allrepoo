import React from "react";
import citiesIcon from "../../public/facts/faMountainCity.svg";
import propertiesIndiaIcon from "../../public/facts/faPropertiesIndia.svg";
import propertiesDelhiIcon from "../../public/facts/faPropertiesDelhi.svg";
import residentialPropertiesIcon from "../../public/facts/faPropertiesResidential.svg";
import commercialPropertiesIcon from "../../public/facts/faPropertiesCommercial.svg";
import institutionalIcon from "../../public/facts/faInstitutional.svg";
import industrialIcon from "../../public/facts/faIndustrial.svg";
import governmentIcon from "../../public/facts/faGovernment.svg";
import foreignMissionsIcon from "../../public/facts/faForeignMissions.svg";
import visitorsCountIcon from "../../public/facts/faVisitorsCount.svg";
import Image from "next/image";
import CountUp from "react-countup";
import { useContext, useEffect, useState } from "react";
import { LangContext } from "./Container";
import Translate from "@/language.json";

const DashboardFacts = () => {
    const { lang } = useContext(LangContext);

    const factsData = [
        // Commented by Swati 01-06-2024 as these two tiles are not required and commented the countup part with static data for now.
        // { factsIcon: citiesIcon, factsTitle: 'No. of Cities', factsValue: <CountUp
        // className=""
        // start={0}
        // end={49870}
        // duration={5}
        // useEasing={true}
        // separator=","
        // enableScrollSpy={true}/> },
        // { factsIcon: propertiesIndiaIcon, factsTitle: 'No. of Properties in India', factsValue: <CountUp
        // className=""
        // start={0}
        // end={98508}
        // duration={5}
        // useEasing={true}
        // separator=","
        // enableScrollSpy={true}/> },
        {
            factsIcon: propertiesDelhiIcon,
            factsTitle: Translate.NoOfPropertiesInDelhi[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={62786}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
        {
            factsIcon: residentialPropertiesIcon,
            factsTitle: Translate.NoOfResidentialProperties[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={55134}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
        {
            factsIcon: commercialPropertiesIcon,
            factsTitle: Translate.CommercialProperties[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={5913}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
        {
            factsIcon: institutionalIcon,
            factsTitle: Translate.Institutional[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={1112}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
        {
            factsIcon: industrialIcon,
            factsTitle: Translate.Industrial[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={121}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
        {
            factsIcon: governmentIcon,
            factsTitle: Translate.Government[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={420}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
        {
            factsIcon: foreignMissionsIcon,
            factsTitle: Translate.ForeignMissions[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={76}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
        {
            factsIcon: visitorsCountIcon,
            factsTitle: Translate.VisitorsCount[lang],
            factsValue: (
                <CountUp
                    className=""
                    start={0}
                    end={548695}
                    duration={5}
                    useEasing={true}
                    separator=","
                    enableScrollSpy={true}
                />
            ),
        },
    ];
    return (
        <div className="dashboard-facts-container px-4 md:px-4 py-10 lg:px-8 xl:py-20 xl:px-10 2xl:px-24 2xl:py-24">
            <div className="facts-row grid grid-cols-2 gap-2 xl::grid-cols-4 xl:gap-6 lg:grid-cols-4 lg:gap-4 md:grid-cols-3 md:gap-3">
                {factsData.map((item, index) => {
                    return (
                        <div
                            key={index}
                            className="facts-card text-center w-full"
                            data-aos="fade-up"
                        >
                            <div className="facts-icon text-center">
                                <Image
                                    src={item.factsIcon}
                                    alt={item.factsTitle}
                                    className="w-auto h-auto m-auto"
                                />
                            </div>
                            <div className="facts-value my-2">
                                <h3 className="text-base 2xl:text-3xl font-bold">
                                    {item.factsValue}
                                </h3>
                            </div>
                            <div className="facts-title">
                                <p className="text-base text-white mb-0">
                                    {item.factsTitle}
                                </p>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
};

export default DashboardFacts;
