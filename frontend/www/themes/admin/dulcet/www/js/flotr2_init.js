//<![CDATA[
/*Basic
--------------------------------*/
(function () {

  var
	container = document.getElementById('graph_show_1'),
    d1 = [[0, 2], [1, 9], [5, 5], [9, 13], [24, 13]], // First data series
    d2 = [],                                // Second data series
    i, graph;

  // Generate first data set
  for (i = 0; i < 24; i += 0.5) {
    d2.push([i, Math.sin(i)]);
  }

  // Draw Graph
  graph = Flotr.draw(container, [ d1, d2 ], {
    xaxis: {
      minorTickFreq: 4
    }, 
    grid: {
      minorVerticalLines: true
    },
	colors: ['#2C93E1', '#6B8E23', '#C71585', '#CD5C5C', '#9440ED'],
	shadowSize: 0,
  });
})();
//]]>

//<![CDATA[
/*Basic Realtime
--------------------------------*/
(function () {

	var
	  container = document.getElementById('graph_show_2'),
	  start = (new Date).getTime(),
	  data, graph, offset, i;

	// Draw a sine curve at time t
	function animate (t) {

	  data = [];
	  offset = 3 * Math.PI * (t - start) / 15000;

	  // Sample the sine function
	  for (i = 10; i < 7 * Math.PI; i += 0.1) {
		data.push([i, Math.sin(i - offset)]);
	  }
	  
	  // Draw Graph
	  graph = Flotr.draw(container, [ data ], {
		yaxis : {
		  max : 4,
		  min : -2
		},
	  colors: ['#AA3FDF'],
	  shadowSize: 1
		
	  });

	  // Animate
	  setTimeout(function () {
		animate((new Date).getTime());
	  }, 50);
	}

	animate(start);
})();
//]]>


//<![CDATA[
/*Basic Axis
--------------------------------*/
(function () {

  var
	container = document.getElementById('graph_show_3'),
    d1 = [],
    d2 = [],
    d3 = [],
    d4 = [],
    d5 = [],                        // Data
    ticks = [[ 0, "Lower"], 10, 20, 30, [40, "Upper"]], // Ticks for the Y-Axis
    graph;
        
  for(var i = 0; i <= 10; i += 0.1){
    d1.push([i, 4 + Math.pow(i,1.5)]);
    d2.push([i, Math.pow(i,3)]);
    d3.push([i, i*5+3*Math.sin(i*4)]);
    d4.push([i, i]);
    if( i.toFixed(1)%1 == 0 ){
      d5.push([i, 2*i]);
    }
  }
        
  d3[30][1] = null;
  d3[31][1] = null;

  function ticksFn (n) { return '('+n+')'; }

  graph = Flotr.draw(container, [ 
      { data : d1, label : 'y = 4 + x^(1.5)', lines : { fill : true } }, 
      { data : d2, label : 'y = x^3'}, 
      { data : d3, label : 'y = 5x + 3sin(4x)'}, 
      { data : d4, label : 'y = x'},
      { data : d5, label : 'y = 2x', lines : { show : true }, points : { show : true } }
    ], {
	  
	  colors: ['#2C93E1', '#6B8E23', '#C71585', '#CD5C5C', '#9440ED'],
	  shadowSize: 0,
      xaxis : {
        noTicks : 7,              // Display 7 ticks.
        tickFormatter : ticksFn,  // Displays tick values between brackets.
        min : 1,                  // Part of the series is not displayed.
        max : 7.5                 // Part of the series is not displayed.
      },
      yaxis : {
        ticks : ticks,            // Set Y-Axis ticks
        max : 40                  // Maximum value along Y-Axis
      },
      grid : {
        verticalLines : false,
        backgroundColor : {
          colors : [[0,'#fff'], [1,'#ccc']],
          start : 'top',
          end : 'bottom'
        }
      },
      legend : {
        position : 'nw'
      }
  });
})();
//]]>


//<![CDATA[
/* Basic bars
----------------------------------*/
(function () {

  var
	container = document.getElementById('graph_show_4'),
    horizontal = (horizontal = false), // Show horizontal bars
    d1 = [],                                  // First data series
    d2 = [],                                  // Second data series
    point,                                    // Data point variable declaration
    i;

  for (i = 0; i < 4; i++) {

    if (horizontal) { 
      point = [Math.ceil(Math.random()*10), i];
    } else {
      point = [i, Math.ceil(Math.random()*10)];
    }

    d1.push(point);
        
    if (horizontal) { 
      point = [Math.ceil(Math.random()*10), i+0.5];
    } else {
      point = [i+0.5, Math.ceil(Math.random()*10)];
    }

    d2.push(point);
  };
              
  // Draw the graph
  Flotr.draw(
    container,
    [d1, d2],
    {
      bars : {
        show : true,
        horizontal : horizontal,
        shadowSize : 0,
        barWidth : 0.44,
		lineWidth: 1, 
      },
	  grid: {
		outlineWidth: 0
	  },
      mouse : {
        track : true,
        relative : true
      },
      yaxis : {
        min : 0,
        autoscaleMargin : 1
      },
	  colors: ['#6495ED', '#82B23F'],
	  shadowSize: 0
    }
  );
})();
//]]>


