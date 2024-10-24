import React from 'react';

function CategoryButton({ category, onClick, isActive }) {
    return (
        <button
            className={`category-button ${isActive ? 'active' : ''}`}
            onClick={() => onClick(category)}
        >
            {category}
        </button>
    );
}

export default CategoryButton;