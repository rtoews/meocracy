/*
    Local chart functions for tapping Google Charts
*/

    var Charts = {
        question: '',
        support: 0,
        oppose: 0,
        votes: 0,
        views: 0,
    };
    Charts.drawVotesChart = function() {
console.log('Charts.drawVotesChart');
            var data = new google.visualization.DataTable();
            data.addColumn("string", "Votes");
            data.addColumn("number", "Support");
            data.addColumn("number", "Oppose");
            data.addRows([
                ["", Charts.support, Charts.oppose],
            ]);
            var options = {
                animation:{duration: 2000},
                legend: {position:"none"},
                axisTitlesPosition:"none",
                width:260, height:70,
                title: Charts.question,
                titleTextStyle: { color: 'white' },
                hAxis: { textStyle: { color: 'white' }},
                colors:["#5cbc6a","#ee2700"],
                backgroundColor:"transparent",
                vAxis:{ textPosition:"none" },
                isStacked:"false"
            };
            var chart = new google.visualization.BarChart(document.getElementById("chart_feedback"));
            chart.draw(data, options);
        };

    Charts.drawTotalsChart = function() {
console.log('Charts.drawTotalsChart');
            var data = new google.visualization.DataTable();
            data.addColumn("string", "Totals");
            data.addColumn("number", "Votes");
            data.addColumn("number", "Views");
            data.addRows([
                ["", Charts.votes, Charts.views],
            ]);
            var options = {
                animation:{duration: 2000},
                legend: {position:"none"},
                axisTitlesPosition:"none",
                width:260, height:70,
                title: "Voting popularity",
                titleTextStyle: { color: 'white' },
                hAxis: { textStyle: { color: 'white' }},
                colors:["#5A80C6","#8C8C8C"],
                backgroundColor:"transparent",
                vAxis:{ textPosition:"none" },
                isStacked:"false"
            };
            var chart = new google.visualization.BarChart(document.getElementById("chart_total"));
            chart.draw(data, options);
        };


