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
<span class=subhead>Contents</span>

<UL>
  <LI><A HREF="#overview">Overview</A>
  <LI><A HREF="reading.php?r=parsinggen">General Parsing</A>
  <LI><A HREF="#CYK">The CYK Algorithm</A>
   <UL>
     <LI><A HREF="#CNF">Conversion to Chomsky Normal form</A>
     <LI><A HREF="#youtry1">Test Yourself #1</A>
     <LI><A HREF="#input">Parsing an Input</A>
     <LI><A HREF="#youtry2">Test Yourself #2</A>
     <LI><A HREF="#accept">Accepting an Input</A>
     <LI><A HREF="#youtry3">Test Yourself #3</A>
   </UL>
</UL>

<P>
<a name=overview></a>
<span class="subhead">Overview</span>
<P>
There are algorithms that can be used to parse the language defined by an
arbitrary CFG.  However, in the worst case, the algorithms take
O(n<sup>3</sup>) time, where n is the number of tokens.  That is
<em>too slow</em>!
<P>
Fortunately, there are classes of grammars for which O(n) parsers can
be built (and given a grammar, we can quickly test whether it is in
such a class).
Two such classes are:
</p>

<!--<div style="align:center; text-align:center; margin-lef:auto; margin-right:auto">-->
<svg xmlns="http://www.w3.org/2000/svg" width=400 height=120 style="margin-left:auto; margin-right:auto; display:block">


<g transform="translate(0,18)"
>

		<text text-anchor=middle x="130" y="15" style="font-family:Source Sans Pro, sans-serif; font-weight: 400; fill:#505050">
			<tspan>L</tspan><tspan>L</tspan><tspan>(1)</tspan>
		</text>

    <path d="M 98,68 c 3.11112,-3.95715 4.6005,-9.15208 4.05866,-14.15653 -0.58524,-5.40527 -3.40802,-10.43814 -3.56687,-15.87268 -0.10628,-3.63599 1.0386,-7.29696 3.19749,-10.22456 2.15889,-2.92761 5.31803,-5.10321 5.82304,-6.07615"
       style="fill:none;stroke:purple;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"/>

<path
			 d="M 100,20 l 10 -4 2 10"
       style="fill:none;stroke:purple;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"/>

<text x=10 y=85 style="font-family:Indie Flower;fill:purple">
Scan input left-to-right
</text>


<path d="M 158,33 c -3.52854,-2.65373 -8.12902,-3.83671 -12.5,-3.21428 -1.74136,0.24797 -3.43072,0.76633 -5.13856,1.18719 -1.70784,0.42086 -3.46591,0.74677 -5.21858,0.59852 -2.27913,-0.19279 -4.47909,-1.21479 -6.09643,-2.83214 -1.61735,-1.61734 -2.63935,-3.8173 -2.83214,-6.09643"
       style="fill:none;stroke:purple;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"/>

<path d="M 123,25 l 2,-8 6,8"
       style="fill:none;stroke:purple;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"/>

<text x=160 y=40 style="font-family:Indie Flower;fill:purple">
Do a leftmost derivation
</text>



<path d="M 162,-10 c -1.30158,1.34834 -3.03968,2.26848 -4.88649,2.5869 -1.84682,0.31842 -3.7927,0.0334 -5.47065,-0.80118 -1.37391,-0.68339 -2.54309,-1.70649 -3.76951,-2.62874 -1.22642,-0.92225 -2.57248,-1.7713 -4.08763,-2.01412 -1.74402,-0.2795 -3.60681,0.32387 -4.85576,1.57281 -1.24894,1.24895 -1.85231,3.11174 -1.57281,4.85576"
       style="fill:none;stroke:purple;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"/>

<path d="M 132,-8 l 5,5 5,-5"
       style="fill:none;stroke:purple;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"/>


<text x=164 y=-7 style="font-family:Indie Flower;fill:purple">
One token of look-ahead
</text>

</g>
</svg>

and<br />

<svg xmlns="http://www.w3.org/2000/svg" width=550 height=180 style="margin-left:auto; margin-right:auto; display:block">

  <g transform="translate(0,60)">

<text text-anchor=middle x="175" y="45" style="font-family:Source Sans Pro, sans-serif; font-weight:400; font-size:12pt; fill:#505050">LALR(1)</text>




<path d="M0,0 m0,0" />
<path
       d="m 120.99177,0.54925 c -0.23107,2.85555 0.62604,5.78585 2.35953,8.06676 1.73349,2.28091 4.32731,3.89131 7.14047,4.43324 2.63602,0.50781 5.3501,0.10224 8.01417,-0.22826 2.66407,-0.3305 5.42491,-0.57681 7.98583,0.22826 2.93239,0.92185 5.38325,3.26048 6.44145,6.14647 1.0582,2.88599 0.69999,6.25461 -0.94145,8.85353"
       id="path3054"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />
    <path
       d="m 140.49177,32.54925 2.75,-4 18.25,0.25 2.5,4.75"
       id="path3058"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />
    <path
       d="m 182.87223,74.26676 c 0.8128,-0.92056 1.35758,-2.07504 1.55139,-3.28768 0.19381,-1.21265 0.036,-2.47942 -0.44937,-3.60746 -0.65947,-1.53263 -1.87987,-2.75437 -3.18035,-3.79965 -1.30047,-1.04528 -2.7108,-1.95697 -3.93207,-3.09379 -2.48988,-2.31769 -4.10196,-5.55673 -4.44975,-8.94055"
       id="path3062"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />
    <path
       d="m 198.78214,15.93046 c -3.30196,0.86526 -6.37571,2.58656 -8.83884,4.94974 -2.78049,2.66766 -4.77019,6.14965 -5.65685,9.8995"
       id="path3066"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />
    <path
       d="m 201.43379,51.10902 c 1.2886,2.18663 3.22627,3.98529 5.5026,5.10787 2.27633,1.12257 4.88287,1.56489 7.4021,1.25609 3.12647,-0.38323 6.00836,-1.87484 8.65931,-3.57606 2.65095,-1.70121 5.15852,-3.64161 7.9577,-5.086 3.17328,-1.63743 6.68906,-2.6073 10.25304,-2.82843"
       id="path3070"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />
    <path
       d="m 170.74177,53.29925 0.75,-4 4,3"
       id="path3074"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />
    <path
       d="m 183.11677,28.54925 -0.125,3.625 4,-1.5625"
       id="path3078"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />
    <path
       d="m 199.48924,52.70001 1.23744,-4.24264 3.71231,3.0052"
       id="path3082"
       style="fill:none;stroke:#000000;stroke-width:1.3px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke:purple" />


