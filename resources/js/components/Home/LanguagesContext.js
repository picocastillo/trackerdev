import React from "react";

export const language = {
    es: {
        name: "Change to English",
        menu: {
            login: "Ingresar",
            home: "Inicio",
            methodology: "Metodología",
            projects: "Proyectos",
            contact: "Contacto",
        },
        home: {
            _1: "Software para",
            _2: "hacer crecer tu negocio.",
            _3: "Automatizar procesos.",
            _4: "Decidir con información clara y precisa.",
            lead: "Diseñamos, desarrollamos, probamos, ponemos en producción y mantenemosp software web y móviles a medida, desde 2018.",
        },
        methodology: {
            left_1: "Metodología de trabajo",
            left_2: "Convertimos ideas claras en productos que se pueden lanzar y medir.",
            right_1: [
                {
                    id: 0,
                    title: "Descubrimiento",
                    content:
                        "Definimos qué problema resolvemos, para quién y con qué objetivos. Entregamos un documento con el alcance del MVP y prioridades claras.",
                },
                {
                    id: 1,
                    title: "Prototipo",
                    content:
                        "Diseñamos una primera versión navegable para validar flujos, pantallas y el concepto del producto antes de invertir en desarrollo completo.",
                },
                {
                    id: 2,
                    title: "Acuerdo y presupuesto",
                    content:
                        "Con el prototipo validado, estimamos horas, armamos el plan de tareas y acordamos un presupuesto alcanzable para la primera entrega.",
                },
                {
                    id: 3,
                    title: "Producto en marcha",
                    content:
                        "Desarrollamos, probamos y te damos visibilidad del avance. Al final tenés una primera versión funcionando, lista para usar y seguir mejorando.",
                },
            ],
        },
        contact: {
            email: "Correo electrónico",
            message: "Mensaje",
            name: "Nombre",
            submit: "Enviar",
            solutions: "Hablemos de",
            integral: "tu proyecto",
            lead: "Escribinos por WhatsApp y te respondemos con una propuesta clara para tu idea.",
        },
        projects: {
            projects: [
                {
                    title: "Comprobar",
                    description:
                        "App para el seguimiento de diabetes: cuestionarios, registro de datos y detección temprana de riesgos.",
                },
                {
                    title: "Show Travelers",
                    description:
                        "App móvil para viajeros con alertas útiles y propuestas de visitas turísticas durante el viaje.",
                },
                {
                    title: "Seccoplac",
                    description:
                        "Sitio web para captar clientes y franquiciados, mostrar productos y atender consultas con chatbot.",
                },
                {
                    title: "Grow 420",
                    description:
                        "Sistema web para controlar stock, ventas, costos y ganancias con una interfaz simple de operación diaria.",
                },
            ],
        },
    },
    en: {
        name: "Cambiar a Español",
        menu: {
            login: "Log in",
            home: "Home",
            methodology: "Methodology",
            projects: "Projects",
            contact: "Contact",
        },
        home: {
            _1: "Software to",
            _2: "grow your business.",
            _3: "Automate processes.",
            _4: "Make decisions with clear, precise data.",
            lead: "We design, build, and test custom web and mobile products from Santa Fe, Argentina.",
        },
        methodology: {
            left_1: "How we work",
            left_2: "We turn clear ideas into products you can launch and measure.",
            right_1: [
                {
                    id: 0,
                    title: "Discovery",
                    content:
                        "We define the problem, the users, and the goals. You get an MVP scope document with clear priorities.",
                },
                {
                    id: 1,
                    title: "Prototype",
                    content:
                        "We design a navigable first version to validate flows, screens, and product concept before full development.",
                },
                {
                    id: 2,
                    title: "Agreement & quote",
                    content:
                        "Once the prototype is validated, we estimate hours, plan tasks, and agree on a realistic budget for the first release.",
                },
                {
                    id: 3,
                    title: "Working product",
                    content:
                        "We build, test, and keep you in the loop. You get a working first version ready to use and improve.",
                },
            ],
        },
        contact: {
            email: "Email",
            message: "Message",
            submit: "Send",
            name: "Name",
            solutions: "Let's talk about",
            integral: "your project",
            lead: "Message us on WhatsApp and we'll reply with a clear proposal for your idea.",
        },
        projects: {
            projects: [
                {
                    title: "Comprobar",
                    description:
                        "An app for diabetes tracking: questionnaires, data logging, and early risk detection.",
                },
                {
                    title: "Show Travelers",
                    description:
                        "A mobile app for travelers with useful alerts and tourist visit suggestions during the trip.",
                },
                {
                    title: "Seccoplac",
                    description:
                        "A website to attract customers and franchisees, showcase products, and handle inquiries with a chatbot.",
                },
                {
                    title: "Grow 420",
                    description:
                        "A web system to track stock, sales, costs, and profits with a simple daily operations interface.",
                },
            ],
        },
    },
};

export const LanguageContext = React.createContext(
    language.es, // valor por defecto
);
