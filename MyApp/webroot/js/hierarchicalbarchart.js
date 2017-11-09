/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/***********************************************************/
 // Change the items below as per application requirements
const xDataIsDate = true;
const barsForLevel1 = true;
const xAxisLabel1 = "Year-Month";
const xAxisLabel2 = "Day of ";
const yAxisLabel1 = "Number of Parts added to the Database";

var formatAsDay   = d3.timeFormat("%d");

var formatAsMonthNum = d3.timeFormat("%m");
var formatAsYear    = d3.timeFormat("%Y");
var formatAsYearMonth= d3.timeFormat("%Y-%m");
var formatAsMonth = d3.timeFormat("%b");
var formatAsDateWithoutTime = d3.timeFormat("%Y%m%d");

    function truncAtT(s) { // Use this function to remove the T_hh:mm:ss +hh:mm, but it will still give those hh:mm as 00:00
        var n = s.indexOf('T');
        return s.substring(0, (n !== -1 ? n : s.length));
    }
    
    function truncAfterMonth(s) {
        var n = 7;
        return s.substring(0, (s.length > n ? n : s.length));
    }

    var parsed = {}; // characters from JSON string are parsed into a propper json object with string elements
    var root =  {}; // the flat JSON object converted into the proper JSON hierarchical nested/structured object
    

    parsed = JSON.parse(data);
    
    // format the data
    parsed.forEach(function(d) {
          d.original = d.created;
          d.created = parseTime(truncAtT(d.created)); //parseTime(
          d.modified = truncAtT(d.modified); //parseTime(
          d.year_month = parseTime(truncAfterMonth(d.original)+"-01");
          d.month = formatAsMonth(parseTime(d.created)); 
      });
      
      parsed.sort(function(a, b) {
          return a.created - b.created;
      });
 console.log("parsed = ",parsed);      
 /* var parsed2 = d3.nest()  // Nesting is an option if the data is not naturally hierarchical
         // .key(function(d) { return d.year_month; })
          .key(function(d) { return d.created; })
          //.rollup(function(v) { return v.length; })
          .entries(parsed);
      
console.log("parsed2 = ",parsed2); 
*/
    // Assigns children, name
    // newData = zoomData(level); Moved inline to root

//console.log("newData = ",newData); 

    // Assigns parent, height, depth, and value is returned as the count of rows as default
    
    /***********************************************************/
    
    var margin = {top: 20, right: 20, bottom: 70, left: 40},
            width = 600 - margin.left - margin.right,
            height = 300 - margin.top - margin.bottom;

    var barWidth;

    var i = 0, duration = 750, delay = 25;

    var color = d3.scaleBand()
        .range(["steelblue", "#ccc"]);

    var chart = d3.select(".chart")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    chart.append("rect")
        .attr("class", "background")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .on("click", up);


    var div = d3.select("body").append("div")
        .attr("class", "tooltip")
        .style("opacity", 0);

    var xScale1 =  d3.scaleBand()
                    .range([0, width]); //.rangeRound([0, width])
    var xScale2 =  d3.scalePoint()
                    .range([0, width]);

    var xAxis;   

    var yScale = d3.scaleLinear()
        .range([height, 0]);

    var yAxis = d3.axisLeft(yScale).tickFormat(d3.format("d")); // ".nice() is not a function"
    zoomData(level);
    drawBars(root);
    formatChart();
    
/****************************************** FUNCTIONS ***************************/



function up(d) {
// reset level
    level = 0; 
  /*  zoomData(level);
    drawBars(root);
    formatChart();*/
    // Just load everything fresh
    window.location.replace("parts");//"http://localhost:8765/parts/");
}


