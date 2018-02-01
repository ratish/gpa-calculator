//Instantiate vee-validate plugin
Vue.use(VeeValidate);
var app = new Vue({
    el: '#semesterGPAApp',
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
            gpaClass: 'box-grey',
        },
        totalCourse: 1,
    },
    methods:{
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
            --this.totalCourse;
            this.calculateGPA();
        },
        calculateGPA: function(){
            this.semester = this._calculateGPA();
        },
        _calculateGPA: function(){
            const semester = this.getSemesterTotal();
            const semesterGPA = (semester.gpa / semester.gpaHours).toFixed(3);
            const gpaClass = this._getGPAClass(semesterGPA);
            return (semesterGPA > -1 && semester.totalHours > -1) ?
                {
                    hours: semester.totalHours,
                    gpa: semesterGPA,
                    gpaClass: gpaClass,
                }
                :
                {
                    hours: '?',
                    gpa: '?',
                    gpaClass: gpaClass
                };
        },
        getSemesterTotal: function(){
            return this.courses.reduce(function(semester, course){
                const creditHour = parseFloat(course.creditHour);
                const grade = parseFloat(course.grade);
                semester.totalHours += creditHour;
                if (grade > -1) {
                    semester.gpaHours += creditHour;
                    semester.gpa += (creditHour * grade);
                }

                return semester;
            }, {totalHours: 0, gpa: 0, gpaHours:0});
        },
        _getGPAClass: function(gpa){
            if (gpa >= 2.5) {
                return 'box-green';
            }
            if (gpa >= 2 && gpa < 2.5) {
                return 'box-yellow';
            }
            if (gpa < 2) {
                return 'box-red';
            }

            return 'box-grey';
        }
    },
    watch: {
        'courses':
        {
            handler: function(){
                this.calculateGPA();
            },
            deep: true
        },
    },
});

var app = new Vue({
    el: '#targetGPAApp',
    data: {
        totalHours: '',
        currentGPA: '',
        targetGPA: '',
        result: '',
    },
    methods:{
        getTargetGPA: function(){
            const RESULT_JOIN = '<br> OR <br>';
            this.$validator.validateAll().then((result) => {
                if (result) {
                    const result = this._calculateTargetGPA(RESULT_JOIN);

                    this.result = this._formatOutputHTML(result, RESULT_JOIN);
                    return;
                }
            });
        },
        _calculateTargetGPA: function(join){
            const MAX_HOURS = 160;
            const MAX_GPA = 4.0;
            let result = '';
            let prevHours = this._calculateHours(MAX_GPA);
            let gpaList = [];
            for (let gpa = MAX_GPA; gpa >= 0; gpa -= 0.1) {
                let requiredHours = this._calculateHours(gpa);
                if (requiredHours > MAX_HOURS || requiredHours < 1) {
                    break;
                }

                let requiredGPA = this._formatGPA(gpa);
                if (requiredHours > 0) {
                    if (prevHours !== requiredHours) {
                        result += this._requiredHoursHTML(prevHours, gpaList) + join;
                        gpaList = [];
                        gpaList.push(requiredGPA);
                    } else {
                        gpaList.unshift(requiredGPA);
                    }
                }
                prevHours = requiredHours;
            }
            //Add the last one to the list since the last will not be added in the loop before exiting the loop
            result += this._requiredHoursHTML(prevHours, gpaList) + join;

            return result;
        },
        _calculateHours: function(max_gpa){
            const totalHours = parseInt(this.totalHours);
            const currentGPA = parseFloat(this.currentGPA);
            const targetGPA = parseFloat(this.targetGPA);

            return Math.ceil((currentGPA - targetGPA) * totalHours / (targetGPA - max_gpa));
        },
        _formatGPA: function(gpa){
            //format GPA to 2 decimal places
            return parseFloat(Math.round(gpa * 100) / 100).toFixed(3);
        },
        _requiredHoursHTML: function(requiredHours, gpaList){
            const addSToHour = (requiredHours > 1) ? 's' : '';

            return '<span class="text-info">' + requiredHours +
                            ' hour' + addSToHour + ' with a GPA of ' + gpaList.join(' or ') + '.</span>';
        },
        _formatOutputHTML: function(result, join){
            let output = '';
            if (result !== '') {
                result = this._removeOrphanORFromResult(result, join);
                output = '<h5>To achieve your target GPA of ' + this.targetGPA  +  ', you will need :</h5><h6>' +  result + '</h6>';
            } else {
                output = '<h5 class="text-danger">Result cannot be calculated.</h5>'
            }

            return output;
        },
        _removeOrphanORFromResult: function(result, join){
            //Remove the orphan OR at the end of the result string
            return result.substring(-1, result.lastIndexOf(join));
        }
    },
});

