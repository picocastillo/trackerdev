import React, { useState } from "react";

function getSlides() {
    if (
        typeof window !== "undefined" &&
        Array.isArray(window.__PORTFOLIO_PROJECTS__)
    ) {
        return window.__PORTFOLIO_PROJECTS__;
    }
    return [];
}

function BadgeList({ badges }) {
    return (
        <div className="mt-3 flex flex-wrap gap-2">
            {(badges || []).map((badge) => (
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
    const slides = getSlides();
    const [activeIndex, setActiveIndex] = useState(0);
    const slide = slides[activeIndex];

    const goPrev = () =>
        setActiveIndex((i) => (i === 0 ? slides.length - 1 : i - 1));
    const goNext = () =>
        setActiveIndex((i) => (i === slides.length - 1 ? 0 : i + 1));

    if (!slides.length || !slide) {
        return (
            <section
                id="proyectos"
                className="relative bg-gradient-to-br from-primary-dark via-brand-dark to-stone-900 py-20 md:py-28"
            >
                <div className="mx-auto max-w-7xl px-4 md:px-6 text-center">
                    <p className="text-3xl" aria-hidden="true">
                        🚀
                    </p>
                    <h2 className="mt-2 font-display text-3xl font-bold text-white md:text-4xl">
                        Proyectos que impulsamos
                    </h2>
                </div>
            </section>
        );
    }

    return (
        <section
            id="proyectos"
            className="relative bg-gradient-to-br from-primary-dark via-brand-dark to-stone-900 py-20 md:py-28"
        >
            <div className="mx-auto max-w-7xl px-4 md:px-6">
                <p
                    data-aos="fade-up"
                    className="text-center text-3xl"
                    aria-hidden="true"
                >
                    🚀
                </p>
                <h2
                    data-aos="fade-up"
                    data-aos-duration="900"
                    className="mt-2 font-display text-center text-3xl font-bold text-white md:text-4xl"
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
