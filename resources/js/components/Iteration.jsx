import React, { Component, useRef } from "react";

const Objetive = ({ addObjetive }) => {
    const [text, setText] = React.useState("");
    const textRef = useRef(null);

    return (
        <div className="flex flex-wrap -mx-2">
            <div className="w-full px-2 md:w-9/12">
                <div className="mb-4">
                    <textarea
                        ref={textRef}
                        className="form-input"
                        onChange={(event) => setText(event.target.value)}
                        placeholder="Nuevo objetivo"
                    />
                </div>
            </div>
            <div className="w-full px-2 md:w-2/12">
                <button
                    onClick={() => {
                        addObjetive(text);
                        setText("");
                        textRef.current.value = "";
                    }}
                    disabled={text === ""}
                    className="btn-primary"
                >
                    +
                </button>
            </div>
        </div>
    );
};

const Task = ({ addTask }) => {
    const [desc, setDesc] = React.useState("");
    const [title, setTitle] = React.useState("");
    const [estimation, setEstimation] = React.useState("");
    const [billed, setBilled] = React.useState("");
    const rname = useRef(null);
    const rbilled = useRef(null);
    const restimation = useRef(null);
    const rdesc = useRef(null);

    const submit = () => {
        addTask(title, desc, estimation, billed);
        rname.current.value = "";
        restimation.current.value = "";
        rbilled.current.value = "";
        rdesc.current.value = "";
    };

    const disabled =
        desc === "" || title === "" || estimation === "" || billed === "";

    return (
        <div className="mx-auto max-w-7xl px-4">
            <div className="card p-1">
                <div className="flex flex-wrap -mx-2">
                    <div className="w-full px-2">
                        <div className="mb-4">
                            <input
                                ref={rname}
                                onChange={(event) =>
                                    setTitle(event.target.value)
                                }
                                className="form-input"
                                placeholder="Nombre tarea"
                            />
                        </div>
                    </div>
                </div>
                <div className="flex flex-wrap -mx-2">
                    <div className="w-full px-2 md:w-1/2">
                        <div className="mb-4">
                            <input
                                ref={restimation}
                                onChange={(event) =>
                                    setEstimation(event.target.value)
                                }
                                type="number"
                                className="form-input"
                                placeholder="Estimación "
                            />
                        </div>
                    </div>
                    <div className="w-full px-2 md:w-1/2">
                        <div className="mb-4">
                            <input
                                ref={rbilled}
                                onChange={(event) =>
                                    setBilled(event.target.value)
                                }
                                type="number"
                                className="form-input"
                                placeholder="Facturado "
                            />
                        </div>
                    </div>
                </div>
                <div className="flex flex-wrap -mx-2">
                    <div className="w-full px-2 md:w-10/12">
                        <div className="mb-4">
                            <textarea
                                ref={rdesc}
                                rows="4"
                                className="form-input"
                                id="description"
                                onChange={(event) =>
                                    setDesc(event.target.value)
                                }
                                placeholder="Descripción"
                            />
                        </div>
                    </div>
                    <div className="w-full px-2 md:w-2/12">
                        <button
                            onClick={submit}
                            disabled={disabled}
                            className="btn-primary mt-4 w-full"
                        >
                            +
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

class Iteration extends Component {
    constructor(props) {
        super(props);
        let objetives = JSON.parse(props.objetives);
        const is_updated = !objetives ? 0 : 1;
        const _token = JSON.parse(props.token);
        const _tasks = JSON.parse(props.tasks);
        let tasks = [];
        if (_tasks) {
            _tasks.map((e) => {
                tasks.push({
                    title: e.name,
                    description: e.description,
                    estimation: e.estimation,
                    billed: e.billed,
                });
            });
        }
        const project_id = parseInt(window.location.pathname.split("/")[2]);
        if (!objetives) {
            objetives = [];
        }
        this.state = {
            objetives,
            tasks,
            _token,
            project_id,
            is_updated,
        };
        this.clickAddObj = this.clickAddObj.bind(this);
        this.clickAddTasks = this.clickAddTasks.bind(this);
    }

    clickAddObj(text) {
        const objetives = this.state.objetives;
        objetives.push(text);
        this.setState({
            objetives,
        });
    }

    getTotalEstimated() {
        if (!this.state.tasks.length) return 0;
        let sum = 0;

        this.state.tasks.map((e) => {
            sum += parseInt(e.estimation);
        });
        return sum;
    }

    getBilledHOurs() {
        if (!this.state.tasks.length) return 0;
        let sum = 0;

        this.state.tasks.map((e) => {
            sum += parseInt(e.billed);
        });
        return sum;
    }

    clickAddTasks(title, description, estimation, billed) {
        const tasks = this.state.tasks;
        tasks.push({
            title,
            description,
            estimation,
            billed,
        });
        this.setState({
            tasks,
        });
    }

    clickDeleteTask(index) {
        const tasks = this.state.tasks;
        tasks.splice(index, 1);
        this.setState({
            tasks,
        });
    }

    clickDeleteObj(index) {
        const objetives = this.state.objetives;
        objetives.splice(index, 1);
        this.setState({
            objetives,
        });
    }

    render() {
        if (!this.state) return null;

        return (
            <div>
                <div className="mx-auto max-w-7xl px-4">
                    <div className="grid grid-cols-12 gap-4">
                        <div className="col-span-12 md:col-span-4">
                            <div className="card">
                                <div className="card-header">Objetivos</div>
                                <div className="m-4">
                                    <div className="scroll-panel iteration_height">
                                        {!this.state.objetives.length ? (
                                            <p>Aun no se agregaron objetivos</p>
                                        ) : (
                                            <ol>
                                                {this.state.objetives.map(
                                                    (e, i) => (
                                                        <li key={i}>
                                                            {e}{" "}
                                                            &nbsp;&nbsp;&nbsp;
                                                            <button
                                                                type="button"
                                                                onClick={() => {
                                                                    this.clickDeleteObj(
                                                                        i,
                                                                    );
                                                                }}
                                                                className="btn-danger btn-sm"
                                                            >
                                                                X
                                                            </button>
                                                        </li>
                                                    ),
                                                )}
                                            </ol>
                                        )}
                                    </div>
                                </div>
                                <div className="ml-2">
                                    <Objetive addObjetive={this.clickAddObj} />
                                </div>
                            </div>
                            <div className="card mt-2">
                                <form
                                    method="POST"
                                    action={
                                        this.state.is_updated
                                            ? `/iteration/update`
                                            : "/iteration"
                                    }
                                >
                                    <input
                                        type="hidden"
                                        name="_token"
                                        value={this.state._token}
                                    />
                                    <input
                                        type="hidden"
                                        name="project_id"
                                        value={this.state.project_id}
                                    />
                                    <input
                                        type="hidden"
                                        name="billed_hours"
                                        value={this.getTotalEstimated()}
                                    />
                                    <input
                                        type="hidden"
                                        name="estimated_hours"
                                        value={this.getBilledHOurs()}
                                    />
                                    <div className="hidden">
                                        {this.state.objetives.length &&
                                            this.state.objetives.map((e, i) => (
                                                <input
                                                    type="hidden"
                                                    key={i}
                                                    name="objetives[]"
                                                    value={e}
                                                />
                                            ))}
                                        {this.state.tasks.length &&
                                            this.state.tasks.map((e, i) => (
                                                <div key={i}>
                                                    <input
                                                        type="hidden"
                                                        name="tasks[estimation][]"
                                                        value={e.estimation}
                                                    />
                                                    <input
                                                        type="hidden"
                                                        name="tasks[billed][]"
                                                        value={e.billed}
                                                    />
                                                    <input
                                                        type="hidden"
                                                        name="tasks[title][]"
                                                        value={e.title}
                                                    />
                                                    <input
                                                        type="hidden"
                                                        name="tasks[description][]"
                                                        value={e.description}
                                                    />
                                                </div>
                                            ))}
                                    </div>

                                    <div className="m-2 mt-4 grid grid-cols-12 gap-4">
                                        <div className="col-span-12 md:col-span-6">
                                            <div className="mb-4">
                                                <input
                                                    name="time"
                                                    type="number"
                                                    required
                                                    placeholder={
                                                        this.props.time
                                                            ? parseInt(
                                                                  this.props
                                                                      .time,
                                                              )
                                                            : "Dias"
                                                    }
                                                    className="form-input"
                                                />
                                            </div>
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <div className="mb-4">
                                                {this.props.billedHours ? (
                                                    <input
                                                        name="billed_hours"
                                                        type="number"
                                                        className="form-input"
                                                        placeholder={
                                                            this.props
                                                                .billedHours
                                                        }
                                                    />
                                                ) : (
                                                    <input
                                                        name="billed_hours"
                                                        type="number"
                                                        className="form-input"
                                                        placeholder="H Aprobadas"
                                                    />
                                                )}
                                            </div>
                                        </div>
                                        <div className="col-span-12">
                                            <div className="mb-4">
                                                {this.props.title ? (
                                                    <input
                                                        name="title"
                                                        type="text"
                                                        required
                                                        className="form-input"
                                                        placeholder={
                                                            this.props.title
                                                        }
                                                    />
                                                ) : (
                                                    <input
                                                        name="title"
                                                        type="text"
                                                        required
                                                        className="form-input"
                                                        placeholder="Titulo"
                                                    />
                                                )}
                                            </div>
                                        </div>
                                        <div className="col-span-12">
                                            <small>
                                                En caso de edición, poner campos
                                                de arriba
                                            </small>
                                            <button
                                                type="submit"
                                                className="btn-primary w-full"
                                            >
                                                Terminar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div className="col-span-12 md:col-span-8">
                            <div className="card">
                                <div className="card-header">
                                    <div className="flex flex-wrap items-center gap-2">
                                        {this.state.tasks.length} Tareas creadas
                                        <span className="inline-flex rounded-full bg-emerald-600 px-2.5 py-0.5 text-xs font-semibold text-white">
                                            {this.getTotalEstimated()} E
                                        </span>
                                        <span className="inline-flex rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-semibold text-white">
                                            {this.getBilledHOurs()} F
                                        </span>
                                        {this.getBilledHOurs() <
                                            this.getTotalEstimated() && (
                                            <span className="inline-flex rounded-full bg-red-600 px-2.5 py-0.5 text-xs font-semibold text-white">
                                                NO DEBEN SER MAYOR LAS ESTIMADAS
                                                QUE LAS FACTURADAS
                                            </span>
                                        )}
                                    </div>
                                </div>
                                <div className="m-4">
                                    <div className="scroll-panel iteration_height">
                                        {!this.state.tasks.length ? (
                                            <p>Aun no se agregaron Tareas</p>
                                        ) : (
                                            this.state.tasks.map((e, i) => (
                                                <div
                                                    key={i}
                                                    className="card mb-2 p-4"
                                                >
                                                    <div className="grid grid-cols-12 gap-4">
                                                        <div className="col-span-12 md:col-span-8">
                                                            <p>
                                                                Nombre:{" "}
                                                                <b>{e.title}</b>
                                                            </p>
                                                        </div>
                                                        <div className="col-span-6 md:col-span-2">
                                                            <span className="inline-flex rounded-full bg-emerald-600 px-2 py-1 text-xs font-semibold text-white">
                                                                <b>
                                                                    {
                                                                        e.estimation
                                                                    }{" "}
                                                                    hs E
                                                                </b>
                                                            </span>
                                                        </div>
                                                        <div className="col-span-6 md:col-span-2">
                                                            <span className="inline-flex rounded-full bg-amber-500 px-2 py-1 text-xs font-semibold text-white">
                                                                <b>
                                                                    {e.billed}{" "}
                                                                    hs F
                                                                </b>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div className="mt-2 grid grid-cols-12 gap-4">
                                                        <div className="col-span-11">
                                                            <i>
                                                                {e.description}
                                                            </i>
                                                        </div>
                                                        <div className="col-span-1">
                                                            <button
                                                                type="button"
                                                                onClick={() => {
                                                                    this.clickDeleteTask(
                                                                        i,
                                                                    );
                                                                }}
                                                                className="btn-danger"
                                                            >
                                                                X
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            ))
                                        )}
                                    </div>
                                </div>
                                <div className="ml-2">
                                    <Task addTask={this.clickAddTasks} />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Iteration;
