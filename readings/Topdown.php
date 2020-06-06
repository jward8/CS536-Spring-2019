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
  <LI><A HREF="#ll1">LL(1) Grammars and Predictive Parsers</A>
  <LI><A HREF="#youtry1">Test Yourself #1</A>
  <LI><A HREF="#trans">Grammar Transformations</A>
      <UL>
	  <LI><A HREF="#leftRec">Left Recursion</A>
	  <LI><A HREF="#leftFact">Left Factoring</A>
	  <LI><A HREF="#youtry2">Test Yourself #2</A>
      </UL>
  <LI><A HREF="#firstFollow">FIRST and FOLLOW Sets</A>
      <UL>
          <LI><A HREF="#first">FIRST</A>
          <LI><A HREF="#follow">FOLLOW</A>
      </UL>
  <LI><A HREF="#youtry3">Test Yourself #3</A>
  <LI><A HREF="#tables">How to Build Parse Tables</A>
      <UL>
          <LI><A HREF="#youtry4">Test Yourself #4</A>
      </UL>
  <LI><A HREF="#code">How to Code a Predictive Parser</A>
  <LI><A HREF="#youtry5">Test Yourself #5</A>
</UL>

<P>
<a name=ll1></a>
<span class=subhead>LL(1) Grammars and Predictive Parsers</span>
<P>
LL(1) grammars are parsed by top-down parsers.  They construct the derivation
tree starting with the start nonterminal and working down.  One kind of
parser for LL(1) grammars is the <em>predictive parser</em>.
The idea is as follows:
<UL>
  <LI> "Build" the parse tree top down (don't actually build it, just
        discover what it would be).
  <LI> Keep track of "work to be done" using a stack of terminals and
       nonterminals; the scanned tokens together with the stack contents
       correspond to the leaves of the current (incomplete) parse tree.
  <LI> Also use a <em>parse table</em> (or selector table) to decide
       how to do the parse.  The rows of the table are indexed by the
       nonterminals of the grammar, and the columns are indexed by the
       tokens (including the special EOF token).  Each element of the
       table for the row indexed by nonterminal X is either empty or
       contains the right-hand side of a grammar rule for X.
</UL>
Here's how the predictive parser works:
<UL>
  <LI> Start by pushing the special "EOF" terminal onto the stack, then
       push the start nonterminal and call the scanner to get the first
       token <em>t</em>.
  <LI> Repeat:
  <UL>
    <LI> If the top-of-stack symbol is a
         <em>nonterminal</em> $X$:
    <UL>
      <LI> Use nonterminal $X$ and the current token t
           to index into the parse table
           to choose a production with $X$ on the left-hand side
	   (the one whose right-hand side is in Table$\left[X\right]$$[\mathrm{t}]$).
      <LI> Pop the $X$ from the stack and push the chosen production's
           right-hand side (push the symbols one at a time, from right
	   to left).
    </UL>
    <LI> If the top-of-stack symbol is a
         <em>terminal</em>, match it with the current
         token.  If it matches, pop it and call the scanner to get the next
         token.
  </UL>
  <LI> until one of the following happens:
    <UL>
      <LI> Top-of-stack is a nonterminal, and the parse table entry is
           empty: reject the input.
      <LI> Top-of-stack is a terminal but doesn't match the current token:
           reject the input.
      <LI> Stack is empty: accept the input.
    </UL>
</OL>
<P>	

<a name=balancedbrackets></a>
Here's a very simple example, using a grammar that defines the language
of balanced parentheses or square brackets, and running the parser on
the input "( [ ] ) EOF".
Note that in the examples on this page we will use sometimes name terminal symbols using 
single characters
(such as: <b>(</b>, <b>)</b>, <b>[</b>, and <b>]</b>) instead
of the token names (<b>lparen</b>, <b>rparen</b>, etc).
Also note that in the picture, the top of stack is to the left.
</p>

<div class=row>
<div class=6u>
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
</div>

<div class=6u>
<table class="parse_table" style="display:inline">
<tr>
<td align="right">Parse Table:</td>
<td align="right"></td>
<td class="parse_table_head" align="center"><b>(</b></td>
<td class="parse_table_head" align="center"><b>)</b></td>
<td class="parse_table_head" align="center"><b>[</b></td>
<td class="parse_table_head" align="center"><b>]</b></td>
<td class="parse_table_head" align="center"><b>EOF</b></td>
</tr>

<tr>
<td align="right"></td>
<td align="right" style="padding-right:10px">$S$</td>
<td align="center" class="parse_table_cell"><b>(</b> $S$ <b>)</b></td>
<td align="center" class="parse_table_cell">$\varepsilon$</td>
<td align="center" class="parse_table_cell"><b>[</b> $S$ <b>]</b></td>
<td align="center" class="parse_table_cell">$\varepsilon$</td>
<td align="center" style="border: solid 1px; padding-left:10px ; padding-right:10px">$\varepsilon$</td>
</tr>
</table>
</div>

</div>

<br />
<br />

<div class=row>
<div class=12u>
<center>
<div text-align=left>
<table style="width:auto; display:inline-block; margin-bottom:50px">
<tr>
<td align="center" style="border-bottom: 1px solid; ">Input seen so far</td>
<td align="center" style="padding-left:10px">&nbsp</td>
<td align="center" style="border-bottom: 1px solid; margin-left:140px ; padding-right:10px">stack</td>
<td align="center" style="padding-left:10px">&nbsp</td>
<td align="center" style="border-bottom: 1px solid; ">Action</td>
</tr>