<text x=160 y=90 style="font-family:indie flower;fill:purple">
do a rightmost derivation
</text>

<text x=245 y=50 style="font-family:Indie Flower;fill:purple">
One token of look-ahead
</text>

<text x=205 y=20 style="font-family:Indie Flower;fill:purple">
scan the input left-to-right
</text>

<text x=0 y=-40 style="font-family:Indie Flower;fill:purple">
<tspan>"Look-ahead". This has nothing to do with the number of tokens the</tspan>
<tspan y=-24 x=0>parser can look at before it chooses what to do -- it is a technical term</tspan>
<tspan y=-8 x=0>that only means something when you study how LR parsers work </tspan>
</text>

</svg>
LALR(1) grammars are:
<UL>
<!--
  <LI> More general than LL(1) grammars (every LL(1) grammar is also LALR(1)
       but not vice versa).
-->
  <LI> The class of grammars accepted by the parser generators yacc, bison,
       and Java Cup.
  <LI> Parsed by bottom-up parsers (they construct the derivation tree by going
       from terminals up to nonterminals, then attaching several nonterminals
       together to form a new nonterminal, etc, ending with just the start
       nonterminal).
  <LI> Harder to understand than LL(1) parsers
       (i.e., it is hard to understand how the LALR(1)
       parser works, and what makes a grammar LALR(1)).
</UL>
We will learn about three parsing techniques:
<OL class=style1>
  <LI> A general, bottom-up parsing algorithm called the <em>CYK algorithm</em>.
  <LI> A technique for parsing LL(1) grammars top-down,
       called <em>predictive parsing</em>.
  <LI> A technique for bottom-up parsing that works for LALR(1)
       grammars, but since that is rather complicated, we will study a
       version of the algorithm that only works for a subset of the LALR(1)
       grammars called <em>SLR(1)</em> grammars.
</OL>

<P>
<a name=overview></a>
<span class=subhead>Overview</span>
<P>
Given a CFG and a string (a sequence of tokens), the goal of parsing
is to determine whether the string is in the language of the CFG.
The answer is yes iff the string can be <em>derived</em> from the CFG's
start nonterminal.
Equivalently, the answer is yes iff we can build a parse tree for
the string, with the CFG's start nonterminal at the root.
<P>
There are a number of algorithms that can answer this question
for <em>any</em> CFG, even ambiguous CFGs.
Their worst-case running time is O(N<sup>3</sup>), where N is
the length of the input (the number of tokens in the input string).
Some algorithms have better running times for non-ambiguous CFGs.
For example, there is an algorithm called Earley's algorithm that
has worst-case runtime O(N<sup>2</sup>) for non-ambiguous grammars.
However, many of these algorithms are rather complicated, so we
will look at one that is not so complicated, but always has
O(N<sup>3</sup>) runtime.
This algorithm is called the <em>CYK</em> algorithm, for its
inventors: Cocke, Younger, and Kasami.
<P>
<a name=CYK></a>
<span class=subhead>The CYK Algorithm</span>
<P>
The CYK algorithm can be used to parse any CFG (i.e., to determine,
for any input, whether that input is in the language of a given CFG).
It essentially builds all of the possible parse trees for all (consecutive)
fragments of the input, bottom-up.
It accepts the input iff it is able to build a complete parse tree:
one with the CFG's start nonterminal at the root, and the entire
input at the leaves.
<span class=subsubhead>Chomsky Normal Form</span>
<P>
The CYK algorithm requires that the CFG be in CNF: <em>Chomsky normal form</em>.
This means that the CFG rules must all be in one of the following forms:
<OL class=style1>
  <LI> $X$ $\longrightarrow$ <b>t</b>
  <LI> $X$ $\longrightarrow$ $A$ $B$
</OL>
where <b>t</b> is a terminal symbol, and $A$ and $B$
are nonterminal symbols.
Also, grammar rules of the form
<p>
$X$ $\longrightarrow$ $\varepsilon$
</p>
<p>
are not allowed, unless the left nonterminal is the start nonterminal.
If there is a rule
</p>
<p>
$S$ $\longrightarrow$ $\varepsilon$
</p>
(where $S$ is the start nonterminal), then $S$ cannot occur on the right-hand
side of any rule.
<P>
<span class=subsubhead>Conversion to CNF</span>
<p>
Fortunately, it is easy to convert any CFG to CNF.
The conversion involves the following steps:
<OL class=style1>
  <LI> Eliminate $\varepsilon$ rules
  <LI> Eliminate unit rules (rules with exactly one nonterminal on the right)
  <LI> Fix remaining rules so that all rules have either a single terminal
       or exactly two nonterminals on the right.
</OL>

<P>
<a name=CNF></a>
<span class=subsubhead>Example: Conversion to CNF</span>
<P>
We'll start with a very simple grammar for function calls (the function name,
followed by a list of zero or more comma-separated arguments, where each
argument can only be an identifier).

<table style="width:auto">
<!--
<tr>
<td colspan=3 style="align:center; border-bottom:1px solid"><b>CFG Production</b></td>
<td style="padding-left:60px"></td>
<td style="align:center; border-bottom:1px solid"><b>Translation rules</b></td>
</tr>
-->
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> $A$ <b>)</b></td>
</tr>

<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$\varepsilon$</td>
</tr>

<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$N$</td>
</tr>

<tr>
<td>&nbsp</td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>,</b> $N$</td>
</tr>
</table>
<P>
<H3>Step 1 (eliminate $\varepsilon$ rules):</H3>
<P>
This CFG has one rule with epsilon on the right-hand side (the second rule).
That rule has $A$ on the left-hand side.
We'll remove the $\varepsilon$-rule
by copying the rule that has $A$ on the right
(the first rule in the CFG above),
and replacing that $A$ with nothing ($\varepsilon$):

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> <b>)</b></td>
</tr>
</table>

