<template>
        <div class="no-projects" v-if="projects">

            <div class="row">
                <div class="col-sm-12">
                    <h2 class="pull-left project-title">Cases and timers</h2>
                </div>
            </div>

            <hr>

            <div v-if="projects.length > 0">
                <div class="panel panel-default" v-for="project in projects" :key="project.case_uuid">
                    <div class="panel-heading clearfix">
                        <h4 class="pull-left">{{ project.name }}</h4>

                        <button class="btn btn-success btn-sm pull-right" :disabled="counter.timer" data-toggle="modal" data-target="#timerCreate" @click="selectedProject = project">
                            <i class="glyphicon glyphicon-plus"></i>
                        </button>
                    </div>

                    
                    <div class="panel-body">
                      
                        <ul class="list-group" v-if="project.timers.length > 0">

                            <li v-for="timer in project.timers" :key="timer.id" class="list-group-item clearfix">
                                <strong class="timer-name">{{ timer.name }}</strong>
                                <div class="pull-right">
                                    <span v-if="showTimerForProject(project, timer)" style="margin-right: 10px">
                                        <strong>{{ activeTimerString }}</strong>
                                    </span>
                                    <span v-else style="margin-right: 10px">
                                        <strong>{{ calculateTimeSpent(timer) }}</strong>
                                    </span>
                                    <button v-if="showTimerForProject(project, timer)" class="btn btn-sm btn-danger" @click="stopTimer()">
                                        <i class="glyphicon glyphicon-stop"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <p v-else>Nothing has been recorded for "{{ project.name }}". Click the play icon to record.</p>
                    </div>
                </div>
                <!-- Create Timer Modal -->
                <div class="modal fade" id="timerCreate" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title">Record Time</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input v-model="newTimerName" type="text" class="form-control" id="usrname" placeholder="What are you working on?">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" v-bind:disabled="newTimerName === ''" @click="createTimer(selectedProject)" type="submit" class="btn btn-default btn-primary"><i class="glyphicon glyphicon-play"></i> Start</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="timers" v-else>
            Loading...
        </div>
</template> 

<script>
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
        window.axios.get('/dashboard/cases/timers_cases').then(response => {
          //var pathname = new URL(url).pathname;
          var pathname = window.location.pathname;
          var uuid = pathname.split("/");
          //uuid = uuid[4];
          if(uuid[4] && uuid[3] == 'case'){
            for(var i = 0; i < response.data.length; i++){
            if (response.data[i].case_uuid == uuid[4]){
               this.projects = [];
                        this.projects.push(response.data[i]);
                             //console.log(response.data[i].case_uuid + ": " + uuid[4]);

             }
            }
          } else {
            this.projects = response.data

          }
          console.log(this.projects);
            
            window.axios.get('/dashboard/cases/timers_cases/active').then(response => {
                if (response.data.id !== undefined) {
                    this.startTimer(response.data.lawcase, response.data)
                }
            })
        })
    },
    methods: {
        /**
         * Conditionally pads a number with "0"
         */
        _padNumber: number =>  (number > 9 || number === 0) ? number : "0" + number,

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
                   this.counter.timer.lawcase.case_uuid === project.case_uuid
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
                const time = this._readableTimeFromSeconds(++this.counter.seconds)

                this.activeTimerString = `${time.hours} Hours | ${time.minutes}:${time.seconds}`
            }, 1000)
        },

        /**
         * Stop the timer from the API and then from the local counter.
         */
        stopTimer: function () {
            window.axios.post(`/dashboard/cases/${this.counter.timer.id}/timers/stop`)
              .then(response => {
                // Loop through the projects and get the right project...
                this.projects.forEach(project => {
                  if (project.case_uuid === parseInt(this.counter.timer.law_case_id)) {
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
        },

        /**
         * Create a new timer.
         */
        createTimer: function (project) {
            window.axios.post(`/dashboard/cases/case/${project.case_uuid}/timers`, {name: this.newTimerName})
              .then(response => {
                  project.timers.push(response.data)
                  this.startTimer(response.data.project, response.data)
              })

            this.newTimerName = ''
        },

    },
}
</script>