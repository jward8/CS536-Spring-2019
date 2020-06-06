<html>
<head>
<link rel="stylesheet" href="dextras.css" />

<script type="text/x-mathjax-config">
    // <![CDATA[
    MathJax.Hub.Config({
        TeX: {extensions: ["AMSmath.js", "AMSsymbols.js"]},
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        showProcessingMessages : false,
        messageStyle : "none" ,
        showMathMenu: false ,
        tex2jax: {
            processEnvironments: true,
            inlineMath: [ ['$','$'] ],
            displayMath: [ ['$$','$$'] ],
            preview : "none",
            processEscapes: true
        },
        "HTML-CSS": { linebreaks: { automatic:true, width: "latex-container"} }
    });
    // ]]>
 // MathJax.Hub.Queue(reportReload);
</script>
<script type="text/javascript" src="MathJax.js"></script>
<script type="text/javascript" src="latex2html5.min.js"></script>


</head>
<body>
<HR>

<A NAME=top></A>
<UL>
  <LI><A HREF="#overview">Overview</A>
  <LI><A HREF="#peep">Peephole Optimization</A>
    <UL>
      <LI><A HREF="#youtry1">Test Yourself #1</A>
    </UL>
  <LI><A HREF="#dataflow">Moving Loop-Invariant Computations</A>
    <UL>
      <LI><A HREF="#youtry2">Test Yourself #2</A>
    </UL>
  <LI> <a href="#strength red">Strength Reduction in <em>for</em> Loops</a>
    <UL>
      <LI><A HREF="#youtry3">Test Yourself #3</A>
      <LI><A HREF="#youtry4">Test Yourself #4</A>
    </UL>
  <LI> <a href="#copy prop">Copy Propagation</a>
</UL>

<P>
<a name=overview></a>
<span class=subhead>Overview</span>
<P>
The goal of optimization is to produce better code (fewer instructions,
and, more importantly, code that runs faster).
However, it is important not to change the behavior of the program (what
it computes)!
<P>
We will look at the following ways to improve a program:
<OL class=style1>
  <LI> <b>Peephole Optimization</b>.
       This is done <em><b>after</b></em> code generation.
       It involves finding opportunities to improve the generated code
       by making small, local changes.
  <LI> <b>Moving Loop-Invariant Computations</b>.
       This is done <em><b>before</b></em> code generation.
       It involves finding computations inside loops that can be
       moved outside, thus speeding up the execution time of the loop.
  <LI> <b>Strength-Reduction in <em>for</em> Loops</b>.
       This is done <em><b>before</b></em> code generation.
       It involves replacing multiplications inside
       loops with additions.  If it takes longer to execute a
       multiplication than an addition, then this speeds up the
       code.
  <LI> <b>Copy Propagation</b>.
       This is done <em><b>before</b></em> code generation.
       It involves replacing the use of a variable with a literal
       or another variable.  Copy propagation can sometimes uncover
       more opportunities for moving loop-invariant computations.
       It may also make it
       possible to remove some assignments from the program, thus
       making the code smaller and faster.
</OL>
<a name=peep></a>
<span class=subhead>Peephole Optimization</span>
<P>
The idea behind peephole optimization is to examine the code "through a
small window," looking for special cases that can be improved.
Below are some common optimizations that can be performed this way.
Note that in all cases that involve removing an instruction,
it is assumed that that instruction is not the target of a branch.

<OL class=style1>
  <LI> Remove a redundant load (fewer instructions generated, and
       fewer executed):
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="420"
   height="60"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-20.30098,-984.17121)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="203.8726"
       y="997.77283"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="203.8726"
         y="997.77283"
         id="tspan3054"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after peephole</tspan><tspan
         x="203.8726"
         y="1012.7728"
         id="tspan3056"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">optimization</tspan></text>
    <path
       d="M 248.06477,1020.9895 251.3258,1023.4695 248.24837,1026.309"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="26.965042"
       y="1020.7806"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.965042"
         y="1020.7806"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">store Rx, M</tspan><tspan
         x="26.965042"
         y="1040.7805"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">load  M, Rx</tspan></text>
    <text
       x="258.58893"
       y="1026.7805"
       id="text3042"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="258.58893"
         y="1026.7805"
         id="tspan3046"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">store Rx, M</tspan></text>
    <path
       d="M 126.74049,40.955017 C 126.74049,40.955017 133.41544,42.676505 136.84429,41.84083 140.27314,41.005155 145.59759,35.448274 151.47405,35.640138 157.35051,35.832002 162.1161,40.956509 170.51903,42.484429 178.92196,44.012349 185.04574,37.731281 192.4402,37.303896 198.81807,36.72804 203.58685,42.956625 208.49231,43.300078 213.39777,43.643531 216.42853,40.070341 221.12111,39.515571 225.81368,38.960801 230.57547,39.339982 230.57547,39.339982"
       transform="translate(20.30098,984.17121)"
       id="path3173"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 142.57741,1010.1555 C 142.57741,1010.1555 145.51668,1007.299 147.35889,1010.1555 149.20109,1013.012 144.95588,1017.2041 144.67312,1020.6895 144.38193,1024.2789 147.35889,1025.4563 147.35889,1025.4563 L 147.35889,1025.4563 147.35889,1025.4563"
       id="path3175"
       style="fill:none;stroke:#800080;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 142.57741,1042.1867 C 142.57741,1042.1867 145.51668,1045.0432 147.35889,1042.1867 149.20109,1039.3302 144.95588,1033.1381 144.67312,1029.6527 144.38193,1026.0633 147.35889,1024.8859 147.35889,1024.8859 L 147.35889,1024.8859 147.35889,1024.8859"
       id="path3947"
       style="fill:none;stroke:#800080;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 144.24395,1091.1323 C 144.24395,1091.1323 147.18322,1088.2758 149.02543,1091.1323 150.86763,1093.9888 146.62242,1098.1809 146.33966,1101.6663 146.04847,1105.2557 149.02543,1106.4331 149.02543,1106.4331 L 149.02543,1106.4331 149.02543,1106.4331"
       id="path3949"
       style="fill:none;stroke:#000000;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
  </g>
</svg>
			</div>

  <LI> Remove a redundant push/pop (fewer instructions generated, and
       fewer executed):
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="420"
   height="60"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-20.30098,-984.17121)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="203.8726"
       y="997.77283"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="203.8726"
         y="997.77283"
         id="tspan3054"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after peephole</tspan><tspan
         x="203.8726"
         y="1012.7728"
         id="tspan3056"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">optimization</tspan></text>
    <path
       d="M 248.06477,1020.9895 251.3258,1023.4695 248.24837,1026.309"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="26.965042"
       y="1020.7806"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.965042"
         y="1020.7806"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">push Rx</tspan><tspan
         x="26.965042"
         y="1040.7805"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">pop into Rx</tspan></text>
    <text
       x="258.58893"
       y="1026.7805"
       id="text3042"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="258.58893"
         y="1026.7805"
         id="tspan3046"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">nothing!</tspan></text>
    <path
       d="M 126.74049,40.955017 C 126.74049,40.955017 133.41544,42.676505 136.84429,41.84083 140.27314,41.005155 145.59759,35.448274 151.47405,35.640138 157.35051,35.832002 162.1161,40.956509 170.51903,42.484429 178.92196,44.012349 185.04574,37.731281 192.4402,37.303896 198.81807,36.72804 203.58685,42.956625 208.49231,43.300078 213.39777,43.643531 216.42853,40.070341 221.12111,39.515571 225.81368,38.960801 230.57547,39.339982 230.57547,39.339982"
       transform="translate(20.30098,984.17121)"
       id="path3173"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 142.57741,1010.1555 C 142.57741,1010.1555 145.51668,1007.299 147.35889,1010.1555 149.20109,1013.012 144.95588,1017.2041 144.67312,1020.6895 144.38193,1024.2789 147.35889,1025.4563 147.35889,1025.4563 L 147.35889,1025.4563 147.35889,1025.4563"
       id="path3175"
       style="fill:none;stroke:#800080;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 142.57741,1042.1867 C 142.57741,1042.1867 145.51668,1045.0432 147.35889,1042.1867 149.20109,1039.3302 144.95588,1033.1381 144.67312,1029.6527 144.38193,1026.0633 147.35889,1024.8859 147.35889,1024.8859 L 147.35889,1024.8859 147.35889,1024.8859"
       id="path3947"
       style="fill:none;stroke:#800080;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 144.24395,1091.1323 C 144.24395,1091.1323 147.18322,1088.2758 149.02543,1091.1323 150.86763,1093.9888 146.62242,1098.1809 146.33966,1101.6663 146.04847,1105.2557 149.02543,1106.4331 149.02543,1106.4331 L 149.02543,1106.4331 149.02543,1106.4331"
       id="path3949"
       style="fill:none;stroke:#000000;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
  </g>
</svg>
			</div>
  <LI> Replace a jump to a jump (same number of instructions generated,
       but fewer executed):
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="420"
   height="75"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-20.30098,-969.17121)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="203.8726"
       y="987.77283"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="203.8726"
         y="987.77283"
         id="tspan3054"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after peephole</tspan><tspan
         x="203.8726"
         y="1002.7728"
         id="tspan3056"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">optimization</tspan></text>
    <path
       d="M 248.06477,1010.2395 251.3258,1012.7195 248.24837,1015.559"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="26.965042"
       y="996.78058"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.965042"
         y="996.78058"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    goto L1</tspan><tspan
         x="26.965042"
         y="1016.7806"
         id="tspan4131"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="26.965042"
         y="1036.7805"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">L1: goto L2</tspan></text>
    <path
       d="M 147.04147,1014.3762 C 147.04147,1014.3762 153.71642,1016.0977 157.14527,1015.262 160.57412,1014.4264 165.89857,1008.8695 171.77503,1009.0613 177.65149,1009.2532 182.41708,1014.3777 190.82001,1015.9056 199.22294,1017.4336 205.34672,1011.1525 212.74118,1010.7251 219.11905,1010.1492 223.88783,1016.3778 228.79329,1016.7213 233.69875,1017.0647 236.72951,1013.4916 241.42209,1012.9368 246.11466,1012.382 250.87645,1012.7612 250.87645,1012.7612"
       id="path3173"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="1004.2388"
       y="-87.262794"
       transform="matrix(0,1,-1,0,0,0)"
       id="text4145"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="1004.2388"
         y="-87.262794"
         id="tspan4147">...</tspan></text>
    <text
       x="282.96503"
       y="996.78058"
       id="text4149"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="282.96503"
         y="996.78058"
         id="tspan4151"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    goto L2</tspan><tspan
         x="282.96503"
         y="1016.7806"
         id="tspan4153"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="282.96503"
         y="1036.7805"
         id="tspan4155"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">L1: goto L2</tspan></text>
    <text
       x="1004.2388"
       y="-343.26279"
       transform="matrix(0,1,-1,0,0,0)"
       id="text4157"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="1004.2388"
         y="-343.26279"
         id="tspan4159">...</tspan></text>
    <path
       d="M 128.18,979.88966 C 133.84439,979.83941 140.31328,985.98275 141.8056,991.01158 143.81345,997.77773 136.67953,1003.9966 131.46442,1006.8331 122.37324,1011.7778 117.35633,1011.2174 112.41163,1002.1262 107.46692,993.03506 107.13712,981.67227 116.22831,976.72756 120.76711,974.2589 129.99227,975.65399 134.90069,977.26722"
       id="path4161"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dashoffset:0" />
  </g>
