// export const getNews = async() => {
//     const res = await fetch('https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=47a979f67068413baa6699a3c40f2c74', {
//         cache: "no-cache",
//     });
//     if(!res.ok) {
//         throw new Error("Failed to get News!")
//     }
//     return res.json();
// }