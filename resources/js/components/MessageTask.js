import React, { Component, useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';


const Message = ({text,author,date}) => {
        return(
          <li className="list-group-item col-12 my-1 ">
              <div className="row text-dark">
                  <div className="col-4">
                     <img src={"https://lh3.googleusercontent.com/a-/AAuE7mD1u11ocX3MV0CBZudM_jRhUm_rSW6QHzMoXQzw=s96-k-no"} className="rounded-circle img-responsive" alt="" />
                  </div>
                  <div className="col-8">
                  <div className="p-2 text_comment" dangerouslySetInnerHTML={{__html: text}}>
                      {/* {
                                  props.isManager &&
                                  <button type="button" onClick={props.clickDelete} data-id={props.idx} className="btn btn-danger btn-xs pl-2" title="Delete">
                                    <i className="fa fa-trash-o "   aria-hidden="true"></i>
                                  </button>
      
                                } */}
                          </div>
                  </div>
                  <footer className="blockquote-footer pl-2 pt-2">Escrito por<cite title="Source Title">&nbsp;{author}&nbsp;&nbsp;&nbsp;&nbsp;{date}</cite></footer>
              </div>
          </li>
        )
}


const AddMessage = ({addItem, token, task_id, user_id}) => {
    const [text, setText] = React.useState('');
    let textRef = useRef(null)

    return (
        <div className="">
             <form className="row" method="POST" action={"/tasks/add-message"}>
                <input type="hidden" name="_token" value={token}></input>
                <input type="hidden" name="task_id" value={task_id}></input>
                <input type="hidden" name="user_id" value={user_id}></input>
                <div className="col-3">
                    <div className="user_image"></div>
                </div>
                <div className="col-7">
                    <div className="form-group">
                        <textarea ref={textRef} style={{height: '100px'}} name="message" type="text" className="form-control" onChange={(asd) => setText(asd.target.value)} placeholder="Mensage"></textarea>
                    </div>

                </div>
                <div className="col-2">

                <button type="submit" onClick={() => {addItem(text);setText('');textRef.current.value=""}} disabled={text==''} className="btn btn-primary">+</button>
                </div>
            </form>    
        </div>
    )
}

class MessageTask extends Component {
    constructor(props){
        super(props);
        const token = JSON.parse(props.token);
        let items = [];
        if (props.items){
            items = JSON.parse(props.items);
        }
        this.state = {
            items: [
                {
                    author: "Cesar",
                    text: "Djo constancia d elo que esta bien y oq que esta mal nadie puede decir nada",
                    date: "13/1/2020, 14:40hs"
                }
            ],
            token
        }

        this.addItem = this.addItem.bind(this)
        
    }
    
    addItem(desc){
        let items = this.state.items;
        items.push({
            text: desc,
            date: "12/12/12, 12:20hs",
            author: "Cesar"
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
                        <div  className="messages_task">
                           {
                                items.map( (e,i) => {
                                    return (
                                        <div>
                                            <input className="d-none" type="hidden"name="items[]" value={e.desc} ></input>
                                            <Message text={e.text} author={e.author} date={e.date}  />
                                        </div>
                                    )
                                })
                           }
                        </div>
                    )
                    :
                    (
                        <p>Aun no hay mensages en esta tarea</p>
                    )
                }
                

                <AddMessage addItem={this.addItem} />
            </div>
        )
    };
}

export default MessageTask;

if (document.getElementById('message_task')) {
    const token = document.getElementById('message_task').getAttribute('token')
    ReactDOM.render(<MessageTask token={token}  />, document.getElementById('message_task'));
}
