'use client'
import React, { useState, useEffect } from "react";

const page = () => {
  // const [scrollPosition, setScrollPosition] = useState(0);
  // useEffect(() => {
  //   const interval = setInterval(() => {
  //     setScrollPosition(scrollPosition - 1);
  //   }, 10);

  //   return () => clearInterval(interval);
  // }, [scrollPosition]);
  return (
    <div style={{ overflow: "scroll", width: "100%", height: "auto" }}>
      <div style={{ position: "absolute", left: scrollPosition, top: 0 }}>
        This is the content that will scroll. This is the content that will scroll. This is the content that will scroll. This is the content that will scroll.
      </div>
    </div>
  )
}

export default page