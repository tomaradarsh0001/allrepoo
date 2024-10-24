import React from "react";
import GridViewWithCategories from "./GridViewWithCategories";

function GridView({ columns, data, language, isMedia }) {
    return (
        <GridViewWithCategories
            data={data}
            language={language}
            isMedia={isMedia}
        />
    );
}

export default GridView;
