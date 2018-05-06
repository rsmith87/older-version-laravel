import React, { Component } from 'react';
import ReactDOM from 'react-dom';

var Clock = React.createClass({
  propTypes: {
    totalSeconds: React.PropTypes.number
  },
  getDefaultProps: function () {
      totalSeconds: 0
  },
  
  formatSeconds: function (totalSeconds) {
    var seconds = totalSeconds % 60;
    var minutes = Math.floor(totalSeconds / 60);
     
    if (seconds < 10) {
      seconds = '0' + seconds;
    }

    if (minutes < 10) {
      minutes = '0' + minutes;
    }

    return minutes + ":" + seconds;
  },
  
 render: function() {
     var {totalSeconds} = this.formatSeconds(this.props);

      return (
        <div className="clock">
          <span className="clock-text">
            {this.formatSeconds(this.props.totalSeconds)}
          </span>
        </div>
      );
  }
});

if (document.getElementById('timer')) {
    ReactDOM.render(<Clock />, document.getElementById('timer'));
}