</svg>
			</div>
  <LI> Remove a jump to the next instruction (fewer instructions generated,
       and fewer executed):
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="420"
   height="60"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-20.30098,-984.17121)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="203.8726"
       y="997.77283"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="203.8726"
         y="997.77283"
         id="tspan3054"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after peephole</tspan><tspan
         x="203.8726"
         y="1012.7728"
         id="tspan3056"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">optimization</tspan></text>
    <path
       d="M 248.06477,1020.9895 251.3258,1023.4695 248.24837,1026.309"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="26.965042"
       y="1020.7806"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.965042"
         y="1020.7806"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    goto L1</tspan><tspan
         x="26.965042"
         y="1040.7805"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">L1: </tspan></text>
    <path
       d="M 126.74049,40.955017 C 126.74049,40.955017 133.41544,42.676505 136.84429,41.84083 140.27314,41.005155 145.59759,35.448274 151.47405,35.640138 157.35051,35.832002 162.1161,40.956509 170.51903,42.484429 178.92196,44.012349 185.04574,37.731281 192.4402,37.303896 198.81807,36.72804 203.58685,42.956625 208.49231,43.300078 213.39777,43.643531 216.42853,40.070341 221.12111,39.515571 225.81368,38.960801 230.57547,39.339982 230.57547,39.339982"
       transform="translate(20.30098,984.17121)"
       id="path3173"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 142.57741,1010.1555 C 142.57741,1010.1555 145.51668,1007.299 147.35889,1010.1555 149.20109,1013.012 144.95588,1017.2041 144.67312,1020.6895 144.38193,1024.2789 147.35889,1025.4563 147.35889,1025.4563 L 147.35889,1025.4563 147.35889,1025.4563"
       id="path3175"
       style="fill:none;stroke:#800080;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 142.57741,1042.1867 C 142.57741,1042.1867 145.51668,1045.0432 147.35889,1042.1867 149.20109,1039.3302 144.95588,1033.1381 144.67312,1029.6527 144.38193,1026.0633 147.35889,1024.8859 147.35889,1024.8859 L 147.35889,1024.8859 147.35889,1024.8859"
       id="path3947"
       style="fill:none;stroke:#800080;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 144.24395,1091.1323 C 144.24395,1091.1323 147.18322,1088.2758 149.02543,1091.1323 150.86763,1093.9888 146.62242,1098.1809 146.33966,1101.6663 146.04847,1105.2557 149.02543,1106.4331 149.02543,1106.4331 L 149.02543,1106.4331 149.02543,1106.4331"
       id="path3949"
       style="fill:none;stroke:#000000;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="68.641869"
       y="1041.0394"
       id="text4227"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="68.641869"
         y="1041.0394"
         id="tspan4229">...</tspan></text>
    <text
       x="264.96503"
       y="1006.7806"
       id="text4231"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="264.96503"
         y="1006.7806"
         id="tspan4233"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="264.96503"
         y="1026.7805"
         id="tspan4235"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">L1: </tspan></text>
    <text
       x="306.64188"
       y="1027.0394"
       id="text4237"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="306.64188"
         y="1027.0394"
         id="tspan4239">...</tspan></text>
  </g>
</svg>
			</div>
  <LI> Replace a jump around jump (fewer instructions generated;
       possibly fewer executed):
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="530"
   height="82"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-24.965042,-941.92159)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="285.87262"
       y="959.77283"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="289.75153"
         y="959.77283"
         id="tspan3054"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after </tspan><tspan
         x="289.75153"
         y="974.77283"
         id="tspan3003"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">peephole </tspan><tspan
         x="285.87262"
         y="989.77283"
         id="tspan3056"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">optimization</tspan></text>
    <path
       d="M 326.06477,994.2395 329.3258,996.7195 326.24837,999.559"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="26.965042"
       y="980.78058"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.965042"
         y="980.78058"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;word-spacing:-0.5px;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    if T0 == 0 goto L1</tspan><tspan
         x="26.965042"
         y="1000.7806"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;word-spacing:-0.5px;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    goto L2</tspan><tspan
         x="26.965042"
         y="1020.7806"
         id="tspan4309"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;word-spacing:-0.5px;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">L1: </tspan></text>
    <path
       d="M 249.66157,997.30034 C 260.01596,992.93105 254.61088,994.97086 272.69389,997.86376 290.7769,1000.7567 310.15881,998.2012 319.42209,996.9368 328.68537,995.67241 328.87645,996.7612 328.87645,996.7612"
       id="path3173"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="68.473122"
       y="1021.7596"
       id="text4145"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="68.473122"
         y="1021.7596"
         id="tspan4147">...</tspan></text>
    <text
       x="330.96503"
       y="994.78058"
       id="text4311"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:-0.1px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="330.96503"
         y="994.78058"
         id="tspan4315"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;word-spacing:-0.5px;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    if T0 != 0 goto L2</tspan><tspan
         x="330.96503"
         y="1014.7806"
         id="tspan4317"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;word-spacing:-0.5px;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">L1: </tspan></text>
    <text
       x="376.47311"
       y="1015.7595"
       id="text4319"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="376.47311"
         y="1015.7595"
         id="tspan4321">...</tspan></text>
  </g>
</svg>
			</div>
  <LI> Remove useless operations (fewer instructions generated and fewer
       executed):
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="420"
   height="75"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-20.30098,-969.17121)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="203.8726"
       y="987.77283"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="203.8726"
         y="987.77283"
         id="tspan3054"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after peephole</tspan><tspan
         x="203.8726"
         y="1002.7728"
         id="tspan3056"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">optimization</tspan></text>
    <path
       d="M 248.06477,1010.2395 251.3258,1012.7195 248.24837,1015.559"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="26.965042"
       y="1004.7806"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.965042"
         y="1004.7806"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">add T0,T0, 0</tspan><tspan
         x="26.965042"
         y="1024.7805"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">mul T0,T0, 1</tspan></text>
    <path
       d="M 147.04147,1014.3762 C 147.04147,1014.3762 153.71642,1016.0977 157.14527,1015.262 160.57412,1014.4264 165.89857,1008.8695 171.77503,1009.0613 177.65149,1009.2532 182.41708,1014.3777 190.82001,1015.9056 199.22294,1017.4336 205.34672,1011.1525 212.74118,1010.7251 219.11905,1010.1492 223.88783,1016.3778 228.79329,1016.7213 233.69875,1017.0647 236.72951,1013.4916 241.42209,1012.9368 246.11466,1012.382 250.87645,1012.7612 250.87645,1012.7612"
       id="path3173"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="340.88187"
       y="993.77283"
       id="text4476"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="340.88187"
         y="993.77283"
         id="tspan4484"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">nothing!</tspan><tspan
         x="344.43753"
         y="1007.9692"
         id="tspan4492"
         style="font-size:11px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">(Adding 0 or multiplying </tspan><tspan
         x="340.88187"
         y="1021.7192"
         id="tspan4504"
         style="font-size:11px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">by 1 has no effect:</tspan><tspan
         x="340.88187"
         y="1035.4692"
         id="tspan4494"
         style="font-size:11px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">these instructions are useless)</tspan></text>
  </g>
</svg>
			</div>

	<LI> Reduction in strength: don't use a slow, general-purpose instruction where
a fast, special-purpose instruction will do (same number of instructions, but faster):
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="420"
   height="85"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <flowRoot
     transform="translate(-20.30098,-959.17121)"
     id="flowRoot2985"
     xml:space="preserve"
     style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
       id="flowRegion2987"><rect
         width="295.64752"
         height="215.3271"
         x="164.05875"
         y="266.24738"
         id="rect2989"
         style="font-size:12px" /></flowRegion><flowPara
       id="flowPara2991" /></flowRoot>  <text
     x="211.57162"
     y="18.601616"
     id="text3019"
     xml:space="preserve"
     style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
       x="211.57162"
       y="18.601616"
       id="tspan3054"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after peephole</tspan><tspan
       x="211.57162"
       y="33.601616"
       id="tspan3056"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">optimization</tspan></text>
  <path
     d="M 255.76379,41.81829 259.02482,44.29829 255.94739,47.13779"
     id="path3805"
     style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  <text
     x="6.664062"
     y="49.609367"
     id="text3062"
     xml:space="preserve"
     style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
       x="6.664062"
       y="49.609367"
       id="tspan3068"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">mul, T0, T0, 2</tspan></text>
  <text
     x="266.28796"
     y="47.609306"
     id="text3042"
     xml:space="preserve"
     style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
       x="266.28796"
       y="47.609306"
       id="tspan3046"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">shift-left T0</tspan></text>
  <path
     d="M 154.74049,45.955017 C 154.74049,45.955017 161.41544,45.676505 164.84429,44.84083 168.27314,44.005155 173.59759,42.448274 179.47405,42.640138 185.35051,42.832002 190.1161,43.956509 198.51903,45.484429 206.92196,47.012349 213.04574,44.731281 220.4402,44.303896 226.81807,43.72804 231.58685,45.956625 236.49231,46.300078 241.39777,46.643531 244.42853,45.070341 249.12111,44.515571 253.81368,43.960801 258.57547,44.339982 258.57547,44.339982"
     id="path3173"
     style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  <path
     d="M 123.94297,131.96109 C 123.94297,131.96109 126.88224,129.10459 128.72445,131.96109 130.56665,134.81759 126.32144,139.00969 126.03868,142.49509 125.74749,146.08449 128.72445,147.26189 128.72445,147.26189 L 128.72445,147.26189 128.72445,147.26189"
     id="path3949"
     style="fill:none;stroke:#000000;stroke-width:1.19290149;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
  <text
     x="6.664062"
     y="77.609367"
     id="text4598"
     xml:space="preserve"
     style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
       x="6.664062"
       y="77.609367"
       id="tspan4600"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">add, T0, T0, 1</tspan></text>
  <text
     x="266.28796"
     y="75.609306"
     id="text4602"
     xml:space="preserve"
     style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
       x="266.28796"
       y="75.609306"
       id="tspan4604"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">inc T0</tspan></text>
  <path
     d="M 154.74049,71.003556 C 154.74049,71.003556 161.41544,69.282068 164.84429,70.117743 168.27314,70.953418 172.84759,73.010299 178.72405,72.818435 184.60051,72.626571 188.4824,74.269467 196.88533,72.741547 205.28826,71.213627 212.77346,70.415321 220.16792,70.842706 226.54579,71.418562 230.77,74.175337 235.67546,73.831884 240.58092,73.488431 244.42853,71.888232 249.12111,72.443002 253.81368,72.997772 258.57547,72.618591 258.57547,72.618591"
     id="path4606"
     style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  <path
     d="M 255.76379,69.81829 259.02482,72.29829 255.94739,75.13779"
     id="path4608"
     style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
</svg>
			</div>