<p>
Now we have this CFG:
</p>

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> $A$ <b>)</b></td>
</tr>

<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> <b>)</b></td>
</tr>

<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$N$</td>
</tr>

<tr>
<td>&nbsp</td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>,</b> $N$</td>
</tr>
</table>

<P>
Note that if $A$ occurred more than once on the
right of some rule, we would have to make as many copies
as needed to get all combinations of $A$ being
there and being replaced by $\varepsilon$.
For example, if we had this rule:


<table style="width:auto">
<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$A$ <b>x</b> $A$ <b>y</b> $A$</td>
</tr>
</table>

<p>
we'd have to add all of the following new rules, as well
as keeping the original rule:
</p>

<table style="width:auto">
<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$A$ <b>x</b> $A$ <b>y</b></td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$A$ <b>x</b> <b>y</b> $A$</td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$A$ <b>x</b> <b>y</b></td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>x</b> $A$ <b>y</b> $A$</td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>x</b> $A$ <b>y</b></td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>x</b> <b>y</b> $A$</td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>x</b> <b>y</b></td>
</tr>

</table>

<P>
Furthermore, if we have a rule
</p>

<table style="width:auto">
<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$\varepsilon$</td>
</tr>
</table>

<p>
where <tt>S</tt> is the start nonterminal, and
there is a grammar rule with <tt>S</tt> on the
right, then we need to  do the following:
<UL>
  <LI> Add a new start nonterminal S'
  <LI> And new rules
		<table style="width:auto">
		<tr>
		<td align="right">S'</td>
		<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
		<td align="left">$S$</td>
		</tr>

		<tr>
		<td align="right">S'</td>
		<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
		<td align="left">$\varepsilon$</td>
		</tr>

		</table>
  <LI> Remove the rule

		<table style="width:auto">
		<tr>
		<td align="right">$S$</td>
		<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
		<td align="left">$\varepsilon$</td>
		</tr>
		</table>
</UL>

<H3>Step 2 (eliminate unit rules):</H3>
<P>
Our current CFG looks like this:
<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> $A$ <b>)</b></td>
</tr>

<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> <b>)</b></td>
</tr>

<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$N$</td>
</tr>

<tr>
<td>&nbsp</td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>,</b> $N$</td>
</tr>
</table>

<p>
It includes one <em>unit rule</em>, a rule with exactly 1 nonterminal
(and no terminals) on the right:
</p>

<table style="width:auto">
<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$N$</td>
</tr>
</table>
<p>
Because there are no other rules with $A$ on the left,
we can remove the unit rule by replacing every occurrence of
$A$ on the right side of some rule with $N$.
(If there were other rules with $A$ on the left,
we would instead make copies of the rules with $A$ on the right,
and replace $A$ with $N$ in the copies).
Here's our CFG after removing the unit rule:

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> $N$ <b>)</b></td>
</tr>

<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> <b>)</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>,</b> $N$</td>
</tr>
</table>

<H3>Step 3 (fix remaining rules):</H3>
<P>
The last step in conversion to CNF is to fix the remaining rules
so that each one has either a single terminal, or exactly two
nonterminals on the right.
<H4>Handle rules with terminals on the right</H4>
First, we find the rules that have a terminal plus some other
symbols on the right:


<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> $N$ <b>)</b></td>
</tr>

<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> <b>)</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>,</b> $N$</td>
</tr>
</table>

<p>
For each such terminal <b>t</b>, we add a new rule
<table style="width:auto">
<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>t</b></td>
</tr>
</table>

Then we replace <b>t</b> with $X$ in the original rules.
Here's the result (including the one rule that was already in the
correct form):

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $L$ $N$ $R$</td>
</tr>

<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $L$ $R$</td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $C$ $N$</td>
</tr>

<tr><td>&nbsp</td></tr>

<tr>
<td align="right">$I$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$L$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>(</b></td>
</tr>

<tr>
<td align="right">$R$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>)</b></td>
</tr>

<tr>
<td align="right">$C$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>,</b></td>
</tr>
</table>

<H4>Handle rules with more than 2 nonterminals on the right</H4>
<p>
Now, for every right-hand side that has more than two nonterminals, we
replace all but the first nonterminal on the right with a <em>new</em>
nonterminal, and we add a new rule for the new nonterminal.
For example, we handle the rule
</p>

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $L$ $N$ $R$</td>
</tr>
</table>

<p>
as follows:
</p>

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $W$</td>
</tr>

<tr>
<td align="right">$W$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$L$ $N$ $R$</td>
</tr>
</table>

<p>
Then we handle the new rule (with <tt>W</tt> on the left)
as follows:
</p>

<table style="width:auto">
<tr>
<td align="right">$W$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$L$ $X$</td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$N$ $R$</td>
</tr>
</table>

<p>
If we take care of the other two "problem" rules:
</p>

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $L$ $R$</td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $C$ $N$</td>
</tr>
</table>

<p>
We finish with the following CFG (in CNF):
</p>

<table style="width:auto">
<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $W$</td>
</tr>

<tr>
<td align="right">$F$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $Y$</td>
</tr>

<tr>
<td align="right">$W$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$L$ $X$</td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$N$ $R$</td>
</tr>

<tr>
<td align="right">$Y$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$L$ $R$</td>
</tr>

<tr><td>&nbsp</td></tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$N$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$I$ $Z$</td>
</tr>

<tr>
<td align="right">$Z$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$C$ $N$</td>
</tr>

<tr><td>&nbsp</td></tr>

<tr>
<td align="right">$I$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b></td>
</tr>

<tr>
<td align="right">$L$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>(</b></td>
</tr>

<tr>
<td align="right">$R$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>)</b></td>
</tr>

<tr>
<td align="right">$C$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>,</b></td>
</tr>

</table>

<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
The CFG below is for a grammar of very simple statements. The terminal symbols are
<b>id</b>, <b>=</b>, <b>(</b>, <b>)</b>, <b>++</b>, and <b>read</b>. Convert this CFG to CNF.