function drawLine(d){
    chart.selectAll("g").remove();
    //chart.selectAll(".y").remove(); 
    //chart.selectAll(".x").remove();  
    //xScale.domain.remove();
    //console.log("d.length = "+d.length);
    // xScale.domain is to equal the serial numbers
    xScale2.domain(d.map(function(e,index) { if (e.data.dur_ea) { return index; } }));
    // yScale.domain is just restricted to the Values in the array for the selected bar
    
    yScale.domain([0, d3.max(d, function(f) { return f.value; })]).nice();
 
    var line = d3.line() // xScale.domain( [0,e.length] );
       // .attr("transform", function(e,index) { return "translate(" + xOffset + ",0)"; })
        .defined( function(e,index) { return e.data.dur_ea; } )
        .x( function(e,index) { return xScale2(index); } )  // set the x values for the line generator
        .y( function(e,index) { return yScale(e.value); } ); // set the y values for the line generator 
  
    // 9. Append the path, bind the data, and call the line generator 
    chart.append("path")
        .datum(d) // 10. Binds data to the line 
        .attr("class", "line") // Assign a class for styling 
        .attr("d", line); // 11. Calls the line generator 

    // 12. Appends a circle for each datapoint 
    chart.selectAll(".dot")
        .data(d.filter(function(e) { return e.data.dur_ea; }))
      .enter().append("circle") // Uses the enter().append() method
        .attr("class", "dot") // Assign a class for styling
        .attr("cx", line.x() )//function(e, index) { return xScale(index); }) //what is cx? x co-ordinate for the centre? i is the index of the data
        .attr("cy", line.y() )//function(e, index) { return yScale(e.value); })  // what is cy, y?
        .attr("r", 5)  
   //    This next section could be applied to either all of the bars or the datapoints of the path
     .on("mouseover", function(e, index) {        
           div.transition()
             .duration(200)
             .style("opacity", .9);
           div.html("Serial No. = " + e.data.serial_num +"<br/><strong>Step No. = " + e.data.step_num ) //formatTime(d.date)
             .style("left", (d3.event.pageX) + "px")
             .style("top", (d3.event.pageY - 28) + "px");
           })
        .on("mouseout", function(d) {
           div.transition()
             .duration(500)
             .style("opacity", 0);
           })
        .on("click", function(e,index) { 
            toggleLevel(); //level = 0; // Clicking on any circle will return the chart to the root level
            zoomData(level);
            if ( level===0 ) { 
                drawBars(root);
                formatChart();
            } 
        });
}

// Creates a set of bars for the given data node, at the specified index.
function drawBars(d) {
    //console.log("Drawbar d =",d);
    if (d.data.children.length === 0) return;
 
    chart.selectAll("g").remove();
    chart.selectAll("path").remove();
    chart.selectAll(".dot").remove();
    // xScale.domain is to equal the same used initially
    
   console.log("DrawBars, d.children= ", d.children);
    
    if ( level===0 ){    
        xScale1.domain( d.children.map(function(elem){ return elem.data.name; }) ); 
    } else {
        xScale1.domain( d.children.map(function(elem){ return elem.data.name; }) );
    }
    // yScal.domain is the same as that used initially               
    barWidth = width / root.children.map(function(elem){ return elem.data.name; }).length; 
    yScale.domain([0, d3.max(root.children, function(d) { return Math.max(5,d.value); })]).nice(); //only works in (brackets with call) - see formatChart()
                
    var bar = chart//.insert("g", ".y.axis")
     // .attr("class", "enter")
      .selectAll("g")
        .data(d.children)
        .enter().append("g")
        .attr("id", function(d,i) {return 'bar_' + i})
      .attr("transform", function(d,i) { return "translate(" + barWidth * i + ",0)"; });
        //.style("cursor", function(d) { return !d.children ? null : "pointer"; })     

    bar.append("rect")
        .attr("x", barWidth/6 )//function(d) { return xScale(d.base_id); }) //xScale(d.base_id)
        .attr("y", function(d) { return yScale(type(d.value)); }) //  d.dur_ea
        .attr("width", 2*barWidth/3)
        .attr("height", function(d) { return height - yScale(type(d.value)); }) //  d.dur_ea
        .on("mouseover", function(d, index) {
           div.transition()
             .duration(200)
             .style("opacity", .9);
    // console.log(d.data.children);
           div.html("Count = " ) //formatTime(d.date)
             .style("left", (d3.event.pageX) + "px")
             .style("top", (d3.event.pageY - 28) + "px");
           })
        .on("mouseout", function(d) {
           div.transition()
             .duration(500)
             .style("opacity", 0);
           })
        .on("click", function(e,index) { 
            if ( level === 0 ){
                //console.log("e.data = ", e.data); 
                var year = formatAsYear(e.data.name); //formatAsYear(e.children[i].data.name));  
                console.log("Year= ", year);       
                var month_num = formatAsMonthNum(e.data.name);
                var subsequent_month = parseInt(month_num)+1;
                console.log("Month_Num= ", month_num); 
                nameOfMonth = formatAsMonth(e.data.name);
                $('#date_begin').val(""+year+"-"+month_num+"-01");
                $('#date_end').val(""+year+"-"+subsequent_month+"-01");
                $('#target').submit();
            } else {
                window.location.replace("parts");
            }       
            
        });
      
}



