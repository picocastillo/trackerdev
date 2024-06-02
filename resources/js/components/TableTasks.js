import React, { Component, useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';

import {RISKS,STATES} from '../utils'

class TableTasks extends Component {
    constructor(props){
        super(props);
        const tasks = JSON.parse(props.tasks);
        this.state = {
            tasks
        }

       
    }
    
    
    render() {
        const {tasks} = this.state
        return (
            <div className="table_task_height">
                <table className="table">
                    <thead className="thead-dark">
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
                        {
                            tasks.map( (e,i) => {
                                return (
                                    <tr key={i}>
                                        <th scope="row"> {i+1}</th>
                                        <td> <a href={`/tasks/${e.id}`} > {e.name} </a>  </td>
                                        <td> {e.estimation} </td>
                                        <td> {STATES[e.state]} </td>
                                        <td> {e.assignTo ? e.assignTo : '-----'} </td>
                                        <td>
                                            <div className="progress">
                                                <div className="progress-bar bg-success" role="progressbar" style={{width: '25%'}} ariaValuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>

                                    </tr>
                                )
                            })
                        }
                            
                    </tbody>
                </table>
            </div>
        )
    };
}

export default TableTasks;

if (document.getElementById('table_tasks')) {
    const tasks = document.getElementById('table_tasks').getAttribute('tasks')
    ReactDOM.render(<TableTasks tasks={tasks} />, document.getElementById('table_tasks'));
}