var app = new Vue({
    el: '#reverseGPAApp',
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
        semesterGPA: '',
        totalSemesterHours: '',
        totalCourse: 1,
    },
    methods:{
        addRow: function(){
            this.courses.push({
                id: ++this.totalCourse,
                title: '',
                creditHour: '',
                grade: '',
            });
        },
        removeRow: function(index){
            this.courses.splice(index, 1);
            --this.totalCourse;
        },
        calculateReverseGPA: function(){
            this.totalSemesterHours = this._gettotalSemesterHours();

            this._calculateCumulativeAndAssignGrades();
        },
        _calculateCumulativeAndAssignGrades: function(){
            const gradeInfo = this._getGradeInfo(this.courses);
            const totalSemesterHours = this.totalSemesterHours;
            const totalCumulativePoints = totalSemesterHours * this.semesterGPA;

            this._assignLetterToEmptyGrades(gradeInfo, totalSemesterHours, totalCumulativePoints);
        },
        _assignLetterToEmptyGrades: function(gradeInfo, totalSemesterHours, totalCumulativePoints){
            let cumulativePointsDiff = totalCumulativePoints - gradeInfo.cumulativePoints;
            let hoursDiff = totalSemesterHours - gradeInfo.totalHours;
            if (cumulativePointsDiff / hoursDiff > 4) {
                alert('It is not possible to achieve this result with the given values.');
            } else {
                const emptyGradeList = gradeInfo.emptyGradeList;
                const emptyListLength = emptyGradeList.length;
                for (i = 0; i < emptyListLength; i++) {
                    let gradeAvg = Math.ceil(cumulativePointsDiff / hoursDiff);
                    let currentIndex = emptyGradeList[i];
                    this.courses[currentIndex].grade = gradeAvg.toFixed(1);
                    cumulativePointsDiff -= gradeAvg * this.courses[currentIndex].creditHour;
                    hoursDiff -= this.courses[currentIndex].creditHour;
                }
            }
        },
        _getGradeInfo: function() {
            let gradeInfo = {
                emptyGradeList:[],
                cumulativePoints: 0,
                totalHours: 0,
            };
            const loopLimit = this.totalCourse;
            for (var i = 0; i < loopLimit; i++) {
                let grade = parseFloat(this.courses[i].grade);
                if (! grade) {
                    grade = '';
                }
                let hours = this.courses[i].creditHour;
                if (grade === '') {
                    gradeInfo.emptyGradeList.push(i);
                } else {
                    gradeInfo.cumulativePoints += grade * hours;
                    gradeInfo.totalHours += hours * 1.0;
                }
            }

            return gradeInfo;
        },
        _gettotalSemesterHours: function(){
            return this.courses.reduce(function(totalHours, course){
                let creditHour = parseFloat(course.creditHour);
                if (! isNaN(creditHour)) {
                    return totalHours += creditHour;
                }
            }, 0);
        },

        calculateGPA: function(){
            const semesterTotal = this._calculateGPA();
            this.semesterGPA = semesterTotal.gpa;
            this.totalSemesterHours = semesterTotal.hours;

        },
        _calculateGPA: function(){
            const semester = this.getSemesterTotal();
            const semesterGPA = (semester.gpa / semester.gpaHours).toFixed(3);
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
        },
        getSemesterTotal: function(){
            return this.courses.reduce(function(semester, course){
                const creditHour = parseFloat(course.creditHour);
                const grade = parseFloat(course.grade);
                semester.totalHours += creditHour;
                if (grade > -1) {
                    semester.gpaHours += creditHour;
                    semester.gpa += (creditHour * grade);
                }

                return semester;
            }, {totalHours: 0, gpa: 0, gpaHours:0});
        },
    },
});