<!-- row 1 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>(</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$S$ <b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, push <b>(</b> $S$ <b>)</b></td>
</tr>

<!-- row 2 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>(</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px"><b>(</b> $S$ <b>)</b> <b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, scan <!--(top-of-stack term matches curr token)--></td>
</tr>

<!-- row 3 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>([</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$S$ <b>)</b> <b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, push <b>[</b> $S$ <b>]</b></td>
</tr>

<!-- row 4 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>([</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px"><b>[</b> $S$ <b>]</b> <b>)</b> <b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, scan</td>
</tr>

<!-- row 5 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>([]</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$S$ <b>]</b> <b>)</b> <b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, push nothing</td>
</tr>

<!-- row 6 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>([]</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px"><b>]</b> <b>)</b> <b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, scan</td>
</tr>

<!-- row 7 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>([])</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px"><b>)</b> <b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, scan</td>
</tr>

<!-- row 8 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>([]) EOF</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px"><b><tt>EOF</tt></b></td>
<td align="center"></td>
<td align="left">pop, scan</td>
</tr>

<!-- row 9 -->
<tr>
<td align="left" style="padding-right:10px"><tt><b>([]) EOF</b></tt></td>
<td align="center"></td>
<td align="center" style="padding-left:10px ; padding-right:10px"></td>
<td align="center"></td>
<td align="left">empty stack: input accepted</td>
</tr>

</table>
</div>
</div> <!-- 12u column div -->
</center>
</div>

<p>
Remember, it is not always possible to build a predictive parser given a CFG;
only if the CFG is LL(1).  For example, the following grammar is <em>not</em>
LL(1) (but it is LL(2)):
</p>

<table style="width:auto">
<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>(</b> $S$ <b>)</b> | <b>[</b> $S$ <b>]</b> | <b>(</b> <b>)</b>
| <b>[</b> <b>]</b>
</td>
</tr>
</table>

If we try to parse an input that starts with a left paren, we are in trouble!
We don't know whether to choose the first production: $S$ $\longrightarrow$ <b>(</b> $S$ <b>)</b>,
or the third one: $S$ $\longrightarrow$ <b>(</b> <b>)</b>.
If the next token is a right paren, we want to push "<b>(</b><b>)</b>".
If the next token is a left paren, we want to push the three symbols "<b>(</b>$S$<b>)</b>".
So here we need <em>two</em> tokens of look-ahead.
<P>
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
Draw a picture like the one given above to illustrate what the parser
for the grammar:
<UL>
<pre>
S -> epsilon | ( S ) | [ S ]
</pre>
</UL>
does on the input: "[[]]".
<P>
<p><a href="SOLUTIONS/PARSING-ANSWERS.html#ans1">solution</a></p>
<hr>
<P>
<a name=trans></a>
<span class=subhead>Grammar Transformations</span>
<P>
We need to answer two important questions:
<OL>
  <LI> How to tell whether a grammar is LL(1).
  <LI> How to build the parse (or selector) table for a predictive parser,
       given an LL(1) grammar.
</OL>
<P>
It turns out that there is really just one answer: if we build the
parse table and no element of the table contains more than one
grammar rule right-hand side, then the grammar is LL(1).
<P>
Before saying how to build the table we will consider two properties
that <em>preclude</em> a context-free grammar from being LL(1):
<b>left-recursive</b> grammars and grammars that are not <b>left
factored</b>.
We will also consider some transformations that can be applied to
such grammars to make them LL(1).
<P>
First, we will introduce one new definition:
<UL>
  A nonterminal $X$ is useless if either:
  <OL class=style1>
    <LI> You can't derive a sequence that includes $X$, or
    <LI> You can't derive a string from $X$ (where "string" means epsilon
         or a sequence of terminals).
  </OL>
</UL>
Here are some examples of useless nonterminals :
<div class=row style="margin-top:20px">
<div class="-1u 5u" style="border:1px solid">
	<center>
	<p>For case 1:</p>
	<table style="width:auto">

	<tr>
	<td align="right">$S$</td>
	<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
	<td align="left">$A$ $B$</td>
	</tr>

	<tr>
	<td align="right">$A$</td>
	<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
	<td align="left"><b>+</b> | <b>-</b> | $\varepsilon$</td>
	</tr>

	<tr>
	<td align="right">$B$</td>
	<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
	<td align="left"><b>digit</b> | $B$ <b>digit</b></td>
	</tr>

	<tr>
	<td align="right">$C$</td>
	<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
	<td align="left"><b>.</b> $B$</td>
	</tr>
	</table>
	</center>
</div>
	<div class="-1u 5u" style="border:1px solid">
	<center>
	<p>For case 2:</p>
	<table style="width:auto">

	<tr>
	<td align="right">$S$</td>
	<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
	<td align="left">$X$ | $Y$</td>
	</tr>

	<tr>
	<td align="right">$X$</td>
	<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
	<td align="left"><b>(</b> <b>)</b></td>
	</tr>

	<tr>
	<td align="right">$Y$</td>
	<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
	<td align="left"><b>(</b> $Y$ $Y$ <b>)</b></td>
	</tr>
	</table>
	</center>
$Y$ just derives more and more nonterminals, so it is useless.
	</div>
</div>
<!--
<UL>
<li>For case 1:
<table style="width:auto">

<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$A$ $B$</td>
</tr>

<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>+</b> | <b>-</b> | $\varepsilon$</td>
</tr>

<tr>
<td align="right">$B$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>digit</b> | $B$ <b>digit</b></td>
</tr>

<tr>
<td align="right">$C$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>.</b> $B$</td>
</tr>
</table>
$C$ is useless
</li>


<li>For case 2:
<table style="width:auto">

<tr>
<td align="right">$S$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$X$ | $Y$</td>
</tr>

<tr>
<td align="right">$X$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>(</b> <b>)</b></td>
</tr>

<tr>
<td align="right">$Y$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><b>(</b> $Y$ $Y$ <b>)</b></td>
</tr>
</table>
-->

<p>
From now on "context-free grammar" means a grammar without useless nonterms.
</p>

<P>
<a name=leftRec href=#leftRec class=subhead>Left Recursion</a>
<P>
<UL>
  <LI> A grammar $G$ is <b><i>recursive</i></b> in a nonterminal $X$ if $X$ can derive a
       sequence of symbols that includes $X$, in one or more steps:
       $$X \stackrel{+}\Longrightarrow \alpha X \beta$$
			where $\alpha$ and $\beta$ are arbitrary sequences of symbols.
  <LI> $G$ is <b><i>left recursive</i></b> in nonterminal $X$ if $X$ can derive a
       sequence of symbols that <em>starts with</em> $X$, in one or more steps:
       $$X \stackrel{+}\Longrightarrow X \beta$$
			where $\beta$ is an arbitrary sequence of symbols.
  <LI> G is <b><i>immediately left recursive</i></b> in nonterminal $X$ if $X$ can derive
       a sequence of symbols that starts with X in <em>one</em> step:
			 $$X  \Longrightarrow X \beta$$
				i.e., the grammar includes the production: $X \longrightarrow X \beta$.
</UL>
<P>
In general, it is not a problem for a grammar to be recursive.
However, if a grammar is <em>left</em> recursive, it is not LL(1).
Fortunately, we can change a grammar to remove immediate left recursion
without changing the language of the grammar.
Here is how to do the transformation:
<UL>
			 Given two productions of the form: 

<table style="width:auto">
<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$A$ $\alpha$</td>
</tr>


<tr>
<td align="right"></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$|$</td>
<td align="left">$\beta$</td>
</tr>
</table>
       where:
       <UL>
         <LI> $A$ is a nonterminal
	 <LI> $\alpha$ is a sequence of terminals and/or nonterminals
	 <LI> $\beta$ is a sequence of terminals and/or nonterminals
	      <em>not</em> starting with $A$
       </UL>
       Replace those two productions with the following three productions:
<table style="width:auto">
<tr>
<td align="right">$A$</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">
$\beta$ A`</td>
</tr>

<tr>
<td align="right">A`</td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">
$\alpha$ A` | $\varepsilon$</td>
</tr>
</table>
where A` is a new nonterminal.
</UL>

<p>
Using this rule, we create a new grammar from a grammar with immediate
left recursion.
The new grammar is equivalent to the original one; i.e.,
the two grammars derive exactly the same sets of strings, but the new
one is <b>not</b> immediately left recursive (and so has a chance of
being LL(1)).
</p>
<P>
To illustrate why the new grammar is equivalent to the original one,
consider the parse trees that can be built using the original grammar:
</p>
<svg style="display:inline-block" width=80 height=220>
	<foreignObject width=50 height=50 x=44 y=8> $A$ </foreignObject>

	<path 
		d="M50,30 l 0,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<foreignObject width=50 height=50 x=44 y=61> $\beta$ </foreignObject>
</svg>

<svg style="display:inline-block" width=150 height=220>
	<foreignObject width=50 height=50 x=85 y=8> $A$ </foreignObject>

	<path 
		d="M85,30 l -30,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<path 
		d="M98,30 l 30,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<foreignObject width=50 height=50 x=44 y=58> $A$ </foreignObject>
	<foreignObject width=50 height=50 x=128 y=55> $\alpha$ </foreignObject>

	<path 
		d="M50,80 l 0,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<foreignObject width=50 height=50 x=44 y=111> $\beta$ </foreignObject>
</svg>

<svg style="display:inline-block" height=220 width=200>
	<foreignObject width=50 height=50 x=125 y=8> $A$ </foreignObject>

	<path 
		d="M125,30 l -30,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<path 
		d="M138,30 l 30,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<foreignObject width=50 height=50 x=85 y=68> $A$ </foreignObject>
	<foreignObject width=50 height=50 x=169 y=68> $\alpha$ </foreignObject>

	<path 
		d="M85,90 l -30,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<path 
		d="M98,90 l 30,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<foreignObject width=50 height=50 x=44 y=118> $A$ </foreignObject>
	<foreignObject width=50 height=50 x=128 y=115> $\alpha$ </foreignObject>

	<path 
		d="M50,140 l 0,30"
	  style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
	/>

	<foreignObject width=50 height=50 x=44 y=171> $\beta$ </foreignObject>
</svg>
etc.

<p>
Note that the derived strings are:
<uL>
  <LI> $\beta$</li>
  <LI> $\beta$ $\alpha$</li>
  <LI> $\beta$ $\alpha$ $\alpha$</li>
	...
</uL>
</p>

<p>
That is, they are of the form "beta, followed by zero or more alphas".
The new grammar derives the same set of strings, but the parse trees
have a different shape (the single "beta" is derived right away, and
then the zero or more alphas):
</p>
<svg
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.2"
   width="142.78549"
   height="124"
   id="svg2">
  <defs
     id="defs4">
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2933" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2921" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5099" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5055" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5051" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5047" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5043" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5039" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect4861" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect4847" />
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)"
         id="path3665"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         id="path3659"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         id="path3641"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt;marker-start:none" />
    </marker>
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2861" />
  </defs>


	<foreignObject width=50 height=50 x=60 y=0>$A$ </foreignObject>
	<foreignObject width=50 height=50 x=105 y=48>A` </foreignObject>
	<foreignObject width=50 height=50 x=12 y=48>$\beta$ </foreignObject>
	<foreignObject width=50 height=50 x=105 y=100>$\varepsilon$ </foreignObject>

  <g
     transform="translate(-312.26643,-1167.4292)"
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
         id="flowPara2991" /></flowRoot>    <flowRoot
       transform="translate(13.124102,1015.9464)"
       id="flowRoot2837"
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"><flowRegion
         id="flowRegion2839"><rect
           width="106.77312"
           height="82.731491"
           x="-237.58788"
           y="19.875111"
           id="rect2841" /></flowRegion><flowPara
         id="flowPara2843" /></flowRoot>    <path
       d="m 369.23536,1186.5246 -30,30"
       id="path2859"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       d="m 390.80568,1186.5246 30,30"
       id="path2919"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       d="m 423.44209,1239.5246 0,30"
       id="path2931"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
  </g>
</svg>
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="191.28549"
   height="178.5"
   id="svg2"
   version="1.1"
   inkscape:version="0.47 r22583"
   sodipodi:docname="topdown_tree2.svg">
  <defs
     id="defs4">
    <inkscape:path-effect
       is_visible="true"
       id="path-effect2986"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2984"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect2982"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2933"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect2921"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5099"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5055"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5051"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5047"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5043"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect5039"
       effect="spiro" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect4861"
       is_visible="true" />
    <inkscape:path-effect
       is_visible="true"
       id="path-effect4847"
       effect="spiro" />
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3665"
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
         id="path3659"
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
         id="path3641"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt;marker-start:none"
         transform="matrix(-0.8,0,0,-0.8,-10,0)" />
    </marker>
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2861"
       is_visible="true" />
    <inkscape:perspective
       sodipodi:type="inkscape:persp3d"
       inkscape:vp_x="0 : 526.18109 : 1"
       inkscape:vp_y="0 : 1000 : 0"
       inkscape:vp_z="744.09448 : 526.18109 : 1"
       inkscape:persp3d-origin="372.04724 : 350.78739 : 1"
       id="perspective2993" />
    <inkscape:perspective
       id="perspective4253"
       inkscape:persp3d-origin="0.5 : 0.33333333 : 1"
       inkscape:vp_z="1 : 0.5 : 1"
       inkscape:vp_y="0 : 1000 : 0"
       inkscape:vp_x="0 : 0.5 : 1"
       sodipodi:type="inkscape:persp3d" />
  </defs>

	<foreignObject width=50 height=50 x=60 y=0>$A$ </foreignObject>
	<foreignObject width=50 height=50 x=110 y=50>A` </foreignObject>
	<foreignObject width=50 height=50 x=10 y=50>$\beta$ </foreignObject>
	<foreignObject width=50 height=50 x=65 y=100>$\alpha$ </foreignObject>
	<foreignObject width=50 height=50 x=155 y=100>A` </foreignObject>
	<foreignObject width=50 height=50 x=155 y=150>$\varepsilon$ </foreignObject>


  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2"
     inkscape:cx="82.408862"
     inkscape:cy="29.174116"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="1200"
     inkscape:window-height="884"
     inkscape:window-x="181"
     inkscape:window-y="569"
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
     transform="translate(-312.26643,-1167.4292)">
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
         id="flowPara2991" /></flowRoot>    <flowRoot
       xml:space="preserve"
       id="flowRoot2837"
       style="font-size:40px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"
       transform="translate(13.124102,1015.9464)"><flowRegion
         id="flowRegion2839"><rect
           id="rect2841"
           width="106.77312"
           height="82.731491"
           x="-237.58788"
           y="19.875111" /></flowRegion><flowPara
         id="flowPara2843" /></flowRoot>    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 369.23536,1186.5246 -30,30"
       id="path2859"
       inkscape:path-effect="#path-effect2861"
       inkscape:original-d="m 369.23536,1186.5246 -30,30"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 390.80568,1186.5246 30,30"
       inkscape:path-effect="#path-effect2921"
       id="path2919"
       d="m 390.80568,1186.5246 30,30"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 417.73536,1241.0246 -30,30"
       inkscape:path-effect="#path-effect2982"
       id="path2960"
       d="m 417.73536,1241.0246 -30,30"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="m 439.30568,1241.0246 30,30"
       id="path2966"
       inkscape:path-effect="#path-effect2984"
       inkscape:original-d="m 439.30568,1241.0246 30,30"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:original-d="m 471.94209,1294.0246 0,30"
       inkscape:path-effect="#path-effect2986"
       id="path2976"
       d="m 471.94209,1294.0246 0,30"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
  </g>
</svg>
<svg
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.2"
   width="242.28549"
   height="231"
   id="svg2">
  <defs
     id="defs4">
    <inkscape:path-effect
       effect="spiro"
       id="path-effect3035" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect3033" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect3031" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2986" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2984" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2982" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2933" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2921" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5099" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5055" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5051" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5047" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5043" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect5039" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect4861" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect4847" />
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Mend"
       style="overflow:visible">



      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)"
         id="path3665"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         id="path3659"
         style="font-size:12px;fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         id="path3641"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt;marker-start:none" />
    </marker>
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2861" />
  </defs>


	<foreignObject width=50 height=50 x=60 y=0>$A$ </foreignObject>
	<foreignObject width=50 height=50 x=110 y=50>A` </foreignObject>
	<foreignObject width=50 height=50 x=10 y=50>$\beta$ </foreignObject>
	<foreignObject width=50 height=50 x=65 y=100>$\alpha$ </foreignObject>
	<foreignObject width=50 height=50 x=155 y=100>A` </foreignObject>
	<foreignObject width=50 height=50 x=122 y=150>$\alpha$ </foreignObject>
	<foreignObject width=50 height=50 x=205 y=150>A` </foreignObject>
	<foreignObject width=50 height=50 x=205 y=200>$\varepsilon$ </foreignObject>

  <g
     transform="translate(-312.26643,-1167.4292)"
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
         id="flowPara2991" /></flowRoot>    <flowRoot
       transform="translate(13.124102,1015.9464)"
       id="flowRoot2837"
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"><flowRegion
         id="flowRegion2839"><rect
           width="106.77312"
           height="82.731491"
           x="-237.58788"
           y="19.875111"
           id="rect2841" /></flowRegion><flowPara
         id="flowPara2843" /></flowRoot>    <path
       d="m 369.23536,1186.5246 -30,30"
       id="path2859"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <text
       x="379.81039"
       y="1179.7172"
       id="text4841"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="379.81039"
         y="1179.7172"
         id="tspan4849"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">$A$</tspan></text>
    <path
       d="m 390.80568,1186.5246 30,30"
       id="path2919"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <text
       x="335.81039"
       y="1233.7172"
       id="text2923"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="335.81039"
         y="1233.7172"
         id="tspan2925"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">$\beta$</tspan></text>
    <path
       d="m 417.73536,1241.0246 -30,30"
       id="path2960"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <text
       x="428.31039"
       y="1234.2172"
       id="text2962"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="428.31039"
         y="1234.2172"
         id="tspan2964"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">A`</tspan></text>
    <path
       d="m 439.30568,1241.0246 30,30"
       id="path2966"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <text
       x="384.31039"
       y="1288.2172"
       id="text2968"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="384.31039"
         y="1288.2172"
         id="tspan2970"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">$\alpha$</tspan></text>
    <path
       d="m 468.73536,1293.5246 -30,30"
       id="path3009"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <text
       x="479.31039"
       y="1286.7172"
       id="text3011"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="479.31039"
         y="1286.7172"
         id="tspan3013"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">A`</tspan></text>
    <path
       d="m 490.30568,1293.5246 30,30"
       id="path3015"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <text
       x="435.31039"
       y="1340.7172"
       id="text3017"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="435.31039"
         y="1340.7172"
         id="tspan3019"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">$\alpha$</tspan></text>
    <text
       x="522.99164"
       y="1340.7172"
       id="text3021"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="522.99164"
         y="1340.7172"
         id="tspan3023"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">A`</tspan></text>
    <path
       d="m 522.94209,1346.5246 0,30"
       id="path3025"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)" />
    <text
       x="522.99164"
       y="1394.7172"
       id="text3027"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="522.99164"
         y="1394.7172"
         id="tspan3029"
         style="font-size:16px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Dosis;-inkscape-font-specification:Dosis">$\epsilon$</tspan></text>
  </g>
</svg>
etc.

<P>
Consider, for instance, the grammar for arithmetic expressions involving only
subtraction:
</p>
<div>
<center>
<table class=cfg width="auto" style="display:inline;">
<tr>
	<td align=right><i>Exp</i></td> <td>$\longrightarrow$</td> 
	<td><i>Exp</i> <b>minus</b> <i>Factor</i>  |  <i>Factor</i> </td>
</tr>
<tr>
	<td><i>Factor</i></td> <td>$\longrightarrow$</td> 
	<td><b>intliteral</b>  |  <b>(</b> <i>Exp</i> <b>)</b></td>
</tr>
</table>
</center>
</div>
<p>
Notice that the first rule (
	<i>Exp</i>$\longrightarrow$<i>Exp</i> <b>minus</b> <i>Factor</i>) has
immediate left recursion, so this grammar is not LL(1).
(For example, if the first token is <b>intliteral</b>, you don't know
whether to choose the production 
	<i>Exp</i>$\longrightarrow$<i>Exp</i> <b>minus</b> <i>Factor</i>, or
<i>Exp</i>$\longrightarrow$<i>Factor</i>.
If the <em>next</em> token is <b>minus</b>, then you should choose
<i>Exp</i>$\longrightarrow$<i>Exp</i> <b>minus</b> <i>Factor</i>, but if the next token is <b>EOF</b>, then you
should choose 
<i>Exp</i>$\longrightarrow$<i>Factor</i>.
</p>
<P>
Using the transformation defined above, we remove the immediate left
recursion, producing the following new grammar:
</p>
<div>
<center>
<table class=cfg width="auto" style="display:inline;">
<tr>
	<td align=right><i>Exp</i></td> <td>$\longrightarrow$</td> 
	<td><i>Factor</i> <i>Exp`</i></td>
</tr>

<tr>
	<td align=right><i>Exp`</i></td> <td>$\longrightarrow$</td> <td>
	<b>minus</b> <i>Factor</i> <i>Exp`</i>  |  </td>
</tr>

<tr>
	<td><i>Factor</i></td> <td>$\longrightarrow$</td> 
	<td><b>intliteral</b>  |  <b>(</b> <i>Exp</i> <b>)</b></td>
</tr>
</table>
</center>
</div>
<p>
Let's consider what the predictive parser built using this grammar
does when the input starts with an integer:
</p>
<UL>
<LI> The predictive parser starts by pushing <b>EOF</b>, then
		 <i>Exp</i> onto the stack.
       Regardless of what the first token is, there is only one production
			 with <i>Exp</i> on the left-hand side, so it will always pop the
			 <i>Exp</i> from the stack and push <i>Factor</i> <i>Exp`</i> 
			 as its first action.
<LI> Now the top-of-stack symbol is the nonterminal <i>Factor</i>.
			 Since the input is the <b>intliteral</b> token (not the <b>(</b> token)
			 it will pop the <i>Factor</i> and push <b>intliteral</b>.
<LI> The top-of-stack symbol is now a terminal (<b>intliteral</b>), which
       <em>does</em> match the current token, so the stack will be popped,
       and the scanner called to get the next token.
<LI> Now the top-of-stack symbol is nonterminal <i>Exp`</i>.
       We'll consider two cases:
    <OL class=style1>
		<LI> The next token is <b>minus</b>.  In this case, we pop
		<i>Exp`</i> and push <b>minus</b> <i>Factor</i> <i>Exp`</i>.
		<LI> The next token is <b>EOF</b>.  In this case, we pop
		<i>Exp`</i> and push $\varepsilon$ (i.e., push nothing).
    </OL>
</UL>
So with the new grammar, the parser is able to tell (using only one token
look-ahead) what action to perform.
<P>
Unfortunately, there is a major disadvantage of the new grammar, too.
Consider the parse trees for the string <tt>2 - 3 - 4</tt> for
the old and the new grammars:
</p>
<div class=row>
<div class=6u>
	<center>
		<p>Before eliminating Left Recursion:</p>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="228"
   height="205"
	 style="margin-right:75px"
   id="opt_copyprop_big">
  <defs
     id="defs4">
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4100"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         id="path4094"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
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
        <dc:identifier>opt_copyprop_big</dc:identifier>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(100.64582,-986.13978)"
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
       x="30.882959"
       y="997.04938"
       id="text1"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="30.882959"
         y="997.04938"
         id="tspan3105"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Exp</tspan></text>
    <path
       d="M 26.29955,1001.8918 -26.70045,1030.8918"
       id="path3119"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M 52.984119,1001.8918 103.98412,1030.8918"
       id="path4699"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M 39.635976,1001.8918 39.635976,1030.8918"
       id="path4701"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="-45.078983"
       y="1043.0114"
       id="text4703"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-45.078983"
         y="1043.0114"
         id="tspan4705"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Exp</tspan></text>
    <text
       x="22.921017"
       y="1043.0114"
       id="text4707"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="22.921017"
         y="1043.0114"
         id="tspan4709"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">minus</tspan></text>
    <text
       x="92.921021"
       y="1043.0114"
       id="text4711"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="92.921021"
         y="1043.0114"
         id="tspan4713"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Factor</tspan></text>
    <path
       d="M -48.955284,1047.8537 -71.955284,1076.8537"
       id="path4715"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M -22.270715,1047.8537 0.729285,1076.8537"
       id="path4717"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M -35.618858,1047.8537 -35.618858,1076.8537"
       id="path4719"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="-90.333817"
       y="1088.9733"
       id="text4721"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-90.333817"
         y="1088.9733"
         id="tspan4723"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Exp</tspan></text>
    <text
       x="-52.333817"
       y="1088.9733"
       id="text4725"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-52.333817"
         y="1088.9733"
         id="tspan4727"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">minus</tspan></text>
    <text
       x="-6.3338175"
       y="1088.9733"
       id="text4729"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-6.3338175"
         y="1088.9733"
         id="tspan4731"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Factor</tspan></text>
    <path
       d="M -80.166585,1095.937 -80.166585,1124.937"
       id="path4737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="-98.333817"
       y="1139.1779"
       id="text4743"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-98.333817"
         y="1139.1779"
         id="tspan4745"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Factor</tspan></text>
    <path
       d="M -81.580799,1144.0203 -81.580799,1173.0203"
       id="path4747"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="-84.295761"
       y="1187.1398"
       id="text4749"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="-84.295761"
         y="1187.1398"
         id="tspan4751"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">2</tspan></text>
    <path
       d="M 8.928869,1095.2299 8.928869,1124.2299"
       id="path4753"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="6.2139072"
       y="1140.3494"
       id="text4755"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="6.2139072"
         y="1140.3494"
         id="tspan4757"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">3</tspan></text>
    <path
       d="M 108.1837,1046.4395 108.1837,1075.4395"
       id="path4759"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="105.46874"
       y="1091.559"
       id="text4761"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="105.46874"
         y="1091.559"
         id="tspan4763"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">4</tspan></text>
  </g>
</svg>
	</center>
</div>
<div class=6u>
	<center>
		<p>After eliminating Left Recursion:</p>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.2"
   width="315"
   height="210"
   id="opt_copyprop_big">
  <defs
     id="defs4">
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4100"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         id="path4094"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="Arrow2Mend-3"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4100-5"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4832"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4834"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4836"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4838"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4840"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4842"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4844"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4846"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4848"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4850"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4852"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4854"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4856"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4858"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4860"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4862"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4864"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4866"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4868"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4870"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
    </marker>
    <marker
       refX="0"
       refY="0"
       orient="auto"
       id="marker4872"
       style="overflow:visible">
      <path
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         id="path4874"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round" />
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
        <dc:identifier>opt_copyprop_big</dc:identifier>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     transform="translate(-24.647175,-1197.168)"
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
         id="flowPara2991" /></flowRoot>    <path
       d="M 105.59253,1211.548 52.592538,1240.548"
       id="path3119"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M 133.27711,1211.548 184.27711,1240.548"
       id="path4699"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="107.214"
       y="1208.6675"
       id="text4703"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="107.214"
         y="1208.6675"
         id="tspan4705"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Exp</tspan></text>
    <text
       x="122.214"
       y="1305.6676"
       id="text4707"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="122.214"
         y="1305.6676"
         id="tspan4709"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">minus</tspan></text>
    <path
       d="M 262.3377,1308.5099 233.3377,1337.5099"
       id="path4715"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M 287.02228,1310.5099 318.02228,1339.5099"
       id="path4717"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M 273.67413,1361.5099 273.67413,1390.5099"
       id="path4719"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="262.95917"
       y="1302.6295"
       id="text4721"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="262.95917"
         y="1302.6295"
         id="tspan4723"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Exp`</tspan></text>
    <text
       x="215.95917"
       y="1353.6295"
       id="text4725"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="215.95917"
         y="1353.6295"
         id="tspan4727"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">minus</tspan></text>
    <text
       x="189.95917"
       y="1256.6294"
       id="text4729"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="189.95917"
         y="1256.6294"
         id="tspan4731"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Exp`</tspan></text>
    <path
       d="M 329.12641,1361.5099 329.12641,1390.5099"
       id="path4737"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="26.959175"
       y="1254.8341"
       id="text4743"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="26.959175"
         y="1254.8341"
         id="tspan4745"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Factor</tspan></text>
    <path
       d="M 43.712188,1259.6765 43.712188,1288.6765"
       id="path4747"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="40.997215"
       y="1302.796"
       id="text4749"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="40.997215"
         y="1302.796"
         id="tspan4751"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">2</tspan></text>
    <path
       d="M 199.22186,1262.8861 199.22186,1291.8861"
       id="path4753"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="183.95917"
       y="1305.8341"
       id="text4784"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="183.95917"
         y="1305.8341"
         id="tspan4786"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Factor</tspan></text>
    <path
       d="M 200.71219,1310.6765 200.71219,1339.6765"
       id="path4788"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="197.99722"
       y="1353.796"
       id="text4790"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="197.99722"
         y="1353.796"
         id="tspan4792"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">3</tspan></text>
    <path
       d="M 187.3377,1261.5099 144.3377,1290.5099"
       id="path4794"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M 215.02227,1261.5099 258.02227,1290.5099"
       id="path4796"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       d="M 272.80194,1310.8861 272.80194,1339.8861"
       id="path4798"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       x="256.9798"
       y="1353.8341"
       id="text4800"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="256.9798"
         y="1353.8341"
         id="tspan4802"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Factor</tspan></text>
    <text
       x="317.95917"
       y="1354.6295"
       id="text4804"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="317.95917"
         y="1354.6295"
         id="tspan4806"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">Exp`</tspan></text>
    <text
       x="270.13773"
       y="1404.796"
       id="text4808"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="270.13773"
         y="1404.796"
         id="tspan4810"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">4</tspan></text>
  </g>
  <text
     x="300.31317"
     y="206.05888"
     id="text3051"
     xml:space="preserve"
     style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
       x="300.31317"
       y="206.05888"
       id="tspan3053"><tspan
         id="tspan3055"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan></tspan></text>
</svg>
	</center>
</div>
</div>

<p>
The original parse tree shows the underlying structure of the expression;
in particular it groups <tt>2 - 3</tt> in one subtree to reflect the fact
that subtraction is left associative.
The parse tree for the new grammar is a mess!
Its subtrees don't correspond to the sub-expressions of <tt>2 - 3 - 4</tt>
at all!
Fortunately, we can design a predictive parser to create an abstract-syntax
tree that <em>does</em> correctly reflect the structure of the parsed
code even though the grammar's parse trees do not.
<P>
Note that the rule for removing immediate left recursion given above
only handled a somewhat restricted case, where there was only one
left-recursive production.
Here's a more general rule for removing immediate left recursion:
<UL>
   <LI> For every set of productions of the form:
$$A \longrightarrow A \alpha_1 \; | \; A \alpha_2 \; | \ldots | \; A \alpha_m \; | \; \beta_1 \; | \ldots | \; \beta_n$$
<!--
       <UL>
       <tt>A -> A alpha<sub>1</sub>  |  A alpha<sub>2</sub>  | .. |  A alpha<sub>m</sub>  |  beta<sub>1</sub>  | .. |  beta<sub>n</sub></tt>
       </UL>
-->
  <LI> Replace them with the following productions:
$$A \longrightarrow \beta_1 A` \; | \; \beta_2 A` \; | \; \ldots \; | \; \beta_n A`$$
$$A` \longrightarrow \alpha_1 A` | \ldots | \alpha_m A` | \varepsilon$$
<!--
       <UL>
       <tt>A  -> beta<sub>1</sub> A`  |  beta<sub>2</sub>  A`  | .. |  beta<sub>n</sub>  A`</tt><BR>
       <tt>A` -> alpha<sub>1</sub> A`  | .. |  alpha<sub>m</sub> A`  |  epsilon</tt>
-->
       </UL>
</UL>
<P>
Note also that there are rules for removing <em>non-immediate</em>
left recursion; for example, you can read about how to do that in the compiler
textbook by Aho, Sethi & Ullman, on page 177.
However, we will not discuss that issue here.
<P>
<a name=leftFact href=#leftFact class=subhead>Left Factoring</a>
<P>
A second property that precludes a grammar from being LL(1) is if
it is not <b>left factored</b>, i.e., if a nonterminal has two productions
whose right-hand sides have a common prefix.
For example, the following grammar is not left factored:
<div>
$Exp$ $\longrightarrow$ <b>(</b> $Exp$ <b>)</b> | <b>(</b>&nbsp&nbsp<b>)</b>
</div>
In this example, the common prefix is "<b>(</b>".
<P>
This problem is solved by left-factoring, as follows:
<UL>
  <LI> Given a pair of productions of the form:
<div>
     $A$ $\longrightarrow$ $\alpha$ $\beta_1$ | $\alpha$ $\beta_2$
</div>
<!--
     <UL><tt> A -> alpha beta<sub>1</sub>  | alpha beta<sub>2</sub></tt></UL>
-->
     where $\alpha$ is a sequence of terminals and/or nonterminals, and
     $\beta_1$ and $beta_2$ are sequences
     of terminals and/or nonterminals that do not have a common prefix (and
     one of the betas could be epsilon),
  <P>
  <LI> Replace those two production with:
<div>
$A$ $\longrightarrow$ $\alpha$ $A`$
<br />
A` $\longrightarrow$ $\beta_1$ | $\beta_2$
</div>
<!--
     <UL><tt> A -> alpha A`</tt><BR>
         <tt> A` -> beta<sub>1</sub> | beta<sub>2</sub></tt>
     </UL>
-->
</UL>
<p>
For example, consider the following productions:
	$Exp$ $\longrightarrow$ <b>(</b> $Exp$ <b>)</b>  |  <b>(</b>&nbsp&nbsp<b>)</b>
</p>
<!--<tt> exp -> ( exp ) | ( )</tt><BR>-->
<p>
Using the rule defined above, they are replaced by:
</p>
<div>
$Exp$ $\longrightarrow$ <b>(</b> $Exp`$<BR>
$Exp`$ $\longrightarrow$ $Exp$ <b>)</b>  |  <b>)</b>
</div>
<!--
<UL><tt> exp  -> ( exp'<BR>
         exp' -> exp ) | )</tt>
</UL>
-->
<p>
Here's the more general algorithm for left factoring (when there may be
more than two productions with a common prefix):
</p>
<UL>
  For each nonterminal $A$:
       <UL>
       <LI> Find the longest non-empty prefix $\alpha$ that is common
            to 2 or more production right-hand sides.
       <LI> Replace the productions:
<table style="width:auto; margin-left:20px">
<tr>
	<td>$A$</td>
	<td>$\longrightarrow$</td>
	<td>$\alpha$ $\beta_1$  | $\ldots$ | $\alpha$ $\beta_m$ | y<sub>1</sub>  | .. | y<sub>n</sub></td>
	</td>
</tr>
</table>
	    with:
<table style="width:auto; margin-left:20px">
<tr>
	<td>$A$</td> 
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td>$\alpha$ A` &nbsp | &nbsp $y_1$ &nbsp | &nbsp $\ldots$ &nbsp | &nbsp $y_n$</td>
	</td>
</tr>
<tr>
	<td>A`</td>
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td>
	<td>$\beta_1$ | &nbsp $\ldots$ &nbsp | &nbsp $\beta_m$</td>
</tr>
</table>
<p>
  Repeat this process until no nonterminal has two productions with a
  common prefix.
</p>
</UL>

<!--
<UL>
  For each nonterminal <tt>A</tt>:
       <UL>
       <LI> Find the longest non-empty prefix <tt>alpha</tt> that is common
            to 2 or more production right-hand sides.
       <LI> Replace the productions:
            <UL><tt> A -> alpha beta<sub>1</sub>  | .. | alpha beta<sub>m</sub>  | y<sub>1</sub>  | .. | y<sub>n</sub></tt></UL>
	    with:
	    <UL><tt> A -> alpha A` | y<sub>1</sub>  | .. | y<sub>n</sub><BR>
	    	     A` -> beta<sub>1</sub>  | .. | beta<sub>m</sub></tt>
            </UL>
       </UL>
  Repeat this process until no nonterminal has two productions with a
  common prefix.
</UL>
-->
<P>
Note that this transformation (like the one for removing immediate left
recursion) has the disadvantage of making the grammar much harder to
understand.
However, it is necessary if you need an LL(1) grammar.
<P>
Here's an example that demonstrates both left-factoring and immediate
left-recursion removal:
<UL>
  <LI> The original grammar is:

<table style="width:auto; margin-left:20px">
<tr>
	<td>$Exp$</td> 
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td><b>(</b> $Exp$ <b>)</b>  |  $Exp$ $Exp$  |  <b>(</b>&nbsp&nbsp<b>)</b></td>
</tr>
</table>
  <LI> After removing immediate left-recursion, the grammar becomes:
<table style="width:auto; margin-left:20px">
<tr>
	<td>$Exp$</td> 
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td><b>(</b> $Exp$ <b>)</b> $Exp`$  |  <b>(</b>&nbsp&nbsp<b>)</b> $Exp`$
	</td>
</tr>
<tr>
	<td>$Exp`$</td> 
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td>$Exp$ $Exp`$  |  $\varepsilon$
	</td>
</tr>
</table>

  <LI> After left-factoring, this <em>new</em> grammar becomes:
<table style="width:auto; margin-left:20px">
<tr>
	<td>$Exp$</td> 
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td><b>(</b> $Exp`$</td>
</tr>
<tr>
	<td>$Exp`$</td> 
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td>$Exp$ <b>)</b> $Exp`$ | <b>)</b> $Exp`$ 
</tr>
<tr>
	<td>$Exp`$</td> 
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td>$Exp$ $Exp`$ | $\varepsilon$</td>
</tr>
</table>
</UL>
<P>
<p><a href="SOLUTIONS/PARSING-ANSWERS.html#ans2">solution</a></p>
<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Using the same grammar: <tt> exp -> ( exp ) | exp exp | ( )</tt>,
do left factoring first, then remove immediate left recursion.
<hr>

<P>
<a name=firstFollow></a>
<span class=subhead>FIRST and FOLLOW Sets</subhead>
<P>
Recall: A predictive parser can only be built for an LL(1) grammar. 
A grammar is <em>not</em> LL(1) if it is:
<ul>
  <LI> Left recursive, or
  <LI> not left factored.
</ul>
However, grammars that are <em>not</em> left recursive and <em>are</em>
left factored may still not be LL(1).
As mentioned earlier, to see if a grammar is LL(1), we try building
the parse table for the predictive parser.
If any element in the table contains more than one grammar rule
right-hand side, then the grammar is not LL(1).
<P>
To build the table, we must must compute <b>FIRST</b> and
<b>FOLLOW</b> sets for the grammar.
<P>
<a name=first></a>
<span class=subsubhead>FIRST Sets</span>
<P>
Ultimately, we want to define FIRST sets for the right-hand sides
of each of the grammar's productions.
To do that, we define FIRST sets for arbitrary sequences of terminals
and/or nonterminals, or $\varepsilon$ (since that's what can be on the right-hand
side of a grammar production).
The idea is that for sequence $\alpha$, FIRST($\alpha$) is the set of
terminals that begin the
strings derivable from $\alpha$, and also, if $\alpha$ can derive $\varepsilon$,
then $\varepsilon$ is in FIRST($\alpha$).
Using derivation notation:
<!--$$FIRST(\alpha) \left\{ t \middle| t\right\}$$-->
$$\mathrm{FIRST}(\alpha) = \left\{ \; t \; \middle| \; 
\left(
t \; \in \Sigma 
\wedge \alpha \stackrel{*}\Longrightarrow t \beta
\right)
\vee 
	\left( t = \varepsilon 
	\wedge \alpha \stackrel{*}\Longrightarrow \varepsilon \right) 
	\right\}
$$

To define FIRST($\alpha$) for arbitrary $\alpha$, we start by defining FIRST(X),
for a <em>single</em> symbol X (a terminal, a nonterminal, or $\varepsilon$):
<OL class=style1>
  <LI> X $\in \Sigma$ (e.g. X is a terminal): FIRST(X) = {X}
  <LI> X $= \varepsilon$: FIRST(X) = {$\varepsilon$}
  <LI> X $\in N$ (e.g. X is a nonterminal): we must look at all grammar
       productions with X on the left, i.e., productions of the form:
<div>
			X $\longrightarrow$ Y<sub>1</sub> Y<sub>2</sub> Y<sub>3</sub> ... Y<sub>k</sub>
</div>
       where each Y<sub>k</sub> is a single terminal or nonterminal (or 
       there is just one Y, and it is $\varepsilon$).
       For each such production, we perform the following actions:
       <UL><LI> Put FIRST(Y<sub>1</sub>) - {$\varepsilon$} into FIRST(X).
           <LI> If $\varepsilon$ is in FIRST(Y<sub>1</sub>),
	        then put FIRST(Y<sub>2</sub>) - {epsilon}
		into FIRST(X).
	   <LI> If epsilon is in FIRST(Y<sub>2</sub>),
	        then put FIRST(Y<sub>3</sub>) - {$\varepsilon$}
		into FIRST(X).
	   <LI> etc...
	   <LI> If $\varepsilon$ is in FIRST(Y<sub>i</sub>)
	        for 1 $\leq$ i $\leq$ k (all production
	        right-hand sides)) then put $\varepsilon$ into FIRST(X).
       </UL>
</OL>
<P>
For example, consider computing FIRST sets for each of the nonterminals
in the following grammar:
<div class=row>
<div class=12u>
<center>

<table align=center style="width:auto">
<tr>
	<td><i>Exp</i></td>
	<td>$\longrightarrow$</td>
	<td><i>Term</i> <i>Exp`</i></td>
</tr>

<tr>
	<td><i>Exp`</i></td>
	<td>$\longrightarrow$</td>
	<td><b>minus</b> <i>Term</i> <i>Exp`</i>  |  $\varepsilon$</td>
</tr>

<tr>
	<td><i>Term</i></td>
	<td>$\longrightarrow$</td>
	<td><i>Factor</i> <i>Term'</i></td>
</tr>

<tr>
	<td><i>Term'</i></td>
	<td>$\longrightarrow$</td>
	<td><b>divide</b> <i>Factor</i> <i>Term'</i>  |  $\varepsilon$</td>
</tr>

<tr>
	<td><i>Factor</i></td>
	<td>$\longrightarrow$</td>
	<td><b>intlit</b>  |  <b>lparens</b> <i>Exp</i> <b>rparens</b></td>
</tr>

</table>
</center>
</div>
</div>
<p>
Here are the FIRST sets (starting with nonterminal factor and working up,
since we need to know FIRST(factor) to compute FIRST(term), and we need to
know FIRST(term) to compute FIRST(exp)):
</p>
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
   width="470"
   height="255"
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
     transform="translate(-81.959286,-320.13555)"
     id="layer1">
    <text
       x="184.22443"
       y="335.38443"
       id="text2985"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="184.22443"
         y="335.38443"
         id="tspan2987"
         style="font-size:14px">FIRST(<tspan
   id="tspan3111"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Factor</tspan>) = { <tspan
   id="tspan2993"
   style="font-size:14px;font-weight:bold">intliteral</tspan>, <tspan
   id="tspan2991"
   style="font-size:14px;font-weight:bold">(</tspan>} </tspan><tspan
         x="184.22443"
         y="353.02933"
         id="tspan2995"
         style="font-size:14px">FIRST(<tspan
   id="tspan3116"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Term'</tspan>) = { <tspan
   id="tspan3003"
   style="font-size:14px;font-weight:bold">divide, </tspan><tspan
   id="tspan3159"
   style="font-size:18px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan>}</tspan><tspan
         x="184.22443"
         y="377.63568"
         id="tspan2997"
         style="font-size:14px">FIRST(<tspan
   id="tspan3120"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Term</tspan>) = { <tspan
   id="tspan3001"
   style="font-size:14px;font-weight:bold">intliteral</tspan>, <tspan
   id="tspan2999"
   style="font-size:14px;font-weight:bold">(</tspan>}</tspan><tspan
         x="184.22443"
         y="395.28058"
         id="tspan3005"
         style="font-size:14px">FIRST(<tspan
   id="tspan3124"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Exp`</tspan>) = { 
	 <tspan style="font-weight:bold">
	 minus
	 </tspan>
	 , <tspan
   id="tspan3151"
   style="font-size:18px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan>}</tspan><tspan
         x="184.22443"
         y="419.88693"
         id="tspan3007"
         style="font-size:14px">FIRST(<tspan
   id="tspan3128"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic">Exp</tspan>) = { <tspan
   id="tspan3009"
   style="font-size:14px;font-weight:bold">intliteral<tspan
   id="tspan3011"
   style="font-size:14px;font-weight:normal">,</tspan> (</tspan> }</tspan></text>
    <text
       x="83.105286"
       y="478.95438"
       id="text3015"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="83.105286"
         y="478.95438"
         id="tspan3017"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">Note that FIRST(<tspan
   id="tspan3079"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Term</tspan>) </tspan><tspan
         x="83.105286"
         y="499.11707"
         id="tspan3023"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">includes FIRST(<tspan
   id="tspan3083"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Factor</tspan>).</tspan><tspan
         x="83.105286"
         y="519.27979"
         id="tspan3019"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">Since FIRST(<tspan
   id="tspan3087"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Factor</tspan>) </tspan><tspan
         x="83.105286"
         y="537.46649"
         id="tspan3025"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">does not include <tspan
   id="tspan3091"
   style="font-size:18px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan>, that's </tspan><tspan
         x="83.105286"
         y="562.78705"
         id="tspan3021"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">all that is in FIRST(<tspan
   id="tspan3103"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Term</tspan>)</tspan></text>
    <path
       d="M 147.94977,460.01237 C 103.39454,425.35924 133.7661,391.33941 176.63174,374.61329"
       id="path3027"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 171.25358,373.34785 176.63174,373.98057 174.73356,379.99145"
       id="path3029"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <text
       x="340.81216"
       y="486.58685"
       id="text3031"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="340.81216"
         y="486.58685"
         id="tspan3033"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">Note that FIRST(<tspan
   id="tspan3055"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Bold Italic">Exp</tspan>) </tspan><tspan
         x="340.81216"
         y="506.74954"
         id="tspan3035"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">includes FIRST(<tspan
   id="tspan3057"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Term</tspan>).</tspan><tspan
         x="340.81216"
         y="526.91223"
         id="tspan3037"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">Since FIRST(<tspan
   id="tspan3065"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Term</tspan>) </tspan><tspan
         x="340.81216"
         y="545.09894"
         id="tspan3039"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">does not include <tspan
   id="tspan3075"
   style="font-size:18px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan>, that's </tspan><tspan
         x="340.81216"
         y="570.41956"
         id="tspan3041"
         style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">all that is in FIRST(<tspan
   id="tspan3053"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Bold Italic">Exp</tspan>)</tspan></text>
    <path
       d="M 412.34862,468.79128 C 406.37419,436.98732 220.31206,461.24958 222.64967,425.84057"
       id="path3107"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       d="M 220.17563,433.47305 221.75486,426.73537 230.75618,429.84057"
       id="path3109"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
