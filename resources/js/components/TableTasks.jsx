import React, { Component } from "react";

import { STATES } from "../utils";

class TableTasks extends Component {
    constructor(props) {
        super(props);
        const tasks = JSON.parse(props.tasks);
        this.state = {
            tasks,
        };
    }

    render() {
        const { tasks } = this.state;
        return (
            <div className="scroll-panel table_task_height">
                <table className="table-app">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre tarea</th>
                            <th scope="col">Estimación</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Asignado</th>
                            <th scope="col">Progreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        {tasks.map((e, i) => (
                            <tr key={i}>
                                <th scope="row">{i + 1}</th>
                                <td>
                                    <a href={`/tasks/${e.id}`}>{e.name}</a>
                                </td>
                                <td>{e.estimation}</td>
                                <td>{STATES[e.state]}</td>
                                <td>{e.assignTo ? e.assignTo : "-----"}</td>
                                <td>
                                    <div className="h-2 w-full overflow-hidden rounded-full bg-stone-200">
                                        <div
                                            className="h-full bg-emerald-500"
                                            role="progressbar"
                                            style={{ width: "25%" }}
                                            aria-valuenow="25"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        />
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default TableTasks;
