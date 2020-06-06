<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
<A NAME=top></A>

<HR>
<H2>Contents</H2>
<UL>
  <LI> <a href="#lrOverview">LR Parsing Overview</a>
  <LI> <a href="#basicAlg">Basic LR Parsing Algorithm</a>
  <LI> <a href="#ex">Example</a>
  <LI> <a href="#tables">Parse Tables</a>
  <LI> <a href="#slrConflicts">Conflicts</a>
</UL>
<HR>

<p>
<a name="lrOverview"></a>
<span class=subhead>LR Parsing Overview</span>

There are several different kinds of bottom-up parsing.
We will discuss an approach called <em>LR</em> parsing, which
includes SLR, LALR, and LR parsers.
LR means that the input is scanned <b>left-to-right</b>, and
that a <b>rightmost</b> derivation, in reverse, is constructed.
SLR means "simple" LR, and LALR means "look-ahead" LR.
<P>
Every SLR(1) grammar is also LALR(1), and every LALR(1) grammar
is also LR(1), so SLR is the most limited of the three, and LR is
the most general.
In practice, it is pretty easy to write an LALR(1) grammar for most
programming languages (i.e., the "power" of an LR parser isn't usually
needed).
A disadvantage of LR parsers is that their tables can be very large.
Therefore, parser generators like Yacc and Java Cup produce LALR(1)
parsers.
<P>
Let's start by considering the advantages and disadvantages of the
LR parsing family:

<p>
<b>Advantages</b>
<UL>
  <LI> Almost all programming languages have LR grammars.
  <LI> LR parsers take time and space linear in the size of the input
       (with a constant factor determined by the grammar).
  <LI> LR is strictly more powerful than LL (for example, every LL(1)
	grammar is also both LALR(1) and LR(1), but not vice versa).
  <LI> LR grammars are more "natural" than LL grammars (e.g., the
       grammars for expression languages get mangled when we remove
	the left recursion to make them LL(1), but that isn't
	necessary for an LALR(1) or an LR(1) grammar).
</UL>

<b>Disadvantages</b>
<UL>
  <LI> Although an LR grammar is usually easier to understand than the
       corresponding LL grammar, the parser itself is harder to understand
       and to write (thus, LR parsers are built using parser generators,
       rather than being written by hand).
  <LI> When we use a parser generator, if the grammar that we provide
       is not LALR(1), it can be difficult to figure out how to fix that.
  <LI> Error repair may be more difficult using LR parsing than using LL.
  <LI> Table sizes may be larger (about a factor of 2) than those used for
       LL parsing.
</UL>
<P>
Recall that top-down parsers use a stack.  The contents of the stack
represent a prediction of what the rest of the input should look like.
The symbols on the stack, from top to bottom, should "match" the remaining
input, from first to last token.
For example, <a href=reading.php?r=parsingtopdown#balancedbrackets>earlier</a>, we looked at the example grammar
</p>
<div class=row>
	<div class=12u>
	<center>
	<table style="width:auto; display:inline; margin-top:50px; margin-right:50px">

<tr>
<td align="left">Grammar:</td>
<td align="right"></td>
<td align="center"></td>
<td align="left"></td>
</tr>


<tr>
<td></td>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$\varepsilon$  |  <b>(</b> $S$ <b>)</b>  |  <b>[</b> $S$ <b>]</b></td>
</tr>
</table>
	</center>
	</div>
</div>
<p>
and parsed the input string <tt>([])</tt>.  At one point during the
parse, after the first parenthesis has been consumed, the stack contains
<pre>    [ S ] ) EOF</pre>
(with the top-of-stack at the left).
This is a prediction that the remaining input will start with a
<tt>'['</tt>, followed by zero or more tokens matching an <tt>S</tt>,
followed by the tokens <tt>']'</tt> and <tt>')'</tt>, in that order,
followed by end-of-file.
<P>
Bottom-up parsers also use a stack, but in this case, the stack
represents a summary of the input already seen, rather than a
prediction about input yet to be seen.
For now, we will pretend that the stack symbols are terminals and nonterminals
(as they are for predictive parsers).
This isn't quite true, but it makes our introduction to
bottom-up parsing easier to understand.
<P>
A bottom-up parser is also called a "shift-reduce" parser because it
performs two kind of operations, <em>shift</em> operations and
<em>reduce</em> operations.
A shift operation simply shifts the next input token from the input to
the top of the stack.  A reduce operation is only possible when the
top N symbols on the stack match the right-hand side of a production in the
grammar.  A reduce operation pops those symbols off the stack
and pushes the non-terminal on the left-hand side of the production.

<p>
One way to think about LR parsing is that
the parse tree for a given input is built, starting at the leaves
and working up towards the root.
More precisely, a <em>reverse rightmost derivation</em> is constructed.

<P>
Recall that a derivation (using a given grammar) is performed as follows:
<OL class=style1>
  <LI> start with the start symbol (i.e., the current string is the start
       symbol)
  <LI> repeat:
  <UL>
    <LI> choose a nonterminal X in the current string
    <LI> choose a grammar rule X &rarr; alpha
    <LI> replace X in the current string with alpha
  </UL>
  until there are no more nonterminals in the current string
</OL>

<p>
A <em>rightmost</em> derivation is one in which the rightmost nonterminal is
always the one chosen.
</p>

<span class=example>Rightmost Derivation</span>

<div class=row style="margin-top:30px">
	<div class="-2u 4u" text-align=center style="text-align:center">
	<center>
	<p>CFG</p>
	<table class="cfg" align=center width=auto style="width:auto">
<!--
<tr>
<td align="left" style="border-bottom: 1px solid"colspan=3><b>CFG</b></td>
</tr>
-->

<tr>
<td class=cfg_lhs align="left">$E$</td>
<td class="cfg_trans" align="center">$\longrightarrow$</td>
<td class=cfg_rhs align="left">$E$ <span class=term>+</span> $T$</td>
</tr>

<tr>
<td class=cfg_lhs align="left"></td>
<td class="cfg_trans" align="center">|</td>
<td class=cfg_rhs align="left">$T$</td>
</tr>


<tr>
<td class=cfg_lhs align="left">$T$</td>
<td class="cfg_trans" align="center">$\longrightarrow$</td>
<td class=cfg_rhs align="left">$T$ <span class=term>*</span> $F$</td>
</tr>

<tr>
<td class=cfg_lhs align="left"></td>
<td class="cfg_trans" align="center">|</td>
<td class=cfg_rhs align="left">$F$</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$F$</td>
<td class="cfg_trans" align="center">$\longrightarrow$</td>
<td class=cfg_rhs align="left"><span class=term>id</span></td>
</tr>

<tr>
<td class=cfg_lhs align="left"></td>
<td class="cfg_trans" align="center">|</td>
<td class=cfg_rhs align="left">
	<span class=term>(</span>
	<span>$E$</span>
	<span class=term>)</span>
</td>
</tr>
</table>
	</center>
	</div>

	<div class=6u>
	<p>Rightmost derivation</p>
	<!--
<table class="cfg" align=center width=auto style="margin-bottom:0px; margin-top:10px">
<tr> 
	<td align="left" style="border-bottom: 1px solid"colspan=3>
		<b>A Rightmost Derivation</b>
	</td>
</tr>
</table>
<br />
-->
<UL>
<span style="color:red">$E$</span>
<ol class=style1>
<li>
$\Longrightarrow$ $E$ <b>+</b> <span style="color:red">$T$</span>
</li>
<li>
$\Longrightarrow$ $E$ <b>+</b> $T$ <b>*</b> <span style="color:red">$F$</span>
</li>

<li>
$\Longrightarrow$ $E$ <b>+</b> <span style="color:red">$T$</span> <b>*</b> <b>id</b>
</li>

<li>
$\Longrightarrow$ $E$ <b>+</b> <span style="color:red">$F$</span> <b>*</b> <b>id</b>
</li>

<li>
$\Longrightarrow$ <span style="color:red">$E$</span> <b>+</b> <b>id</b> <b>*</b> <b>id</b>
</li>

<li>
$\Longrightarrow$ <span style="color:red">$T$</span> <b>+</b> <b>id</b> <b>*</b> <b>id</b>
</li>

<li>
$\Longrightarrow$ <span style="color:red">$F$</span> <b>+</b> <b>id</b> <b>*</b> <b>id</b>
</li>

<li>
$\Longrightarrow$ <span class="term">id</span> <span class="term">+</span> <span class=term>id</span> <b>*</b> <b>id</b>
</li>
</ol>
</UL>
	</div>


</div>


