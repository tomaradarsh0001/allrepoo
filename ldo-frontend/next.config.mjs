/** @type {import('next').NextConfig} */
const nextConfig = {
    images: {
        domains: ["192.168.0.62"], // Add the hostname(s) of your images here
        unoptimized: true
    },
    async rewrites() {
        return [
            {
                source: "/api/:path*",
                destination: "http://192.168.0.62:8080/api/:path*"
            }
        ];
    },
    compiler: {
        styledComponents: {
            cssProp: true
        }
    },
    reactStrictMode: false
};

export default nextConfig;