</OL>
Note that doing one optimization may enable another: for example:
			<div align=center style="margin-top:20px; margin-bottom:20px">
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="505"
   height="72"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-24.621292,-980.08175)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="27.349594"
       y="998.78058"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="27.349594"
         y="998.78058"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">load  Tx, M</tspan><tspan
         x="27.349594"
         y="1018.7806"
         id="tspan3000"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">add   Tx, 0</tspan><tspan
         x="27.349594"
         y="1038.7805"
         id="tspan3002"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">store Tx, M</tspan><tspan
         x="27.349594"
         y="1058.7805"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /></text>
    <text
       x="222.22849"
       y="1011.9958"
       id="text3004"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="222.22849"
         y="1011.9958"
         id="tspan3008"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">load  Tx, M</tspan><tspan
         x="222.22849"
         y="1031.9958"
         id="tspan3010"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">store Tx, M</tspan><tspan
         x="222.22849"
         y="1051.9958"
         id="tspan3012"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /></text>
    <text
       x="417.74323"
       y="1020.8539"
       id="text3016"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="417.74323"
         y="1020.8539"
         id="tspan3020"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">load  Tx, M</tspan><tspan
         x="417.74323"
         y="1040.8539"
         id="tspan3022"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /></text>
    <text
       x="178.12891"
       y="995.74158"
       id="text3019-0"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="178.12891"
         y="995.74158"
         id="tspan3054-6"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after</tspan></text>
    <path
       d="M 214.32107,1016.1715 217.5821,1018.6515 214.50467,1021.491"
       id="path3805-3"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 144.08893,1017.7014 C 149.96539,1017.8933 148.67338,1018.3098 157.07631,1019.8377 165.47924,1021.3656 171.60302,1019.0845 178.99748,1018.6571 185.37535,1018.0813 190.14413,1020.3099 195.04959,1020.6533 199.95505,1020.9968 202.98581,1019.4236 207.67839,1018.8688 212.37096,1018.3141 217.13275,1018.6932 217.13275,1018.6932"
       id="path3173-5"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 139.24806,985.53352 C 139.24806,985.53352 142.18733,979.55045 144.02954,985.53352 145.87174,991.51659 141.62653,1000.2972 141.34377,1007.5975 141.05258,1015.1156 144.02954,1017.5818 144.02954,1017.5818 L 144.02954,1017.5818 144.02954,1017.5818"
       id="path3949-2"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 139.24806,1048.9226 C 139.24806,1048.9226 142.18733,1054.9057 144.02954,1048.9226 145.87174,1042.9396 141.62653,1034.159 141.34377,1026.8587 141.05258,1019.3405 144.02954,1016.8744 144.02954,1016.8744 L 144.02954,1016.8744 144.02954,1016.8744"
       id="path4222"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="178.12891"
       y="1011.7416"
       id="text4732"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="178.12891"
         y="1011.7416"
         id="tspan4734"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">round 1</tspan></text>
    <text
       x="372.81161"
       y="995.10376"
       id="text4750"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="372.81161"
         y="995.10376"
         id="tspan4752"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after</tspan></text>
    <path
       d="M 409.00378,1015.5338 412.26481,1018.0138 409.18738,1020.8533"
       id="path4754"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 338.77164,1017.0637 C 344.6481,1017.2555 343.35609,1017.672 351.75902,1019.1999 360.16195,1020.7278 366.28573,1018.4468 373.68019,1018.0194 380.05806,1017.4435 384.82684,1019.6721 389.7323,1020.0156 394.63776,1020.359 397.66852,1018.7858 402.3611,1018.2311 407.05367,1017.6763 411.81546,1018.0555 411.81546,1018.0555"
       id="path4756"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="372.81161"
       y="1011.1038"
       id="text4762"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="372.81161"
         y="1011.1038"
         id="tspan4764"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">round 2</tspan></text>
    <path
       d="M 336.3592,1000.0726 C 336.3592,1000.0726 337.95426,996.82571 338.95398,1000.0726 339.95368,1003.3194 337.64993,1008.0843 337.49648,1012.046 337.33846,1016.1259 338.95398,1017.4642 338.95398,1017.4642 L 338.95398,1017.4642 338.95398,1017.4642"
       id="path4766"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 336.3592,1034.0211 C 336.3592,1034.0211 337.95426,1037.268 338.95398,1034.0211 339.95368,1030.7743 337.64993,1026.0094 337.49648,1022.0477 337.33846,1017.9678 338.95398,1016.6295 338.95398,1016.6295 L 338.95398,1016.6295 338.95398,1016.6295"
       id="path4768"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
  </g>
</svg>
			</div>
<P>
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
Consider the following program:
<UL>
<pre>
public class Opt {

   public static void main() {
       int a;
       int b;

       if (true) {
         if (true) {
      	    b = 0;
         }
         else {
            b = 1;
         }
         return;
       }
       a = 1;
       b = a;
   }
}
</pre>
</UL>
<b>Question 1</b>:
The code generated for this program contains opportunities for the first two
kinds of peephole optimization (removing a redundant load, and replacing
a jump to a jump).
Can you explain how those opportunities arise just by looking at the
source code?
<P>
<b>Question 2</b>:
Below is the generated code.
Verify your answer to question 1 by finding the opportunities for the
two kinds of optimization.
What other opportunity for removing redundant code is common in this example?
<UL>
<pre>
.text
	.globl main
main:		# FUNCTION ENTRY
	sw    $ra, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	sw    $fp, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	addu  $fp, $sp, 8
	subu  $sp, $sp, 8
	# STATEMENTS
	# if-then
	li    $t0, 1
	sw    $t0, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	lw    $t0, 4($sp)	#POP
	addu  $sp, $sp, 4
	beq   $t0, 0, _L0
	# if-then-else
	li    $t0, 1
	sw    $t0, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	lw    $t0, 4($sp)	#POP
	addu  $sp, $sp, 4
	beq   $t0, 0, _L1
	li    $t0, 0
	sw    $t0, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	lw    $t0, 4($sp)	#POP
	addu  $sp, $sp, 4
	sw    $t0, -12($fp)
	b     _L2
_L1:
	li    $t0, 1
	sw    $t0, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	lw    $t0, 4($sp)	#POP
	addu  $sp, $sp, 4
	sw    $t0, -12($fp)
_L2:
	# return
	b     main_Exit
_L0:
	li    $t0, 1
	sw    $t0, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	lw    $t0, 4($sp)	#POP
	addu  $sp, $sp, 4
	sw    $t0, -8($fp)
	lw    $t0, -8($fp)
	sw    $t0, 0($sp)	#PUSH
	subu  $sp, $sp, 4
	lw    $t0, 4($sp)	#POP
	addu  $sp, $sp, 4
	sw    $t0, -12($fp)
		#FUNCTION EXIT
main_Exit:
	lw    $ra, 0($fp)
	move  $sp, $fp		#restore SP
	lw    $fp, -4($fp)	#restore FP
	jr    $ra		#return
</pre>
<hr>
<P>
<a name=dataflow></a>
<span class=subhead>Optimization #1: Loop-Invariant Code Motion</span>
<P>
The ideas behind this optimization are:
<UL>
  <LI>For greatest gain, optimize "<I>hot spots</I>" i.e., inner loops.
  <LI>An expression is "loop invariant" if the <em>same</em> value is
      computed for that expression on every iteration of the loop.
  <LI>Instead of computing the same value over and over, compute the
      value once outside the loop and reuse it.
</UL>

<P>
Example:
<UL>
<pre>
for (i=0; i<100; i++) {
    for (j=0; j<100; j++) {
        for (k=0; k<100; k++) {
            A[i][j][k] = <FONT color="red">i*j</FONT>*k
        }
    }
}
</pre>
</UL>

In this example, <FONT color="red">i*j</FONT>
is invariant with respect to the inner loop.
But there are <em>more</em> loop-invariant expressions; to find them,
we need to look at a lower-level version of this code.
If we assume the following:
<UL>
  <LI>A is a 3D array
  <LI>each element requires 4 bytes
  <LI>elements are stored in the current activation record in row-major order
      (note: in Java, arrays are allocated from the heap, not stored on the
      stack; however, in other languages they may be stored on the stack)
</UL>
then the code for A[i][j][k] = ... involves computing the <em>address</em>
of A[i][j][k] (i.e., where to store the value of the right-hand-side
expression).
That computation looks something like:
<UL><FONT FACE==Courier>
address = FP + &lt;offset of A> - (i*10,000*4)-(j*100*4)-(k*4)
</FONT></UL>

<P>
So the code for the inner loop is actually something like:

<UL><FONT FACE==Courier>
T0 = <FONT COLOR="#FF0000">i*j</FONT>*k<BR>
T1 = <FONT COLOR=#3333FF>FP + &lt;offset of A></FONT> - <FONT COLOR="orange">i*40000</FONT> -<FONT COLOR="#FF0000">j*400</FONT> - k*4<BR>
Store T0, 0(T1)
</FONT></UL>
And we have the following loop-invariant expressions:
<UL>
  <FONT COLOR="#3333FF">invariant to i loop: FP + &lt;offset of A></FONT>
  <BR><FONT COLOR="orange">invariant to j loop: i*40000</FONT>
  <BR><FONT COLOR="#FF0000">invariant to k loop: i*j, j*400</FONT></UL>
</UL>

<P>
We can move the computations of the loop-invariant expressions out of
their loops, assigning the values of those expressions to new temporaries,
and then using the temporaries in place of the expressions.
When we do that for the example above, we get:

<div align=center style="margin-top:20px; margin-bottom:20px">
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="335"
   height="295"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(7.6467786,-960.70374)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="74.051071"
       y="974.11835"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="74.051071"
         y="974.11835"
         id="tspan3022"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">tmp0 = FP + offsetA</tspan><tspan
         x="74.051071"
         y="994.11835"
         id="tspan3009"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">for (i=0; i&lt;100; i++){</tspan><tspan
         x="74.051071"
         y="1014.1183"
         id="tspan3024"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  tmp1 = tmp0 - i*40000</tspan><tspan
         x="74.051071"
         y="1034.1184"
         id="tspan4728"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  for (j=0; j&lt;100; j++){</tspan><tspan
         x="74.051071"
         y="1054.1184"
         id="tspan3011"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp2 = ind2</tspan><tspan
         x="74.051071"
         y="1074.1184"
         id="tspan3034"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp3 = i*j</tspan><tspan
         x="74.051071"
         y="1094.1184"
         id="tspan3017"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    for (k=0; k&lt;100; k++){</tspan><tspan
         x="74.051071"
         y="1114.1184"
         id="tspan3019"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T0 = tmp3 * k</tspan><tspan
         x="74.051071"
         y="1134.1184"
         id="tspan3023"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T1 = tmp2 - k*4</tspan><tspan
         x="74.051071"
         y="1154.1184"
         id="tspan3038"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      store T0, 0(T1)</tspan><tspan
         x="74.051071"
         y="1174.1184"
         id="tspan3042"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    }</tspan><tspan
         x="74.051071"
         y="1194.1184"
         id="tspan3044"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  }</tspan><tspan
         x="74.051071"
         y="1214.1184"
         id="tspan4726"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">}</tspan></text>
    <text
       x="9.6392698"
       y="1056.2231"
       id="text4080"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="9.6392698"
         y="1056.2231"
         id="tspan4082"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;fill-opacity:1;font-family:Purisa;-inkscape-font-specification:Purisa">T0 is i*j*k</tspan></text>
    <text
       x="41.866753"
       y="1156.3813"
       id="text4084"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="45.745659"
         y="1156.3813"
         id="tspan4086"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa">T1 is the </tspan><tspan
         x="41.866753"
         y="1171.3813"
         id="tspan4088"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa">address of A</tspan></text>
    <text
       x="233.32315"
       y="1201.0417"
       id="text4090"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="233.32315"
         y="1201.0417"
         id="tspan4094"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;font-family:Purisa;-inkscape-font-specification:Purisa">store i*j*k into</tspan><tspan
         x="233.32315"
         y="1216.0417"
         id="tspan4098"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;font-family:Purisa;-inkscape-font-specification:Purisa">A[i][j][k]</tspan></text>
    <path
       d="M 53.859821,1067.8355 C 75.387085,1093.0275 89.968327,1115.3148 126.06322,1108.8399"
       id="path4100"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 40.309641,1140.3 C 45.809589,1120.0709 108.27558,1123.1554 127.84602,1127.5593"
       id="path4102"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 177.03551,1195.9329 C 158.81538,1190.1544 157.51596,1180.4221 151.1926,1161.2655"
       id="path4104"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 148.04102,1166.3081 151.1926,1158.114 155.6048,1163.7868"
       id="path4106"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 123.50044,1124.5452 128.40314,1127.4423 125.28324,1130.5622"
       id="path4108"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 120.04627,1105.4972 126.06322,1109.2856 120.93767,1112.1827"
       id="path4110"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
</div>
<P>
Here is a comparison of the original code and the optimized code (the number
of instructions performed in the innermost loop, which is executed
1,000,000 times):

