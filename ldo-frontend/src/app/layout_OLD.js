import { Montserrat } from "next/font/google";
import "./globals.css";
import "./style.css";
import Container from "@/components/Container";
const montserrat = Montserrat({ subsets: ["latin"] })

export const metadata = {
  title: "Land and Development Office | Ministry of Home Affairs",
  description: "With the decision to form the Capital at Delhi, the Lieutenant Governor of Punjab in his notification, ordered the Collector of Delhi District to acquire land for the New Capital of India. After the land was acquired Imperial Delhi Estate was created vide Chief Commissioner, Delhi notification.",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body className={montserrat.className}>
        <Container>{children}</Container>
      </body>
    </html>
  );
}
