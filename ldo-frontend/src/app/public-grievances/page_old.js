'use client'
import PageHeader from '@/components/PageHeader';
import { User, Phone, Mail, LandPlot, MapPin, PenLine, SendHorizonal } from 'lucide-react';
import React, {useState, useContext } from "react";
import { LangContext } from "@/components/Container"; //added by Nitin
import Translate from "../../language.json";


const Page = () => {
  const { lang } = useContext(LangContext);
  const [formData, setFormData] = useState({
    name: '',
    mobileNumber: '',
    email: '',
    propertyId: '',
    colornyName: '',
    address: '',
    description: '',
  });

  const [errors, setErrors] = useState({});

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
    let error = '';
    if (name === 'name') {
      const nameRegex = /^[a-zA-Z\s]+$/;
      if (!nameRegex.test(value)) {
        error = 'Name can only contain letters and spaces';
      }
    } else if (name === 'mobileNumber') {
      const mobileRegex = /^[0-9]{10}$/;
      if (!mobileRegex.test(value)) {
        error = 'Mobile number must be 10 digits';
      }
    }
    setErrors({
      ...errors,
      [name]: error,
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Validate all inputs before submitting
    let valid = true;
    for (const [key, value] of Object.entries(formData)) {
      validateInput(key, value);
      if (errors[key]) {
        valid = false;
      }
    }

    if (valid) {
      // Handle form submission logic (e.g., sending data to an API)
      console.log(formData);
    } else {
      console.log('Form has errors:', errors);
    }
  };

  return (
    <div className="contact-us">
      <PageHeader pageTitle={Translate.PublicGrievances[lang]} language={lang} />
      <div className='section-bg-1'>
        <div className='whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10'>
          <div className='w-full px-4'>
            <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
              <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>{Translate.PublicGrievances[lang]}</h2>
            </div>
          </div>
        </div>
        <div className="form_box_container mx-auto w-3/4 py-16 p-6 pb-32 rounded">
          <form onSubmit={handleSubmit}>
            <div className='block lg:flex items-center w-full'>
              <div className="w-full lg:w-2/4 px-4 mb-4">
                <label htmlFor="name" className="block text-xl">{Translate.Name[lang]}<span className='text-red-600'>*</span></label>
                <div className='relative'>
                  <input
                    type="text"
                    id="name"
                    name="name"
                    value={formData.name}
                    onChange={handleChange}
                    className={`w-full px-3 pl-14 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                    required
                  />
                  <span className='absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400'><User className='w-[18px] h-[18px]' /></span>
                </div>
              </div>
              <div className="w-full lg:w-2/4 px-4 mb-4">
                <label htmlFor="mobileNumber" className="block text-xl">{Translate.MobileNumber[lang]}<span className='text-red-600'>*</span></label>
                <div className='relative'>
                  <input
                    type="text"
                    id="mobileNumber"
                    name="mobileNumber"
                    value={formData.mobileNumber}
                    onChange={handleChange}
                    maxLength={10}
                    className={`w-full px-3 pl-14 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                    required
                  />
                  <span className='absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400'><Phone className='w-[18px] h-[18px]' /></span>
                </div>
              </div>
            </div>
            <div className='block lg:flex items-center w-full'>
              <div className="w-full lg:w-2/4 px-4 mb-4">
                <label htmlFor="email" className="block text-xl">{Translate.email[lang]}<span className='text-red-600'>*</span></label>
                <div className='relative'>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    className="w-full px-3 pl-14 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                    required
                  />
                  <span className='absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400'><Mail className='w-[18px] h-[18px]' /> </span>
                </div>
              </div>
              <div className="w-full lg:w-2/4 px-4 mb-4">
                <label htmlFor="propertyId" className="block text-xl">{Translate.PropertyId[lang]}</label>
                <div className='relative'>
                  <input
                    type="text"
                    id="propertyId"
                    name="propertyId"
                    value={formData.propertyId}
                    onChange={handleChange}
                    className="w-full px-3 pl-14 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                  />
                  <span className='absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400'><LandPlot className='w-[18px] h-[18px]' /> </span>
                </div>
              </div>
            </div>
            <div className='block lg:flex items-center w-full'>
              <div className="w-full lg:w-2/4 px-4 mb-4">
                <label htmlFor="colornyName" className="block text-xl">{Translate.ColonyName[lang]}</label>
                <div className='relative'>
                  <input
                    type="text"
                    id="colornyName"
                    name="colornyName"
                    value={formData.colornyName}
                    onChange={handleChange}
                    className="w-full px-3 pl-14 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                  />
                  <span className='absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400'><PenLine className='w-[18px] h-[18px]' /> </span>
                </div>
              </div>
              <div className="w-full lg:w-2/4 px-4 mb-4">
                <label htmlFor="address" className="block text-xl">{Translate.Address[lang]}<span className='text-red-600'>*</span></label>
                <div className='relative'>
                  <input
                    type="text"
                    id="address"
                    name="address"
                    value={formData.address}
                    onChange={handleChange}
                    className="w-full px-3 pl-14 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                    required
                  />
                  <span className='absolute top-1/2 -translate-y-1/2 left-1 border-r border-slate-400 px-2 text-slate-400'><MapPin className='w-[18px] h-[18px]' /> </span>
                </div>
              </div>
            </div>
            <div className='block w-full'>
              <div className="mx-4 mb-4">
                <label htmlFor="description" className="block text-xl">{Translate.Description[lang]}<span className='text-red-600'>*</span></label>
                <textarea
                  id="description"
                  name="description"
                  value={formData.description}
                  onChange={handleChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                  required
                ></textarea>
              </div>
            </div>
            <div className='mx-4 mb-4 text-center'>
              <button type="submit" className="apply-btn text-sm md:text-lg px-5 py-2 w-2/5 mx-auto">{Translate.Send[lang]} <SendHorizonal className='inline w-[18px] h-[18px] -mt-[2px]' /></button>
            </div>
          </form>
        </div>
      </div>
    </div>

  );
};

export default Page;