<P>
<CENTER><TABLE class=cfg>
<TR>
	<TD align=left style="border-bottom:1px solid"><b>Original Code</b></TD>
	<td style="padding-left:30px"></td>	
	<TD align=left style="border-bottom:1px solid"><b>New Code</b></TD>
</TR>
<TR>
<TD align=left>5 multiplications (3 for lvalue, 2 for rvalue)</td>
<td></td>
<TD align=left>2 multiplications (1 for lvalue, 1 for rvalue)</td>
</tr>

<tr>
<td align=left>3 subtractions (for lvalue)</td>
<td></td>
<td align=left>1 subtractions (for lvalue)</td>
</tr>

<tr>
<td align=left>1 indexed store</td>
<td></td>
<td align=left>1 indexed store</td>
</tr>
</TABLE></CENTER>

<P>
Questions:
<OL class="style1">
  <LI> How do we recognize loop-invariant expressions?
  <LI> When and where do we move the computations of those expressions?
</OL>
Answers:
<OL class=style1>
  <LI> An expression is <em>invariant</em> with respect to a loop if for
       every operand, one of the following holds:
       <OL class=style1>
         <LI type=a> It is a literal, or
				 <LI type=a> It is a variable that gets its value <em>only</em> from outside
							the loop.
       </OL>

  <P>
  <LI>To answer question 2, we need to consider <em>safety</em> and
      <em>profitability</em>.
</OL>

<span class=subsubhead>Safety</span>
<p>
If evaluating the expression might cause an <em>error</em>,
then there is a possible problem if the expression <em>might not</em> be
executed in the original, unoptimized code. For example:
</p>
<div align=center style="margin-top:20px; margin-bottom:20px">
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="170"
   height="125"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-26.965042,-961.85581)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="26.965042"
       y="972.78058"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.965042"
         y="972.78058"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">b = a;</tspan><tspan
         x="26.965042"
         y="992.78058"
         id="tspan4722"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">while (a != 0){</tspan><tspan
         x="26.965042"
         y="1012.7806"
         id="tspan4724"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  x = 1/b;</tspan><tspan
         x="26.965042"
         y="1032.7805"
         id="tspan4728"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  a--;</tspan><tspan
         x="26.965042"
         y="1052.7805"
         id="tspan4726"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">}</tspan></text>
    <text
       x="133.55522"
       y="1051.9038"
       id="text4476"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="137.43413"
         y="1051.9038"
         id="tspan4741"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">possible divide by </tspan><tspan
         x="137.43413"
         y="1066.9038"
         id="tspan4494"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">zero if moved out </tspan><tspan
         x="133.55522"
         y="1081.9038"
         id="tspan4749"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">of the loop</tspan></text>
    <path
       d="M 99.84927,997.07297 C 99.84927,997.07297 85.943814,995.33984 81.684699,999.5784 77.425584,1003.817 75.534476,1011.7377 79.179242,1017.1166 88.00881,1024.9277 108.58267,1023.4881 114.27748,1017.1166 118.6446,1012.2131 118.92127,1006.5548 115.53021,1002.7102 111.30182,996.97734 104.79432,999.2956 94.616901,999.6479"
       id="path4730"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 111.18998,1018.188 C 111.18998,1018.188 120.5437,1020.6173 124.26145,1027.7789 127.9792,1034.9404 125.86494,1041.0738 125.86494,1041.0738"
       id="path4751"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
</div>
<p>
  What about preserving the <em>order</em> of events?
  e.g. if the unoptimized code performed output then had a runtime error,
  is it valid for the optimized code to simply have a runtime error? Also
  note that changing the order of floating-point computations may change
  the result, due to differing precisions.
</p>

<span class=subsubhead>Profitability</span>
<p>
  If the computation might <em>not</em> execute in the
  original program, moving the computation might actually slow the
  program down!
</p>

<P>Moving a computation is both safe and profitable if one of the
following holds:
<OL class=style1>
  <LI>It can be determined that the loop will execute at least once
      and the code is guaranteed to execute if the loop does:
      <UL>
        <LI>it isn't inside any condition, or
        <LI>it is on <em>all</em> paths through the loop (e.g., it
	    occurs in both branches of an if-then-else).
      </UL>
  <LI>The expression is in (a non short-circuited) part of the loop
      test / loop bounds, e.g.:
      <UL> <FONT FACE="Courier">
      while (x &lt; i + j * 100)  // j*100 will always be evaluated</FONT>
      </UL>
</OL>
<P>
<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
What are some examples of loops for which the compiler can be sure that
the loop will execute at least once?
<P>
<p><a href="SOLUTIONS/OPTIMIZATION-ANSWERS.html#ans2">solution</a></p>
<hr>

<A name="strength red"></a>
<span class=subsubhead> Optimization #2: Strength reduction in for-loops</span>
<p>
The basic idea here is to take advantage of patterns in for-loops to replace expensive
operations, like multiplications, with cheaper ones, like additions. 
</p>

<p>
The particular pattern that we will handle takes the general form of a loop where:
<ol class=style1>
<li><span class=code>L</span> is the loop index</li>
<li><span class=code>B</span> is the beginning value of the loop</li>
<li><span class=code>E</span> is the end value of the loop</li>
<li>The body of the loop contains a right-hand-side expression of the form <span class=code>L * M + C</span>. We call this the <em>induction expression</em>.
<li>The factors of the induction expression, <span class=code>M</span> and <span class=code>C</span>, must be constant with respect to the loop.
</ol>
These rules define a sort-of "template" of the following form<sup><a href="#" class=tooltip>*
<span><img class="callout" src="css/callout.gif"/>The Ackermann function is famously slow to compute. In this example, the resultant call will return a number with nearly 20,000 digits.</span></a> 
</sup>


:
</p>
<p>
<span class=code>
for L from B to E do {<br />
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $\vdots$<br />
&nbsp&nbsp $\ldots$ = L * M + C<br />
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $\vdots$<br />
}</span>
</p>
<P>Consider the sequences of values for <span class=code>L</span> and
for the induction expression:&nbsp;
</p>

<CENTER><TABLE class=cfg>
<TR>
	<TD align=left style="border-bottom:1px solid"><b>Iteration #</b></TD>
	<td style="padding-left:30px"></td>	
	<TD align=left style="border-bottom:1px solid"><b>L</b></TD>
	<td style="padding-left:30px"></td>	
	<TD align=left style="border-bottom:1px solid"><b>L * M + C</b></TD>
</TR>

<TR>
	<TD align=left>1</TD>
	<td style="padding-left:30px"></td>	
	<TD align=left>B</TD>
	<td style="padding-left:30px"></td>	
	<TD align=left>B * M + C</TD>
</TR>

<TR>
	<TD align=left>2</TD>
	<td style="padding-left:30px"></td>	
	<TD align=left>B + 1</TD>
	<td style="padding-left:30px"></td>	
	<TD align=left>(B + 1) * M + C = <span style="color:darkorange">B * M</span> + M <span style=color:darkorange>+ C</span></TD>
</TR>

<TR>
	<TD align=left>3</TD>
	<td style="padding-left:30px"></td>	
	<TD align=left>B + 1 + 1</TD>
	<td style="padding-left:30px"></td>	
	<TD align=left>(B + 1 + 1) * M + C = <span style="color:darkorange">B * M + M</span> + M <span style="color:darkorange">+ C</span></TD>
</TR>
</table>
</center>

Note that in each case, the part of the induction expression highlighted in <span style="color:darkorange">orange</span>
is the same as the value of the whole expression on the previous iteration,
and the non-highlighted part each time is always &nbsp <tt>+ M</tt>.
In other words, each time around the loop, the induction expression
increases by adding <tt>M</tt>, a <em>constant</em> value!
So we can avoid doing the multiplication each time around the loop by:
<UL>
  <LI> computing <tt>B * M + C</tt> once before the loop,
  <LI> storing that value in a temporary,
  <LI> using the temporary instead of the expression inside the loop, and
  <LI> incrementing the temporary by <tt>M</tt> at the end of the loop.
</UL>
Here is the transformed loop:
<UL>
<p>
<span class=code>
ind = B * M + C //Initialize temp to first value of expression<br />
for L from B to E do {<br />
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $\vdots$<br />
&nbsp&nbsp $\ldots$ = ind //Use ind instead of recalculating expression<br />
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $\vdots$<br />
&nbsp&nbsp ind = ind + M //Increment ind at the end of the loop by M<br />
}</span>
</p>
<p>
Note that instead of doing a multiplication and an addition each time
around the loop, we now do just one addition each time.
Although in this example we've <em>removed</em> a multiplication,
in general we are replacing a multiplication with an addition (that is
why this optimization is called <em>reduction</em> in strength).
Although this pattern may seem restrictive, in practice many loops fit into
this template, especially since we allow <span class=code>M</span> or <span>C</span>
to be absent. 
In particular, if there were no <span class=code>C</span>, the original induction
expression would be: <tt>L * M</tt>, and that would be replaced
inside the loop by: <tt>ind = ind + M</tt>;
an addition replaces a multiplication.
</p>

