"use client";
import React, { createContext, useState } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import Cookies from "js-cookie";
import ChatBot from "@/components/ChatBot";

export const LangContext = createContext();
const Container = ({ children }) => {
    const [lang, setLang] = useState(Cookies.get("language") || "english");

    const changeLanguage = newLang => {
        setLang(newLang);
        Cookies.set("language", newLang);
    };
    return (
        <LangContext.Provider value={{ lang, setLang: changeLanguage }}>
            <Navbar />
            {children}
            <ChatBot />

            <Footer />
        </LangContext.Provider>
    );
};
export default Container;
