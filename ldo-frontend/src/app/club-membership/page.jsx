'use client'
import PageHeader from '@/components/PageHeader'
import CommonTable from '@/components/CommonTable'
import { ArrowBigRight, Check } from 'lucide-react'
import Link from 'next/link'
import React, { useState } from 'react'

const Page = () => {
  const [visibleDiv, setVisibleDiv] = useState('div1');
  const [childVisibleDiv, setChildVisibleDiv] = useState('IHC');

  const toggleVisibility = (div) => {
    setVisibleDiv((prevDiv) => (prevDiv === div ? null : div));
  };
  const childToggleVisibility = (div) => {
    setChildVisibleDiv((prevDiv) => (prevDiv === div ? null : div));
  };


  const [formData, setFormData] = useState({
    name: '',
    mobileNumber: '',
    designation: '',
    service: '',
    allotyear: '',
    doj: '',
    doct: '',
    supperannuationDate: '',
    officeAddress: '',
    telephoneNo: '',
    payScale: '',
    indMem: '',
    tenureMem: '',
    nameofPrevMembers: '',
    anyOtherReleInfo: '',
  });

  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
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

  const handleIHCSubmit = (e) => {
    e.preventDefault();
    let valid = true;
    for (const [key, value] of Object.entries(formData)) {
      validateInput(key, value);
      if (errors[key]) {
        valid = false;
      }
    }

    if (valid) {
      console.log(formData);
    } else {
      console.log('Form has errors:', errors);
    }
  };

  const handleDGCSubmit = (e) => {
    e.preventDefault();
    let valid = true;
    for (const [key, value] of Object.entries(formData)) {
      validateInput(key, value);
      if (errors[key]) {
        valid = false;
      }
    }

    if (valid) {
      console.log(formData);
    } else {
      console.log('Form has errors:', errors);
    }
  };
  const columns = [
    { Header: "#", accessor: (row, index) => index + 1 },
    { Header: "Title", accessor: "title" },
    { Header: "Updated Date", accessor: "date" },
    { Header: "Action", accessor: "action" },
];

const tableData = []
  return (
    <div className="contact-us">
      <PageHeader pageTitle={'Club Membership'} />
      <div className='section-bg-1'>
        <div className='whatwedo px-4 md:px-6 pt-10 lg:px-8 xl:pt-20 xl:px-10 2xl:px-24 2xl:pt-10'>
          <div className='w-full px-4'>
            <div className='title-group2 pb-2 lg:pb-5 mb-5' data-aos="fade-up" data-aos-duration="1000">
              <h2 className='themeTitle text-2xl lg:text-4xl font-bold text-center'>Club Membership</h2>
            </div>
            <div className="block md:flex items-start gap-2 pb-40">
              <div className="w-full md:w-1/5">
                <div className="membership-btns">
                  <ul className="btns-list">
                    <li><button onClick={() => toggleVisibility('div1')} className={`club_btn block w-full rounded-md px-3.5 py-2.5 text-base font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 mb-2 ${visibleDiv === 'div1' ? 'active_border' : ''}`}>Apply Online {visibleDiv === 'div1' ? <Check className='inline text-white' /> : ''}</button></li>
                    <li><button onClick={() => toggleVisibility('div2')} className={`club_btn block w-full rounded-md px-3.5 py-2.5 text-base font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 mb-2 ${visibleDiv === 'div2' ? 'active_border' : ''}`}>Already Applied {visibleDiv === 'div2' ? <Check className='inline text-white' /> : ''}</button></li>
                    <li><button onClick={() => toggleVisibility('div3')} className={`club_btn block w-full rounded-md px-3.5 py-2.5 text-base font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 mb-2 ${visibleDiv === 'div3' ? 'active_border' : ''}`}>Existing Members {visibleDiv === 'div3' ? <Check className='inline text-white' /> : ''}</button></li>
                    <li><button onClick={() => toggleVisibility('div4')} className={`club_btn block w-full rounded-md px-3.5 py-2.5 text-base font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 ${visibleDiv === 'div4' ? 'active_border' : ''}`}>Waiting List Members {visibleDiv === 'div4' ? <Check className='inline text-white' /> : ''}</button></li>
                  </ul>
                </div>
              </div>
              {visibleDiv === 'div1' && (
                <div className="membership_container w-full md:w-4/5 p-5 bg-white">
                <div style={{ padding: '10px', }}>
                  <div className="btns-list flex items-start justify-center w-full">
                   <button onClick={() => childToggleVisibility('IHC')} className={`childClub_btn block w-full rounded-md px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2  ${childVisibleDiv === 'IHC' ? 'child_active_border' : ''}`}>Apply For India Habitat Centre (IHC)</button>
                   <button onClick={() => childToggleVisibility('DGC')} className={`childClub_btn block w-full rounded-md px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2  ${childVisibleDiv === 'DGC' ? 'child_active_border' : ''}`}>Apply For Delhi Golf Club (DGC)</button>
                  </div>
                  {childVisibleDiv === 'IHC' && (
                    <div style={{ padding: '10px', }}>
                      <div className="form_box_container membership_forms mt-5">
                        <h2 class="themeTitle text-xl lg:text-2xl font-bold text-center mb-9">Apply For India Habitat Centre (IHC)</h2>
                        <div className="form_container">
                          <form onSubmit={handleIHCSubmit}>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="name" className="block text-base">Name<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="name" className="block text-base">Designation<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <select
                                    id="Designation"
                                    name="designation"
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  >
                                    <option value={formData.designation}>Designation 1</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="service" className="block text-base">Name Of Service<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="service"
                                    name="service"
                                    value={formData.service}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="allotyear" className="block text-base">Allotment Year<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="allotyear"
                                    name="allotyear"
                                    value={formData.allotyear}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="doj" className="block text-base">Date Of Joining On Central Deputation In Delhi<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="doj"
                                    name="doj"
                                    value={formData.doj}
                                    onChange={handleChange}
                                    maxLength={10}
                                    className={`w-full px-3 py-2 border ${errors.doj ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />

                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="doct" className="block text-base">Expected Date Of Completion Of Tenure<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="doct"
                                    name="doct"
                                    value={formData.doct}
                                    onChange={handleChange}
                                    maxLength={10}
                                    className={`w-full px-3 py-2 border ${errors.doct ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />

                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="supperannuationDate" className="block text-base">Date Of Superannuation<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="date"
                                    id="supperannuationDate"
                                    name="supperannuationDate"
                                    value={formData.supperannuationDate}
                                    onChange={handleChange}
                                    maxLength={10}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />

                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="officeAddress" className="block text-base">Office Address<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="officeAddress"
                                    name="officeAddress"
                                    value={formData.officeAddress}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.officeAddress ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="telephoneNo" className="block text-base">Telephone No.<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="telephoneNo"
                                    name="telephoneNo"
                                    value={formData.telephoneNo}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.telephoneNo ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="telephoneNo" className="block text-base">Pay Scale (Pay Band & Grade Pay)<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="payScale"
                                    name="payScale"
                                    value={formData.payScale}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.payScale ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="indMem" className="block text-base">Whether Applied For Individual Membership In Ihc? If So, Date/ Relevant Details Thereof<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="date"
                                    id="indMem"
                                    name="indMem"
                                    value={formData.indMem}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="tenureMem" className="block text-base">Whether Tenure Member Of Delhi Golf Club<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="tenureMem"
                                    name="tenureMem"
                                    value={formData.tenureMem}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>


                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="nameofPrevMembers" className="block text-base">Name Of Present/previous Memberships Of Other Clubs</label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="nameofPrevMembers"
                                    name="nameofPrevMembers"
                                    value={formData.nameofPrevMembers}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                  />

                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="anyOtherReleInfo" className="block text-base">Any Other Relevant Information You May Like To Furnish</label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="anyOtherReleInfo"
                                    name="anyOtherReleInfo"
                                    value={formData.anyOtherReleInfo}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                  />
                                </div>
                              </div>
                            </div>

                            <div className='mx-4 mb-4 text-center'>
                              <button type="submit" className="apply-btn text-sm md:text-lg px-5 py-2 w-2/5 mx-auto">Submit</button>
                            </div>
                          </form>
                        </div>

                      </div>
                    </div>
                  )}
                  {childVisibleDiv === 'DGC' && (
                    <div style={{ padding: '10px', }}>
                      <div className="form_box_container membership_forms mt-5">
                        <h2 class="themeTitle text-xl lg:text-2xl font-bold text-center mb-9">Apply For Delhi Golf Club (DGC)</h2>
                        <div className="form_container">
                          <form onSubmit={handleDGCSubmit}>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="name" className="block text-base">Name<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="name" className="block text-base">Designation<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <select
                                    id="Designation"
                                    name="designation"
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  >
                                    <option value={formData.designation}>Designation</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="service" className="block text-base">Name Of Service<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="service"
                                    name="service"
                                    value={formData.service}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="allotyear" className="block text-base">Allotment Year<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="allotyear"
                                    name="allotyear"
                                    value={formData.allotyear}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="doj" className="block text-base">Date Of Joining On Central Deputation In Delhi<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="doj"
                                    name="doj"
                                    value={formData.doj}
                                    onChange={handleChange}
                                    maxLength={10}
                                    className={`w-full px-3 py-2 border ${errors.doj ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />

                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="doct" className="block text-base">Expected Date Of Completion Of Tenure<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="doct"
                                    name="doct"
                                    value={formData.doct}
                                    onChange={handleChange}
                                    maxLength={10}
                                    className={`w-full px-3 py-2 border ${errors.doct ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />

                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="supperannuationDate" className="block text-base">Date Of Superannuation<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="date"
                                    id="supperannuationDate"
                                    name="supperannuationDate"
                                    value={formData.supperannuationDate}
                                    onChange={handleChange}
                                    maxLength={10}
                                    className={`w-full px-3 py-2 border ${errors.name ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />

                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="officeAddress" className="block text-base">Office Address<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="officeAddress"
                                    name="officeAddress"
                                    value={formData.officeAddress}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.officeAddress ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="telephoneNo" className="block text-base">Telephone No.<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="telephoneNo"
                                    name="telephoneNo"
                                    value={formData.telephoneNo}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.telephoneNo ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="telephoneNo" className="block text-base">(Please indicate Pay Scale and Grade Pay)<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="payScale"
                                    name="payScale"
                                    value={formData.payScale}
                                    onChange={handleChange}
                                    className={`w-full px-3 py-2 border ${errors.payScale ? 'border-red-500' : 'border-gray-300'} rounded focus:outline-none focus:border-indigo-500 bg-transparent`}
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="regMem" className="block text-base">Whether applied to DGC for regular membership? If so, date/relevant details thereof.<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="date"
                                    id="regMem"
                                    name="regMem"
                                    value={formData.regMem}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="tenureMem" className="block text-base">Have you been a tenure member of Delhi Golf Club earlier. If so, indicate the period.<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="tenureMem"
                                    name="tenureMem"
                                    value={formData.tenureMem}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="indMem" className="block text-base">Current Handicap in Golf (Along with certification, if any).<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="date"
                                    id="indMem"
                                    name="indMem"
                                    value={formData.indMem}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                    required
                                  />
                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="tenureMem" className="block text-base">Whether you are tenure member of India Habitat Centre? If so, indicate the date of nomination.<span className='text-red-600'>*</span></label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="tenureMem"
                                    name="tenureMem"
                                    value={formData.tenureMem}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                    required
                                  />
                                </div>
                              </div>
                            </div>
                            <div className='block lg:flex items-center w-full'>


                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="nameofPrevMembers" className="block text-base">Name Of Present/previous Memberships Of Other Clubs</label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="nameofPrevMembers"
                                    name="nameofPrevMembers"
                                    value={formData.nameofPrevMembers}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                  />

                                </div>
                              </div>
                              <div className="w-full lg:w-2/4 px-4 mb-4">
                                <label htmlFor="anyOtherReleInfo" className="block text-base">Any Other Relevant Information You May Like To Furnish</label>
                                <div className='relative'>
                                  <input
                                    type="text"
                                    id="anyOtherReleInfo"
                                    name="anyOtherReleInfo"
                                    value={formData.anyOtherReleInfo}
                                    onChange={handleChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-indigo-500 bg-transparent"
                                  />
                                </div>
                              </div>
                            </div>

                            <div className='mx-4 mb-4 text-center'>
                              <button type="submit" className="apply-btn text-sm md:text-lg px-5 py-2 w-2/5 mx-auto">Submit</button>
                            </div>
                          </form>
                        </div>

                      </div>
                    </div>
                  )}
                </div>
                </div>
              )}
              {visibleDiv === 'div2' && (
                <div className="membership_container w-full md:w-4/5 p-5 bg-white">
                  <div style={{ marginTop: '10px', padding: '10px', }}>
                  <CommonTable columns={columns} data={tableData} />
                  </div>
                </div>
              )}
              {visibleDiv === 'div3' && (
                <div className="membership_container w-full md:w-4/5 p-5 bg-white">
                <div style={{ marginTop: '10px', padding: '10px', }}>
                  <CommonTable columns={columns} data={tableData} />
                </div>
                </div>
              )}
              {visibleDiv === 'div4' && (
                <div className="membership_container w-full md:w-4/5 p-5 bg-white">
                <div style={{ marginTop: '10px', padding: '10px', }}>
                  <CommonTable columns={columns} data={tableData} />
                </div>
                </div>
              )}
            </div>
          </div>
        </div>

      </div>
    </div>
  )
}

export default Page