<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
Some languages actually have for-loops with the syntax used above
(<tt>for i from low to high do ...</tt>), but other languages
(including Java) do not use that syntax.
Must a Java compiler give up on performing this optimization, or
might it be able to recognize opportunities in some cases?
<P>
<p><a href="SOLUTIONS/OPTIMIZATION-ANSWERS.html#ans3">solution</a></p>
<hr>
<P>
As mentioned above, many loops naturally fit the template for strength reduction that
we defined above. 
Now let's see how to apply this optimization to the example
code we used to illustrate moving loop-invariant computations
out of the loop.
Below is the code we had after moving the loop-invariant
computations. Each induction expression is circled and identified by a number:
<div align=center style="margin-top:20px; overflow:auto; width:100%; text-align:center">
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
	 style="display:inline; float:left; margin-right:50px; width:255px; height:260px"
   width="255"
   height="260"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(3.9489336,-953.89179)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="-1.9489336"
       y="972.11835"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-1.9489336"
         y="972.11835"
         id="tspan4722"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">tmp0 = FP + offsetA</tspan><tspan
         x="-1.9489336"
         y="992.11835"
         id="tspan3009"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">for (i=0; i&lt;100; i++){</tspan><tspan
         x="-1.9489336"
         y="1012.1183"
         id="tspan4724"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  tmp1 = tmp0 - i * 40000</tspan><tspan
         x="-1.9489336"
         y="1032.1184"
         id="tspan4728"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  for (j=0; j&lt;100; j++){</tspan><tspan
         x="-1.9489336"
         y="1052.1184"
         id="tspan3011"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp2 = tmp1 - j*400</tspan><tspan
         x="-1.9489336"
         y="1072.1184"
         id="tspan3015"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp3 = i*j</tspan><tspan
         x="-1.9489336"
         y="1092.1184"
         id="tspan3017"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    for (k=0; k&lt;100; k++){</tspan><tspan
         x="-1.9489336"
         y="1112.1184"
         id="tspan3019"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T0 = tmp3 * k</tspan><tspan
         x="-1.9489336"
         y="1132.1184"
         id="tspan3023"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T1 = tmp2 - k*4</tspan><tspan
         x="-1.9489336"
         y="1152.1184"
         id="tspan3025"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      store T0, 0(T1)</tspan><tspan
         x="-1.9489336"
         y="1172.1184"
         id="tspan3021"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    }</tspan><tspan
         x="-1.9489336"
         y="1192.1184"
         id="tspan3013"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  }</tspan><tspan
         x="-1.9489336"
         y="1212.1184"
         id="tspan4726"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">}</tspan></text>
    <text
       x="240.52188"
       y="977.31653"
       id="text4476"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="240.52188"
         y="977.31653"
         id="tspan4749"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">#1</tspan></text>
    <path
       d="M 125.73704,1098.853 C 125.73704,1098.853 109.83159,1097.1199 105.57247,1101.3585 101.31336,1105.5971 103.2326,1112.465 105.08998,1114.5595 106.94737,1116.6541 189.92636,1117.728 192.16525,1114.8967 197.45957,1112.3533 190.80904,1106.3349 187.41798,1102.4903 183.18959,1096.7574 130.68209,1101.0757 120.50467,1101.428"
       id="path4730"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 233.10175,974.47226 C 223.91523,972.8326 221.21106,989.48676 221.97555,998.1087"
       id="path4751"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 151.12294,1120.8349 C 149.09996,1121.5092 112.77069,1117.6318 107.62895,1118.8119 102.48721,1119.992 99.87421,1126.5667 99.87421,1128.2525 99.87421,1129.9383 99.8462,1134.1365 107.62895,1137.693 115.4117,1141.2495 133.69076,1136.457 146.73982,1136.6815 159.78888,1136.9061 175.86471,1139.4724 185.85069,1139.0417 195.83667,1138.6109 207.07846,1140.0037 210.41762,1136.0072 213.83499,1131.4059 209.4032,1120.1093 207.01195,1118.9188 204.6207,1117.7283 153.14592,1120.1606 151.12294,1120.8349 z"
       id="path3029"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 173.15488,997.2008 C 161.29889,997.2008 130.29468,996.6966 115.15174,997.2008 100.0088,997.705 88.252944,993.43178 82.319775,999.3896 76.386606,1005.3475 77.750893,1012.6318 81.225376,1016.9 84.699859,1021.1682 95.952695,1016.7614 108.54174,1017.5411 121.13079,1018.3208 139.59265,1018.5898 156.7389,1017.9944 173.88515,1017.399 186.64075,1015.927 199.42045,1015.8056 212.20015,1015.6843 225.6893,1016.4288 233.34682,1016.9 241.00434,1017.3712 242.02212,1020.3297 245.3852,1017.9944 248.74828,1015.6592 249.57003,1009.8722 248.6684,1005.956 247.76677,1002.0399 246.65094,999.6152 241.00761,997.2008 235.36428,994.78648 224.96371,998.2659 215.83643,998.2952 206.70915,998.3246 193.40342,997.3326 186.28767,997.2008 179.17192,997.0691 185.01087,997.2008 173.15488,997.2008 z"
       id="path3031"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 154.37015,1039.0001 C 139.65784,1039.0496 137.88418,1037.8758 128.83287,1038.2263 119.78156,1038.5767 105.18617,1036.0531 100.20017,1041.3217 95.214169,1046.5903 97.019091,1050.7689 99.42631,1054.025 101.83352,1057.2811 97.262713,1055.0124 108.7126,1056.3466 120.16248,1057.6807 149.72298,1057.4161 168.29957,1057.5727 186.87616,1057.7293 210.16811,1061.5072 220.14798,1059.1204 230.12785,1056.7336 227.09185,1058.1847 228.6604,1054.4773 230.8851,1039.9657 219.02646,1037.1051 217.05255,1036.6786 205.42595,1034.1602 169.08246,1038.9507 154.37015,1039.0001 z"
       id="path3033"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 110.26031,1059.1204 C 104.83062,1060.0015 103.46368,1061.5691 100.97403,1064.2159 98.48438,1066.8626 96.305863,1070.0569 97.878596,1073.8237 99.45133,1077.5905 102.22494,1076.6814 108.7126,1078.0146 115.20026,1079.3478 132.51029,1081.4346 137.79758,1076.9191 146.9269,1067.3968 143.17518,1060.9754 138.64513,1059.6373 134.11507,1058.2992 115.69,1058.2393 110.26031,1059.1204 z"
       id="path3035"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="237.84561"
       y="1074.6564"
       id="text3041"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="237.84561"
         y="1074.6564"
         id="tspan3043"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">#2</tspan></text>
    <path
       d="M 142.93777,1068.2761 168.23009,1072.1671"
       id="path3047"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="179.96556"
       y="1074.8645"
       id="text3817"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="179.96556"
         y="1074.8645"
         id="tspan3819"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">#3</tspan></text>
    <text
       x="234.17299"
       y="1114.8677"
       id="text3821"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="234.17299"
         y="1114.8677"
         id="tspan3823"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">#4</tspan></text>
    <text
       x="205.90195"
       y="1167.4542"
       id="text3825"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="205.90195"
         y="1167.4542"
         id="tspan3827"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">#5</tspan></text>
    <path
       d="M 225.01597,1106.3409 C 215.43509,1101.75 202.85274,1099.6628 192.65276,1108.5746"
       id="path3829"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 219.71794,1152.9917 C 237.36713,1142.018 240.54007,1141.1775 212.06891,1129.6543"
       id="path3020"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 228.29115,1070.9533 C 228.29115,1070.9533 221.73304,1071.0394 218.68501,1069.1744 215.63698,1067.3094 211.56934,1059.924 211.56934,1059.924"
       id="path3021"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
<table class=cfg style="display:inline; float:left">
<TR>
	<TD align=left style="border-bottom:1px solid"><b><br />Original Expression</b></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left style="border-bottom:1px solid"><b>Loop<br />Index (L)</b></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left style="border-bottom:1px solid"><b>Multiply<br /> Term (M)</b></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left style="border-bottom:1px solid"><b>Addition<br />Term (C)</b></TD>
</TR>

<TR>
	<TD align=left>#1: <span style="font-family:Cutive Mono">tmp0 - i * 40000</span></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>i</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>-40000</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>tmp0</TD>
</TR>

<TR>
	<TD align=left>#2: <span style="font-family:Cutive Mono">tmp1 - j * 400</span></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>j</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>-400</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>tmp1</TD>
</TR>

<TR>
	<TD align=left>#3: <span style="font-family:Cutive Mono">i * j</span></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>j</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>i</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>0</TD>
</TR>

<TR>
	<TD align=left>#4: <span style="font-family:Cutive Mono">tmp3 * k</span></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>k</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>tmp3</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>0</TD>
</TR>

<TR>
	<TD align=left>#5: <span style="font-family:Cutive Mono">tmp2 - k * 4</span></TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>k</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>-4</TD>
	<td style="padding-left:15px"></td>	
	<TD align=left>tmp2</TD>
</TR>
</table>
<div style="clear:both">&nbsp</div>
</div>
<br />

<P>
After performing the reduction in strength optimizations:
<div align=center style="margin-top:20px; margin-bottom:20px">
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="367"
   height="455"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(1.9489336,-958.89179)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="0.051066399"
       y="970.11835"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="0.051066399"
         y="970.11835"
         id="tspan4722"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">tmp0 = FP + offsetA</tspan><tspan
         x="0.051066399"
         y="990.11835"
         id="tspan3022"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind1 = tmp0</tspan><tspan
         x="0.051066399"
         y="1010.1183"
         id="tspan3009"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">for (i=0; i&lt;100; i++){</tspan><tspan
         x="0.051066399"
         y="1030.1184"
         id="tspan4724"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  tmp1 = ind1</tspan><tspan
         x="0.051066399"
         y="1050.1184"
         id="tspan3026"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  ind2 = tmp1</tspan><tspan
         x="0.051066399"
         y="1070.1184"
         id="tspan3024"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  ind3 = 0</tspan><tspan
         x="0.051066399"
         y="1090.1184"
         id="tspan4728"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  for (j=0; j&lt;100; j++){</tspan><tspan
         x="0.051066399"
         y="1110.1184"
         id="tspan3011"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp2 = ind2</tspan><tspan
         x="0.051066399"
         y="1130.1184"
         id="tspan3015"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp3 = ind3</tspan><tspan
         x="0.051066399"
         y="1150.1184"
         id="tspan3032"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind4 = 0</tspan><tspan
         x="0.051066399"
         y="1170.1184"
         id="tspan3034"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind5 = tmp2</tspan><tspan
         x="0.051066399"
         y="1190.1184"
         id="tspan3017"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    for (k=0; k&lt;100; k++){</tspan><tspan
         x="0.051066399"
         y="1210.1184"
         id="tspan3019"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T0 = ind4</tspan><tspan
         x="0.051066399"
         y="1230.1184"
         id="tspan3023"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T1 = ind5</tspan><tspan
         x="0.051066399"
         y="1250.1184"
         id="tspan3025"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      store T0, 0(T1)</tspan><tspan
         x="0.051066399"
         y="1270.1184"
         id="tspan3036"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      ind4 = ind4 + tmp3</tspan><tspan
         x="0.051066399"
         y="1290.1184"
         id="tspan3038"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      ind5 = ind5 - 4</tspan><tspan
         x="0.051066399"
         y="1310.1184"
         id="tspan3021"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    }</tspan><tspan
         x="0.051066399"
         y="1330.1184"
         id="tspan3040"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind2 = ind2 - 400</tspan><tspan
         x="0.051066399"
         y="1350.1184"
         id="tspan3042"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind3 = ind3 + i</tspan><tspan
         x="0.051066399"
         y="1370.1184"
         id="tspan3013"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  }</tspan><tspan
         x="0.051066399"
         y="1390.1184"
         id="tspan3044"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  ind1 = ind1 - 40000</tspan><tspan
         x="0.051066399"
         y="1410.1184"
         id="tspan4726"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">}</tspan></text>
    <path
       d="M 148.77765,985.80548 C 141.86868,986.47284 138.03033,986.72755 127.6125,986.72755 117.19467,986.72755 113.39871,985.30931 113.39871,985.30931"
       id="path3046"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="156.05107"
       y="970.11835"
       id="text3048"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="156.05107"
         y="970.11835"
         id="tspan3050"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="156.05107"
         y="990.11835"
         id="tspan3094"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind1 = 0*-40000+tmp0</tspan></text>
    <path
       d="M 117.81092,981.82682 113.39871,985.29355 117.49576,989.70576"
       id="path3972"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 166.77765,1045.8055 C 159.86868,1046.4728 156.03033,1046.7275 145.6125,1046.7275 135.19467,1046.7275 131.39871,1045.3093 131.39871,1045.3093"
       id="path3974"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="174.05107"
       y="1030.1183"
       id="text3976"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="174.05107"
         y="1030.1183"
         id="tspan3978"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="174.05107"
         y="1050.1183"
         id="tspan3980"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind2 = 0*-400+tmp1</tspan></text>
    <path
       d="M 135.81092,1041.8268 131.39871,1045.2935 135.49576,1049.7058"
       id="path3982"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 160.48897,1065.3093 C 160.48897,1065.3093 147.68662,1063.1381 140.77765,1063.8055 133.86868,1064.4728 130.03033,1066.7275 119.6125,1066.7275 109.19467,1066.7275 105.39871,1065.3093 105.39871,1065.3093"
       id="path3984"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="164.05107"
       y="1050.1183"
       id="text3986"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="164.05107"
         y="1050.1183"
         id="tspan3988"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="164.05107"
         y="1070.1183"
         id="tspan3990"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind3 = 0*i+0</tspan></text>
    <path
       d="M 109.81092,1061.8268 105.39871,1065.2935 109.49576,1069.7058"
       id="path3992"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 156.77765,1145.8055 C 149.86868,1146.4728 146.03033,1146.7275 135.6125,1146.7275 125.19467,1146.7275 121.39871,1145.3093 121.39871,1145.3093"
       id="path4002"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="160.05107"
       y="1130.1183"
       id="text4004"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="160.05107"
         y="1130.1183"
         id="tspan4006"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="160.05107"
         y="1150.1183"
         id="tspan4008"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind4 = 0*tmp3+0</tspan></text>
    <path
       d="M 125.81092,1141.8268 121.39871,1145.2935 125.49576,1149.7058"
       id="path4010"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 186.77765,1165.8055 C 179.86868,1166.4728 176.03033,1166.7275 165.6125,1166.7275 155.19467,1166.7275 151.39871,1165.3093 151.39871,1165.3093"
       id="path4012"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="190.05107"
       y="1150.1183"
       id="text4014"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="190.05107"
         y="1150.1183"
         id="tspan4016"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="190.05107"
         y="1170.1183"
         id="tspan4018"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind5 = 0*-4+tmp2</tspan></text>
    <path
       d="M 155.81092,1161.8268 151.39871,1165.2935 155.49576,1169.7058"
       id="path4020"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