<table style="width:auto">
<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>=</b> <b>id</b></td>
</tr>

<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>(</b> <b>)</b></td>
</tr>

<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>id</b> <b>++</b></td>
</tr>

<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>read</b> <b>(</b> <b>id</b> <b>)</b></td>
</tr>

<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$S$ $S$</td>
</tr>

</table>

<P>
<p><a href="SOLUTIONS/GENERAL-PARSING-ANSWERS.html#ans1">solution</a></p>
<hr>

<a name=input href=#input class=example>Parsing an Input</a>
<P>
Now that we have converted our CFG to Chomsky Normal form, we can
use it to try to parse an input.
Let's use the input <tt>f(x,y)</tt> as an example.
The actual sequence of tokens for that input would be:
<b>id ( id , id )</b>

<P>
To parse an input of length <i>N</i>, we use an <i>N</i>-by-<i>N</i> grid.
First, we fill in the squares along the diagonal.
The <i>k</i><sup>th</sup> square contains the nonterminal(s) $X$
such that there is a CFG rule
</p>
<p>
$X$ $\longrightarrow$ <b>t</b>
</p>
<p>
and <tt>t</tt> is the k<sup>th</sup> token.
Here's the 6-by-6 grid for our example, with the diagonal
filled in (and with the rows and columns numbered):
</p>
<P>

<svg height=250 width=250 align=center>

<g transform="translate(0,25)">
		<text x=5 y=20 text-anchor="middle">1</text>
		<text x=5 y=50 text-anchor="middle">2</text>
		<text x=5 y=80 text-anchor="middle">3</text>
		<text x=5 y=110 text-anchor="middle">4</text>
		<text x=5 y=140 text-anchor="middle">5</text>
		<text x=5 y=170 text-anchor="middle">6</text>
</g>

<g transform="translate(20,0)">
		<text x=15 y=20 text-anchor="middle">1</text>
		<text x=45 y=20 text-anchor="middle">2</text>
		<text x=75 y=20 text-anchor="middle">3</text>
		<text x=105 y=20 text-anchor="middle">4</text>
		<text x=135 y=20 text-anchor="middle">5</text>
		<text x=165 y=20 text-anchor="middle">6</text>
</g>


<g transform="translate(20,25)">

		<text x=15 y=20 text-anchor="middle" style="font-size:16">I,N</text>
		<text x=45 y=50 text-anchor="middle">L</text>
		<text x=75 y=80 text-anchor="middle">I,N</text>
		<text x=105 y=110 text-anchor="middle">C</text>
		<text x=135 y=140 text-anchor="middle">I,N</text>
		<text x=165 y=170 text-anchor="middle">R</text>

<!--
		<text x=135 y=110 text-anchor="middle">Z</text>
		<text x=135 y=80 text-anchor="middle">N</text>
		<text x=165 y=80 text-anchor="middle">X</text>
		<text x=165 y=50 text-anchor="middle">W</text>
		<text x=165 y=20 text-anchor="middle">F</text>
-->


    <rect width="30" height="30" ry="0" x="0" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" ry="0" x="0" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
		</g>
</svg>
<P>
Now we fill in each successive diagonal above the one that's already
filled in.
When filling in a square, we consider what's already
in the squares to the left and below it.
For example, to fill in the square in position 2,1, we look at the
contents of the square to its left ($I$,$N$) and below it
($L$).
If there were a grammar rule with "$I$ $L$" or with
"$N$ $L$" on the right-hand side, we would put the left-side nonterminal
into the square in position 2,1.
However, for our example, there are no such grammar rules, so
square 2,1 stays empty.
The first new square that we can fill in is in position 5,4.
That square has $C$ to its left, and $I$, $N$ below it.
There is a grammar rule $Z$ $\longrightarrow$ $C$ $N$, so we put
a $Z$ in square 5,4.
If there had also been a grammar rule $H$ $\longrightarrow$ $C$ $I$,
we would also have put an $H$ in square 5,4.
Note that we also fill in square 5,5 due to the rule $X$ $\longrightarrow$ $N$ $R$.
Here is the grid so far:
</P>

<svg height=250 width=250 align=center>

<g transform="translate(0,25)">
		<text x=5 y=20 text-anchor="middle">1</text>
		<text x=5 y=50 text-anchor="middle">2</text>
		<text x=5 y=80 text-anchor="middle">3</text>
		<text x=5 y=110 text-anchor="middle">4</text>
		<text x=5 y=140 text-anchor="middle">5</text>
		<text x=5 y=170 text-anchor="middle">6</text>
</g>

<g transform="translate(20,0)">
		<text x=15 y=20 text-anchor="middle">1</text>
		<text x=45 y=20 text-anchor="middle">2</text>
		<text x=75 y=20 text-anchor="middle">3</text>
		<text x=105 y=20 text-anchor="middle">4</text>
		<text x=135 y=20 text-anchor="middle">5</text>
		<text x=165 y=20 text-anchor="middle">6</text>
</g>


<g transform="translate(20,25)">

		<text x=15 y=20 text-anchor="middle">I,N</text>
		<text x=45 y=50 text-anchor="middle">L</text>
		<text x=75 y=80 text-anchor="middle">I,N</text>
		<text x=105 y=110 text-anchor="middle">C</text>
		<text x=135 y=140 text-anchor="middle">I,N</text>
		<text x=165 y=170 text-anchor="middle">R</text>
		<text x=165 y=140 text-anchor="middle">X</text>

		<text x=135 y=110 text-anchor="middle">Z</text>
<!--
		<text x=135 y=80 text-anchor="middle">N</text>
		<text x=165 y=80 text-anchor="middle">X</text>
		<text x=165 y=50 text-anchor="middle">W</text>
		<text x=165 y=20 text-anchor="middle">F</text>
-->


    <rect width="30" height="30" ry="0" x="0" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" ry="0" x="0" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
		</g>
</svg>
<P>