</center>
</div>
</div>
<P>
Once we have computed FIRST(X) for each terminal and nonterminal X,
we can compute FIRST($\alpha$) for every
production's right-hand-side $\alpha$.
In general, $\alpha$ will be of the form:
<UL><tt>X<sub>1</sub> X<sub>2</sub> ... X<sub>n</sub></tt></UL>
where each X is a single terminal or nonterminal, or there is just one
X and it is $\varepsilon$.
The rules for computing FIRST($\alpha$) are essentially the same as the rules for
computing the first set of a nonterminal:
<OL class=style1>
<LI> Put FIRST(X<sub>1</sub>) - {$\varepsilon$} into FIRST($\alpha$)
	<LI> If $\varepsilon$ is in FIRST(X<sub>1</sub>) put FIRST(X<sub>2</sub>)
		into FIRST($\alpha$).
  <LI> etc...
	<LI> If $\varepsilon$ is in the FIRST set for every X<sub>k</sub>, put $\varepsilon$	into FIRST($\alpha$).
</OL>
<p>
For the example grammar above, here are the FIRST sets for each production
right-hand side:
</p>
<div class=row style="margin-bottom:30px; margin-top:30px">
<div class=12u>
<center>
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   version="1.1"
   width="300"
   height="130"
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
     transform="translate(-157.23959,-339.67919)"
     id="layer1">
    <text
       x="159.80365"
       y="353.88135"
       id="text2985"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="159.80365"
         y="353.88135"
         id="tspan2987"
         style="font-size:14px;text-align:start;text-anchor:start">FIRST(<tspan
   id="tspan3111"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Term Exp<tspan
   id="tspan3267"
   style="font-style:normal;text-align:start;text-anchor:start">'</tspan></tspan>)</tspan><tspan
         x="159.80365"
         y="371.52625"
         id="tspan2995"
         style="font-size:14px;text-align:start;text-anchor:start">FIRST(<tspan
   id="tspan3194"
   style="font-weight:bold;text-align:start;text-anchor:start">minus</tspan><tspan
   id="tspan3116"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;font-family:LMRoman10;-inkscape-font-specification:LMRoman10"> Term Exp<tspan
   id="tspan3263"
   style="font-style:normal;text-align:start;text-anchor:start">'</tspan></tspan>)</tspan><tspan
         x="159.80365"
         y="389.17117"
         id="tspan2997"
         style="font-size:14px;text-align:start;text-anchor:start">FIRST(<tspan
   id="tspan3120"
   style="font-size:18px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan>)</tspan><tspan
         x="159.80365"
         y="413.7775"
         id="tspan3005"
         style="font-size:14px;text-align:start;text-anchor:start">FIRST(<tspan
   id="tspan3124"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;font-family:LMRoman10;-inkscape-font-specification:LMRoman10">Factor Term<tspan
   id="tspan3259"
   style="font-style:normal;text-align:start;text-anchor:start">'</tspan></tspan>)</tspan><tspan
         x="159.80365"
         y="431.42239"
         id="tspan3007"
         style="font-size:14px;text-align:start;text-anchor:start">FIRST(<tspan
   id="tspan3249"
   style="font-weight:bold;text-align:start;text-anchor:start">divide</tspan><tspan
   id="tspan3128"
   style="font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"> Factor Term<tspan
   id="tspan3255"
   style="font-style:normal;text-align:start;text-anchor:start">'</tspan></tspan>)</tspan><tspan
         x="159.80365"
         y="449.06732"
         id="tspan3271"
         style="font-size:14px;text-align:start;text-anchor:start">FIRST(<tspan
   id="tspan3273"
   style="font-weight:bold;text-align:start;text-anchor:start">intliteral</tspan>)</tspan><tspan
         x="159.80365"
         y="466.6272"
         id="tspan3277"
         style="font-size:14px;text-align:start;text-anchor:start">FIRST( <tspan
   id="tspan3279"
   style="font-weight:bold;text-align:start;text-anchor:start">( </tspan><tspan
   id="tspan3283"
   style="font-style:italic;font-weight:normal;text-align:start;text-anchor:start">Exp</tspan><tspan
   id="tspan3287"
   style="font-weight:bold;text-align:start;text-anchor:start"> )</tspan> )</tspan></text>
    <text
       x="361.39609"
       y="354.17535"
       id="text3335"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="361.39609"
         y="354.17535"
         id="tspan3337"
         style="font-size:14px">{ <tspan
   id="tspan3343"
   style="font-size:14px;font-weight:bold">intliteral</tspan>, <tspan
   id="tspan3345"
   style="font-size:14px;font-weight:bold">(</tspan>} </tspan><tspan
         x="361.39609"
         y="371.67535"
         id="tspan3347"
         style="font-size:14px">{ <tspan
   id="tspan3355"
   style="font-size:14px;font-weight:bold">minus </tspan>}</tspan><tspan
         x="361.39609"
         y="389.17535"
         id="tspan3357"
         style="font-size:14px">{ <tspan
   id="tspan3361"
   style="font-size:18px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan> }</tspan><tspan
         x="361.39609"
         y="413.78171"
         id="tspan3363"
         style="font-size:14px">{ <tspan
   id="tspan3369"
   style="font-weight:bold">intliteral<tspan
   id="tspan3371"
   style="font-weight:normal">, </tspan>( </tspan>} </tspan><tspan
         x="361.39609"
         y="431.28171"
         id="tspan3373"
         style="font-size:14px">{ <tspan
   id="tspan3381"
   style="font-size:14px;font-weight:bold">divide </tspan>} </tspan><tspan
         x="361.39609"
         y="448.78171"
         id="tspan3383"
         style="font-size:14px">{ <tspan
   id="tspan3387"
   style="font-weight:bold">intliteral </tspan>}</tspan><tspan
         x="361.39609"
         y="466.28171"
         id="tspan3389"
         style="font-size:14px">{ <tspan
   id="tspan3397"
   style="font-weight:bold">(</tspan> }</tspan></text>
    <text
       x="339.1875"
       y="353.70935"
       id="text3399"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"><tspan
         x="339.1875"
         y="353.70935"
         id="tspan3401"
         style="font-size:14px">=</tspan><tspan
         x="339.1875"
         y="371.20935"
         id="tspan3411"
         style="font-size:14px">=</tspan><tspan
         x="339.1875"
         y="388.70935"
         id="tspan3421"
         style="font-size:14px">= <tspan
   id="tspan3425"
   style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:none;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math">epsilon</tspan></tspan><tspan
         x="339.1875"
         y="413.3157"
         id="tspan3427"
         style="font-size:14px">=</tspan><tspan
         x="339.1875"
         y="430.8157"
         id="tspan3437"
         style="font-size:14px">=  </tspan><tspan
         x="339.1875"
         y="448.3157"
         id="tspan3447"
         style="font-size:14px">=</tspan><tspan
         x="339.1875"
         y="465.8157"
         id="tspan3453"
         style="font-size:14px">=</tspan></text>
  </g>
</svg>
</center>
</div>
</div>
<P>
Why do we care about the FIRST($\alpha$) sets?
During parsing, suppose that there are two productions:
<center>
<table align=center style="width:auto">
<tr>
<td style="padding-right:30px">Production 1:</td>
<td>$A$</td>
<td>$\longrightarrow$</td>
<td>$\alpha$</td>
</tr>
<tr>
<td style="padding-right:30px">Production 2:</td>
<td>$A$</td>
<td>$\longrightarrow$</td>
<td>$\beta$</td>
</tr>
</table>
</center>
Consider the situation when the top-of-stack symbol is <i>A</i> and the current token is <b>a</b>.
If <span class=term>a</span> $\in$ FIRST($\alpha$), choose production 1: pop,
push $\alpha$.
If, on the other hand, <span class=term>a</span> $\in$ FIRST($\beta$), choose production 2 :
pop, push $\beta$.
We haven't yet given the rules for using FIRST and FOLLOW sets
to determine whether a grammar is LL(1);
however, you might be able to guess based on this discussion, that if
<span class=term>a</span> is in <em>both</em> FIRST($\alpha$) and FIRST($\beta$), the grammar is
<em>not</em> LL(1).
<P>
<a name=follow href=#follow class=subsubhead>FOLLOW Sets</a>
<P>
FOLLOW sets are only defined for <em>single nonterminals</em>.
The definition is as follows:
<UL>
For a nonterminal $A$, FOLLOW($A$) is the set of terminals
that can appear <em>immediately</em> to the right of <i>A</i> in some
partial derivation; i.e., terminal <b>t</b> is in FOLLOW(<i>A</i>) if
$
S \stackrel{+}\Longrightarrow\ldots A \; \mathrm{t} \ldots
$
where t is a terminal. If $A$ can be the <em>rightmost</em> symbol in a derivation,
then EOF is in FOLLOW($A$).
</UL>
It is worth noting that $\varepsilon$ is <em>never</em> in a FOLLOW set.
<P>
Using notation:

$$\mathrm{FOLLOW}(A) = \left\{ \; \mathrm{t} \; \middle| \; 
\left(
\mathrm{t} \; \in \Sigma 
\wedge S \stackrel{+}\Longrightarrow \alpha \; A \; \mathrm{t} \; \beta
\right)
\vee 
	\left( \mathrm{t} = \mathrm{EOF}
	\wedge S \stackrel{*}\Longrightarrow \alpha A \right) 
	\right\}
$$
<p>
Here are some pictures illustrating the conditions under which symbols
a, c, and EOF are in the FOLLOW set of nonterminal A:
</p>
<div class="row">
	<div class="4u">
<center>
		<p>Figure 1</p>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="135"
   height="210"
   id="opt_copyprop_big"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="topdown_factoredparse.svg">
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
         id="path4100"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path4094"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend-3"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4100-5"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4832"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4834"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4836"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4838"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4840"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4842"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4844"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4846"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4848"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4850"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4852"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4854"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4856"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4858"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4860"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4862"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4864"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4866"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4868"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4870"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4872"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4874"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1"
     inkscape:cx="48.793187"
     inkscape:cy="48.234567"
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
        <dc:identifier>opt_copyprop_big</dc:identifier>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-42.073396,-1196.5089)">
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
         id="flowPara2991" /></flowRoot>    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 107.59253,1209.548 70.592538,1238.548"
       id="path3119"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="109.214"
       y="1206.6675"
       id="text4703"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="109.214"
         y="1206.6675"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan4705">S</tspan></text>
    <flowRoot
       xml:space="preserve"
       id="flowRoot5139"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       transform="translate(24.647175,1197.168)"><flowRegion
         id="flowRegion5141"><rect
           id="rect5143"
           width="75.660423"
           height="18.384777"
           x="26.870058"
           y="-47.386868" /></flowRegion><flowPara
         id="flowPara5145">,,...</flowPara></flowRoot>    <text
       sodipodi:linespacing="125%"
       id="text4784"
       y="1307.8341"
       x="91.959167"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"
       inkscape:label="#text3099"><tspan
         id="tspan4786"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         y="1307.8341"
         x="91.959167"
         sodipodi:role="line">A</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 109.92409,1265.5099 98.743902,1294.5099"
       id="path4794"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="-1232.7845"
       y="113.0899"
       id="text5147"
       sodipodi:linespacing="125%"
       transform="matrix(0,-1,1,0,0,0)"><tspan
         sodipodi:role="line"
         id="tspan5149"
         x="-1232.7845"
         y="113.0899">...</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5151"
       y="1258.0898"
       x="106.3879"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="1258.0898"
         x="106.3879"
         id="tspan5153"
         sodipodi:role="line">...</tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5155"
       d="M 118.7439,1265.5099 129.92409,1294.5099"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="48.463406"
       y="1258.0898"
       id="text5157"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5159"
         x="48.463406"
         y="1258.0898">...</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5161"
       y="1258.0898"
       x="160.89339"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="1258.0898"
         x="160.89339"
         id="tspan5163"
         sodipodi:role="line">...</tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5165"
       d="M 120.05611,1209.548 157.0561,1238.548"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="122.89339"
       y="1308.0898"
       id="text5201"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5203"
         x="122.89339"
         y="1308.0898">...</tspan></text>
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="107.95917"
       y="1355.8341"
       id="text5205"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="107.95917"
         y="1355.8341"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5207">a</tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5209"
       d="M 125.92409,1313.5099 114.7439,1342.5099"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 134.7439,1313.5099 145.92409,1342.5099"
       id="path5211"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 130.71219,1314.6765 130.71219,1343.6765"
       id="path5213"
       inkscape:connector-curvature="0" />
    <text
       sodipodi:linespacing="125%"
       id="text5250"
       y="1355.8341"
       x="127.95917"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"
       inkscape:label="#text3099"><tspan
         id="tspan5252"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         y="1355.8341"
         x="127.95917"
         sodipodi:role="line">b</tspan></text>
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="145.95917"
       y="1355.8341"
       id="text5254"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="145.95917"
         y="1355.8341"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5256">c</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 79.147175,1367.168 C 65.421879,1351.3021 77.247205,1354.7565 103.14718,1355.168"
       id="path5258"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="81.567146"
       y="1382.168"
       id="text5260"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5262"
         x="86.092537"
         y="1382.168"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080"><tspan
           style="font-size:14px;font-weight:bold;text-align:center;text-anchor:middle;fill:#800080"
           id="tspan5268">a</tspan><tspan
           style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
           id="tspan5270"> is in </tspan></tspan><tspan
         sodipodi:role="line"
         x="81.567146"
         y="1402.5209"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080"
         id="tspan5272">FOLLOW(<tspan
   style="font-size:14px;font-style:italic;text-align:center;text-anchor:middle;fill:#800080"
   id="tspan5264">A</tspan>)</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 101.24694,1353.0189 103.19148,1354.875 101.33533,1357.3499"
       id="path5917"
       inkscape:connector-curvature="0" />
  </g>
</svg>
		</center>
	</div>
	<div class="4u">
		<center>
		<p>Figure 2</p>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="135.23579"
   height="202.69627"
   id="opt_copyprop_big"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="topdown_follow2.svg">
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
         id="path4100"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path4094"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend-3"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4100-5"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4832"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4834"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4836"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4838"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4840"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4842"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4844"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4846"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4848"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4850"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4852"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4854"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4856"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4858"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4860"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4862"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4864"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4866"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4868"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4870"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4872"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4874"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
  </defs>

				 <text x=55 y=160 style="font-family:Latin Modern Math">&#x03b5</text>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.2744293"
     inkscape:cx="8.1586434"
     inkscape:cy="94.506056"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="1200"
     inkscape:window-height="724"
     inkscape:window-x="0"
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
        <dc:title />
        <dc:identifier>opt_copyprop_big</dc:identifier>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-49.813406,-1198.4835)">
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
         id="flowPara2991" /></flowRoot>    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 107.59253,1209.548 70.592538,1238.548"
       id="path3119"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="112.21156"
       y="1206.6675"
       id="text4703"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="112.21156"
         y="1206.6675"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan4705">S</tspan></text>
    <flowRoot
       xml:space="preserve"
       id="flowRoot5139"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       transform="translate(24.647175,1197.168)"><flowRegion
         id="flowRegion5141"><rect
           id="rect5143"
           width="75.660423"
           height="18.384777"
           x="26.870058"
           y="-47.386868" /></flowRegion><flowPara
         id="flowPara5145">,,...</flowPara></flowRoot>    <text
       sodipodi:linespacing="125%"
       id="text4784"
       y="1260.3367"
       x="89.314507"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"
       inkscape:label="#text3099"><tspan
         id="tspan4786"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         y="1260.3367"
         x="89.314507"
         sodipodi:role="line">X</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 87.126886,1267.5099 75.946698,1296.5099"
       id="path4794"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5155"
       d="M 95.9467,1267.5099 107.12689,1296.5099"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="48.463406"
       y="1258.0898"
       id="text5157"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5159"
         x="48.463406"
         y="1258.0898">...</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5161"
       y="1258.0898"
       x="160.89339"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="1258.0898"
         x="160.89339"
         id="tspan5163"
         sodipodi:role="line">...</tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5165"
       d="M 120.05611,1209.548 157.0561,1238.548"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="124.2486"
       y="1310.337"
       id="text5205"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="124.2486"
         y="1310.337"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5207">c</tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5209"
       d="M 142.21351,1268.0129 131.03332,1297.0129"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 151.03332,1268.0129 162.21351,1297.0129"
       id="path5211"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 108.92404,1312.2378 108.92404,1341.2378"
       id="path5213"
       inkscape:connector-curvature="0" />
    <text
       sodipodi:linespacing="125%"
       id="text5250"
       y="1310.337"
       x="142.98491"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"
       inkscape:label="#text3099"><tspan
         id="tspan5252"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         y="1310.337"
         x="142.98491"
         sodipodi:role="line">d</tspan></text>
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="162.2486"
       y="1310.337"
       id="text5254"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="162.2486"
         y="1310.337"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5256">e</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 131.85716,1362.1744 C 118.13186,1346.3085 151.04118,1331.4531 130.33443,1316.329"
       id="path5258"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="148.70302"
       y="1378.8389"
       id="text5260"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5262"
         x="153.22841"
         y="1378.8389"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080"><tspan
           style="font-size:14px;font-weight:bold;text-align:center;text-anchor:middle;fill:#800080"
           id="tspan5268">c</tspan><tspan
           style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
           id="tspan5270"> is in </tspan></tspan><tspan
         sodipodi:role="line"
         x="148.70302"
         y="1399.1918"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080"
         id="tspan5272">FOLLOW(<tspan
   style="font-size:14px;font-style:italic;text-align:center;text-anchor:middle;fill:#800080"
   id="tspan5264">A</tspan>)</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 129.22163,1318.4925 129.87597,1315.8852 132.94457,1316.2778"
       id="path5917"
       inkscape:connector-curvature="0" />
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="143.31451"
       y="1258.3367"
       id="text5938"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="143.31451"
         y="1258.3367"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5940">Y</tspan></text>
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="68.454857"
       y="1309.1271"
       id="text5942"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="68.454857"
         y="1309.1271"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5944">A</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5946"
       y="1309.1271"
       x="105.55714"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"
       inkscape:label="#text3099"><tspan
         id="tspan5948"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         y="1309.1271"
         x="105.55714"
         sodipodi:role="line">B</tspan></text>
    <flowRoot
       xml:space="preserve"
       id="flowRoot2989"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math"
       transform="matrix(1.7617364,0,0,1.7617364,-369.32464,527.96485)"><flowRegion
         id="flowRegion2991"><rect
           id="rect2993"
           width="102"
           height="116"
           x="269"
           y="461.36218"
           style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Latin Modern Math;-inkscape-font-specification:Latin Modern Math" /></flowRegion><flowPara
         id="flowPara2995"
         style="font-size:11.92005825px;font-weight:bold">epsilon</flowPara></flowRoot>    <path
       inkscape:connector-curvature="0"
       id="path3135"
       d="M 146.65329,1268.0699 146.65329,1297.0699"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
  </g>
</svg>
		</center>
	</div>
	<div class="4u">
		<center>
		<p>Figure 2</p>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="135.23579"
   height="202.69627"
   id="opt_copyprop_big"
   version="1.1"
   inkscape:version="0.47 r22583"
   sodipodi:docname="topdown_follow2.svg">
  <defs
     id="defs4">
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2895"
       is_visible="true" />
    <inkscape:perspective
       sodipodi:type="inkscape:persp3d"
       inkscape:vp_x="0 : 101.34814 : 1"
       inkscape:vp_y="0 : 1000 : 0"
       inkscape:vp_z="135.23579 : 101.34814 : 1"
       inkscape:persp3d-origin="67.617897 : 67.565425 : 1"
       id="perspective83" />
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path4100"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path4094"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend-3"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4100-5"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4832"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4834"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4836"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4838"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4840"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4842"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4844"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4846"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4848"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4850"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4852"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4854"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4856"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4858"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4860"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4862"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4864"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4866"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4868"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4870"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4872"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4874"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.6046304"
     inkscape:cx="-4.512378"
     inkscape:cy="111.89373"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="1200"
     inkscape:window-height="724"
     inkscape:window-x="0"
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
        <dc:identifier>opt_copyprop_big</dc:identifier>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-49.813406,-1198.4835)">
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
         id="flowPara2991" /></flowRoot>    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="M 107.59253,1209.548 70.592538,1238.548"
       id="path3119"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="112.21156"
       y="1206.6675"
       id="text4703"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="112.21156"
         y="1206.6675"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan4705">S</tspan></text>
    <flowRoot
       xml:space="preserve"
       id="flowRoot5139"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       transform="translate(24.647175,1197.168)"><flowRegion
         id="flowRegion5141"><rect
           id="rect5143"
           width="75.660423"
           height="18.384777"
           x="26.870058"
           y="-47.386868" /></flowRegion><flowPara
         id="flowPara5145">,,...</flowPara></flowRoot>    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="50.463406"
       y="1256.4618"
       id="text5157"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5159"
         x="50.463406"
         y="1256.4618">...</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5161"
       y="1300.4618"
       x="153.18861"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="1300.4618"
         x="153.18861"
         id="tspan5163"
         sodipodi:role="line">...</tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5165"
       d="M 120.05611,1209.548 157.0561,1238.548"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="m 159.92726,1261.2346 0,29"
       id="path5213"
       inkscape:connector-curvature="0" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="m 112.32703,1311.1712 c -34.139266,-24.4972 2.62603,-36.4914 35.10276,-50.0461"
       id="path5258"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="107.11578"
       y="1327.8357"
       id="text5260"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5262"
         x="111.6448"
         y="1327.8357"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080"><tspan
           style="font-size:14px;font-weight:bold;text-align:center;text-anchor:middle;fill:#800080"
           id="tspan5268">EOF</tspan><tspan
           style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
           id="tspan5270"> is in </tspan></tspan><tspan
         sodipodi:role="line"
         x="107.11578"
         y="1348.1886"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080"
         id="tspan5272">FOLLOW(<tspan
   style="font-size:14px;font-style:italic;text-align:center;text-anchor:middle;fill:#800080"
   id="tspan5264">A</tspan>)</tspan></text>
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="155.45808"
       y="1258.1239"
       id="text5942"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="155.45808"
         y="1258.1239"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5944">A</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="107.18861"
       y="1256.4618"
       id="text2889"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2891"
         x="107.18861"
         y="1256.4618">...</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="m 94.184414,61.90514 3.814538,0.208066 -1.179039,4.300025"
       id="path2893"
       inkscape:path-effect="#path-effect2895"
       inkscape:original-d="m 94.184414,61.90514 3.814538,0.208066 -1.179039,4.300025"
       transform="translate(49.813406,1198.4835)" />
  </g>
</svg>
		</center>
	</div>
</div>

How to compute FOLLOW(A) for each nonterminal A:
<UL>
  <LI> If A is the start nonterminal, put EOF in FOLLOW(A) (like S in Fig. 3).
  <LI> Now find the productions with A on the right-hand-side:
    <UL>
    <LI> For each production <tt>$X$ $\longrightarrow$ $\alpha$ $A$ $\beta$</tt>,
         put FIRST($\beta$) - {$\varepsilon$} in FOLLOW($A$) -- see Fig. 1.
    <LI> If epsilon is in FIRST($\beta$) then put FOLLOW($X$) into
	 FOLLOW(A) -- see Fig. 2.
    <LI> For each production <tt>$X$ $\longrightarrow$ $\alpha$ $A$</tt>, put FOLLOW(X) into
	 FOLLOW(A) -- see Fig. 3, and Fig. 4 below:
<div class=row" style="padding-top:15px">
	<div class=12u>
		<center>
		<p>Figure 4</p>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="325"
   height="202.69627"
   id="opt_copyprop_big"
   version="1.1"
   inkscape:version="0.47 r22583"
   sodipodi:docname="topdown_follow4.svg">
  <defs
     id="defs4">
    <inkscape:path-effect
       effect="spiro"
       id="path-effect3006"
       is_visible="true" />
    <inkscape:path-effect
       effect="spiro"
       id="path-effect2895"
       is_visible="true" />
    <inkscape:perspective
       sodipodi:type="inkscape:persp3d"
       inkscape:vp_x="0 : 101.34814 : 1"
       inkscape:vp_y="0 : 1000 : 0"
       inkscape:vp_z="135.23579 : 101.34814 : 1"
       inkscape:persp3d-origin="67.617897 : 67.565425 : 1"
       id="perspective83" />
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path4100"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path4094"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend-3"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4100-5"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4832"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4834"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4836"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4838"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4840"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4842"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4844"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4846"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4848"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4850"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4852"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4854"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4856"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4858"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4860"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4862"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4864"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4866"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4868"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4870"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="marker4872"
       style="overflow:visible">
      <path
         inkscape:connector-curvature="0"
         id="path4874"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 c -1.7454984,2.3720609 -1.7354408,5.6174519 -6e-7,8.035443 z"
         transform="scale(-0.6,-0.6)" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1"
     inkscape:cx="-10.001575"
     inkscape:cy="125.45178"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="1200"
     inkscape:window-height="724"
     inkscape:window-x="0"
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
        <dc:identifier>opt_copyprop_big</dc:identifier>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-49.813406,-1198.4835)">
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
         id="flowPara2991" /></flowRoot>    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="m 210.59253,1209.548 -36.99999,29"
       id="path3119"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="215.21158"
       y="1206.6675"
       id="text4703"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="215.21158"
         y="1206.6675"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan4705">S</tspan></text>
    <flowRoot
       xml:space="preserve"
       id="flowRoot5139"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       transform="translate(24.647175,1197.168)"><flowRegion
         id="flowRegion5141"><rect
           id="rect5143"
           width="75.660423"
           height="18.384777"
           x="26.870058"
           y="-47.386868" /></flowRegion><flowPara
         id="flowPara5145">,,...</flowPara></flowRoot>    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="153.46341"
       y="1256.4618"
       id="text5157"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5159"
         x="153.46341"
         y="1256.4618">...</tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path5165"
       d="m 223.05611,1209.548 36.99999,29"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       d="m 221.80903,1249.2346 15.30097,29"
       id="path5213"
       inkscape:connector-curvature="0" />
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="213.33984"
       y="1246.1239"
       id="text5942"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="213.33984"
         y="1246.1239"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan5944">X</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="258.4455"
       y="1256.4618"
       id="text2889"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2891"
         x="258.4455"
         y="1256.4618">...</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="m 225.16346,1244.664 -2.21478,-3.1126 4.09747,-1.7581"
       id="path2893"
       inkscape:path-effect="#path-effect2895"
       inkscape:original-d="m 225.16346,1244.664 -2.21478,-3.1126 4.09747,-1.7581" />
    <text
       sodipodi:linespacing="125%"
       id="text2986"
       y="1289.5137"
       x="236.58987"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"
       inkscape:label="#text3099"><tspan
         id="tspan2988"
         style="font-size:12px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         y="1289.5137"
         x="236.58987"
         sodipodi:role="line">A</tspan></text>
    <text
       inkscape:label="#text3099"
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#1a1a1a;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="190.83984"
       y="1289.0137"
       id="text2994"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="190.83984"
         y="1289.0137"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;text-anchor:start;fill:#1a1a1a;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         id="tspan2996">α</tspan></text>
    <path
       inkscape:connector-curvature="0"
       id="path2998"
       d="m 211.23652,1249.2346 -15.30097,29"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-mid:none;marker-end:url(#Arrow2Mend)"
       sodipodi:nodetypes="cc" />
    <text
       sodipodi:linespacing="125%"
       id="text3000"
       y="-214.96179"
       x="1218.9835"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"
       transform="matrix(0,1,-1,0,0,0)"><tspan
         y="-214.96179"
         x="1218.9835"
         id="tspan3002"
         sodipodi:role="line">...</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="m 265.31341,1271.1798 c -3.67115,1.0213 -7.63155,0.9793 -11.2802,-0.1197 -3.64865,-1.099 -6.97343,-3.2513 -9.4698,-6.1303 -2.21747,-2.5573 -3.755,-5.6193 -5.29293,-8.6346 -1.53793,-3.0152 -3.1268,-6.0584 -5.42258,-8.5457 -2.76816,-2.9991 -6.52209,-5.0729 -10.53449,-5.8197"
       id="path3004"
       inkscape:path-effect="#path-effect3006"
       inkscape:original-d="m 265.31341,1271.1798 c 0,0 -16.67989,-2.4908 -20.75,-6.25 -4.07011,-3.7593 -6.08137,-12.0569 -10.71551,-17.1803 -4.63415,-5.1235 -10.53449,-5.8197 -10.53449,-5.8197"
       sodipodi:nodetypes="czsz" />
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Vera Sans"
       x="267.56342"
       y="1273.4298"
       id="text3008"><tspan
         sodipodi:role="line"
         id="tspan3010"
         x="267.56342"
         y="1273.4298"
         style="font-size:12px;fill:#800080"><tspan
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Indie Flower;-inkscape-font-specification:Indie Flower"
   id="tspan3014">whatever follows</tspan> X</tspan><tspan
         sodipodi:role="line"
         x="267.56342"
         y="1290.4109"
         id="tspan3012"
         style="font-size:12px;fill:#800080"><tspan
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Indie Flower;-inkscape-font-specification:Indie Flower"
   id="tspan3016">will also follow</tspan> A</tspan></text>
  </g>
</svg>
		</center>
	</div>
</div>
    </UL>
</UL>
<P>
It is worth noting that:
<UL>
  <LI> To compute FIRST(A) you must look for A on a production's
       <b>left-hand</b> side.
  <LI> To compute FOLLOW(A) you must look for A on a production's
       <b>right-hand</b> side.
  <LI> FIRST and FOLLOW sets are always sets of <b>terminals</b>
       (plus, perhaps, $\varepsilon$ for FIRST sets, and EOF for follow sets).
       Nonterminals are <em>never</em> in a FIRST or a FOLLOW set;
       $\varepsilon$ is <em>never</em> in a FOLLOW set.
</UL>
<P>
Here's an example of FOLLOW sets (and the FIRST sets we need to
compute them).  In this example, nonterminals are upper-case letters, and
terminals are lower-case letters.
<table style="width:auto">
<tr>
	<td>$S$</td>
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td>$B$ <b>c</b>  |  $D$ $B$</td>
</tr>

<tr>
	<td>$B$</td>
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td><b>a</b> <b>b</b>  |  <b>c</b> $S$</td>
</tr>

<tr>
	<td>$D$</td>
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td><b>d</b>  |  $\varepsilon$</td>
</tr>

</table>
<table class=cfg>
<tr>
<td style="border-bottom:1px solid">$\alpha$</td>
<td style="padding-left:10px"></td>
<td style="border-bottom:1px solid; padding-right:20px">FIRST($\alpha$)</td>
<td style="padding-left:10px"></td>
<td style="border-bottom:1px solid; padding-right:20px">FOLLOW($\alpha$)</td>
<td></td>
</tr>

<tr>
<td>$D$</td>
<td></td>
<td>{<b>d</b>, $\varepsilon$}</td>
<td></td>
<td>{<b>a</b>, <b>c</b>}</td>
<td></td>
</tr>

<tr>
<td>$B$</td>
<td></td>
<td>{<b>a</b>, <b>c</b>}</td>
<td></td>
<td>{<b>c, <b>EOF</b>}</td>
<td></td>
</tr>

<tr>
<td>$S$</td>
<td></td>
<td>{<b>a</b>, <b>c</b>, <b>d</b>}</td>
<td></td>
<td>{<b>EOF</b>, <b>c</b>}</td>
<td>Note that FOLLOW($S$) always includes <b>EOF</b></td>
</tr>
</table>

<P>
Now let's consider why we care about FOLLOW sets:
<UL>
  <LI> Suppose, during parsing, we have some $X$ at the top-of-stack, and
       <b>a</b> is the current token.
  <LI> We need to replace $X$ on the stack with the right-hand side of
       a production $X \longrightarrow \alpha$. What if $X$ has
       an additional production $X \longrightarrow \beta$.
       Which one should we use?
  <LI> We've already said that if <b>a</b> is in FIRST($\alpha$), but
			 not in FIRST($\beta$), then we want to choose 
			$X \longrightarrow \alpha$</tt>.
  <LI> But what if <b>a</b> is not in FIRST($\alpha$) or FIRST($\beta$)?  
       If $\alpha$ or $\beta$ can derive $\varepsilon$, and <b>a</b> is in FOLLOW($X$),
       then we still have hope that the input will be accepted:
       If $\alpha$ can derive $\varepsilon$ (i.e.,
			 $\varepsilon \in$ FIRST($\alpha$), then we want to choose 
			 $X \longrightarrow \alpha$
       (and similarly if $\beta$ can derive $\varepsilon$).
       The idea is that since $\alpha$ can derive $\varepsilon$, it will
       eventually be popped from the stack, and if we're lucky, the
       next symbol down (the one that was under the $X$) will be
       <b>a</b>.
</UL>
<P>
<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
Here are five grammar productions for (simplified) method headers:
<UL>
<pre>
1. methodHeader -> VOID ID LPAREN paramList RPAREN

2. paramList    -> epsilon 
3. paramList    -> nonEmptyParamList

4. nonEmptyParamList -> ID ID
5. nonEmptyParamList -> ID ID COMMA nonEmptyParamList

</pre>
</UL>
<b>Question 1:</b>
Compute the FIRST and FOLLOW sets for the three nonterminals,
and the FIRST sets for each production right-hand side.
<P>
<b>Question 2:</b>
Draw a picture to illustrate what the predictive parser will do, given
the input sequence of
tokens: "VOID ID LPAREN RPAREN EOF".
Include an explanation of how the FIRST and FOLLOW sets
are used when there is a nonterminal on the top-of-stack that has
more than one production.
<P>
<p><a href="SOLUTIONS/PARSING-ANSWERS.html#ans3">solution</a></p>


<hr>
</p>
<a name=tables></a>
<span class=subhead>How to Build Parse Tables</span>
<p>
Recall that the form of the parse table is:
</p>
<div class=row>
<div class=12u>
<center>
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="490"
   height="338"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.2 r9819"
   sodipodi:docname="New document 1">
  <defs
     id="defs4" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="0.70710678"
     inkscape:cx="128.78848"
     inkscape:cy="191.95131"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="957"
     inkscape:window-height="704"
     inkscape:window-x="465"
     inkscape:window-y="128"
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
     transform="translate(94.916504,-785.77429)">
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect2985"
       width="62.034023"
       height="62.034023"
       x="4.9829893"
       y="987.34521" />
    <rect
       y="987.34521"
       x="68.982986"
       height="62.034023"
       width="62.034023"
       id="rect3755"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3757"
       width="62.034023"
       height="62.034023"
       x="132.98299"
       y="987.34521" />
    <rect
       y="923.34515"
       x="4.9829893"
       height="62.034023"
       width="62.034023"
       id="rect3759"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3761"
       width="62.034023"
       height="62.034023"
       x="68.982986"
       y="923.34515" />
    <rect
       y="923.34515"
       x="132.98299"
       height="62.034023"
       width="62.034023"
       id="rect3763"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3765"
       width="62.034023"
       height="62.034023"
       x="4.9829893"
       y="859.34515" />
    <rect
       y="859.34515"
       x="68.982986"
       height="62.034023"
       width="62.034023"
       id="rect3767"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3769"
       width="62.034023"
       height="62.034023"
       x="132.98299"
       y="859.34515" />
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="29"
       y="847.36218"
       id="text3771"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3773"
         x="29"
         y="847.36218"
         style="font-size:18px">a</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3775"
       y="847.36218"
       x="93"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px"
         y="847.36218"
         x="93"
         id="tspan3777"
         sodipodi:role="line">b</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="159"
       y="847.36218"
       id="text3779"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3781"
         x="159"
         y="847.36218"
         style="font-size:18px">c</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#800080;fill-opacity:1;stroke:none;font-family:Indie Flower"
       x="207"
       y="800.36218"
       id="text3783"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3785"
         x="207"
         y="800.36218"
         style="font-size:14px;fill:#800080;fill-opacity:1;-inkscape-font-specification:Indie+Flower;font-family:Indie+Flower">column: current token</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3787"
       y="1101.3622"
       x="-23.541504"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#800080;fill-opacity:1;stroke:none;font-family:Indie Flower"
       xml:space="preserve"><tspan
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;-inkscape-font-specification:Purisa;font-family:Indie Flower"
         y="1101.3622"
         x="-21.312988"
         id="tspan3789"
         sodipodi:role="line">row: nonterminal at </tspan><tspan
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;-inkscape-font-specification:Purisa;font-family:Indie Flower"
         y="1118.8622"
         x="-23.541504"
         sodipodi:role="line"
         id="tspan3801">top of stack</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#800080;fill-opacity:1;stroke:none;font-family:Indie Flower"
       x="313.96143"
       y="930.36218"
       id="text3791"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="316.18994"
         y="930.36218"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;-inkscape-font-specification:Purisa;font-family:Indie Flower"
         id="tspan3795">cell: production </tspan><tspan
         sodipodi:role="line"
         x="313.96143"
         y="947.86218"
         style="font-size:14px;text-align:center;text-anchor:middle;fill:#800080;fill-opacity:1;-inkscape-font-specification:Purisa;font-family:Indie Flower"
         id="tspan3799">right-hand-side to push</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3803"
       y="896.92322"
       x="-21"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px"
         y="896.92322"
         x="-21"
         id="tspan3805"
         sodipodi:role="line">X</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="-21"
       y="960.92322"
       id="text3807"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3809"
         x="-21"
         y="960.92322"
         style="font-size:18px">Y</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3811"
       y="1024.9232"
       x="-21"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px"
         y="1024.9232"
         x="-21"
         id="tspan3813"
         sodipodi:role="line">Z</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:2;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke-miterlimit:4;stroke-dasharray:none"
       d="m -25,1087.3622 c -10.228006,-12.3103 12.204731,-13.9041 11,-31"
       id="path3815"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#800080;stroke-width:2;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke-miterlimit:4;stroke-dasharray:none"
       d="m 286.48916,808.88522 c -24.87497,23.1976 -37.0611,27.56255 -71.77134,31.1127"
       id="path3850"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#800080;stroke-width:2;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;stroke-miterlimit:4;stroke-dasharray:none"
       d="m 309,954.36218 c -51.8706,47.22822 -88.73244,81.52722 -136,7"
       id="path3852"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
  </g>
</svg>
</center>
</div>
</div>
<p>
Table entry[$X$,<b>a</b>] is either empty (if having $X$ on top of stack and having
<b>a</b> as the current token means a syntax error) or contains
the right-hand side of
a production whose left-hand-side nonterminal is $X$ -- that right-hand
side is what should be pushed.
<P>
To build the table, we fill in the rows one at a time for each
nonterminal $X$ as follows:
<UL> for each production $X \longrightarrow \alpha$</tt>:
     <UL> for each terminal <b>t</b> in FIRST($\alpha$):
          <UL>put $\alpha$ in Table[$X$,<b>t</b>]
	  </UL>
          <BR>
	  if $\epsilon \in$ FIRST($\alpha$) then:
	  <UL>for each terminal <b>t</b> in FOLLOW($X$):
	      <UL> put $\alpha$ in Table[$X$,<b>t</b>]
	      </UL>
          </UL>
     </UL>
</UL>
<P>
The grammar is not LL(1) iff there is more
than one entry for any cell in the table.
<P>
<div class=row>
	<div class=6u>
		Let's try building a parse table for the following grammar:
	</div>
	<div class=6u>
		<table style="width:auto">
<tr>
	<td>$S$</td>
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td>$B$ <b>c</b>  |  $D$ $B$</td>
</tr>

<tr>
	<td>$B$</td>
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td><b>a</b> <b>b</b>  |  <b>c</b> $S$</td>
</tr>

<tr>
	<td>$D$</td>
	<td style="padding-left:10px; padding-right:10px">$\longrightarrow$</td> 
	<td><b>d</b>  |  $\varepsilon$</td>
</tr>
</table>
	</div>
</div> <!-- end row -->

<div class=row>
	<div class=6u>
	First we calculate the FIRST and FOLLOW sets:
	</div>
	<div class=6u>
		<table class=cfg style="margin-top:0px; padding-top:0px; margin-left:0px; padding-left: 0px">
	<tr>
		<td style="border-bottom:1px solid">$\alpha$</td>
		<td style="padding-left:10px"></td>
		<td style="border-bottom:1px solid; padding-right:20px">FIRST($\alpha$)</td>
		<td style="padding-left:10px"></td>
		<td style="border-bottom:1px solid; padding-right:20px">FOLLOW($\alpha$)</td>
		<td></td>
	</tr>

	<tr>
		<td>$D$</td>
		<td></td>
		<td>{<b>d</b>, $\varepsilon$}</td>
		<td></td>
		<td>{<b>a</b>, <b>c</b>}</td>
		<td></td>
	</tr>

	<tr>
		<td>$B$</td>
		<td></td>
		<td>{<b>a</b>, <b>c</b>}</td>
		<td></td>
		<td>{<b>c, <b>EOF</b>}</td>
		<td></td>
	</tr>

	<tr>
		<td>$S$</td>
		<td></td>
		<td>{<b>a</b>, <b>c</b>, <b>d</b>}</td>
		<td></td>
		<td>{<b>EOF</b>, <b>c</b>}</td>
		<td></td>
	</tr>

	<tr>
		<td>$B$ <b>c</b></td>
		<td></td>
		<td>{<b>a</b>, <b>c</b>}</td>
		<td></td>
		<td>-</td>
		<td></td>
	</tr>

<tr>
<td>$D$ $B$</td>
<td></td>
<td>{<b>d</b>, <b>a</b>, <b>c</b>}</td>
<td></td>
<td>-</td>
<td></td>
</tr>

<tr>
<td><b>a</b> <b>b</b></td>
<td></td>
<td>{<b>a</b>}</td>
<td></td>
<td>-</td>
<td></td>
</tr>

<tr>
<td><b>c</b> $S$</td>
<td></td>
<td>{<b>c</b>}</td>
<td></td>
<td>-</td>
<td></td>
</tr>

<tr>
<td><b>d</b></td>
<td></td>
<td>{<b>d</b>}</td>
<td></td>
<td>-</td>
<td></td>
</tr>

<tr>
<td>$\varepsilon$</td>
<td></td>
<td>{$\varepsilon$}</td>
<td></td>
<td>-</td>
<td></td>
</tr>

</table>
	</div>
</div>

<div class=row>
	<div class=6u>
		Then we use those sets to start filling in the parse table:
	</div>

	<div class=6u>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="340"
   height="220"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.2 r9819"
   sodipodi:docname="topdown_parsetable2.svg">
  <defs
     id="defs4" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.0872962"
     inkscape:cx="182.80531"
     inkscape:cy="108.34642"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="957"
     inkscape:window-height="704"
     inkscape:window-x="465"
     inkscape:window-y="128"
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
     transform="translate(20.894531,-830.37924)">
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect2985"
       width="62.034023"
       height="62.034023"
       x="6.9829893"
       y="985.34521" />
    <rect
       y="985.34521"
       x="68.982986"
       height="62.034023"
       width="62.034023"
       id="rect3755"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3757"
       width="62.034023"
       height="62.034023"
       x="130.98299"
       y="985.34521" />
    <rect
       y="923.34515"
       x="6.9829893"
       height="62.034023"
       width="62.034023"
       id="rect3759"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3761"
       width="62.034023"
       height="62.034023"
       x="68.982986"
       y="923.34515" />
    <rect
       y="923.34515"
       x="130.98299"
       height="62.034023"
       width="62.034023"
       id="rect3763"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3765"
       width="62.034023"
       height="62.034023"
       x="6.9829893"
       y="861.34515" />
    <rect
       y="861.34515"
       x="68.982986"
       height="62.034023"
       width="62.034023"
       id="rect3767"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3769"
       width="62.034023"
       height="62.034023"
       x="130.98299"
       y="861.34515" />
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="31"
       y="849.36218"
       id="text3771"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3773"
         x="31"
         y="849.36218"
         style="font-size:18px;font-weight:bold">a</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3775"
       y="849.36218"
       x="93"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-weight:bold"
         y="849.36218"
         x="93"
         id="tspan3777"
         sodipodi:role="line">b</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="157"
       y="849.36218"
       id="text3779"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3781"
         x="157"
         y="849.36218"
         style="font-size:18px;font-weight:bold">c</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3803"
       y="898.92322"
       x="-19"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-style:italic"
         y="898.92322"
         x="-19"
         id="tspan3805"
         sodipodi:role="line">S</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="-19"
       y="960.92322"
       id="text3807"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3809"
         x="-19"
         y="960.92322"
         style="font-size:18px;font-style:italic">B</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3811"
       y="1022.9232"
       x="-19"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-style:italic"
         y="1022.9232"
         x="-19"
         id="tspan3813"
         sodipodi:role="line">D</tspan></text>
    <rect
       y="985.34521"
       x="192.98285"
       height="62.034023"
       width="62.034023"
       id="rect3873"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3875"
       width="62.034023"
       height="62.034023"
       x="192.98285"
       y="923.34515" />
    <rect
       y="861.34515"
       x="192.98285"
       height="62.034023"
       width="62.034023"
       id="rect3877"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text3879"
       y="849.36218"
       x="218.99986"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-weight:bold"
         y="849.36218"
         x="218.99986"
         id="tspan3881"
         sodipodi:role="line">d</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3883"
       width="62.034023"
       height="62.034023"
       x="254.98285"
       y="985.34521" />
    <rect
       y="923.34515"
       x="254.98285"
       height="62.034023"
       width="62.034023"
       id="rect3885"
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       id="rect3887"
       width="62.034023"
       height="62.034023"
       x="254.98285"
       y="861.34515" />
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="272.99985"
       y="849.36218"
       id="text3889"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3891"
         x="272.99985"
         y="849.36218"
         style="font-size:18px;font-weight:bold">EOF</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="19"
       y="884.92322"
       id="text3912"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3914"
         x="19"
         y="884.92322"
         style="font-size:18px;font-style:italic">B</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3916"
       y="885.36218"
       x="47"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-weight:bold"
         y="885.36218"
         x="47"
         id="tspan3918"
         sodipodi:role="line">c</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="19"
       y="910.92322"
       id="text3920"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3922"
         x="19"
         y="910.92322"
         style="font-size:18px;font-style:italic">D</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3924"
       y="910.92322"
       x="47"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-style:italic"
         y="910.92322"
         x="47"
         id="tspan3926"
         sodipodi:role="line">B</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3928"
       y="884.92322"
       x="141"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-style:italic"
         y="884.92322"
         x="141"
         id="tspan3930"
         sodipodi:role="line">B</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="169"
       y="885.36218"
       id="text3932"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3934"
         x="169"
         y="885.36218"
         style="font-size:18px;font-weight:bold">c</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3936"
       y="910.92322"
       x="141"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-style:italic"
         y="910.92322"
         x="141"
         id="tspan3938"
         sodipodi:role="line">D</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="169"
       y="910.92322"
       id="text3940"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3942"
         x="169"
         y="910.92322"
         style="font-size:18px;font-style:italic">B</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="203"
       y="882.92322"
       id="text3944"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3946"
         x="203"
         y="882.92322"
         style="font-size:18px;font-style:italic">D</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3948"
       y="882.92322"
       x="231"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:18px;font-style:italic"
         y="882.92322"
         x="231"
         id="tspan3950"
         sodipodi:role="line">B</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       x="31.549036"
       y="1023.547"
       id="text3956"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3958"
         x="31.549036"
         y="1023.547"
         style="font-size:16px">epsilon</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3960"
       y="1023.547"
       x="155.54903"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
       xml:space="preserve"><tspan
         style="font-size:16px"
         y="1023.547"
         x="155.54903"
         id="tspan3962"
         sodipodi:role="line">epsilon</tspan></text>
  </g>
</svg>
	</div>
</div> <!-- End row -->
</UL>
Not all entries have been filled in,
but already we can see that this grammar is not LL(1) since there are
two entries in table[S,a] and in table[S,c].
<P>
Here's how we filled in this much of the table:
<OL class=style1>
  <LI> First, we considered the production <tt>S -> B c</tt>.
       FIRST(<tt>Bc</tt>) = { a, c }, so we put the production's
       right-hand side (<tt>B c</tt>) in Table[S, a] and in
       Table[S, c].
       FIRST(<tt>Bc</tt>) does not include epsilon, so we're done
       with that production.
  <LI> Next, we considered the production <tt>S -> D B </tt>.
       FIRST(<tt>DB</tt>) = { d, a, c }, so we put the production's
       right-hand side (<tt>D B</tt>) in Table[S, d], Table[S, a], and
       Table[S, c].
  <LI> Next, we considered the production <tt>D -> epsilon</tt>.
       FIRST(epsilon) = { epsilon }, so we must look at FOLLOW(D).
       FOLLOW(D) = { a, c }, so we put the production's right-hand side
       (epsilon) in Table[D, a] and Table[D, c}.
</OL>
<P>
<hr>
<a name=youtry4>
<P>
<center>
<u><b>TEST YOURSELF #4</b></u>
</center></a>
<P>
Finish filling in the parse table given above.
<P>
<p><a href="SOLUTIONS/PARSING-ANSWERS.html#ans4">solution</a></p>
<hr>
<P>
<a name=code></a>
<span class=subhead>How to Code a Predictive Parser</span>
<P>
Now, suppose we actually want to code a predictive parser for a grammar
that <em>is</em> LL(1).
The simplest idea is to use a table-driven parser with an explicit stack.
Here's pseudo-code for a table-driven predictive parser:
<pre>
   Stack.push(EOF);
   Stack.push(start-nonterminal);
   currToken = scan();

   while (! Stack.empty()) {
     topOfStack = Stack.pop();
     if (isNonTerm(topOfStack)) {
       // top of stack symbol is a nonterminal
       p = table[topOfStack, currToken];
       if (p is empty) report syntax error and quit
       else {
         // p is a production's right-hand side
	 push p, one symbol at a time, from right to left
       }
     }
     else {
       // top of stack symbol is a terminal
       if (topOfStack == currToken) currToken = scan();
       else report syntax error and quit
     }
   }
</pre>

<P>
<hr>
<a name=youtry5>
<P>
<center>
<u><b>TEST YOURSELF #5</b></u>
</center></a>
<P>
Here is a CFG (with rule numbers):
<pre>
S -> epsilon               (1)
  |  X Y Z                 (2)

X -> epsilon               (3)
  |  X S                   (4)

Y -> epsilon               (5)
  |  a Y b                 (6)

Z -> c Z                   (7)
  |  d                     (8)
</pre>

<P>
<b>Question 1(a)</b>: Compute the First and Follow Sets for each nonterminal.

<P>
<b>Question 1(b)</b>: Draw the Parse Table.
<P>
<p><a href="SOLUTIONS/PARSING-ANSWERS.html#ans5">solution</a></p>

<hr>
</body></html>