In this example, the nonterminal that is chosen at each step is in red,
and each derivation step is numbered.
The corresponding bottom-up parse is shown below by showing the parse tree
with its edges numbered to show the order in which the tree was built
(e.g., the first step was to add the nonterminal F as the parent of
the leftmost parse-tree leaf "id", and the last step was to combine
the three subtrees representing "id", "+", and "id * id" as the
children of a new root node E).
<div class="row">
<div class="12u">
<center>
	<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.1"
   width="250.562"
   height="325.31464"
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
     transform="translate(-95.314,-140.04198)"
     id="layer1">
    <text
       x="196.59515"
       y="152.11998"
       id="text2985"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="196.59515"
         y="152.11998"
         id="tspan2987">E</tspan></text>
    <path
       d="M 133.65002,392.43139 133.65002,445.58018"
       id="path2995"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="127.95551"
       y="465.19461"
       id="text2997"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="127.95551"
         y="465.19461"
         id="tspan2999"
         style="font-weight:bold">id</tspan></text>
    <text
       x="133.714"
       y="385.19461"
       id="text3001"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="133.714"
         y="385.19461"
         id="tspan3003"
         style="font-weight:normal;text-align:center;text-anchor:middle">F</tspan></text>
    <path
       d="M 133.65002,313.97365 133.65002,367.12244"
       id="path3007"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="133.65593"
       y="305.24603"
       id="text3009"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="133.65593"
         y="305.24603"
         id="tspan3011"
         style="font-weight:normal;text-align:center;text-anchor:middle">T</tspan></text>
    <text
       x="133.60657"
       y="227.87184"
       id="text3013"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="133.60657"
         y="227.87184"
         id="tspan3015"
         style="font-weight:normal;text-align:center;text-anchor:middle">E</tspan></text>
    <text
       x="224.63129"
       y="461.19461"
       id="text3025"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="224.63129"
         y="461.19461"
         id="tspan3027"
         style="font-weight:bold">id</tspan></text>
    <text
       x="307.18341"
       y="381.19461"
       id="text3033"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="307.18341"
         y="381.19461"
         id="tspan3035"
         style="font-weight:bold">id</tspan></text>
    <path
       d="M 133.65002,233.49417 133.65002,286.64296"
       id="path3037"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 232.19476,388.43139 232.19476,441.58018"
       id="path3051"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="232.25873"
       y="381.19461"
       id="text3053"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="232.25873"
         y="381.19461"
         id="tspan3055"
         style="font-weight:normal;text-align:center;text-anchor:middle">F</tspan></text>
    <path
       d="M 232.19476,309.97365 232.19476,363.12244"
       id="path3057"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="232.20065"
       y="301.24603"
       id="text3059"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="232.20065"
         y="301.24603"
         id="tspan3061"
         style="font-weight:normal;text-align:center;text-anchor:middle">T</tspan></text>
    <path
       d="M 314.14335,308.43139 314.14335,361.58018"
       id="path3063"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="314.20731"
       y="301.19461"
       id="text3065"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="314.20731"
         y="301.19461"
         id="tspan3067"
         style="font-weight:normal;text-align:center;text-anchor:middle">F</tspan></text>
    <text
       x="270.79678"
       y="228.0755"
       id="text3069"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="270.79678"
         y="228.0755"
         id="tspan3071"
         style="font-weight:normal;text-align:center;text-anchor:middle">T</tspan></text>
    <g
       transform="translate(-41.759763,-234.10776)"
       id="g3077">
      <text
         x="235.46889"
         y="465.19461"
         id="text3017"
         xml:space="preserve"
         style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
           x="235.46889"
           y="465.19461"
           id="tspan3019"
           style="font-weight:bold">+</tspan></text>
      <path
         d="M 242.96589,398.43139 242.96589,445.58018"
         id="path3039"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
      <path
         d="M 228.96589,398.43139 184.96589,441.58018"
         id="path3073"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
      <path
         d="M 258.96589,398.43139 302.96589,441.58018"
         id="path3075"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    </g>
    <g
       transform="translate(28.668942,-160.10776)"
       id="g3084">
      <text
         x="243.00589"
         y="465.19461"
         id="text3086"
         xml:space="preserve"
         style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
           x="243.00589"
           y="465.19461"
           id="tspan3088"
           style="font-weight:bold;text-align:center;text-anchor:middle">*</tspan></text>
      <path
         d="M 242.96589,398.43139 242.96589,439.58018"
         id="path3090"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
      <path
         d="M 228.96589,398.43139 210.96589,439.58018"
         id="path3092"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
      <path
         d="M 258.96589,398.43139 276.96589,439.58018"
         id="path3094"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    </g>
    <text
       x="94"
       y="346.86218"
       id="text3100"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="94"
         y="346.86218"
         id="tspan3102"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">2</tspan></text>
    <text
       x="97.25"
       y="426.61218"
       id="text3128"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="97.25"
         y="426.61218"
         id="tspan3130"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">1</tspan></text>
    <text
       x="95"
       y="273.36218"
       id="text3132"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="95"
         y="273.36218"
         id="tspan3134"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">3</tspan></text>
    <text
       x="211"
       y="418.36218"
       id="text3136"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="211"
         y="418.36218"
         id="tspan3138"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">4</tspan></text>
    <text
       x="211"
       y="333.36218"
       id="text3140"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="211"
         y="333.36218"
         id="tspan3142"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">5</tspan></text>
    <text
       x="339"
       y="342.36218"
       id="text3144"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="339"
         y="342.36218"
         id="tspan3146"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">6</tspan></text>
    <text
       x="331"
       y="233.36218"
       id="text3148"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="331"
         y="233.36218"
         id="tspan3150"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">7</tspan></text>
    <text
       x="284.62741"
       y="157.80583"
       id="text3152"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="284.62741"
         y="157.80583"
         id="tspan3154"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">8</tspan></text>
    <path
       d="M 105,422.36218 C 116.56915,429.98771 117.69555,428.7589 127,416.36218"
       id="path3156"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 124.125,416.48718 127.375,415.73718 127.75,419.61218"
       id="path3158"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 106.75,265.24193 C 118.31915,257.6164 119.44555,258.84521 128.75,271.24193"
       id="path3160"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 125.875,271.11693 129.125,271.86693 129.5,267.99193"
       id="path3162"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 128.25903,324.32043 130.691,326.60309 127.68037,329.07136"
       id="path3166"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 106.41957,337.27911 C 113.14299,331.04329 121.30272,328.94252 129.93087,326.49573"
       id="path3168"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 219,334.11218 C 221.17753,338.73035 224.32854,342.3407 228,345.86218"
       id="path3170"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 224.1875,346.11218 228.3125,345.86218 227.5625,341.36218"
       id="path3172"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 223,411.61218 229,411.61218"
       id="path3174"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 226.8045,409.69051 229.27937,411.9886 227.42322,413.66798"
       id="path3178"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 335.16861,343.84119 C 329.5201,351.90176 328.73212,352.74061 320.31937,344.54829"
       id="path3180"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 320.67293,348.08383 319.43549,344.19474 322.44069,343.13408"
       id="path3182"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 306.5,237.11218 C 291.8711,224.04398 249.96523,232.09244 234,239.86218 218.03477,247.63192 220.61516,253.14382 220.5,265.86218 220.38484,278.58054 255.07602,286.38321 273.25,284.86218 291.42398,283.34115 301.40706,292.29428 315.5,275.86218 329.59294,259.43008 321.1289,250.18038 306.5,237.11218 z"
       id="path3184"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 335.87572,238.83583 C 335.87572,238.83583 334.27363,245.51998 331.98663,248.73533 329.69963,251.95068 322.08714,257.22061 322.08714,257.22061"
       id="path3186"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 146.3711,19.844507 C 126.99095,13.321298 79.338327,16.130647 60.811183,21.25872 42.284039,26.386792 26.346255,28.879141 22.627417,42.471924 18.908579,56.064707 28.955105,60.618469 44.547727,70.049088 60.14035,79.479707 81.378021,80.706266 104.65181,77.120156 127.9256,73.534047 173.3875,72.734163 176.7767,52.371419 180.1659,32.008675 165.75125,26.367715 146.3711,19.844507 z"
       transform="translate(95.314,140.04198)"
       id="path3958"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 196.57569,26.208468 C 196.57569,26.208468 198.48277,35.161314 195.16147,39.643497 191.84017,44.12568 176.7767,47.421671 176.7767,47.421671"
       transform="translate(95.314,140.04198)"
       id="path3960"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
</center>
</div>
</div>
<p>
Note that both the rightmost derivation and the bottom-up parse have 8 steps.
Step 1 of the derivation corresponds to step 8 of the parse;
step 2 of the derivation corresponds to step 7 of the parse; etc.
Each step of building the parse tree (adding a new nonterminal as the parent
of some existing subtrees) is called a <em>reduction</em> (that's where
the "reduce" part of "shift-reduce" parsing comes from).
</p>

<p>
<a name="basicAlg"></a>
<h3>Basic LR Parsing Algorithm</h3>
All LR parsers use the same basic algorithm:

<UL>
Based on:
<UL>
  <LI> the top-of-stack symbol and
  <LI> the current input symbol (token) and
  <LI> the entry in one of the parse tables (indexed by the top-of-stack and
	current-input symbols)
</UL>
the parser performs one of the following actions:
<OL class=style1>
  <LI> <b>shift</b>: push the current input symbol onto the stack, and go on
        to the next input symbol (i.e., call the scanner again)
  <LI> <b>reduce</b>: a grammar rule's right-hand side is on the top of the
        stack! pop it off and push the grammar rule's left-hand-side
	nonterminal
  <LI> <b>accept</b>: accept the input (parsing has finished successfully)
  <LI> <b>reject</b>: the input is not syntactically correct
</OL>
</UL>
<P>
The difference between SLR, LALR, and LR parsers is in the tables that
they use.
Those tables use different techniques to determine when to do a
<b>reduce</b> step, and, if there is more than one grammar rule with
the <em>same</em> right-hand side, which left-hand-side nonterminal
to push.