<P>
The squares in the next diagonal each have two squares to their
left and two squares below.
We look at pairs of squares: first the square all the way to the
left and the square immediately below, then the square immediately
to the left and the one two squares down.
If either square in the current pair is empty, we go on to the
next pair.
Each time we look at a pair of non-empty squares, we are looking for a 
nonterminal $A$ in the square to the left and a nonterminal $B$
in the square that is below, such that there is a grammar rule with
$A$ $B$ on the right.
If there is such a grammar rule, we fill in the current square with
the left-side nonterminal.
For example, when trying to fill in the square in position 3,5,
we see $I$, $N$ in position 3,3, and $Z$ in position
4,5.
There is a grammar rule $N$ $\longrightarrow$ $I$ $Z$, so we put
an $N$ is position 3,5.
Here is the result of filling in the whole grid:
</p>

<svg height=250 width=250 align=center>

<g transform="translate(0,25)">
		<text x=5 y=20 text-anchor="middle">1</text>
		<text x=5 y=50 text-anchor="middle">2</text>
		<text x=5 y=80 text-anchor="middle">3</text>
		<text x=5 y=110 text-anchor="middle">4</text>
		<text x=5 y=140 text-anchor="middle">5</text>
		<text x=5 y=170 text-anchor="middle">6</text>
</g>

<g transform="translate(20,0)">
		<text x=15 y=20 text-anchor="middle">1</text>
		<text x=45 y=20 text-anchor="middle">2</text>
		<text x=75 y=20 text-anchor="middle">3</text>
		<text x=105 y=20 text-anchor="middle">4</text>
		<text x=135 y=20 text-anchor="middle">5</text>
		<text x=165 y=20 text-anchor="middle">6</text>
</g>


<g transform="translate(20,25)">

		<text x=15 y=20 text-anchor="middle">I,N</text>
		<text x=45 y=50 text-anchor="middle">L</text>
		<text x=75 y=80 text-anchor="middle">I,N</text>
		<text x=105 y=110 text-anchor="middle">C</text>
		<text x=135 y=140 text-anchor="middle">I,N</text>
		<text x=165 y=170 text-anchor="middle">R</text>
		<text x=165 y=140 text-anchor="middle">X</text>

		<text x=135 y=110 text-anchor="middle">Z</text>
		<text x=135 y=80 text-anchor="middle">N</text>
		<text x=165 y=80 text-anchor="middle">X</text>
		<text x=165 y=50 text-anchor="middle">W</text>
		<text x=165 y=20 text-anchor="middle">F</text>


    <rect width="30" height="30" ry="0" x="0" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="1"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" ry="0" x="0" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="31"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="61"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="91"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="121"
       style="fill:none;stroke:#000000;stroke-width:1" />

    <rect width="30" height="30" x="0" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="30" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="60" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="90" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="120" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
    <rect width="30" height="30" x="150" y="151"
       style="fill:none;stroke:#000000;stroke-width:1" />
		</g>
</svg>

<P>
To understand this process,
remember that the CYK algorithm works by building up parse trees
for longer and longer fragments of the input.
Each box in the upper-right part of the grid represents the root of
one or more parse trees.
The nonterminal(s) in a square is/are the root nonterminal(s) of 
those trees.
The children of the root are the nonterminals in the pairs of
squares, one to the left, and one below, that caused the nonterminal
to be filled in.
The five nonterminals in the upper-right part of our grid
represent these five parse trees:
</p>
<!--
<P>
<IMG SRC="diagrams/CYK4.gif">
<P>
-->

<!--<div style="border:solid 1px; width:auto; display:inline-block">-->
<div style="display:inline-block">
<svg height=300 width=180 style="">
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-1.4879587,-7.1803046)">
		 <text x=118 y=180>Z</text>

		 <text x=94 y=220>C</text>
		 <text x=137 y=220>N</text>

		 <text x=96 y=255>,</text>
		 <text x=137 y=260>id</text>
				 
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 115.52361,186.89838 -12.86168,18.86676"
       id="path5337"
       inkscape:path-effect="#path-effect5357"
       inkscape:original-d="m 115.52361,186.89838 -12.86168,18.86676"
       sodipodi:nodetypes="cc" />


        <path
       inkscape:original-d="m 98.42046,226.69533 0,15.65737"
       inkscape:path-effect="#path-effect5379"
       id="path5369"
       d="m 98.42046,226.69533 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />

        <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 143.52153,226.45803 0,15.65737"
       id="path5389"
       inkscape:path-effect="#path-effect5399"
       inkscape:original-d="m 143.52153,226.45803 0,15.65737" />

        <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 128.42388,186.89838 12.86168,18.86676"
       inkscape:path-effect="#path-effect5403"
       id="path5401"
       d="m 128.42388,186.89838 12.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
</svg>
</div>
<div style="display:inline-block">
<svg height=300 width=180>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path4119"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path4113"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)" />
    </marker>
    <marker
       inkscape:stockid="Arrow1Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         id="path4095"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt;marker-start:none"
         transform="matrix(-0.8,0,0,-0.8,-10,0)" />
    </marker>
    <inkscape:path-effect
       effect="spiro"
       id="path-effect3315"
       is_visible="true" />
    <inkscape:perspective
       sodipodi:type="inkscape:persp3d"
       inkscape:vp_x="0 : 526.18109 : 1"
       inkscape:vp_y="0 : 1000 : 0"
       inkscape:vp_z="744.09448 : 526.18109 : 1"
       inkscape:persp3d-origin="372.04724 : 350.78739 : 1"
       id="perspective10" />
  </defs>

		 <text x=118 y=180>Z</text>

		 <text x=94 y=220>C</text>
		 <text x=137 y=220>N</text>

		 <text x=96 y=255>,</text>
		 <text x=137 y=260>id</text>

		 <text x=95 y=144>N</text>

		 <text x=69 y=180>I</text>
		 <text x=118 y=180>Z</text>

		 <text x=65 y=218>id</text>


  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1"
     inkscape:cx="91.409309"
     inkscape:cy="85.117443"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     inkscape:window-width="615"
     inkscape:window-height="735"
     inkscape:window-x="577"
     inkscape:window-y="614"
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
     transform="translate(-1.4879587,0.1803046)">
    <path
       inkscape:original-d="m 71.590868,184.95803 0,15.65737"
       inkscape:path-effect="#path-effect5327"
       id="path5317"
       d="m 71.590868,184.95803 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 115.52361,186.89838 -12.86168,18.86676"
       id="path5337"
       inkscape:path-effect="#path-effect5357"
       inkscape:original-d="m 115.52361,186.89838 -12.86168,18.86676"
       sodipodi:nodetypes="cc" />
    <path
       inkscape:original-d="m 98.42046,226.69533 0,15.65737"
       inkscape:path-effect="#path-effect5379"
       id="path5369"
       d="m 98.42046,226.69533 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 143.52153,226.45803 0,15.65737"
       id="path5389"
       inkscape:path-effect="#path-effect5399"
       inkscape:original-d="m 143.52153,226.45803 0,15.65737" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 128.42388,186.89838 12.86168,18.86676"
       inkscape:path-effect="#path-effect5403"
       id="path5401"
       d="m 128.42388,186.89838 12.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="M 92.9066,147.3004 76.155833,166.16716"
       inkscape:path-effect="#path-effect5577"
       id="path5573"
       d="M 92.9066,147.3004 76.155833,166.16716"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 105.78373,147.3004 16.75077,18.86676"
       id="path5592"
       inkscape:path-effect="#path-effect5594"
       inkscape:original-d="m 105.78373,147.3004 16.75077,18.86676"
       sodipodi:nodetypes="cc" />
  </g>
