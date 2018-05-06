import React, { Component } from 'react';
import ReactDom from 'react-dom';
import axios from 'axios';

import moment from 'moment'
    export default {
        data() {
            return {
                projects: null,
                newTimerName: '',
                newProjectName: '',
                activeTimerString: 'Calculating...',
                counter: { seconds: 0, timer: null },
            }
        },
        created() {
            window.axios.get('/projects').then(response => {
                this.projects = response.data
                window.axios.get('/project/timers/active').then(response => {
                    if (response.data.id !== undefined) {
                        this.startTimer(response.data.project, response.data)
                    }
                })
            })
        },
        methods: {



            /**
             * Calculate the amount of time spent on the project using the timer object.
             */
            calculateTimeSpent: function (timer) {
                if (timer.stopped_at) {
                    const started = moment(timer.started_at)
                    const stopped = moment(timer.stopped_at)
                    const time = this._readableTimeFromSeconds(
                        parseInt(moment.duration(stopped.diff(started)).asSeconds())
                    )
                    return `${time.hours} Hours | ${time.minutes} mins | ${time.seconds} seconds`
                }
                return ''
            },

            /**
             * Determines if there is an active timer and whether it belongs to the project
             * passed into the function.
             */
            showTimerForProject: function (project, timer) {
                return this.counter.timer &&
                       this.counter.timer.id === timer.id &&
            },

            /**
             * Start counting the timer. Tick tock.
             */
            startTimer: function (project, timer) {
                const started = moment(timer.started_at)

                this.counter.timer = timer
                this.counter.timer.project = project
                this.counter.seconds = parseInt(moment.duration(moment().diff(started)).asSeconds())
                this.counter.ticker = setInterval(() => {
                    const time = this._readableTimeFromSeconds(++vm.counter.seconds)

                    this.activeTimerString = `${time.hours} Hours | ${time.minutes}:${time.seconds}`
                }, 1000)
            },

        },
    }

var Timer = React.createClass({
    

  
  getDefaultProps: function() {
    return {
      seconds: 0,
      timerName: '',      
      counter: { seconds: 0, timer: null },
      timers: null
    };
  },
  
  getInitialState: function() {
    axios.get('/timers').then(response => {
      this.timers = response.data
      if(this.timers) {
        window.axios.get('/timers/active').then(response => {
          if (response.data.id !== undefined) {
            this._handleState(response.data)
          }
        })
      }
     });  
    

	},
  // timer interval 
  timer: '',
  _handleState: function(data) {
    return {
			timeDisplay: {
        timerName: data.name,
				seconds: data.seconds
			},
			timerStarted: 0,
			timerStoped: 0,
			timerEnded: 0,
      counter: { seconds:data.seconds, timer: data.counter.timer},
    };    
  },

/**
 * Splits seconds into hours, minutes, and seconds.
 */
  _readableTimeFromSeconds: function(seconds) {
    const hours = 3600 > seconds ? 0 : parseInt(seconds / 3600, 10)
    return {
      hours: this._padNumber(hours),
      seconds: this._padNumber(seconds % 60),
      minutes: this._padNumber(parseInt(seconds / 60, 10) % 60),
    }
  },
	_handleTimerStart: function(data) {
		console.log('Timer Started');
    
    if (this.state.timerEnded) {
			this._handleTimerReset();
			return
		}

		this.setState({ 
			timerStarted: 1,
			timerStopped: 0,
			timerEnded: 0
		});

		var self = this;
		var time = this.state.timeDisplay;
    
   
    axios.post(`/timers`, {name: this.newTimerName})
      .then(response => {
          timers.push(response.data)
          this.startTimer(response.data.project, response.data)
      })

    this.newTimerName = '';
   },
    
    

		this.timer = setInterval(function() {
				time.seconds++;
        this.counter++;
				self.setState({
					timeDisplay: time
				});
		  if (time.seconds > 59) {
				time.minutes++;
				time.seconds = 0;
				self.setState({
					timeDisplay: time
				});
			} else  if (time.seconds > 59 && time.minutes > 59){
        time.hours++
        time.minutes = 0;
        time.seconds = 0;
        self.setState({
          timeDisplay: time
        });      
      } else {
				self.setState({
          timeDisplay: time
        });
			}
			// console.log(self.state.timeDisplay);
		}, 1000);
	},

	_handleTimerStop: function() {
		console.log('Timer Stopped');

    
    axios.post(`/timers/${this.timer.id}/stop`)
      .then(response => {
        // Loop through the projects and get the right project...
        this.projects.forEach(project => {
          if (project.id === parseInt(this.counter.timer.project.id)) {
            // Loop through the timers of the project and set the `stopped_at` time
            return project.timers.forEach(timer => {
              if (timer.id === parseInt(this.counter.timer.id)) {
                return timer.stopped_at = response.data.stopped_at
              }
              })
            }
          });

          // Stop the ticker
          clearInterval(this.counter.ticker)

          // Reset the counter and timer string
          this.counter = { seconds: 0, timer: null }
          this.activeTimerString = 'Calculating...'
      })
    clearInterval(this.timer);
		this.setState({ 
			timerStarted: 0, 
			timerStopped: 1 
		});
	},

	_handleTimerEnd: function() {
		console.log('Timer Ended');
		clearInterval(this.timer);
		this.setState({ 
			timerStarted: 0, 
			timerEnded: 1 
		});
	},
    
  _handleTimerCreate: function() {
    axios.post(`/timers`, {name: this.newTimerName})
      .then(response => {
        project.timers.push(response.data)
        this._handleTimerStart(response.data)
      })
      this.newTimerName = ''    
  },

	_handleTimerReset: function() {
		console.log('Timer Reset');
		var self = this;
    var hours = this.props.hours;
		var minutes = this.props.minutes;
		var seconds = this.props.seconds;
		this.setState({
			timeDisplay: {
        hours: hours,
				minutes: minutes,
				seconds: seconds
			},
			timerStarted: 0,
			timerStopped: 0,
			timerEnded: 0,
		});
	},

	_minTwoDigits: function(n) {
		return (n < 10 ? '0' : '') + n;
	},

	render: function() {

		// set display time to use at min 2 digits for all numbers
		var seconds = this._minTwoDigits(this.state.timeDisplay.seconds);
		var minutes = this._minTwoDigits(this.state.timeDisplay.minutes);
    var hours = this._minTwoDigits(this.state.timeDisplay.hours);
		// Set Timer State
		var timerState = this.state.timerStarted ? 'Running' : 
						(this.state.timerStopped ? 'Stopped' : 
						(this.state.timerEnded ? 'Ended' : 'Ready'));
    
    this.state.timerStarted ? '' : '';
    this.state.timerStopped ? '' : ''; 
		var ui = [
			<button onClick={this._handleTimerCreate}>Start</button>,
			<button onClick={this._handleTimerStop}>Stop</button>,
			<button onClick={this._handleTimerReset}>Reset</button>
		];

		return (
			<div className="timer">
				{ui}
				<hr/>
				<h2>Time</h2>
        <h3 className="time"><span>{hours}</span>:<span>{minutes}</span>:<span>{seconds}</span></h3>
				<hr/>
				<h3>{timerState}</h3>
			</div>
		);
	},

});

ReactDom.render(<Timer/>, document.getElementById('timer'));