var queryString = location.href.split('?');
queryString = (queryString[1] !== undefined) ? '?' + queryString[1] : '';

d3.json('/get_metrics.php' + queryString, function (json) {
    var data = [];
    json.commits.forEach(function (commit) {
        data.push({
            'id': commit.commitHash,
            'date': new Date(commit.date * 1000),
            'author': commit.author,
            'message': commit.message.match('.+?\n'),
            'ratio': commit.changedFiles.testCodeRatio,
            'size': commit.changedFiles.changes,
            'files': commit.changedFiles.changedFiles.length
        });
    });

    // configure the bubble chart
    var svg = dimple.newSvg("#test-ratio-per-commit", "95%", "95%");
    var chart = new dimple.chart(svg, data);
    chart.setBounds(50, 35, 975, 425);
    chart.addCategoryAxis("x", ["date"]);
    chart.addMeasureAxis("y", "ratio");
    chart.addLogAxis("z", "size", .1);
    chart.axes[0].timeField = "date";
    chart.axes[0].timePeriod = d3.time.days;
    chart.axes[0].timePeriod.timeInterval = 1;

    chart.addSeries(["message", "id", "files", "author"], dimple.plot.bubble);
    // draw legend
    var myLegend = chart.addLegend("100%,10px", 100, 60, 300, "Left");
    chart.draw();
    // This is a critical step.  By doing this we orphan the legend. This
    // means it will not respond to graph updates.  Without this the legend
    // will redraw when the chart refreshes removing the unchecked item and
    // also dropping the events we define below.
    chart.legends = [];
    // Get a unique list of Owner values to use when filtering
    var filterValues = dimple.getUniqueValues(data, "author");
    // Get all the rectangles from our now orphaned legend
    // Add a click event to each rectangle
    myLegend.shapes.selectAll("rect").on("click", function (e) {
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
        chart.draw(window.innerWidth);
    });

    // average the ratios
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
    chart2.setBounds(50, 35, 975, 425);
    chart2.addCategoryAxis("x", "author");
    chart2.addMeasureAxis("y", "average");
    chart2.axes[1].tickFormat = ',';
    chart2.axes[1].title = 'average ratio';
    chart2.addSeries(["author", "commits", "average"], dimple.plot.bar);
    chart2.draw();
});