</div>

In the original code, the innermost loop (executed 1,000,000 times) had
two multiplications and one subtraction.
In the optimized code, the inner loop has no multiplications,
one subtraction, and one addition.
(Similarly, the middle loop went from two multiplications and one
subtraction to no multiplications, one subtraction, and one addition;
the outer loop went from one multiplication and one subtraction to
no multiplications and one subtraction.)
On the other hand, we have added a number of assignments;
for example, the inner loop had just two assignments, and now it
has four.
We'll deal with that in the next section using <a href="#copy prop">copy 
propagation</a>
<hr>
<a name=youtry4>
<P>
<center>
<u><b>TEST YOURSELF #4</b></u>
</center></a>
<P>
Suppose that the index variable is incremented by something other than
one each time around the loop.
For example, consider a loop of the form:
<pre>
for (i=low; i<=high; i+=2) ...
</pre>
Can strength reduction still be performed?
If yes, what changes must be made to the proposed algorithm?
<P>
<p><a href="SOLUTIONS/OPTIMIZATION-ANSWERS.html#ans4">solution</a></p>
<hr>
<A name="copy prop"></a>
<H2> Optimization #3: Copy propagation</H2>
Statements of the form <tt>x = y</tt> (call this definition $d$)
are called <em>copy statements</em>.
For every use $u$ of variable <tt>x</tt> reached by definition $d$
such that:
<OL class=style1>
  <LI>no other definition of <tt>x</tt> reaches $u$, and
  <LI><tt>y</tt> can't change between $d$ and $u$
</OL>
we can replace the use of <tt>x</tt> at $u$ with a use of <tt>y</tt>.

<P>
Examples:
</p>
<div align=center style="width:100%; height: 200px; display:inline-block">
<div>
	<div style="display:block; margin:auto; float:left">
	<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="72"
   height="107"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-13.124102,-1000.9464)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="54.110207"
       y="1072.9584"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="57.989113"
         y="1072.9584"
         id="tspan3021"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">x can be </tspan><tspan
         x="57.989113"
         y="1087.9584"
         id="tspan3025"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">replaced </tspan><tspan
         x="54.110207"
         y="1102.9584"
         id="tspan3023"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">with y</tspan></text>
    <path
       d="M 22.176777,1073.9731 C 22.176777,1073.9731 12.808981,1073.1889 16.474874,1065.6655 20.140766,1058.1421 35.226371,1060.238 42.456161,1058.1754 50.752103,1055.8086 55.112713,1048.2963 55.112713,1048.2963"
       id="path3027"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 51.314379,1049.2186 55.284532,1048.2074 55.594045,1052.3832"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="16.965042"
       y="1024.7806"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="16.965042"
         y="1024.7806"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">x = y</tspan><tspan
         x="16.965042"
         y="1044.7806"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">a = x+z</tspan></text>
  </g>
</svg>
	</div>
</div>

<div>
	<div style="display:block; margin-left:50px; margin-right:50px; float:left">
	<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="140"
   height="140"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-13.224015,-996.10618)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="94.94355"
       y="1083.3126"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="98.822456"
         y="1083.3126"
         id="tspan3021"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">x cannot be </tspan><tspan
         x="98.822456"
         y="1098.3126"
         id="tspan3023"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">replaced with </tspan><tspan
         x="94.94355"
         y="1113.3126"
         id="tspan3971"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">y; violates</tspan><tspan
         x="94.94355"
         y="1128.3126"
         id="tspan3967"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">condition 1</tspan></text>
    <path
       d="M 52.846088,1103.9541 C 52.846088,1103.9541 35.83647,1099.8039 32.857973,1092.1908 29.879476,1084.5777 52.058503,1060.4844 52.058503,1060.4844"
       id="path3027"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 48.491412,1060.8487 52.529718,1060.1585 52.504108,1064.3457"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="13.888078"
       y="1016.54"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="13.888078"
         y="1016.54"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">x = y</tspan><tspan
         x="13.888078"
         y="1036.54"
         id="tspan3181"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">if (...) x = 2</tspan><tspan
         x="13.888078"
         y="1056.54"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">a = x+z</tspan></text>
  </g>
</svg>
	</div>
</div>

<div style="display:block">
	<div style="float:left">
	<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="145px"
   height="117px"
	 style="width:145px; height:117px; display:block-inline"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-13.422558,-991.31414)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="88.110207"
       y="1074.3622"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="91.989113"
         y="1074.3622"
         id="tspan3021"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">x cannot be </tspan><tspan
         x="88.110207"
         y="1089.3622"
         id="tspan3967"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">replaced with y;</tspan><tspan
         x="88.110207"
         y="1104.3622"
         id="tspan3408"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">violates condition 2</tspan></text>
    <g
       transform="matrix(0.99557457,0.09397486,-0.09397486,0.99557457,87.99747,-7.0202806)"
       id="g3997">
      <path
         d="M 57.165977,1080.7721 C 57.165977,1080.7721 45.221954,1084.8074 42.243457,1077.1943 39.26496,1069.5812 63.508675,1056.1159 63.508675,1056.1159"
         id="path3027"
         style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
      <path
         d="M 60.061027,1055.8901 63.649191,1055.701 63.339483,1058.7257"
         id="path3805"
         style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    </g>
    <text
       x="16.08662"
       y="1008.9141"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="16.08662"
         y="1008.9141"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">x = y</tspan><tspan
         x="16.08662"
         y="1028.9141"
         id="tspan3349"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">if (...) y = 3</tspan><tspan
         x="16.08662"
         y="1048.9141"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">a = x+z</tspan></text>
  </g>
</svg>
	</div>
</div>
</div>

<P>
Question: Why is this a useful transformation?
<P>
Answers:
<UL>
  <LI>If <U>all</U> uses of <tt>x</tt> reached by $d$ are replaced,
			then definition $d$ is <em>useless</em>, and can be removed.
  <P>
  <LI>Even if the definition cannot be removed, copy propagation
      can lead to improved code:
     <OL class=style1>
       <LI> If the definition is actually of the form: <tt>x = literal</tt>,
            then copy propagation can create opportunities for better code:
			<div align=center>
			<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="280"
   height="60"
   id="svg2">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-18.30098,-986.17127)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="147.8726"
       y="999.77283"
       id="text3019"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="147.8726"
         y="999.77283"
         id="tspan3054"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">after copy</tspan><tspan
         x="147.8726"
         y="1014.7728"
         id="tspan3056"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">propagation</tspan></text>
    <path
       d="M 175.25197,1021.9202 178.513,1024.4002 175.43557,1027.2397"
       id="path3805"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="18.965042"
       y="1022.7806"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="18.965042"
         y="1022.7806"
         id="tspan3068"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">x = 5;</tspan><tspan
         x="18.965042"
         y="1042.7805"
         id="tspan3074"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">a = b + x;</tspan></text>
    <text
       x="202.58893"
       y="1022.7806"
       id="text3042"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="202.58893"
         y="1022.7806"
         id="tspan3044"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">x = 5;</tspan><tspan
         x="202.58893"
         y="1042.7805"
         id="tspan3046"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">a = b + 5;</tspan></text>
    <path
       d="M 123.79834,1030.3981 C 123.79834,1030.3981 130.36191,1024.9171 135.38608,1024.7608 140.41025,1024.6046 144.99871,1031.1639 151.35838,1031.3377 157.71805,1031.5114 166.50499,1025.1183 171.08886,1024.4476 175.67273,1023.777 177.97887,1024.7608 177.97887,1024.7608"
       id="path3048"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
			</div>
			For a machine like the MIPS, there are fast instructions that can be used
			when one of the operands is an "immediate" (literal) value. These 
			instructions can be used for operation <tt>a = b + 5</tt>, since one of the 
			operands is a literal, but not for <tt>a = b + x</tt>.  The improvemnt is even 
			more striking because MIPS doesn't allow arithmetic operands to be memory 
			locations, so to generate assembly for statements like <tt>a = b + x</tt>, it 
			would be necessary to load the values for both <tt>b</tt> and <tt>x</tt> into registers,
			which would require additional <tt>load</tt> instructions<sup><a href="#" class=tooltip>*
<span><img class="callout" src="css/callout.gif"/>It's worth noting that compilers work hard to keep values in registers and avoid loads. This process is called register allocation. As such, it's possible that all operand values are already in registers when an operation occurs, so it may not necessarily be true that we're saving a load here. Nevertheless, it's always better to use constants where possible.</span></a> 
</sup>
</p>
<p>
	    Furthermore, this kind of copy propagation can lead to 
	    opportunities for <em>constant folding</em>: evaluating,
	    at compile time,
	    an expression that involves only literals.
	    For example:
			<div align=center>
						<!--<object data="svgs/opt_copyprop_constantfolding.svg" type="image/svg+xml" id="optcf" width="100%" height="100%" align=center></object>-->
			</div>



      <LI> Sometimes copy propagation can be combined with moving
           loop-invariant computations out of the loop, to lead to a better
	   overall optimization.
	   For example:
	   <pre>
	   while (...) {
	      x = a * b; // loop-inv
	      y = x * c;
	      ...
	   }
	   </pre>
	   Move "<tt>a * b</tt>" out of the loop:
	   <pre>
	   tmp1 = a * b;
	   while (...) {
	      x = tmp1;
	      y = x * c;
	      ...
	   }
	   </pre>
	   Note that at this point, even if <tt>c</tt> is not modified in
	   the loop, we cannot move "<tt>x * c</tt>" out
	   of the loop, because <tt>x</tt> gets its value inside the loop.
	   However, after we do copy propagation:
	   <pre>
	   tmp1 = a * b;
	   while (...) {
	      x = tmp1;
	      y = tmp1 * c;
	      ...
	   }
	   </pre>
	   "<tt>tmp1 * c</tt>" can also be moved out of the loop:
	   <pre>
	   tmp1 = a * b;
	   tmp2 = tmp1 * c;
	   while (...) {
	      x = tmp1;
	      y = tmp2;
	      ...
	   }
	   </pre>
	   
     </OL>
</UL>
<P>
Given a definition d that is a copy statement: <tt>x = y</tt>,
and a use $u$ of <tt>x</tt>, we must
determine whether the two important properties hold that permit
the use of <tt>x</tt> to be replaced with <tt>y</tt>.
<P>
The first property (use $u$ is reached only by definition $d$)
is best solved using the standard "reaching-definitions" dataflow-analysis
problem, which computes, for each definition of a variable <tt>x</tt>,
all of the uses of <tt>x</tt> that might be reached by that definition.
Note that this property can also be determined by doing a backward
depth-first or breadth-first search in the control-flow graph, starting at
use $u$, and terminating a branch of the search when a definition of <tt>x</tt>
is reached.  If definition d is the <em>only</em> definition encountered
in the search, then it is the only one that reaches use $u$.
(This technique will, in general, be less efficient than doing
reaching-definitions analysis.)
<P>
The second property (that variable <tt>y</tt> cannot change its value
between definition d and use $u$), can also be verified using dataflow
analysis, or using a backwards search in the control-flow graph starting
at $u$, and quitting at $d$.
If no definition of <tt>y</tt> is encountered during the search, then
its value cannot change, and the copy propagation can be performed.
Note that when <tt>y</tt> is a literal, property (2) is always satisfied.