</svg>
</div>
<div style="display:inline-block">
<svg height=300 width=180>
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path4119"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path4113"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)" />
    </marker>
    <marker
       inkscape:stockid="Arrow1Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         id="path4095"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt;marker-start:none"
         transform="matrix(-0.8,0,0,-0.8,-10,0)" />
    </marker>
  </defs>







  <g transform="translate(-1.4879587,-7.1803046)">
		 <text x=128 y=104>X</text>

		 <text x=95 y=144>N</text>
		 <text x=155 y=144>R</text>

		 <text x=69 y=180>I</text>
		 <text x=118 y=180>Z</text>
		 <text x=158 y=184>)</text>

		 <text x=65 y=218>id</text>
		 <text x=94 y=220>C</text>
		 <text x=137 y=220>N</text>

		 <text x=96 y=255>,</text>
		 <text x=137 y=260>id</text>


    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 161.40098,150.45803 0,15.65737"
       id="path5281"
       inkscape:path-effect="#path-effect5291"
       inkscape:original-d="m 161.40098,150.45803 0,15.65737" />
    <path
       inkscape:original-d="m 71.590868,184.95803 0,15.65737"
       inkscape:path-effect="#path-effect5327"
       id="path5317"
       d="m 71.590868,184.95803 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 115.52361,186.89838 -12.86168,18.86676"
       id="path5337"
       inkscape:path-effect="#path-effect5357"
       inkscape:original-d="m 115.52361,186.89838 -12.86168,18.86676"
       sodipodi:nodetypes="cc" />
    <path
       inkscape:original-d="m 98.42046,226.69533 0,15.65737"
       inkscape:path-effect="#path-effect5379"
       id="path5369"
       d="m 98.42046,226.69533 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 143.52153,226.45803 0,15.65737"
       id="path5389"
       inkscape:path-effect="#path-effect5399"
       inkscape:original-d="m 143.52153,226.45803 0,15.65737" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 128.42388,186.89838 12.86168,18.86676"
       inkscape:path-effect="#path-effect5403"
       id="path5401"
       d="m 128.42388,186.89838 12.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 124.02361,109.39838 -17.86168,18.86676"
       inkscape:path-effect="#path-effect5420"
       id="path5416"
       d="m 124.02361,109.39838 -17.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 142.92388,109.39838 17.36168,18.86676"
       id="path5418"
       inkscape:path-effect="#path-effect5422"
       inkscape:original-d="m 142.92388,109.39838 c 4.28723,6.28892 92.26494,-3.26026 17.36168,18.86676"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="M 92.9066,147.3004 76.155833,166.16716"
       inkscape:path-effect="#path-effect5577"
       id="path5573"
       d="M 92.9066,147.3004 76.155833,166.16716"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 105.78373,147.3004 16.75077,18.86676"
       id="path5592"
       inkscape:path-effect="#path-effect5594"
       inkscape:original-d="m 105.78373,147.3004 16.75077,18.86676"
       sodipodi:nodetypes="cc" />
  </g>
