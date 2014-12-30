var queryString = location.href.split('?');
queryString = (queryString[1] !== undefined) ? '?' + queryString[1] : '';

d3.json('/get_metrics.php' + queryString, function (err, json) {

    if (json === undefined) {
        alert(JSON.parse(err.response).error);
        return;
    }

    var data = [];
    json.commits.forEach(function (commit) {
        data.push({
            'id': commit.commitHash,
            'date': new Date(commit.date * 1000),
            'author': commit.author,
            'message': commit.message,
            'ratio': commit.changedFiles.testCodeRatio,
            'size': commit.changedFiles.changes,
            'files': commit.changedFiles.changedFiles.length
        });
    });

    if (data.length < 2) {
        alert('You need to have at least two commits in the queried range');
        return;
    }

    var transitionTime = 1000;
    var margin = {top: 75, right: 75, bottom: 75, left: 75};
    var width = parseInt(d3.select('#test-ratio-per-commit').style('width'), 10)
        - margin.left - margin.right;
    
    var timePeriod = d3.time.days;
    var firstDate = moment(data[0].date);
    var lastDate = moment(data[data.length - 1].date);
    var diff = firstDate.diff(lastDate, 'days');
    if (diff > width/4) {
        timePeriod = d3.time.weeks;
        diff = firstDate.diff(lastDate, 'weeks');
        if (diff > width/4) {
            timePeriod = d3.time.years;
        } else {
            timePeriod = d3.time.months;
        }
    }

    // configure the bubble chart
    var svg = dimple.newSvg("#test-ratio-per-commit", "95%", "95%");
    var chart = new dimple.chart(svg, data);
    chart.setBounds(margin.left, margin.top, width - 250, 425);
    chart.addCategoryAxis("x", ["date"]);
    chart.addMeasureAxis("y", "ratio");
    chart.addLogAxis("z", "size", .1);
    chart.axes[0].timeField = "date";
    chart.axes[0].title = null;
    chart.axes[0].timePeriod = timePeriod;
    chart.axes[0].timePeriod.timeInterval = 1;
    chart.axes[0].tickFormat = '%x';
    chart.axes[0].showGridlines = true;
    chart.addSeries(["message", "id", "files", "author"], dimple.plot.bubble);

    // average the ratios per day
    var averagesPerDay = [];
    data.forEach(function(commit) {
        averagesPerDay.push({
            "date": new Date(commit.date.getFullYear(), commit.date.getMonth(), commit.date.getDate() + 1, 0,0,0,0),
            "ratio": commit.ratio
        });
    });
    chart.addSeries(['Average Ratio'], dimple.plot.line);
    chart.series[1].data = averagesPerDay;
    chart.series[1].aggregate = dimple.aggregateMethod.avg;
    chart.series[1].lineMarkers = true;

    // draw legend
    var myLegend = chart.addLegend(width - 150, 75, 225, 425);
    chart.draw(transitionTime);
    // make all labels vertical
    chart.axes[0].shapes.selectAll('text').attr('transform', 'translate(14,35)rotate(90)');

    // This is a critical step.  By doing this we orphan the legend. This
    // means it will not respond to graph updates.  Without this the legend
    // will redraw when the chart refreshes removing the unchecked item and
    // also dropping the events we define below.
    chart.legends = [];
    // Get a unique list of Owner values to use when filtering
    var filterValues = dimple.getUniqueValues(data, "author");
    // Add a click event to each author name
    myLegend.shapes.selectAll("text").on("click", function (e) {

        if (e.key === 'Average Ratio') return;

        // This indicates whether the item is already visible or not
        var hide = false;
        var newFilters = [];
        // If the filters contain the clicked shape hide it
        filterValues.forEach(function (f) {
            if (f === e.aggField.slice(-1)[0]) {
                hide = true;
            } else {
                newFilters.push(f);
            }
        });
        // Hide the shape or show it
        if (hide) {
            d3.select(this).style("opacity", 0.2);
        } else {
            newFilters.push(e.aggField.slice(-1)[0]);
            d3.select(this).style("opacity", 0.8);
        }
        // Update the filters
        filterValues = newFilters;
        // Filter the data
        chart.data = dimple.filterData(data, "author", filterValues);

        // average the ratios per day
        var filteredAveragesPerDay = [];
        chart.data.forEach(function(commit) {
            if (hide && commit.author === e.key) {
                // dont include
            } else {
                filteredAveragesPerDay.push({
                    "date": new Date(commit.date.getFullYear(), commit.date.getMonth(), commit.date.getDate() + 1, 0, 0, 0, 0),
                    "ratio": commit.ratio
                });
            }
        });
        chart.series[1].data = filteredAveragesPerDay;
        chart.draw(transitionTime);

    });

    // average the ratios per author
    var averages = {};
    data.forEach(function(commit) {
        if (!averages[commit.author]) {
            averages[commit.author] = {
                'author': commit.author,
                'ratio': 0,
                'commits': 0,
                'average': 0
            };
        }
        averages[commit.author].ratio += parseFloat(commit.ratio);
        averages[commit.author].commits++;
        averages[commit.author].average = averages[commit.author].ratio / averages[commit.author].commits;
    });
    var data2 = [];
    for (var commit in averages) {
        data2.push(averages[commit]);
    };
    // configure the bar chart
    var svg2 = dimple.newSvg("#test-ratio-on-average", "95%", "95%");
    var chart2 = new dimple.chart(svg2, data2);
    chart2.setBounds(margin.left, margin.top, width - 75, 425);
    chart2.addCategoryAxis("x", "author");
    chart2.addMeasureAxis("y", "average");
    chart2.axes[1].tickFormat = ',';
    chart2.axes[1].title = 'average ratio';
    chart2.addSeries(["author", "commits", "average"], dimple.plot.bar);
    chart2.draw(transitionTime);
});