//<![CDATA[
/* Horizontal Basic bars
----------------------------------*/
(function () {

  var
	container = document.getElementById('graph_show_5'),
    horizontal = (horizontal = true), // Show horizontal bars
    d1 = [],                                  // First data series
    d2 = [],                                  // Second data series
    point,                                    // Data point variable declaration
    i;

  for (i = 0; i < 3; i++) {

    if (horizontal) { 
      point = [Math.ceil(Math.random()*10), i];
    } else {
      point = [i, Math.ceil(Math.random()*10)];
    }

    d1.push(point);
        
    if (horizontal) { 
      point = [Math.ceil(Math.random()*10), i+0.5];
    } else {
      point = [i+0.5, Math.ceil(Math.random()*10)];
    }

    d2.push(point);
  };
              
  // Draw the graph
  Flotr.draw(
    container,
    [d1, d2],
    {
      bars : {
        show : true,
        horizontal : horizontal,
        shadowSize : 0,
        barWidth : 0.44,
		lineWidth: 1, 
      },
      mouse : {
        track : true,
        relative : true
      },
	  grid: {
		outlineWidth: 0
	  },
      yaxis : {
        min : 0,
        autoscaleMargin : 1
      },
	  colors: ['#6495ED', '#82B23F']
    }
  );
})();
//]]>


//<![CDATA[
/* Stacked Bars
-----------------------------------*/
(function bars_stacked(container, horizontal) {

  var
    d1 = [],
    d2 = [],
    d3 = [],
    graph, i;

  for (i = -10; i < 10; i++) {
    if (horizontal) {
      d1.push([Math.random(), i]);
      d2.push([Math.random(), i]);
      d3.push([Math.random(), i]);
    } else {
      d1.push([i, Math.random()]);
      d2.push([i, Math.random()]);
      d3.push([i, Math.random()]);
    }
  }

  graph = Flotr.draw(container,[
    { data : d1, label : 'Serie 1' },
    { data : d2, label : 'Serie 2' },
    { data : d3, label : 'Serie 3' }
  ], {
    legend : {
      backgroundColor : '#B0C4DE',
	  backgroundOpacity: 0.5
    },
    bars : {
      show : true,
      stacked : true,
      horizontal : horizontal,
      barWidth : 0.6,
      lineWidth : 1,
      shadowSize : 0
    },
    grid : {
      verticalLines : horizontal,
      horizontalLines : !horizontal,
	  outlineWidth: 0
    },
	mouse : {
	  track : true,
	  relative : true
	},
	colors: ['#6495ED', '#008B8B', '#FF4500']
  });
})(document.getElementById("graph_show_6"));
//]]>


//<![CDATA[
/* Basic Pie
------------------------------------------------*/
(function basic_pie(container) {

  var
    d1 = [[0, 4]],
    d2 = [[0, 3]],
    d3 = [[0, 3.5]],
    d4 = [[0, 1.03]],
    graph;
  
  graph = Flotr.draw(container, [
    { data : d1, label : 'Search Engines',
      pie : {
        explode : 40
      }
    },
    { data : d2, label : 'Referals' },
    { data : d3, label : 'Direct' },
    { data : d4, label : 'Unknown'}
  ], {
    HtmlText : false,
    grid : {
      verticalLines : false,
      horizontalLines : false,
	  outlineWidth: 0
    },
    xaxis : { showLabels : false, minorTickFreq: 4 },
    yaxis : { showLabels : false, minorVerticalLines: true },
    pie : {
      show : true, 
      explode : 6,
	  lineWidth: 0
    },
    mouse : { track : true, lineColor: null },
    legend : {
      position : 'se',
      backgroundColor : '#D2E8FF'
    },
	colors : ['#3D8B13', '#237B8B', '#9932CC', '#B22222' ],
  });
})(document.getElementById("graph_show_7"));
//]]>