<p>
<a name="ex"></a>
<h2>Example</h2>
Here are the steps the parser would perform using the grammar of
arithmetic expressions with + and * given above, if the input
is <pre>id + id * id</pre>
<UL>


<table class="cfg">

<tr>
<td class=cfg_lhs align="left" style="border-bottom:1px solid"><b>Stack</b></td>
<td style="padding-left:40px"></td>
<td class="cfg_trans" align="left" style="border-bottom:1px solid"><b>Input</b></td>
<td style="padding-left:40px"></td>
<td class=cfg_rhs align="left" style="border-bottom:1px solid"><b>Action</b></td>
</tr>

<tr>
<td class=cfg_lhs align="left"></td>
<td></td>
<td class="cfg_trans" align="right">id + id * id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">shift (<span class=term>id</span>)</td>
</tr>

<tr>
<td class=cfg_lhs align="left">id</td>
<td></td>
<td class="cfg_trans" align="right">+ id * id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $F$ $\longrightarrow$ <span class=term>id</span></td>
</tr>

<tr>
<td class=cfg_lhs align="left">$F$</td>
<td></td>
<td class="cfg_trans" align="right">+ id * id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $T$ $\longrightarrow$ $F$</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$T$</td>
<td></td>
<td class="cfg_trans" align="right">+ id * id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $E$ $\longrightarrow$ $T$</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$</td>
<td></td>
<td class="cfg_trans" align="right">+ id * id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">shift(<span class=term>+</span>)</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ +</td>
<td></td>
<td class="cfg_trans" align="right">id * id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">shift(<span class=term>id</span>)</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ + id</td>
<td></td>
<td class="cfg_trans" align="right">* id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $F$ $\longrightarrow$ <span class=term>id</span></td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ + $F$</td>
<td></td>
<td class="cfg_trans" align="right">* id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $T$ $\longrightarrow$ $F$</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ + $T$</td>
<td></td>
<td class="cfg_trans" align="right">* id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">shift(<span class=term>*</span>)</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ <span class=term>+</span> $T$ *</td>
<td></td>
<td class="cfg_trans" align="right">id <span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">shift(<span class=term>id</span>)</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ + $T$ * id</td>
<td></td>
<td class="cfg_trans" align="right"><span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $F$ $\longrightarrow$ <span class=term>id</span></td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ + $T$ * $F$</td>
<td></td>
<td class="cfg_trans" align="right"><span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $T$ $\longrightarrow$ $T$ <span class=term>*</span> $F$</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$ + $T$</td>
<td></td>
<td class="cfg_trans" align="right"><span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">reduce by $E$ $\longrightarrow$ $E$ <span class=term>+</span> $T$</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$E$</td>
<td></td>
<td class="cfg_trans" align="right"><span class=EOF></span></td>
<td></td>
<td class=cfg_rhs align="left">accept</td>
</tr>

</table>

<p>
(NOTE: the top of stack is to the right; the reverse rightmost derivation is
       formed by concatenating the stack with the remaining input at each
       reduction step)
</p>
</UL>
Note that in step 8 the top of the stack contained
<tt>E + T</tt>, which is the right-hand side of the first grammar rule.
However, the parser does not do a reduce step at that point;
instead it does a shift, which is the right thing to do since (as you
can see if you look back at the parse tree given above), that particular
"E + T" is <em>not</em> grouped together (because the grammar reflects
the fact that multiplication has higher precedence than addition).
<P>
<a name="tables"></a>
<span class=subhead>Parse Tables</span>
<p>
As mentioned above, the symbols pushed onto the parser's
stack are not actually terminals and nonterminals.
Instead, they are <em>states</em>, that correspond to a
finite-state machine that represents the parsing process (more on
this soon).
</p>
<P>
All LR Parsers use two tables: the <em>action</em> table and the
<em>goto</em> table.
The action table is indexed by the top-of-stack symbol and
the current token, and it tells which of the four actions to
perform: shift, reduce, accept, or reject.
The goto table is used during a reduce action as explained below.
<P>
Above we said that a <em>shift</em> action means to push the
current token onto the stack.
In fact, we actually push a state symbol onto the stack.
Each "shift" action in the action table includes the state to
be pushed.
<P>
Above, we also said that when we reduce using the grammar rule A &rarr;
alpha, we pop alpha off of the stack and then push A.
In fact, if alpha contains N symbols, we pop N <em>states</em> off of
the stack.
We then use the goto table to know what to push:
the goto table is indexed by state symbol t and nonterminal A, where t
is the state symbol that is on top of the stack after popping N times.
<P>
Here's pseudo code for the parser's main loop:
<UL>
<pre>
push initial state s<sub>0</sub>
a = scan()
do forever
    t = top-of-stack (state) symbol
    switch action[t, a] {
       case shift s:
           push(s)
           a = scan()
       case reduce by A &rarr; alpha:
           for i = 1 to length(alpha) do pop() end
	   t = top-of-stack symbol
           push(goto[t, A])
       case accept:
           return( SUCCESS )
       case error:
           call the error handler
           return( FAILURE )
    }
end do
</pre>
</UL>
<P>
Remember, all LR parsers use this same basic algorithm.
As mentioned above, for all
LR parsers, the states that are pushed onto the stack represent the states in
an underlying <em>finite-state machine</em>.  Each state represents "where
we might be" in a parse; each "place we might be" is represented (within the
state) by an <b>item</b>.  What's different for the different LR parsers is:
<UL>
  <LI> the definition of an item
  <LI> the number of states in the underlying finite-state machine
  <LI> the amount of information in a state
</UL>

<p>
<a name="slr"></a>
<span class=subhead>SLR Parsing</span>

<p>
SLR means simple LR; it is the weakest member of the LR family (i.e., every
SLR grammar is also LALR and LR, but not vice versa).
To understand SLR parsing we'll use a new example grammar (a very simple
grammar for parameter lists):

<table class="cfg">
<tr>
<td class=cfg_lhs align="left">$PList$</td>
<td class="cfg_trans" align="center">$\longrightarrow$</td>
<td class=cfg_rhs align="left">
	<span class=term>(</span>
	$IDList$ 
	<span class=term>)</span>
</td>
</tr>

<tr>
<td class=cfg_lhs align="left">$IDList$</td>
<td class="cfg_trans" align="center">$\longrightarrow$</td>
<td class=cfg_rhs align="left">
	<span class=term>id</span>
</td>
</tr>

<tr>
<td class=cfg_lhs align="left"></td>
<td class="cfg_trans" align="center">|</td>
<td class=cfg_rhs align="left">
	$IDList$
	<span class=term>id</span>
</td>
</tr>
</table>

<p>
<a name="slrTables"></a>
<span class=subsubhead>Building the Action and Goto Tables for an SLR Parser</span>

<p>
<b>Definition of an SLR item</b>:
<UL>
An item (for a given grammar) is a production with a dot somewhere on the 
right-hand side.
</UL>

<u>Example</u>

<div class=row>
	<div class=12u>
	<center>
	<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.1"
   width="247"
   height="115"
   id="svg4038">
  <defs
     id="defs4040" />
  <metadata
     id="metadata4043">
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
     transform="translate(-214.7426,-205.67292)"
     id="layer1">
    <text
       x="215.8606"
       y="218.96327"
       id="text4046"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="215.8606"
         y="218.96327"
         id="tspan4048"
         style="font-size:14px"><tspan
   id="tspan4058"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan4052"
   style="font-weight:bold">(</tspan><tspan
   id="tspan4054"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"> IDList</tspan><tspan
   id="tspan4050"
   style="font-weight:bold"> )</tspan></tspan></text>
    <text
       x="215.8606"
       y="256.43994"
       id="text4062"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="215.8606"
         y="256.43994"
         id="tspan4064"
         style="font-size:14px"><tspan
   id="tspan4066"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan4068"
   style="font-weight:bold">(</tspan><tspan
   id="tspan4070"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"> IDList</tspan><tspan
   id="tspan4072"
   style="font-weight:bold"> )</tspan></tspan></text>
    <text
       x="264.5"
       y="257.2002"
       id="text4074"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="264.5"
         y="257.2002"
         id="tspan4076">.</tspan></text>
    <text
       x="215.8606"
       y="273.18994"
       id="text4094"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="215.8606"
         y="273.18994"
         id="tspan4096"
         style="font-size:14px"><tspan
   id="tspan4098"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan4100"
   style="font-weight:bold">(</tspan><tspan
   id="tspan4102"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"> IDList</tspan><tspan
   id="tspan4104"
   style="font-weight:bold"> )</tspan></tspan></text>
    <text
       x="274.5"
       y="273.9502"
       id="text4106"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="274.5"
         y="273.9502"
         id="tspan4108">.</tspan></text>
    <text
       x="215.8606"
       y="292.18994"
       id="text4110"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="215.8606"
         y="292.18994"
         id="tspan4112"
         style="font-size:14px"><tspan
   id="tspan4114"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan4116"
   style="font-weight:bold">(</tspan><tspan
   id="tspan4118"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"> IDList</tspan><tspan
   id="tspan4120"
   style="font-weight:bold"> )</tspan></tspan></text>
    <text
       x="316.89075"
       y="292.9502"
       id="text4122"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="316.89075"
         y="292.9502"
         id="tspan4124">.</tspan></text>
    <text
       x="215.8606"
       y="312.45645"
       id="text4126"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="215.8606"
         y="312.45645"
         id="tspan4128"
         style="font-size:14px"><tspan
   id="tspan4130"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan4132"
   style="font-weight:bold">(</tspan><tspan
   id="tspan4134"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"> IDList</tspan><tspan
   id="tspan4136"
   style="font-weight:bold"> )</tspan></tspan></text>
    <text
       x="326.89075"
       y="313.21671"
       id="text4138"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="326.89075"
         y="313.21671"
         id="tspan4140">.</tspan></text>
    <path
       d="M 358.35372,215.36714 C 347.79131,219.71159 342.17972,209.52518 329.21488,213.93408"
       id="path4142"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 330.14839,211.16026 328.45951,213.69357 330.31728,216.05799"
       id="path4144"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="359.80829"
       y="217.52267"
       id="text4146"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="359.80829"
         y="217.52267"
         id="tspan4148"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">The production</tspan></text>
    <text
       x="386.90549"
       y="284.52267"
       id="text4658"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="390.78439"
         y="284.52267"
         id="tspan4670"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">The possible </tspan><tspan
         x="386.90549"
         y="299.52267"
         id="tspan4674"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">items</tspan></text>
    <path
       d="M 330.5,316.74064 C 330.5,316.74064 335.52404,319.94473 338.625,316.74064 341.72596,313.53655 335.98072,286.21487 338.625,283.48265 341.26928,280.75043 342.875,281.41614 342.875,281.41614"
       id="path4664"
       style="opacity:0.98999999;fill:none;stroke:#800080;stroke-width:1.01649296px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 330.5,245.6526 C 330.5,245.6526 335.52404,242.44851 338.625,245.6526 341.72596,248.85669 335.98072,276.17837 338.625,278.91059 341.26928,281.64281 342.60983,281.15388 342.60983,281.15388"
       id="path4668"
       style="opacity:0.98999999;fill:none;stroke:#800080;stroke-width:1.01649296px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
	</center>
	NOTE: for the production <i>X</i> $\longrightarrow$ $\varepsilon$, there is just one item: <i>X</i> $\longrightarrow$ <b>.</b> $\varepsilon$	</div>
