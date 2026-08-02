import React, { useState } from "react";

const slides = [
    {
        id: 0,
        title: "Comprobar",
        badges: [
            "React Native",
            "Firebase",
            "Redux",
            "Thunk",
            "Bootstrap",
            "XD",
        ],
        description:
            "App para el seguimiento de diabetes: cuestionarios, registro de datos y detección temprana de riesgos.",
        image: "/images/proj_1.webp",
    },
    {
        id: 1,
        title: "Show Travelers",
        badges: [
            "React Native",
            "PHP",
            "Bootstrap",
            "Redux",
            "Google Map",
            "Sagas",
        ],
        description:
            "App móvil para viajeros con alertas útiles y propuestas de visitas turísticas durante el viaje.",
        image: "/images/proj_2.webp",
    },
    {
        id: 2,
        title: "Sprint",
        badges: [
            "React Native",
            "Laravel",
            "Barcode",
            "React JS",
            "Bootstrap",
            "Redux",
            "Sagas",
            "Expo",
        ],
        description:
            "Ecosistema web + apps iOS/Android para gestionar la logística de paquetería de punta a punta.",
        image: "/images/proj_4.webp",
        secondaryImage: "/images/proj_4_1.webp",
    },
    {
        id: 3,
        title: "Prego",
        badges: [
            "React Native",
            "Laravel",
            "React JS",
            "Bootstrap",
            "Redux",
            "Sagas",
            "Landing Page",
            "Expo",
            "Figma",
        ],
        description:
            "Marketplace que conecta profesionales con pedidos de trabajo, de forma simple y rápida.",
        image: "/images/proj_5_1.webp",
        secondaryImage: "/images/proj_5_2.webp",
    },
    {
        id: 4,
        title: "Moveler",
        badges: [
            "React Native",
            "Laravel",
            "React JS",
            "Redux",
            "Bootstrap",
            "Sagas",
            "Expo",
            "Figma",
        ],
        description:
            "CMS web que alimenta una app móvil con contenidos y administración centralizada.",
        image: "/images/proj_6_1.webp",
        secondaryImage: "/images/proj_6_2.webp",
    },
    {
        id: 5,
        title: "Estoker",
        badges: ["Laravel", "React JS", "Bootstrap", "Figma"],
        description:
            "Control de stock y productos por Excel o interfaz gráfica, pensado para la operación diaria.",
        image: "/images/proj_7.webp",
    },
    {
        id: 6,
        title: "Seccoplac",
        badges: ["Laravel", "React JS", "Bootstrap", "Landing Page"],
        description:
            "Sitio corporativo para captar clientes y franquiciados, con catálogo de productos y chatbot.",
        image: "/images/proj_3.webp",
    },
];

function BadgeList({ badges }) {
    return (
        <div className="mt-3 flex flex-wrap gap-2">
            {badges.map((badge) => (
                <span
                    key={badge}
                    className="inline-flex border border-white/15 bg-white/5 px-2.5 py-0.5 text-xs font-medium text-white/75"
                >
                    {badge}
                </span>
            ))}
        </div>
    );
}

export default function Projects() {
    const [activeIndex, setActiveIndex] = useState(0);
    const slide = slides[activeIndex];

    const goPrev = () =>
        setActiveIndex((i) => (i === 0 ? slides.length - 1 : i - 1));
    const goNext = () =>
        setActiveIndex((i) => (i === slides.length - 1 ? 0 : i + 1));

    return (
        <section
            id="proyectos"
            className="relative bg-gradient-to-br from-primary-dark via-brand-dark to-stone-900 py-20 md:py-28"
        >
            <div className="mx-auto max-w-7xl px-4 md:px-6">
                <h2
                    data-aos="fade-up"
                    data-aos-duration="900"
                    className="font-display text-center text-3xl font-bold text-white md:text-4xl"
                >
                    Proyectos que impulsamos
                </h2>

                <div
                    className="relative mt-12"
                    data-aos="fade-up"
                    data-aos-delay="120"
                >
                    <ol className="mb-8 flex list-none justify-center gap-2 p-0">
                        {slides.map((s, i) => (
                            <li key={s.id}>
                                <button
                                    type="button"
                                    aria-label={`Proyecto ${i + 1}`}
                                    onClick={() => setActiveIndex(i)}
                                    className={`h-2.5 w-2.5 rounded-full transition duration-300 ${
                                        i === activeIndex
                                            ? "scale-125 bg-primary-light"
                                            : "bg-white/30 hover:bg-white/50"
                                    }`}
                                />
                            </li>
                        ))}
                    </ol>

                    <div
                        key={slide.id}
                        className="marketing-project-panel grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-10"
                    >
                        <div className="lg:col-span-5">
                            <h3 className="font-display text-2xl font-bold text-white md:text-3xl">
                                {slide.title}
                            </h3>
                            <BadgeList badges={slide.badges} />
                            <p className="mt-4 text-base leading-relaxed text-white/70">
                                {slide.description}
                            </p>
                        </div>
                        <div className="lg:col-span-7">
                            <div
                                className={`grid gap-3 ${slide.secondaryImage ? "sm:grid-cols-5" : ""}`}
                            >
                                <div
                                    className={`overflow-hidden rounded-lg border border-white/10 bg-black/30 shadow-xl shadow-black/30 transition duration-500 hover:border-white/25 ${slide.secondaryImage ? "sm:col-span-3" : ""}`}
                                >
                                    <img
                                        src={slide.image}
                                        alt={`Desarrollo de software: proyecto ${slide.title}`}
                                        className="aspect-[16/10] w-full object-cover object-top transition duration-700 hover:scale-[1.03]"
                                        loading={
                                            activeIndex === 0 ? "eager" : "lazy"
                                        }
                                        decoding="async"
                                    />
                                </div>
                                {slide.secondaryImage && (
                                    <div className="overflow-hidden rounded-lg border border-white/10 bg-black/30 shadow-xl shadow-black/30 transition duration-500 hover:border-white/25 sm:col-span-2">
                                        <img
                                            src={slide.secondaryImage}
                                            alt={`Desarrollo de software: detalle de ${slide.title}`}
                                            className="aspect-[16/10] h-full w-full object-cover object-top transition duration-700 hover:scale-[1.03] sm:aspect-auto"
                                            loading="lazy"
                                            decoding="async"
                                        />
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={goPrev}
                        className="absolute left-0 top-1/2 hidden -translate-y-1/2 rounded-md border border-white/15 bg-black/40 px-3 py-2 text-white transition hover:bg-black/60 md:inline-flex"
                        aria-label="Anterior"
                    >
                        <i className="fas fa-chevron-left" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        onClick={goNext}
                        className="absolute right-0 top-1/2 hidden -translate-y-1/2 rounded-md border border-white/15 bg-black/40 px-3 py-2 text-white transition hover:bg-black/60 md:inline-flex"
                        aria-label="Siguiente"
                    >
                        <i
                            className="fas fa-chevron-right"
                            aria-hidden="true"
                        />
                    </button>

                    <div className="mt-8 flex justify-center gap-3 md:hidden">
                        <button
                            type="button"
                            onClick={goPrev}
                            className="rounded-md border border-white/15 bg-black/40 px-4 py-2 text-white"
                            aria-label="Anterior"
                        >
                            <i
                                className="fas fa-chevron-left"
                                aria-hidden="true"
                            />
                        </button>
                        <button
                            type="button"
                            onClick={goNext}
                            className="rounded-md border border-white/15 bg-black/40 px-4 py-2 text-white"
                            aria-label="Siguiente"
                        >
                            <i
                                className="fas fa-chevron-right"
                                aria-hidden="true"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
}