//<![CDATA[
/* Basic Radar
--------------------------------------*/
(function basic_radar(container) {

  // Fill series s1 and s2.
  var
    s1 = { label : 'Actual', data : [[0, 3], [1, 8], [2, 5], [3, 5], [4, 3], [5, 9]] },
    s2 = { label : 'Target', data : [[0, 8], [1, 7], [2, 8], [3, 2], [4, 4], [5, 7]] },
    graph, ticks;

  // Radar Labels
  ticks = [
    [0, "Statutory"],
    [1, "External"],
    [2, "Videos"],
    [3, "Yippy"],
    [4, "Management"],
    [5, "oops"]
  ];
    
  // Draw the graph.
  graph = Flotr.draw(container, [ s1, s2 ], {
    radar : { show : true, lineWidth: 1}, 
    grid  : { circular : true, minorHorizontalLines : true}, 
    yaxis : { min : 0, max : 10, minorTickFreq : 2}, 
    xaxis : { ticks : ticks},
	colors: [ '#228B22', '#FF8C00' ],
  });
})(document.getElementById("graph_show_8"));
//]]>


//<![CDATA[
/* Basic Bubble
--------------------------------------*/
(function basic_bubble(container) {

  var
    d1 = [],
    d2 = [],
    d3 = [],
    point, graph, i;
      
  for (i = 0; i < 10; i++ ){
    point = [i, Math.ceil(Math.random()*10), Math.ceil(Math.random()*10)];
    d1.push(point);
    
    point = [i, Math.ceil(Math.random()*7), Math.ceil(Math.random()*7)];
    d2.push(point);	
	
    point = [i, Math.ceil(Math.random()*4), Math.ceil(Math.random()*4)];
    d3.push(point);
  }
  
  // Draw the graph
  graph = Flotr.draw(container, [
    { data : d1, label : 'Serie 1' },
    { data : d2, label : 'Serie 2' },
    { data : d3, label : 'Serie 3' },
	], {
    legend : {
      position : 'se',
      backgroundColor : '#D2E8FF'
    },
	grid: {
	outlineWidth: 0
	},
    bubbles : { show : true, baseRadius : 5, lineWidth: 1 },
    xaxis   : { min : -4, max : 14 },
    yaxis   : { min : -4, max : 14 }
  });
})(document.getElementById("graph_show_9"));
//]]>


//<![CDATA[
/* Basic Candle
------------------------------------*/
(function basic_candle(container) {

  var
    d1 = [],
    price = 3.206,
    graph,
    i, a, b, c;

  for (i = 0; i < 50; i++) {
      a = Math.random();
      b = Math.random();
      c = (Math.random() * (a + b)) - b;
      d1.push([i, price, price + a, price - b, price + c]);
      price = price + c;
  }
    
  // Graph
  graph = Flotr.draw(container, [ d1 ], { 
    candles : { show : true, candleWidth : 0.6 },
    xaxis   : { noTicks : 10 }
  });
})(document.getElementById("graph_show_10"));
//]]>


//<![CDATA[
/* Mouse Tracking
-------------------------------------*/

(function mouse_tracking(container) {

  var
    d1 = [],
    d2 = [],
    d3 = [],
    graph, i;

  for (i = 0; i < 20; i += 0.5) {
    d1.push([i, 2*i]);
    d2.push([i, i*1.5+1.5*Math.sin(i)]);
    d3.push([i, 3*Math.cos(i)+10]);
  }

  graph = Flotr.draw(
    container, 
    [
      {
        data : d1,
        mouse : { track : false } // Disable mouse tracking for d1
      },
      d2,
      d3
    ],
    {
      mouse : {
        track           : true, // Enable mouse tracking
        lineColor       : 'purple',
        relative        : true,
        position        : 'ne',
        sensibility     : 1,
        trackDecimals   : 2,
        trackFormatter  : function (o) { return 'x = ' + o.x +', y = ' + o.y; }
      },
      crosshair : {
        mode : 'xy'
      },
	  colors: ['#2C93E1', '#6B8E23', '#C71585', '#CD5C5C', '#9440ED'],
	  shadowSize: 0
    }
  );

})(document.getElementById("graph_show_11"));
//]]>


