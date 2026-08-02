import React, { Component, useRef } from "react";

const Message = ({ text, author, date }) => (
    <li className="my-1 w-full rounded-md border border-stone-200 bg-white p-3">
        <div className="flex flex-wrap text-stone-900">
            <div className="w-full px-2 md:w-1/3">
                <img
                    src="https://lh3.googleusercontent.com/a-/AAuE7mD1u11ocX3MV0CBZudM_jRhUm_rSW6QHzMoXQzw=s96-k-no"
                    className="h-16 w-16 rounded-full object-cover"
                    alt=""
                />
            </div>
            <div className="w-full px-2 md:w-2/3">
                <div
                    className="text_comment p-2"
                    dangerouslySetInnerHTML={{ __html: text }}
                />
            </div>
            <footer className="blockquote-footer w-full pl-2 pt-2 text-sm text-stone-500">
                Escrito por
                <cite title="Source Title">
                    &nbsp;{author}&nbsp;&nbsp;&nbsp;&nbsp;{date}
                </cite>
            </footer>
        </div>
    </li>
);

const AddMessage = ({ addItem, token, task_id, user_id }) => {
    const [text, setText] = React.useState("");
    const textRef = useRef(null);

    return (
        <div>
            <form
                className="flex flex-wrap -mx-2"
                method="POST"
                action="/tasks/add-message"
            >
                <input type="hidden" name="_token" value={token} />
                <input type="hidden" name="task_id" value={task_id} />
                <input type="hidden" name="user_id" value={user_id} />
                <div className="w-full px-2 md:w-1/4">
                    <div className="user_image" />
                </div>
                <div className="w-full px-2 md:w-7/12">
                    <div className="mb-4">
                        <textarea
                            ref={textRef}
                            style={{ height: "100px" }}
                            name="message"
                            className="form-input"
                            onChange={(event) => setText(event.target.value)}
                            placeholder="Mensage"
                        />
                    </div>
                </div>
                <div className="w-full px-2 md:w-2/12">
                    <button
                        type="submit"
                        onClick={() => {
                            addItem(text);
                            setText("");
                            textRef.current.value = "";
                        }}
                        disabled={text === ""}
                        className="btn-primary"
                    >
                        +
                    </button>
                </div>
            </form>
        </div>
    );
};

class MessageTask extends Component {
    constructor(props) {
        super(props);
        const token = JSON.parse(props.token);
        this.state = {
            items: [
                {
                    author: "Cesar",
                    text: "Djo constancia d elo que esta bien y oq que esta mal nadie puede decir nada",
                    date: "13/1/2020, 14:40hs",
                },
            ],
            token,
        };

        this.addItem = this.addItem.bind(this);
    }

    addItem(desc) {
        const items = this.state.items;
        items.push({
            text: desc,
            date: "12/12/12, 12:20hs",
            author: "Cesar",
        });
        this.setState({ items });
    }

    delItem(idx) {
        const items = this.state.items;
        items.splice(idx, 1);
        this.setState({ items });
    }

    render() {
        const { items, token } = this.state;
        return (
            <div>
                {items.length ? (
                    <div className="messages_task">
                        {items.map((e, i) => (
                            <div key={i}>
                                <Message
                                    text={e.text}
                                    author={e.author}
                                    date={e.date}
                                />
                            </div>
                        ))}
                    </div>
                ) : (
                    <p>Aun no hay mensages en esta tarea</p>
                )}

                <AddMessage addItem={this.addItem} token={token} />
            </div>
        );
    }
}

export default MessageTask;
