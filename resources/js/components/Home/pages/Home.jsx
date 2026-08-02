import React from "react";
import Main from "../shared/Main";
import { LanguageContext } from "../LanguagesContext";
import Methodology from "./Methodology";
import Projects from "./Projects";
import ParticleComponent from "../shared/ParticleComponent";
import Footer from "../shared/Footer";
import { WhatsAppLink } from "../shared/WhatsAppButton";

const services = [
    {
        title: "Sistemas web",
        icon: "fas fa-laptop-code",
        description:
            "Plataformas a medida para operar tu negocio: paneles, gestión interna y flujos pensados para el día a día.",
    },
    {
        title: "ERP y CRM",
        icon: "fas fa-chart-line",
        description:
            "Sistemas de gestión empresarial: stock, ventas, finanzas, clientes y procesos a medida para tu operación.",
    },
    {
        title: "Diseño UX / UI",
        icon: "fas fa-pen-ruler",
        description:
            "Prototipos navegables y una identidad visual clara para que tu producto se entienda desde el primer clic.",
    },
    {
        title: "Apps móviles",
        icon: "fas fa-mobile-screen-button",
        description:
            "Aplicaciones para Android e iOS conectadas a un backend central, fáciles de usar y de mantener.",
    },
    {
        title: "Landing pages",
        icon: "fas fa-rocket",
        description:
            "Páginas enfocadas en conversión: presentar tu oferta, captar leads y facilitar el primer contacto.",
    },
    {
        title: "Hardware e IoT",
        icon: "fas fa-microchip",
        description:
            "Productos con Raspberry Pi y todo tipo de hardware: sensores, automatización, dispositivos conectados y prototipos electrónicos.",
    },
];

