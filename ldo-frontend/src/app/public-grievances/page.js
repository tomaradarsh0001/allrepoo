"use client";
import PageHeader from "@/components/PageHeader";
import {
    User,
    Phone,
    Mail,
    LandPlot,
    MapPin,
    PenLine,
    SendHorizonal,
} from "lucide-react";
import React, { useState, useContext } from "react";
import { LangContext } from "@/components/Container"; //added by Nitin
import Translate from "../../language.json";
import StatusAlert from "@/components/StatusAlert";

const Page = () => {
    const { lang } = useContext(LangContext);
    const [formData, setFormData] = useState({
        name: "",
        mobile_number: "",
        email: "",
        // property_id: "",
        // colony_name: "",
        address: "",
        description: "",
    });

    const [errors, setErrors] = useState({});

    // const [submissionMessage, setSubmissionMessage] = useState('');
    const [alert, setAlert] = useState({ type: "", message: "" });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });

        // Validate inputs on change
        validateInput(name, value);
    };

    const validateInput = (name, value) => {
        let error = "";
        if (name === "name") {
            const nameRegex = /^[a-zA-Z\s]+$/;
            if (!nameRegex.test(value)) {
                error = "Name can only contain letters and spaces";
            }
        } else if (name === "mobile_number") {
            const mobileRegex = /^[0-9]{10}$/;
            if (!mobileRegex.test(value)) {
                error = "Mobile number must be 10 digits";
            }
        } else if (name === "email") {
            const emailRegex =
                /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(value)) {
                error = "Invalid email address";
            }
        }
        // else if (name === "property_id") {
        //     const propertyIdRegex = /^[0-9]{1,5}$/;
        //     if (!propertyIdRegex.test(value)) {
        //         error = "Property ID must be a number up to 5 digits";
        //     }
        // } else if (name === "colony_name") {
        //     const colonyNameRegex = /^[a-zA-Z0-9\-\s]+$/;
        //     if (!colonyNameRegex.test(value)) {
        //         error =
        //             "Colony name can only contain letters, numbers, and hyphens";
        //     }
        // }
        else if (name === "address") {
            const addressRegex = /^[a-zA-Z0-9\/\-,&\s]+$/;
            if (!addressRegex.test(value)) {
                error =
                    "Address can only contain letters, numbers, /, -, , , and &";
            }
        } else if (name === "description") {
            if (value.length > 500) {
                error = "Description cannot exceed 500 characters";
            }
        }
        setErrors({
            ...errors,
            [name]: error,
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        let valid = true;
        for (const [key, value] of Object.entries(formData)) {
            validateInput(key, value);
            if (errors[key]) {
                valid = false;
            }
        }

        if (valid) {
            // console.log(formData);
            try {
                const response = await fetch(
                    "http://192.168.0.62:8080/api/public-grievances",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(formData),
                    }
                );

                const result = await response.json();
                console.log(result);

                setFormData({
                    name: "",
                    mobile_number: "",
                    email: "",
                    // property_id: "",
                    // colony_name: "",
                    address: "",
                    description: "",
                });
                setAlert({
                    type: "success",
                    message: "Form submitted successfully!",
                });
            } catch (error) {
                setAlert({
                    type: "error",
                    message: "Error submitting form. Please try again.",
                });
            }
        } else {
            setAlert({
                type: "error",
                message: "Please correct the errors in the form.",
            });
        }
    };

    const handleHideAlert = () => {
        setAlert({ type: "", message: "" });
    };

    return (
        <div className="contact-us">
            <PageHeader
                pageTitle={Translate.PublicGrievances[lang]}
                language={lang}
            />
            <div className="section-bg-1">
                <div className="whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10">
                    <div className="w-full px-4">
                        <div
                            className="title-group2 pb-2 lg:pb-5 mb-5"
                            data-aos="fade-up"
                            data-aos-duration="1000"
                        >
                            <h2 className="themeTitle text-2xl lg:text-4xl font-bold text-center">
                                {Translate.PublicGrievances[lang]}
                            </h2>
                        </div>
                    </div>
                </div>
                <div className="form_box_container mx-auto w-3/4 py-16 p-6 pb-32 rounded">
                    {/* {submissionMessage && <p className="text-center text-green-500 mt-4">{submissionMessage}</p>} */}
                    <StatusAlert
                        type={alert.type}
                        message={alert.message}
                        onHide={handleHideAlert}
                    />
                    <form onSubmit={handleSubmit}>
                        <div className="block lg:flex items-center w-full">
                            <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="name" className="block text-xl">
                                    {Translate.Name[lang]}
                                    <span className="text-red-600">*</span>
                                </label>
                                <div className="relative">
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value={formData.name}
                                        onChange={handleChange}
                                        className={`w-full px-3 pl-14 py-2 border ${
                                            errors.name
                                                ? "border-red-500"
                                                : "border-gray-300"
                                        } rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                        required
                                    />
                                    <span className="absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400">
                                        <User className="w-[18px] h-[18px]" />
                                    </span>
                                    {errors.name && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.name}
                                        </p>
                                    )}
                                </div>
                            </div>
                            <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label
                                    htmlFor="mobile_number"
                                    className="block text-xl"
                                >
                                    {Translate.MobileNumber[lang]}
                                    <span className="text-red-600">*</span>
                                </label>
                                <div className="relative">
                                    <input
                                        type="text"
                                        id="mobile_number"
                                        name="mobile_number"
                                        value={formData.mobile_number}
                                        onChange={handleChange}
                                        maxLength={10}
                                        className={`w-full px-3 pl-14 py-2 border ${
                                            errors.mobile_number
                                                ? "border-red-500"
                                                : "border-gray-300"
                                        } rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                        required
                                    />
                                    <span className="absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400">
                                        <Phone className="w-[18px] h-[18px]" />
                                    </span>
                                    {errors.mobile_number && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.mobile_number}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div>
                        <div className="block lg:flex items-center w-full">
                            <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label
                                    htmlFor="email"
                                    className="block text-xl"
                                >
                                    {Translate.email[lang]}
                                    <span className="text-red-600">*</span>
                                </label>
                                <div className="relative">
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        className={`w-full px-3 pl-14 py-2 border ${
                                            errors.email
                                                ? "border-red-500"
                                                : "border-gray-300"
                                        } rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                        required
                                    />
                                    <span className="absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400">
                                        <Mail className="w-[18px] h-[18px]" />{" "}
                                    </span>
                                    {errors.email && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.email}
                                        </p>
                                    )}
                                </div>
                            </div>
                            {/* <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label
                                    htmlFor="property_id"
                                    className="block text-xl"
                                >
                                    {Translate.PropertyId[lang]}
                                </label>
                                <div className="relative">
                                    <input
                                        type="text"
                                        id="property_id"
                                        name="property_id"
                                        value={formData.property_id}
                                        onChange={handleChange}
                                        className={`w-full px-3 pl-14 py-2 border ${
                                            errors.property_id
                                                ? "border-red-500"
                                                : "border-gray-300"
                                        } rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    />
                                    <span className="absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400">
                                        <LandPlot className="w-[18px] h-[18px]" />{" "}
                                    </span>
                                    {errors.property_id && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.property_id}
                                        </p>
                                    )}
                                </div>
                            </div> */}

                            <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label
                                    htmlFor="address"
                                    className="block text-xl"
                                >
                                    {Translate.Address[lang]}
                                    <span className="text-red-600">*</span>
                                </label>
                                <div className="relative">
                                    <input
                                        type="text"
                                        id="address"
                                        name="address"
                                        value={formData.address}
                                        onChange={handleChange}
                                        className={`w-full px-3 pl-14 py-2 border ${
                                            errors.address
                                                ? "border-red-500"
                                                : "border-gray-300"
                                        } rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                        required
                                    />
                                    <span className="absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400">
                                        <MapPin className="w-[18px] h-[18px]" />{" "}
                                    </span>
                                    {errors.address && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.address}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div>
                        {/* <div className="block lg:flex items-center w-full">
                            <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label
                                    htmlFor="colony_name"
                                    className="block text-xl"
                                >
                                    {Translate.ColonyName[lang]}
                                </label>
                                <div className="relative">
                                    <input
                                        type="text"
                                        id="colony_name"
                                        name="colony_name"
                                        value={formData.colony_name}
                                        onChange={handleChange}
                                        className={`w-full px-3 pl-14 py-2 border ${
                                            errors.colony_name
                                                ? "border-red-500"
                                                : "border-gray-300"
                                        } rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    />
                                    <span className="absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400">
                                        <PenLine className="w-[18px] h-[18px]" />{" "}
                                    </span>
                                    {errors.colony_name && (
                                        <p className="text-red-500 text-sm mt-1">
                                            {errors.colony_name}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div> */}
                        <div className="block w-full">
                            <div className="mx-4 mb-4">
                                <label
                                    htmlFor="description"
                                    className="block text-xl"
                                >
                                    {Translate.Description[lang]}
                                    <span className="text-red-600">*</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    value={formData.description}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${
                                        errors.description
                                            ? "border-red-500"
                                            : "border-gray-300"
                                    } rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                ></textarea>
                                {errors.description && (
                                    <p className="text-red-500 text-sm mt-1">
                                        {errors.description}
                                    </p>
                                )}
                            </div>
                        </div>
                        <div className="mx-4 mb-4 text-center">
                            <button
                                type="submit"
                                className="apply-btn text-sm md:text-lg px-5 py-2 w-2/5 mx-auto"
                            >
                                {Translate.Send[lang]}{" "}
                                <SendHorizonal className="inline w-[18px] h-[18px] -mt-[2px]" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default Page;
