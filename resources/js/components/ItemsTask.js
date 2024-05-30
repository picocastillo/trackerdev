import React, { Component, useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';

import {RISKS} from '../utils'

const Item = ({addItem}) => {
    const [text, setText] = React.useState('');
    let textRef = useRef(null)

    return (
        <div className="row">
            <div className="col-10">
                <div className="form-group">
                    <textarea ref={textRef} type="email" className="form-control" onChange={(asd) => setText(asd.target.value)} placeholder="Descripción"></textarea>
                </div>

            </div>
            <div className="col-2">

            <button onClick={() => {addItem(text);setText('');textRef.current.value=""}} disabled={text==''} className="btn btn-primary">+</button>
            </div>
        </div>
    )
}

class ItemsTask extends Component {
    constructor(props){
        super(props);
        console.log(JSON.parse(props.items))
        let items = [];
        if (props.items){
            items = JSON.parse(props.items);
        }
        this.state = {
            items
        }

        this.addItem = this.addItem.bind(this)
        
    }
    
    addItem(desc){
        let items = this.state.items;
        items.push({
            desc,
            complete: false
        })
        this.setState({items})
    }


    delItem(idx){
        let items = this.state.items;
        items.splice(idx,1)
        this.setState({items})
    }

    
      

    render() {
        const {items} = this.state
        return (
            <div>
                {
                    items.length ? 
                    (
                        <ol>
                           {
                                items.map( (e,i) => {
                                    return (
                                        <div>
                                            <input className="d-none" type="hidden"name="items[]" value={e.desc} ></input>
                                            <li className="py-1" key={i} >{e.desc} &nbsp;&nbsp;&nbsp;<button onClick={() => {this.delItem(i)}} className="btn btn-danger btn-sm" >X</button> </li>
                                        </div>
                                    )
                                })
                           }
                        </ol>
                    )
                    :
                    (
                        <p>Aun no hay items agregados</p>
                    )
                }
                

                <Item addItem={this.addItem} />
            </div>
        )
    };
}

export default ItemsTask;

if (document.getElementById('create_task')) {
    const token = document.getElementById('create_task').getAttribute('token')
    const objetives = document.getElementById('create_task').getAttribute('objetives')
    const tasks = document.getElementById('create_task').getAttribute('tasks')
    const time = document.getElementById('create_task').getAttribute('time')
    const items = document.getElementById('create_task').getAttribute('items')
    ReactDOM.render(<ItemsTask token={token} tasks={tasks} items={items} objetives={objetives} time={time} />, document.getElementById('create_task'));
}
