// var store = 'https://www.jsonstore.io/1162c4c76a1b748d0d1218636a9b4cd333c2d6b58f0ecedf344f68d1254e02fa'
// var store = 'https://www.jsonstore.io/c1842f748d6118171b71d7538541ac9f8be122abdf0d2262e1c8177c0944914e'
// var store = 'https://www.jsonstore.io/3adce64d3bd81c6f3eaeb3eef30f006aea72dbcbb020b142ba3ca4763a272b5e';
var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");

var pts = [], k = 1;

// var zoom = d3.zoom()
//     .on("zoom", zoomed);

var x = d3.scaleLinear()
    .domain([-1, width + 1])
    .range([-1, width + 1]);

var y = d3.scaleLinear()
    .domain([-1, height + 1])
    .range([-1, height + 1]);

var xAxis = d3.axisBottom(x)
    .ticks((width + 2) / (height + 2) * 5)
    .tickSize(height)
    .tickPadding(8 - height);

var yAxis = d3.axisRight(y)
    .ticks(5)
    .tickSize(width)
    .tickPadding(8 - width);

var gX = svg.append("g")
    .attr("class", "axis axis--x")
    .call(xAxis);

var gY = svg.append("g")
    .attr("class", "axis axis--y")
    .call(yAxis);

var g = svg.append("g");

var path = g.append('path')
    .attr('fill', 'transparent')
    .attr('stroke', 'red');

// svg.call(zoom);

// fetch(store).then(r => r.json()).then(r => {
//     if (!r.result) return
//     pts = r.result
//     path.attr('d', 'M' + pts.join('L'));
//     pts.forEach(addPt)
// })

svg.on('click', e => {
    let pt = d3.mouse(g.node());
    pts.push(pt);
    pts.sort((p1, p2) => p1[0] - p2[0])
    addPt(pt)
    path.attr('d', 'M' + pts.join('L'));
    // post(pts)
});

// function post(points) {
//     fetch(store, {
//         headers: { 'Content-type': 'application/json' },
//         method: 'POST',
//         body: JSON.stringify(points),
//     });
// }

function addPt(pt) {
    g.append('circle')
        .attr('cx', pt[0])
        .attr('cy', pt[1])
        .attr('r', 0.001)
        .transition()
        .duration(300)
        .attr('r', 3 / k)
}

function zoomed() {
    k = d3.event.transform.k;
    g.attr("transform", d3.event.transform);
    gX.call(xAxis.scale(d3.event.transform.rescaleX(x)));
    gY.call(yAxis.scale(d3.event.transform.rescaleY(y)));
    path.attr('stroke-width', 1 / d3.event.transform.k)
    g.selectAll('circle').attr('r', 3 / d3.event.transform.k)
}