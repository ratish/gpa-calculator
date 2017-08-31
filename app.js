//Instantiate vee-validate plugin
Vue.use(VeeValidate);
// https://codepen.io/izobiz/pen/vgeWaY?editors=1000

var app = new Vue({
    el: '#gpaCalculatorApp',
    data: {
        show: true,
        courses:[
            {
                id: 1,
                title: '',
                creditHour: '',
                grade: '',
            }
        ],
        semester:{
            hours: '?',
            gpa: '?',
        },
        totalCourse:1,
    },
    methods:{
        calculateGPA: function(){
            this.semester = this._calculateGPA();
        },
        showGPA: function(){
            this.show = true;
        },
        hideGPA: function(){
            this.show = false;
        },
        addRow: function(){
            this.courses.push({
                id: ++this.totalCourse,
                title: '',
                creditHour: '',
                grade: '',
            });
            this.calculateGPA();
        },
        removeRow: function(index){
            this.courses.splice(index, 1);
            this.calculateGPA();
        },
        getSemesterTotal: function(){
            return this.courses.reduce(function(semester, course){
                const creditHour= parseFloat(course.creditHour);
                const grade     = parseFloat(course.grade);
                semester.totalHours += creditHour;
                if (grade > -1) {
                    semester.gpaHours += parseFloat(creditHour);
                    semester.gpa += (creditHour * grade);
                }

                return semester;
            }, {totalHours: 0, gpa: 0, gpaHours:0});
        },
        _calculateGPA: function(){
            const semester      = this.getSemesterTotal();
            const semesterGPA   = (semester.gpa / semester.gpaHours).toFixed(2);
            return (semesterGPA > -1 && semester.totalHours > -1) ?
                {
                    hours: semester.totalHours,
                    gpa: semesterGPA,
                }
                :
                {
                    hours: '?',
                    gpa: '?',
                };
        }
    },
    watch: {
        'courses': {
            handler: function(){
                this.calculateGPA();
            },
            deep: true
        },
    },
    filters: {
        formatGPA: function(gpa){

            return gpa;
        }
    }
});