"use client";
import React, { useEffect, useState, useRef } from "react";
import ChatBot from "react-simple-chatbot";
import { ThemeProvider } from "styled-components";

const ChatBotComponent = () => {
    const [showTooltip, setShowTooltip] = useState(true);
    const [fadeTooltip, setFadeTooltip] = useState(false);
    const chatContentRef = useRef(null);

    // Developed By Diwakar Sinha -> 10-10-2024
    const scrollToLastMessage = () => {
        const content = chatContentRef.current;
        if (content) {
            const lastMessage = content.querySelector('.rsc-ts-bot:last-child, .rsc-ts-user:last-child, .rsc-os:last-child');
            if (lastMessage) {
                lastMessage.scrollIntoView({ behavior: 'smooth' });
            }
        }
    };

    // Developed By Diwakar Sinha -> 10-10-2024
    useEffect(() => {
        const chatContent = document.querySelector('.rsc-content');
        if (chatContent) {
            chatContentRef.current = chatContent;

            const observer = new MutationObserver(() => {
                scrollToLastMessage();
            });

            observer.observe(chatContent, { childList: true, subtree: true });

            return () => observer.disconnect();
        }
    }, []);
    // End

    useEffect(() => {
        setFadeTooltip(true);

        const hideTooltipTimer = setTimeout(() => {
            setFadeTooltip(false);
            setTimeout(() => setShowTooltip(false), 1200);
        }, 5000);

        return () => clearTimeout(hideTooltipTimer);
    }, []);

    // Comment By Diwakar Sinha -> 10-10-2024
    // const handleBotClick = () => {
    //     setFadeTooltip(false);
    //     setShowTooltip(false);
    // };

    useEffect(() => {
        const link = document.createElement("link");
        link.href =
            "https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap";
        link.rel = "stylesheet";
        document.head.appendChild(link);
    }, []);

    const theme = {
        background: "#ffffff",
        fontFamily: "Montserrat, sans-serif",
        headerBgColor: "#0f3557",
        headerFontColor: "#fff",
        headerFontSize: "15px",
        botBubbleColor: "#0f3557",
        botFontColor: "#fff",
        userBubbleColor: "#fff",
        userFontColor: "#4a4a4a"
    };

    const steps = [
        {
            id: "1",
            message:
                'Hello! My name is Bhoomi, your digital assistant. Welcome to eDharti 2.0 Portal. Type "Hi, Hello or Hey" to start the conversation!',
            trigger: "greet-response"
        },
        {
            id: "greet-response",
            user: true,
            trigger: value => {
                const userInput = String(value.value)
                    .toLowerCase()
                    .trim();
                return userInput === "hi" ||
                    userInput === "hey" ||
                    userInput === "hello"
                    ? "ask-name"
                    : "type-hi";
            }
        },
        {
            id: "ask-name",
            message: "Hi! May I know your name, please?",
            trigger: "user-name",
            delay: 1000
        },
        {
            id: "user-name",
            user: true,
            trigger: "help"
        },
        {
            id: "help",
            message:
                "Thanks! {previousValue}, Type 'Help' for getting help related options.",
            trigger: "check-help",
            delay: 1000
        },
        {
            id: "check-help",
            user: true,
            trigger: value => {
                const userInput = String(value.value)
                    .toLowerCase()
                    .trim();
                return userInput === "help" ? "provide-options" : "type-hi";
            }
        },
        {
            id: "provide-options",
            message: "Here are some options that may help you:",
            trigger: "display-options"
        },
        {
            id: "display-options",
            options: [
                { value: "2", label: "Visit eDharti", trigger: "redirect-property" },
                { value: "3", label: "Services", trigger: "trigger-services" },
                {
                    value: "5",
                    label: "Book an Appointment",
                    trigger: "redirect-support"
                },
                { value: "6", label: "About L&DO", trigger: "about-lndo" },
                { value: "7", label: "Contact Us", trigger: "contact-us" }
            ]
        },
        {
            id: "trigger-services",
            options: [
                {
                    value: "1",
                    label: "Substitution",
                    trigger: "redirect-property"
                },
                { value: "2", label: "Mutation", trigger: "display-options" },
                { value: "3", label: "Conversion", trigger: "display-options" },
                {
                    value: "4",
                    label: "Land Use Change",
                    trigger: "display-options"
                },
                { value: "5", label: "Lease of Deed", trigger: "display-options" },

            ]
        },
        {
            id: "redirect-property",
            message:
                "You Selected eDharti Portal option here is the direct link to open eDharti Portal.",
            trigger: "show-edharti-button"
        },
        {
            id: "show-edharti-button",
            component: (
                <div>
                    <button
                        id="btnedharti"
                        onClick={() =>
                            window.open("https://ldo.gov.in/edharti/", "_blank")
                        }
                        style={{
                            backgroundColor: "#3879b3",
                            color: "#ffffff",
                            border: "none",
                            padding: "7px 12px",
                            borderRadius: "5px",
                            cursor: "pointer",
                            marginTop: "10px"
                        }}
                    >
                        Open eDharti
                    </button>
                </div>
            ),
            asMessage: true,
            trigger: "display-restart"
        },
        {
            id: "redirect-payment",
            message:
                "You Selected Services option here is the direct link to open Services.",
            trigger: "show-services-button"
        },
        {
            id: "show-services-button",
            component: (
                <div>
                    <button
                        onClick={() =>
                            window.open(
                                "http://192.168.0.62:40/services",
                                "_blank"
                            )
                        }
                        style={{
                            backgroundColor: "#3879b3",
                            color: "#ffffff",
                            border: "none",
                            padding: "7px 12px",
                            borderRadius: "5px",
                            cursor: "pointer",
                            marginTop: "10px"
                        }}
                    >
                        Open Services
                    </button>
                </div>
            ),
            asMessage: true,
            trigger: "display-restart"
        },
        {
            id: "redirect-support",
            message:
                "You Selected Book an Appoitment option here is the direct link to open Appoitments.",
            trigger: "show-contact-button"
        },
        {
            id: "show-contact-button",
            component: (
                <div>
                    <button
                        onClick={() =>
                            window.open(
                                "http://192.168.0.62:40/contact-us",
                                "_blank"
                            )
                        }
                        style={{
                            backgroundColor: "#3879b3",
                            color: "#ffffff",
                            border: "none",
                            padding: "7px 12px",
                            borderRadius: "5px",
                            cursor: "pointer",
                            marginTop: "10px"
                        }}
                    >
                        Open Contact Us
                    </button>
                </div>
            ),
            asMessage: true,
            trigger: "display-restart"
        },
        {
            id: "about-lndo",
            message:
                "With the decision to form the Capital at Delhi, the Lieutenant Governor of Punjab in his notification, ordered the Collector of Delhi District to acquire land for the New Capital of India. After the land was acquired Imperial Delhi Estate was created vide Chief Commissioner, Delhi notification. The Land and Development work was then done by an Executive Engineer of PWD, known as Land and Development Officer, in the Chief Engineer office, under the control of the Secretary to the Chief Commissioner in the Public Works Department. The Land and Development Officer formally charged with the land record work and administration on behalf of Government of Raisina Estate. The work was transferred under the direct control of the Chief Commissioner, Delhi with effect from 1 March 1928. Thus in 1928, the office of the Land and Development Officer came into being as a separate Organization under the Administrative control of Commissioner., Delhi. Since independence, the activities of this office have gradually expanded. In 1958, the Chief Commissioner resumed Nazul lands under the management of the Notified Area Committee, Civil Section, Delhi and put them under the administrative control of the Land and Development Officer. Land and Development Officer was brought under the control of the then Ministry of Urban Development (presently Ministry of Housing and Urban Affairs) with effect from the 1 October 1959 and since then, it had been functioning as a subordinate office of this Ministry till it was upgraded from that of a subordinate office to an attached office of Ministry of Urban Development (presently Ministry of Housing and Urban Affairs) vide Gazette Notification dated 4 April 2000.",
            trigger: "for-more"
        },
        {
            id: "contact-us",
            message:
                "Please contact us with Nirman Bhawan Phone : 23022174, Email Address:ldo@nic.in.",
            trigger: "for-more"
        },
        {
            id: "type-hi",
            message: "Please type 'Hi' to Start the Conversation.",
            trigger: "greet-response"
        },
        {
            id: "for-more",
            message: "Please type 'More' to Chat again.",
            trigger: "p-options"
        },
        {
            id: "p-options",
            user: true,
            trigger: value => {
                const userInput = String(value.value)
                    .toLowerCase()
                    .trim();
                return userInput === "more"
                    ? "provide-options"
                    : "end-convo-wrong-input";
            }
        },
        {
            id: "end-conversation",
            message: "Thank you! Have a great day.",
            end: true
        },
        {
            id: "end-convo-wrong-input",
            message: "You Input Inappropriate Kindly Restart the Chat.",
            trigger: "display-restart"
        },
        {
            id: "display-restart",
            options: [
                { value: "1", label: "Restart", trigger: "provide-options" },
                { value: "2", label: "End Chat", trigger: "end-conversation" }
            ]
        }
    ];

    return (
        <ThemeProvider theme={theme}>
            <div style={{ position: "relative" }}>
                {showTooltip && (
                    <div
                        style={{
                            position: "fixed",
                            bottom: "98px",
                            right: "38px",
                            backgroundColor: "#0f3557",
                            color: "white",
                            padding: "10px 15px",
                            borderRadius: "15px",
                            fontSize: "14px",
                            zIndex: 1000,
                            boxShadow: "0 4px 8px rgba(0, 0, 0, 0.2)",
                            opacity: fadeTooltip ? 1 : 0,
                            transition: "opacity 1s ease-in"
                        }}
                    >
                        Hi, I&apos;m Bhoomi, How may I assist you?
                        <div
                            style={{
                                position: "absolute",
                                top: "100%",
                                right: "20px",
                                width: 0,
                                height: 0,
                                borderLeft: "8px solid transparent",
                                borderRight: "8px solid transparent",
                                borderTop: "8px solid #0f3557"
                            }}
                        />
                    </div>
                )}

                {/* <div onClick={handleBotClick}>  */}
                <div onClick={()=> setFadeTooltip(false)}> 
                    <ChatBot
                        steps={steps}
                        floating={true}
                        botDelay={1000}
                        headerTitle="Bhoomi"
                        speechSynthesis={{ enable: true, lang: 'en' }}
                        botAvatar="/usericon.png"
                        userAvatar="/usericon.png"
                    />
                </div>
            </div>
        </ThemeProvider>
    );
};

export default ChatBotComponent;
