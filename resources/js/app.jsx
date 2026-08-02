import "./bootstrap";

import React from "react";
import { createRoot } from "react-dom/client";
import AOS from "aos";

import App from "./components/Home/App";
import Iteration from "./components/Iteration";
import TableTasks from "./components/TableTasks";
import ItemsTask from "./components/ItemsTask";
import MessageTask from "./components/MessageTask";

AOS.init({
    duration: 800,
    easing: "ease-out-cubic",
    once: true,
    offset: 80,
    mirror: false,
});

function mountIsland(elementId, component) {
    const el = document.getElementById(elementId);
    if (!el) {
        return;
    }
    createRoot(el).render(component);
}

const appHomeEl = document.getElementById("app-home");
if (appHomeEl) {
    mountIsland("app-home", <App />);
}

const createIterationEl = document.getElementById("create_iteration");
if (createIterationEl) {
    mountIsland(
        "create_iteration",
        <Iteration
            billedHours={createIterationEl.getAttribute("billedHours")}
            title={createIterationEl.getAttribute("title")}
            token={createIterationEl.getAttribute("token")}
            projectId={createIterationEl.getAttribute("project_id")}
            tasks={createIterationEl.getAttribute("tasks")}
            objetives={createIterationEl.getAttribute("objetives")}
            time={createIterationEl.getAttribute("time")}
        />,
    );
}

const tableTasksEl = document.getElementById("table_tasks");
if (tableTasksEl) {
    mountIsland(
        "table_tasks",
        <TableTasks tasks={tableTasksEl.getAttribute("tasks")} />,
    );
}

const createTaskEl = document.getElementById("create_task");
if (createTaskEl) {
    mountIsland(
        "create_task",
        <ItemsTask
            token={createTaskEl.getAttribute("token")}
            tasks={createTaskEl.getAttribute("tasks")}
            items={createTaskEl.getAttribute("items")}
            objetives={createTaskEl.getAttribute("objetives")}
            time={createTaskEl.getAttribute("time")}
        />,
    );
}

const messageTaskEl = document.getElementById("message_task");
if (messageTaskEl) {
    mountIsland(
        "message_task",
        <MessageTask token={messageTaskEl.getAttribute("token")} />,
    );
}

const selectProject = document.getElementById("select_project");
if (selectProject) {
    selectProject.addEventListener("change", function handleProjectChange() {
        const id = this.value;
        const goToProject = document.getElementById("go_to_project");
        const goToEditProject = document.getElementById("go_to_edit_project");
        const inputDeposit = document.getElementById("input_deposit_project");

        if (goToProject) {
            goToProject.setAttribute("href", `manager/iteration/${id}`);
        }
        if (goToEditProject) {
            goToEditProject.setAttribute(
                "href",
                `manager/iteration/edit/${id}`,
            );
        }
        if (inputDeposit) {
            inputDeposit.setAttribute("value", id);
        }
    });
}