</svg>
</div>
<div style="display:inline-block">
<svg height=300 width=180>
  <defs
     id="defs4">
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5594"
       is_visible="true" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5579"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5577"
       effect="spiro" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5434"
       effect="spiro" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5430"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5428"
       is_visible="true" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5422"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5420"
       effect="spiro" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5403"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5399"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5379"
       effect="spiro" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5359"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5357"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5327"
       effect="spiro" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5307"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5305"
       is_visible="true" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5291"
       is_visible="true" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5279"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5277"
       effect="spiro" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5247"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5245"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5219"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5208"
       is_visible="true" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5205"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5202"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect4941"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect4937"
       effect="spiro" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect4933"
       effect="spiro" />
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path4119"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path4113"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)" />
    </marker>
    <marker
       inkscape:stockid="Arrow1Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         id="path4095"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt;marker-start:none"
         transform="matrix(-0.8,0,0,-0.8,-10,0)" />
    </marker>
    <inkscape:path-effect
       effect="spiro"
       id="path-effect3315"
       is_visible="true" />
    <inkscape:perspective
       sodipodi:type="inkscape:persp3d"
       inkscape:vp_x="0 : 526.18109 : 1"
       inkscape:vp_y="0 : 1000 : 0"
       inkscape:vp_z="744.09448 : 526.18109 : 1"
       inkscape:persp3d-origin="372.04724 : 350.78739 : 1"
       id="perspective10" />
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2"
     inkscape:cx="141.63454"
     inkscape:cy="92.181753"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     inkscape:window-width="615"
     inkscape:window-height="735"
     inkscape:window-x="577"
     inkscape:window-y="614"
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
     transform="translate(-1.4879587,-7.1803046)">


		 <text x=55 y=104>L</text>

				 <text x=87 y=64>W</text>

		 <text x=128 y=104>X</text>

		 <text x=95 y=144>N</text>
		 <text x=155 y=144>R</text>
		 <text x=55 y=144>(</text>

		 <text x=69 y=180>I</text>
		 <text x=118 y=180>Z</text>
		 <text x=158 y=184>)</text>

		 <text x=65 y=218>id</text>
		 <text x=94 y=220>C</text>
		 <text x=137 y=220>N</text>

		 <text x=96 y=255>,</text>
		 <text x=137 y=260>id</text>


    <path
       inkscape:original-d="m 58.990828,108.45803 0,15.65737"
       inkscape:path-effect="#path-effect5202"
       id="path5017"
       d="m 58.990828,108.45803 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 161.40098,150.45803 0,15.65737"
       id="path5281"
       inkscape:path-effect="#path-effect5291"
       inkscape:original-d="m 161.40098,150.45803 0,15.65737" />
    <path
       inkscape:original-d="m 71.590868,184.95803 0,15.65737"
       inkscape:path-effect="#path-effect5327"
       id="path5317"
       d="m 71.590868,184.95803 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 115.52361,186.89838 -12.86168,18.86676"
       id="path5337"
       inkscape:path-effect="#path-effect5357"
       inkscape:original-d="m 115.52361,186.89838 -12.86168,18.86676"
       sodipodi:nodetypes="cc" />
    <path
       inkscape:original-d="m 98.42046,226.69533 0,15.65737"
       inkscape:path-effect="#path-effect5379"
       id="path5369"
       d="m 98.42046,226.69533 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 143.52153,226.45803 0,15.65737"
       id="path5389"
       inkscape:path-effect="#path-effect5399"
       inkscape:original-d="m 143.52153,226.45803 0,15.65737" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 128.42388,186.89838 12.86168,18.86676"
       inkscape:path-effect="#path-effect5403"
       id="path5401"
       d="m 128.42388,186.89838 12.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 124.02361,109.39838 -17.86168,18.86676"
       inkscape:path-effect="#path-effect5420"
       id="path5416"
       d="m 124.02361,109.39838 -17.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 142.92388,109.39838 17.36168,18.86676"
       id="path5418"
       inkscape:path-effect="#path-effect5422"
       inkscape:original-d="m 142.92388,109.39838 c 4.28723,6.28892 92.26494,-3.26026 17.36168,18.86676"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 83.718518,67.76832 65.856838,86.63508"
       id="path5424"
       inkscape:path-effect="#path-effect5428"
       inkscape:original-d="M 83.718518,67.76832 65.856838,86.63508"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 106.61879,67.76832 c 4.28723,6.28892 92.26494,-3.260258 17.36168,18.86676"
       inkscape:path-effect="#path-effect5430"
       id="path5426"
       d="m 106.61879,67.76832 17.36168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="M 92.9066,147.3004 76.155833,166.16716"
       inkscape:path-effect="#path-effect5577"
       id="path5573"
       d="M 92.9066,147.3004 76.155833,166.16716"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 105.78373,147.3004 16.75077,18.86676"
       id="path5592"
       inkscape:path-effect="#path-effect5594"
       inkscape:original-d="m 105.78373,147.3004 16.75077,18.86676"
       sodipodi:nodetypes="cc" />
  </g>