//<![CDATA[
/* Mouse Zoom
----------------------------------------*/
(function mouse_zoom(container) {

  var
    d1 = [],
    d2 = [],
    d3 = [],
    options,
    graph,
    i;

  for (i = 0; i < 40; i += 0.5) {
    d1.push([i, Math.sin(i)+3*Math.cos(i)]);
    d2.push([i, Math.pow(1.1, i)]);
    d3.push([i, 40 - i+Math.random()*10]);
  }
      
  options = {
    selection : { mode : 'x', fps : 30 },
    title : 'Select an area of the graph to zoom. Click to reset the chart'
  };
    
  // Draw graph with default options, overwriting with passed options
  function drawGraph (opts) {

    // Clone the options, so the 'options' variable always keeps intact.
    var o = Flotr._.extend(Flotr._.clone(options), opts || {});

    // Return a new graph.
    return Flotr.draw(
      container,
      [ d1, d2, d3 ],
      o
    );
  }

  // Actually draw the graph.
  graph = drawGraph({
	colors: ['#2C93E1', '#6B8E23', '#C71585', '#CD5C5C', '#9440ED'],
	shadowSize: 0
  });      
    
  // Hook into the 'flotr:select' event.
  Flotr.EventAdapter.observe(container, 'flotr:select', function (area) {

    // Draw graph with new area
    f = drawGraph({
      xaxis: {min:area.x1, max:area.x2},
      yaxis: {min:area.y1, max:area.y2},
	  colors: ['#2C93E1', '#6B8E23', '#C71585', '#CD5C5C', '#9440ED'],
	  shadowSize: 0
    });

  });
    
  // When graph is clicked, draw the graph with default area.
  Flotr.EventAdapter.observe(container, 'flotr:click', function () { drawGraph({
	colors: ['#2C93E1', '#6B8E23', '#C71585', '#CD5C5C', '#9440ED'],
	shadowSize: 0
  }); });
})(document.getElementById("graph_show_12"));
//]]>


//<![CDATA[
/* Mouse Drag
---------------------------------*/
(function mouse_drag(container) {

  var
    d1 = [],
    d2 = [],
    d3 = [],
    options,
    graph,
    start,
    i;

  for (i = -40; i < 40; i += 0.5) {
    d1.push([i, Math.sin(i)+3*Math.cos(i)]);
    d2.push([i, Math.pow(1.1, i)]);
    d3.push([i, 40 - i+Math.random()*10]);
  }
      
  options = {
    xaxis: {min: 0, max: 20}
  };

  // Draw graph with default options, overwriting with passed options
  function drawGraph (opts) {

    // Clone the options, so the 'options' variable always keeps intact.
    var o = Flotr._.extend(Flotr._.clone(options), opts || {});

    // Return a new graph.
    return Flotr.draw(
      container,
      [ d1, d2, d3 ],
      o
    );
  }

  graph = drawGraph();      

  function initializeDrag (e) {
    start = graph.getEventPosition(e);
    Flotr.EventAdapter.observe(document, 'mousemove', move);
    Flotr.EventAdapter.observe(document, 'mouseup', stopDrag);
  }

  function move (e) {
    var
      end     = graph.getEventPosition(e),
      xaxis   = graph.axes.x,
      offset  = start.x - end.x;

    graph = drawGraph({
      xaxis : {
        min : xaxis.min + offset,
        max : xaxis.max + offset
      }
    });
    // @todo: refector initEvents in order not to remove other observed events
    Flotr.EventAdapter.observe(graph.overlay, 'mousedown', initializeDrag);
  }

  function stopDrag () {
    Flotr.EventAdapter.stopObserving(document, 'mousemove', move);
  }

  Flotr.EventAdapter.observe(graph.overlay, 'mousedown', initializeDrag);

})(document.getElementById("graph_show_13"));
//]]>


//<![CDATA[
/* Click Example
--------------------------*/
(function click_example(container) {

  var
    d1 = [[0,0]], // Point at origin
    options,
    graph;

  options = {
    xaxis: {min: 0, max: 15},
    yaxis: {min: 0, max: 15},
    lines: {show: true},
    points: {show: true},
    mouse: {track:true}
  };

  graph = Flotr.draw(container, [d1], options);

  // Add a point to the series and redraw the graph
  Flotr.EventAdapter.observe(container, 'flotr:click', function(position){

    // Add a point to the series at the location of the click
    d1.push([position.x, position.y]);
    
    // Sort the series.
    d1 = d1.sort(function (a, b) { return a[0] - b[0]; });
    
    // Redraw the graph, with the new series.
    graph = Flotr.draw(container, [d1], options);
  });
})(document.getElementById("graph_show_14"));
//]]>


