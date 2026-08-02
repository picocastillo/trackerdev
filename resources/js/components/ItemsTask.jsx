import React, { Component, useRef } from "react";

const Item = ({ addItem }) => {
    const [text, setText] = React.useState("");
    const textRef = useRef(null);

    return (
        <div className="flex flex-wrap -mx-2">
            <div className="w-full px-2 md:w-10/12">
                <div className="mb-4">
                    <textarea
                        ref={textRef}
                        className="form-input"
                        onChange={(event) => setText(event.target.value)}
                        placeholder="Descripción"
                    />
                </div>
            </div>
            <div className="w-full px-2 md:w-2/12">
                <button
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
        </div>
    );
};

class ItemsTask extends Component {
    constructor(props) {
        super(props);
        let items = [];
        if (props.items) {
            items = JSON.parse(props.items);
        }
        this.state = {
            items,
        };

        this.addItem = this.addItem.bind(this);
    }

    addItem(desc) {
        const items = this.state.items;
        items.push({
            desc,
            complete: false,
        });
        this.setState({ items });
    }

    delItem(idx) {
        const items = this.state.items;
        items.splice(idx, 1);
        this.setState({ items });
    }

    render() {
        const { items } = this.state;
        return (
            <div>
                {items.length ? (
                    <ol>
                        {items.map((e, i) => (
                            <div key={i}>
                                <input
                                    className="hidden"
                                    type="hidden"
                                    name="items[]"
                                    value={e.desc}
                                />
                                <li className="py-1">
                                    {e.desc} &nbsp;&nbsp;&nbsp;
                                    <button
                                        type="button"
                                        onClick={() => {
                                            this.delItem(i);
                                        }}
                                        className="btn-danger btn-sm"
                                    >
                                        X
                                    </button>
                                </li>
                            </div>
                        ))}
                    </ol>
                ) : (
                    <p>Aun no hay items agregados</p>
                )}

                <Item addItem={this.addItem} />
            </div>
        );
    }
}

export default ItemsTask;