</div>

<p>
The item &quot;<i>PList</i> $\longrightarrow$ <b>.</b> <b>lparens</b> <i>IDList</i> <b>rparens</b>&quot; can be thought of as meaning &quot;we may be
parsing a <i>PList</i>, but so far we haven't seen anything&quot;.

<p>
The item 
&quot;<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b>&quot; 
means &quot;we may be parsing a <i>PList</i>, and so
	far we've seen a <b>lparens</b>&quot;.

<p>
The item 
&quot;<i>PList</i> $\longrightarrow$ <b>lparens</b> <i>IDList</i> <b>.</b> <b>rparens</b>&quot; 
means "we may be parsing a <i>PList</i>, and so far
	we've seen a <b>lparens</b> and parsed an <i>IDList</i>.

</UL>



<p>
We need 2 operations on sets of items: Closure and Goto
</p>

<a class=subsubhead>Closure</a>
<p>
To compute Closure($I$), where $I$ is a set of items:
</p>
<OL class=style1>
  <LI> put $I$ itself into Closure($I$)</li>
	<LI>
<table style="display:inline">

<tr><td>while</td><td></td></tr>

<tr>
<td></td>
<td>
there exists an item in Closure($I$) of the form

<UL>
		 <i>X</i> $\longrightarrow$ $\alpha$ <b>.</b> <i>B</i> $\beta$</UL>
		such that there is a production <i>B</i> $\longrightarrow$ $\gamma$, and 
		<i>B</i> $\longrightarrow$ <b>.</b> $\gamma$ is
       <em>not</em> in Closure($I$)
</ul>
</td>
</tr>
<tr>
<td>
do
</td>
<td>
</td>
</tr>

<tr>
<td></td>
<td>add <i>B</i> $\longrightarrow$ <b>.</b> $\gamma$ to Closure($I$)</td>
</tr>

</table>
</OL>

<p>
The idea is that the item 
&quot;<i>X</i> $\longrightarrow$ $\alpha$ <b>.</b> <i>B</i> $\beta$&quot;
means 
&quot;we may be trying to parse an <i>X</i>, and so far we've parsed all of 
$\alpha$, so the
next thing we'll parse may be a <i>B</i>&quot;.  And the item 
&quot;<i>B</i> $\longrightarrow$ <b>.</b> $\gamma$&quot;
also means that the next thing we'll parse may be a <i>B</i> (in particular,
	a <i>B</i> that derives $\gamma$), but we haven't seen any part of it yet.
</p>

<p>
<u>Example 1:</u> 
Closure({ <i>PList</i> $\longrightarrow$ <b>.</b> <b>lparens</b> <i>IDList</i> <b>rparens</b> })
<UL>
<p>
We'll begin by putting the initial item into the Closure (Step 1 above). So far, our
set is: { <i>PList</i> $\longrightarrow$ <b>.</b> <b>lparens</b> <i>IDList</i> <b>rparens</b>}
</p>
<p>
Now, we will do step 2, checking the set we build for productions of the form 
<i>B</i> $\longrightarrow$ $\gamma$, where the item <i>B</i> 
$\longrightarrow$ <b>.</b> $\gamma$ is not in the set. There's only one item that 
we can check, and the symbol to the immediate right of the dot is <b>lparens</b> which is
a terminal symbol. Obviously, there are no productions of the form <i>B</i> $\longrightarrow$ $\gamma$ with a terminal symbol on the left-hand side, so there's nothing else to check. 
</p>
<p>
With Step 2 exhausted, we can return the set we've built up:
Closure({ <i>PList</i> $\longrightarrow$ <b>.</b> <b>lparens</b> <i>IDList</i> <b>rparens</b> }) = 
{ <i>PList</i> $\longrightarrow$ <b>.</b> <b>lparens</b> <i>IDList</i> <b>rparens</b> }
</p>
</Ul>
<u>Example 2:</u>
Closure({ <i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> })
<ul>
<p>
As with the previous example, we put the initial item into the Closure. So far, our set is 
{ <i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> }
</p>
<p>
For step 2, we begin by selecting the only item in our working set, <i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b>. We now look for productions with a left-hand side of <i>IDList</i>, since that's the symbol to the immediate right of the dot. One production of this form is &quot;<i>IDList</i> $\longrightarrow$ <b>id</b>&quot;. Since the item <i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b> is not in the Closure yet, we add it. Our set so far is 
{ 
<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> ,
<i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b>}
</p>

<p>
We know that the item that we just added, 
<i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b> will not yield any more items, because the symbol immediately to the right of 
the dot is a terminal. However, we still haven't captured every production with 
<i>IDList</i> on the left-hand side, which we need to check because of 
our initial item. The grammar also has the production 
<i>IDList</i> $\longrightarrow$ <i>IDList</i> <b>id</b>, so we add
the item 
&quot;<i>IDList</i> $\longrightarrow$ <b>.</b> <i>IDList</i> <b>id</b>&quot;
to the closure. At this point, our working set is 
{
<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> ,
<i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b>,
<i>IDList</i> $\longrightarrow$ <b>.</b> <i>IDList</i> <b>id</b>}
</p>

<p>
The new item that we added has <i>IDList</i> to the right-hand side of the dot.
Fortunately, we've already exhausted every production of the grammar with <i>IDList</i> on the left-hand side. Thus, we can pronounce our working set complete:
</p>
<p>
Closure({ <i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> })
=
{
<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> ,
<i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b>,
<i>IDList</i> $\longrightarrow$ <b>.</b> <i>IDList</i> <b>id</b>}
</p>
</ul>
</UL>

<a class=subsubhead>Goto</a>
<p>
Now that we have defined the Closure of a set of items, we can use it to define the
Goto operation. The basic idea is that $I$ tells us where we might
be in the parse, and Goto($I$, X) tells us where we might be after parsing an X. Here
is the definition:
<UL>
	Let $I$ be a set of items, and X be a grammar symbol (i.e. a single terminal or nonterminal). <br />
  Goto($I$, X) = the Closure of the set of items
	<UL> <i>A</i> $\longrightarrow$ $\alpha$ <i>X</i> <b>.</b> $\beta$ </UL>
    such that
	<UL> <i>A</i> $\longrightarrow$ $\alpha$ <b>.</b> <i>X</i> $\beta$ </UL>
    is in $I$
  </UL>
</UL>


<u>Example 1</u>: Goto($I$<sub>1</sub>, X<sub>1</sub>)
<UL>
where 
<ul>
$I$<sub>1</sub> = { <i>PList</i> $\longrightarrow$ <b>.</b> <b>lparens</b> <i>IDList</i> <b>rparens</b> }
<br />
X<sub>1</sub> = <b>lparens</b></ul>

<p>
Let us begin by defining an intermediate set:
<ul>
$\mathcal{W}$ = the set of items of the form 
<i>A</i> $\longrightarrow$ $\alpha$ <i>X</i> <b>.</b> $\beta$ such that an item of the form
<i>A</i> $\longrightarrow$ $\alpha$ <b>.</b> <i>X</i> $\beta$ is in $I$.
</ul>
</p>

