import React from "react";

function Spinner() {
    return (
        <div className="text-center">
            <div className="lds-grid my-5">
                <div />
                <div />
                <div />
                <div />
                <div />
                <div />
                <div />
                <div />
                <div />
            </div>
        </div>
    );
}

export default Spinner;
