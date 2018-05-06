import React, { Component } from 'react';
import ReactDOM from 'react-dom';


var Timer = React.createClass({
  PropTypes: {
    timer: null,
    counter: 0,
  },
  componentDidMount: function() {
    let timer = setInterval(this.tick, 1000),
    this.setState({timer})
  },
  componentWillUnmount: function() {
   this.clearInterval(this.state.timer)
  },
  tick: function() {
    this.setState({
     counter: this.state.counter + 1
    })
  },
  render: function () {
    return (
      <div className="timer">
        Loading
        {
          "...".substr(0, this.state.counter % 3 + 1)
        }
      </div>
     )
  }
});

if (document.getElementById('timer')) {
  ReactDOM.render(<Timer />, document.getElementById('timer'));
}
