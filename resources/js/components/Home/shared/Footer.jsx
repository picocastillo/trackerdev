import React from "react";
import { WHATSAPP_DISPLAY, WHATSAPP_URL, WhatsAppLink } from "./WhatsAppButton";

const socialLinks = [
    {
        href: "https://www.facebook.com/trackerdev",
        icon: "fab fa-facebook-f",
        label: "Facebook",
        className: "hover:border-[#3b5998] hover:text-[#3b5998]",
    },
    {
        href: "https://www.linkedin.com/in/trackerdev-solutions",
        icon: "fab fa-linkedin-in",
        label: "LinkedIn",
        className: "hover:border-[#0e76a8] hover:text-[#0e76a8]",
    },
    {
        href: "https://www.instagram.com/trackerdev/",
        icon: "fab fa-instagram",
        label: "Instagram",
        className: "hover:border-[#fd1d1d] hover:text-[#fd1d1d]",
    },
    {
        href: WHATSAPP_URL,
        icon: "fab fa-whatsapp",
        label: "WhatsApp",
        className: "hover:border-[#25d366] hover:text-[#25d366]",
    },
];

const Footer = () => (
    <section
        id="contacto"
        className="relative mt-0 border-t border-white/10 bg-gradient-to-b from-brand-dark via-[#120f0f] to-black"
    >
        <div className="mx-auto max-w-7xl px-4 py-16 md:px-6 md:py-20">
            <div className="mx-auto max-w-2xl text-center">
                <div
                    data-aos="zoom-in"
                    className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-md bg-[#25d366]/15 text-[#25d366]"
                >
                    <i
                        className="fab fa-whatsapp text-3xl"
                        aria-hidden="true"
                    />
                </div>
                <h2
                    data-aos="fade-up"
                    data-aos-delay="80"
                    className="font-display text-3xl font-bold text-white md:text-4xl"
                >
                    ¿Tenés una idea? <span aria-hidden="true">💡</span>
                </h2>
                <p
                    data-aos="fade-up"
                    data-aos-delay="140"
                    className="mt-3 text-white/70"
                >
                    Escribinos por WhatsApp y te ayudamos a convertirla en un
                    producto web o móvil a medida.
                </p>
                <p
                    data-aos="fade-up"
                    data-aos-delay="170"
                    className="mt-3 inline-flex items-center justify-center gap-2 text-sm text-white/50"
                >
                    <i
                        className="fas fa-location-dot text-primary-light"
                        aria-hidden="true"
                    />
                    Santa Fe, Argentina
                </p>
                <div data-aos="fade-up" data-aos-delay="200" className="mt-6">
                    <WhatsAppLink label="Chateá con nosotros" />
                    <p className="mt-3 text-xs text-white/45">
                        <i
                            className="fab fa-whatsapp mr-1"
                            aria-hidden="true"
                        />
                        Respuesta inmediatas cualquier dia/horario.
                       
                    </p>
                </div>
                <div
                    data-aos="fade-up"
                    data-aos-delay="260"
                    className="mt-10 flex flex-wrap justify-center gap-3"
                >
                    {socialLinks.map((link) => (
                        <a
                            key={link.label}
                            href={link.href}
                            target="_blank"
                            rel="noreferrer"
                            aria-label={link.label}
                            className={`inline-flex h-12 w-12 items-center justify-center rounded-md border border-white/15 text-white/80 transition duration-300 hover:-translate-y-0.5 ${link.className}`}
                        >
                            <i
                                className={`${link.icon} text-xl`}
                                aria-hidden="true"
                            />
                        </a>
                    ))}
                </div>
            </div>

            <div className="mt-14 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 text-center text-sm text-white/60 sm:flex-row sm:text-left">
                <a
                    href="https://trackerdev.com.ar"
                    target="_blank"
                    rel="noreferrer"
                    className="inline-flex items-center gap-2 text-white/80 hover:text-white"
                >
                    <picture>
                        <source
                            srcSet="/images/icon_td.webp"
                            type="image/webp"
                        />
                        <img
                            src="/images/icon_td.png"
                            alt="TrackerDev"
                            width="32"
                            height="32"
                            className="h-8 w-8 rounded-md object-cover"
                            loading="lazy"
                        />
                    </picture>
                    <span className="font-display font-semibold">
                        TrackerDev
                    </span>
                </a>
                <p>
                    © {new Date().getFullYear()} Todos los derechos reservados
                </p>
            </div>
        </div>
    </section>
);

export default Footer;