<p>
We can now build $\mathcal{W}$ by taking each item from $I$ (of which there is only one) and advancing the dot to the right.<br />Thus, $\mathcal{W}$ = {
<i>PList</i> $\longrightarrow$ <b>lparens</b>  <b>.</b> <i>IDList</i> <b>rparens</b>}
</p>

<p>
With $\mathcal{W}$ in hand, we are ready to perform the Goto operation by computing Closure($\mathcal{W}$) = 
Closure(
{
<i>PList</i> $\longrightarrow$ <b>lparens</b>  <b>.</b> <i>IDList</i> <b>rparens</b>}
)
. We already computed this closure above as 
{
<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> ,
<i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b>,
<i>IDList</i> $\longrightarrow$ <b>.</b> <i>IDList</i> <b>id</b>}, so we are done:
</p>
<ul>
<p>
Goto($I$<sub>1</sub>, X<sub>1</sub>) = 
{
<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> ,
<i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b>,
<i>IDList</i> $\longrightarrow$ <b>.</b> <i>IDList</i> <b>id</b>}
</p>
</ul>
</ul>

<u>Example 2</u>: Goto( $I$<sub>2</sub>, X<sub>2</sub> ) 
<ul>
where
<ul>
<p>
$I$<sub>2</sub> = Goto($I$<sub>1</sub>, X<sub>1</sub> )<br />
X<sub>2</sub> = <i>IDList</i></p>
</ul>
<p>
The inner Goto operation is the result of Example 1, so we can substitute that result directly. Expanded, the problem statement is:<br />
<div class=row>
<div class=12u>
<center>
Goto( {
<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b> ,
<i>IDList</i> $\longrightarrow$ <b>.</b> <b>id</b>,
<i>IDList</i> $\longrightarrow$ <b>.</b> <i>IDList</i> <b>id</b>} , <i>IDList</i>)
</center>
</div>
</div>
Again, we start by computing $\mathcal{W}$:
<div class=row>
<div class=12u>
<center>
<table width="auto" style="width:auto">
<tr>
<td><b>Item in $I$
	of the form 
<i>A</i> $\longrightarrow$ $\alpha$ <b>.</b> <i>X</i> $\beta$ 
</b>
</td>
<td style="padding-right:60px"></td>
<td>
	<b>
	Item of the form <i>A</i> $\longrightarrow$ $\alpha$ <i>X</i> <b>.</b> $\beta$ 
</b>
</td>
</tr>

<tr>
<td>
<i>PList</i> $\longrightarrow$ <b>lparens</b> <b>.</b> <i>IDList</i> <b>rparens</b></td>
<td></td>
<td>
<i>PList</i> $\longrightarrow$ <b>lparens</b> <i>IDList</i> <b>.</b> <b>rparens</b></td>
</tr>

<tr>
<td>
<i>IDList</i> $\longrightarrow$ <b>.</b> <i>IDList</i> <b>id</b></td>
<td> </td>
<td>
<i>IDList</i> $\longrightarrow$ <i>IDList</i> <b>.</b> <b>id</b></td>
</tr>
</table>
</center>
</div>
</div>
<p>
Thus, $\mathcal{W}$ = {
<i>PList</i> $\longrightarrow$ <b>lparens</b> <i>IDList</i> <b>.</b> <b>rparens</b>, <i>IDList</i> $\longrightarrow$ <i>IDList</i> <b>.</b> <b>id</b> 
}
</p>

<p>
We can now take the closure of $\mathcal{W}$ to complete the operation. This 
turns out to be trivial, since no element in $\mathcal{W}$ is followed by a nonterminal, and
therefore yields no additional items. Thus, Goto($I$<sub>2</sub>, X<sub>2</sub>) = Closure($\mathcal{W}$) = $\mathcal{W}$ = 
<br />
<i>PList</i> $\longrightarrow$ <b>lparens</b> <i>IDList</i> <b>.</b> <b>rparens</b>, <i>IDList</i> $\longrightarrow$ <i>IDList</i> <b>.</b> <b>id</b>
}

</p>
<ul>
</ul> <!-- end example2 list -->

<!--
<pre>
I = { plist &rarr; . ( idlist ) }