</svg>
</div>
<div style="display:inline-block">
<svg height=300 width=180>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-1.4879587,-7.1803046)">
				 <text x=44 y=23>F</text>
				 <text x=6 y=64>I</text>
				 <text x=87 y=64>W</text>
		 <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 56.318408,22.231523 29.49646,28.587323"
       id="path5013"
       inkscape:path-effect="#path-effect5208"
       inkscape:original-d="m 56.318408,22.231523 29.49646,28.587323"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 8.090868,72.011736 0,15.65737"
       id="path5015"
       inkscape:path-effect="#path-effect5205"
       inkscape:original-d="m 8.090868,72.011736 0,15.65737" />
    <flowRoot
       transform="translate(-154.30501,-169.56344)"
       xml:space="preserve"
       id="flowRoot5027"
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"><flowRegion
         id="flowRegion5029"><rect
           id="rect5031"
           width="120"
           height="111.42857"
           x="154.28572"
           y="255.21933"
           style="font-size:18px" /></flowRegion><flowPara
         style="font-weight:bold"
         id="flowPara5033">id</flowPara></flowRoot>    
		 <text x=4 y=104>id</text>
		 <text x=55 y=104>L</text>
		 <text x=128 y=104>X</text>

		 <text x=95 y=144>N</text>
		 <text x=155 y=144>R</text>
		 <text x=55 y=144>(</text>

		 <text x=69 y=180>I</text>
		 <text x=118 y=180>Z</text>
		 <text x=158 y=184>)</text>

		 <text x=65 y=218>id</text>
		 <text x=94 y=220>C</text>
		 <text x=137 y=220>N</text>

		 <text x=96 y=255>,</text>
		 <text x=137 y=260>id</text>
				 
				 
		 <path
       inkscape:original-d="m 58.990828,108.45803 0,15.65737"
       inkscape:path-effect="#path-effect5202"
       id="path5017"
       d="m 58.990828,108.45803 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
		 <g
       id="g5581"
       transform="translate(-6,3)">
     <path
         inkscape:original-d="m 167.40098,147.45803 0,15.65737"
         inkscape:path-effect="#path-effect5291"
         id="path5281"
         d="m 167.40098,147.45803 0,15.65737"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
         sodipodi:nodetypes="cc" />
    </g>
    <flowRoot
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"
       id="flowRoot5309"
       xml:space="preserve"
       transform="translate(-84.982532,-92.617155)"><flowRegion
         id="flowRegion5311"><rect
           style="font-size:18px"
           y="255.21933"
           x="154.28572"
           height="111.42857"
           width="120"
           id="rect5313" /></flowRegion><flowPara
         id="flowPara5315"
         style="font-style:italic">I</flowPara></flowRoot>    <path
       inkscape:original-d="m 71.590868,184.95803 0,15.65737"
       inkscape:path-effect="#path-effect5327"
       id="path5317"
       d="m 71.590868,184.95803 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <flowRoot
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"
       id="flowRoot5319"
       xml:space="preserve"
       transform="translate(-90.805006,-56.617155)"><flowRegion
         id="flowRegion5321"><rect
           style="font-size:18px"
           y="255.21933"
           x="154.28572"
           height="111.42857"
           width="120"
           id="rect5323" /></flowRegion><flowPara
         id="flowPara5325"
         style="font-weight:bold">id</flowPara></flowRoot>    <flowRoot
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"
       id="flowRoot5329"
       xml:space="preserve"
       transform="translate(-36.513242,-88.617155)"><flowRegion
         id="flowRegion5331"><rect
           style="font-size:18px"
           y="255.21933"
           x="154.28572"
           height="111.42857"
           width="120"
           id="rect5333" /></flowRegion><flowPara
         style="font-style:italic"
         id="flowPara5335">Z</flowPara></flowRoot>    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 115.52361,186.89838 -12.86168,18.86676"
       id="path5337"
       inkscape:path-effect="#path-effect5357"
       inkscape:original-d="m 115.52361,186.89838 -12.86168,18.86676"
       sodipodi:nodetypes="cc" />
    <flowRoot
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"
       id="flowRoot5019"
       xml:space="preserve"
       transform="translate(-58.35704,-21.405729)"><flowRegion
         id="flowRegion5021"><rect
           style="font-size:18px"
           y="255.21933"
           x="154.28572"
           height="111.42857"
           width="120"
           id="rect5023" /></flowRegion><flowPara
         id="flowPara5025"
         style="font-weight:bold">,</flowPara></flowRoot>    <flowRoot
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"
       id="flowRoot5361"
       xml:space="preserve"
       transform="translate(-62.48253,-50.879851)"><flowRegion
         id="flowRegion5363"><rect
           style="font-size:18px"
           y="255.21933"
           x="154.28572"
           height="111.42857"
           width="120"
           id="rect5365" /></flowRegion><flowPara
         id="flowPara5367"
         style="font-style:italic">C</flowPara></flowRoot>    <path
       inkscape:original-d="m 98.42046,226.69533 0,15.65737"
       inkscape:path-effect="#path-effect5379"
       id="path5369"
       d="m 98.42046,226.69533 0,15.65737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <flowRoot
       transform="translate(-17.482532,-51.117155)"
       xml:space="preserve"
       id="flowRoot5381"
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"><flowRegion
         id="flowRegion5383"><rect
           id="rect5385"
           width="120"
           height="111.42857"
           x="154.28572"
           y="255.21933"
           style="font-size:18px" /></flowRegion><flowPara
         style="font-style:italic"
         id="flowPara5387">N</flowPara></flowRoot>    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 143.52153,226.45803 0,15.65737"
       id="path5389"
       inkscape:path-effect="#path-effect5399"
       inkscape:original-d="m 143.52153,226.45803 0,15.65737" />
    <flowRoot
       transform="translate(-20.277452,-15.117155)"
       xml:space="preserve"
       id="flowRoot5391"
       style="font-size:18px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"><flowRegion
         id="flowRegion5393"><rect
           id="rect5395"
           width="120"
           height="111.42857"
           x="154.28572"
           y="255.21933"
           style="font-size:18px" /></flowRegion><flowPara
         style="font-weight:bold"
         id="flowPara5397">id</flowPara></flowRoot>    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 128.42388,186.89838 12.86168,18.86676"
       inkscape:path-effect="#path-effect5403"
       id="path5401"
       d="m 128.42388,186.89838 12.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 124.02361,109.39838 -17.86168,18.86676"
       inkscape:path-effect="#path-effect5420"
       id="path5416"
       d="m 124.02361,109.39838 -17.86168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 142.92388,109.39838 17.36168,18.86676"
       id="path5418"
       inkscape:path-effect="#path-effect5422"
       inkscape:original-d="m 142.92388,109.39838 c 4.28723,6.28892 92.26494,-3.26026 17.36168,18.86676"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 83.718518,67.76832 65.856838,86.63508"
       id="path5424"
       inkscape:path-effect="#path-effect5428"
       inkscape:original-d="M 83.718518,67.76832 65.856838,86.63508"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 106.61879,67.76832 c 4.28723,6.28892 92.26494,-3.260258 17.36168,18.86676"
       inkscape:path-effect="#path-effect5430"
       id="path5426"
       d="m 106.61879,67.76832 17.36168,18.86676"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="M 42.272568,22.231523 12.776108,50.818846"
       inkscape:path-effect="#path-effect5434"
       id="path5432"
       d="M 42.272568,22.231523 12.776108,50.818846"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="M 92.9066,147.3004 76.155833,166.16716"
       inkscape:path-effect="#path-effect5577"
       id="path5573"
       d="M 92.9066,147.3004 76.155833,166.16716"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 105.78373,147.3004 16.75077,18.86676"
       id="path5592"
       inkscape:path-effect="#path-effect5594"
       inkscape:original-d="m 105.78373,147.3004 16.75077,18.86676"
       sodipodi:nodetypes="cc" />
  </g>
</svg>
</div>


<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Use the statement CFG that was converted to CNF
for the "Test Yourself #1" question to parse the
input <tt>x ++ y = z y ++</tt> (i.e.,
build and fill in the grid).

<P>
<p><a href="SOLUTIONS/GENERAL-PARSING-ANSWERS.html#ans2">solution</a></p>
<hr>

<a name=accept></a>
<span class=subhead>Accepting an Input</span>
<P>
The CYK discovers that an input <em>is</em> in the language of
a CFG iff the CFG's start nonterminal appears in the upper-right
corner of the grid.
For our example, <tt>F</tt> is the start nonterminal, and it
does appear in square 1,6, so the example input is in the
language of the CFG.
The rightmost parse tree shown above is the (unique) parse
tree for the example input.

<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
Is the input <tt>x ++ y = z y ++</tt>
in the language of the simple statement grammar?
(Justify your answer.)
<P>
<p><a href="SOLUTIONS/GENERAL-PARSING-ANSWERS.html#ans3">solution</a></p>
<hr>
</body>
</html>
