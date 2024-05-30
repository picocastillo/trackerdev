import React, { Component, useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';

import {RISKS} from '../utils'

const Objetive = ({addObjetive}) => {
    const [text, setText] = React.useState('');
    let textRef = useRef(null)

    return (
        <div className="row">
            <div className="col-9">
                <div className="form-group">
                    <textarea ref={textRef} type="email" className="form-control" onChange={(asd) => setText(asd.target.value)} placeholder="Nuevo objetivo"></textarea>
                </div>

            </div>
            <div className="col-2">

            <button onClick={() => {addObjetive(text);setText('');textRef.current.value=""}} disabled={text==''} className="btn btn-primary">+</button>
            </div>
        </div>
    )
}
const Task = ({addTask}) => {
    const [desc, setDesc] = React.useState('');
    const [title, setTitle] = React.useState('');
    const [estimation, setEstimation] = React.useState('');
    const [billed, setBilled] = React.useState('');
    let rname = useRef(null);
    let rbilled = useRef(null);
    let restimation = useRef(null);
    let rdesc = useRef(null);
    const submit = () => {
        addTask(
            title,
            desc,
            estimation,
            billed,
        )
        rname.current.value='';
        robjs.current.value='';
        restimation.current.value='';
        billed.current.value='';
        rdesc.current.value='';
    }
    const disabled =  desc=='' || title=='' || estimation=='' || desc=='' || billed=='' 

    

    return (
        <div className="container">
            <div className="card p-1">
                <div className="row">
                    <div className="col-12">
                        <div className="form-group">
                            <input ref={rname} onChange={(asd) => setTitle(asd.target.value)} className="form-control" placeholder="Nombre tarea"></input>
                        </div>
                    </div>
                    
                </div>
                <div className="row">
                    <div className="col-6">
                        <div className="form-group">
                            <input ref={restimation} onChange={(asd) => setEstimation(asd.target.value)} type="number" className="form-control" placeholder="Estimación " ></input>
                        </div>
                    </div>
                    <div className="col-6">
                        <div className="form-group">
                            <input ref={rbilled} onChange={(asd) => setBilled(asd.target.value)} type="number" className="form-control" placeholder="Facturado " ></input>
                        </div>
                    </div>
                    
                    
                </div>
                <div className="row">
                    <div className="col-10">
                        <div className="row">
                            <div className="col-12">
                                <div className="form-group">
                                    <textarea ref={rdesc} rows="4" className="form-control" id="description" onChange={(asd) => setDesc(asd.target.value)} placeholder="Descripción"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-2">

                        <button onClick={submit} disabled={disabled}  className="btn btn-primary col-12 mt-4">+</button>
                    </div>
                </div>

            </div>

        </div>
       
    )
}

class Iteration extends Component {
    constructor(props){
        super(props);
        let objetives = JSON.parse(props.objetives);
        const is_updated = !objetives ? 0 : 1;
        const _token = JSON.parse(props.token);
        let _tasks = JSON.parse(props.tasks);
        let tasks = [];
        if (_tasks){
            _tasks.map ( e => {
                tasks.push({
                    title: e.name,
                    description: e.description,
                    estimation: e.estimation,
                    billed: e.billed,
                })
            })

        }
        const project_id = parseInt(window.location.pathname.split('/')[2])
        if (!objetives){
            objetives = []
        }
       this.state = {
           objetives,
           tasks,
           _token,
           project_id,
           is_updated,
       }
        this.clickAddObj = this.clickAddObj.bind(this)
        this.clickAddTasks = this.clickAddTasks.bind(this)
    }
    
    
      clickAddObj(text){
        //   console.log(this)
          let objetives = this.state.objetives;
          objetives.push(text);
          this.setState({
            objetives
          })

      }

      getTotalEstimated(){
          if (!this.state.tasks.length) return 0;
          let sum = 0;

           this.state.tasks.map( (e,i) => {
                  sum += parseInt(e.estimation)
          })
          return sum
      }
      getBilledHOurs(){
          if (!this.state.tasks.length) return 0;
          let sum = 0;

           this.state.tasks.map( (e,i) => {
                  sum += parseInt(e.billed)
          })
          return sum
      }

      clickAddTasks(title,description,estimation,billed){
        let tasks = this.state.tasks;
        tasks.push({
            title,description,estimation,billed
        })
        this.setState({
            tasks
        })
      }
      clickDeleteTask(index){
          let tasks = this.state.tasks
          tasks.splice(index,1);
          this.setState({
              tasks
          })
      }
      clickDeleteObj(index){
          let objetives = this.state.objetives
          objetives.splice(index,1);
          this.setState({
              objetives
          })
      }

    render() {
        if (!this.state)
            return (null)
        return (
            <div>
            <div className="container">
                <div className="row ">
                    <div className="col-4">
                        <div className="card">
                            <div className="card-header">Objetivos</div>
                            <div className="row m-4">
                                <div className="col-12 iteration_height">
                                    {
                                        !this.state.objetives.length ? 
                                        (
                                            <p>Aun no se agregaron objetivos</p>
                                        )
                                        :
                                        (
                                            <ol>
                                            {
                                                    this.state.objetives.map ((e,i) => {
                                                        return (
                                                                <li key={i} >{e} &nbsp;&nbsp;&nbsp;
                                                                <div onClick={() => {this.clickDeleteObj(i)}} className="btn btn-danger btn-sm"> X </div>
                                                                </li>
                                                        )
                                                    })
                                            }
                                            </ol>
                                        )
                                    }
                                </div>
                            </div>
                            <div className="row ml-2">
                                <div className="col-12">
                                    <Objetive addObjetive={this.clickAddObj} />
                                </div>
                            </div>

                        </div>
                        <div className="card mt-2">
                            <form method="POST" action={this.state.is_updated ? `/iteration/update` : "/iteration"}>
                                <input type="hidden" name="_token" value={this.state._token}></input>
                                <input type="hidden" name="project_id" value={this.state.project_id}></input>
                                <input type="hidden" name="billed_hours" value={this.getTotalEstimated()}></input>
                                <input type="hidden" name="estimated_hours" value={this.getBilledHOurs()}></input>
                                <div className="d-none">

                                    {
                                        this.state.objetives.length && 
                                            this.state.objetives.map ((e,i) => {
                                                return (
                                                    <input type="hidden" key={i} name="objetives[]" value={e}></input>
                                                )
                                            })
                                    }
                                    {
                                        this.state.tasks.length && 
                                            this.state.tasks.map ((e,i) => {
                                                return (
                                                    <div >
                                                        <input type="hidden" key={i+123} name="tasks[estimation][]" value={e.estimation}></input>
                                                        <input type="hidden" key={i+222} name="tasks[billed][]" value={e.billed}></input>
                                                        <input type="hidden" key={i+333} name="tasks[title][]" value={e.title}></input>
                                                        <input type="hidden" key={i+323} name="tasks[description][]" value={e.description}></input>

                                                    </div>
                                                )
                                            })
                                    }
                                </div>

                                <div className="row m-2 mt-4">
                                    <div className="col-6">
                                        <div className="form-group">
                                            <input name="time" type="number" required placeholder={this.props.time ? parseInt(this.props.time) : "Dias"} className="form-control"  ></input>
                                        </div>
                                    </div>
                                    <div className="col-6">
                                        <div className="form-group">
                                            
                                            {
                                                this.props.billedHours ?
                                                (
                                                    <input name="billed_hours" type="number"  className="form-control" placeholder={this.props.billedHours} ></input>
                                                )
                                                :
                                                (
                                                    <input name="billed_hours" type="number"  className="form-control" placeholder={ "H Aprobadas"} ></input>
                                                )
                                            }
                                        </div>
                                    </div>
                                    <div className="col-12">
                                        <div className="form-group">
                                            {
                                                this.props.title ?
                                                (
                                                    <input name="title" type="text" required className="form-control" placeholder={this.props.title} ></input>
                                                )
                                                :
                                                (
                                                    <input name="title" type="text" required className="form-control" placeholder={"Titulo"} ></input>
                                                )
                                            }
                                            
                                        </div>
                                    </div>
                                    <div className="col-12">
                                        <smal>En caso de edición, poner campos de arriba</smal>
                                        <button type="submit" className="btn btn-primary col-12">
                                            Terminar
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                    <div className="col-8">
                        <div className="card">
                            <div className="card-header">
                                <div className="row">
                                    <div className="col-12"> {this.state.tasks.length} Tareas creadas &nbsp;&nbsp;&nbsp;
                                        <div className="badge badge-success"> {this.getTotalEstimated()} E</div>&nbsp;&nbsp;&nbsp;
                                        <div className="badge badge-warning">{this.getBilledHOurs()} F</div>&nbsp;&nbsp;&nbsp;
                                        {
                                            this.getBilledHOurs()<this.getTotalEstimated() &&
                                            <div className="badge badge-danger">NO DEBEN SER MAYOR LAS ESTIMADAS QUE LAS FACTURADAS</div>
                                        }
                                    </div>
                                </div>
                            </div>
                            <div className="row m-4">
                                <div className="col-12">
                                    <div className="iteration_height">
                                        {
                                            !this.state.tasks.length ? 
                                            (
                                                <p>Aun no se agregaron Tareas</p>
                                            )
                                            :
                                            (
                                            this.state.tasks.map( (e,i) => {
                                                return (
                                                        <div key={i} className="card p-4">
                                                            <div className="row">
                                                                <div className="col-8">
                                                                    <div className="form-group">
                                                                        <p>Nombre: <b>{e.title}</b> </p>
                                                                    </div>
                                                                </div>
                                                               
                                                               
                                                                <div className="col-2">
                                                                    <div className="form-group">
                                                                        <span className="badge badge-success p-2"> <b>{e.estimation} hs E</b> </span>
                                                                    </div>
                                                                </div>
                                                                <div className="col-2">
                                                                    <div className="form-group">
                                                                        <span className="badge badge-warning p-2"><b>{e.billed} hs F</b> </span>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div className="row">
                                                                <div className="col-11">
                                                                    <i>{e.description} </i>
                                                                </div>
                                                                <div className="col-1">
                                                                    <div onClick={() => {this.clickDeleteTask(i)}} className="btn btn-danger"> X </div>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                )
                                            })
                                            )
                                        }
                                    </div>
                                </div>
                            </div>
                            <div className="row ml-2">
                                <div className="col-12">
                                    <Task addTask={this.clickAddTasks}/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            
            </div>
            </div>
        )
    };
}

export default Iteration;

if (document.getElementById('create_iteration')) {
    const token = document.getElementById('create_iteration').getAttribute('token')
    const objetives = document.getElementById('create_iteration').getAttribute('objetives')
    const tasks = document.getElementById('create_iteration').getAttribute('tasks')
    const project_id = document.getElementById('create_iteration').getAttribute('project_id')
    const time = document.getElementById('create_iteration').getAttribute('time')
    const billedHours = document.getElementById('create_iteration').getAttribute('billedHours')
    const title = document.getElementById('create_iteration').getAttribute('title')
    ReactDOM.render(<Iteration billedHours={billedHours} title={title} token={token} projectId={project_id} tasks={tasks} objetives={objetives} time={time} />, document.getElementById('create_iteration'));
}