Goto( I, "(" ) = Closure( { plist &rarr; ( . idlist ) } =
    { plist &rarr; ( . idlist )
      idlist &rarr; . ID
      idlist &rarr; . idlist ID
    }
Goto( Goto ( I, "(" ), idlist ) =
    { plist &rarr; ( idlist . )
      idlist &rarr; idlist . ID
    }
</pre>
</UL>
-->

<P>
Our ultimate goal is to create a 
To build the FSM:
<OL class=style1>
  <LI> Augment the grammar
    <UL> (a) add new start nonterminal S'
	 <BR> (b) add new production S' &rarr; S (where S is the old start nonterminal)
    </UL>
  <LI> I<sub>0</sub> = closure({ S' &rarr; . S })
  <LI> for each grammar symbol X such that there is an item in I<sub>0</sub>
       containing ".X" do
       <UL> add a transition on X from state I<sub>0</sub> to state
            Goto(I<sub>0</sub>,X)
      </UL>
  <LI> repeat step (3) for each new state until there are no more new states
</OL>
Note: In step (3), when we say "state Goto(...)" we mean the state that
contains exactly that set of items; if there is not already such a state,
then create a new one.

<p>
<u>Example</u>
<UL>

<pre>

  <u>grammar</u>
S' &rarr; plist
plist &rarr; ( idlist )
idlist &rarr; ID
idlist &rarr; idlist ID
</pre>
<div class=row>
	<div class=12u>
<center>
	<p>Corresponding SLR FSM</p>
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="435"
   height="398"
   id="svg2">
  <defs
     id="defs4">
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         id="path3894"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt" />
    </marker>
  </defs>
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
     transform="translate(-122.69029,-220.97719)"
     id="layer1">
    <rect
       width="161.82402"
       height="54.006927"
       rx="13.919923"
       ry="13.919923"
       x="141.58131"
       y="252.97018"
       id="rect2985"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="184.1918"
       y="274.29001"
       id="text3777"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="184.1918"
         y="274.29001"
         id="tspan3779"
         style="font-size:14px"><tspan
   id="tspan3783"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">S'</tspan> → <tspan
   id="tspan3781"
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"><tspan
   id="tspan3835"
   style="font-style:normal;font-weight:bold">.</tspan> PList</tspan></tspan></text>
    <text
       x="163.94464"
       y="291.68991"
       id="text3791"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="163.94464"
         y="291.68991"
         id="tspan3793"
         style="font-size:14px"><tspan
   id="tspan3795"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan3797"
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"><tspan
   id="tspan3805"
   style="font-style:normal;font-weight:bold">. ( </tspan>IDList <tspan
   id="tspan3803"
   style="font-style:normal;font-weight:bold">)</tspan></tspan></tspan></text>
    <text
       x="161.94464"
       y="441.15109"
       id="text3823"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="161.94464"
         y="441.15109"
         id="tspan3825"
         style="font-size:14px"><tspan
   id="tspan3827"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan3829"
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"><tspan
   id="tspan3831"
   style="font-style:normal;font-weight:bold">( . </tspan>IDList <tspan
   id="tspan3833"
   style="font-style:normal;font-weight:bold">)</tspan></tspan></tspan></text>
    <text
       x="161.94464"
       y="463.92914"
       id="text3839"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="161.94464"
         y="463.92914"
         id="tspan3841"
         style="font-size:14px"><tspan
   id="tspan3843"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> →<tspan
   id="tspan3845"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"> . ID</tspan></tspan></text>
    <text
       x="161.94464"
       y="487.97266"
       id="text3855"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="161.94464"
         y="487.97266"
         id="tspan3857"
         style="font-size:14px"><tspan
   id="tspan3859"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan3861"
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"><tspan
   id="tspan3863"
   style="font-style:normal;font-weight:bold">. </tspan>IDList <tspan
   id="tspan3867"
   style="font-style:normal;font-weight:bold">ID</tspan></tspan></tspan></text>
    <rect
       width="164.14278"
       height="92.377655"
       rx="13.919923"
       ry="13.919923"
       x="141.22955"
       y="412.67853"
       id="rect3869"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       d="M 503.64805,639.1936 A 52.516064,52.516064 0 1 1 398.61592,639.1936 52.516064,52.516064 0 1 1 503.64805,639.1936 z"
       transform="matrix(0.36961859,0,0,0.36961859,-24.145568,181.04601)"
       id="path3873"
       style="fill:#ffffff;stroke:#000000;stroke-width:2.70549154;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="136.35544"
       y="421.47269"
       id="text4996"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="142.19138"
         y="0"
         id="tspan4998"><tspan
           x="136.35544"
           y="421.47269"
           id="tspan5000">I</tspan><tspan
           x="142.19138"
           y="421.47269"
           id="tspan5002"
           style="font-size:65.00091553%;baseline-shift:sub">2</tspan></tspan></text>
    <path
       d="M 214.49332,307.97711 214.49332,412.67853"
       id="path3885"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)" />
    <text
       x="227.32574"
       y="377.246"
       id="text4331"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="227.32574"
         y="377.246"
         id="tspan4333"
         style="font-size:14px"><tspan
           id="tspan4337"
           style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">(</tspan></tspan></text>
    <rect
       width="108.56921"
       height="43.006927"
       rx="12.919922"
       ry="12.919922"
       x="419.83347"
       y="249.50554"
       id="rect4343"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="436.57193"
       y="275.79001"
       id="text4359"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="436.57193"
         y="275.79001"
         id="tspan4361"
         style="font-size:14px"><tspan
   id="tspan4363"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">S'</tspan> → <tspan
   id="tspan4365"
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">PList <tspan
   id="tspan4381"
   style="font-style:normal;font-weight:bold">.</tspan></tspan></tspan></text>
    <path
       d="M 306.22474,272.49885 417.8551,272.49885"
       id="path4383"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)" />
    <path
       d="M 214.49332,504.78324 214.49332,572.48466"
       id="path4385"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)" />
    <text
       x="227.32574"
       y="537.05212"
       id="text4387"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="227.32574"
         y="537.05212"
         id="tspan4389"
         style="font-size:14px;font-weight:bold">ID</tspan></text>
    <rect
       width="113.82402"
       height="43.006958"
       rx="13.919923"
       ry="13.919923"
       x="160.08131"
       y="575.47021"
       id="rect4393"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       d="M 503.64805,639.1936 A 52.516064,52.516064 0 1 1 398.61592,639.1936 52.516064,52.516064 0 1 1 503.64805,639.1936 z"
       transform="matrix(0.36961859,0,0,0.36961859,-5.373489,338.72297)"
       id="path4397"
       style="fill:#ffffff;stroke:#000000;stroke-width:2.70549154;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="155.12752"
       y="579.14966"
       id="text4988"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="160.96346"
         y="0"
         id="tspan4990"><tspan
           x="155.12752"
           y="579.14966"
           id="tspan4992">I</tspan><tspan
           x="160.96346"
           y="579.14966"
           id="tspan4994"
           style="font-size:65.00091553%;baseline-shift:sub">4</tspan></tspan></text>
    <text
       x="175.44464"
       y="600.18994"
       id="text4419"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="175.44464"
         y="600.18994"
         id="tspan4421"
         style="font-size:14px"><tspan
   id="tspan4423"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan> → <tspan
   id="tspan4431"
   style="font-weight:bold">ID</tspan><tspan
   id="tspan4425"
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"><tspan
     id="tspan4427"
     style="font-style:normal;font-weight:bold"> .</tspan></tspan></tspan></text>
    <text
       x="419.94464"
       y="596.68994"
       id="text4449"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="419.94464"
         y="596.68994"
         id="tspan4451"
         style="font-size:14px"><tspan
   id="tspan4453"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan> → <tspan
   id="tspan4461"
   style="font-style:italic">IDList</tspan><tspan
   id="tspan4455"
   style="font-weight:bold"> ID</tspan><tspan
   id="tspan4457"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"> .</tspan></tspan></text>
    <rect
       width="159.82401"
       height="62.006927"
       rx="13.919923"
       ry="13.919923"
       x="392.58133"
       y="434.97018"
       id="rect4750"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="414.19177"
       y="461.29001"
       id="text4479"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="414.19177"
         y="461.29001"
         id="tspan4481"
         style="font-size:14px"><tspan
   id="tspan4483"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList'</tspan> → <tspan
   id="tspan4485"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">( <tspan
   id="tspan4527"
   style="font-style:italic;font-weight:normal">IDList</tspan> . )</tspan></tspan></text>
    <text
       x="328.57193"
       y="449.29001"
       id="text4501"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="328.57193"
         y="449.29001"
         id="tspan4503"
         style="font-size:14px"><tspan
           id="tspan4507"
           style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">IDList</tspan></tspan></text>
    <text
       x="414.19177"
       y="480.29001"
       id="text4537"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="414.19177"
         y="480.29001"
         id="tspan4539"
         style="font-size:14px"><tspan
   id="tspan4541"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList </tspan>→ <tspan
   id="tspan4543"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"><tspan
   id="tspan4545"
   style="font-style:italic;font-weight:normal">IDList</tspan> . ID</tspan></tspan></text>
    <path
       d="M 477.49332,497.78324 477.49332,565.48466"
       id="path4547"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)" />
    <text
       x="490.32574"
       y="530.05212"
       id="text4549"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="490.32574"
         y="530.05212"
         id="tspan4551"
         style="font-size:14px;font-weight:bold">ID</tspan></text>
    <rect
       width="159.82401"
       height="43.006927"
       rx="13.919923"
       ry="13.919923"
       x="395.58133"
       y="333.97018"
       id="rect4553"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       d="M 503.64805,639.1936 A 52.516064,52.516064 0 1 1 398.61592,639.1936 52.516064,52.516064 0 1 1 503.64805,639.1936 z"
       transform="matrix(0.36961859,0,0,0.36961859,231.44884,99.072208)"
       id="path4557"
       style="fill:#ffffff;stroke:#000000;stroke-width:2.70549154;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="391.94986"
       y="339.49887"
       id="text4964"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="397.7858"
         y="0"
         id="tspan4966"><tspan
           x="391.94986"
           y="339.49887"
           id="tspan4968">I</tspan><tspan
           x="397.7858"
           y="339.49887"
           id="tspan4970"
           style="font-size:65.00091553%;baseline-shift:sub">5</tspan></tspan></text>
    <g
       transform="translate(-102,-2)"
       id="g4702">
      <text
         x="511.19177"
         y="365.29001"
         id="text4569"
         xml:space="preserve"
         style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
           x="511.19177"
           y="365.29001"
           id="tspan4571"
           style="font-size:14px"><tspan
   id="tspan4573"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan> → <tspan
   id="tspan4575"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">( <tspan
   id="tspan4577"
   style="font-style:italic;font-weight:normal">IDList</tspan> ) .</tspan></tspan></text>
      <text
         x="511.19177"
         y="384.29001"
         id="text4579"
         xml:space="preserve"
         style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
           x="511.19177"
           y="384.29001"
           id="tspan4581"
           style="font-size:14px">
<!--<tspan
   id="tspan4583"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList </tspan>→ <tspan
   id="tspan4585"
   style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"><tspan
   id="tspan4587"
   style="font-style:italic;font-weight:normal">IDList</tspan> . ID</tspan></tspan>
--></text>
    </g>
    <path
       d="M 305.22474,465.49885 391.8551,465.49885"
       id="path4589"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)" />
    <path
       d="M 478.03926,434.78944 478.03926,378.04818"
       id="path4591"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)" />
    <text
       x="489.70758"
       y="419.67239"
       id="text4593"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="489.70758"
         y="419.67239"
         id="tspan4595"
         style="font-size:14px"><tspan
           id="tspan4597"
           style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">)</tspan></tspan></text>
    <text
       x="334.57193"
       y="261.29001"
       id="text4599"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="334.57193"
         y="261.29001"
         id="tspan4601"
         style="font-size:14px"><tspan
           id="tspan4603"
           style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">PList</tspan></tspan></text>
    <path
       d="M 503.64805,639.1936 A 52.516064,52.516064 0 1 1 398.61592,639.1936 52.516064,52.516064 0 1 1 503.64805,639.1936 z"
       transform="matrix(0.36961859,0,0,0.36961859,-23.373489,15.72297)"
       id="path4670"
       style="fill:#ffffff;stroke:#000000;stroke-width:2.70549154;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="137.12752"
       y="256.14963"
       id="text4929"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="142.96346"
         y="0"
         id="tspan4931"><tspan
           x="137.12752"
           y="256.14963"
           id="tspan4933">I</tspan><tspan
           x="142.96346"
           y="256.14963"
           id="tspan4935"
           style="font-size:65.00091553%;baseline-shift:sub">0</tspan></tspan></text>
    <path
       d="M 503.64805,639.1936 A 52.516064,52.516064 0 1 1 398.61592,639.1936 52.516064,52.516064 0 1 1 503.64805,639.1936 z"
       transform="matrix(0.36961859,0,0,0.36961859,256.62651,8.22297)"
       id="path4684"
       style="fill:#ffffff;stroke:#000000;stroke-width:2.70549154;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="417.12753"
       y="248.64963"
       id="text4956"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="422.96347"
         y="0"
         id="tspan4958"><tspan
           x="417.12753"
           y="248.64963"
           id="tspan4960">I</tspan><tspan
           x="422.96347"
           y="248.64963"
           id="tspan4962"
           style="font-size:65.00091553%;baseline-shift:sub">1</tspan></tspan></text>
    <path
       d="M 503.64805,639.1936 A 52.516064,52.516064 0 1 1 398.61592,639.1936 52.516064,52.516064 0 1 1 503.64805,639.1936 z"
       transform="matrix(0.36961859,0,0,0.36961859,231.44884,198.07221)"
       id="path4764"
       style="fill:#ffffff;stroke:#000000;stroke-width:2.70549154;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="391.94986"
       y="438.49887"
       id="text4972"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="397.7858"
         y="0"
         id="tspan4974"><tspan
           x="391.94986"
           y="438.49887"
           id="tspan4976">I</tspan><tspan
           x="397.7858"
           y="438.49887"
           id="tspan4978"
           style="font-size:65.00091553%;baseline-shift:sub">3</tspan></tspan></text>
    <rect
       width="156.82401"
       height="43.006958"
       rx="13.919923"
       ry="13.919923"
       x="399.08133"
       y="570.47021"
       id="rect4776"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       d="M 503.64805,639.1936 A 52.516064,52.516064 0 1 1 398.61592,639.1936 52.516064,52.516064 0 1 1 503.64805,639.1936 z"
       transform="matrix(0.36961859,0,0,0.36961859,233.62651,333.72297)"
       id="path4780"
       style="fill:#ffffff;stroke:#000000;stroke-width:2.70549154;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       x="394.12753"
       y="574.14966"
       id="text4980"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="399.96347"
         y="inf"
         id="tspan4982"><tspan
           x="394.12753"
           y="574.14966"
           id="tspan4984">I</tspan><tspan
           x="399.96347"
           y="574.14966"
           id="tspan4986"
           style="font-size:65.00091553%;baseline-shift:sub">6</tspan></tspan></text>
  </g>
