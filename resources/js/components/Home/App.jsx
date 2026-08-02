import React from "react";
import { BrowserRouter, Routes, Route, useLocation } from "react-router-dom";
import { CSSTransition, TransitionGroup } from "react-transition-group";

import Home from "./pages/Home";
import Methodology from "./pages/Methodology";
import Projects from "./pages/Projects";
import Contact from "./pages/Contact";
import Layout from "./shared/Layout";

const NotFound = () => (
    <div className="flex min-h-screen items-center justify-center px-4">
        <h1 className="font-display text-center text-2xl text-white">
            No existe esa URL
        </h1>
    </div>
);

function AnimatedRoutes() {
    const location = useLocation();

    return (
        <Layout pathname={location.pathname}>
            <TransitionGroup component={null}>
                <CSSTransition
                    key={location.pathname}
                    timeout={500}
                    classNames="page"
                >
                    <Routes location={location}>
                        <Route path="/" element={<Home />} />
                        <Route path="/methodology" element={<Methodology />} />
                        <Route path="/projects" element={<Projects />} />
                        <Route path="/contact" element={<Contact />} />
                        <Route path="*" element={<NotFound />} />
                    </Routes>
                </CSSTransition>
            </TransitionGroup>
        </Layout>
    );
}

function App() {
    return (
        <BrowserRouter>
            <div className="app">
                <AnimatedRoutes />
            </div>
        </BrowserRouter>
    );
}

export default App;