//<![CDATA[
/* Donload Image
----------------------------------*/
(function download_image(container) {

  var
    d1 = [],
    d2 = [],
    d3 = [],
    d4 = [],
    d5 = [],
    graph,
    i;
  
  for (i = 0; i <= 10; i += 0.1) {
    d1.push([i, 5 + Math.pow(i,1.5)]);
    d2.push([i, Math.pow(i,3)]);
    d3.push([i, i*5+3*Math.sin(i*4)]);
    d4.push([i, i]);
    if( i.toFixed(1)%1 == 0 ){
      d5.push([i, 4*i]);
    }
  }

  // Draw the graph
  graph = Flotr.draw(
    container,[ 
      {data:d1, label:'y = 4 + x^(1.5)', lines:{fill:true}}, 
      {data:d2, label:'y = x^9', yaxis:2}, 
      {data:d3, label:'y = 7x + 3sin(4x)'}, 
      {data:d4, label:'y = '},
      {data:d5, label:'y = 2x', lines: {show: true}, points: {show: true}}
    ],{
      title: 'This is the title of this chart',
      subtitle: 'An here is the subtitle',
      xaxis:{
        noTicks: 7, // Display 7 ticks.
        tickFormatter: function(n){ return '('+n+')'; }, // => displays tick values between brackets.
        min: 1,  // => part of the series is not displayed.
        max: 7.5, // => part of the series is not displayed.
        labelsAngle: 45,
        title: 'Horizontal title'
      },
      yaxis:{
        ticks: [[0, "Lower"], 10, 20, 30, [40, "Upper"]],
        max: 40,
        title: 'Vertical title'
      },
      grid:{
        verticalLines: false,
        backgroundColor: 'white'
      },
      HtmlText: false,
      legend: {
        position: 'nw'
      },
	  mouse: {track : true},
	  shadowSize: 0,
	  colors: ['#6B8E23', '#2C93E1', '#C71585', '#9440ED', '#CD5C5C']
  });

  this.CurrentExample = function (operation) {

    var
      format = ('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };

  return graph;
})(document.getElementById("graph_show_15"));
//]]>


//<![CDATA[
/* Basic Timeline
------------------------------------*/
(function basic_timeline(container) {

  var
    d1        = [[1, 4, 5]],
    d2        = [[3.2, 3, 4]],
    d3        = [[1.9, 2, 2], [5, 2, 3.3]],
    d4        = [[1.55, 1, 9]],
    d5        = [[5, 0, 2.3]],
    data      = [],
    timeline  = { show : true, barWidth : .5 },
    markers   = [],
    labels    = ['Obama', 'Bush', 'Clinton', 'Palin', 'McCain'],
    i, graph, point;

  // Timeline
  Flotr._.each([d1, d2, d3, d4, d5], function (d) {
    data.push({
      data : d,
      timeline : Flotr._.clone(timeline)
    });
  });

  // Markers
  Flotr._.each([d1, d2, d3, d4, d5], function (d) {
    point = d[0];
    markers.push([point[0], point[1]]);
  });
  data.push({
    data: markers,
    markers: {
      show: true,
      position: 'rm',
      fontSize: 11,
      labelFormatter : function (o) { return labels[o.index]; }
    }
  });
  
  // Draw Graph
  graph = Flotr.draw(container, data, {
    xaxis: {
      noTicks: 3,
      tickFormatter: function (x) {
        var
          x = parseInt(x),
          months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return months[(x-1)%12];
      }
    }, 
    yaxis: {
      showLabels : false
    },
    grid: {
      horizontalLines : false
    }
  });
})(document.getElementById("graph_show_16"));
//]]>


//<![CDATA[
/* Basic bars big
----------------------------------*/
(function () {

  var
	container = document.getElementById('graph_show_17'),
    horizontal = (horizontal = false), // Show horizontal bars
    d1 = [],                                  // First data series
    d2 = [],                                  // Second data series
    point,                                    // Data point variable declaration
    i;

  for (i = 0; i < 4; i++) {

    if (horizontal) { 
      point = [Math.ceil(Math.random()*10), i];
    } else {
      point = [i, Math.ceil(Math.random()*10)];
    }

    d1.push(point);
        
    if (horizontal) { 
      point = [Math.ceil(Math.random()*10), i+0.5];
    } else {
      point = [i+0.5, Math.ceil(Math.random()*10)];
    }

    d2.push(point);
  };
              
  // Draw the graph
  Flotr.draw(
    container,
    [d1, d2],
    {
      bars : {
        show : true,
        horizontal : horizontal,
        shadowSize : 0,
        barWidth : 0.44,
		lineWidth: 1, 
      },
	  grid: {
		outlineWidth: 0
	  },
      mouse : {
        track : true,
        relative : true
      },
      yaxis : {
        min : 0,
        autoscaleMargin : 1
      },
	  colors: ['#6495ED', '#82B23F'],
	  shadowSize: 0
    }
  );
})();
//]]>