<P>
Below is our running example (after doing reduction in strength).
Each copy statements either has a red X next to it (if it can't be 
propagated) or a green check (if it can be propagated).
In this particular example, each variable x that is defined in a copy
statement reaches only <em>one</em> use.
Comments indicate which of them cannot be propagated (because of a violation
of property (1) -- in this example there are no instances where property (2)
is violated).
<div align=center style="margin-top:20px; margin-bottom:20px">
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="410.80426"
   height="457.1572"
   id="opt_copyprop_big">
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
        <dc:identifier>opt_copyprop_big</dc:identifier>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(133.70045,-954.73458)"
     id="layer1">
    <flowRoot
       id="flowRoot2985"
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           id="rect2989"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       x="0.051066399"
       y="970.11835"
       id="text3062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="0.051066399"
         y="970.11835"
         id="tspan4722"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">tmp0 = FP + offsetA</tspan><tspan
         x="0.051066399"
         y="990.11835"
         id="tspan3022"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind1 = tmp0</tspan><tspan
         x="0.051066399"
         y="1010.1183"
         id="tspan3009"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">for (i=0; i&lt;100; i++){</tspan><tspan
         x="0.051066399"
         y="1030.1184"
         id="tspan4724"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  tmp1 = ind1</tspan><tspan
         x="0.051066399"
         y="1050.1184"
         id="tspan3026"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  ind2 = tmp1</tspan><tspan
         x="0.051066399"
         y="1070.1184"
         id="tspan3024"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  ind3 = 0</tspan><tspan
         x="0.051066399"
         y="1090.1184"
         id="tspan4728"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  for (j=0; j&lt;100; j++){</tspan><tspan
         x="0.051066399"
         y="1110.1184"
         id="tspan3011"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp2 = ind2</tspan><tspan
         x="0.051066399"
         y="1130.1184"
         id="tspan3015"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    tmp3 = ind3</tspan><tspan
         x="0.051066399"
         y="1150.1184"
         id="tspan3032"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind4 = 0</tspan><tspan
         x="0.051066399"
         y="1170.1184"
         id="tspan3034"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind5 = tmp2</tspan><tspan
         x="0.051066399"
         y="1190.1184"
         id="tspan3017"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    for (k=0; k&lt;100; k++){</tspan><tspan
         x="0.051066399"
         y="1210.1184"
         id="tspan3019"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T0 = ind4</tspan><tspan
         x="0.051066399"
         y="1230.1184"
         id="tspan3023"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      T1 = ind5</tspan><tspan
         x="0.051066399"
         y="1250.1184"
         id="tspan3025"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      store T0, 0(T1)</tspan><tspan
         x="0.051066399"
         y="1270.1184"
         id="tspan3036"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      ind4 = ind4 + tmp3</tspan><tspan
         x="0.051066399"
         y="1290.1184"
         id="tspan3038"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">      ind5 = ind5 - 4</tspan><tspan
         x="0.051066399"
         y="1310.1184"
         id="tspan3021"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    }</tspan><tspan
         x="0.051066399"
         y="1330.1184"
         id="tspan3040"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind2 = ind2 - 400</tspan><tspan
         x="0.051066399"
         y="1350.1184"
         id="tspan3042"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">    ind3 = ind3 + i</tspan><tspan
         x="0.051066399"
         y="1370.1184"
         id="tspan3013"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  }</tspan><tspan
         x="0.051066399"
         y="1390.1184"
         id="tspan3044"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">  ind1 = ind1 - 40000</tspan><tspan
         x="0.051066399"
         y="1410.1184"
         id="tspan4726"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">}</tspan></text>
    <path
       d="M -39.786379,971.78777 C -32.877409,975.15443 -29.039059,976.90876 -18.621229,980.97895 -8.2033989,985.04914 -4.4074389,985.11396 -4.4074389,985.11396"
       id="line1"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M -43.021989,1085.8933 C -29.766412,1095.4863 -3.4292679,1068.8716 11.252337,1067.1001"
       id="line3"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M -0.53487609,1176.3507 C -3.3409065,1168.4045 15.12726,1170.2185 32.806456,1164.507"
       id="line5"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="-97.584396"
       y="1169.5599"
       id="text5"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-97.584396"
         y="1169.5599"
         id="tspan4016"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="-97.584396"
         y="1189.5599"
         id="tspan3059"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind5 <tspan
   id="tspan3149"
   style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Purisa;-inkscape-font-specification:Purisa">updated</tspan></tspan><tspan
         x="-97.584396"
         y="1205.135"
         id="tspan3096"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">in k-loop</tspan></text>
    <path
       d="M 126.46957,1095.6372 C 118.94173,1096.0053 107.74185,1096.2246 104.13592,1100.104 100.52999,1103.9834 102.28767,1106.4465 103.01923,1109.0374 103.75079,1111.6283 101.09057,1109.4231 107.48596,1112.6208 113.88135,1115.8185 136.45916,1116.7762 143.21981,1113.7375 149.98046,1110.6988 147.6748,1112.1541 148.80322,1109.0374 149.93164,1105.9207 152.56217,1102.0242 148.80322,1097.8706 145.04427,1093.717 133.99741,1095.2691 126.46957,1095.6372 z"
       id="ind2circle"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 103.03214,1107.6894 C 103.03214,1107.6894 98.446494,1116.745 99.682084,1131.4191 100.91766,1146.0932 100.16207,1145.6449 103.59048,1159.336"
       id="ind2arrowline"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M -10.236707,1130.4872 C 9.0241812,1125.2984 18.714635,1143.9717 33.889833,1145.3541"
       id="line4"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       x="-106.568"
       y="1105.6342"
       id="text4"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-106.568"
         y="1105.6342"
         id="tspan3067"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="-106.568"
         y="1125.6342"
         id="tspan3071"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind4 <tspan
   id="tspan3147"
   style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Purisa;-inkscape-font-specification:Purisa">updated</tspan></tspan><tspan
         x="-106.568"
         y="1141.2092"
         id="tspan3092"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">in k-loop</tspan></text>
    <path
       d="M 105.33932,1152.4154 103.93241,1160.1248 94.600814,1158.3006"
       id="ind2arrowhead"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="-125.30956"
       y="1005.6997"
       id="text2"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-125.30956"
         y="1005.6997"
         id="tspan3081"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="-125.30956"
         y="1025.6997"
         id="tspan3085"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind2 <tspan
   id="tspan3143"
   style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Purisa;-inkscape-font-specification:Purisa">updated</tspan></tspan><tspan
         x="-125.30956"
         y="1041.2748"
         id="tspan3076"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">in j-loop</tspan></text>
    <path
       d="M -25.718097,1039.8705 C -18.809127,1040.5378 -10.606125,1046.7275 -0.188295,1046.7275 10.229535,1046.7275 12.025495,1045.3093 12.025495,1045.3093"
       id="line2"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       d="M 103.01924,1017.5611 C 95.688981,1017.5995 88.204459,1016.1465 84.035629,1020.3528 79.866799,1024.5591 79.744107,1026.527 81.802263,1030.5447 83.860419,1034.5624 84.07079,1034.0289 91.294066,1035.5697 98.517342,1037.1105 122.89189,1036.2555 128.79455,1033.3364 135.20128,1030.2145 134.34983,1023.8957 131.58626,1020.9111 127.14302,1016.2132 110.3495,1017.5227 103.01924,1017.5611 z"
       id="ind1circle"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 133.26128,1023.6112 C 133.26128,1023.6112 142.86646,1025.3449 143.86976,1029.753 144.87306,1034.1611 135.49464,1042.0365 135.49464,1042.0365"
       id="ind1arrowline"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 135.49581,1036.0616 135.49581,1042.7733 141.41792,1041.194"
       id="ind1arrowhead"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="-130.57721"
       y="946.53058"
       id="text1"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-130.57721"
         y="946.53058"
         id="tspan3101"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="-130.57721"
         y="966.53058"
         id="tspan3103"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind1 <tspan
   id="tspan3137"
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Purisa;-inkscape-font-specification:Purisa"><tspan
     id="tspan3141"
     style="font-size:12px">updated </tspan></tspan></tspan><tspan
         x="-130.57721"
         y="982.10565"
         id="tspan3105"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">in i-loop</tspan></text>
    <path
       d="M 122.27675,158.52076 C 115.20997,158.93151 110.95174,158.53047 108.31822,161.87081 103.29838,172.70212 109.97314,174.19445 112.78495,174.71266 118.50269,175.80425 140.8227,177.4203 146.93538,175.271 154.37206,172.38687 157.02501,165.43277 154.19381,161.87081 150.37932,157.24936 129.34353,158.11001 122.27675,158.52076 z"
       transform="translate(-1.9489336,958.89179)"
       id="ind3circle"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 151.96045,171.36261 C 151.96045,171.36261 240.07303,180.67515 262.97876,209.88816 285.88448,239.10117 286.37753,279.22388 245.06169,307.03955"
       transform="translate(-1.9489336,958.89179)"
       id="ind3arrowline"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 243.90447,1258.1146 241.11276,1265.9313 248.3712,1267.6064 248.3712,1267.6064 248.3712,1267.6064"
       id="ind3arrowhead"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 127.12783,237.85619 C 123.44098,237.77608 112.38018,234.92066 108.96671,237.06657 105.55324,239.21248 107.07092,239.78403 106.59787,242.59387 106.12482,245.40371 105.48907,251.20329 107.38748,253.64847 109.28589,256.09365 107.90527,255.11229 114.49401,256.01731 121.08275,256.92233 144.90818,257.07873 150.86818,254.43808 156.82351,252.02557 158.23164,244.24055 155.60586,241.01464 152.4091,237.28847 135.18383,238.17034 131.0759,237.85619 126.96797,237.54204 130.81468,237.9363 127.12783,237.85619 z"
       transform="translate(-1.9489336,958.89179)"
       id="ind4circle"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 109.75633,254.43808 C 109.75633,254.43808 102.72907,257.76428 101.07058,261.93941 99.412092,266.11454 99.854251,272.98157 102.6498,277.33688 105.44535,281.69219 116.07324,285.23302 116.07324,285.23302"
       transform="translate(-1.9489336,958.89179)"
       id="ind4arrowline"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 116.07324,278.1265 116.86285,285.23302 110.54594,286.81225"
       transform="translate(-1.9489336,958.89179)"
       id="ind4arrowhead"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 128.70706,259.17576 C 122.12695,259.17576 116.49045,257.26212 112.91478,259.17576 109.33911,261.0894 108.81181,263.32513 108.1771,266.28229 107.54239,269.23945 103.7148,270.63971 109.75633,275.75765 115.79786,280.87559 140.44615,278.74481 147.65779,275.75765 154.86943,272.77049 152.97293,272.50081 153.9747,269.44074 154.97647,266.38067 156.70566,262.87355 152.39547,259.17576 148.08528,255.47797 135.28718,259.17576 128.70706,259.17576 z"
       transform="translate(-1.9489336,958.89179)"
       id="ind5circle"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 153.18509,266.28229 C 170.4287,268.89486 169.37219,266.40074 180.03196,278.1265"
       transform="translate(-1.9489336,958.89179)"
       id="ind5arrowline"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 172.13582,276.54727 180.82157,278.1265 181.61119,271.01997"
       transform="translate(-1.9489336,958.89179)"
       id="ind5arrowhead"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="-134.87645"
       y="1057.2017"
       id="text3"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-134.87645"
         y="1057.2017"
         id="tspan3084"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         x="-134.87645"
         y="1077.2017"
         id="tspan3086"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">ind3 <tspan
   id="tspan3145"
   style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Purisa;-inkscape-font-specification:Purisa">updated</tspan></tspan><tspan
         x="-134.87645"
         y="1092.7767"
         id="tspan3088"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">in j-loop</tspan></text>
    <path
       d="M 10.84148,102.1273 17.835984,112.96879"
       transform="translate(-1.9489336,958.89179)"
       id="path3098"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 10.14203,112.96879 17.136533,102.47703"
       transform="translate(-1.9489336,958.89179)"
       id="path3100"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 8.8925464,1041.0191 15.88705,1051.8606"
       id="path3102"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 8.1930964,1051.8606 15.187599,1041.3688"
       id="path3104"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M -6.8450862,980.51665 0.14941782,991.3582"
       id="path3106"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M -7.5445362,991.3582 -0.55003318,980.86635"
       id="path3108"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 30.892546,1140.0683 37.88705,1150.9098"
       id="path3110"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 30.193096,1150.9098 37.187599,1140.418"
       id="path3112"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 30.892546,1160.0683 37.88705,1170.9098"
       id="path3114"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 30.193096,1170.9098 37.187599,1160.418"
       id="path3116"
       style="fill:none;stroke:#800000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 142.38603,73.372083 145.89092,77.315081 151.58636,64.609866"
       transform="translate(-133.70045,954.73458)"
       id="path4146"
       style="fill:none;stroke:#008000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 28.68558,1106.1067 32.19047,1110.0497 37.88591,1097.3444"
       id="myCheck1"
       style="fill:none;stroke:#008000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 28.68558,1126.1067 32.19047,1130.0497 37.88591,1117.3444"
       id="path4150"
       style="fill:none;stroke:#008000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 46.68558,1206.1067 50.19047,1210.0497 55.88591,1197.3444"
       id="path4152"
       style="fill:none;stroke:#008000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 46.68558,1226.1067 50.19047,1230.0497 55.88591,1217.3444"
       id="path4154"
       style="fill:none;stroke:#008000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
