import React, { useEffect, useState } from "react";
import AOS from "aos";
import Header from "./Header";
import WhatsAppButton from "./WhatsAppButton";
import { LanguageContext, language } from "../LanguagesContext";

function Layout({ children, pathname }) {
    const [lang] = useState(language.es);

    useEffect(() => {
        AOS.refreshHard();
    }, [pathname]);

    return (
        <LanguageContext.Provider value={lang}>
            <Header />
            <main className="relative min-h-screen">{children}</main>
            <WhatsAppButton />
        </LanguageContext.Provider>
    );
}

export default Layout;