</svg>
</center>
	</div>
</div>
<pre>
</UL>

<p>
Given the FSM, here's how to build Action and Goto tables:

<DL>
  <DT>Action Table:
  <DD><OL>
    <LI> if state i includes item
      <UL> A &rarr; alpha . a beta </UL>
         where a is a <em>terminal</em>, and the transition out of state i on a
	 is to state j,
	 <BR>then set Action[i,a] = shift j
  <LI> if state i includes item
    <UL> A &rarr; alpha . </UL>
       where A is <em>not</em> the new start symbol S'
       <BR> then for every a in FOLLOW(A),
       set Action[i,a] = reduce by A &rarr; alpha
  <LI> if state i includes item
    <UL> S' &rarr; S . </UL>
       then set Action[i,$] = accept
  <LI> set all other entries of Action table to error
  </OL>
  <DT> Goto Table:
  <DD> for every nonterminal X
       <br>if there is a transition from state i to state j on X
       <br> then set Goto[i, X] = j
</DL>

<p>
<u>Example</u>
<UL>
<pre>
  FOLLOW(idlist) = { ), ID }
  FOLLOW(plist) = { $ }
</pre>
<div class=row>
	<div class=12u>
<center>
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="630"
   height="565"
   id="svg2">
  <defs
     id="defs4">
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         id="path3894"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt" />
    </marker>
  </defs>
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
     transform="translate(-13.607058,-109.24519)"
     id="layer1">
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="223.57973"
       y="544.20392"
       id="rect5023"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="320.69031"
       y="544.20392"
       id="rect5137"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="126.46914"
       y="544.20392"
       id="rect5157"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="417.80093"
       y="544.20392"
       id="rect5159"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="417.8009"
       y="485.58411"
       id="rect5161"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="223.57973"
       y="485.58411"
       id="rect5185"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="320.69031"
       y="485.58411"
       id="rect5205"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="126.46914"
       y="485.58411"
       id="rect5225"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="417.8009"
       y="426.81122"
       id="rect5227"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="223.57973"
       y="426.81122"
       id="rect5249"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="320.69031"
       y="426.81122"
       id="rect5269"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="126.46914"
       y="426.81122"
       id="rect5289"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="417.8009"
       y="368.03833"
       id="rect5291"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="223.57973"
       y="368.03833"
       id="rect5293"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="320.69031"
       y="368.03833"
       id="rect5313"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="126.46914"
       y="368.03833"
       id="rect5333"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="417.8009"
       y="309.26547"
       id="rect5335"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="223.57973"
       y="309.26547"
       id="rect5337"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="320.69031"
       y="309.26547"
       id="rect5343"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="126.46914"
       y="309.26547"
       id="rect5349"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="417.8009"
       y="249.88667"
       id="rect5351"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="223.57973"
       y="249.88667"
       id="rect5353"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="320.69031"
       y="249.88667"
       id="rect5355"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="126.46914"
       y="249.88667"
       id="rect5361"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="417.8009"
       y="190.50789"
       id="rect5363"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="223.57973"
       y="190.50789"
       id="rect5365"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="320.69031"
       y="190.50789"
       id="rect5367"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="97.059242"
       height="58.741806"
       rx="0"
       ry="0"
       x="126.46914"
       y="190.50789"
       id="rect5373"
       style="fill:none;stroke:#000000;stroke-width:1.55768645;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="515.31677"
       y="544.31293"
       id="rect5476"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="515.31677"
       y="485.64978"
       id="rect5478"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="515.31677"
       y="426.83347"
       id="rect5480"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="515.31677"
       y="368.01712"
       id="rect5482"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="515.31677"
       y="309.20084"
       id="rect5484"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="515.31677"
       y="249.77817"
       id="rect5486"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="515.31677"
       y="190.3555"
       id="rect5488"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="578.46057"
       y="544.31293"
       id="rect5494"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="578.46057"
       y="485.64978"
       id="rect5496"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="578.46057"
       y="426.83347"
       id="rect5498"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="578.46057"
       y="368.01712"
       id="rect5500"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="578.46057"
       y="309.20084"
       id="rect5502"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="578.46057"
       y="249.77817"
       id="rect5504"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       width="62.747093"
       height="58.785213"
       rx="0"
       ry="0"
       x="578.46057"
       y="190.3555"
       id="rect5506"
       style="fill:none;stroke:#000000;stroke-width:1.25290692;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
  </g>
  <g
     transform="translate(109.08324,111.732)"
     id="layer2"
     style="display:inline">
    <text
       x="103"
       y="369.961"
       id="text5776"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="177.02344"
         y="0"
         id="tspan5778"><tspan
           x="103"
           y="369.961"
           id="tspan5780"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="131.16406"
           y="369.961"
           id="tspan5782"
           style="font-size:12px"> → </tspan><tspan
           x="148.85938"
           y="369.961"
           id="tspan5784"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="177.02344"
           y="369.961"
           id="tspan5786"
           style="font-size:10px;font-weight:bold"> ID</tspan></tspan></text>
    <text
       x="120"
       y="351.99997"
       id="text5133"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="120"
         y="351.99997"
         id="tspan5135"
         style="font-size:14px">reduce by</tspan></text>
    <text
       x="199.99998"
       y="369.961"
       id="text5788"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="274.02341"
         y="0"
         id="tspan5790"><tspan
           x="199.99998"
           y="369.961"
           id="tspan5792"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="228.16405"
           y="369.961"
           id="tspan5794"
           style="font-size:12px"> → </tspan><tspan
           x="245.85936"
           y="369.961"
           id="tspan5796"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="274.02341"
           y="369.961"
           id="tspan5798"
           style="font-size:10px;font-weight:bold"> ID</tspan></tspan></text>
    <text
       x="216.99998"
       y="351.99997"
       id="text5153"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="216.99998"
         y="351.99997"
         id="tspan5155"
         style="font-size:14px">reduce by</tspan></text>
    <text
       x="300.18604"
       y="307.21362"
       id="text5738"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="382.07666"
         y="0"
         id="tspan5740"><tspan
           x="300.18604"
           y="307.21362"
           id="tspan5742"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan><tspan
           x="323.70166"
           y="307.21362"
           id="tspan5744"
           style="font-size:12px"> → </tspan><tspan
           x="341.39697"
           y="307.21362"
           id="tspan5746"
           style="font-size:12px;font-weight:bold;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">(</tspan><tspan
           x="346.76416"
           y="307.21362"
           id="tspan5748"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"> IDList </tspan><tspan
           x="382.07666"
           y="307.21362"
           id="tspan5750"
           style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Bold">)</tspan></tspan></text>
    <text
       x="314"
       y="289.25262"
       id="text5177"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="314"
         y="289.25262"
         id="tspan5179"
         style="font-size:14px">reduce by</tspan></text>
    <text
       x="103"
       y="250.21362"
       id="text5764"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="177.02344"
         y="0"
         id="tspan5766"><tspan
           x="103"
           y="250.21362"
           id="tspan5768"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="131.16406"
           y="250.21362"
           id="tspan5770"
           style="font-size:12px"> → </tspan><tspan
           x="148.85938"
           y="250.21362"
           id="tspan5772"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="177.02344"
           y="250.21362"
           id="tspan5774"
           style="font-size:10px;font-weight:bold"> ID</tspan></tspan></text>
    <text
       x="120"
       y="232.25261"
       id="text5265"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="120"
         y="232.25261"
         id="tspan5267"
         style="font-size:14px">reduce by</tspan></text>
    <text
       x="199.99998"
       y="250.21362"
       id="text5752"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="274.02341"
         y="0"
         id="tspan5754"><tspan
           x="199.99998"
           y="250.21362"
           id="tspan5756"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="228.16405"
           y="250.21362"
           id="tspan5758"
           style="font-size:12px"> → </tspan><tspan
           x="245.85936"
           y="250.21362"
           id="tspan5760"
           style="font-size:10px;font-style:italic;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan><tspan
           x="274.02341"
           y="250.21362"
           id="tspan5762"
           style="font-size:10px;font-weight:bold"> ID</tspan></tspan></text>
    <text
       x="216.99998"
       y="232.25261"
       id="text5285"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="216.99998"
         y="232.25261"
         id="tspan5287"
         style="font-size:14px">reduce by</tspan></text>
    <text
       x="130.52689"
       y="181.57704"
       id="text5309"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="130.52689"
         y="181.57704"
         id="tspan5311"
         style="font-size:14px">shift 5</tspan></text>
    <text
       x="227.27486"
       y="181.55605"
       id="text5329"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="227.27486"
         y="181.55605"
         id="tspan5331"
         style="font-size:14px">shift 6</tspan></text>
    <text
       x="227.27486"
       y="122.60818"
       id="text5345"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="227.27486"
         y="122.60818"
         id="tspan5347"
         style="font-size:14px">shift 4</tspan></text>
    <text
       x="323.84317"
       y="60.772381"
       id="text5357"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="323.84317"
         y="60.772381"
         id="tspan5359"
         style="font-size:14px">accept</tspan></text>
    <text
       x="33.248997"
       y="4.046598"
       id="text5369"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="33.248997"
         y="4.046598"
         id="tspan5371"
         style="font-size:14px">shift 2</tspan></text>
    <text
       x="401.59317"
       y="-37.477619"
       id="text5472"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="401.59317"
         y="-37.477619"
         id="tspan5474"
         style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">PList</tspan></text>
    <text
       x="465.53787"
       y="-37.477619"
       id="text5490"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="465.53787"
         y="-37.477619"
         id="tspan5492"
         style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">IDList</tspan></text>
    <text
       x="42.723797"
       y="-37.477619"
       id="text5508"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="42.723797"
         y="-37.477619"
         id="tspan5510"
         style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
           id="tspan5512"
           style="font-style:normal;font-weight:bold">(</tspan></tspan></text>
    <text
       x="142.7238"
       y="-37.477619"
       id="text5514"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="142.7238"
         y="-37.477619"
         id="tspan5516"
         style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
           id="tspan5518"
           style="font-style:normal;font-weight:bold">)</tspan></tspan></text>
    <text
       x="242.46997"
       y="-37.477619"
       id="text5520"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="242.46997"
         y="-37.477619"
         id="tspan5522"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">ID</tspan></text>
    <text
       x="339.62222"
       y="-37.477619"
       id="text5526"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="339.62222"
         y="-37.477619"
         id="tspan5528"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">$</tspan></text>
    <text
       x="-12.570275"
       y="4.0535979"
       id="text5530"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="-12.570275"
         y="4.0535979"
         id="tspan5532"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">0</tspan></text>
    <text
       x="-12.570275"
       y="122.94425"
       id="text5536"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="-12.570275"
         y="122.94425"
         id="tspan5538"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">2</tspan></text>
    <text
       x="-12.570275"
       y="63.345581"
       id="text5540"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="-12.570275"
         y="63.345581"
         id="tspan5542"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">1</tspan></text>
    <text
       x="-12.570275"
       y="181.58453"
       id="text5544"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="-12.570275"
         y="181.58453"
         id="tspan5546"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">3</tspan></text>
    <text
       x="-12.570275"
       y="240.40088"
       id="text5548"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="-12.570275"
         y="240.40088"
         id="tspan5550"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">4</tspan></text>
    <text
       x="-12.570275"
       y="299.12982"
       id="text5552"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="-12.570275"
         y="299.12982"
         id="tspan5554"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">5</tspan></text>
    <text
       x="-12.570275"
       y="357.74963"
       id="text5556"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="-12.570275"
         y="357.74963"
         id="tspan5558"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">6</tspan></text>
    <text
       x="147"
       y="-96.191895"
       id="text5686"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="147"
         y="-96.191895"
         id="tspan5688"><tspan
           x="147"
           y="-96.191895"
           id="tspan5690">Action Table</tspan></tspan></text>
    <path
       d="M 10.000006,-57.25263 10.000006,-79.75263 197.50001,-79.75263 197.50001,-86.25263 197.50001,-87.25263"
       id="path5570"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 383.18482,-57.25263 383.18482,-79.75263 197.68482,-79.75263 197.68482,-86.25263 197.68482,-87.25263"
       id="path5572"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="412.00925"
       y="-96.191895"
       id="text5680"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="412.00925"
         y="-96.191895"
         id="tspan5682"><tspan
           x="412.00925"
           y="-96.191895"
           id="tspan5684">Goto Table</tspan></tspan></text>
    <path
       d="M 395.00924,-57.25263 395.00924,-79.75263 452.00924,-79.75263 452.00924,-86.25263 452.00924,-87.25263"
       id="path5582"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 508.00924,-57.25263 508.00924,-79.75263 451.00924,-79.75263 451.00924,-86.25263 451.00924,-87.25263"
       id="path5586"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="-108.18124"
       y="-36.009987"
       id="text5588"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"><tspan
         x="-108.18124"
         y="-36.009987"
         id="tspan5590"
         style="fill:#800080">Symbol</tspan></text>
    <text
       x="-60.681244"
       y="449.99002"
       id="text5592"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"><tspan
         x="-60.681244"
         y="449.99002"
         id="tspan5594"
         style="fill:#800080">FSM State</tspan></text>
    <path
       d="M -29.999994,424.74737 C -34.65539,407.38351 -11.332368,409.84095 -10.999994,386.74737"
       id="path5596"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M -16.499994,393.74737 -11.499994,382.74737 -5.9999941,395.24737"
       id="path5598"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M -41.557129,-43.695499 C -24.193269,-48.350895 -32.400709,-40.277873 -9.307129,-39.945499"
       id="path5600"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M -16.307129,-45.445499 -5.3071295,-40.445499 -17.807129,-34.945499"
       id="path5602"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="418.42972"
       y="4.0535979"
       id="text5711"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="418.42972"
         y="4.0535979"
         id="tspan5713"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">1</tspan></text>
    <text
       x="486.42972"
       y="122.94425"
       id="text5715"
       xml:space="preserve"
       style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"><tspan
         x="486.42972"
         y="122.94425"
         id="tspan5717"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">3</tspan></text>
  </g>
</svg>
</center>
	</div>
</div>
</UL>

<a name="slrConflicts"></a>
<span class=subsubhead>Conflicts</span>

<p>
Not every grammar is SLR(1).
If a grammar is <em>not</em> SLR(1), there will be a conflict in the
SLR Action table.
There is a conflict in the table if there is a table entry with more than
1 rule in it.
There are two possible kinds of conflicts:
<OL class=style1>
  <LI> shift/reduce conflicts (a shift and a reduce action in the same table
       entry)
  <LI> reduce/reduce conflicts (two reduce actions in the same table entry)
</OL>

<p>
A shift/reduce conflict means that it is not possible to determine, based only
on the top-of-stack state symbol and the current token, whether to shift
or to reduce.  This kind of conflict arises when one state contains two
items of the following form:
<OL class=style1>
<li>
<i>X</i> $\longrightarrow$ $\alpha$ <b>.</b> <b>a</b> $\beta$</li>

<li>
<i>Y</i> $\longrightarrow$ $\alpha$</li>
</OL>
and <b>a</b> is in FOLLOW(<i>Y</i>).

<p>
A reduce/reduce conflict means that it is not possible to determine, based only
on the top-of-stack state symbol and the current token, whether to reduce
by one grammar rule or by another grammar rule.
This kind of conflict arises when one state contains two
items of the form
<OL claass=style1>
	<li>
	<i>X</i> $\longrightarrow$ $\alpha$ <b>.</b>	</li>
	<li>
	<i>Y</i> $\longrightarrow$ $\beta$ <b>.</b>	</li>
</OL>
and there is some symbol <b>a</b> that is in both FOLLOW(<i>X</i>)
and FOLLOW(<i>Y</i>).

<p>
<span class=example> A non-SLR(1) grammar</span>
<p>
This grammar causes a shift/reduce conflict
</p>
<UL>

<div class=row>
	<div class=12u>
	<p><u>grammar</u></p>
	<i>S'</i> $\longrightarrow$ <i>S</i>	<br />
	<i>S</i> $\longrightarrow$ <i>A</i> $\times$ <i>B</i>									  |  <i>B</i>	<br />
	<i>A</i> $\longrightarrow$ <b>a</b>									  |  <b>+</b> <i>B</i>	<br />
	<i>B</i> $\longrightarrow$ <i>A</i>	</div>
</div>
<div class=row>
	<div class=12u>
		<p><u>relevant part of the FSM</u></p>
			</div>
</div>
</UL>

<div>
<span class=example> Another non-SLR(1) grammar</span>
</div>
<p>
This grammar causes a reduce/reduce conflict:
</p>
<div class=row>

	<div class=6u style="text-align:center width:auto" width=auto>
		<p><u>grammar</u></p>
		<div style="text-align:left">
			<i>S'</i> $\longrightarrow$ <i>S</i>			<br />
			<i>S</i> $\longrightarrow$ <b>a</b> <i>A</i> <b>d</b>											  |  <b>b</b> <i>B</i> <b>d</b>											  |  <b>a</b> <i>B</i> <b>e</b>											  |  <b>b</b> <i>A</i> <b>e</b>			<br />
			<i>A</i> $\longrightarrow$ <b>c</b>			<br />
			<i>B</i> $\longrightarrow$ <b>c</b>		</div>
	</div>

	<div class=6u>
		<p><u>Follow sets</u></p>
		Follow(<i>A</i>) = {<b>d</b>,<b>e</b>}
		<br />
		Follow(<i>B</i>) = {<b>d</b>,<b>e</b>}
	</div>
</div>

<div class=row>
	<div class=12u>
		<p><u>relevant part of the FSM</u></p>
			</div>
</div>
</UL>

This concludes our discussion of bottom-up parsing. Have a nice day.