</div>
<!--
			<object data="svgs/opt_copyprop_big.svg" type="image/svg+xml" id="opt_copyprop_big" align=center></object>
-->
</div>
    <script>
        var svga = document.getElementById("opt_copyprop_big");
				var delta = svga.getElementById("myCheck1"); //get the inner element by id
				//delta.addEventListener("mousedown",function(){
				delta.addEventListener("mouseover",function(){
					var objs = [
						svga.getElementById("ind1circle"),
						svga.getElementById("ind2circle"),
						svga.getElementById("ind3circle"),
						svga.getElementById("ind4circle"),
						svga.getElementById("ind5circle"),

						svga.getElementById("ind1arrowline"),
						svga.getElementById("ind2arrowline"),
						svga.getElementById("ind3arrowline"),
						svga.getElementById("ind4arrowline"),
						svga.getElementById("ind5arrowline"),

						svga.getElementById("ind1arrowhead"),
						svga.getElementById("ind2arrowhead"),
						svga.getElementById("ind3arrowhead"),
						svga.getElementById("ind4arrowhead"),
						svga.getElementById("ind5arrowhead"),

						svga.getElementById("line1"),
						svga.getElementById("line2"),
						svga.getElementById("line3"),
						svga.getElementById("line4"),
						svga.getElementById("line5"),

						svga.getElementById("text1"),
						svga.getElementById("text2"),
						svga.getElementById("text3"),
						svga.getElementById("text4"),
						svga.getElementById("text5")
						];
					for (idx = 0 ; idx < objs.length; ++idx){
						obj = objs[idx];
						obj.setAttribute('opacity', 0.0);
					}


				},false);

				delta.addEventListener("mouseout",function(){
					var objs = [
						svga.getElementById("ind1circle"),
						svga.getElementById("ind2circle"),
						svga.getElementById("ind3circle"),
						svga.getElementById("ind4circle"),
						svga.getElementById("ind5circle"),

						svga.getElementById("ind1arrowline"),
						svga.getElementById("ind2arrowline"),
						svga.getElementById("ind3arrowline"),
						svga.getElementById("ind4arrowline"),
						svga.getElementById("ind5arrowline"),

						svga.getElementById("ind1arrowhead"),
						svga.getElementById("ind2arrowhead"),
						svga.getElementById("ind3arrowhead"),
						svga.getElementById("ind4arrowhead"),
						svga.getElementById("ind5arrowhead"),

						svga.getElementById("line1"),
						svga.getElementById("line2"),
						svga.getElementById("line3"),
						svga.getElementById("line4"),
						svga.getElementById("line5"),

						svga.getElementById("text1"),
						svga.getElementById("text2"),
						svga.getElementById("text3"),
						svga.getElementById("text4"),
						svga.getElementById("text5")
						];
					for (idx = 0 ; idx < objs.length; ++idx){
						obj = objs[idx];
						obj.setAttribute('opacity', 1.0);
					}


				},false);



				/*
        svga.addEventListener("load",function(){
						alert("svg loaded");
            var svgDoc = svga.contentDocument; //get the inner DOM of alpha.svg
            var delta = svgDoc.getElementById("myCheck1"); //get the inner element by id
						alert("delta is an svg object: " + delta);
            delta.addEventListener("mousedown",function(){alert('hello world!')},false);    //add behaviour
        },false);
				 */
    </script>

Here's the code after propagating the copies that are legal, and removing
the copy statements that become dead.  Note that we are able to remove 5
copy statements, including 2 from the innermost loop.

<div align=center style="margin-top:20px; margin-bottom:20px">
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="358.58163"
   height="351.05469"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="opt_copyprop_big2.svg">
  <defs
     id="defs4">
    <inkscape:perspective
       sodipodi:type="inkscape:persp3d"
       inkscape:vp_x="0 : 526.18109 : 1"
       inkscape:vp_y="0 : 1000 : 0"
       inkscape:vp_z="744.09448 : 526.18109 : 1"
       inkscape:persp3d-origin="372.04724 : 350.78739 : 1"
       id="perspective2993" />
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.0218927"
     inkscape:cx="145.27824"
     inkscape:cy="235.36726"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(25.464811,-960.8371)">
    <flowRoot
       xml:space="preserve"
       id="flowRoot2985"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><flowRegion
         id="flowRegion2987"><rect
           id="rect2989"
           width="295.64752"
           height="215.3271"
           x="164.05875"
           y="266.24738"
           style="font-size:12px" /></flowRegion><flowPara
         id="flowPara2991" /></flowRoot>    <text
       sodipodi:linespacing="125%"
       id="text3062"
       y="970.11835"
       x="0.051066399"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="970.11835"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan4722">tmp0 = FP + offsetA</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="990.11835"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3022">ind1 = tmp0</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1010.1183"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan4724">for (i=0; i&lt;100; i++){</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1030.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3026">  ind2 = ind1</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1050.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3024">  ind3 = 0</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1070.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3015">  for (j=0; j&lt;100; j++){</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1090.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3032">    ind4 = 0</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1110.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3034">    ind5 = ind2</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1130.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3023">    for (k=0; k&lt;100; k++){</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1150.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3025">      store ind4, 0(ind5)</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1170.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3036">      ind4 = ind4 + ind3</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1190.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3038">      ind5 = ind5 - 4</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1210.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3021">    }</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1230.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3040">    ind2 = ind2 - 400</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1250.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3042">    ind3 = ind3 + i</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1270.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3013">  }</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1290.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan3044">  ind1 = ind1 - 40000</tspan><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1310.1184"
         x="0.051066399"
         sodipodi:role="line"
         id="tspan4726">}</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 121.34884,1096.3851 C 129.70826,1073.1039 145.55365,1092.6529 160.23526,1090.8814"
       id="path3984"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 232.78754,1137.395 C 249.26914,1127.5397 249.87884,1150.4698 257.29486,1152.6861"
       id="path4012"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path3063"
       d="M 24.963762,1142.4233 C 34.962081,1133.8985 109.9929,1131.0318 115.3758,1141.5071"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 130.06263,1023.0837 C 143.47082,1025.0373 132.24588,1041.6392 145.62051,1035.517"
       id="path3087"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       sodipodi:linespacing="125%"
       id="text3099"
       y="1014.6464"
       x="146.06509"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1014.6464"
         x="146.06509"
         sodipodi:role="line"
         id="tspan3101" /><tspan
         id="tspan3105"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="1029.2389"
         x="146.06509"
         sodipodi:role="line">propagated </tspan><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="1047.765"
         x="146.06509"
         sodipodi:role="line"
         id="tspan3995">through <tspan
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
   id="tspan3943">tmp1</tspan></tspan></text>
    <rect
       y="1137.0172"
       x="203.42284"
       height="16.815981"
       width="39.991165"
       id="rect3937"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect3948"
       width="39.991165"
       height="16.815981"
       x="119.42284"
       y="1137.0172" />
    <rect
       y="1097.5334"
       x="111.40118"
       height="16.815981"
       width="39.991165"
       id="rect3950"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect3952"
       width="39.991165"
       height="16.815981"
       x="90.628563"
       y="1017.9051" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="166.95026"
       y="1074.5914"
       id="text3968"
       sodipodi:linespacing="125%"><tspan
         id="tspan3970"
         sodipodi:role="line"
         x="166.95026"
         y="1074.5914"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         sodipodi:role="line"
         x="166.95026"
         y="1089.184"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         id="tspan3972">propagated </tspan><tspan
         sodipodi:role="line"
         x="166.95026"
         y="1107.7101"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         id="tspan3998">through <tspan
   id="tspan3974"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">tmp2</tspan></tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3976"
       y="1138.5371"
       x="259.2887"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1138.5371"
         x="259.2887"
         sodipodi:role="line"
         id="tspan3978" /><tspan
         id="tspan3980"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="1153.1296"
         x="259.2887"
         sodipodi:role="line">propagated </tspan><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="1171.6558"
         x="259.2887"
         sodipodi:role="line"
         id="tspan4001">through <tspan
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
   id="tspan3982">T1</tspan></tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="-26.028811"
       y="1143.2103"
       id="text3984"
       sodipodi:linespacing="125%"><tspan
         id="tspan3986"
         sodipodi:role="line"
         x="-26.028811"
         y="1143.2103"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono" /><tspan
         sodipodi:role="line"
         x="-26.028811"
         y="1157.8029"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         id="tspan3988">propagated </tspan><tspan
         sodipodi:role="line"
         x="-26.028811"
         y="1176.329"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         id="tspan3992">through <tspan
   id="tspan3990"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">T0</tspan></tspan></text>
    <rect
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4004"
       width="39.991165"
       height="16.815981"
       x="201.42284"
       y="1157.0172" />
    <text
       sodipodi:linespacing="125%"
       id="text4006"
       y="1191.3137"
       x="218.38721"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#000000;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="1191.3137"
         x="218.38721"
         sodipodi:role="line"
         id="tspan4008" /><tspan
         id="tspan4010"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="1205.9062"
         x="218.38721"
         sodipodi:role="line">propagated </tspan><tspan
         id="tspan4012"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="1224.4324"
         x="218.38721"
         sodipodi:role="line">through <tspan
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
   id="tspan4014">tmp3</tspan></tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path4016"
       d="M 216.71774,1174.0168 C 212.83616,1184.9857 212.88862,1199.4558 216.09294,1202.9902"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
  </g>
</svg>
</div>
<!--
<div align=center style="margin-top:20px; margin-bottom:20px">
			<object data="svgs/opt_copyprop_big2.svg" type="image/svg+xml" id="opt_copyprop_big2" align=center></object>
</div>
-->
Comparing this code with the original code, we see that, in the inner loop
(which is executed 1,000,000 times) we originally had 5 multiplications,
3 additions/subtractions, and 1 indexed store.
We now have no multiplications and just 2 additions/subtractions.
We have added 2 additions/subtractions and 2 copy statements to the
middle loop (which executes 10,000 times) and 1 addition/subtraction
and 1 copy statement to the outer loop (which executes 100 times), but
overall this should be a win!
</HTML>
