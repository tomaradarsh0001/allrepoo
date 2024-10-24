"use client";
import React, { useEffect } from "react";
import { getLocation } from "current-location-geo";
import Hero from "@/components/Hero";
import DashboardFacts from "@/components/DashboardFacts";
import Introduction from "@/components/Introduction";
import WhatWeDo from "@/components/WhatWeDo";
import PortalGroup from "@/components/PortalGroup";
import NewsSlider from "@/components/NewsSlider";
import SlickSlider from "@/components/SlickSlider";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import EmbassyContainer from "@/components/EmbassyContainer";

export default function Home() {
    useEffect(() => {
        getLocation(function (err, position) {
            if (err) {
            } else {
            }
        });
    }, []);
    return (
        <div className="Homepage">
            <NewsSlider />
            <Hero />
            <PortalGroup />
            <DashboardFacts />
            <Introduction />
            {/* Diwakar Sinha */}
            <SlickSlider />
            <WhatWeDo />
            {/* Diwakar Sinha */}
            <EmbassyContainer />
        </div>
    );
}
