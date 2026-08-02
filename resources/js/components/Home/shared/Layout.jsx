import React from "react";
import Header from "./Header";
import WhatsAppButton from "./WhatsAppButton";
import { LanguageContext, language } from "../LanguagesContext";

function Layout(props) {
    const [lang] = React.useState(language.es);

    return (
        <LanguageContext.Provider value={lang}>
            <Header />
            <main className="relative min-h-screen">{props.children}</main>
            <WhatsAppButton />
        </LanguageContext.Provider>
    );
}

export default Layout;
