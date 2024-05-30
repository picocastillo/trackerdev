import React from 'react';


function Fatal(props) {
  return(
    <div className="center">
      <h3> Existe un error, mas precisamente: </h3> <br/>
      <i>{props.message}</i>
    </div>
  )
}

export default Fatal;