function formatChart() {
 // Clear the old axis labels, hints and tool tips
    chart.selectAll(".y").remove(); 
    chart.selectAll(".x").remove();  
    toggleHint();
    
    // Design the x-Axis according to the domain set in drawBars/drawLine and format tick mark labels
    if ( level === 0 ){ 
      // Level 0 is always a barChart, and uses xScale1 which is a scaleBand
        xAxis = d3.axisBottom(xScale1).tickFormat(d3.timeFormat("%b"));
    } else if ( barsForLevel1) { 
        // If const determines a barChart is to be used for level 1, again use xScale1 which is a scaleBand
        xAxis = d3.axisBottom(xScale1).tickFormat(d3.timeFormat("%d/%m"));
    } else { 
        // Otherwise we are at level 1 and we are creating a lineChart
        xAxis = d3.axisBottom(xScale2);
    }

 // A - THE X-AXIS
    chart.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis);

    
    if ( level === 0 )  {
        // Don't add a special label at the root level as both the year and month are included in the tickmark labels
         appendXLabel(width/2, xAxisLabel1);
    } else {
        // Determine if More than 1 month is covered by the filtered range  
        if ( firstMonth !== nameOfMonth ){ // 
            // Add labels for the first and last month of the range, at either end
            appendXLabel(width/10, firstMonth);
            appendXLabel(9*width/10, nameOfMonth);
        } else {
            // If the data is all within one month, Just put a label for the month 

            appendXLabel( width/2, nameOfMonth );
        }
    }   
    // B - THE Y-AXIS 
    // Update the y-scale domain.
    //yScale.domain([0, d3.max(d.children, function(d) { return d.value; })]).nice();
  var numTick = 5;
    // Append the y-Axis
    chart.append("g")
        .attr("class", "y axis")
        .call(yAxis.ticks(numTick))
        //.select(".domain").remove()
        .selectAll(".tick text").attr("dx", -4)

      chart.append("text")
        .attr("class", "y axis")
        .attr("transform", "rotate(-90)")
        .attr("y",  - margin.left)
        .attr("x", - (height / 2))
        .attr("dy", ".8em") //0.71
        .style("text-anchor", "middle")
        .text(yAxisLabel1);  
}


function appendXLabel(xPosition, labelText){
    chart.append("text") 
        .attr("class", "x axis")
        .attr("transform",
                "translate(" + (xPosition) + " ," + 
                               (height + margin.top + 20) + ")")
        .style("text-anchor", "middle")
        .text(labelText);
}


function type(d) {
  d.value = +d.value; // coerce to number
  return d;
}


function zoomData(theLevel){
    
    //Build a hierarchy structure
    var newData = {"name": "root", "children": {}};
    var name;
    
    parsed.forEach(function (d) {
        name = (theLevel== 0 ? d.year_month : d.created); //
        console.log(name);
        if (typeof newData.children[name] !== 'undefined') {
            newData.children[name].children.push(d)
        } else {
            newData.children[name] = {"name": name, "children": [d]}
        }
    })
    newData.children = Object.keys(newData.children).map(function (key) {
        return newData.children[key];
    }); 
    
    // Create a d3 hierarchy object
    root = d3.hierarchy(newData, function(d) { return d.children; });


    // Create the 'Value' variable. Equals count of the children (rows of data in that group: level0 group by month; level1 group by day. Sort ascending or descending
    console.log("root pre-partitioning = ",root); 
    root.sum(function(d) { return  d.children? 0 : 1; }) // .children? 0 : d.dur_ea; for sum //d.children ? 0 : 1; for count     
        .sort(function(a,b) { console.log(a.name); return a.name - b.name; } ); //for asc return a.value - b.value; 
// Sort by a.value for pareto style chart
// Sort by a.name  

    var partition = d3.partition()
                      //.size([height, width // Use this version for horizontal bar charts
                      .size([width,height]) // This version for vertical bar charts
                      .padding(0);
    partition(root);
    
//console.log("root post-partitioning = ",root);  
}




function toggleLevel(){
    level ^= 1;
}

function toggleHint(){
        if (level === 0 ){ 
            chart.selectAll(".hint").remove();
            chart.append("text") 
                .attr("class", "hint")
                .attr("transform",
                        "translate(" + (width/2) + " ," + 
                                       (height+margin.bottom) + ")")
                .style("text-anchor", "middle")
                .text("Click on a bar to drill down into that data"); 
        } else {          
            chart.selectAll(".hint").remove();
            chart.append("text") 
                .attr("class", "hint")
                .attr("transform",
                        "translate(" + (width/2) + " ," + 
                                       (height+margin.bottom-4) + ")")
                .style("text-anchor", "middle")
                .text("Click anywhere in the chart to return to top level"); 
        }
}