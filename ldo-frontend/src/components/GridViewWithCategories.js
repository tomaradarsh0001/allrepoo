import React, { useState } from "react";
import GridCard from "./GridCard";
// import CategoryButton from './CategoryButton';
import pdFIcon from "../../public/pdf_icon.svg";

function GridViewWithCategories({ columns, data, language, isMedia }) {
    const [selectedCategory, setSelectedCategory] = useState("All");

    const handleCategorySelect = (category) => {
        setSelectedCategory(category);
    };

    const filteredData =
        selectedCategory === "All"
            ? data
            : data.filter((item) => item.category === selectedCategory);
    return (
        <div data-aos="fade-up" data-aos-duration="1000">
            {/* <div className="category-buttons flex justify-end items-center">
        <CategoryButton
          category="All"
          onClick={handleCategorySelect}
          isActive={selectedCategory === 'All'}
        />
        <CategoryButton
          category="Video Tutorial"
          onClick={handleCategorySelect}
          isActive={selectedCategory === 'Video Tutorial'}
        />
        <CategoryButton
          category="Manual"
          onClick={handleCategorySelect}
          isActive={selectedCategory === 'Manual'}
        />
        {/* Add more category buttons as needed *}
      </div> */}
            <div
                className="grid-container grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6 2xl:grid-cols-7 2xl:gap-7 xl:grid-cols-6 xl:gap-6 lg:grid-cols-5 lg:gap-4 md:grid-cols-3 md:gap-5"
                style={{ marginBottom: 100 }}
            >
                {filteredData.map((item) => (
                    <GridCard key={item.id} item={item} isMedia={isMedia} />
                ))}
            </div>
        </div>
    );
}

export default GridViewWithCategories;
