import React, { useEffect, useState } from "react";
import { LanguageContext } from "../LanguagesContext";

const navLinks = [
    { href: "/#servicios", label: "Servicios" },
    { href: "/#metodologia", label: "Metodología" },
    { href: "/#proyectos", label: "Proyectos" },
    { href: "/#contacto", label: "Contacto" },
];

function Header() {
    const [isScrolled, setIsScrolled] = useState(false);
    const [isOpen, setIsOpen] = useState(false);

    useEffect(() => {
        const onScroll = () => setIsScrolled(window.scrollY > 24);
        onScroll();
        window.addEventListener("scroll", onScroll, { passive: true });
        return () => window.removeEventListener("scroll", onScroll);
    }, []);

    return (
        <header
            className={`fixed inset-x-0 top-0 z-50 transition-colors duration-300 ${
                isScrolled
                    ? "bg-brand-dark/90 shadow-lg shadow-black/20 backdrop-blur-md"
                    : "bg-transparent"
            }`}
        >
            <nav className="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 md:px-6">
                <a
                    href="/"
                    className="group flex items-center gap-2 text-white"
                >
                    <picture>
                        <source
                            srcSet="/images/icon_td.webp"
                            type="image/webp"
                        />
                        <img
                            src="/images/icon_td.png"
                            alt="TrackerDev"
                            width="36"
                            height="36"
                            className="h-9 w-9 rounded-md object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                    </picture>
                    {/* <span className="font-display text-lg font-bold tracking-wide md:text-xl">
                        TrackerDev
                    </span> */}
                </a>

                <div className="hidden items-center gap-8 md:flex">
                    <ul className="flex items-center gap-6">
                        {navLinks.map((link) => (
                            <li key={link.href}>
                                <a
                                    href={link.href}
                                    className="font-display text-sm font-medium text-white/80 transition hover:text-white"
                                >
                                    {link.label}
                                </a>
                            </li>
                        ))}
                    </ul>
                    <LanguageContext.Consumer>
                        {({ menu }) => (
                            <a
                                href="/login"
                                className="btn-primary btn-sm !rounded-md"
                            >
                                {menu.login}
                            </a>
                        )}
                    </LanguageContext.Consumer>
                </div>

                <button
                    type="button"
                    className="inline-flex items-center justify-center rounded-md border border-white/20 p-2 text-white md:hidden"
                    aria-label="Abrir menú"
                    aria-expanded={isOpen}
                    onClick={() => setIsOpen((open) => !open)}
                >
                    <i
                        className={`fas ${isOpen ? "fa-times" : "fa-bars"} text-lg`}
                        aria-hidden="true"
                    />
                </button>
            </nav>

            {isOpen && (
                <div className="border-t border-white/10 bg-brand-dark/95 px-4 py-4 backdrop-blur-md md:hidden">
                    <ul className="flex flex-col gap-3">
                        {navLinks.map((link) => (
                            <li key={link.href}>
                                <a
                                    href={link.href}
                                    className="block font-display text-base text-white/90"
                                    onClick={() => setIsOpen(false)}
                                >
                                    {link.label}
                                </a>
                            </li>
                        ))}
                        <li className="pt-2">
                            <LanguageContext.Consumer>
                                {({ menu }) => (
                                    <a
                                        href="/login"
                                        className="btn-primary w-full"
                                    >
                                        {menu.login}
                                    </a>
                                )}
                            </LanguageContext.Consumer>
                        </li>
                    </ul>
                </div>
            )}
        </header>
    );
}

export default Header;
