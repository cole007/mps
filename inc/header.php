<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bar Chart</title>
    <link rel="stylesheet" type="text/css" href="_assets/css/styles.css"/>
    <script type="text/javascript" src="_assets/js/libs/d3.js"></script>
    <script src="/_assets/js/libs/d3.tip.v0.6.3.min.js" charset="utf-8"></script>
    <script src="//use.typekit.net/zeu7gks.js"></script>
    <script>try{Typekit.load();}catch(e){}</script>
    <style>
    	.d3-tip {
    	  line-height: 1;
    	  font-size: 18px;
    	  font-weight: bold;
    	  padding: 12px;
    	  background: rgba(0, 0, 0, 0.8);
    	  color: #fff;
    	  border-radius: 2px;
    	}    	
    	.chart {
    		position: relative;
    	}
    	.label {
    		font-size: 1.5em;
    	}
    	.tooltip {
    		padding: 10px;
    		display: inline-block;
    		background: rgba(0,0,0,0.75);
    		color: white;
    		position: absolute;
    		top: 0;
    		/*right: 0;*/
    		left: 0;
    		margin-left: auto;
    		margin-right: auto;
    		font-size: 1.2em;
    	}
    </style>
</head>
<body>
