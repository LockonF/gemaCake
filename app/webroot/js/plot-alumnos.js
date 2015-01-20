
$.get('getDatosAlumno', function(raw_data){
    raw_data = jQuery.parseJSON(raw_data);

    var dataAvances = {
        labels: raw_data['avances']['labels'],
        datasets: [

            {
                label: "Periodo Actual",
                fillColor: "rgba(207, 37, 66, 0.2)",
                strokeColor: "rgba(207, 37, 66, 1)",
                pointColor: "rgba(207, 37, 66, 1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(151,187,205,1)",
                data: raw_data['avances']['periodos']['actual']
            },
            {
                label: "Periodo Anterior",
                fillColor: "rgba(66,139,202,0.2)",
                strokeColor: "rgba(66,139,202,0.5)",
                pointColor: "rgba(66,139,202,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: raw_data['avances']['periodos']['anterior']
            }
        ]
    };

    var optionsAvances = {

        //Boolean - Whether we show the angle lines out of the radar
        angleShowLineOut : false,

        //Boolean - Whether to show labels on the scale
        scaleShowLabels : false,

        // Boolean - Whether the scale should begin at zero
        scaleBeginAtZero : true,

        //String - Colour of the angle line
        angleLineColor : "rgba(0,0,0,.1)",

        //Number - Pixel width of the angle line
        angleLineWidth : 10,

        //String - Point label font declaration
        pointLabelFontFamily : "'Arial'",

        //String - Point label font weight
        pointLabelFontStyle : "normal",

        //Number - Point label font size in pixels
        pointLabelFontSize : 12,

        //String - Point label font colour
        pointLabelFontColor : "#666",

        //Boolean - Whether to show a dot for each point
        pointDot : true,

        //Number - Radius of each point dot in pixels
        pointDotRadius : 5,

        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth : 1,

        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius : 10,

        //Boolean - Whether to show a stroke for datasets
        datasetStroke : true,

        //Number - Pixel width of dataset stroke
        datasetStrokeWidth : 5,

        //Boolean - Whether to fill the dataset with a colour
        datasetFill : true,

        //String - A legend template

    }

    var dataPromedios = {
        labels: raw_data['promedios']['labels'],
        datasets: [
            {
                label: "Promedios",
                fillColor: "rgba(183,204,20,0.2)",
                strokeColor: "rgba(183,204,20,1)",
                pointColor: "rgba(100,100,100,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "rgba(183,204,20,1)",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: raw_data['promedios']['data'].reverse()
            },

        ]
    };


    var optionsPromedios ={

        ///Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines : false,



        //String - Colour of the grid lines
        scaleGridLineColor : "rgba(0,0,0,.05)",

        //Number - Width of the grid lines
        scaleGridLineWidth : 1,


        //Boolean - Whether the line is curved between points
        bezierCurve : true,

        //Number - Tension of the bezier curve between points
        bezierCurveTension : 0.4,

        //Boolean - Whether to show a dot for each point
        pointDot : true,

        //Number - Radius of each point dot in pixels
        pointDotRadius : 8,

        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth : 1,

        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius : 20,

        //Boolean - Whether to show a stroke for datasets
        datasetStroke : true,

        //Number - Pixel width of dataset stroke
        datasetStrokeWidth : 5,

        //Boolean - Whether to fill the dataset with a colour
        datasetFill : false,




    };

    var ctxAvances = $("#myChart").get(0).getContext("2d");
    var ctxPromedios = $("#grafica-promedios").get(0).getContext("2d");

    var myRadarChart = new Chart(ctxAvances).Radar(dataAvances, optionsAvances);
    var myLineChart = new Chart(ctxPromedios).Line(dataPromedios, optionsPromedios);



});