function Home() {
    return (
        <Main>
            <section className="relative flex min-h-screen items-center overflow-hidden">
                <ParticleComponent />
                <div
                    className="pointer-events-none absolute inset-x-0 bottom-0 z-[1] h-40 bg-gradient-to-t from-brand-dark to-transparent"
                    aria-hidden="true"
                />

                <div className="relative z-10 mx-auto w-full max-w-7xl px-4 pb-20 pt-28 md:px-6 md:pb-24 md:pt-32">
                    <div className="grid grid-cols-1 items-center gap-10 md:grid-cols-2 md:gap-12">
                        <div className="max-w-xl">
                            <LanguageContext.Consumer>
                                {({ home }) => (
                                    <>
                                        <p className="marketing-fade-up inline-flex items-center gap-2 font-display text-sm font-medium uppercase tracking-[0.25em] text-primary-light md:text-base">
                                            <span aria-hidden="true">✨</span>
                                            Materializamos tu idea
                                        </p>
                                        <h1 className="marketing-fade-right marketing-delay-1 mt-4 font-display text-4xl font-bold leading-tight text-white md:text-6xl">
                                            {home._1}{" "}
                                            <span className="text-primary-light">
                                                {home._2.replace(/\.$/, "")}
                                            </span>
                                        </h1>
                                        <p className="marketing-fade-up marketing-delay-2 mt-5 text-base text-white/75 md:text-lg">
                                            {home.lead}
                                        </p>
                                    </>
                                )}
                            </LanguageContext.Consumer>
                            <div className="marketing-fade-up marketing-delay-3 mt-8 flex flex-wrap gap-3">
                                <a
                                    href="#contacto"
                                    className="btn-primary inline-flex items-center gap-2 transition hover:scale-[1.03]"
                                >
                                    <i
                                        className="fas fa-file-invoice-dollar"
                                        aria-hidden="true"
                                    />
                                    Pedí tu presupuesto sin cargo
                                </a>
                                <WhatsAppLink variant="compact" />
                                <a
                                    href="#proyectos"
                                    className="inline-flex items-center justify-center gap-2 rounded-md border border-white/30 px-4 py-2 text-sm font-semibold text-white transition hover:scale-[1.03] hover:border-white hover:bg-white/10"
                                >
                                    <i
                                        className="fas fa-images"
                                        aria-hidden="true"
                                    />
                                    Experiencias
                                </a>
                            </div>
                            <ul className="marketing-fade-up marketing-delay-3 mt-6 flex list-none flex-wrap gap-x-5 gap-y-2 p-0 text-sm text-white/55">
                                <li className="inline-flex items-center gap-2">
                                    <i
                                        className="fas fa-calendar-check text-primary-light"
                                        aria-hidden="true"
                                    />
                                    Desde 2018
                                </li>
                                <li className="inline-flex items-center gap-2">
                                    <i
                                        className="fas fa-location-dot text-primary-light"
                                        aria-hidden="true"
                                    />
                                    Santa Fe, Argentina
                                </li>
                                <li className="inline-flex items-center gap-2">
                                    <i
                                        className="fas fa-code text-primary-light"
                                        aria-hidden="true"
                                    />
                                    Web, mobile e IoT
                                </li>
                            </ul>
                        </div>
                        <div className="flex justify-center md:justify-end">
                            <img
                                src="/images/td_white.png"
                                alt="TrackerDev - desarrollo de software"
                                width="667"
                                height="206"
                                className="marketing-logo-reveal h-auto w-full max-w-xs object-contain md:max-w-md lg:max-w-lg"
                                fetchPriority="high"
                            />
                        </div>
                    </div>
                </div>

                <a
                    href="#servicios"
                    className="group absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 flex-col items-center gap-2 text-white/55 transition-colors hover:text-white focus-visible:text-white focus-visible:outline-none md:bottom-8"
                    aria-label="Ver más servicios"
                >
                    <span className="font-display text-[0.7rem] font-medium uppercase tracking-[0.28em]">
                        Ver más
                    </span>
                    <span
                        className="marketing-scroll-cue flex h-9 w-9 items-center justify-center rounded-full border border-white/25 bg-white/5 transition-[border-color,background-color] group-hover:border-white/50 group-hover:bg-white/10 group-focus-visible:border-white/50 group-focus-visible:bg-white/10"
                        aria-hidden="true"
                    >
                        <i className="fas fa-chevron-down text-sm" />
                    </span>
                </a>
            </section>

            <section
                id="servicios"
                className="relative bg-gradient-to-b from-brand-dark via-[#1f1515] to-brand-dark py-20 md:py-28"
            >
                <div className="mx-auto max-w-7xl px-4 md:px-6">
                    <p
                        data-aos="fade-up"
                        className="text-center text-3xl"
                        aria-hidden="true"
                    >
                        🛠️
                    </p>
                    <h2
                        data-aos="fade-up"
                        data-aos-duration="900"
                        className="mt-2 font-display text-center text-3xl font-bold text-white md:text-4xl"
                    >
                        Qué podemos construir juntos
                    </h2>
                    <p
                        data-aos="fade-up"
                        data-aos-delay="120"
                        className="mx-auto mt-3 max-w-2xl text-center text-white/65"
                    >
                        Desde la idea hasta el producto en producción: web, ERP,
                        CRM, hardware e IoT, con foco en claridad, calidad y
                        resultados.
                    </p>

                    <div className="mt-14 grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                        {services.map((service, index) => (
                            <article
                                key={service.title}
                                data-aos="zoom-in"
                                data-aos-delay={100 + index * 100}
                                className="marketing-service text-center"
                            >
                                <div className="marketing-service-icon mx-auto flex h-16 w-16 items-center justify-center rounded-md bg-primary/25 text-primary-light ring-1 ring-primary/40">
                                    <i
                                        className={`${service.icon} text-2xl`}
                                        aria-hidden="true"
                                    />
                                </div>
                                <h3 className="mt-5 font-display text-xl font-semibold text-white">
                                    {service.title}
                                </h3>
                                <p className="mt-3 text-sm leading-relaxed text-white/65">
                                    {service.description}
                                </p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>

            <Methodology />
            <Projects />

            {/* <section className="bg-brand-dark py-16 md:py-20">
                <div className="mx-auto max-w-5xl px-4 md:px-6">
                    <div
                        data-aos="zoom-in"
                        data-aos-duration="900"
                        className="relative aspect-video w-full overflow-hidden rounded-lg border border-white/10 shadow-2xl shadow-black/40"
                    >
                        <iframe
                            className="absolute inset-0 h-full w-full"
                            src="https://www.youtube.com/embed/vTcMYK8VvIo?rel=0"
                            title="Trackerdev video"
                            allowFullScreen
                        />
                    </div>
                </div>
            </section> */}

            <Footer />
        </Main>
    );
}

export default Home;
